<?php 

    include 'db.php';
    include 'admin-sidebar.php';

    function getSchoolYear($currentDate) {
        $currentMonth = (int) date('m', strtotime($currentDate));
        $currentYear = (int) date('Y', strtotime($currentDate));
        
        if ($currentMonth >= 8) {
            $startYear = $currentYear;
            $endYear = $currentYear + 1;
        } else {
            $startYear = $currentYear - 1;
            $endYear = $currentYear;
        }
        return "$startYear-$endYear";
    }
    
    $currentDate = date('Y-m-d');
    $schoolYear = getSchoolYear($currentDate);

    if (isset($_GET['position_id'])) {
        $position_id = $_GET['position_id'] ?? null;
    
        if (!$position_id) {
            echo "Invalid position.";
            exit;
        }
    
        $validations = [
            'Tertiary Vice President' => "voter_grade IN ('1st year', '2nd year', '3rd year', '4th year')",
            'Senior High Vice President' => "voter_grade IN ('g11', 'g12')",
            'Grade 11 HUMSS Representative' => "voter_grade = 'g11' AND program_code = 'HUMSS'",
            'Grade 11 ABM Representative' => "voter_grade = 'g11' AND program_code = 'ABM'",
            'Grade 11 STEM Representative' => "voter_grade = 'g11' AND program_code = 'STEM'",
            'Grade 11 CUART Representative' => "voter_grade = 'g11' AND program_code = 'CUART'",
            'Grade 11 MAWD Representative' => "voter_grade = 'g11' program_code = 'MAWD'",
            'Grade 12 HUMSS Representative' => "voter_grade = 'g12' AND program_code = 'HUMSS'",
            'Grade 12 ABM Representative' => "voter_grade = 'g12' AND program_code = 'ABM'",
            'Grade 12 STEM Representative' => "voter_grade = 'g12' AND program_code = 'STEM'",
            'Grade 12 CUART Representative' => "voter_grade = 'g12' AND program_code = 'CUART'",
            'Grade 12 MAWD Representative' => "voter_grade = 'g12' program_code = 'MAWD'",
            'BSIS 1 Representative' => "voter_grade = '1st year' AND program_code = 'BSIS'",
            'BSIS 2 Representative' => "voter_grade = '2nd year' AND program_code = 'BSIS'",
            'BSIS 3 Representative' => "voter_grade = '3rd year' AND program_code = 'BSIS'",
            'BSIS 4 Representative' => "voter_grade = '4th year' AND program_code = 'BSIS'",
            'BSTM 1A Representative' => "voter_grade = '1st year' AND program_code = 'BSTM'",
            'BSTM 1B Representative' => "voter_grade = '1st year' AND program_code = 'BSTM'",
            'BSTM 2A Representative' => "voter_grade = '1st year' AND program_code = 'BSTM'",
            'BSTM 2B Representative' => "voter_grade = '1st year' AND program_code = 'BSTM'",
            'BSTM 2 Representative' => "voter_grade = '2nd year' AND program_code = 'BSTM'",
            'BSTM 3 Representative' => "voter_grade = '3rd year' AND program_code = 'BSTM'",
            'BSTM 4 Representative' => "voter_grade = '4th year' AND program_code = 'BSTM'"
        ];
    
        $position_query = "SELECT position_name FROM position WHERE position_id = '$position_id'";
        $position_result = mysqli_query($conn, $position_query);
        $position_row = mysqli_fetch_assoc($position_result);
        $position_name = $position_row['position_name'] ?? null;
    
        if (!$position_name) {
            echo "Position not found.";
            exit;
        }

        $filter = $validations[$position_name] ?? "1=1"; 
    
        $query = "
            SELECT v.voter_id, u.user_profile, u.user_name, v.voter_grade, v.program_code
            FROM voters v
            LEFT JOIN users u ON v.user_id = u.user_id
            WHERE $filter
            AND NOT EXISTS (
                SELECT 1
                FROM candidate c
                WHERE c.voter_id = v.voter_id)
            AND NOT EXISTS (
                SELECT 1
                FROM council c
                WHERE c.voter_id = v.voter_id AND NOT c.academic_year = '$schoolYear')";
        $result = mysqli_query($conn, $query);
    }
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/non-can_style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <title>EMVS</title>
    </head>
<body>
    <div class="main-content">
        <div class="container">
            <div class="card" id="heading"><h1>Select a Student for <?php echo $position_name ?></h1></div>
            <div class="card" id="tbl">
                <?php 
                
                if (mysqli_num_rows($result) > 0) {
                    echo "<form id='appointmentForm' action='appointment-non.php' method='POST'>";
                    echo "<input type='hidden' name='position_id' value='$position_id'>";
                
                    echo "<table id='myTable' class='display'>";
                    echo "<thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Grade</th>
                                <th>Program</th>
                                <th></th>
                            </tr>
                          </thead>";
                    echo "<tbody>";
                
                    while ($row = mysqli_fetch_assoc($result)) {
                        $voter_id = $row['voter_id'];
                        $user_name = $row['user_name'];
                        $voter_grade = $row['voter_grade'];
                        $program_code = $row['program_code'];
                        $img = $row['user_profile'];

                        function is_base64($data) {
                            $decodedData = base64_decode($data, true);
                            return $decodedData !== false && base64_encode($decodedData) === $data;
                        }
                
                        if (!is_base64($img)) {
                            $img = base64_encode($img);
                        }
                        $userprof = 'data:image/jpeg;base64,' . $img;

                        echo "<tr>
                                <td><img src='$userprof' alt='Image'></td>
                                <td>$user_name</td>
                                <td>$voter_grade</td>
                                <td>$program_code</td>
                                <td><button type='button' class='appoint-btn' data-voter-id='$voter_id'><i class='bx bxs-user-check'></i>Appoint student</button></td>
                              </tr>";
                    }
                
                    echo "</tbody>";
                    echo "</table>";
                    echo "</form>";
                } else {
                    echo "<p>No eligible students for $position_name.</p>";
                }
                
                
                
                ?>
                <div id="confirmationModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <p>Are you sure you want to appoint this student?</p>
                        <button id="confirmButton" type="submit">Yes</button>
                        <button id="cancelButton" type="button">Cancel</button>
                    </div>
                </div>
            </div>
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

            let voterId = null;

            $('.appoint-btn').on('click', function() {
                voterId = $(this).data('voter-id'); 
                $('#confirmationModal').show();
        });

    $('#confirmButton').on('click', function() {
        if (voterId) {
            $('<input>').attr({
                type: 'hidden',
                name: 'voter_id',
                value: voterId
            }).appendTo('#appointmentForm');

            $('#appointmentForm').submit();
        }
        $('#confirmationModal').hide();
    });

    $('#cancelButton, .close').on('click', function() {
        $('#confirmationModal').hide(); 
        voterId = null; 
    });

    $(window).on('click', function(event) {
        if ($(event.target).is('#confirmationModal')) {
            $('#confirmationModal').hide();
            voterId = null;
        }
    });
});
    </script>
</body>
</html>