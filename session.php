<?php

    $timeoutDuration = 600; 

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeoutDuration) {
        session_unset();
        session_destroy();
        header("Location: index.php?timeout=true");
        exit();
    }
    
    $_SESSION['last_activity'] = time();

?>