<?php 
header('Content-Type: application/json');

    include 'db.php';

    $position = $_GET['position'];

    $qry1 = "SELECT u.user_name, c.candidate_id
            FROM candidate c 
            JOIN voters v ON c.voter_id = v.voter_id 
            JOIN users u ON v.user_id = u.user_id 
            WHERE c.position_id = ?
            GROUP BY u.user_name";
    $stmt = $conn->prepare($qry1);
    $stmt->bind_param("s", $position);
    $stmt->execute();
    $result = $stmt->get_result();

    $candidate_names = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $candidate_id = $row['candidate_id'];
            $qry2 = "SELECT vote_count FROM votes WHERE candidate_id = '$candidate_id'";
            $vote_count = mysqli_query($conn, $qry2);
            if ($vote_count->num_rows > 0) {
                while ($vrow = $vote_count->fetch_assoc()) {

                    $user_name = $row['user_name'];
                    $user_name = str_replace("(Student)", "", $user_name);
                    $name_parts = explode(", ", trim($user_name));
                    if (count($name_parts) == 2) {
                        $formatted_name = $name_parts[1] . " " . $name_parts[0];
                    } else {
                        $formatted_name = $user_name;
                    }
                    $candidate_names[] = $formatted_name;
                    $votes[] = (int)$vrow['vote_count'];
                        }
            }

        }
    }

    echo json_encode(['names' => $candidate_names, 'votes' => $votes]);


?>