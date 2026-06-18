<?php
include('header.php');
include_once("../database/db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            background: #2c3e50;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .w3layouts-main {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        .bg-layer {
            background: rgba(0, 0, 0, 0.7);

            height: 100%;
            width: 40%;

            display: flex;
            justify-content: center;
            align-items: center;

            border-radius: 0;
            margin: 0;
            padding: 0;
        }

        .header-main {
            background: #34495e;
            padding: 40px 30px;

            width: 80%;
            max-width: 420px;

            border-radius: 12px;
        }

        .main-icon img {
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            font-family: 'Lucida Sans Unicode', sans-serif;
            color: #ffffff; 
        }

        .header-left-bottom {
            margin-top: 20px;
        }

        .login-field {
            display: flex;
            align-items: stretch;
            width: 100%;
            height: 48px;
            margin-bottom: 15px;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
        }

        .login-field-icon {
            width: 48px;
            flex: 0 0 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef4fb;
            color: #34495e;
        }

        .login-field-input {
            flex: 1;
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            padding: 0 14px;
            font-size: 14px;
            background: #ffffff;
            color: #1f2d3d;
        }

        .login-field-input::placeholder {
            color: #8b97a3;
        }

        .password-toggle {
            width: 48px;
            flex: 0 0 48px;
            border: none;
            border-left: 1px solid #d7e2ec;
            background: #eef4fb;
            color: #34495e;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .password-toggle:focus {
            outline: none;
        }

        .login-check {
            text-align: left;
            margin-bottom: 15px;
        }

        .bottom {
            margin-top: 20px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            display: none;
            justify-content: center;
            align-items: center;

            background: rgba(15, 23, 42, 0.72);
            z-index: 1000;
        }


        .popup {
            position: relative;

            width: 520px;
            height: 620px;

            border-radius: 16px;

            background: rgba(255, 255, 255, 0.96);

            display: flex;
            justify-content: center;
            align-items: center;

            box-shadow: 0 20px 55px rgba(0, 0, 0, 0.35);
        }

        /* iframe */
        .popup iframe {
            width: 100%;
            height: 100%;
            border: none;

            display: block;
        }

        .popup span {
            position: absolute;
            top: 15px;
            right: 18px;
            font-size: 24px;
            font-weight: bold;
            color: #64748b;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .popup span:hover {
            color: #334155;
        }

        .btn {
            background: #1abc9c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100%;
            height: 48px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 15px;
        }

        .btn:hover {
            background: #16a085;
        }

        .copyright {
            margin-top: 10px;
        }

        .copyright a {
            color: #ED2C02;
            /* Bright red */
        }

        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }

        #login-form {
            width: 100%;
        }

        /* Inputs */
        .icon1 input {
            width: 100%;
            height: 45px;
            padding: 10px 10px 10px 40px;
            border-radius: 6px;
            border: none;
        }

        @media (max-width: 991px) {
            body {
                justify-content: center;
                align-items: stretch;
            }

            .w3layouts-main {
                justify-content: center;
                align-items: stretch;
            }

            .bg-layer {
                width: 100%;
                padding: 30px 20px;
            }

            .header-main {
                width: 100%;
                max-width: 540px;
            }

            .popup {
                width: min(92vw, 620px);
                height: min(88vh, 700px);
            }
        }

        @media (max-width: 600px) {
            body,
            .w3layouts-main {
                align-items: flex-start;
            }

            .bg-layer {
                padding: 16px 12px;
            }

            .header-main {
                border-radius: 10px;
                padding: 24px 16px;
            }

            .school-name {
                font-size: 17px;
                margin-bottom: 14px;
            }

            .popup {
                width: 96vw;
                height: 92vh;
                border-radius: 12px;
            }

            .popup span {
                top: 10px;
                right: 12px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script/validation.min.js"></script>
<script src="script/login.js"></script>
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }


        function openRegister() {
            document.getElementById("registerPopup").style.display = "flex";
        }

        function closePopup() {
            document.getElementById("registerPopup").style.display = "none";
        }

        function openForgotPassword() {
            document.getElementById("forgotPasswordPopup").style.display = "flex";
        }

        function closeForgotPasswordPopup() {
            document.getElementById("forgotPasswordPopup").style.display = "none";
        }

        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var toggleButton = document.getElementById("passwordToggle");
            var toggleIcon = document.getElementById("passwordToggleIcon");

            if (!passwordField || !toggleButton || !toggleIcon) {
                return;
            }

            var isPassword = passwordField.type === "password";
            passwordField.type = isPassword ? "text" : "password";
            toggleIcon.className = isPassword ? "fa fa-eye-slash" : "fa fa-eye";
            toggleButton.setAttribute("aria-label", isPassword ? "Hide password" : "Show password");
        }

        window.addEventListener("message", function (event) {
            if (event.origin !== window.location.origin) {
                return;
            }

            var data = event.data || {};
            if (data.type === "student-registration-success") {
                var usernameField = document.getElementById("username");
                var passwordField = document.getElementById("password");

                if (usernameField) {
                    usernameField.value = data.username || "";
                }

                if (passwordField) {
                    passwordField.value = data.password || "";
                }

                closePopup();

                if (usernameField) {
                    usernameField.focus();
                }
            }
        });
        

    </script>
