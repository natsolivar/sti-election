<?php 

        session_start();
        include 'sidebar.php';
        include 'db.php';
        include 'session.php';


        $usere = $prof = $usern = $grade = $program = $voter_id = $gender = $club = $votestatus = $acadyr = $dateregis = "";
        $can_id = $can_details = $can_plat = $can_party = $date_applied = $can_status = $position = "";
        $image_count = 0;
        $images = [];


        if (isset($_GET['id'])) {
            $candidate_id = $_GET['id'];
            

            $query = "SELECT u.user_name, v.voter_grade, v.program_code, v.voter_gender, v.voter_club, c.candidate_id, c.candidate_details, c.platform, c.party_code, p.position_name, i.image, v.academic_year, v.voter_id
              FROM users u
              INNER JOIN voters v ON u.user_id = v.user_id
              INNER JOIN candidate c ON v.voter_id = c.voter_id
              LEFT JOIN position p ON c.position_id = p.position_id
              LEFT JOIN images i ON c.candidate_id = i.candidate_id
              WHERE c.candidate_id = $candidate_id";
            $result = mysqli_query($conn, $query);


            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $usern = $row['user_name'];
                    $grade = $row['voter_grade'];
                    $prof = $row['image'];
                    $program = $row['program_code'];
                    $club = $row['voter_club'];
                    $gender = $row['voter_gender'];
                    $can_id = $row['candidate_id'];
                    $can_details = $row['candidate_details'];
                    $can_plat = $row['platform'];
                    $can_party = $row['party_code'];
                    $position = $row['position_name'];
                    $acadyr = $row['academic_year'];

                    $imgBase64 = base64_encode($prof);
                    $imgSrc = "data:image/jpeg;base64,$imgBase64";

                    $user_name = str_replace("(Student)", "", $usern);
                    $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                                $formatted_name = $name_parts[1];
                        } else {
                                $formatted_name = $user_name;
                        }
                }
            }

            $imageQuery = "SELECT id, image FROM images WHERE candidate_id = $candidate_id";
            $imageResult = mysqli_query($conn, $imageQuery);
            if ($imageResult->num_rows > 0) {
                while ($imageRow = $imageResult->fetch_assoc()) {
                    $images[] = [
                        'id' => $imageRow['id'],
                        'url' => 'data:image/jpeg;base64,' . base64_encode($imageRow['image'])
                    ];
             }

        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $user_id = $_SESSION['userID'];
        
            $quer = "SELECT voter_id FROM voters WHERE user_id = '$user_id'";
            $res = mysqli_query($conn, $quer);

        
            if ($res->num_rows > 0) {
                $rows = $res->fetch_assoc();
                $v_id = $rows['voter_id'];
        
                $checkSupport = "SELECT * FROM support WHERE voter_id = '$v_id' AND candidate_id = '$id'";
                $supportResult = mysqli_query($conn, $checkSupport);
        
                if ($supportResult->num_rows > 0) {

                    $removeSupport = "DELETE FROM support WHERE voter_id = '$v_id' AND candidate_id = '$id'";
                    mysqli_query($conn, $removeSupport);
        
                    $updateCandidate = "UPDATE candidate SET support_count = support_count - 1 WHERE candidate_id = $id";
                    mysqli_query($conn, $updateCandidate);
        
                } else {

                    $addSupport = "INSERT INTO support (voter_id, candidate_id) VALUES ('$v_id', '$id')";
                    mysqli_query($conn, $addSupport);
        
                    $updateCandidate = "UPDATE candidate SET support_count = support_count + 1 WHERE candidate_id = $id";
                    mysqli_query($conn, $updateCandidate);
        
                }
            }
        
            $conn->close();
            exit();
        }

            $checkSupport = "SELECT * FROM support WHERE voter_id = (SELECT voter_id FROM voters WHERE user_id = '$user_id') AND candidate_id = '$candidate_id'";
            $supportResult = mysqli_query($conn, $checkSupport);

            $isSupported = $supportResult->num_rows > 0;
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles/canprof_style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
        <title>EMVS</title>
    </head>
    <body>
            <div class="student-profile py-4">
            <div class="back-button">
                <i class='bx bx-arrow-back' onclick="location.href='javascript:history.go(-1)'"></i>
            </div>
                <div class="container">
                    <div class="row">
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                        <div class="card-header bg-transparent text-center">
                            <?php 

                                if (!$prof == NULL || !$prof == '' ) {
                                    echo "<img class='profile_img' src='$imgSrc' alt='Profile'>";
                                } else {
                                    echo "<img src='admin-img/profile.png' alt='Image 1'>";
                                }
                            
                            
                            ?>

                            <h3><?php echo $usern; ?></h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><strong class="pr-1">Running for:</strong><?php echo $position; ?></p>
                            <p class="mb-0"><strong class="pr-1">Grade/Year Level:</strong><?php echo $grade; ?></p>
                            <p class="mb-0"><strong class="pr-1">Program:</strong></strong><?php echo $program; ?></p>
                            <button class="support-button <?php echo $isSupported ? 'active' : ''; ?>" 
                                    data-id="<?php echo $candidate_id; ?>" 
                                    onclick="updateVote(this)">
                                <i class='bx bxs-heart'></i>Support Candidate
                            </button>

                        </div>
                        </div>
                        <div class="card shadow-sm mt-3">
                            <div class="card-header bg-transparent text-center">
                            <h3 class="mb-0"><i class="far fa-clone pr-1"></i>
                                Platform</h3>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">
                                    <?php 
                                    
                                        echo $can_plat;
                                    
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="mb-0"><i class="far fa-clone pr-1"></i>Candidate Information</h3>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-bordered">
                            <tr>
                                <th width="30%">Candidate ID</th>
                                <td width="2%">:</td>
                                <td><?php echo $can_id; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Gender</th>
                                <td width="2%">:</td>
                                <td><?php echo $gender; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Club</th>
                                <td width="2%">:</td>
                                <td><?php echo $club; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Partylist</th>
                                <td width="2%">:</td>
                                <td><?php echo $can_party; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Hobbies</th>
                                <td width="2%">:</td>
                                <td><?php echo $acadyr; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Academic Year</th>
                                <td width="2%">:</td>
                                <td><?php echo $acadyr; ?></td>
                            </tr>
                            </table>
                        </div>
                        </div>
                        <div style="height: 26px; width: 100vh;"></div>
                            <div class="card shadow-sm">
                            <div class="card-header bg-transparent border-0">
                                <h3 class="mb-0"><i class="far fa-clone pr-1"></i>Who is <?php echo $formatted_name; ?></h3>
                            </div>
                            <div class="card-body pt-0">
                                <p><?php 
                                
                                echo $can_details;
                                
                                
                                ?></p>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </body>
    <script>
        function updateVote(button) {
            const id = button.getAttribute('data-id');
            const isActive = button.classList.contains('active');

            button.classList.toggle('active');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Success:', xhr.responseText);
                } else {
                    console.error('Error:', xhr.statusText);
                    button.classList.toggle('active');
                }
            };
            xhr.send(`id=${id}`);
        }


    </script>
</html>