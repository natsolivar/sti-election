<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';

    date_default_timezone_set('Asia/Hong_Kong');

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
    
    $currentcalendar = date('Y-m-d');
    $schoolYear = getSchoolYear($currentcalendar);
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles/council_style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>EMVS</title>
    </head>
    <body>
    <div class="main-content">
        <div class="header">
            <p>The Council of Leaders</p>
        </div>
        <div class="container">
            <h1>COL Advisor</h1>
            <div class="pres">
                

                    <div class='flex' id='flex1'>
                    <div class='img-container'>
                    <img src='assets/images/sirpebs.jpg' alt='Council Advisor'>
                    </div>
                    <div class='details'>
                    <h3 class='title'>Mr. Phillip Edgar Sabayle</h3>
                    <p class='price'>Student Affairs Officer</p>
                    </div>
                    </div>


            </div>
        </div>
        <div class="container">
    <?php 
        $positionGroups = [
            'Vice President' => ['Senior High Vice President', 'Tertiary Vice President'],
            'Secretary' => ['Internal Secretary', 'External Secretary'],
            'Grade 11 Representatives' => ['Grade 11 ABM Representative', 'Grade 11 HUMSS Representative', 'Grade 11 STEM Representative', 'Grade 11 CUART Representative', 'Grade 11 MAWD Representative'],
            'Grade 12 Representatives' => ['Grade 12 ABM Representative', 'Grade 12 HUMSS Representative', 'Grade 12 STEM Representative', 'Grade 12 CUART Representative', 'Grade 12 MAWD Representative'],
            'BSIS Representatives' => ['BSIS 1 Representative', 'BSIS 2 Representative', 'BSIS 3 Representative', 'BSIS 4 Representative'],
            'BSTM Representatives' => ['BSTM 1A Representative', 'BSTM 1B Representative', 'BSTM 2 Representative', 'BSTM 2A Representative', 'BSTM 2B Representative', 'BSTM 3 Representative', 'BSTM 4 Representative']
        ];

        foreach ($positionGroups as $groupName => $positions) {
            echo "<h1 style='text-transform: uppercase;'>$groupName</h1>";
            echo "<div class='pres'>";

            $positionPlaceholders = implode(',', array_map(fn($p) => "'$p'", $positions));
            $qryCouncil = "SELECT p.position_name, u.user_name, i.image 
                           FROM council co
                           INNER JOIN position p ON co.position_id = p.position_id
                           INNER JOIN voters v ON co.voter_id = v.voter_id
                           INNER JOIN users u ON v.user_id = u.user_id
                           LEFT JOIN candidate c ON co.candidate_id = c.candidate_id
                           LEFT JOIN images i ON c.candidate_id = i.candidate_id
                           WHERE p.position_name IN ($positionPlaceholders) 
                           AND
                           co.academic_year = '$schoolYear'
                           AND
                           co.status = 'ACTIVE'";
            $resCouncil = mysqli_query($conn, $qryCouncil);

            if ($resCouncil->num_rows > 0) {
                while ($rows = $resCouncil->fetch_assoc()) {
                    $fname = htmlspecialchars($rows['user_name'], ENT_QUOTES, 'UTF-8');
                    $position = htmlspecialchars($rows['position_name'], ENT_QUOTES, 'UTF-8');
                    $img = isset($rows['image']) ? 'data:image/jpeg;base64,' . base64_encode($rows['image']) : 'assets/images/profile.png';


                    echo "<div class='flex' id='flex1'>";
                    echo "<div class='img-container'>";
                    echo "<img src=$img alt=''>";
                    echo "</div>";
                    echo "<div class='details'>";
                    echo "<h3 class='title'>$fname</h3>";
                    echo "<p class='price'>$position</p>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No one appointed yet.</p>";
            }

            echo "</div>";
        }
    ?>
</div>
    </div>
    </body>
</html>