-- =====================================================================
--  LMS navigation reseed  (Phase 6)
--
--  Adds LMS menus/submenus into the existing DB-driven menu engine
--  (lms_menu_master / lms_sub_menu_master / lms_menu_allocation_master)
--  and allocates them per role. Idempotent (INSERT ... WHERE NOT EXISTS).
--
--  Routes are app-root-relative (contain "/") so side_menu.php resolves them
--  against LMS_BASE_URL and they work from /admin, /teacher and /student alike.
--
--  Roles: 1 Super Admin · 2 Admin · 3 Teacher(was Coordinator) · 4 Teacher(was Mentor) · 5 Student
--
--  NOTE: none of the legacy menu tables are AUTO_INCREMENT, so every id is
--  assigned explicitly from a counter seeded at the current MAX.
--
--  APPLY DURING PHASE 6, once the target pages exist:
--     mysql -u root lms < database/lms_menu_seed.sql
--  Optionally run the LEGACY CLEANUP block at the bottom for an LMS-only sidebar.
-- =====================================================================

-- ---------- 1. Top-level menus ----------
SET @menu_id := (SELECT COALESCE(MAX(`menu_id`), 0) FROM `lms_menu_master`);

INSERT INTO `lms_menu_master` (`menu_id`,`menu_name`,`menu_icon`)
SELECT (@menu_id := @menu_id + 1), 'Courses', 'fa fa-book'
WHERE NOT EXISTS (SELECT 1 FROM `lms_menu_master` WHERE `menu_name` = 'Courses');
SET @m_courses := (SELECT `menu_id` FROM `lms_menu_master` WHERE `menu_name` = 'Courses' ORDER BY `menu_id` LIMIT 1);

INSERT INTO `lms_menu_master` (`menu_id`,`menu_name`,`menu_icon`)
SELECT (@menu_id := @menu_id + 1), 'Learning', 'fa fa-play-circle'
WHERE NOT EXISTS (SELECT 1 FROM `lms_menu_master` WHERE `menu_name` = 'Learning');
SET @m_learning := (SELECT `menu_id` FROM `lms_menu_master` WHERE `menu_name` = 'Learning' ORDER BY `menu_id` LIMIT 1);

INSERT INTO `lms_menu_master` (`menu_id`,`menu_name`,`menu_icon`)
SELECT (@menu_id := @menu_id + 1), 'Progress', 'fa fa-line-chart'
WHERE NOT EXISTS (SELECT 1 FROM `lms_menu_master` WHERE `menu_name` = 'Progress');
SET @m_progress := (SELECT `menu_id` FROM `lms_menu_master` WHERE `menu_name` = 'Progress' ORDER BY `menu_id` LIMIT 1);

INSERT INTO `lms_menu_master` (`menu_id`,`menu_name`,`menu_icon`)
SELECT (@menu_id := @menu_id + 1), 'Administration', 'fa fa-cogs'
WHERE NOT EXISTS (SELECT 1 FROM `lms_menu_master` WHERE `menu_name` = 'Administration');
SET @m_admin := (SELECT `menu_id` FROM `lms_menu_master` WHERE `menu_name` = 'Administration' ORDER BY `menu_id` LIMIT 1);

-- ---------- 2. Submenus (route = unique idempotency key) ----------
SET @sub_id := (SELECT COALESCE(MAX(`sub_menu_id`), 0) FROM `lms_sub_menu_master`);

-- Courses
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_courses, 0, 'Dashboard', 'fa fa-dashboard', 'student/dashboard.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'student/dashboard.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_courses, 1, 'Course Catalog', 'fa fa-th-list', 'student/catalog.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'student/catalog.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_courses, 2, 'My Courses', 'fa fa-bookmark', 'student/my_courses.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'student/my_courses.php');

-- Learning
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_learning, 1, 'My Learning', 'fa fa-graduation-cap', 'student/learning.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'student/learning.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_learning, 2, 'Quizzes', 'fa fa-question-circle', 'student/quizzes.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'student/quizzes.php');

-- Progress
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_progress, 1, 'Course Progress', 'fa fa-tasks', 'student/progress.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'student/progress.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_progress, 2, 'Certificates', 'fa fa-certificate', 'student/certificates.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'student/certificates.php');

-- Administration
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_admin, 0, 'Teacher Dashboard', 'fa fa-dashboard', 'teacher/dashboard.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'teacher/dashboard.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_admin, 1, 'User Management', 'fa fa-users', 'admin/user_management.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'admin/user_management.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_admin, 2, 'Course Management', 'fa fa-folder-open', 'teacher/courses.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'teacher/courses.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_admin, 3, 'Enrollments', 'fa fa-users', 'teacher/enrollments.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'teacher/enrollments.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_admin, 4, 'Reports', 'fa fa-file-text', 'admin/reports.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'admin/reports.php');
INSERT INTO `lms_sub_menu_master` (`sub_menu_id`,`menu_id`,`sort_order`,`sub_menu_name`,`sub_menu_icon`,`sub_menu_route`)
SELECT (@sub_id := @sub_id + 1), @m_admin, 5, 'Analytics', 'fa fa-bar-chart', 'admin/analytics.php'
WHERE NOT EXISTS (SELECT 1 FROM `lms_sub_menu_master` WHERE `sub_menu_route` = 'admin/analytics.php');

