<?php 
    include 'db.php';


    $query = "SELECT date_start, date_end FROM e_period";
    $result = $conn->query($query);
    $rrow = $result->fetch_assoc();

    $e_start = $rrow['date_start'];
    $e_end = $rrow['date_end'];

    $voteQuery = "SELECT DATE(vote_date) AS vote_date, COUNT(r_vote_id) AS total_votes 
                  FROM registered_votes 
                  GROUP BY DATE(vote_date)";
    $voteResult = $conn->query($voteQuery);

    $voteData = [];
    while ($row = $voteResult->fetch_assoc()) {
        $voteData[$row['vote_date']] = $row['total_votes'];
    }

    $period = new DatePeriod(
        new DateTime($e_start),
        new DateInterval('P1D'), 
        (new DateTime($e_end))->modify('+1 day')
    );

    $dates = [];
    $totalVotes = [];

    foreach ($period as $date) {
        $formattedDate = $date->format('Y-m-d');
        $dates[] = $formattedDate;
        $totalVotes[] = isset($voteData[$formattedDate]) ? $voteData[$formattedDate] : 0;
    }

?>

<script>
    var dates = <?php echo json_encode($dates); ?>;
    var totalVotes = <?php echo json_encode($totalVotes); ?>;
</script>
