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
    $candidate_id = $_POST['candidate_id'];

    $candidate_sql = "SELECT c.position_id, c.voter_id, i.image 
                  FROM candidate c 
                  LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                  WHERE c.candidate_id = $candidate_id";

    $result = mysqli_query($conn, $candidate_sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $position_id = $row['position_id'];
        $voter_id = $row['voter_id'];
        $image =  mysqli_real_escape_string($conn, $row['image']);

        mysqli_begin_transaction($conn);

        try {

            $check_existing_sql = "SELECT COUNT(*) as count FROM council WHERE position_id = '$position_id'";
            $check_result = mysqli_query($conn, $check_existing_sql);
            $check_row = mysqli_fetch_assoc($check_result);
            
            if ($check_row['count'] > 0) {
                $update_sql = "UPDATE council SET status = 'INACTIVE' WHERE position_id = '$position_id'";
                mysqli_query($conn, $update_sql);
            }

            $insert_sql = "INSERT INTO council (position_id, voter_id, candidate_id, image, academic_year, date_appointed, status) 
                        VALUES ('$position_id', '$voter_id', '$candidate_id' , '$image', '$schoolYear', NOW(), 'ACTIVE')";
            mysqli_query($conn, $insert_sql);

            mysqli_commit($conn);

            echo "Candidate appointed successfully.";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }

    } else {
        echo "Candidate not found.";
    }

    mysqli_close($conn);


?>
