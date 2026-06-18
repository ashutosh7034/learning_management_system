<?php
session_start();
require "../database/db_connect.php";
$db_handle = new DBController();

$message = "";
$messageType = ""; // "success" or "error"

if (isset($_POST['reset_btn'])) {

    $email = mysqli_real_escape_string($db_handle->conn, $_POST['email']);

    if (strpos($email, "@tcetmumbai.in") === false) {
        $message = "🚫 Use institute email only!";
        $messageType = "error";
    } else {

        $check = $db_handle->query("SELECT user_id FROM lms_user_master WHERE email_id='$email'");

        if ($check && mysqli_num_rows($check) > 0) {

            $row = mysqli_fetch_assoc($check);
            $user_id = $row['user_id'];

            $new_password = "Tcet@1234";

            $update = $db_handle->query("UPDATE lms_login SET password='$new_password' WHERE user_id='$user_id'");

            if ($update) {
                $message = "✓ Password reset successful! Your new password is: <strong>Tcet@1234</strong>";
                $messageType = "success";
            } else {
                $message = "❌ Error resetting password!";
                $messageType = "error";
            }

        } else {
            $message = "❌ Email not found!";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
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

        .register-footer {
            padding: 0 26px 28px;
        }

        button,
        .btn-link-like {
            width: 100%;
            height: 48px;
            margin-top: 8px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-reset {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .btn-reset:hover {
            background: linear-gradient(135deg, #0284c7, #1d4ed8);
            transform: translateY(-1px);
        }

        .btn-back {
            display: block;
            width: 100%;
            line-height: 46px;
            text-align: center;
            background: transparent;
            color: #334155;
            border: 1px solid #cbd5e1;
            text-decoration: none;
        }

        .btn-back:hover {
            background: #f8fafc;
            text-decoration: none;
            transform: translateY(-1px);
        }

        #msgBox {
            text-align: center;
            margin-bottom: 20px;
            padding: 18px 16px;
            border-radius: 12px;
            font-weight: 500;
            border: 2px solid;
            animation: slideIn 0.3s ease-out;
        }

        #msgBox.success {
            color: #065f46;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border-color: #86efac;
        }

        #msgBox.error {
            color: #7f1d1d;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-color: #fca5a5;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .helper-text {
            text-align: center;
            color: #64748b;
            margin-bottom: 14px;
            font-size: 13px;
        }

        .copyright {
            margin-top: 12px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        .copyright a {
            color: #ED2C02;
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
        window.onload = function () {
            const msg = document.getElementById("msgBox");

            if (msg && msg.innerText.trim() !== "") {
                // Check if this is a success message
                const successMessage = "Password reset successful!";
                if (msg.innerText.includes(successMessage)) {
                    // Reset successful - close popup after delay
                    setTimeout(() => {
                        try {
                            parent.closeForgotPasswordPopup();
                        } catch(e) {
                            console.log("Parent close not available, reloading parent");
                            parent.location.reload();
                        }
                    }, 2000);
                } else {
                    // Error message - fade out after 3 seconds
                    setTimeout(() => {
                        msg.style.transition = "opacity 0.5s";
                        msg.style.opacity = "0";

                        setTimeout(() => {
                            msg.style.display = "none";
                        }, 500);
                    }, 3000);
                }
            }
        };

        function closeModal() {
            try {
                parent.closeForgotPasswordPopup();
            } catch(e) {
                window.location.href = "index.php";
            }
        }
    </script>
</head>

<body>

    <div class="register-wrap">
        <div class="register-card">
            <div class="register-header">
                <h2>Forgot Password</h2>
                <p>Reset your password using your institute email address.</p>
            </div>

            <div class="register-body">
                <div style="text-align:center; margin-bottom: 18px;">
                    <img src="images/school_logo.jpg" alt="logo" width="110" height="110" style="border-radius:50%;">
                </div>

                <div class="helper-text">The default password will be set to <strong>Tcet@1234</strong>.</div>

                <?php if (!empty($message)) { ?>
                    <div id="msgBox" class="<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php } ?>

                <form method="POST" autocomplete="off">
                    <div class="form-group">
                        <label>Institute Email</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="name@tcetmumbai.in" required>
                        </div>
                    </div>

                    <div class="register-footer">
                        <button type="submit" name="reset_btn" class="btn-reset">Reset Password</button>
                        <a href="#" class="btn-back" onclick="closeModal(); return false;">Back to Login</a>
                    </div>
                </form>
            </div>

            <div class="copyright">
                <p>© 2019. All rights reserved | Designed by <a href="https://dignityitsolution.com/" target="_blank">Dignity IT Solution</a></p>
            </div>
        </div>
    </div>

</body>
</html>