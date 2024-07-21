<?php 

    session_start();
    include 'db.php';
    require 'config.php';

    $dateTime = new DateTime();

    $formattedDate = $dateTime->format('Y-m-d');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/ballot_style.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
</head>
<body>
    <header class="site-header">
        <div class="site-identity">
            <a href="#"><img src="assets/logo/STI-LOGO.png" alt="STI logo" /></a>
             <a href="#"><img src="assets/logo/COL-LOGO.png" alt="COL logo" /></a>
            <h1>UNOFFICIAL E-BALLOT OF STI COLLEGE ILIGAN</h1>
        </div> 
    </header>
    <div class="header-content">
        <div class="box" id="box1"><h3>September 32, 2024 STI College Council of Leaders Election</h3>
            <p>Quezon Avenue corner Mabini Street, Iligan City 9200</p>
            <p>Type: <strong>COL</strong></p>
            <h3>INSTRUCTIONS FOR VOTING</h3>
            <p><strong>(1) Select the Appropriate Number of Candidates:</strong> For example, if the system allows you to vote for up to three candidates, make sure you choose exactly three, not more or less.</p>
            <p><strong>(2) Review Your Choices Carefully:</strong> Thoroughly review all available options and candidates before making your selection.</p>
        </div>
        <div class="box" id="box2"><?php 
            $qry1 = "SELECT *, DATE(date_registered) AS date_registered FROM voters WHERE voter_id = (SELECT voter_id FROM voters WHERE user_id = '".$_SESSION['userID']."')";
            $result = $conn->query($qry1);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $voter_id = $row['voter_id'];
                    $voter_gender = $row['voter_gender'];
                    $voter_grade = $row['voter_grade'];
                    $program = $row['program_code'];
                    $voter_club = $row['voter_club'];
                    $academic_yr = $row['academic_year'];
                    $voter_registered = $row['date_registered'];
                    
                    echo "<p>Date: $formattedDate</p>";
                    echo "<p><br></p>";
                    echo "<p>Voter ID: <strong>$voter_id</strong></p>";
                    echo "<p>Name: <strong>$_SESSION[displayName]</strong></p>";
                    echo "<p>Gender: <strong>$voter_gender</strong></p>";
                    echo "<p>Program: <strong>$voter_grade $program</strong></p>";
                    echo "<p>Club: <strong>$voter_club</strong></p>";
                    echo "<p>Academic Year: <strong>$academic_yr</strong></p>";
                    echo "<p>Registration Date: <strong>$voter_registered</strong></p>";
                } 
            } else {
                echo "NO DATA AVAILABLE";
            }
        ?></div>
        </div>
        <div class="main-content">
            <div class="box" ><h1>PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>TERTIARY VICE PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>SENIOR HIGH VICE PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>INTERNAL SECRETARY / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>EXTERNAL SECRETARY / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>TREASURER / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>AUDITOR / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>PUBLIC INFORMATION OFFICER / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (ABM) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (HUMSS) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (STEM) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (CUART) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (MAWD) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (ABM) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (HUMSS) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (STEM) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (CUART) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (MAWD) / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSTM 1A REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSTM 1B REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSTM 2 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </>
            <div class="box" ><h1>BSTM 3 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSTM 4 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSIS 1 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSIS 2 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSIS 3 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
            <div class="box" ><h1>BSIS 4 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                    <div class="item"><input type="radio" name="choices[]" value="1" id="checkbox1"> <label for="checkbox1">Checkbox 1</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="2" id="checkbox2"> <label for="checkbox2">Checkbox 2</label></div>
                    <div class="item"><input type="radio" name="choices[]" value="3" id="checkbox3"> <label for="checkbox3">Checkbox 3</label></div>
                </div>
        </div>
        
    </body>
    <script>
    </script>
    </html>