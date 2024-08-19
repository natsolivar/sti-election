<?php
ob_start(); 

include 'admin-sidebar.php';
include 'db.php';

$sql = "SELECT * FROM party";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM club";
$res = $conn->query($sql1);

$error_message = '';
$error_message1 = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $formType = $_POST['form_type'];

        if ($formType == 'form1') {
            if (isset($_POST['party_code']) && isset($_POST['party_name'])) {
                $party_code = $conn->real_escape_string($_POST['party_code']);
                $party_name = $conn->real_escape_string($_POST['party_name']);

                $checkQuery = "SELECT COUNT(*) as count FROM party WHERE party_code='$party_code'";
                $checkResult = $conn->query($checkQuery);
                $row = $checkResult->fetch_assoc();

                if ($row['count'] > 0) {
                    $error_message1 = "Party code already exists.";
                } else {
                    $query1 = "INSERT INTO party (party_code, party_name, party_img, date_created) VALUES ('$party_code', '$party_name', NULL, NOW())";
                    if ($conn->query($query1) === TRUE) {
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    } else {
                        $error_message1 = "Error inserting into party: " . $conn->error;
                    }
                }
            } else {
                $error_message1 = "Partylist: Missing required fields.";
            }
        } elseif ($formType == 'form2') {
            if (isset($_POST['club_id']) && isset($_POST['club_name'])) {
                $club_id = $conn->real_escape_string($_POST['club_id']);
                $club_name = $conn->real_escape_string($_POST['club_name']);

                $checkQuery = "SELECT COUNT(*) as count FROM club WHERE club_id='$club_id'";
                $checkResult = $conn->query($checkQuery);
                $row = $checkResult->fetch_assoc();

                if ($row['count'] > 0) {
                    $error_message1 = "Club Code already exists.";
                } else {
                    $query2 = "INSERT INTO club (club_id, club_name, date_created) VALUES ('$club_id', '$club_name', NOW())";
                    if ($conn->query($query2) === TRUE) {
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    } else {
                        $error_message1 = "Error inserting into club: " . $conn->error;
                    }
                }
            } else {
                $error_message1 = "Club: Missing required fields.";
            }
        } elseif ($formType == 'delete_party') {
            if (isset($_POST['delete_party_code'])) {
                $party_code = $conn->real_escape_string($_POST['delete_party_code']);

                $query = "DELETE FROM party WHERE party_code='$party_code'";
                try {
                    if ($conn->query($query) === TRUE) {
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    } else {
                        $error_message1 = "Error deleting party: " . $conn->error;
                    }
                } catch (mysqli_sql_exception $e) {
                    if ($e->getCode() == 1451) { 
                        $error_message1 = "Unable to delete. This party is active and has members.";
                    } else {
                        $error_message1 = "Error deleting party: " . $conn->error;
                    }
                }
            }
        } elseif ($formType == 'delete_club') {
            if (isset($_POST['delete_club_id'])) {
                $club_id = $conn->real_escape_string($_POST['delete_club_id']);

                $query = "DELETE FROM club WHERE club_id='$club_id'";
                try {
                    if ($conn->query($query) === TRUE) {
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit;
                    } else {
                        $error_message = "Error deleting club: " . $conn->error;
                    }
                } catch (mysqli_sql_exception $e) {
                    if ($e->getCode() == 1451) { 
                        $error_message = "Unable to delete. This club is active and has members.";
                    } else {
                        $error_message = "Error deleting club: " . $conn->error;
                    }
                }
            }
        }
    }
    if (isset($_POST['updateDates'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $date_initialized = date("Y-m-d H:i:s");

        $update_query = "UPDATE e_period SET date_start = ?, date_end = ?, date_initialized = ? WHERE id = (SELECT id FROM e_period ORDER BY id DESC LIMIT 1)";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('sss', $startDate, $endDate, $date_initialized);
        
        if ($stmt->execute()) {
            echo "Dates updated successfully.";
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "Error updating dates: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['startDate']) && isset($_POST['endDate'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $date_initialized = date("Y-m-d H:i:s");

        $insert_query = "INSERT INTO e_period (date_start, date_end, date_initialized) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('sss', $startDate, $endDate, $date_initialized);
        
        if ($stmt->execute()) {
            echo "New election period set successfully.";
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "Error inserting dates: " . $stmt->error;
        }
        $stmt->close();
    }
}

ob_end_flush();

if (!empty($error_message)) {
    echo "<script>alert('$error_message');</script>";
}

if (!empty($error_message1)) {
    echo "<script>alert('$error_message1');</script>";
}


?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/council_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <title>EMVS</title>
    </head>
    <body>
    <div class="main-content">
            <div class="item" id="item-1">
                <h2>Other Election Details</h2>
            </div>
            <div class="item" id="item-2">
                <div class="child" id="c-item1"><p style="font-size: 25px; font-weight: bold;">Election date:</p>
                <?php 
                
                $quer = "SELECT id, date_start, date_end, date_initialized FROM e_period ORDER BY id DESC LIMIT 1";
                $ress = $conn->query($quer);
                $current_date = date("Y-m-d");
                
                if ($ress->num_rows > 0) {
                    $rowwss = $ress->fetch_assoc();
                    $date_start = new DateTime($rowwss['date_start']);
                    $date_end = new DateTime($rowwss['date_end']);
                
                    if ($current_date > $date_end) {
                        echo "<p>The election period has ended.</p>";
                    } else {
                        echo "<div id='dateDisplay'>";
                        echo "<h2 style='margin-left: 20px;'>" . $date_start->format('F j, Y') . " - " .  $date_end->format('F j, Y') . "</h2>";
                        echo "<button type='button' class='btn btn-primary btn-sm' onclick='showEditForm()'>Edit Dates</button>";
                        echo "</div>";
                    }
                } else {
                    ?>
                    <form action="admin-council.php" method="post">
                        <label for="startDate">Start Date:</label>
                        <input type="date" id="startDate" name="startDate" required>
                        <label for="endDate">End Date:</label>
                        <input type="date" id="endDate" name="endDate" required>     
                        <button type="submit" class="btn btn-primary btn-sm">Set Dates</button>
                    </form>
                <?php 
                
                }
                
                ?>
                <div id="editForm" style="display: none;">
                    <form action="admin-council.php" method="post">
                        <input type="hidden" name="updateDates" value="1">
                        <label for="editStartDate">Start Date:</label>
                        <input type="date" id="editStartDate" name="startDate" required>
                        <label for="editEndDate">End Date:</label>
                        <input type="date" id="editEndDate" name="endDate" required>
                        <button type="submit" class="btn btn-primary btn-sm">Update Dates</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm()">Cancel</button>
                    </form>
                </div>
                </div>
            </div>
            <div class="item" id="item-3">
                <h3>Partylist</h3>
                    <table id="table1" class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['party_code'] . "</td>";
                                    echo "<td>" . $row['party_name'] . "</td>";
                                    echo "<td>" . $row['date_created'] . "</td>";
                                    echo "<td>";
                                    echo "<form method='POST' style='display:inline;' onsubmit='return confirmDelete();'>";
                                    echo "<input type='hidden' name='delete_party_code' value='" . $row['party_code'] . "'>";
                                    echo "<input type='hidden' name='form_type' value='delete_party'>";
                                    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="input-row">
                            <form method="POST" action="admin-council.php">
                                <td><input type="text" name="party_code" class="form-control" placeholder="Enter Code" required></td>
                                <td><input type="text" name="party_name" class="form-control" placeholder="Enter Name" required></td>
                                <td></td>
                                <td>
                                    <input type="hidden" name="form_type" value="form1">
                                    <button type="submit" class="btn btn-primary mt-2">Add</button>
                                </td>
                            </form>
                            </tr>
                        </tfoot>
                    </table>
            </div>

            <div class="item" id="item-4">
                <h3>Club</h3>
                    <table id="table2" class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($res->num_rows > 0) {
                                while($rows = $res->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $rows['club_id'] . "</td>";
                                    echo "<td>" . $rows['club_name'] . "</td>";
                                    echo "<td>" . $rows['date_created'] . "</td>";
                                    echo "<td>";
                                    echo "<form method='POST' style='display:inline;' onsubmit='return confirmDelete();'>";
                                    echo "<input type='hidden' name='delete_club_id' value='" . $rows['club_id'] . "'>";
                                    echo "<input type='hidden' name='form_type' value='delete_club'>";
                                    echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="input-row">
                            <form method="POST" action="admin-council.php">
                                <td><input type="text" name="club_id" class="form-control" placeholder="Enter Code" required></td>
                                <td><input type="text" name="club_name" class="form-control" placeholder="Enter Name" required></td>
                                <td></td>
                                <td>
                                    <input type="hidden" name="form_type" value="form2">
                                    <button type="submit" class="btn btn-primary mt-2">Add</button>
                                </td>
                            </form>
                            </tr>
                        </tfoot>
                    </table>
            </div>
            </div>
        <script>

            function confirmDelete() {
                return confirm("Are you sure you want to delete this item?");
            }

                $(document).ready(function() {
                    $('#table1').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "pageLength": 5,
                        "lengthMenu": [5, 10]
                    });
                });

                $(document).ready(function() {
                    $('#table2').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "pageLength": 5,
                        "lengthMenu": [5, 10]
                    });
                });

                const today = new Date().toISOString().split('T')[0];

            const startDateInput = document.getElementById('startDate');
            startDateInput.setAttribute('min', today);

            startDateInput.addEventListener('change', function() {
                const selectedStartDate = this.value;
                const endDateInput = document.getElementById('endDate');

                endDateInput.setAttribute('min', selectedStartDate);
            });

            function setDates() {
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;
                if (!startDate || !endDate) {
                    alert("Please select both start and end dates.");
                    return;
                }
                alert("Dates set successfully:\nStart Date: " + startDate + "\nEnd Date: " + endDate);
            }

            function clearDates() {
                startDateInput.value = '';
                document.getElementById('endDate').value = '';
                document.getElementById('endDate').removeAttribute('min');
            }

            function showEditForm() {
                document.getElementById('dateDisplay').style.display = 'none';
                document.getElementById('editForm').style.display = 'block';
                document.getElementById('editStartDate').value = "<?php echo isset($date_start) ? $date_start->format('Y-m-d') : ''; ?>";
                document.getElementById('editEndDate').value = "<?php echo isset($date_end) ? $date_end->format('Y-m-d') : ''; ?>";
            }

            function hideEditForm() {
                document.getElementById('editForm').style.display = 'none';
                document.getElementById('dateDisplay').style.display = 'block';
            }

        </script>
    </body>
    </html>