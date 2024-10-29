<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';
    require 'config.php';
    
    $que = "SELECT date_start, date_end FROM c_period ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $que);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $date_start = $row['date_start'];
        $date_end = $row['date_end'];
    } else {
        $date_start = $date_end = null;
    }

    $currentDate = new DateTime("now", new DateTimeZone('Asia/Hong_Kong'));
    $startDate = new DateTime($date_start, new DateTimeZone('Asia/Hong_Kong'));
    $endDate = new DateTime($date_end, new DateTimeZone('Asia/Hong_Kong'));


    $daysRemaining = $currentDate->diff($startDate)->days;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
    <style>
        body {
            overflow-y: auto;
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            grid-template-columns: auto 1fr;
        }
        .card {
            padding: 2em;
            text-align: center;
            border: 1px solid black;
            border-radius: 5%;
        }
        
        .main {
            padding: 2em;
            justify-content: center;
        }

        .header {
            padding: 1em;
            padding-bottom: 2em;
        }
        
        .main .grid-container {
            display: grid;
            grid-template-columns: repeat(2, auto);
            justify-content: center;
            gap: 1.5em;
        }

        .main .grid-container #card-1 {
            min-width: 250px;
            max-width: 250px;
        }

        .main .grid-container #card-2 {
            min-width: 600px;
        }
        
        .main .grid-container #card-2 table th, td {
            font-size: 15px;
            padding: 5px 10px;
            text-align: left;
        }

        .main .grid-container #profile {
            margin-bottom: 30px;
        }

        .main .grid-container #profile img {
            width: 70%;
            height: auto;
            object-fit: cover;
            border-radius: 100%;
        }
    </style>
    <body>
    <div class="main">
        <div class="header">Header1</div>
        <div class="grid-container">
            <div class="card" id="card-1">
                <div class="min-card" id="profile">
                    <?php if ($userProfilePic): ?>
                        <img src="data:image/jpeg;base64,<?php echo $userProfilePic; ?>" alt="Profile Picture" class="profile-pic" onclick="location.href='profile.php'">
                    <?php endif; ?>
                </div>
                <div class="min-card" id="name">
                    <div class="name">
                        <p><?php echo htmlspecialchars($_SESSION['displayName']); ?></p>
                    </div>
                    <div class="program">
                        <p>
                            <?php 
                            $qry1 = "SELECT voter_grade FROM voters WHERE user_id = '$_SESSION[userID]'";
                            $results = mysqli_query($conn, $qry1);
                            if ($results->num_rows > 0) { 
                                while ($row = $results->fetch_assoc()) {
                                    if ($row['voter_grade'] == 'g11') {
                                        echo "Grade 11" . "<br>";
                                    } elseif ($row['voter_grade'] == 'g12') {
                                        echo "Grade 12" . "<br>";
                                    } else {
                                        echo $row['voter_grade'];
                                    }
                                    
                                }
                            } else {
                                echo "NO DATA";
                            }
                            ?>
                        </p>
                        <p>
                            <?php 
                            $qry2 = "SELECT program_code FROM voters WHERE user_id = '$_SESSION[userID]'";
                            $results = mysqli_query($conn, $qry2);
                            if ($results->num_rows > 0) { 
                                while ($row = $results->fetch_assoc()) {
                                    echo $row['program_code'] . "<br>";
                                }
                            } else {
                                echo "NO DATA";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card" id="card-2">
                <div class="min-card" id="details">
                <table>
                    <tr>
                        <th width="30%">Voter ID</th>
                        <td width="2%">:</td>
                        <td><?php 
                         $qry3 = "SELECT voter_id FROM voters WHERE user_id = '$_SESSION[userID]'";
                         $results = mysqli_query($conn, $qry3);
                         if ($results->num_rows > 0) { 
                             while ($row = $results->fetch_assoc()) {
                                 echo $row['voter_id'] . "<br>";
                             }
                         } else {
                             echo "NO DATA";
                         }
                         ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Gender</th>
                        <td width="2%">:</td>
                        <td><?php 
                        $qry5 = "SELECT voter_gender FROM voters WHERE user_id = '$_SESSION[userID]'";
                        $results = mysqli_query($conn, $qry5);
                        if ($results->num_rows > 0) { 
                           while ($row = $results->fetch_assoc()) {
                               echo $row['voter_gender'] . "<br>";
                           }
                        } else {
                           echo "NO DATA";
                       }
                       ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Club</th>
                        <td width="2%">:</td>
                        <td><?php 
                        $qry6 = "SELECT voter_club FROM voters WHERE user_id = '$_SESSION[userID]'";
                        $results = mysqli_query($conn, $qry6);
                        if ($results->num_rows != NULL) { 
                           while ($row = $results->fetch_assoc()) {
                               echo $row['voter_club'] . "<br>";
                           }
                        } else {
                           echo "N/A";
                       }
                       ?></td>
                    </tr>
                    <tr>
                        <th width="30%">Vote Status</th>
                        <td width="2%">:</td>
                        <td><?php 
                        $qry6 = "SELECT vote_status FROM voters WHERE user_id = '$_SESSION[userID]'";
                        $results = mysqli_query($conn, $qry6);
                        if ($results->num_rows > 0) { 
                           while ($row = $results->fetch_assoc()) {
                               echo $row['vote_status'] . "<br>";
                           }
                        } else {
                           echo "NO DATA";
                       }?></td>
                    </tr>
                    <tr>
                        <th width="30%">Candidacy</th>
                        <td width="2%">:</td>
                        <td><?php 
                        $qry7 = "SELECT voter_id FROM candidate WHERE voter_id = (SELECT voter_id FROM voters WHERE user_id = '".$_SESSION['userID']."')";
                        $results = mysqli_query($conn, $qry7);
                        if ($results->num_rows > 0) {
                            while ($row = $results -> fetch_assoc()) {
                                $voter_id = $row['voter_id'];
                                $qry8 = "SELECT p.position_name FROM candidate c JOIN position p ON c.position_id = p.position_id WHERE c.voter_id = '$voter_id'";
                                $result = mysqli_query($conn, $qry8);
                                while ($row1 = $result->fetch_assoc()) {
                                    echo $row1['position_name'] . "<br>";
                                }
                            }
                            
                        } else {

                            if ($currentDate <= $startDate) {
                                echo "Registration of Candidacy starts in {$daysRemaining} day/s";
                            } elseif ($currentDate > $endDate) {
                                echo "Candidacy period has ended.";
                            } else { 
                                echo "<a href='candidate_registration.php'>Register Candidacy</a>";
                            }
                        } 
                        
                        ?></td>

                    </tr>
                    <tr>
                        <th width="30%">Academic Year</th>
                        <td width="2%">:</td>
                        <td><?php 
                         $qry4 = "SELECT academic_year FROM voters WHERE user_id = '$_SESSION[userID]'";
                         $results = mysqli_query($conn, $qry4);
                         if ($results->num_rows > 0) { 
                            while ($row = $results->fetch_assoc()) {
                                echo $row['academic_year'] . "<br>";
                            }
                         } else {
                            echo "NO DATA";
                        }
                        ?></td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>