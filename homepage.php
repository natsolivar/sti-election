<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';
    require 'config.php';


    function getGreeting() {
        $currentTime = new DateTime(null, new DateTimeZone('Asia/Hong_Kong'));
        $hour = $currentTime->format('G');
    
        if ($hour < 12) {
            return "Good morning, ";
        } elseif ($hour <= 18) {
            return "Good afternoon, ";
        } else {
            return "Good evening, ";
        }
    }

    $que = "SELECT date_start, date_end FROM c_period ORDER BY id DESC LIMIT 1";
    $res = mysqli_query($conn, $que);

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $date_start = $row['date_start'];
        $date_end = $row['date_end'];
    } else {
        $date_start = $date_end = null;
    }

    $currentDate = new DateTime("now", new DateTimeZone('Asia/Hong_Kong'));
    $startDate = new DateTime($date_start, new DateTimeZone('Asia/Hong_Kong'));
    $endDate = new DateTime($date_end, new DateTimeZone('Asia/Hong_Kong'));


    $daysRemaining = $currentDate->diff($startDate)->days;

    $greetingMessage = getGreeting();

    $que = "SELECT date_start, date_end FROM e_period LIMIT 1";
    $results = mysqli_query($conn, $que);

    if ($results->num_rows > 0) {
        $row = $results->fetch_assoc();
        $date_start = $row['date_start'];
        $date_end = $row['date_end'];
    } else {
        $date_start = $date_end = null;
    }

    $qry1 = "SELECT u.user_name, v.program_code, v.voter_grade, c.candidate_id, c.candidate_details, p.party_name, pos.position_name, i.image
            FROM candidate c
            LEFT JOIN images i ON c.candidate_id = i.candidate_id
            LEFT JOIN voters v ON c.voter_id = v.voter_id
            LEFT JOIN users u ON v.user_id = u.user_id
            LEFT JOIN party p ON c.party_code = p.party_code
            LEFT JOIN position pos ON c.position_id = pos.position_id
            WHERE c.status = 'Accepted'
            LIMIT 1";
        $resultss = $conn->query($qry1);

        if ($resultss->num_rows > 0) {
        while ($row = $resultss->fetch_assoc()) {
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="styles/style.css?v=<?php echo time(); ?>">
    <title>EMVS</title>
</head>
<body>
            <div class="main-content">
                <div class="item" id="item-1"><h1><b>
                <?php 
                    echo $greetingMessage;
                    echo htmlspecialchars($_SESSION['userName']); ?>!</b></h1>
                    <?php 
                    
                    $query = "SELECT 
                                CASE 
                                    WHEN CURDATE() < date_start THEN DATEDIFF(date_start, CURDATE()) 
                                    WHEN CURDATE() BETWEEN date_start AND date_end THEN DATEDIFF(date_end, CURDATE()) 
                                    ELSE 0 
                                END AS days_remaining,
                                date_start,
                                date_end
                            FROM e_period
                            LIMIT 1"; 
                            $result = mysqli_query($conn, $query);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $days_remaining = $row['days_remaining'];

                                if ($currentDate < $startDate) {
                                    echo "<p>Registration of Candidacy starts in <strong>{$daysRemaining} days</strong>";
                                } 

                                if (strtotime($row['date_start']) > time()) {
                                    echo "<p>Voting starts in <strong>{$days_remaining} days</strong></p>";
                                } elseif (strtotime($row['date_start']) <= time() && strtotime($row['date_end']) >= time()) {
                                    echo "<p>Voting ends in <strong>{$days_remaining} days</strong></p>";
                                } else {
                                    echo "<p>The election has ended.</p>";
                                }
                            } else {
                                echo "<p>Election in ? days</p>";
                            }
                    ?>
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
                <?php
                    $currentDate = new DateTime("now", new DateTimeZone('Asia/Hong_Kong'));
                    $startDate = new DateTime($date_start, new DateTimeZone('Asia/Hong_Kong'));
                    $endDate = new DateTime($date_end, new DateTimeZone('Asia/Hong_Kong'));

                    if ($currentDate < $startDate || $currentDate > $endDate) {
                        if ($resultss -> num_rows > 0) {
                        ?>
                        <div class="item" id="item-3">
                            <?php 
                                echo "<h2>Meet a candidate from <strong>$party</strong></h2>";
                            ?>
                        </div>
                        <div class="item" id="item-4"><h2><b>PLATFORM</b></h2></div>
                        <div class="item" id="item-5">
                            <?php 
                                if ($img) {
                                    $image_url = 'data:image/jpeg;base64,' . base64_encode($img);
                                    echo "<img src='$image_url' alt='Candidate image'>";
                                } else {
                                    echo "<img src='assets/images/profile.png' alt='Image 1' alt='Candidate image'>";
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

                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        $plat = $row['platform'];
                                        echo "<p align='left'>$plat</p>";
                                    }
                                }
                            } else {
                                echo "<h2 style='text-align: center;'>NO CANDIDATES YET</h2>";
                            }
                            ?>
                        </div>
                        <?php
                    } else {
                       ?>
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR PRESIDENT</h2>
                        <div class="sub-items">
                            <?php 

                                $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                    }
                                }
                            
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'PRES' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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
                                        
                                        $total_votes = ($tvotes * 100) / $overall;
                                        $total_votes = number_format($total_votes, 2) . '%';


                                        echo "<div class='item' id='item-8'>";
                                        echo "<div class='sub-item' id='sub-item1'>";
                                        if ($img1) {
                                            $image_url1 = 'data:image/jpeg;base64,' . base64_encode($img1);
                                            echo "<img src='$image_url1' alt='Candidate image' >";
                                        } else {
                                            echo "<img src='assets/images/profile.png' alt='Candidate image' >";
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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR TERTIARY VICE PRESIDENT</h2>
                        <div class="sub-items">
                            <?php 
                            
                                $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                    }
                                }
                            
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'TERVP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';


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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR SENIOR HIGH VICE PRESIDENT</h2>
                        <div class="sub-items">
                            <?php 

                                    $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                    $ress = mysqli_query($conn, $qry2);

                                    if ($ress -> num_rows > 0 ) {
                                        while ($rowss = $ress -> fetch_assoc()) {
                                            $overall = $rowss['total'];

                                        }
                                    }
                            
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'SHVP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR INTERNAL SECRETARY</h2>
                        <div class="sub-items">
                            <?php 

                                $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                    }
                                }

                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'INTSEC' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                        $total_votes = ($tvotes * 100) / $overall;
                                        $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR EXTERNAL SECRETARY</h2>
                        <div class="sub-items">
                            <?php 
                            
                                $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                    }
                                }

                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'EXTSEC' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR TREASURER</h2>
                        <div class="sub-items">
                            <?php 

                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }

                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'TREA' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR AUDITOR</h2>
                        <div class="sub-items">
                            <?php 

                                $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                    }
                                }
                            
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'AUD' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR PUBLIC INFORMATION OFFICER</h2>
                        <div class="sub-items">
                            <?php 

                                $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                $ress = mysqli_query($conn, $qry2);

                                if ($ress -> num_rows > 0 ) {
                                    while ($rowss = $ress -> fetch_assoc()) {
                                        $overall = $rowss['total'];

                                    }
                                }
                            
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'PIO' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 11 ABM REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '11ABMREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 11 HUMSS REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '11HUMSSREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 11 STEM REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '11STEMREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 11 CUART REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '11CUARTREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 11 MAWD REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '11MAWDREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 12 ABM REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '12ABMREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 12 HUMSS REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '12HUMSSREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 12 STEM REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '12STEMREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 12 CUART REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '12CUARTREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR GRADE 12 MAWD REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = '12MAWDREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSTM 1 REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSTM1AREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSTM 2-A REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSTM2AREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSTM 2-B REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSTM2BREP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSTM 3 REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSTM3REP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSTM 4 REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSTM4REP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSIS 1 REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSIS1REP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSIS 2 REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSIS2REP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSIS 3 REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSIS3REP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                    <div class="carousel-item">
                    <h2 style="font-size: 30px; font-weight: bold; text-align:center;">FOR BSIS 4 REPRESENTATIVE</h2>
                        <div class="sub-items">
                            <?php 
                                 $qry2 = "SELECT COUNT(DISTINCT 'candidate_id') AS total FROM candidate";
                                 $ress = mysqli_query($conn, $qry2);
 
                                 if ($ress -> num_rows > 0 ) {
                                     while ($rowss = $ress -> fetch_assoc()) {
                                         $overall = $rowss['total'];
 
                                     }
                                 }
                                $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                        FROM candidate c
                                        LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                        LEFT JOIN voters v ON c.voter_id = v.voter_id
                                        LEFT JOIN users u ON v.user_id = u.user_id
                                        LEFT JOIN party p ON c.party_code = p.party_code
                                        LEFT JOIN position pos ON c.position_id = pos.position_id
                                        LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                        WHERE pos.position_id = 'BSIS4REP' AND c.status = 'Accepted'
                                        GROUP BY c.candidate_id";
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

                                            $total_votes = ($tvotes * 100) / $overall;
                                            $total_votes = number_format($total_votes, 2) . '%';

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
                                        echo "</div>";
                                        echo "</div>";
                                        



                                    }
                                }    
                            
                            ?>
                        </div>
                    </div>
                </div>
                </div>
            
                       <?php
                    }
                    ?>  
        </div>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendarDays = document.getElementById('calendar-days');
            const monthYear = document.getElementById('month-year');
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');

            let date = new Date();

            const electionStart = new Date("<?php echo $date_start; ?>T00:00:00+08:00");
            const electionEnd = new Date("<?php echo $date_end; ?>T23:59:59+08:00");

            function renderCalendar() {
                calendarDays.innerHTML = '';
                const month = date.getMonth();
                const year = date.getFullYear();

                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const firstDay = new Date(year, month, 1).getDay();
                const lastDate = new Date(year, month + 1, 0).getDate();

                monthYear.innerHTML = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

                for (let i = 0; i < firstDay; i++) {
                    const emptyDiv = document.createElement('div');
                    calendarDays.appendChild(emptyDiv);
                }

                for (let i = 1; i <= lastDate; i++) {
                    const dayDiv = document.createElement('div');
                    dayDiv.textContent = i;

                    const currentDate = new Date(year, month, i);
                    currentDate.setHours(0, 0, 0, 0); 

                    if (currentDate >= electionStart && currentDate <= electionEnd) {
                        dayDiv.classList.add('election-period');
                    }

                    if (currentDate.getTime() === today.getTime()) {
                        dayDiv.classList.add('today');
                    }

                    calendarDays.appendChild(dayDiv);
                }
            }

            prevButton.addEventListener('click', () => {
                date.setMonth(date.getMonth() - 1);
                renderCalendar();
            });

            nextButton.addEventListener('click', () => {
                date.setMonth(date.getMonth() + 1);
                renderCalendar();
            });

            renderCalendar();
        });

    </script>
</html>