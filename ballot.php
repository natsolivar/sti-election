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
        <form action="vote_count.php" method="post" id="vote">
            <div class="box" ><h1>PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                    <?php 
                        
                        $qry2 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'PRES'";
                        $result = mysqli_query($conn, $qry2);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='president' value='$name' id='pres' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>

                </div>
            <div class="box" ><h1>TERTIARY VICE PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'TERVP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='tervpresident' value='$name' id='tervp' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
                <div class="box" ><h1>SENIOR HIGH VICE PRESIDENT / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'SHVP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='shvpresident' value='$name' id='shvp' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>INTERNAL SECRETARY / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'ENTSEC'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='entsec' value='$name' id='entsec' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>EXTERNAL SECRETARY / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'EXTSEC'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='extsec' value='$name' id='extsec' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>TREASURER / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'TREA'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='trea' value='$name' id='trea' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>AUDITOR / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'AUD'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='aud' value='$name' id='aud' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>PUBLIC INFORMATION OFFICER / Vote for 1</h1></div>
                <div class="container">
                    <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'PIO'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='pio' value='$name' id='pio' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (ABM) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11ABMREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11abmrep' value='$name' id='11abmrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (HUMSS) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11HUMSSREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11humssrep' value='$name' id='11hummsrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (STEM) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11STEMREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11stemrep' value='$name' id='11stemrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (CUART) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11CUARTREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11cuartrep' value='$name' id='11cuartrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 11 REPRESENTATIVE (MAWD) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '11MAWDREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='11mawdrep' value='$name' id='11mawdrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (ABM) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12ABMREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12abmrep' value='$name' id='12abmrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (HUMSS) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12HUMSSREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12humssrep' value='$name' id='12hummsrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (STEM) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12STEMREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12stemrep' value='$name' id='12stemrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (CUART) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12SCUARTREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12cuartrep' value='$name' id='12cuartrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>GRADE 12 REPRESENTATIVE (MAWD) / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = '12MAWDREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='12mawdrep' value='$name' id='12mawdrep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 1A REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM1AREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm1arep' value='$name' id='bstm1arep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 1B REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM1BREP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm1brep' value='$name' id='bstm1brep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 2 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM2REP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm2rep' value='$name' id='bstm2rep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 3 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM3REP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm3rep' value='$name' id='bstm3rep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSTM 4 REPRESENTATIVE/ Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSTM4REP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bstm4rep' value='$name' id='bstm4rep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 1 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS1REP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis1rep' value='$name' id='bsis1rep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 2 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS2REP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis2rep' value='$name' id='bsis2rep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 3 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS3REP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis3rep' value='$name' id='bsis3rep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
            <div class="box" ><h1>BSIS 4 REPRESENTATIVE / Vote for 1</h1></div>
                <div class="container">
                <?php 
                        
                        $qry3 = "SELECT u.user_name FROM candidate c JOIN voters v ON c.voter_id = v.voter_id JOIN users u ON v.user_id = u.user_id WHERE c.position_id = 'BSIS4REP'";
                        $result = mysqli_query($conn, $qry3);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $name = $row['user_name'];
                                echo "<div class='item'><input type='radio' name='bsis4rep' value='$name' id='bsis4rep' required><label for='checkbox1'>$name</label></div>";
                            }
                        } else {
                            echo "No candidate";
                        }
                    
                    ?>
                </div>
                <button type="submit" class="btn btn-primary" name="vote">Submit</button>  
        </form>
        </div>   
    </body>
    <script>
    </script>
    </html>