<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';

    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $qry5 = "SELECT voter_id FROM voters WHERE user_id = '$_SESSION[userID]'";
    $result_select = mysqli_query($conn, $qry5);
        if ($result_select->num_rows > 0) { 
            while ($row = $result_select->fetch_assoc()) {
                    $voter_id = $row['voter_id'];
            }
        }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        if (isset($_POST['register_candidate'])) {

            $position_id = mysqli_real_escape_string($conn, $_POST['position']);
            $party_id = mysqli_real_escape_string($conn, $_POST['party']);
            $details = mysqli_real_escape_string($conn, $_POST['details']);
            $platform = mysqli_real_escape_string($conn, $_POST['platform']);
            $upload_dir = "uploads/";
            $file_paths = [];


            if (isset($_FILES['images']['name'][0]) && !empty($_FILES['images']['name'][0])) {
                $max_files = 2;
    
                if (count($_FILES['images']['name']) > $max_files) {
                    echo "You can upload a maximum of $max_files images.";
                    exit;
                }
    
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $file_name = basename($_FILES['images']['name'][$key]);
                    $file_tmp = $_FILES['images']['tmp_name'][$key];
    
                    $target_file = $upload_dir . $file_name;
                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $file_paths[] = $target_file;
                    } else {
                        echo "Failed to upload file: $file_name<br>";
                    }
                }
            }
        
            while (count($file_paths) < 2) {
                $file_paths[] = null;
            }
        
            $qry2 = "INSERT INTO candidate (voter_id, position_id, candidate_img1, candidate_img2, candidate_details, platform, party_code, date_applied, status) 
            VALUES ('$voter_id', '$position_id', '$file_paths[0]', '$file_paths[1]', '$details', '$platform', '$party_id', NOW(), 'Under Review')";
            $result = mysqli_query($conn, $qry2);
            
            $qry3 = "SELECT candidate_id FROM candidate WHERE voter_id = '$voter_id'";
            $results = mysqli_query($conn, $qry3);
            
            if ($results->num_rows > 0) {
                while ($row = $results->fetch_assoc()) {
                    $candidate_id = $row['candidate_id'];
                    
                    $qry4 = "INSERT INTO votes (candidate_id, SHvote_count, TERvote_count, date_updated) 
                                VALUES ('$candidate_id', 0, 0, NOW())";
                    $insert_result = mysqli_query($conn, $qry4);
            
                    $qry5 = "INSERT INTO polls (candidate_id, poll_count, date_updated) 
                                VALUES ('$candidate_id', 0, NOW())";
                    $insert_results = mysqli_query($conn, $qry5);
                }
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
    <link rel="stylesheet" type="text/css" href="styles/regis_style.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
</head>
<body>
<div class="main-content">
<div class="container">
            <form action="candidate_registration.php" method="post" id="register_candidate" enctype="multipart/form-data" onsubmit="return validateForm()">
                <h2>Voter Registration</h2>
                    <div class="content">
                        <div class="input-box">
                            <label for="name">Full Name</label>
                            <input type="text" placeholder="<?php echo htmlspecialchars($_SESSION['displayName']); ?>" name="name" readonly>
                        </div>
                        <div class="input-box">
                            <label for="Email">Voters ID</label>
                            <input type="text" placeholder="<?php echo $voter_id; ?>" name="email" readonly>
                        </div>
                        <div class="input-box">
                            <label for="position">Position</label>
                            <select id="position" name="position" required>
                                <?php 
                                $sql = "SELECT position_id, position_name FROM position ORDER BY position_rank";
                                $result = $conn->query($sql);
                    
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['position_id'] . '">' . $row['position_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No positions available</option>';
                                }
                    
                                ?>
                            </select>
                        </div>
                        <div class="input-box">
                            <label for="party">Partylist</label>
                            <select id="party" name="party" required>
                            <?php 
                                $sql = "SELECT party_code, party_name FROM party";
                                $result = $conn->query($sql);
                    
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['party_code'] . '">' . $row['party_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No positions available</option>';
                                }
                    
                                $conn->close();?>
                            </select>
                        </div>
                        <div class="input-box">
                            <label for="details">Tell us about yourself</label>
                            <textarea name="details" placeholder="Enter details here..." rows="5"></textarea>
                        </div>
                        <div class="input-box">
                            <label for="platform">Platform</label>
                            <textarea name="platform" placeholder="Enter details here..." rows="5"></textarea>
                        </div>
                        <div class="input-box">
                            <label for="img">Images</label>
                            <input type="file" id="img" name="images[]" accept="image/*" multiple>
                        </div>
                    </div>
                    <div class="alert">
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolore eos exercitationem sequi hic totam magnam in nemo fugit sapiente eius.</p>
                    </div>
                    <div class="button-container">
                        <div id="form-error-message" class="error-message" style="display: none;">Password requirement not meet</div>
                        <button type="submit" name="register_candidate">Register</button>
                    </div>
            </form>
        </div>
</div>
    
</body>
<script>
        function validateForm() {
        var files = document.querySelector('input[type="file"]').files;
        if (files.length > 2) {
            alert("You can upload a maximum of 2 images.");
            return false;
        }
            return true;
        }
</script>
</html>