<?php 

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate_id = $_POST['candidate_id'];
    $status = $_POST['status'];

    $query = "UPDATE candidate SET status = ? WHERE candidate_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $candidate_id);
  
    if ($stmt->execute()) {
      if ($status == 'Accepted')
          $qry4 = "INSERT INTO votes (candidate_id, SHvote_count, TERvote_count, total_votes, date_updated) 
          VALUES ('$candidate_id', 0, 0, 0, NOW())";
          mysqli_query($conn, $qry4);

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