</head>

<body>

    <div class="w3layouts-main">
        <div class="bg-layer"><br /><br /><br /><br /><br />
            <div class="header-main">
                <div class="main-icon">
                    <img src="images/school_logo.jpg" alt="logo" width='150px' height='150px'>
                </div>
                <div class="school-name"> LEARNING MANAGEMENT SYSTEM </div>
                <div class="header-left-bottom">
                    <form id="login-form">
                        <div class="login-field">
                            <div class="login-field-icon"><i class="fa fa-user"></i></div>
                            <input type="text" class="login-field-input" placeholder="Enter username" name="username" id="username" required="" />
                        </div>
                        <div class="login-field">
                            <div class="login-field-icon"><i class="fa fa-lock"></i></div>
                            <input type="password" class="login-field-input" placeholder="Enter password" name="password" id="password" required="" />
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password" onclick="togglePasswordVisibility()">
                                <i id="passwordToggleIcon" class="fa fa-eye"></i>
                            </button>
                        </div> 
                        <div class="login-check">
                            <label class="checkbox">
                                <input type="checkbox" name="checkbox" checked="">
                                <i></i> Keep me logged in
                            </label>
                        </div>
                        <div id="error" style="color:red;"></div>
                        <div class="bottom">
                            <button type="submit" class="btn" name="login_button" id="login_button">Log In</button>
                            <div style="margin-top:15px; text-align:center;">
                                <span style="color:#fff;">Are you Student?</span><br>
                                <button type="button" class="btn" onclick="openRegister()">
                                    Register
                                </button>
                            </div>
                    </form>
                </div>
            </div>
            <div id="registerPopup" class="overlay">
                <div class="popup">

                    <span style="float:right; cursor:pointer;" onclick="closePopup()">❌</span>

                    <iframe src="student_register.php" width="100%" height="500px" style="border:none;"></iframe>

                </div>
            </div>
            <div id="forgotPasswordPopup" class="overlay">
                <div class="popup">

                    <span style="float:right; cursor:pointer;" onclick="closeForgotPasswordPopup()">❌</span>

                    <iframe src="forgot_password.php" width="100%" height="500px" style="border:none;"></iframe>

                </div>
            </div>
                <div style="text-align:center; margin-top:10px;">
                <button type="button" class="btn" onclick="openForgotPassword()" style="color:white; background-color:transparent; border:1px solid white; padding:8px 16px; border-radius:4px; cursor:pointer;">Forgot Password?</button>
            </div>
            <div class="copyright">
                <p>© 2026. All rights reserved | Learning Management System</p>
            </div>
        </div>
    </div>

</body>

</html>


