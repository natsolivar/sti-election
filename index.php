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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <title>Login | EMVS</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: url("assets/images/index-background2.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-position: 0px 60px;
            font-family: Arial, sans-serif;
        }

        .site-header {
            padding: .5em 1em;
            background-color: #0079c2;
            width: 100%;
        }

        .site-header::after {
            content: "";
            display: table;
            clear: both;
        }

        .site-identity {
            display: flex;
            justify-content: flex-start;
        }

        .site-identity h1 {
            font-size: 1.5em;
            margin: .7em 0 .3em 0;
        }

        .site-identity img {
            max-width: 55px;
            margin-right: 10px;
            border-radius: 5px;
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
            width: 350px;
        }

        .logo img {
            max-width: 55px;
            height: auto;
            border-radius: 5px;
        }

        h2 {
            margin: 20px 0;
            font-size: 1.5em;
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
            font-size: 0.8em;
            color: #777;
        }

        .modal {
    display: block; 
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-content p {
            font-size: 20px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }


        @media (max-width: 768px) {
            .site-identity {
                flex-direction: column;
                align-items: center;
            }

            .site-identity img {
                margin: 0 auto 10px auto;
            }

            .login-container {
                top: 30vh;
                right: 50%;
                transform: translateX(50%);
            }
        }

        @media (max-width: 365px) {
            .site-identity {
                flex-direction: column;
                align-items: center;
            }

            .site-identity img {
                margin: 0 auto 10px auto;
            }

            .login-container {
                top: 30vh;
                right: 50%;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="site-identity">
            <a onclick="window.open('admincheck.php')"><img src="assets/logo/STI-LOGO.png" alt="EMVS" /></a>
            <h1>Election Management and Voting System</h1>
        </div>  
    </header>
    <div class="login-container">
        <div class="logo">
            <img src="assets/logo/STI-LOGO.png" alt="STI Logo">
        </div>
        <h2>Login now</h2>
        <button class="login-button" onclick="location.href='signin.php'">
            <img src="assets/logo/microsoft-logo.png" alt="Microsoft Logo" />
            <span>SIGN IN WITH YOUR STI O365 ACCOUNT</span>
        </button>
        <footer>
            <p>&copy; STI Education Services Group, Inc. All Rights Reserved.</p>
        </footer>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Announcement</h2>
            <p>We're excited to share our system <strong>Election Management and Voting System</strong> with you, but please note that it's still in its early stages of development. The design, layout, and content may evolve as we continue to refine the user experience.</p>
            <p>For the most optimal viewing experience, we recommend using a desktop or laptop computer. While we're working on making our system fully mobile-friendly, there may be some limitations or visual inconsistencies on smaller screens at this time.</p>
            <p>Thank you for your understanding and patience.</p>
        </div>
    </div>
    <script>
        var modal = document.getElementById('myModal');
        var span = document.getElementsByClassName('close')[0];

        span.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
