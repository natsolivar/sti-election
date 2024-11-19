<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';
    require 'config.php';

    if (!isset($_SESSION['userID'])) {
        header('Location: index');
        exit();
    }

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

    function getGreeting() {
        $currentTime = new DateTime(null, new DateTimeZone('Asia/Hong_Kong'));
        $hour = $currentTime->format('G');
    
        if ($hour < 12) {
            return "Good morning, ";
        } elseif ($hour <= 18) {
            return "Good afternoon, ";
        } else {
            return "Good evening, ";
        }
    }
    $time_diff = 12;

    $que = "SELECT date_start, date_end, date_initialized FROM c_period ORDER BY id DESC LIMIT 1";
    $res = mysqli_query($conn, $que);

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $date_start = $row['date_start'];
        $date_end = $row['date_end'];
        $date_initialized = $row['date_initialized'];

        $start_time = new DateTime($date_initialized);
        $end_time = new DateTime($date_end);

        $current_time = new DateTime("now", new DateTimeZone('Asia/Hong_Kong'));

        $start_time_adjusted = clone $start_time;
        $start_time_adjusted->modify("+$time_diff hours");

    } else {
        $date_start = $date_end = null;
    }

    $currentDate = new DateTime("now", new DateTimeZone('Asia/Hong_Kong'));
    $now = $currentDate->format('Y-m-d');
    $startDate = new DateTime($date_start, new DateTimeZone('Asia/Hong_Kong'));
    $endDate = new DateTime($date_end, new DateTimeZone('Asia/Hong_Kong'));


    $daysRemaining = $currentDate->diff($startDate)->days;

    $greetingMessage = getGreeting();

    $que = "SELECT date_start, date_end FROM e_period LIMIT 1";
    $results = mysqli_query($conn, $que);

    if ($results->num_rows > 0) {
        $row = $results->fetch_assoc();
        $date_start = $row['date_start'];
        $date_end = $row['date_end'];
    } else {
        $date_start = $date_end = null;
    }

   /* $qry1 = "SELECT u.user_name, 
                v.program_code, 
                v.voter_grade, 
                c.candidate_id, 
                c.candidate_details, 
                p.party_name, 
                pos.position_name, 
                i.image,
                COALESCE(support_counts.support_count, 0) AS support_count
            FROM candidate c
            LEFT JOIN images i ON c.candidate_id = i.candidate_id
            LEFT JOIN voters v ON c.voter_id = v.voter_id
            LEFT JOIN users u ON v.user_id = u.user_id
            LEFT JOIN party p ON c.party_code = p.party_code
            LEFT JOIN position pos ON c.position_id = pos.position_id
            LEFT JOIN (
                SELECT candidate_id, COUNT(support_id) AS support_count
                FROM support
                GROUP BY candidate_id
            ) AS support_counts ON c.candidate_id = support_counts.candidate_id
            WHERE c.status = 'Accepted';"; */
            
        $qry1 = "SELECT u.user_name, v.program_code, v.voter_grade, c.candidate_id, c.candidate_details, p.party_name, pos.position_name, i.image
            FROM candidate c
            LEFT JOIN images i ON c.candidate_id = i.candidate_id
            LEFT JOIN voters v ON c.voter_id = v.voter_id
            LEFT JOIN users u ON v.user_id = u.user_id
            LEFT JOIN party p ON c.party_code = p.party_code
            LEFT JOIN position pos ON c.position_id = pos.position_id
            WHERE c.status = 'Accepted' ORDER BY RAND ()
            LIMIT 1";
        $resultss = $conn->query($qry1);

        $weighted_candidates = [];
        $total_supports = 0;

        if ($resultss->num_rows > 0) {
            while ($row = $resultss->fetch_assoc()) {
                $candidate_id = $row['candidate_id'];
                $user_name = $row['user_name'];
                $details = $row['candidate_details'];
                $program = $row['program_code'];
                $img = $row['image'];
                $grade = $row['voter_grade'];
                $party = $row['party_name'];
                $position = $row['position_name'];
               // $support_count = $row['support_count'];

                $user_name = str_replace("(Student)", "", $user_name);
                $name_parts = explode(", ", trim($user_name));
                if (count($name_parts) == 2) {
                    $formatted_name = $name_parts[1] . " " . $name_parts[0];
                    $fname = $name_parts[1];
                } else {
                    $formatted_name = $user_name;
                }
                /*
                for ($i = 0; $i < $support_count; $i++) {
                    $weighted_candidates[] = $candidate_id;
                }
                $total_supports += $support_count;
            }
        }

        if (!empty($weighted_candidates)) {
            $selected_candidate_id = $weighted_candidates[array_rand($weighted_candidates)];
        
            $c_id = $selected_candidate_id;
        } */
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
    <link rel="stylesheet" type="text/css" href="styles/style.css?v=<?php echo time(); ?>">
    <title>EMVS</title>
</head>
<body>
    <main>
        <div class="grid-container-1">
            <div class="card" id="card-1">
                <?php 
                    if (isset($_SESSION['message'])) {
                        echo '<p style="color: green; margin-top: 20px;">' . $_SESSION['message'] . '</p>';
                        unset($_SESSION['message']); 
                    }
                ?>
                <h1><?php 
                    echo $greetingMessage;
                    echo htmlspecialchars($_SESSION['userName']); ?>!</h1>
                    <?php 
                    
                    $query = "SELECT 
                                CASE 
                                    WHEN CURDATE() < date_start THEN DATEDIFF(date_start, CURDATE()) 
                                    WHEN CURDATE() BETWEEN date_start AND date_end THEN DATEDIFF(date_end, CURDATE()) 
                                    ELSE 0 
                                END AS days_remaining,
                                date_start,
                                date_end
                            FROM e_period
                            LIMIT 1"; 
                            $result = mysqli_query($conn, $query);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $days_remaining = $row['days_remaining'];

                                if ($currentDate < $startDate) {
                                    echo "<p>Registration of Candidacy starts in <strong>{$daysRemaining} " . ($daysRemaining == 1 ? "day" : "days") . "</strong></p>";
                                } 

                                if (strtotime($row['date_start']) > time()) {
                                    echo "<p>Voting starts in <strong>{$days_remaining} " . ($days_remaining == 1 ? "day" : "days") . "</strong></p>";

                                } elseif (strtotime($row['date_start']) <= time() && strtotime($row['date_end']) >= time()) { 
                                    echo "<p>Voting ends in <strong>{$days_remaining} " . ($days_remaining == 1 ? "day" : "days") . "</strong></p>";

                                    $qry1 = "SELECT COUNT(r_vote_id) AS vote_count FROM registered_votes WHERE academic_year = '$schoolYear'";
                                            $res = mysqli_query($conn, $qry1);

                                            $row = mysqli_fetch_array($res);
                                            $vote_count = $row['vote_count'];

                                            if ($vote_count > 0) {
                                                echo '<div class="status-container">';
                                                echo '<div class="status-indicator"></div>Unofficial voting result is live!<a href="vote_tabulation">Click here</a>';
                                                echo '</div>';
                                            }


                                } else {
                                    echo "<p>The election has ended.</p>";
                                }
                            } else {
                                echo "<p>Election in ? days</p>";
                                echo "<p>SINS Election in 4 days</p>";
                            }

                            $qrynon = "SELECT voter_id FROM voters WHERE user_id = '$_SESSION[userID]'";
                            $resnon =  mysqli_query($conn, $qrynon);
                            if ($resnon && mysqli_num_rows($resnon) > 0) {
                                $row = mysqli_fetch_assoc($resnon);
                                $v_id = $row['voter_id'];

                                $qrynon2 = "SELECT status FROM candidate WHERE voter_id = '$v_id'";
                                $resnon2 = mysqli_query($conn, $qrynon2);

                                if ($resnon2 && mysqli_num_rows($resnon2) > 0) {
                                    $row = mysqli_fetch_assoc($resnon2);
                                    $status = $row['status'];
                                    $_SESSION['voter_id'] = $v_id;

                                    if ($status == 'Pending') {
                                        echo '<div class="status-container">';
                                        echo '<div class="status-indicator"></div>A position was offered to you!<a href="appointment_letter">Click here</a>';
                                        echo '</div>';
                                    }
                                }
                            }
                            echo '<div class="vote-btn">';
                            echo '<div class="status-container">';
                            echo '<div class="status-indicator"></div>Vote here!<a href="ballot">Click here</a>';
                            echo '</div>';
                            echo '</div>';

                    ?>
            </div>
            <div class="card" id="card-2">
                <div class="calendar">
                        <div class="calendar-header">
                            <button id="prev">&#8249;</button>
                                <div id="month-year"></div>
                            <button id="next">&#8250;</button>
                        </div>
                        <div class="calendar-body">
                            <div class="calendar-weekdays">
                                <div>Sun</div>
                                <div>Mon</div>
                                <div>Tue</div>
                                <div>Wed</div>
                                <div>Thu</div>
                                <div>Fri</div>
                                <div>Sat</div>
                            </div>
                        <div id="calendar-days" class="calendar-days"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid-container-2">
                <?php
                    $currentDate = new DateTime("now", new DateTimeZone('Asia/Hong_Kong'));
                    $startDate = new DateTime($date_start, new DateTimeZone('Asia/Hong_Kong'));
                    $endDate = new DateTime($date_end, new DateTimeZone('Asia/Hong_Kong'));

                        if ($resultss -> num_rows == 0) {

                            echo "<h2 style='text-align: center;'>NO CANDIDATES YET</h2>";

                        } else {
                ?>
            <div class="card" id="card-3">
                <?php 
                    echo "<h2>Meet a candidate from <strong>$party</strong></h2>";
                ?>
            </div>
            <div class="card" id="card-4">
                            <?php 
                                if ($img) {
                                    $image_url = 'data:image/jpeg;base64,' . base64_encode($img);
                                    echo "<img src='$image_url' alt='Candidate image'>";
                                    if (isset($candidate_id) && !empty($candidate_id)) {
                                        $s_qry = "SELECT COUNT(support_id) AS supp FROM support WHERE candidate_id = $candidate_id";
                                        $s_res = mysqli_query($conn, $s_qry);
                                    
                                        if ($s_res && $s_res->num_rows > 0) {
                                            $rowss = $s_res->fetch_assoc();
                                            $sup_num = $rowss['supp'];

                                            if ($sup_num != 0) {
                                                $studentText = $sup_num > 1 ? "{$sup_num} students support" : "{$sup_num} student supports";
                                                echo "<p style='color: grey; font-style: italic; font-size: 12px; margin-top: 1rem;'><i class='bx bx-like'></i>{$studentText} {$fname}</p>";
                                            }
                                        }
                                    } 
                                } else {
                                    echo "<img src='assets/images/profile.png' alt='Image 1' alt='Candidate image'>";
                                }
                            ?>
            </div>
            <div class="card" id="card-5">
                        <?php 
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
                                echo "<p>Hi students of STI College Iligan! My name is <strong>$formatted_name</strong>, a $ggrade $program student and I am currently running for the position of <strong>$position</strong>.</p>
                                <p>$details</p>";
                            }
                            ?>
            </div>
            <div class="card" id="card-6">
                <h2>PLATFORM</h2>
            </div>
            <div class="card" id="card-7">
                        <?php 
                                $query = "SELECT platform FROM candidate WHERE candidate_id = '$candidate_id'";
                                $res = mysqli_query($conn, $query);

                                if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) {
                                        $plat = $row['platform'];
                                        echo "<p id='platform'>$plat</p>";
                                    }
                                }
                             ?>    
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendarDays = document.getElementById('calendar-days');
            const monthYear = document.getElementById('month-year');
            const prevButton = document.getElementById('prev');
            const nextButton = document.getElementById('next');

            let date = new Date();

            const electionStart = new Date("<?php echo $date_start; ?>T00:00:00+08:00");
            const electionEnd = new Date("<?php echo $date_end; ?>T23:59:59+08:00");

            function renderCalendar() {
                calendarDays.innerHTML = '';
                const month = date.getMonth();
                const year = date.getFullYear();

                const today = new Date();
                today.setHours(0, 0, 0, 0);

                const firstDay = new Date(year, month, 1).getDay();
                const lastDate = new Date(year, month + 1, 0).getDate();

                monthYear.innerHTML = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

                for (let i = 0; i < firstDay; i++) {
                    const emptyDiv = document.createElement('div');
                    calendarDays.appendChild(emptyDiv);
                }

                for (let i = 1; i <= lastDate; i++) {
                    const dayDiv = document.createElement('div');
                    dayDiv.textContent = i;

                    const currentDate = new Date(year, month, i);
                    currentDate.setHours(0, 0, 0, 0); 

                    if (currentDate >= electionStart && currentDate <= electionEnd) {
                        dayDiv.classList.add('election-period');
                    }

                    if (currentDate.getTime() === today.getTime()) {
                        dayDiv.classList.add('today');
                    }

                    calendarDays.appendChild(dayDiv);
                }
            }

            prevButton.addEventListener('click', () => {
                date.setMonth(date.getMonth() - 1);
                renderCalendar();
            });

            nextButton.addEventListener('click', () => {
                date.setMonth(date.getMonth() + 1);
                renderCalendar();
            });

            renderCalendar();
        });


    </script>
</body>
</html>