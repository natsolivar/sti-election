<?php
include 'db.php'; // Ensure you have your database connection setup here

if (isset($_GET['candidate_id'])) {
    $candidate_id = $_GET['candidate_id'];

    $query = "SELECT candidate_img1, candidate_img2 FROM candidate WHERE candidate_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $candidate_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            foreach (['candidate_img1', 'candidate_img2'] as $image_col) {
                if (!empty($row[$image_col])) {
                    $image_data = htmlspecialchars($row[$image_col]);
                    echo "<img src='data:image/jpeg;base64,$image_data' alt='Candidate Image' style='width:100%; padding-bottom:10px;'>";
                }
            }
        } else {
            echo "No images found for this candidate.";
        }
        
        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
    }
} else {
    echo "No candidate ID provided.";
}
?>
