<?php
session_start();
include 'db.php';

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $qry = "UPDATE users SET session_token = NULL WHERE user_id = '$userID'";

    if (mysqli_query($conn, $qry)) {
        session_unset();
        session_destroy();

        if (isset($_GET['backnav']) && $_GET['backnav'] == 'true') {
            $logoutUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode('http://localhost/Capstone/EMVS/?backnav=true');
        } else {
            $logoutUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode('http://localhost/Capstone/EMVS/');
        }

        header('Location: ' . $logoutUrl);
        exit();
    } else {
        echo "Error updating session token: " . mysqli_error($conn);
    }
} else {
    if (isset($_GET['backnav']) && $_GET['backnav'] == 'true') {
        $logoutUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode('http://localhost/Capstone/EMVS/?backnav=true');
    } else {
        $logoutUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode('http://localhost/Capstone/EMVS/');
    }

    header('Location: ' . $logoutUrl);
    exit();
}
?>
