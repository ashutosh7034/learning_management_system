-- =====================================================================
--  LMS schema migration  (run AFTER importing lms.sql)
--  Adds the lms_* tables alongside the existing st_* tables.
--  Safe & idempotent: uses CREATE TABLE IF NOT EXISTS.
--
--  Conventions:
--    - InnoDB, utf8mb4
--    - AUTO_INCREMENT surrogate PK `id`
--    - created_at / updated_at timestamps
--    - deleted_at (NULL = active) for soft delete
--    - FOREIGN KEYs between lms_* tables (ON DELETE CASCADE where it makes sense)
--    - References to existing users use lms_user_master.user_id as a plain
--      indexed int (no hard FK) so this script never fails against the legacy
--      schema regardless of its key/charset state.
--
--  Import:  mysql -u root lms < database/lms_schema.sql
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- Categories  (replaces the TCET "department/branch" grouping concept)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_categories` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(150) NOT NULL,
  `slug`        VARCHAR(170) NOT NULL,
  `parent_id`   INT(11) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at`  TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at`  TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_categories_slug` (`slug`),
  KEY `idx_lms_categories_parent` (`parent_id`),
  CONSTRAINT `fk_lms_categories_parent`
      FOREIGN KEY (`parent_id`) REFERENCES `lms_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Courses   (replaces lms_specialization_master)
--   teacher_id -> lms_user_master.user_id (role Teacher)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_courses` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `category_id`   INT(11) DEFAULT NULL,
  `teacher_id`    INT(11) NOT NULL,
  `title`         VARCHAR(200) NOT NULL,
  `slug`          VARCHAR(220) NOT NULL,
  `summary`       VARCHAR(500) DEFAULT NULL,
  `description`   TEXT DEFAULT NULL,
  `thumbnail`     VARCHAR(255) DEFAULT NULL,
  `intro_video_path` VARCHAR(255) DEFAULT NULL,
  `intro_video_url`  VARCHAR(500) DEFAULT NULL,
  `level`         ENUM('Beginner','Intermediate','Advanced') NOT NULL DEFAULT 'Beginner',
  `status`        ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
  `published_at`  TIMESTAMP NULL DEFAULT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at`    TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at`    TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_courses_slug` (`slug`),
  KEY `idx_lms_courses_category` (`category_id`),
  KEY `idx_lms_courses_teacher` (`teacher_id`),
  KEY `idx_lms_courses_status` (`status`),
  CONSTRAINT `fk_lms_courses_category`
      FOREIGN KEY (`category_id`) REFERENCES `lms_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Course sections  (Course Curriculum grouping)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_course_sections` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `course_id`  INT(11) NOT NULL,
  `title`      VARCHAR(200) NOT NULL,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lms_sections_course` (`course_id`),
  CONSTRAINT `fk_lms_sections_course`
      FOREIGN KEY (`course_id`) REFERENCES `lms_courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Lessons   (replaces lms_specialization_subject_master)
--   A lesson is one learning unit of a given type.
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_lessons` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `section_id` INT(11) NOT NULL,
  `course_id`  INT(11) NOT NULL,
  `title`      VARCHAR(200) NOT NULL,
  `type`       ENUM('video','pdf','quiz') NOT NULL DEFAULT 'video',
  `is_free`    TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lms_lessons_section` (`section_id`),
  KEY `idx_lms_lessons_course` (`course_id`),
  CONSTRAINT `fk_lms_lessons_section`
      FOREIGN KEY (`section_id`) REFERENCES `lms_course_sections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lms_lessons_course`
      FOREIGN KEY (`course_id`) REFERENCES `lms_courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Videos
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_videos` (
  `id`               INT(11) NOT NULL AUTO_INCREMENT,
  `lesson_id`        INT(11) NOT NULL,
  `title`            VARCHAR(200) DEFAULT NULL,
  `file_path`        VARCHAR(255) DEFAULT NULL,
  `external_url`     VARCHAR(500) DEFAULT NULL,
  `duration_seconds` INT(11) NOT NULL DEFAULT 0,
  `created_at`       TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at`       TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at`       TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lms_videos_lesson` (`lesson_id`),
  CONSTRAINT `fk_lms_videos_lesson`
      FOREIGN KEY (`lesson_id`) REFERENCES `lms_lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- PDFs
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_pdfs` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `lesson_id`  INT(11) NOT NULL,
  `title`      VARCHAR(200) DEFAULT NULL,
  `file_path`  VARCHAR(255) NOT NULL,
  `page_count` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lms_pdfs_lesson` (`lesson_id`),
  CONSTRAINT `fk_lms_pdfs_lesson`
      FOREIGN KEY (`lesson_id`) REFERENCES `lms_lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Enrollments   (refactor of lms_enrollment)
