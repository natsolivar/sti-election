<?php 

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate_id = $_POST['candidate_id'];
    $status = $_POST['status'];

    $query = "UPDATE candidate SET status = ? WHERE candidate_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $candidate_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Status updated successfully!');
                window.location.href = 'admin-candidate.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating status.');
                window.location.href = 'admin-candidate.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}

?>