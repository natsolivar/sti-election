<?php
    include 'session.php';
    session_start();

    if (isset($_SESSION['userID'])) {
        header('Location: selection');
        exit();
    } 

    if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
        echo '<p>Your session has expired due to inactivity. Please log in again.</p>';
    } 
    if (isset($_SESSION['error_message'])) {
        $error_message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
    } else {
        $error_message = '';
    }

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
    <title>Login | EMVS</title>
    <style>

        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            height: 100vh;
        }

        .carousel {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: flex;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
        }

        .carousel-slide {
            flex: 0 0 100%;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            background-position: 0px 60px;
            scroll-snap-align: start;
        }

        .carousel-slide:nth-child(1) {
            background-image: url('assets/images/index-background2.jpg');
        }

        .carousel-slide:nth-child(2) {
            background-image: url('assets/images/index-background.jpg');
        }

        .carousel-slide:nth-child(3) {
            background-image: url('assets/images/index-background3.jfif');
        }

        .carousel-slide:nth-child(4) {
            background-image: url('assets/images/index-background4.jpg');
        }

        .site-header {
            padding: .5em 1em;
            background-color: #0079c2;
            width: 100%;
        }

        #top {
            background-color: #E4D00A;
            padding: 0.3em;
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
            max-width: 150px;
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
            max-width: 150px;
            height: auto;
            border-radius: 5px;
        }

        h2 {
            margin: 15px 0;
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

        .error-message {
            color: red;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 15px;
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
    <header class="site-header" id="top">
    </header>
    <header class="site-header">
        <div class="site-identity">
            <img src="assets/logo/sti-systems.png" alt="EMVS" /></a>
        </div>  
    </header>
    <div class="carousel">
        <div class="carousel-slide"></div>
        <div class="carousel-slide"></div>
        <div class="carousel-slide"></div>
        <div class="carousel-slide"></div>
    </div>
    <div class="login-container">
        <div class="logo">
            <img src="assets/logo/EMVS-LOGO.png" alt="STI Logo">
        </div>
        <h2>Login now</h2>
        <?php if ($error_message): ?>
            <div class="error-message" style="color: red; text-align: center; margin-bottom: 10px;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <button class="login-button" onclick="location.href='signin.php'">
            <img src="assets/logo/microsoft-logo.png" alt="Microsoft Logo" />
            <span>SIGN IN WITH YOUR STI O365 ACCOUNT</span>
        </button>
        <p style="font-size: 12px;"><a href="admincheck.php">Admin Login</a></p>
        <footer>
            <p>&copy; STI Education Services Group, Inc. All Rights Reserved.</p>
        </footer>
    </div>
<script>
    window.onload = function() {
        if (sessionStorage.getItem('loggedIn') === 'true') {
            window.location.href = 'homepage';
        }
    };

    let currentIndex = 0;
        const slides = document.querySelectorAll('.carousel-slide');
        const slideCount = slides.length;

        function showNextSlide() {
        currentIndex++;
        
        if (currentIndex >= slideCount) {
            currentIndex = 0; 
        }

        document.querySelector('.carousel').scrollTo({
            left: currentIndex * slides[0].clientWidth,
            behavior: 'smooth'
        });
        }

    setInterval(showNextSlide, 5000);
</script>
</body>
</html>
