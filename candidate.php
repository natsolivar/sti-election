<?php 
    session_start();
    include 'sidebar.php';
    include 'db.php';
    include 'session.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles/candidate_style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>EMVS</title>
    </head>
    <body>
    <div class="main-content">
        <div class="header">
            <p>Running Candidates</p>
        </div>
        <header>
            <h1>For PRESIDENT</h1>
        </header>
        <main>
        <div class="flex-container">
                <?php 

                    $qry1 = "SELECT * FROM candidate c
                    LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                    INNER JOIN position p ON c.position_id = p.position_id 
                    INNER JOIN voters v ON c.voter_id = v.voter_id 
                    INNER JOIN users u ON v.user_id = u.user_id 
                    WHERE c.position_id = 'PRES' AND c.status = 'Accepted'";
                    $result = mysqli_query($conn, $qry1);

                    if($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $img1 = $row['image'];
                            $user_name = $row['user_name'];
                            $pos = $row['position_name'];
                            $candidate_id = $row['candidate_id'];

                            $user_name = str_replace("(Student)", "", $user_name);
                            $name_parts = explode(", ", trim($user_name));
                            if (count($name_parts) == 2) {
                                $formatted_name = $name_parts[1] . " " . $name_parts[0];
                            } else {
                                $formatted_name = $user_name;
                            }
                            
                            echo "<div class='flex-item'>";
                            if (isset($img1) && !empty($img1)) {
                                $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                                echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                            } else {
                                echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                            }
                            echo "<div class='details'>
                                    <h2>$formatted_name</h2>
                                    <p><b>$pos</b></p>
                                </div>";
                            echo "</div>";
                        }
                    } else {
                        echo "No Candidate";
                    }?>
        </div>
        </main> 
        <header>
            <h1>For VICE PRESIDENT</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE c.position_id = 'TERVP' OR c.position_id = 'SHVP' AND c.status = 'Accepted'";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For SECRETARY</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                 WHERE c.position_id = 'EXTSEC' OR c.position_id = 'INTSEC' AND c.status = 'Accepted'";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For TREASURER</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE c.position_id = 'TREA' AND c.status = 'Accepted'";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For AUDITOR</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE c.position_id = 'AUD' AND c.status = 'Accepted'";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For PUBLIC INFORMATION OFFICER</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE c.position_id = 'PIO' AND c.status = 'Accepted'";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For GRADE 11 REPRESENTATIVES</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE c.position_id = '11ABMREP' OR c.position_id = '11STEMREP' OR c.position_id = '11HUMSSREP' OR c.position_id = '11CUARTREP' OR c.position_id = '11MAWDREP' AND c.status = 'Accepted'
                ORDER BY c.position_id";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For GRADE 12 REPRESENTATIVES</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE c.position_id = '12ABMREP' OR c.position_id = '12STEMREP' OR c.position_id = '12HUMSSREP' OR c.position_id = '12CUARTREP' OR c.position_id = '12MAWDREP' AND c.status = 'Accepted'
                ORDER BY c.position_id";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For BSTM REPRESENTATIVES</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE (c.position_id = 'BSTM1AREP' OR c.position_id = 'BSTM2AREP' OR c.position_id = 'BSTM2BREP' OR c.position_id = 'BSTM3REP' OR c.position_id = 'BSTM4REP') 
                AND c.status = 'Accepted'
                ORDER BY c.position_id";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
        <header>
            <h1>For BSIS REPRESENTATIVES</h1>
        </header>
        <main>
        <div class="flex-container">
            <?php 

                $qry2 = "SELECT * FROM candidate c 
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                INNER JOIN position p ON c.position_id = p.position_id 
                INNER JOIN voters v ON c.voter_id = v.voter_id 
                INNER JOIN users u ON v.user_id = u.user_id 
                WHERE c.position_id = 'BSIS1REP' OR c.position_id = 'BSIS2REP' OR c.position_id = 'BSIS3REP' OR c.position_id = 'BSIS4REP' AND c.status = 'Accepted'
                ORDER BY c.position_id";
                $result = mysqli_query($conn, $qry2);

                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $img1 = $row['image'];
                        $user_name = $row['user_name'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];

                        $user_name = str_replace("(Student)", "", $user_name);
                        $name_parts = explode(", ", trim($user_name));
                        if (count($name_parts) == 2) {
                            $formatted_name = $name_parts[1] . " " . $name_parts[0];
                        } else {
                            $formatted_name = $user_name;
                        }

                        echo "<div class='flex-item'>";
                        if (isset($img1) && !empty($img1)) {
                            $image_url = 'data:image/jpeg;base64,' . base64_encode($img1);
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$image_url' alt='Image 1'></a>";
                        } else {
                            echo "<a href='candidate_prof.php?id=$candidate_id'><img src='assets/images/profile.png' alt='Image 1' value='$candidate_id'></a>";
                        }
                        echo "<div class='details'>
                                <h2>$formatted_name</h2>
                                <p><b>$pos</b></p>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    echo "No Candidate";
                }?>
            </div>
        </main> 
    </div>
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            history.pushState(null, null, location.href);

            window.addEventListener('popstate', function (event) {
                if (confirm("Navigating back will log you out. Do you want to proceed?")) {
                    window.location.href = "logout.php?backnav=true";
                } else {
                    history.pushState(null, null, location.href);
                }
            });
        });
    </script>
</html>