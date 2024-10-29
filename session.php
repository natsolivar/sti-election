<?php
include 'db.php'; 

$timeoutDuration = 600; 

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
    $userID = $_SESSION['userID'];

    if ($userID) {
        $qry = "UPDATE users SET session_token = NULL WHERE user_id = '$userID'";

        if (mysqli_query($conn, $qry)) {
            session_unset();
            session_destroy();
            header("Location: index.php?timeout=true");
            exit();
        } else {
            echo "Error updating session token: " . mysqli_error($conn);
        }
    } else {
        echo "No user ID found in session.";
    }
}

$_SESSION['last_activity'] = time();
?>
