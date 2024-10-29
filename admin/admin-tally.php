<?php 

include 'admin-sidebar.php';
include 'db.php';


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

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/tally_style.css?v=<?php echo time(); ?>">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>EMVS</title>
    </head>
<body>
    <div class="main-content">
        <div class="header">
            <h1>Vote Tally</h1>
        </div>
        <div class="container">
            <div class="card">
            <?php 
                $qry = "SELECT p.position_id, p.position_name, c.candidate_id, i.image, u.user_name, v.voter_grade, 
                v.program_code, vs.total_votes, vs.SHvote_count, vs.TERvote_count, 
                co.position_id AS appointed_position, co.academic_year AS council_year, co.status AS council_status, c.status
                FROM position p
                LEFT JOIN candidate c ON p.position_id = c.position_id AND c.status IN ('Accepted', 'Pending')
                LEFT JOIN images i ON c.candidate_id = i.candidate_id 
                LEFT JOIN voters v ON c.voter_id = v.voter_id 
                LEFT JOIN votes vs ON c.candidate_id = vs.candidate_id
                LEFT JOIN users u ON v.user_id = u.user_id 
                LEFT JOIN council co ON c.candidate_id = co.candidate_id AND co.status = 'ACTIVE' 
                    AND co.academic_year = '$schoolYear'
                ORDER BY p.position_rank ASC"; 
        
                $result = mysqli_query($conn, $qry);
                
                $max_votes = 0;
                
                while ($row = $result->fetch_assoc()) {
                    if (isset($row['total_votes']) && $row['total_votes'] > $max_votes) {
                        $max_votes = $row['total_votes'];
                    }
                }
                
                $result->data_seek(0); 
                
                if ($result->num_rows > 0) {
                    $candidates_by_position = [];
                
                    while ($row = $result->fetch_assoc()) {
                        $position_id = $row['position_id'];
                        $pos = $row['position_name'];
                        $candidate_id = $row['candidate_id'];
                        $v_grade = $row['voter_grade'];
                        $program = $row['program_code'];
                        $status = $row['status'];
                        $t_votes = isset($row['total_votes']) ? $row['total_votes'] : 0;
                        $sh_votes = isset($row['SHvote_count']) ? $row['SHvote_count'] : 0;
                        $ter_votes = isset($row['TERvote_count']) ? $row['TERvote_count'] : 0;
                        $img = isset($row['image']) ? $row['image'] : null;
                        $user_name = $row['user_name'] ?? null;

                        $appointed_position = $row['appointed_position'] ?? null;
                        $council_year = $row['council_year'] ?? null;
                        $council_status = $row['council_status'] ?? null;
                
                        if (!empty($user_name)) {
                            $user_name = str_replace("(Student)", "", $user_name);
                            $name_parts = explode(", ", trim($user_name));
                            $formatted_name = (count($name_parts) == 2) ? $name_parts[1] . " " . $name_parts[0] : $user_name;
                        } else {
                            $formatted_name = null;
                        }
                
                        if (!isset($candidates_by_position[$pos])) {
                            $candidates_by_position[$pos] = [
                                'position_id' => $position_id,
                                'candidates' => []
                            ];
                        }
                
                        if (!empty($candidate_id)) {
                            $candidates_by_position[$pos]['candidates'][] = [
                                'candidate_id' => $candidate_id,
                                'formatted_name' => $formatted_name,
                                'pos_name' => $pos,
                                'program' => $program,
                                'v_grade' => $v_grade,
                                'image' => $img,
                                't_votes' => $t_votes,
                                'sh_votes' => $sh_votes,
                                'tr_votes' => $ter_votes,
                                'appointed_position' => $appointed_position,
                                'council_year' => $council_year,
                                'council_status' => $council_status,
                                'status' => $status
                            ];
                        }
                    }
                
                    foreach ($candidates_by_position as $position => $data) {
                        $position_id = $data['position_id'];
                        echo "<div class='title'><h2>For $position</h2></div>";
                
                        if (!empty($data['candidates'])) {
                            usort($data['candidates'], function($a, $b) {
                                return $b['t_votes'] <=> $a['t_votes'];
                            });
                
                            $top_candidate_id = $data['candidates'][0]['candidate_id'];
                            $appointButtonShown = false;
                
                            foreach ($data['candidates'] as $candidate) {
                                $img = isset($candidate['image']) ? 'data:image/jpeg;base64,' . base64_encode($candidate['image']) : 'assets/images/profile.png';
                                $formatted_name = $candidate['formatted_name'];
                                $program = $candidate['program'];
                                $v_grade = $candidate['v_grade'];
                                $total_votes = $candidate['t_votes'];
                                $s_votes = $candidate['sh_votes'];
                                $t_votes = $candidate['tr_votes'];
                                $stat = $candidate['status'];

                                
                
                                echo "<div class='min-card' id='candi'>"; 
                                echo "<div class='min-card-1' id='profimg'><img src='$img' alt='Image'></div>";
                                echo "<div class='min-card-1' id='can-name'>
                                        <h3>$formatted_name</h3>
                                        <p>$program</p>
                                    </div>";
                
                                if ($total_votes == $max_votes) {
                                    echo "<div class='min-card-1' id='vote' style='font-weight: bold; font-size: 1.5rem; color: #2ea44f;'>
                                            <p>{$total_votes} " . ($total_votes == 1 ? "vote" : "votes") . "</p>
                                            <p style='font-weight: lighter; font-size: 0.9rem; color: grey;'>sh: $s_votes / ter: $t_votes</p>
                                        </div>";
                                } else {
                                    echo "<div class='min-card-1' id='vote' style='font-weight: bold; font-size: 1.2rem; color: grey;'>
                                            <p>{$total_votes} " . ($total_votes == 1 ? "vote" : "votes") . "</p>
                                            <p style='font-weight: lighter; font-size: 0.9rem; color: grey;'>sh: $s_votes / ter: $t_votes</p>
                                        </div>";
                                }
                
                                if ($candidate['candidate_id'] == $top_candidate_id) {
                                    if (!$candidate['appointed_position'] || $candidate['council_status'] != 'ACTIVE' || $candidate['council_year'] != $schoolYear) {
                                        echo "<div class='min-card-1' id='btn'>
                                                <form action='appointment.php' method='POST'>
                                                    <input type='hidden' name='candidate_id' value='{$candidate['candidate_id']}'>
                                                    <button type='submit' role='button'><i class='bx bxs-user-check'></i>Appoint as $position</button>
                                                </form>
                                              </div>";
                                    } else {
                                        if ($stat == 'Pending') {
                                            echo "<div class='min-card-1' id='btn'>
                                                <p style='color: grey;'>Awaiting Response</p>
                                              </div>";
                                        } else {
                                            echo "<div class='min-card-1' id='btn'>
                                                <h3>{$candidate['pos_name']}</h3>
                                              </div>";
                                        }
                                        $appointmentDone = true;
                                    }
                                }
                        
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='min-card-non'>
                                    <a href='non-candidate.php?position_id=$position_id' class='btn btn-primary'>Appoint Non-Candidate for $position</a>
                                </div>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>