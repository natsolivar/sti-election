<?php
session_start();
require 'config.php';

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

    header('Location: homepage.php');
    exit();
} else {
    echo 'Invalid state or code';
}
?>
