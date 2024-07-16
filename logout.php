<?php
session_start();
session_unset();
session_destroy();

if (isset($_GET['backnav']) && $_GET['backnav'] == 'true') {
    $logoutUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode('http://yourapp.com/login.php?backnav=true');
} else {
    $logoutUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/logout?post_logout_redirect_uri=' . urlencode('http://yourapp.com/login.php');
}

header('Location: ' . $logoutUrl);
exit();

?>
