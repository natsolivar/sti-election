<?php 

    include 'db.php';


    $query = "SELECT date_start, date_end FROM e_period";
    $result = $conn->query($query);
    $rrow = $result->fetch_assoc();

    $e_start = $rrow['date_start'];
    $e_end = $rrow['date_end'];

    $voteQuery = "SELECT DATE(vote_date) AS vote_date, COUNT('r_vote_id') AS total_votes FROM registered_votes WHERE vote_date BETWEEN '$e_start' AND '$e_end' GROUP BY DATE(vote_date)";
    $voteResult = $conn->query($voteQuery);


    $dates = [];
    $totalVotes = [];

    while ($row = $voteResult->fetch_assoc()) {
        $dates[] = $row['vote_date'];
        $totalVotes[] = $row['total_votes'];
    }

?>
<script>
    var dates = <?php echo json_encode($dates); ?>;
    var totalVotes = <?php echo json_encode($totalVotes); ?>;
</script>