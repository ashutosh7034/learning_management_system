<?php
session_start();
require "../database/db_connect.php";
require_once __DIR__ . '/../includes/password.php';

$db_handle = new DBController();

$message = "";

function getNextId(mysqli $conn, string $table, string $column): int
{
    $result = mysqli_query($conn, "SELECT COALESCE(MAX($column), 0) + 1 AS next_id FROM $table");
    if ($result && ($row = mysqli_fetch_assoc($result))) {
        return (int) ($row['next_id'] ?? 1);
    }

    return 1;
}

// ================= REGISTER LOGIC =================
if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = $_POST['department'];

    // VALIDATIONS
    if (!preg_match("/^[A-Za-z_ ]{3,20}$/", $username)) {
        $message = "Username must be 3-20 characters (letters, space, underscore)";
    }
    elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $message = "Phone must be 10 digits";
    }
    elseif (strpos($email, "@tcetmumbai.in") === false) {
        $message = "Use institute email only!";
    }
    else {

        $username = mysqli_real_escape_string($db_handle->conn, $username);
        $email = mysqli_real_escape_string($db_handle->conn, $email);
        $phone = mysqli_real_escape_string($db_handle->conn, $phone);
        $department = mysqli_real_escape_string($db_handle->conn, $department);

        $plain_password = "Tcet@1234";
        $hashed_password = lms_hash_password($plain_password);
        $role_id = 5;

        $check = $db_handle->query("SELECT * FROM lms_user_master WHERE email_id='$email'");

        if ($check && mysqli_num_rows($check) > 0) {
            $message = "Email already exists!";
        } else {

            $user_id = getNextId($db_handle->conn, 'lms_user_master', 'user_id');
            $login_id = getNextId($db_handle->conn, 'lms_login', 'login_id');
            $student_id = getNextId($db_handle->conn, 'lms_student_master', 'student_id');

            mysqli_begin_transaction($db_handle->conn);

            // Insert minimal student record so admission form can be prefilled
            $regNo = '';
            $classId = 0;
            $divisionId = 0;
            $gradYear = 'NULL';
            $rollNo = '';
            $specId = 'NULL';
            $specSubId = 'NULL';
            $minorCourse = 'NULL';
            $minorSubject = 'NULL';
            $cgpa = 'NULL';
            $fname = $username;
            $mobile = $phone;
            $createdAt = date('Y-m-d H:i:s');

            $sqlStudent = "INSERT INTO lms_student_master (student_id, registration_no, class_id, division_id, grad_year, roll_no, department_id, specialization_id, specialization_subject_id, minor_course_id, minor_subject_id, cgpa, fname, mobile, email, mark_list, status, m_sem1, m_sem2, m_sem3, created_at, academic_year_id, current_semester_id)
                           VALUES ($student_id, '" . mysqli_real_escape_string($db_handle->conn, $regNo) . "', $classId, $divisionId, $gradYear, '" . mysqli_real_escape_string($db_handle->conn, $rollNo) . "', $department, $specId, $specSubId, $minorCourse, $minorSubject, $cgpa, '" . mysqli_real_escape_string($db_handle->conn, $fname) . "', '" . mysqli_real_escape_string($db_handle->conn, $mobile) . "', '" . mysqli_real_escape_string($db_handle->conn, $email) . "', '', 1, '', '', '', '$createdAt', NULL, NULL)";

            if ($db_handle->query($sqlStudent)) {
                // Insert user with linked student_id
                $sql1 = "INSERT INTO lms_user_master 
                (user_id, user_name, email_id, phone_number, department_id, role_id, student_id)
                VALUES ($user_id,'$username','$email','$phone',$department,$role_id,$student_id)";

                if ($db_handle->query($sql1)) {
                    $sql2 = "INSERT INTO lms_login(login_id, username, password, user_id)
                             VALUES($login_id,'$email','$hashed_password',$user_id)";

                    if ($db_handle->query($sql2)) {
                        mysqli_commit($db_handle->conn);

                        // ✅ STORE IN SESSION (SHOW ONCE)
                        $_SESSION['show_credentials'] = true;
                        $_SESSION['registered_username'] = $email;
                        $_SESSION['registered_password'] = $plain_password;

                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();

                    } else {
                        mysqli_rollback($db_handle->conn);
                        $message = "Login Insert Error";
                    }

                } else {
                    mysqli_rollback($db_handle->conn);
                    $message = "User Insert Error";
                }
            } else {
                mysqli_rollback($db_handle->conn);
                $message = "Student Insert Error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Registration</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    min-height: 100vh;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #101828 0%, #1f2937 45%, #0f172a 100%);
    padding: 24px 16px;
}

.register-wrap {
    width: 100%;
    max-width: 620px;
    margin: 0 auto;
}

.register-card {
    background: rgba(255, 255, 255, 0.96);
    width: 100%;
    border-radius: 16px;
    box-shadow: 0 20px 55px rgba(0, 0, 0, 0.35);
    overflow: hidden;
}

.register-header {
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #fff;
    text-align: center;
    padding: 22px 20px;
}

.register-header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: 0.4px;
}

.register-header p {
    margin: 8px 0 0;
    opacity: 0.92;
    font-size: 13px;
}

.register-body {
    padding: 28px 26px 10px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    color: #334155;
    font-weight: 600;
    margin-bottom: 7px;
}

.input-group {
    width: 100%;
}

.input-group-addon {
    background: #eff6ff;
    color: #2563eb;
    border-color: #cbd5e1;
    min-width: 44px;
}

.form-control {
    height: 46px;
    border-radius: 10px;
    border-color: #cbd5e1;
    box-shadow: none;
}

.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

select.form-control {
    padding-top: 8px;
    padding-bottom: 8px;
}

select.form-control option {
    color: #0f172a;
}

.register-footer {
    padding: 0 26px 28px;
}

button {
    width: 100%;
    height: 48px;
    margin-top: 8px;
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
}

button:hover {
    background: linear-gradient(135deg, #0284c7, #1d4ed8);
    transform: translateY(-1px);
}

#msgBox {
    color: #b91c1c;
    text-align: center;
    margin-bottom: 16px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #fee2e2;
    border: 1px solid #fecaca;
}

#credentialsBox {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    padding: 32px 24px;
    border-radius: 14px;
    margin-bottom: 0;
    color: #0f172a;
    text-align: center;
    border: 2px solid #86efac;
    margin-top: 12px;
}

