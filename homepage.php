<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';
    require 'config.php';

    if (!isset($_SESSION['access_token'])) {
        header('Location: signin.php');
        exit();
        
    } 

?>

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
                        <?php
                            $qry1 = "SELECT u.user_name, v.program_code, v.voter_grade, c.candidate_img1, c.candidate_img2, c.candidate_details, p.party_name, pos.position_name FROM candidate c 
                            JOIN voters v ON c.voter_id = v.voter_id 
                            JOIN users u ON v.user_id = u.user_id 
                            JOIN party p ON c.party_code = p.party_code
                            JOIN position pos ON c.position_id = pos.position_id
                            WHERE v.voter_id = (SELECT voter_id FROM voters WHERE user_id = '".$_SESSION['userID']."')";
                            $result = $conn->query($qry1);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $user_name = $row['user_name'];
                                    $details = $row['candidate_details'];
                                    $img1 = $row['candidate_img1'];
                                    $img2 = $row['candidate_img2'];
                                    $program = $row['program_code'];
                                    $grade = $row['voter_grade'];
                                    $party = $row['party_name'];
                                    $position = $row['position_name'];

                                    $user_name = str_replace("(Student)", "", $user_name);
                                    $name_parts = explode(", ", trim($user_name));
                                    if (count($name_parts) == 2) {
                                        $formatted_name = $name_parts[1] . " " . $name_parts[0];
                                    } else {
                                        $formatted_name = $user_name;
                                    }

                                    echo "<section class='candidates'>
                                    <h2>Meet a candidate from <strong>$party</strong></h2>
                                    <div class='candidate'>";

                                    if ($img1) {
                                        echo "<img src='$img1' alt='Candidate image' >";
                                    }

                                    echo "<div class='candidate-info'>
                                    <p>Hi students of STI College Iligan! My name is <strong>$formatted_name</strong>, a $grade $program student and I am currently running for the position of $position.</p>
                                    <p>$details</p>
                                    </div>";
                                    
                                }
                            } else {
                                echo "No candidate found.";
                            }

                            $conn->close();
                            ?>
                        </div>
                    </section></div>
            </div>
    </body>
    <script type="text/javascript" src="script.js"></script>
</html>