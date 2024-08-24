<?php 

    include 'admin-sidebar.php';
    include 'db.php';

    $query1 = "SELECT COUNT(*) AS total_voters FROM voters";
    $res = mysqli_query($conn, $query1);

    if ($res->num_rows > 0) {
        while ($row = $res -> fetch_assoc()) {
            $resvotes = (int)$row['total_voters'];
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/voters_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>EMVS</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    </head>
<body>
    <div class="main-content">
        <div class="item1" id="item-1"><p style="font-size: 30px; font-weight: bold;">Registered Voters</p></div>
        <div class="item1" id="item-2"><h4>Total Registered Voters: <b><?php echo $resvotes ?></b></h4></div>
        <div class="item" id="item-3">
            <table id="myTable" class="table table-hover">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Grade/Year</th>
                    <th scope="col">Program</th>
                    <th scope="col">Date Registered</th>
                    <th scope="col">Vote Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 

                        $query = "SELECT u.user_name, v.voter_id, v.voter_grade, v.program_code, v.voter_num, v.vote_status, v.date_registered, rv.vote_date
                                FROM users u 
                                INNER JOIN voters v ON u.user_id = v.user_id
                                LEFT JOIN registered_votes rv ON v.voter_id = rv.voter_id";
                        $result = mysqli_query($conn, $query);

                        if ($result->num_rows > 0) {
                            while ($row = $result -> fetch_assoc()) {
                                $voterid = $row['voter_id'];
                                $username = $row['user_name'];
                                $grade = $row['voter_grade'];
                                $program = $row['program_code'];
                                $date = $row['date_registered'];
                                $status = $row['vote_status'];
                                $vote = $row['vote_date'];

                                $user_name = str_replace("(Student)", "", $username);

                                $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                                $formattedDate = $dateTime ? $dateTime->format('m/d/Y h:i A') : 'N/A';

                                $voteTime = DateTime::createFromFormat('Y-m-d H:i:s', $vote);
                                $formattedvDate = $voteTime ? $voteTime->format('m/d/Y h:i A') : 'N/A';

                                switch ($grade) {
                                    case 'g11':
                                        $ggrade = 'Grade 11';
                                        break;
                                    case 'g12':
                                        $ggrade = 'Grade 12';
                                        break;
                                    default:
                                        $ggrade = $grade;
                                }   

                                echo "<tr>";
                                echo "<th scope='row'>$voterid</th>";
                                echo "<td>$user_name</td>";
                                echo "<td>$ggrade</td>";
                                echo "<td>$program</td>";
                                echo "<td>$formattedDate</td>";

                                if ($status == 'YES') {
                                    echo "<td style='color: green;'>$formattedvDate</td>";
                                } else {
                                    echo "<td><i class='bx bxs-user-x' style='color: red;'></i></td>";
                                }
                                echo "</tr>";
                            }
                        }
                    
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "pageLength": 10,
                        "lengthMenu": [10, 25, 50, 100]
                    });
                });
        </script>
</body>
</html>