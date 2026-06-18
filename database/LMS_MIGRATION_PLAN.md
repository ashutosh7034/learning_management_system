# TCET Specialization Tracker → LMS — Migration Plan

> Generated during Phase 1. This is the contract for the incremental refactor.
> **Principle: reuse the AdminLTE theme, `DBController`, the DB-driven menu engine,
> the auth flow, and `role_*_base.php` CRUD. Refactor incrementally; do not rewrite working modules.**

---

## 1. Architecture (unchanged)

| Layer | Current | Keep? |
|-------|---------|-------|
| Theme | AdminLTE 2, Bootstrap 3.3.6, jQuery 3, Chart.js, FullCalendar, DataTables | ✅ Keep |
| DB    | MySQL/MariaDB via `mysqli`, `DBController` class | ✅ Keep & extend |
| Auth  | Session-based, role_id 1–5, `login/login.php` | ✅ Keep flow, **fix hashing** |
| Nav   | DB-driven: `st_menu_master` / `st_sub_menu_master` / `st_menu_allocation_master` rendered by `header/side_menu.php` | ✅ Reuse — just reseed rows |
| Layout| `admin/header/header.php` + `side_menu.php` + `footer.php` | ✅ Reuse |

## 2. Role mapping (no schema change to `st_role_master`)

| role_id | Current | LMS |
|---------|---------|-----|
| 1 | Super Admin | **Super Admin** (manage admins, system settings, audit logs, permissions) |
| 2 | Admin | **Admin** (manage users/courses/reports/certificates, monitoring) |
| 3 | Coordinator/HOD | **Teacher** (create courses, upload content, quizzes, view progress) |
| 4 | Mentor | *(merge into Teacher or retire)* |
| 5 | Student | **Student** (browse/enroll/learn/quiz/progress/certificate) |

> Decision: remap role 3 → Teacher; retire role 4 (Mentor) or alias to Teacher.

## 3. Terminology replacement (global)

| TCET term | LMS term |
|-----------|----------|
| Specialization | Course |
| Specialization Subject | Lesson |
| Student Specialization Progress | Course Progress |
| Domain Completion | Course Completion |
| Learning Path | Course Curriculum |
| Specialization Certificate | Course Certificate |
| Department / Branch | Category |
| Enrollment (spec) | Enrollment (course) |

## 4. Database refactoring

### Reuse as-is
`st_login`, `st_user_master`, `st_role_master`, `st_audit_log`,
`st_menu_master`, `st_sub_menu_master`, `st_menu_allocation_master`,
`st_session_master`, `st_semester`, `st_batch_master`.

### New LMS tables (prefix `lms_`, InnoDB, FKs, soft-delete `deleted_at`, `created_at`/`updated_at`)
```
lms_categories        (id, name, slug, parent_id, ...)            ← replaces st_department_master concept
lms_courses           (id, category_id, teacher_id, title, slug, thumbnail, status[draft/published/archived], ...)
lms_course_sections   (id, course_id, title, sort_order)
lms_lessons           (id, section_id, type[video/pdf/quiz], title, sort_order)
lms_videos            (id, lesson_id, url/path, duration_seconds)
lms_pdfs              (id, lesson_id, path, page_count)
lms_enrollments       (id, student_id, course_id, status, enrolled_at)   ← refactor st_enrollment
lms_video_progress    (id, enrollment_id, video_id, watched_seconds, percent, last_position)
lms_pdf_progress      (id, enrollment_id, pdf_id, pages_read, percent, last_page)
lms_course_progress   (id, enrollment_id, video_pct, pdf_pct, quiz_pct, assignment_pct, overall_pct)
lms_quizzes           (id, lesson_id, title, time_limit, max_attempts, pass_pct)
lms_questions         (id, quiz_id, text, sort_order)
lms_question_options  (id, question_id, text, is_correct)
lms_quiz_attempts     (id, enrollment_id, quiz_id, score_pct, started_at, submitted_at)
lms_certificates      (id, enrollment_id, certificate_no UNIQUE, issued_at, qr_token)
lms_notifications     (id, user_id, title, body, is_read, created_at)
lms_announcements     (id, course_id, author_id, title, body, created_at)
```
> `audit_logs` ← reuse existing `st_audit_log`. `students/teachers/admins` ← views/role filters over `st_user_master`, not new tables (avoids duplicating user identity).

