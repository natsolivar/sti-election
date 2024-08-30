<?php 

    session_start();
    include 'db.php';
    require 'config.php';
    include 'check_login.php';
    

    $dateTime = new DateTime();

    $formattedDate = $dateTime->format('Y-m-d');

    $query1 = "SELECT * FROM voters WHERE user_id = '$_SESSION[userID]'";
    $result = mysqli_query($conn, $query1);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $voter_id = $row['voter_id'];
            $voter_grade = $row['voter_grade'];
            $voter_program = $row['program_code'];

            $query2 = "SELECT voter_id FROM registered_votes WHERE voter_id = '$voter_id'";
            $result_votes = mysqli_query($conn, $query2);

            if($result_votes->num_rows > 0) {
                echo "<script>
                alert('You have already voted.');
                window.location.href = 'javascript:history.go(-1)'; 
                </script>";
            } else {

            }
        } else {
            echo "<script>
                    alert('Error retrieving voter information.');
                    window.location.href = 'javascript:history.go(-1)'; 
                  </script>";
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/ballot_style.css?v=<?php echo time(); ?>">
    <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
</head>
<body>

<div class="content">
    <header class="site-header">
        <div class="site-identity">
            <a href="#"><img src="assets/logo/STI-LOGO.png" alt="STI logo" /></a>
             <a href="#"><img src="assets/logo/COL-LOGO.png" alt="COL logo" /></a>
            <h1>UNOFFICIAL E-BALLOT OF STI COLLEGE ILIGAN</h1>
        </div> 
    </header>
    <div class="header-content">
        <div class="box" id="box1"><h3>STI College Council of Leaders Election</h3>
            <p>Quezon Avenue corner Mabini Street, Iligan City 9200</p>
            <p>Type: <strong>COL</strong></p>
            <h3>INSTRUCTIONS FOR VOTING</h3>
            <p><strong>(1) Select the Appropriate Number of Candidates:</strong> For example, if the system allows you to vote for two candidates, make sure you choose exactly two, not more or less.</p>
            <p><strong>(2) Candidate Selection by Grade or Program:</strong> Vote a candidate based on your Grade/Year level and Academic Program.</p>
            <p><strong>(3) Review Your Choices Carefully:</strong> Thoroughly review all available options and candidates before making your selection.</p>
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
        <form action="vote_count.php" method="post" id="vote">
            <div class="box" ><h1>PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                    <?php 
                        
                        $qry2 = "SELECT u.user_name FROM candidate c INNER JOIN voters v ON c.voter_id = v.voter_id INNER JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'PRES' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry2);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='president' value='$name' id='pres'><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>

                </div>
            <div class="box" ><h1>TERTIARY VICE PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (($voter_grade == 'g11' || $voter_grade == 'g12'));
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'TERVP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];

                                $disabled = $disable_vote ? 'disabled' : '';
                                
                                echo "<div class='item'><input type='radio' name='tervpresident' value='$name' id='tervp' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
                <div class="box" ><h1>SENIOR HIGH VICE PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $disable_vote = (!($voter_grade == 'g11' || $voter_grade == 'g12'));
                        $disabled = $disable_vote ? 'disabled' : '';

                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'SHVP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='shvpresident' value='$name' id='shvp' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>INTERNAL SECRETARY / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'INTSEC' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='intsec' value='$name' id='intsec'><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>EXTERNAL SECRETARY / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'EXTSEC' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='extsec' value='$name' id='extsec'><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>TREASURER / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'TREA' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='trea' value='$name' id='trea'><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>AUDITOR / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'AUD' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='aud' value='$name' id='aud'><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>PUBLIC INFORMATION OFFICER / Vote for 2</h1></div>
                <div class="container">
                    <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'PIO' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            $count = 1;
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='checkbox' name='pio[]' value='$name' id='pio_{$count}_$name'><label for='checkbox1'>$name</label></div>";

                            $count++;

                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (ABM) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g11' && $voter_program == 'ABM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11ABMREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11abmrep' value='$name' id='11abmrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (HUMSS) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $disable_vote = (!($voter_grade == 'g11' && $voter_program == 'HUMSS'));
                        $disabled = $disable_vote ? 'disabled' : '';

                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11HUMSSREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11humssrep' value='$name' id='11humssrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (STEM) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g11' && $voter_program == 'STEM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11STEMREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11stemrep' value='$name' id='11stemrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (CUART) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g11' && $voter_program == 'CUART'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11CUARTREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11cuartrep' value='$name' id='11cuartrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (MAWD) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g11' && $voter_program == 'MAWD'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11MAWDREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11mawdrep' value='$name' id='11mawdrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (ABM) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g12' && $voter_program == 'ABM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12ABMREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12abmrep' value='$name' id='12abmrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (HUMSS) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g12' && $voter_program == 'HUMSS'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12HUMSSREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12humssrep' value='$name' id='12humssrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (STEM) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g12' && $voter_program == 'STEM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12STEMREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12stemrep' value='$name' id='12stemrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (CUART) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g12' && $voter_program == 'CUART'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12SCUARTREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12cuartrep' value='$name' id='12cuartrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (MAWD) / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == 'g12' && $voter_program == 'MAWD'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12MAWDREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12mawdrep' value='$name' id='12mawdrep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 1 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '2nd year' && $voter_program == 'BSTM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM1AREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm1arep_' value='$name' id='bstm1arep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 2-A REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '2nd year' && $voter_program == 'BSTM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM2AREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm2arep' value='$name' id='bstm2arep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 2-B REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '2nd year' && $voter_program == 'BSTM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM2BREP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm2brep' value='$name' id='bstm2brep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 3 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $disable_vote = (!($voter_grade == '3rd year' && $voter_program == 'BSTM'));
                        $disabled = $disable_vote ? 'disabled' : '';

                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM3REP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm3rep' value='$name' id='bstm3rep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 4 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '4th year' && $voter_program == 'BSTM'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM4REP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm4rep' value='$name' id='bstm4rep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 1 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '1st year' && $voter_program == 'BSIS'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS1REP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis1rep' value='$name' id='bsis1rep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 2 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '2nd year' && $voter_program == 'BSIS'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS2REP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis2rep' value='$name' id='bsis2rep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 3 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '3rd year' && $voter_program == 'BSIS'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS3REP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis3rep' value='$name' id='bsis3rep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 4 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 

                        $disable_vote = (!($voter_grade == '4th year' && $voter_program == 'BSIS'));
                        $disabled = $disable_vote ? 'disabled' : '';
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS4REP' AND c.status = 'Accepted'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis4rep' value='$name' id='bsis4rep' $disabled><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
                <button class="submit-ballot" id="scrollButton" name="vote" style="display: none;">
                    <div>
                        <div class="pencil"></div>
                        <div class="folder">
                            <div class="top">
                                <svg viewBox="0 0 24 27">
                                    <path d="M1,0 L23,0 C23.5522847,-1.01453063e-16 24,0.44771525 24,1 L24,8.17157288 C24,8.70200585 23.7892863,9.21071368 23.4142136,9.58578644 L20.5857864,12.4142136 C20.2107137,12.7892863 20,13.2979941 20,13.8284271 L20,26 C20,26.5522847 19.5522847,27 19,27 L1,27 C0.44771525,27 6.76353751e-17,26.5522847 0,26 L0,1 C-6.76353751e-17,0.44771525 0.44771525,1.01453063e-16 1,0 Z"></path>
                                </svg>
                            </div>
                            <div class="paper"></div>
                        </div>
                    </div>
                    Submit Ballot
                </button>
        </form>
        </div>  
    </div> 

    <div id="voteModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Your Vote</h2>
            <p id="voteSummary"></p>
            <button id="editVote">Edit Vote</button>
            <button id="confirmVote">Confirm Vote</button>
        </div>
    </div>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="pio[]"]');
            const maxSelection = 2;
            let selectedCheckboxes = [];

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (checkbox.checked) {
                        selectedCheckboxes.push(checkbox);
                    } else {
                        selectedCheckboxes = selectedCheckboxes.filter(cb => cb !== checkbox);
                    }
                    if (selectedCheckboxes.length > maxSelection) {
                        const oldestCheckbox = selectedCheckboxes.shift();
                        oldestCheckbox.checked = false;
                    }
                });
            });
        });

        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY + window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            
            if (scrollPosition >= documentHeight) {
                document.getElementById('scrollButton').style.display = 'block';
            } else {
                document.getElementById('scrollButton').style.display = 'none';
            }
        });


         window.addEventListener('load', function() {
        setTimeout(function() {
            const loading = document.getElementById('loading');
            const content = document.getElementById('content');
            loading.style.display = 'none';
            content.style.display = 'block'; 
        }, 2000); 
    });

    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('vote');
    const modal = document.getElementById('voteModal');
    const closeModal = document.querySelector('.modal .close');
    const voteSummary = document.getElementById('voteSummary');
    const editVote = document.getElementById('editVote');
    const confirmVote = document.getElementById('confirmVote');

    const categoryTitles = {
        'president': 'President',
        'tervpresident': 'Tertiary Vice President',
        'shvpresident': 'Senior High Vice President',
        'intsec': 'Internal Secretary',
        'extsec': 'External Secretary',
        'trea': 'Treasurer',
        'aud': 'Auditor',
        'pio': 'PIO',
        '11abmrep': '11 ABM Representative',
        '11humssrep': '11 HUMSS Representative',
        '11stemrep': '11 STEM Representative',
        '11cuartrep': '11 CUART Representative',
        '11mawdrep': '11 MAWD Representative',
        '12abmrep': '12 ABM Representative',
        '12humssrep': '12 HUMSS Representative',
        '12stemrep': '12 STEM Representative',
        '12cuartrep': '12 CUART Representative',
        '12mawdrep': '12 MAWD Representative',
        'bstm1arep_': 'BSTM 1 Representative',
        'bstm2arep': 'BSTM 2-A Representative',
        'bstm2brep': 'BSTM 2-B Representative',
        'bstm3rep': 'BSTM 3 Representative',
        'bstm4rep': 'BSTM 4 Representative',
        'bsis1rep': 'BSIS 1 Representative',
        'bsis2rep': 'BSIS 2 Representative',
        'bsis3rep': 'BSIS 3 Representative',
        'bsis4rep': 'BSIS 4 Representative'
    };

        form.addEventListener('submit', function (event) {
            event.preventDefault(); 

            const categories = Object.keys(categoryTitles);
            let summaryHTML = '<div class="vote-summary">';

            categories.forEach(category => {
                if (category === 'pio') {
                    const selectedCheckboxes = Array.from(document.querySelectorAll(`input[name="pio[]"]:checked`));
                    const votes = selectedCheckboxes.map(cb => {
                        let name = cb.nextElementSibling.textContent.trim();
                        name = name.replace("(Student)", "").trim();
                        return name;
                    }).join(', ') || 'None';
                    const title = categoryTitles[category];
                    summaryHTML += `<p><b>${title}:</b> ${votes}</p>`;
                } else {
                    const selectedRadio = document.querySelector(`input[name="${category}"]:checked`);
                    let vote = selectedRadio ? selectedRadio.nextElementSibling.textContent.trim() : 'None';
                    if (vote !== 'None') {
                        vote = vote.replace("(Student)", "").trim();
                    }
                    const title = categoryTitles[category];
                    summaryHTML += `<p><b>${title}:</b> ${vote}</p>`;
                }
            });

            summaryHTML += '</div>';

            voteSummary.innerHTML = summaryHTML;
            modal.style.display = 'block';
        });

        closeModal.addEventListener('click', function () {
            modal.style.display = 'none'; 
        });

        editVote.addEventListener('click', function () {
            modal.style.display = 'none'; 
        });

        confirmVote.addEventListener('click', function () {
            form.submit(); 
        });
    });

    </script>
    </html>