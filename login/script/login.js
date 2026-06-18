
$(document).ready(function () {

	$("#login-form").validate({
		rules: {
			username: { required: true },
			password: { required: true }
		},
		messages: {
			username: "please enter your username",
			password: "please enter your password"
		},
		submitHandler: function (form, event) {
			event.preventDefault();
			submitForm();
		}
	});

	function submitForm() {

		var data = $("#login-form").serialize();

		$.ajax({
			type: 'POST',
			url: 'login.php',
			data: data,

			beforeSend: function () {
				$("#error").fadeOut();
				$("#login_button").html('sending...');
			},

			success: function (response) {
				var cleanResponse = $.trim(response || '');
				var lowerResponse = cleanResponse.toLowerCase();

				if (
					lowerResponse.indexOf('fatal error') !== -1 ||
					lowerResponse.indexOf('uncaught') !== -1 ||
					lowerResponse.indexOf('mysqli_sql_exception') !== -1 ||
					lowerResponse.indexOf('unable to connect with database') !== -1
				) {
					cleanResponse = 'Unable to connect with database';
				}

				console.log(cleanResponse);


				if (cleanResponse === "change_password") {
					$("#login_button").html('<img src="ajax-loader.gif" /> &nbsp; Redirecting ...');

					setTimeout(function () {
						window.location.href = "../admin/change_password.php";
					}, 500);

					return;
				}


				else if (cleanResponse == "ok" || cleanResponse == "ok1" || cleanResponse == "ok2" || cleanResponse == "ok3" || cleanResponse == "ok4") {
					$("#login_button").html('<img src="ajax-loader.gif" /> &nbsp; Signing In ...');
					var redirectUrl = "../admin/index.php";
					if (cleanResponse == "ok4") {
						redirectUrl = "../student/dashboard.php";
					} else if (cleanResponse == "ok2" || cleanResponse == "ok3") {
						redirectUrl = "../teacher/dashboard.php";
					}
					setTimeout(function () { window.location.href = redirectUrl; }, 1000);
				}
				else {
					$("#error").fadeIn(1000, function () {
						var $alert = $('<div class="alert alert-danger">');
						$('<span class="glyphicon glyphicon-info-sign">').appendTo($alert);
						$alert.append(document.createTextNode(' ' + cleanResponse + ' !'));
						$("#error").empty().append($alert);
						$("#login_button").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In');
					});
				}
			}
		}); return false;
	}
});