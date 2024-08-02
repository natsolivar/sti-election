<?php 
header('Content-Type: application/json');

include 'db.php';

$position = $_GET['position'];

// Query to get candidate names and their vote counts for SH and TER
$qry = "SELECT u.user_name, vs.SHvote_count, vs.TERvote_count
    FROM candidate c 
    JOIN voters v ON c.voter_id = v.voter_id 
    JOIN users u ON v.user_id = u.user_id 
    LEFT JOIN votes vs ON vs.candidate_id = c.candidate_id
    WHERE c.position_id = ?";

$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $position);
$stmt->execute();
$result = $stmt->get_result();

$candidate_names = [];
$sh_votes = [];
$ter_votes = [];
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
        $sh_votes[] = (int)$row['SHvote_count'];
        $ter_votes[] = (int)$row['TERvote_count'];
    }
}

// Close the connection
$stmt->close();
$conn->close();

// Output JSON
echo json_encode(['names' => $candidate_names, 'sh_votes' => $sh_votes, 'ter_votes' => $ter_votes]);


?>