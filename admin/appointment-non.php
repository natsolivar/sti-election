<?php 

include 'db.php';
function getSchoolYear($currentDate) {
    $currentMonth = (int) date('m', strtotime($currentDate));
    $currentYear = (int) date('Y', strtotime($currentDate));
    
    if ($currentMonth >= 8) {
        $startYear = $currentYear;
        $endYear = $currentYear + 1;
    } else {
        $startYear = $currentYear - 1;
        $endYear = $currentYear;
    }
    return "$startYear-$endYear";
}

$currentDate = date('Y-m-d');
$schoolYear = getSchoolYear($currentDate);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voter_id = $_POST['voter_id'];
    $position_id = $_POST['position_id'];

    $candidate_sql = "SELECT v.voter_id, u.user_profile
                      FROM users u 
                      LEFT JOIN voters v ON u.user_id = v.user_id 
                      WHERE v.voter_id = $voter_id";

    $result = mysqli_query($conn, $candidate_sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $voter_id = $row['voter_id'];
        $img = mysqli_real_escape_string($conn, $row['user_profile']);
        $image = base64_encode($row['user_profile']);

        mysqli_begin_transaction($conn);

        try {
            $insert_candidate_sql = "INSERT INTO candidate (voter_id, position_id, candidate_details, platform, party_code, date_applied, status, support_count) 
                                     VALUES ('$voter_id', '$position_id', 'N/A', 'N/A', NULL, NOW(), 'Pending', 0)";
            mysqli_query($conn, $insert_candidate_sql);

            $candidate_id = mysqli_insert_id($conn);

            $imgqry = "INSERT INTO images (candidate_id, image, date_uploaded) VALUES ('$candidate_id', '$img', NOW())";
            mysqli_query($conn, $imgqry);

            $check_existing_sql = "SELECT COUNT(*) as count FROM council WHERE position_id = '$position_id'";
            $check_result = mysqli_query($conn, $check_existing_sql);
            $check_row = mysqli_fetch_assoc($check_result);
            
            if ($check_row['count'] > 0) {
                $update_sql = "UPDATE council SET status = 'INACTIVE' WHERE position_id = '$position_id'";
                mysqli_query($conn, $update_sql);
            }

            $insert_council_sql = "INSERT INTO council (position_id, voter_id, candidate_id, image, academic_year, date_appointed, status) 
                                   VALUES ('$position_id', '$voter_id', '$candidate_id', '$image', '$schoolYear', NOW(), 'ACTIVE')";
            mysqli_query($conn, $insert_council_sql);

            mysqli_commit($conn);
            header("Location: admin-tally");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            header("Location:  admin-tally");
            exit;
        }
    } else {
        echo "Candidate not found.";
    }

    mysqli_close($conn);
}


?>