### Drop / archive (TCET-specific)
`st_specialization_master`, `st_specialization_subject_master`, `st_minorcourse`,
`st_minorsubject`, `st_minor_certificates`, `st_credit_ledger`, `st_eligibility_log`,
`st_nptel_records`, `st_research_records`, `st_offline_marks_entry`, `unaided_sub`,
`st_coordinator*`, `st_mentor_student_mapping`, `st_cgpa_master`, `st_division_master`.

## 5. Progress engine (Module 8)
```
overall = video*0.40 + pdf*0.30 + quiz*0.20 + assignment*0.10
```
Recomputed into `lms_course_progress` on each video/pdf/quiz save (AJAX). Certificate auto-issues at `overall >= 100` (or configurable pass threshold).

## 6. Folder structure (target)
```
/admin      (admin + super-admin pages — already exists)
/teacher    (NEW: course/content/quiz authoring)
/student    (NEW: catalog, player, quiz runner, certificates)
/ajax       (NEW: progress save, enroll, quiz submit endpoints)
/includes   (NEW: auth guard, role middleware, csrf, helpers, progress calc)
/assets     (consolidate admin/bootstrap, dist, css, js, plugins)
/uploads    (videos, pdfs, thumbnails — already partially exists)
/database   (schema + migrations)
```
> Reuse `admin/header/*` as the shared layout for all three role areas.

## 7. Navigation (reseed menu tables — no code change to renderer)
```
Dashboard
Courses    → Course Catalog, My Courses
Learning   → Videos, PDFs, Quizzes
Progress   → Course Progress, Certificates
Admin      → User Management, Course Management, Reports, Analytics
Settings
```
Allocate per role via `st_menu_allocation_master` (Student sees Courses/Learning/Progress; Teacher adds authoring; Admin adds Admin; Super Admin adds system settings).

## 8. Security hardening (Phase 8)
- `password_hash()` / `password_verify()` + one-time migration of plaintext `st_login` rows
- CSRF token in `/includes/csrf.php`, validated on all POST
- `htmlspecialchars()` on output (XSS); prepared statements everywhere (kill string-interp SQL)
- `/includes/auth.php` guard + `require_role()` middleware
- Session: `session_regenerate_id`, httponly/samesite cookies
- File upload validation (mime, ext, size) for video/pdf/thumbnail

## 9. Implementation sequence (incremental, verifiable)
1. **Foundation**: fix root redirect, add `/includes` (auth guard, csrf, role middleware, db bootstrap), hash passwords. *(no UI change)*
2. **DB**: create `lms_*` schema migration + seed categories/menu rows.
3. **Course Management** (Teacher/Admin): course CRUD + sections + lessons (reuse `role_*_base` + DataTables patterns).
4. **Content**: video/pdf/thumbnail upload + viewers.
5. **Catalog + Enrollment** (Student).
6. **Players + Progress engine** (video %, pdf %, AJAX autosave).
7. **Quiz module** (builder + runner + grading).
8. **Certificates** (generate + QR + PDF via existing jsPDF/dompdf).
9. **Dashboards** (student/teacher/admin — reskin existing dashboard.php cards/charts).
10. **Reports & Analytics** (Excel/CSV/PDF export).
11. **Cleanup**: drop TCET tables, remove dead pages, rename UI strings.

## 10. Deliverables (Phase 10)
Schema SQL · migration scripts · nav reseed SQL · updated folder tree · ER diagram (this doc + dbdiagram export) · page-by-page implementation · `/ajax` API list · progress logic · certificate workflow · deployment guide (`README` update).