--   student_id -> lms_user_master.user_id (role Student)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_enrollments` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `student_id`  INT(11) NOT NULL,
  `course_id`   INT(11) NOT NULL,
  `status`      ENUM('active','completed','dropped') NOT NULL DEFAULT 'active',
  `enrolled_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `completed_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at`  TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at`  TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_enrollment` (`student_id`, `course_id`),
  KEY `idx_lms_enroll_course` (`course_id`),
  CONSTRAINT `fk_lms_enroll_course`
      FOREIGN KEY (`course_id`) REFERENCES `lms_courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Video progress  (per enrollment + video)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_video_progress` (
  `id`              INT(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id`   INT(11) NOT NULL,
  `video_id`        INT(11) NOT NULL,
  `watched_seconds` INT(11) NOT NULL DEFAULT 0,
  `last_position`   INT(11) NOT NULL DEFAULT 0,
  `percent`         DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `completed`       TINYINT(1) NOT NULL DEFAULT 0,
  `updated_at`      TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_video_progress` (`enrollment_id`, `video_id`),
  KEY `idx_lms_vprog_video` (`video_id`),
  CONSTRAINT `fk_lms_vprog_enroll`
      FOREIGN KEY (`enrollment_id`) REFERENCES `lms_enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lms_vprog_video`
      FOREIGN KEY (`video_id`) REFERENCES `lms_videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- PDF progress  (per enrollment + pdf)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_pdf_progress` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id` INT(11) NOT NULL,
  `pdf_id`        INT(11) NOT NULL,
  `pages_read`    INT(11) NOT NULL DEFAULT 0,
  `last_page`     INT(11) NOT NULL DEFAULT 1,
  `percent`       DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `completed`     TINYINT(1) NOT NULL DEFAULT 0,
  `updated_at`    TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_pdf_progress` (`enrollment_id`, `pdf_id`),
  KEY `idx_lms_pprog_pdf` (`pdf_id`),
  CONSTRAINT `fk_lms_pprog_enroll`
      FOREIGN KEY (`enrollment_id`) REFERENCES `lms_enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lms_pprog_pdf`
      FOREIGN KEY (`pdf_id`) REFERENCES `lms_pdfs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Course progress  (rolled-up per enrollment — Module 8 engine output)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_course_progress` (
  `id`              INT(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id`   INT(11) NOT NULL,
  `video_pct`       DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `pdf_pct`         DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `quiz_pct`        DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `assignment_pct`  DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `overall_pct`     DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `updated_at`      TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_course_progress` (`enrollment_id`),
  CONSTRAINT `fk_lms_cprog_enroll`
      FOREIGN KEY (`enrollment_id`) REFERENCES `lms_enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Quizzes
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_quizzes` (
  `id`             INT(11) NOT NULL AUTO_INCREMENT,
  `lesson_id`      INT(11) NOT NULL,
  `title`          VARCHAR(200) NOT NULL,
  `description`    TEXT DEFAULT NULL,
  `time_limit_min` INT(11) NOT NULL DEFAULT 0,
  `max_attempts`   INT(11) NOT NULL DEFAULT 0,
  `pass_percent`   DECIMAL(5,2) NOT NULL DEFAULT 60.00,
  `created_at`     TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at`     TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at`     TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lms_quizzes_lesson` (`lesson_id`),
  CONSTRAINT `fk_lms_quizzes_lesson`
      FOREIGN KEY (`lesson_id`) REFERENCES `lms_lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Questions
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_questions` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `quiz_id`    INT(11) NOT NULL,
  `text`       TEXT NOT NULL,
  `marks`      INT(11) NOT NULL DEFAULT 1,
  `sort_order` INT(11) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lms_questions_quiz` (`quiz_id`),
  CONSTRAINT `fk_lms_questions_quiz`
      FOREIGN KEY (`quiz_id`) REFERENCES `lms_quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Question options  (MCQ)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_question_options` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `question_id` INT(11) NOT NULL,
  `text`        VARCHAR(500) NOT NULL,
  `is_correct`  TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order`  INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_lms_options_question` (`question_id`),
  CONSTRAINT `fk_lms_options_question`
      FOREIGN KEY (`question_id`) REFERENCES `lms_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Quiz attempts  (auto-graded)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_quiz_attempts` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id` INT(11) NOT NULL,
  `quiz_id`       INT(11) NOT NULL,
  `score_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `passed`        TINYINT(1) NOT NULL DEFAULT 0,
  `answers_json`  LONGTEXT DEFAULT NULL,
  `started_at`    TIMESTAMP NULL DEFAULT NULL,
  `submitted_at`  TIMESTAMP NULL DEFAULT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_lms_attempts_enroll` (`enrollment_id`),
  KEY `idx_lms_attempts_quiz` (`quiz_id`),
  CONSTRAINT `fk_lms_attempts_enroll`
      FOREIGN KEY (`enrollment_id`) REFERENCES `lms_enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lms_attempts_quiz`
      FOREIGN KEY (`quiz_id`) REFERENCES `lms_quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Certificates   (replaces lms_minor_certificates)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_certificates` (
  `id`             INT(11) NOT NULL AUTO_INCREMENT,
  `enrollment_id`  INT(11) NOT NULL,
  `certificate_no` VARCHAR(50) NOT NULL,
  `qr_token`       VARCHAR(64) NOT NULL,
  `issued_at`      TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_cert_no` (`certificate_no`),
  UNIQUE KEY `uq_lms_cert_qr` (`qr_token`),
  UNIQUE KEY `uq_lms_cert_enroll` (`enrollment_id`),
  CONSTRAINT `fk_lms_cert_enroll`
      FOREIGN KEY (`enrollment_id`) REFERENCES `lms_enrollments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Notifications  (per user; user_id -> lms_user_master.user_id)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_notifications` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11) NOT NULL,
  `title`      VARCHAR(200) NOT NULL,
  `body`       TEXT DEFAULT NULL,
  `link`       VARCHAR(255) DEFAULT NULL,
  `is_read`    TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_lms_notif_user` (`user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Announcements  (course-scoped or global when course_id IS NULL)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_announcements` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `course_id`  INT(11) DEFAULT NULL,
  `author_id`  INT(11) NOT NULL,
  `title`      VARCHAR(200) NOT NULL,
  `body`       TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_lms_announce_course` (`course_id`),
  CONSTRAINT `fk_lms_announce_course`
      FOREIGN KEY (`course_id`) REFERENCES `lms_courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Settings  (Super Admin system configuration — key/value)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_settings` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `setting_key`   VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `updated_at`    TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_lms_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ---------------------------------------------------------------------
-- Seed data
-- ---------------------------------------------------------------------
INSERT INTO `lms_categories` (`name`, `slug`, `description`)
SELECT * FROM (
  SELECT 'Programming' AS name, 'programming' AS slug, 'Software development courses' AS description UNION ALL
  SELECT 'Data Science', 'data-science', 'Data, ML & analytics' UNION ALL
  SELECT 'Web Development', 'web-development', 'Frontend & backend web' UNION ALL
  SELECT 'Design', 'design', 'UI/UX and graphic design' UNION ALL
  SELECT 'Business', 'business', 'Management & entrepreneurship'
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM `lms_categories` LIMIT 1);

INSERT INTO `lms_settings` (`setting_key`, `setting_value`)
SELECT 'site_name', 'Learning Management System'
WHERE NOT EXISTS (SELECT 1 FROM `lms_settings` WHERE `setting_key` = 'site_name');

INSERT INTO `lms_settings` (`setting_key`, `setting_value`)
SELECT 'certificate_threshold', '100'
WHERE NOT EXISTS (SELECT 1 FROM `lms_settings` WHERE `setting_key` = 'certificate_threshold');

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
--  End of LMS schema migration
-- =====================================================================
