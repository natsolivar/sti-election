<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';
    require 'config.php';
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles/profile_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
        <title>EMVS</title>
    </head>
    <body>
    <div class="main-content">
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
                            echo $row['voter_grade'] . "<br>";
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
                    <h3 class="mb-0"><i class="far fa-clone pr-1"></i>Voters Information</h3>
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
                            echo "<a href='candidate_registration.php'>Register Candidacy</a>";
                        }
                        
                        ?></td>

                    </tr>
                    <tr>
                        <th width="30%">Academic Year	</th>
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
                <div class="card shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h3 class="mb-0"><i class="far fa-clone pr-1"></i>Other Information</h3>
                </div>
                <div class="card-body pt-0">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </body>
    <script>
    </script>
</html>