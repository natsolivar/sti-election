<?php

    if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
        echo '<p>Your session has expired due to inactivity. Please log in again.</p>';
    } 

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <title>Login | EMVS</title>
    <style>

        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-image: url("assets/images/index-background2.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-position: 0px 60px;
        }

        .site-header { 
            padding: .5em 1em;
            background-color: #0079c2;
        }

        .site-header::after {
            content: "";
            display: table;
            clear: both;
        }

        .site-identity {
            float: left;
        }

        .site-identity h1 {
            font-size: 1.5em;
            margin: .7em 0 .3em 0;
            display: inline-block;
        }

        .site-identity img {
            max-width: 55px;
            float: left;
            margin: 0 10px 0 0;
        }
        .login-container {
            background-color: #DBE9F4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: absolute;
            top: 30vh;
            right: 10vh;
        }

        .logo img {
            max-width: 20%;
            height: auto;
        }

        h2 {
            margin: 20px 0;
        }

        .login-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            margin: 10px auto;
            width: calc(100% - 40px); 
        }

        .login-button img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .login-button:hover {
            background-color: #c0392b;
        }

        p {
            margin: 10px 0;
            font-size: 14px;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }

    </style>
    </head>
    <body>
        <header class="site-header">
        <div class="site-identity">
            <a href="#"><img src="assets/logo/STI-LOGO.png" alt="Site Name" /></a>
            <h1>Election Management and Voting System</h1>
        </div>  
        </header>
        <div class="login-container">
            <div class="logo">
                <img src="assets/logo/STI-LOGO.png" alt="STI Logo">
            </div>
            <h2>Login now</h2>
                <button class="login-button" onclick="location.href='signin.php'"><img src="assets/logo/microsoft-logo.png" alt="Microsoft Logo" /><span>SIGN IN WITH YOUR STI O365 ACCOUNT</span></button>
            <footer>
                <p>&copy; STI Education Services Group, Inc. All Rights Reserved.</p>
            </footer>
        </div>
    </body>
</html>