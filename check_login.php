<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];

    header('Location: signin.php');
    exit();
}
?>