<?php 
header('Content-Type: application/json');

include 'db.php';

$position = $_GET['position'];

$qry = "SELECT u.user_name, SUM(votes.vote_count) as total_votes
        FROM candidate c 
        JOIN voters v ON c.voter_id = v.voter_id 
        JOIN users u ON v.user_id = u.user_id 
        LEFT JOIN votes ON votes.candidate_id = c.candidate_id
        WHERE c.position_id = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $position);
$stmt->execute();
$result = $stmt->get_result();

$candidate_names = [];
$votes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $user_name = $row['user_name'];
        $user_name = str_replace("(Student)", "", $user_name);
        $name_parts = explode(", ", trim($user_name));
        if (count($name_parts) == 2) {
            $formatted_name = $name_parts[1] . " " . $name_parts[0];
        } else {
            $formatted_name = $user_name;
        }
        $candidate_names[] = $formatted_name;
        $votes[] = (int)$row['total_votes'];
    }
}


echo json_encode(['names' => $candidate_names, 'votes' => $votes]);


?>