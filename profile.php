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
        <link rel="stylesheet" type="text/css" href="styles/profile_style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
        <title>EMVS</title>
    </head>
    <body>
    <main>
        <div class="student-profile py-4">
        <div class="container">
            <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm">
                <div class="card-header bg-transparent text-center">
                    <?php if ($userProfilePic): ?>
                        <img src="data:image/jpeg;base64,<?php echo $userProfilePic; ?>" alt="Profile Picture" class="profile-pic" onclick="location.href='profile.php'">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($_SESSION['displayName']); ?></h3>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong class="pr-1">Email:</strong><?php 
                    $qry1 = "SELECT user_email FROM users WHERE user_id = '$_SESSION[userID]'";
                    $results = mysqli_query($conn, $qry1);
                    if ($results->num_rows > 0) { 
                        while ($row = $results->fetch_assoc()) {
                            echo $row['user_email'] . "<br>";
                        }
                    } else {
                        echo "NO DATA";
                    }
                    ?></p>
                    <p class="mb-0"><strong class="pr-1">Grade/Year Level:</strong><?php 
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
                    ?></p>
                    <p class="mb-0"><strong class="pr-1">Program:</strong><?php 
                    $qry2 = "SELECT program_code FROM voters WHERE user_id = '$_SESSION[userID]'";
                    $results = mysqli_query($conn, $qry2);
                    if ($results->num_rows > 0) { 
                        while ($row = $results->fetch_assoc()) {
                            echo $row['program_code'] . "<br>";
                        }
                    } else {
                        echo "NO DATA";
                    }
                    ?></p>
                </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h3 class="mb-0"><i class='bx bx-user' style="font-size: 32px;"></i>Voters Information</h3>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-bordered">
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
                <div style="height: 26px"></div>
                        <?php 
                        $qry5 = "SELECT voter_id FROM voters WHERE user_id = '$_SESSION[userID]'";
                        $result = mysqli_query($conn, $qry5);
                        if ($result -> num_rows > 0) {
                            while ($row = $result -> fetch_assoc()) {
                                $v_id = $row['voter_id'];
                                    
                                    $qry6 = "SELECT c.candidate_id, c.candidate_details, c.platform, c.party_code, c.date_applied, c.status, p.position_name
                                            FROM users u
                                            INNER JOIN voters v ON u.user_id = v.user_id
                                            INNER JOIN candidate c ON v.voter_id = c.voter_id
                                            LEFT JOIN position p ON c.position_id = p.position_id
                                            WHERE c.voter_id = $v_id";
                                    $res = mysqli_query($conn, $qry6);

                                    if ($res -> num_rows > 0 ) {
                                        while ($rows = $res -> fetch_assoc()) {
                                            $candid = $rows['candidate_id'];
                                            $candide = $rows['candidate_details'];
                                            $candipla = $rows['platform'];
                                            $candipa = $rows['party_code'];
                                            $date_app = $rows['date_applied'];
                                            $status = $rows['status'];
                                            $pos = $rows['position_name'];

                                            $imageQuery = "SELECT id, image FROM images WHERE candidate_id = $candid";
                                            $imageResult = mysqli_query($conn, $imageQuery);
                                            if ($imageResult->num_rows > 0) {
                                                while ($imageRow = $imageResult->fetch_assoc()) {
                                                    $images[] = [
                                                        'id' => $imageRow['id'],
                                                        'url' => 'data:image/jpeg;base64,' . base64_encode($imageRow['image'])
                                                    ];
                                                }
                                            }
                                        }

                                 ?>

                            <div class="card shadow-sm">
                                <div class="card-header bg-transparent border-0">
                                    <h3 class="mb-0"><i class='bx bxs-user-account' style="font-size: 32px;"></i>Candidacy Information</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Candidate ID</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $candid; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Position</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $pos; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Details</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $candide; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Platform</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $candipla; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Partylist</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $candipa; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Image Uploaded</th>
                                        <td width="2%">:</td>
                                        <td> 
                                            <?php if (!empty($images)): ?>
                                                <?php foreach ($images as $image): ?>
                                                    <img src="<?php echo $image['url']; ?>" alt="Candidate Image" class="img-thumbnail" width="150">
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                <p>No images uploaded.</p>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Date Applied</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $date_app; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Candidacy Status</th>
                                        <td width="2%">:</td>
                                        <?php 

                                            if ($status == 'Accepted') {
                                                
                                                echo "<td style='color: green;'>$status</td>";

                                            } else {

                                                echo "<td style='color: red;'>$status</td>";

                                            }

                                        ?>
                                    </tr>
                                </table>
                                </div>
                                </div>


                                 <?php
                                }
                            }
                        }
                        
                        ?>
            </div>
            </div>
        </div>
        </div>
    </main>
    </body>
    <script>
    </script>
</html>