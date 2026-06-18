-- =====================================================================
--  LMS SQL Views  (Phase 5)
--  Creates the conceptual 'users', 'roles', 'students', 'teachers',
--  and 'admins' views on top of existing lms_* tables.
-- =====================================================================

CREATE OR REPLACE VIEW `users` AS
SELECT * FROM `lms_user_master`;

CREATE OR REPLACE VIEW `roles` AS
SELECT * FROM `lms_role_master`;

CREATE OR REPLACE VIEW `students` AS
SELECT u.user_id, u.user_name, u.email_id, u.phone_number, u.department_id, u.role_id,
       s.student_id, s.registration_no, s.class_id, s.division_id, s.grad_year, s.roll_no, s.cgpa, s.fname, s.mobile, s.email, s.status, s.created_at
FROM `lms_user_master` u
JOIN `lms_student_master` s ON s.student_id = u.student_id
WHERE u.role_id = 5;

CREATE OR REPLACE VIEW `teachers` AS
SELECT * FROM `lms_user_master`
WHERE `role_id` IN (3, 4);

CREATE OR REPLACE VIEW `admins` AS
SELECT * FROM `lms_user_master`
WHERE `role_id` IN (1, 2);
