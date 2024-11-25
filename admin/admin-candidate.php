<?php 

    include 'admin-sidebar.php';
    include 'db.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/candidate_style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>EMVS</title>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <div class="main-content">
            <div class="item1" id="item-1">
                <p style="font-size: 30px; font-weight: bold;">Running Candidates</p>
            </div>
                <div class="item" id="item-3">
                <!--<select id="positionDropdown" onchange="updateTable()">
                        <option value="" disabled selected hidden>All candidates</option>
                        <?php 

                            $query = "SELECT position_id, position_name FROM position ORDER BY position_rank";
                            $result = mysqli_query($conn, $query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $pos = $row['position_id'];
                                        $pos_name = $row['position_name'];
                                        echo "<option value='$pos'>$pos_name</option>";
                                    }
                                }
                            
                        ?>
                    </select> -->
                <table id="myTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Program</th>
                            <th scope="col">Position</th>
                            <th scope="col">Status</th>
                            <th scope="col">Total Votes</th>
                            <th scope="col">Date Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                                $query = "SELECT c.candidate_id, p.position_name, u.user_name, v.program_code, v.voter_grade,  c.position_id, c.date_applied, c.status, vs.total_votes
                                FROM users u 
                                INNER JOIN voters v ON u.user_id = v.user_id
                                INNER JOIN candidate c ON v.voter_id = c.voter_id
                                LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                LEFT JOIN position p ON c.position_id = p.position_id
                                WHERE c.status IN ('Under Review', 'Accepted')";
                                $result = mysqli_query($conn, $query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                    $c_id = $row['candidate_id'];
                                    $position = $row['position_name'];
                                    $position_id = $row['position_id'];
                                    $usern = $row['user_name'];
                                    $grade = $row['voter_grade'];
                                    $program = $row['program_code'];
                                    $date = $row['date_applied'];
                                    $status = $row['status'];
                                    $tvotes = $row['total_votes'];

                                    $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
                                    $formattedDate = $dateTime ? $dateTime->format('m/d/Y h:i A') : 'N/A';

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

                                        echo "<tr class='clickable-row' data-position='$position_id' data-candidate-id='$c_id'>";
                                        echo "<th scope='row'>$c_id</th>";
                                        echo "<td>$usern</td>";
                                        echo "<td>$ggrade, $program</td>";
                                        echo "<td>$position</td>";

                                        if (!($status == 'Under Review' || $status == null)) {

                                            if ($status == 'Accepted') {

                                                echo "<td style='color: green;'>$status</td>";

                                                echo "<td style='text-align: center'>$tvotes</td>";

                                            } elseif ($status == 'Rejected') {

                                                echo "<td style='color: red;'>$status</td>";

                                                echo "<td style='text-align: center;'>N/A</td>";

                                            } else {

                                                echo "<td style='color: grey;'>$status</td>";

                                            }


                                        } else {

                                            echo "<td>
                                                <form method='POST' action='update_status.php' style='display:inline;' onsubmit='return confirmAction()'>
                                                    <input type='hidden' name='candidate_id' value='$c_id'>
                                                    <input type='hidden' name='status' value='Accepted'>
                                                    <button type='submit' id='btn' class='btn btn-success' onclick='event.stopPropagation();'>Accept</button>
                                                </form>
                                                <form method='POST' action='update_status.php' style='display:inline;' onsubmit='return confirmAction()'>
                                                    <input type='hidden' name='candidate_id' value='$c_id'>
                                                    <input type='hidden' name='status' value='Rejected'>
                                                    <button type='submit' id='btn' class='btn btn-danger' onclick='event.stopPropagation();'>Reject</button>
                                                </form>
                                            </td>";

                                            echo "<td style='text-align: center;'>N/A</td>";
                                        }

                                        echo "<td>$formattedDate</td>";
                                        echo "</tr>";

                                    } 

                                } else {
                                    echo "<tr>";
                                    echo "<td>NO DATA</td>";
                                    echo "</tr>";
                                } 
                            ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>

                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelectorAll('.clickable-row').forEach(row => {
                        row.addEventListener('click', function() {
                            const candidateId = this.getAttribute('data-candidate-id');
                            window.location.href = `can-details.php?candidate_id=${candidateId}`;
                        });
                    });

                    document.querySelectorAll('button').forEach(button => {
                        button.addEventListener('click', function(event) {
                            event.stopPropagation();
                        });
                    });
                });

                function updateTable() {
                    var dropdown = document.getElementById("positionDropdown");
                    var selectedPosition = dropdown.value;
                    var table = document.getElementById("myTable");
                    var rows = table.getElementsByTagName("tr");

                    for (var i = 1; i < rows.length; i++) {
                        var row = rows[i];
                        var position = row.getAttribute("data-position");
                        
                        if (selectedPosition === "" || position === selectedPosition) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    }
                }

                function confirmAction() {
                    return confirm("Are you sure you want to proceed?");
                }

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