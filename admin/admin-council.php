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
$error_message2 = '';


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
                        $error_message1 = "Unable to delete. This party is active and have members.";
                    } else {
                        $error_message1 = "Error deleting party: " . $conn->error;
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

    if (!empty($_POST['candidacyStartDate']) && !empty($_POST['candidacyEndDate']) && !isset($_POST['updateCandidacyDates'])) {
        $candidacyStartDate = $_POST['candidacyStartDate'];
        $candidacyEndDate = $_POST['candidacyEndDate'];
        $date_initialized = date("Y-m-d H:i:s");

        $insert_query = "INSERT INTO c_period (date_start, date_end, date_initialized) VALUES ('$candidacyStartDate', '$candidacyEndDate', '$date_initialized')";
        if ($conn->query($insert_query) === TRUE) {
            echo "New candidacy period set successfully.";
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['updateCandidacyDates'])) {
        $candidacyStartDate = $_POST['candidacyStartDate'];
        $candidacyEndDate = $_POST['candidacyEndDate'];
        $date_initialized = date("Y-m-d H:i:s");

        $update_query = "UPDATE c_period SET date_start = '$candidacyStartDate', date_end = '$candidacyEndDate', date_initialized = '$date_initialized' WHERE id = (SELECT id FROM c_period ORDER BY id DESC LIMIT 1)";
        if ($conn->query($update_query) === TRUE) {
            echo "Candidacy dates updated successfully.";
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "Error: " . $update_query . "<br>" . $conn->error;
        }
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
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <title>EMVS</title>
    </head>
    <body>
    <div class="main-content">
            <div class="item1" id="item-1">
                <h2>Other Election Details</h2>
            </div>
            <div class="item" id="item-1-5">
                <div class="child" id="c-item1"><p style="font-size: 25px; font-weight: bold;">Date of Candidacy:</p>
                <?php 
                $quer_candidacy = "SELECT id, date_start, date_end, date_initialized FROM c_period ORDER BY id DESC LIMIT 1";
                $ress_candidacy = $conn->query($quer_candidacy);
                $current_date = date("Y-m-d");

                if ($ress_candidacy->num_rows > 0) {
                    $rowwss_candidacy = $ress_candidacy->fetch_assoc();
                    $date_start_candidacy = new DateTime($rowwss_candidacy['date_start']);
                    $date_end_candidacy = new DateTime($rowwss_candidacy['date_end']);

                        echo "<div id='candidacyDateDisplay'>";
                        echo "<h2 style='margin-left: 20px;'>" . $date_start_candidacy->format('F j, Y') . " - " .  $date_end_candidacy->format('F j, Y') . "</h2>";
                        echo "<button type='button' class='btn btn-primary btn-sm' onclick='showEditCandidacyForm()'>Edit Dates</button>";
                        echo "</div>";

                } else {
                    ?>
                    <form action="admin-council.php" method="post">
                        <label for="candidacyStartDate">Start Date:</label>
                        <input type="date" id="candidacyStartDate" name="candidacyStartDate" required>
                        <label for="candidacyEndDate">End Date:</label>
                        <input type="date" id="candidacyEndDate" name="candidacyEndDate" required>     
                        <button type="submit" class="btn btn-primary btn-sm">Set Candidacy Dates</button>
                    </form>
                <?php 
                }
                ?>
                <div id="editCandidacyForm" style="display: none;">
                    <form action="admin-council.php" method="post">
                        <input type="hidden" name="updateCandidacyDates" value="1">
                        <label for="editCandidacyStartDate">Start Date:</label>
                        <input type="date" id="editCandidacyStartDate" name="candidacyStartDate" required>
                        <label for="editCandidacyEndDate">End Date:</label>
                        <input type="date" id="editCandidacyEndDate" name="candidacyEndDate" required>
                        <button type="submit" class="btn btn-primary btn-sm">Update Dates</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditCandidacyForm()">Cancel</button>
                    </form>
                </div>
            </div>
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
            </table>
                <button onclick="openModal()" class="button-3">Add New Party</button>
            </div>
            <div id="addPartyModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Add New Party</h2>
                    <form method="POST" action="admin-council.php">
                        <div>
                            <label for="party_code">Party Code</label>
                            <input type="text" name="party_code" id="party_code" class="form-control" placeholder="Enter Code" required>
                        </div>
                        <div>
                            <label for="party_name">Party Name</label>
                            <input type="text" name="party_name" id="party_name" class="form-control" placeholder="Enter Name" required>
                        </div>
                        <input type="hidden" name="form_type" value="form1">
                        <button type="submit" class="btn btn-primary">Add Party</button>
                    </form>
                </div>
            </div>
            </div>
            
        <script>

            function openModal() {
                document.getElementById("addPartyModal").style.display = "block";
            }

            function closeModal() {
                document.getElementById("addPartyModal").style.display = "none";
            }

            // Close modal when clicking outside of modal content
            window.onclick = function(event) {
                var modal = document.getElementById("addPartyModal");
                if (event.target === modal) {
                    closeModal();
                }
            }

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

            function showEditCandidacyForm() {
                document.getElementById('candidacyDateDisplay').style.display = 'none';
                document.getElementById('editCandidacyForm').style.display = 'block';
            }

            function hideEditCandidacyForm() {
                document.getElementById('editCandidacyForm').style.display = 'none';
                document.getElementById('candidacyDateDisplay').style.display = 'block';
            }

        </script>
    </body>
    </html>