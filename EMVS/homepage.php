<?php 
session_start();
include 'sidebar.php';
require 'config.php';

if (!isset($_SESSION['access_token'])) {
    header('Location: signin.php');
    exit();
} 

$user_info_url = 'https://graph.microsoft.com/v1.0/me';
$response = file_get_contents($user_info_url, false, stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Authorization: Bearer ' . $_SESSION['access_token']
    ]
]));
$_SESSION['user_info'] = json_decode($response, true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
</head>
<body>
            <div class="main-content">
                <div class="box" id="box1"><h1>Hi <b><?php echo htmlspecialchars($_SESSION['user_info']['givenName']); ?>!</b></h1>
                <p>Election in <strong>? days</strong></p></div>
                <div class="box" id="box2">
                <div class="calendar">
                    <div class="calendar-header">
                        <button id="prev">&lt;</button>
                        <div id="month-year"></div>
                        <button id="next">&gt;</button>
                    </div>
                    <div class="calendar-body">
                        <div class="calendar-weekdays">
                            <div>Sun</div>
                            <div>Mon</div>
                            <div>Tue</div>
                            <div>Wed</div>
                            <div>Thu</div>
                            <div>Fri</div>
                            <div>Sat</div>
                        </div>
                        <div id="calendar-days" class="calendar-days"></div>
                    </div>
                </div>
                </div>
                <div class="box" id="box3">3</div>
             </div>
        <script type="text/javascript" src="script.js"></script>
</body>
</html>