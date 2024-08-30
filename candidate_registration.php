<?php 
session_start();
include 'sidebar.php';
include 'session.php';
include 'db.php';

$qry5 = "SELECT voter_id, voter_grade, program_code FROM voters WHERE user_id = '$_SESSION[userID]'";
$result_select = mysqli_query($conn, $qry5);
$voter_id = '';
if ($result_select->num_rows > 0) { 
    while ($row = $result_select->fetch_assoc()) {
        $voter_id = $row['voter_id'];
        $grade = $row['voter_grade'];
        $program = $row['program_code'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if (isset($_POST['register_candidate'])) {

        $position_id = mysqli_real_escape_string($conn, $_POST['position']);
        $party_id = mysqli_real_escape_string($conn, $_POST['party']);
        $details = mysqli_real_escape_string($conn, $_POST['details']);
        $platform = mysqli_real_escape_string($conn, $_POST['platform']);

        $qry5 = "SELECT voter_id FROM voters WHERE user_id = '$_SESSION[userID]'";
        $result_select = mysqli_query($conn, $qry5);
        $voter_id = $result_select->fetch_assoc()['voter_id'];


        $qry2 = "INSERT INTO candidate (voter_id, position_id, candidate_details, platform, party_code, date_applied, status, support_count) 
                 VALUES ('$voter_id', '$position_id', '$details', '$platform', '$party_id', NOW(), 'Under Review', 0)";
        mysqli_query($conn, $qry2);

        $candidate_id = mysqli_insert_id($conn);


        if (isset($_FILES['images']['name'][0]) && !empty($_FILES['images']['name'][0])) {
            $max_files = 1;
            if (count($_FILES['images']['name']) > $max_files) {
                echo "You can upload a maximum of $max_files images.";
                exit;
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $file = fopen($tmp_name, 'rb');
                $image = fread($file, filesize($tmp_name));
                fclose($file);
                $image = mysqli_real_escape_string($conn, $image);

                $imgqry = "INSERT INTO images (candidate_id, image, date_uploaded) VALUES ('$candidate_id', '$image', NOW())";
                mysqli_query($conn, $imgqry);
            }
        }

    }

    echo "<script>
    alert('Registration Successful.');
    window.location.href = 'profile.php';
    </script>";
    die();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/regis_style.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
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

                                echo '<option value="PRES">President</option>';
                                if ($grade == 'g11' || $grade == 'g12') {
                                    echo '<option value="SHVP">Senior High Vice President</option>';
                                } else {
                                    echo '<option value="TERVP">Tertiary Vice President</option>';
                                }
                                echo '<option value="INTSEC">Internal Secretary</option>';
                                echo '<option value="EXTSEC">External Secretary</option>';
                                echo '<option value="TREA">Treasurer</option>';
                                echo '<option value="AUD">Auditor</option>';
                                echo '<option value="PIO">Public Information Officer</option>';

                            switch ($grade) { 
                                case 'g11':
                                switch ($program) {
                                    case 'ABM':
                                        echo '<option value="11ABMREP">GRADE 11 ABM Representative</option>';
                                        break;
                                    case 'HUMSS':
                                        echo '<option value="11HUMSSREP">Grade 11 HUMSS Representative</option>';
                                        break;
                                    case 'STEM':
                                        echo '<option value="11STEMREP">Grade 11 STEM Representative</option>';
                                        break;
                                    case 'CUART':
                                        echo '<option value="11CUARTREP">Grade 11 CUART Representative</option>';
                                        break;
                                    case 'MAWD':
                                        echo '<option value="11MAWDREP">Grade 11 MAWD Representative</option>';
                                        break;
                                }
                                break;
                                case 'g12':
                                switch ($program) {
                                    case 'ABM':
                                        echo '<option value="12ABMREP">GRADE 12 ABM Representative</option>';
                                        break;
                                    case 'HUMSS':
                                        echo '<option value="12HUMSSREP">Grade 12 HUMSS Representative</option>';
                                        break;
                                    case 'STEM':
                                        echo '<option value="12STEMREP">Grade 12 STEM Representative</option>';
                                        break;
                                    case 'CUART':
                                        echo '<option value="12CUARTREP">Grade 12 CUART Representative</option>';
                                        break;
                                    case 'MAWD':
                                        echo '<option value="12MAWDREP">Grade 12 MAWD Representative</option>';
                                        break;
                                }
                                break;
                                case '1st year':
                                switch ($program) {
                                    case 'BSIS':
                                        echo '<option value="BSIS1REP">BSIS 1 Representative</option>';
                                        break;
                                    case 'BSTM':
                                        echo '<option value="BSTM1REP">BSTM 1 Representative</option>';
                                        break;
                                }
                                break;
                                case '2nd year':
                                switch ($program) {
                                    case 'BSIS':
                                        echo '<option value="BSIS2REP">BSIS 2 Representative</option>';
                                        break;
                                    case 'BSTM':
                                        echo '<option value="BSTM2AREP">BSTM 2-A Representative</option>';
                                        echo '<option value="BSTM2BREP">BSTM 2-B Representative</option>';
                                        break;
                                }
                                break;
                                case '3rd year':
                                switch ($program) {
                                    case 'BSIS':
                                        echo '<option value="BSIS3REP">BSIS 3 Representative</option>';
                                        break;
                                    case 'BSTM':
                                        echo '<option value="BSTM3REP">BSTM 3 Representative</option>';
                                        break;
                                }
                                break;
                                case '4th year':
                                switch ($program) {
                                    case 'BSIS':
                                        echo '<option value="BSIS4REP">BSIS 4 Representative</option>';
                                        break;
                                    case 'BSTM':
                                        echo '<option value="BSTM4AREP">BSTM 4 Representative</option>';
                                        break;
                                }

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
                        echo '<option value="">No partylist available</option>';
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
            <p>Input the correct informations as it wont be editable in the profile page.</p>
        </div>
        <div class="button-container">
            <div id="form-error-message" class="error-message" style="display: none;">Password requirement not met</div>
            <button type="submit" name="register_candidate" class="btn btn-primary">Register</button>
        </div>
    </form>
</div>
</div>
    
</body>
<script>
    function validateForm() {
        var files = document.querySelector('input[type="file"]').files;
        if (files.length > 1) {
            alert("You can upload a maximum of 1 image.");
            return false;
        }
        return true;
    }
</script>
</html>
