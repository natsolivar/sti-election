<?php 
    session_start();
    include 'db.php';

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    
        $query = "SELECT * FROM `e-officials` WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);
    
        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            if (isset($_SESSION['username'])) {
                header('Location: ' . ($_SESSION['redirect_to'] ?? 'index.php'));
                exit();
            }
        } else {
            $error = "Incorrect username or password.";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <title>EMVS</title>
    </head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        body {
            font-family: "Poppins" , sans-serif;
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;
            border: 1px groove black; 
            max-width: 360px;
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-container .password-container {
            position: relative;
        }
        .login-container .password-container .show-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            opacity: 0.6;
        }
        .login-container a {
            text-decoration: none;
            color: #5a5a5a;
            display: block;
            margin-bottom: 20px;
            text-align: right;
        }
        .login-container button {
            width: 100%;
            background-color: #0079c2;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-container .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
    <body>
    <div class="login-container">
        <h1 style="text-align: center;">EMVS | Admin</h1>
    <h2>LOG IN</h2>
    <form action="admin-login.php" method="POST">
        <input type="text" name="username" placeholder="Username*" required>
        <div class="password-container">
            <input type="password" name="password" placeholder="Password*" required>
        </div>
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <a href="#"></a>
        <button type="submit">Log In</button>
    </form>
</div>

    </body>
</html>