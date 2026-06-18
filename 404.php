<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>404 Page Not Found</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- This handles the actual browser redirection -->
    <meta http-equiv="refresh" content="3;url=index.php" />
    
    <style>
        body { background-color: #f4f4f7; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .error-container { margin-top: 8%; }
        .error-code { font-size: 80px; font-weight: bold; color: #423cbc; line-height: 1; }
        .error-text { font-size: 24px; color: #555; margin-bottom: 20px; }
        #countdown { font-weight: bold; color: #dd4b39; font-size: 1.2em; }
    </style>
</head>
<body>

<div class="container text-center error-container">
    <!-- Your SVG Icon -->
    <svg fill="#423cbc" height="150px" width="150px" viewBox="0 0 60 60" style="margin-bottom: 20px;">
        <g>
            <path d="M9,39h6v8c0,0.552,0.448,1,1,1s1-0.448,1-1v-8h3c0.552,0,1-0.448,1-1s-0.448-1-1-1h-3v-2c0-0.552-0.448-1-1-1s-1,0.448-1,1 v2h-5V27c0-0.552-0.448-1-1-1s-1,0.448-1,1v11C8,38.552,8.448,39,9,39z"></path>
            <path d="M40,39h6v8c0,0.552,0.448,1,1,1s1-0.448,1-1v-8h3c0.552,0,1-0.448,1-1s-0.448-1-1-1h-3v-2c0-0.552-0.448-1-1-1 s-1,0.448-1,1v2h-5V27c0-0.552-0.448-1-1-1s-1,0.448-1,1v11C39,38.552,39.448,39,40,39z"></path>
            <path d="M29.5,48c3.584,0,6.5-2.916,6.5-6.5v-9c0-3.584-2.916-6.5-6.5-6.5S23,28.916,23,32.5v9C23,45.084,25.916,48,29.5,48z M25,32.5c0-2.481,2.019-4.5,4.5-4.5s4.5,2.019,4.5,4.5v9c0,2.481-2.019,4.5-4.5,4.5S25,43.981,25,41.5V32.5z"></path>
            <path d="M0,0v14v46h60V14V0H0z M2,2h56v10H2V2z M58,58H2V14h56V58z"></path>
        </g>
    </svg>

    <div class="error-code">404</div>
    <div class="error-text">
        <i class="fa fa-warning" style="color: #f39c12;"></i> Oops! Page not found.
    </div>

    <p style="font-size: 16px;">
        We couldn't find what you were looking for.<br>
        Redirecting to dashboard in <span id="countdown">3</span> seconds...
    </p>

    <a href="index.php" class="btn btn-primary btn-flat" style="margin-top: 20px; background-color: #423cbc; border: none; padding: 10px 20px;">
        <i class="fa fa-dashboard"></i> Return to Home
    </a>
</div>

<script>
    // Visual countdown logic
    let seconds = 3;
    const display = document.getElementById('countdown');

    const timer = setInterval(() => {
        seconds--;
        display.textContent = seconds;
        if (seconds <= 0) clearInterval(timer);
    }, 1000);
</script>

</body>
</html>