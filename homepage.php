<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    require 'config.php';

    if (!isset($_SESSION['access_token'])) {
        header('Location: signin.php');
        exit();
        
    } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/style.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
</head>
<body>
            <div class="main-content">
                <div class="box" id="box1"><h1>Hi <b><?php echo htmlspecialchars($_SESSION['userName']); ?>!</b></h1>
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
                <div class="box" id="box3">
                    <section class="candidates">
                        <h2>Meet a candidate from <strong>DAGAN Partylist</strong></h2>
                        <div class="candidate">
                            <img src="assets/images/sample-images.png" alt="Candidate Photo">
                            <div class="candidate-info">
                                <p>Hi students of STI College Iligan! My name is <strong>Juan To</strong>, and I am running for the position of president.</p>
                                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Inventore soluta ullam, saepe itaque velit harum sunt consequatur, ex natus nam est earum necessitatibus. Veritatis consequuntur dolorum iste, assumenda reprehenderit alias obcaecati. Quas autem cupiditate, quo voluptas laboriosam totam qui beatae vitae officiis ab quasi et numquam placeat praesentium ex sapiente.</p>
                            </div>
                        </div>
                    </section></div>
             </div>
        <script type="text/javascript" src="script.js">
        </script>
</body>
</html>