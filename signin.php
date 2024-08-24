<?php
session_start();
require 'config.php';

$db = mysqli_connect('localhost', 'root', '', 'electionmvs');

if (!isset($_GET['code'])) {
    $_SESSION['state'] = bin2hex(random_bytes(8));
    $authorize_url = AUTHORITY . '/oauth2/v2.0/authorize?' . http_build_query([
        'client_id' => CLIENT_ID,
        'response_type' => 'code',
        'redirect_uri' => REDIRECT_URI,
        'response_mode' => 'query',
        'scope' => SCOPES,
        'state' => $_SESSION['state']
    ]);
    header('Location: ' . $authorize_url);
    exit();
} elseif (isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] === $_SESSION['state']) {
    $token_url = AUTHORITY . '/oauth2/v2.0/token';
    $response = file_get_contents($token_url, false, stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query([
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'code' => $_GET['code'],
                'redirect_uri' => REDIRECT_URI,
                'grant_type' => 'authorization_code',
            ])
        ]
    ]));
    $token_response = json_decode($response, true);
    $_SESSION['access_token'] = $token_response['access_token'];

    $graph_url = "https://graph.microsoft.com/v1.0/me";
    $user_response = file_get_contents($graph_url, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Authorization: Bearer ' . $_SESSION['access_token']
        ]
    ]));
    $user = json_decode($user_response, true);
    $userEmail = $user['mail']; 
    $displayName = $user['displayName'];
    $userName = $user['givenName'];
    $userID = $user['id'];

    $profile_pic_url = "https://graph.microsoft.com/v1.0/me/photo/\$value";
    $profile_pic_response = @file_get_contents($profile_pic_url, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Authorization: Bearer ' . $_SESSION['access_token']
        ]
    ]));

    if ($profile_pic_response !== false) {
        $profile_pic_data = base64_encode($profile_pic_response);
        $_SESSION['user_profile_pic'] = $profile_pic_data;
        file_put_contents('profile_pic.jpg', $profile_pic_response);
    } else {
        $_SESSION['user_profile_pic'] = null;
        error_log('Failed to fetch profile picture');
    }

    $qry1 = "SELECT * FROM users WHERE user_email = '$userEmail'";
    $results = mysqli_query($db, $qry1);
    if (mysqli_num_rows($results) == 1) {
        $_SESSION['userEmail'] = $userEmail;
        $_SESSION['displayName'] = $displayName;
        $_SESSION['userName'] = $userName;
        $_SESSION['user_profile_pic'] = $profile_pic_data;
        $_SESSION['userID'] = $user['id'];
        if (isset($_SESSION['userID'])) {
            header('Location: ' . ($_SESSION['redirect_to'] ?? 'homepage.php'));
            exit();
        }
    } else {
        $qry2 = "INSERT INTO users (user_id, user_profile, user_name, user_email, user_pw) VALUES ('$userID', '$profile_pic_data', '$displayName', '$userEmail', '')";
        mysqli_query($db, $qry2);
        $_SESSION['userEmail'] = $userEmail;
        $_SESSION['displayName'] = $displayName;
        $_SESSION['userName'] = $userName;
        $_SESSION['user_profile_pic'] = $profile_pic_data;
        $_SESSION['userID'] = $user['id'];
        header('location: register.php');
    }


} else {
    echo 'Invalid state or code';
}
?>
