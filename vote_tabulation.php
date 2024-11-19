<?php 
    session_start();
    include 'sidebar.php';
    include 'db.php';
    include 'session.php';

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
    
    $currentDate = date('Y-m-d');
    $schoolYear = getSchoolYear($currentDate);

    $qry1 = "SELECT COUNT(r_vote_id) AS vote_count FROM registered_votes WHERE academic_year = '$schoolYear'";
    $res = mysqli_query($conn, $qry1);

    $row = mysqli_fetch_array($res);
    $vote_count = $row['vote_count'];

    if ($vote_count == 0) {
        echo "<script>alert('Result is not yet ready');</script>";
        echo "<script>location.href='javascript:history.go(-1)';</script>";
    }

    $qry2 = "SELECT COUNT(DISTINCT voter_id) AS total FROM registered_votes";
    $ress = mysqli_query($conn, $qry2);

    if ($ress -> num_rows > 0 ) {
        while ($rowss = $ress -> fetch_assoc()) {
            $overall = $rowss['total'];

        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>EMVS</title>
    </head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        body {
            font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-y: auto;
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            grid-template-columns: auto 1fr;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, auto);
            justify-content: space-around;
            gap: 15px;
        }

        .grid-container .grid-card {
            display: grid;
            grid-template-columns: repeat(2, auto);
            border-radius: 15px;
            padding: 2em;
        }

        .grid-container .grid-card .inner-card {
            display: grid;
            padding: 1em;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0;
            margin: 0;
            > p {
                padding: 0;
                margin: 0;
            }
        }

        .grid-container .grid-card #title {
            grid-column: span 2;
            padding-bottom: 0.5em;
        }

        .grid-container .grid-card #img-card img {
            margin-top: 1rem;
            min-width: 150px;
            max-width: 150px;
            border-radius: 5px;
        }

        .grid-container .grid-card #img-details p {
            padding: 1rem;
            font-size: 1.5em;
        }

        .grid-container .grid-card #votes {
            color: green;
        }

        @media(max-width: 800px) {
            .grid-container {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
            }
        } 

    </style>
    <body>
        <main>
            <h1>Live Vote Tabulation</h1>
            <div class="grid-container">
                <?php
                
                $position_qry = "SELECT position_id, position_name FROM position";
                $position_res = mysqli_query($conn, $position_qry);
                
                if ($position_res && mysqli_num_rows($position_res) > 0) {
                    while ($position_row = mysqli_fetch_assoc($position_res)) {
                        $position_id = $position_row['position_id'];
                        $title = $position_row['position_name'];

                        $qry = "SELECT u.user_name, c.candidate_id, p.party_name, pos.position_id, pos.position_name, i.image, vs.total_votes
                                FROM candidate c
                                LEFT JOIN images i ON c.candidate_id = i.candidate_id
                                LEFT JOIN voters v ON c.voter_id = v.voter_id
                                LEFT JOIN users u ON v.user_id = u.user_id
                                LEFT JOIN party p ON c.party_code = p.party_code
                                LEFT JOIN position pos ON c.position_id = pos.position_id
                                LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                                WHERE pos.position_id = '$position_id' AND c.status = 'Accepted'
                                ORDER BY CAST(pos.position_rank AS UNSIGNED) ASC";

                        $res = mysqli_query($conn, $qry);
                        
                        if ($res && mysqli_num_rows($res) > 0) {
                            echo "<div class='grid-card'>";
                            echo "<div class='inner-card' id='title'>";
                            echo "<h2>$title</h2>";
                            echo "</div>";

                            $max_votes = 0;

                            while ($rows = $res->fetch_assoc()) {
                                if ($rows['total_votes'] > $max_votes) {
                                    $max_votes = $rows['total_votes'];
                                }
                            }

                            $res->data_seek(0); 
                            while ($rows = $res->fetch_assoc()) {
                                $usern = $rows['user_name'];
                                $partyy = $rows['party_name'];
                                $position = $rows['position_name'];
                                $img1 = $rows['image'];
                                $tvotes = $rows['total_votes'];

                                $usern = str_replace("(Student)", "", $usern);
                                $name_parts = explode(", ", trim($usern));
                                if (count($name_parts) == 2) {
                                    $formatted_name1 = $name_parts[1] . " " . $name_parts[0];
                                } else {
                                    $formatted_name1 = $usern;
                                }

                                $total_votes = ($tvotes * 100) / $overall;
                                $total_votes_percentage = number_format($total_votes, 2) . '%'; 
                                
                                echo "<div class='inner-card' id='img-card'>";
                                if ($img1) {
                                    $image_url1 = 'data:image/jpeg;base64,' . base64_encode($img1);
                                    echo "<img src='$image_url1' alt='Candidate image' >";
                                    echo "<p><strong>$formatted_name1</strong></p>";
                                    echo "<p style='font-style: italic; font-size: 0.8rem;'>$partyy</p>";
                                } else {
                                    echo "<img src='assets/images/profile.png' alt='Image 1' alt='Candidate image'>";
                                    echo "<p><strong>$formatted_name1</strong></p>";
                                    echo "<p style='font-style: italic; font-size: 0.8rem;'>$partyy</p>";
                                }
                                echo "</div>";

                                $style = $tvotes == $max_votes ? "font-weight: bold; font-size: 2rem; color: green;" : "";

                                echo "<div class='inner-card' id='img-details'>";
                                echo "<p id='votes' style='$style'>$total_votes_percentage</p>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                    }
                } else {
                    echo "<p>No positions available.</p>";
                }
    
                ?>
            </div>
        </main>
    </body>
</html>