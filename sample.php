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

    function getGreeting() {
        $currentTime = new DateTime(null, new DateTimeZone('Asia/Hong_Kong'));
        $hour = $currentTime->format('G');
    
        if ($hour < 12) {
            return "Good morning ";
        } elseif ($hour <= 18) {
            return "Good afternoon ";
        } else {
            return "Good evening ";
        }
    }

    $greetingMessage = getGreeting();

    $qry1 = "SELECT u.user_name, v.program_code, v.voter_grade, c.candidate_id, c.candidate_details, p.party_name, pos.position_name, i.image
                                    FROM candidate c
                                    LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                    LEFT JOIN voters v ON c.voter_id = v.voter_id
                                    LEFT JOIN users u ON v.user_id = u.user_id
                                    LEFT JOIN party p ON c.party_code = p.party_code
                                    LEFT JOIN position pos ON c.position_id = pos.position_id
                                    ORDER BY RAND()
                                    LIMIT 1";
                            $result = $conn->query($qry1);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $c_id = $row['candidate_id'];
                                    $user_name = $row['user_name'];
                                    $details = $row['candidate_details'];
                                    $program = $row['program_code'];
                                    $img = $row['image'];
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
                                }
                            }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>EMVS</title>
    <style>
        @import url("sidebar_style.css");

        body {
            background-image: url("assets/images/background.jpg");
            overflow-y: auto;
        }

        .main-content {
            position: absolute;
            top: 0;
            left: 80px;
            transition: all 0.5s ease;
            width: calc(100% - 78px);
            display: flex;
            flex-wrap: wrap;
            padding-left: 0.5rem;
        }

        .sidebar.active ~ .main-content {
            width: calc(100% - 240px);
            left: 240px;
        }

        .main-content .item {
            min-height: 50px; 
            text-align: left;
            padding: 0.5rem;
            border-radius: 5px;
            margin: 5px;
            gap: 10px;
        }

        .main-content #item-1 {
            flex-grow: 1;
            width: 50%;
            height: 250px;
            margin-bottom: 50px;
        }

        .main-content #item-1 p {
            font-size: 30px;
        }

        .main-content #item-2{
            width: 40%;
            height: 250px;
        }

        .main-content #item-3 {
            flex-grow: 1;
            width: 50%;
            height: auto;
        }

        .main-content #item-3 h2 {
            font-size: 30px;
        }

        .main-content #item-4 {
            width: 40%;
            height: auto;
        }

        .main-content #item-5 {
            flex-grow: 1;
            width: 15%;
            padding: 0;
        }

        .main-content img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .main-content #item-6 {
            width: 35%;
            
        }

        .main-content #item-7 {
            width: 40%;
        }

        .sub-items {
            display: flex;
            min-height: 250px; 
            max-height: 350px;
            text-align: left;
            padding: 0;
            border-radius: 5px;
            margin: 5px;
            min-width: 100%;
        }

        .sub-items .sub-item img {
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .sub-items #item-8 {
            width: 50%;
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .sub-items #item-8 #sub-item1 {
            width: 50%;
            height: 100%;
        }

        .sub-items #item-8 #sub-item2 {
            width: 50%;
            height: 100%;
        }

        .sub-items #item-8 #sub-item2 h2 {
            font-size: 30px;
        }

        .sub-items #item-8 #sub-item2 .percentage {
            padding-top: 25px;
            font-size: 50px;
            text-align: center;
            font-weight: bold;
        }

        .sub-items #item-8 #sub-item2 .position {
            padding-top: 50px;
            align-items: center;
            font-weight: bold;
            font-size: 20px;
        }

        
        .calendar {
            width: 100%;
            border-radius: 5px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
            background: #0079c2;
            color: #fff;
        }

        .calendar-header button {
            background: none;
            border: none;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
        }

        .calendar-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            padding: 0.5rem;
        }

        .calendar-weekdays, .calendar-days {
            display: flex;
            flex-wrap: wrap;
        }

        .calendar-weekdays div, .calendar-days div {
            width: 14.28%;
            text-align: center;
            padding: 0.2rem 0;
            box-sizing: border-box;
        }

        .calendar-weekdays {
            border-bottom: 1px solid #ddd;
        }

        .calendar-days div {
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .calendar-days div:nth-child(7n) {
            border-right: none;
        }

        .today {
            background-color: yellow; 
        }


        


    </style>
</head>
    <body>
            <div class="main-content">
                <div class="item" id="item-1"><h1><b>
                <?php 
                    echo $greetingMessage;
                    echo htmlspecialchars($_SESSION['userName']); ?>!</b></h1>
                    <p>Election in <strong>? days</strong></p>
                </div>
                <div class="item" id="item-2">
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
                <div class="item" id="item-3">
                    <?php 

                        if ($party != NULL ) {

                            echo "<h2>Meet a candidate from <strong>$party</strong></h2>";

                        } else {

                            echo "<h2>Meet a candidate from <strong>INDEPENDENT</strong></h2>";

                        }
                    
                    
                    ?>
                </div>
                <div class="item" id="item-4"><h2><b>Platform</b></h2></div>
                <div class="item" id="item-5">
                    <?php 

                        if ($img) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img);
                            echo "<img src='$image_url' alt='Candidate image' >";
                        } else {
                            echo "<img src='assets/images/profile.png' alt='Image 1' alt='Candidate image' >";
                        }
                    
                    ?>
                </div>
                <div class="item" id="item-6">
                    <?php 

                        switch ($grade) {
                            case 'g11':
                                $ggrade = 'Grade 11';
                                break;
                            case 'g12':
                                $ggrade = 'Grade 12';
                                break;
                            default:
                                $ggrade = $grade;
                        }     

                        echo "<p align='left'>Hi students of STI College Iligan! My name is <strong>$formatted_name</strong>, a $ggrade $program student and I am currently running for the position of <strong>$position</strong>.</p>
                        <p align='left'>$details</p>";
                    
                    ?>
                </div>
                <div class="item" id="item-7">
                <?php 

                    $query = "SELECT platform FROM candidate WHERE candidate_id = '$c_id'";
                    $res = mysqli_query($conn, $query);

                    if ($res -> num_rows > 0 ) {
                        while ($row = $res -> fetch_assoc()) {
                            $plat = $row['platform'];

                            echo "<p align='left'>$plat</p>";
                        }
                    }



                    ?>
                </div> 
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                <div class="item active">
                    <div class="sub-items">
                    <?php 
                    
                        $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                FROM candidate c
                                LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                LEFT JOIN voters v ON c.voter_id = v.voter_id
                                LEFT JOIN users u ON v.user_id = u.user_id
                                LEFT JOIN party p ON c.party_code = p.party_code
                                LEFT JOIN position pos ON c.position_id = pos.position_id
                                LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                WHERE pos.position_id = 'PRES' ";
                        $res = mysqli_query($conn, $qry);

                        if ($res -> num_rows > 0 ) {
                            while ($rows = $res -> fetch_assoc()) {
                                $usern = $rows['user_name'];
                                $partyy = $rows['party_name'];
                                $position = $rows['position_name'];
                                $img1 = $rows['image'];
                                $tvotes = $rows['total_votes'];

                                $usern = str_replace("(Student)", "", $usern);
                                    $name_parts = explode(", ", trim($usern));
                                    if (count($name_parts) == 2) {
                                        $formatted_name1 = $name_parts[1] . " " . $name_parts[0];
                                    } else {
                                        $formatted_name1 = $user_name;
                                    }

                                $qry2 = "SELECT COUNT('candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                        $total_votes = ($tvotes * $overall) / 100 . "" .  '%';
                                    }
                                }

                                echo "<div class='item' id='item-8'>";
                                echo "<div class='sub-item' id='sub-item1'>";
                                if ($img1) {
                                    $image_url1 = 'data:image/jpeg;base64,' . base64_encode($img1);
                                    echo "<img src='$image_url1' alt='Candidate image' >";
                                } else {
                                    echo "<img src='assets/images/profile.png' alt='Image 1' alt='Candidate image' >";
                                }
                                echo "</div>";
                                echo "<div class='sub-item' id='sub-item2'><h2><strong>$formatted_name1</strong></h2>";
                                echo "<div class='detail'><p>";
                                        if ($partyy != NULL ) {

                                            echo $partyy;
                
                                        } else {
                
                                            echo "INDEPENDENT";
                
                                        }
                                echo "</p></div>";
                                echo "<div class='percentage'>
                                            <p>$total_votes</p>
                                        </div>";
                                echo "<div class='position'>
                                            <p>$position</p>
                                        </div>";
                                echo "</div>";
                                echo "</div>";
                                



                            }
                        }

                    
                    
                    
                    ?>
                </div>
                </div>
                <div class="item">
                <div class="sub-items">
                    <?php 
                    
                        $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                FROM candidate c
                                LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                LEFT JOIN voters v ON c.voter_id = v.voter_id
                                LEFT JOIN users u ON v.user_id = u.user_id
                                LEFT JOIN party p ON c.party_code = p.party_code
                                LEFT JOIN position pos ON c.position_id = pos.position_id
                                LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                WHERE pos.position_id = 'TERVP'";
                        $res = mysqli_query($conn, $qry);

                        if ($res -> num_rows > 0 ) {
                            while ($rows = $res -> fetch_assoc()) {
                                $usern = $rows['user_name'];
                                $partyy = $rows['party_name'];
                                $position = $rows['position_name'];
                                $img1 = $rows['image'];
                                $tvotes = $rows['total_votes'];

                                $usern = str_replace("(Student)", "", $usern);
                                    $name_parts = explode(", ", trim($usern));
                                    if (count($name_parts) == 2) {
                                        $formatted_name1 = $name_parts[1] . " " . $name_parts[0];
                                    } else {
                                        $formatted_name1 = $user_name;
                                    }

                                $qry2 = "SELECT COUNT('candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                        $total_votes = ($tvotes * $overall) / 100 . "" .  '%';
                                    }
                                }

                                echo "<div class='item' id='item-8'>";
                                echo "<div class='sub-item' id='sub-item1'>";
                                if ($img1) {
                                    $image_url1 = 'data:image/jpeg;base64,' . base64_encode($img1);
                                    echo "<img src='$image_url1' alt='Candidate image' >";
                                } else {
                                    echo "<img src='assets/images/profile.png' alt='Image 1' alt='Candidate image' >";
                                }
                                echo "</div>";
                                echo "<div class='sub-item' id='sub-item2'><h2><strong>$formatted_name1</strong></h2>";
                                echo "<div class='detail'><p>";
                                        if ($partyy != NULL ) {

                                            echo $partyy;
                
                                        } else {
                
                                            echo "INDEPENDENT";
                
                                        }
                                echo "</p></div>";
                                echo "<div class='percentage'>
                                            <p>$total_votes</p>
                                        </div>";
                                echo "<div class='position'>
                                            <p>$position</p>
                                        </div>";
                                echo "</div>";
                                echo "</div>";
                                



                            }
                        }

                    
                    
                    
                    ?>
                    </div>
                    </div>
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
    </body>
    <script type="text/javascript" src="script.js"></script>
</html>