#credText {
    background: #ffffff;
    padding: 20px 16px;
    border-radius: 10px;
    margin: 18px 0;
    border: 1px solid #d1fae5;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    line-height: 1.8;
    color: #065f46;
    font-weight: 500;
}

#credText strong {
    display: block;
    font-size: 12px;
    color: #047857;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
    font-weight: 600;
}

.credentials-title {
    color: #059669;
    margin-bottom: 16px;
    font-size: 18px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.credentials-title::before {
    content: "✓ ";
    color: #10b981;
}

.helper-text {
    text-align: center;
    color: #64748b;
    margin-bottom: 14px;
    font-size: 13px;
}

.btn-copy {
    margin-top: 18px;
    width: 100%;
    height: 48px;
    padding: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #059669, #10b981);
    color: white;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-copy:hover {
    background: linear-gradient(135deg, #047857, #059669);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(5, 150, 105, 0.3);
}

.btn-proceed {
    margin-top: 14px;
    width: 100%;
    height: 44px;
    padding: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: white;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.btn-proceed:hover {
    background: linear-gradient(135deg, #0284c7, #1d4ed8);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(2, 132, 199, 0.3);
}

.form-hidden {
    display: none;
}

.form-hidden {
    display: none;
}

@media (max-width: 480px) {
    .register-body,
    .register-footer {
        padding-left: 18px;
        padding-right: 18px;
    }

    .register-header h2 {
        font-size: 18px;
    }
}
</style>

<script>
function copyCredentials() {
    const text = document.getElementById("credText").innerText;
    navigator.clipboard.writeText(text);

    alert("Copied!");
}

function proceedToEnrollment() {
    // Close the modal - user can now access enrollment form
    if (window.parent && window.parent !== window) {
        if (typeof window.parent.closePopup === 'function') {
            window.parent.closePopup();
            // Show alert with next steps
            setTimeout(function() {
                alert("Registration successful!\n\nNext: Log in with your credentials or contact admin for enrollment.");
            }, 300);
        }
    }
}

function showRegisterError(message) {
    var msgBox = document.getElementById('msgBox');
    if (!msgBox) {
        msgBox = document.createElement('div');
        msgBox.id = 'msgBox';
        var form = document.querySelector('.register-body form');
        if (form) {
            form.parentNode.insertBefore(msgBox, form);
        }
    }

    msgBox.textContent = message;
    msgBox.style.display = 'block';
}

function validateStudentRegisterForm(event) {
    var username = document.querySelector('input[name="username"]');
    var email = document.querySelector('input[name="email"]');
    var phone = document.querySelector('input[name="phone"]');
    var department = document.querySelector('select[name="department"]');

    var usernameValue = username ? username.value.trim() : '';
    var emailValue = email ? email.value.trim() : '';
    var phoneValue = phone ? phone.value.trim() : '';
    var departmentValue = department ? department.value.trim() : '';

    var emailPattern = /^[^\s@]+@tcetmumbai\.in$/i;
    var phonePattern = /^[0-9]{10}$/;
    var usernamePattern = /^[A-Za-z_ ]{3,20}$/;

    if (!usernameValue) {
        event.preventDefault();
        showRegisterError('Username is required.');
        return false;
    }

    if (!usernamePattern.test(usernameValue)) {
        event.preventDefault();
        showRegisterError('Username must be 3-20 characters and contain only letters, spaces, or underscore.');
        return false;
    }

    if (!emailValue) {
        event.preventDefault();
        showRegisterError('Institute email is required.');
        return false;
    }

    if (!emailPattern.test(emailValue)) {
        event.preventDefault();
        showRegisterError('Use institute email only.');
        return false;
    }

    if (!phoneValue) {
        event.preventDefault();
        showRegisterError('Phone number is required.');
        return false;
    }

    if (!phonePattern.test(phoneValue)) {
        event.preventDefault();
        showRegisterError('Phone must be 10 digits.');
        return false;
    }

    if (!departmentValue) {
        event.preventDefault();
        showRegisterError('Please select a department.');
        return false;
    }

    return true;
}

window.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('.register-body form');
    if (form) {
        form.addEventListener('submit', validateStudentRegisterForm);
    }
});
</script>

<?php if (isset($_SESSION['show_credentials']) && $_SESSION['show_credentials']) { ?>
<script>
window.addEventListener('load', function () {
    if (window.parent && window.parent !== window) {
        try {
            var parentDocument = window.parent.document;
            var usernameField = parentDocument.getElementById('username');
            var passwordField = parentDocument.getElementById('password');

            if (usernameField) {
                usernameField.value = <?php echo json_encode($_SESSION['registered_username']); ?>;
            }

            if (passwordField) {
                passwordField.value = <?php echo json_encode($_SESSION['registered_password']); ?>;
            }

            if (usernameField) {
                usernameField.focus();
            }
        } catch (error) {
            window.parent.postMessage({
                type: 'student-registration-success',
                username: <?php echo json_encode($_SESSION['registered_username']); ?>,
                password: <?php echo json_encode($_SESSION['registered_password']); ?>
            }, window.location.origin);
        }
    }
});
</script>
<?php } ?>

</head>
<body>

<div class="register-wrap">
    <div class="register-card">
        <div class="register-header">
            <h2>Student Registration</h2>
            <p>Use your institute email and active phone number to create the account.</p>
        </div>

        <div class="register-body">
            <?php if (!empty($message)) { ?>
            <div id="msgBox"><?php echo htmlspecialchars($message); ?></div>
            <?php } ?>

            <?php if (isset($_SESSION['show_credentials']) && $_SESSION['show_credentials']) { ?>
            <div id="credentialsBox">
                <div class="credentials-title">Registration Successful!</div>
                <p style="color: #059669; margin: 14px 0; font-size: 14px;">Your account has been created. Please save your login credentials below.</p>
                <div id="credText">
                    <strong>Username:</strong>
                    <?php echo $_SESSION['registered_username']; ?>
                    <br><br>
                    <strong>Password:</strong>
                    <?php echo $_SESSION['registered_password']; ?>
                </div>
                <button type="button" class="btn-copy" onclick="copyCredentials()">📋 Copy Credentials</button>
                <p style="color: #6b7280; margin-top: 16px; font-size: 12px; line-height: 1.6;">
                    Login with these credentials.<br>
                    <strong>Keep this information secure</strong>
                </p>
                <button type="button" class="btn-proceed" onclick="proceedToEnrollment()">→ Proceed to Enrollment</button>
            </div>
            <?php
            unset($_SESSION['show_credentials']);
            unset($_SESSION['registered_username']);
            unset($_SESSION['registered_password']);
            ?>
            <?php } else { ?>
            <form method="POST" autocomplete="off">
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label>Institute Email</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="name@tcetmumbai.in" required autocomplete="email">
                    </div>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <input type="text" name="phone" class="form-control" placeholder="10 digit phone number" required maxlength="10" inputmode="numeric" autocomplete="tel">
                    </div>
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-building"></i></span>
                        <select name="department" class="form-control" required>
                            <option value="">Select department</option>
                            <?php
                            $dept = $db_handle->query("SELECT department_id, department_name FROM lms_department_master ORDER BY department_name ASC");
                            while ($row = $dept->fetch_assoc()) {
                                echo '<option value="'.$row['department_id'].'">'.$row['department_name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="register-footer">
                    <button type="submit" name="register">REGISTER</button>
                </div>
            </form>
            <?php } ?>
        </div>
</div>

</body>
</html>