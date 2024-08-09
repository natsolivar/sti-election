<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


if (!isLoggedIn()) {

    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    header('Location: index.php');
    exit();
}
?>