-- ---------- 3. Role allocations ----------
DROP TEMPORARY TABLE IF EXISTS `tmp_lms_alloc`;
CREATE TEMPORARY TABLE `tmp_lms_alloc` (`role_id` INT, `menu_id` INT, `sub_menu_route` VARCHAR(255));

INSERT INTO `tmp_lms_alloc` (`role_id`,`menu_id`,`sub_menu_route`) VALUES
-- Student (5)
(5, @m_courses,  'student/dashboard.php'),
(5, @m_courses,  'student/catalog.php'),
(5, @m_courses,  'student/my_courses.php'),
(5, @m_learning, 'student/learning.php'),
(5, @m_learning, 'student/quizzes.php'),
(5, @m_progress, 'student/progress.php'),
(5, @m_progress, 'student/certificates.php'),
-- Teacher (3 and legacy 4)
(3, @m_courses,  'student/catalog.php'),
(3, @m_admin,    'teacher/dashboard.php'),
(3, @m_admin,    'teacher/courses.php'),
(3, @m_admin,    'teacher/enrollments.php'),
(3, @m_admin,    'admin/reports.php'),
(4, @m_courses,  'student/catalog.php'),
(4, @m_admin,    'teacher/dashboard.php'),
(4, @m_admin,    'teacher/courses.php'),
(4, @m_admin,    'teacher/enrollments.php'),
(4, @m_admin,    'admin/reports.php'),
-- Admin (2)
(2, @m_courses,  'student/catalog.php'),
(2, @m_admin,    'teacher/dashboard.php'),
(2, @m_admin,    'admin/user_management.php'),
(2, @m_admin,    'teacher/courses.php'),
(2, @m_admin,    'teacher/enrollments.php'),
(2, @m_admin,    'admin/reports.php'),
(2, @m_admin,    'admin/analytics.php'),
-- Super Admin (1)
(1, @m_courses,  'student/catalog.php'),
(1, @m_admin,    'teacher/dashboard.php'),
(1, @m_admin,    'admin/user_management.php'),
(1, @m_admin,    'teacher/courses.php'),
(1, @m_admin,    'teacher/enrollments.php'),
(1, @m_admin,    'admin/reports.php'),
(1, @m_admin,    'admin/analytics.php');

-- menu_allocation_id is NOT auto-increment; assign explicit ids from a counter.
SET @alloc_id := (SELECT COALESCE(MAX(`menu_allocation_id`), 0) FROM `lms_menu_allocation_master`);

-- Parent (sub_menu_id IS NULL) allocations
INSERT INTO `lms_menu_allocation_master` (`menu_allocation_id`,`user_id`,`role_id`,`menu_id`,`sub_menu_id`)
SELECT (@alloc_id := @alloc_id + 1), 0, t.`role_id`, t.`menu_id`, NULL
FROM (SELECT DISTINCT `role_id`, `menu_id` FROM `tmp_lms_alloc`) t
WHERE NOT EXISTS (
  SELECT 1 FROM `lms_menu_allocation_master` a
  WHERE a.`user_id`=0 AND a.`role_id`=t.`role_id` AND a.`menu_id`=t.`menu_id` AND a.`sub_menu_id` IS NULL
);

-- Child (sub_menu_id) allocations
INSERT INTO `lms_menu_allocation_master` (`menu_allocation_id`,`user_id`,`role_id`,`menu_id`,`sub_menu_id`)
SELECT (@alloc_id := @alloc_id + 1), 0, t.`role_id`, t.`menu_id`, s.`sub_menu_id`
FROM `tmp_lms_alloc` t
JOIN `lms_sub_menu_master` s ON s.`sub_menu_route` = t.`sub_menu_route`
WHERE NOT EXISTS (
  SELECT 1 FROM `lms_menu_allocation_master` a
  WHERE a.`user_id`=0 AND a.`role_id`=t.`role_id` AND a.`menu_id`=t.`menu_id` AND a.`sub_menu_id`=s.`sub_menu_id`
);

DROP TEMPORARY TABLE IF EXISTS `tmp_lms_alloc`;

-- =====================================================================
--  OPTIONAL — LEGACY CLEANUP (uncomment for an LMS-only sidebar)
--  Removes role allocations for the old TCET menus. Rows are kept intact.
-- =====================================================================
-- DELETE a FROM `lms_menu_allocation_master` a
-- JOIN `lms_menu_master` m ON m.menu_id = a.menu_id
-- WHERE m.menu_name IN ('Students','Admin','coordinator','mentor');
