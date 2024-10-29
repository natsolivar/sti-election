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
    <main>
    <div class="header">
    <p>Running Candidates</p>
</div>
<div class="main">
<?php 
    $qry = "SELECT p.position_name, c.candidate_id, i.image, u.user_name, v.voter_grade, v.program_code 
            FROM position p
            LEFT JOIN candidate c ON p.position_id = c.position_id AND c.status = 'Accepted'
            LEFT JOIN images i ON c.candidate_id = i.candidate_id 
            LEFT JOIN voters v ON c.voter_id = v.voter_id 
            LEFT JOIN users u ON v.user_id = u.user_id 
            ORDER BY p.position_rank ASC";

    $result = mysqli_query($conn, $qry);

    if ($result->num_rows > 0) {
        $candidates_by_position = [];

        while ($row = $result->fetch_assoc()) {
            $img = $row['image'];
            $user_name = $row['user_name'];
            $pos = $row['position_name'];
            $candidate_id = $row['candidate_id'];
            $v_grade = $row['voter_grade'];
            $program = $row['program_code'];

            if (!empty($user_name)) {
                $user_name = str_replace("(Student)", "", $user_name);
                $name_parts = explode(", ", trim($user_name));
                $formatted_name = (count($name_parts) == 2) ? $name_parts[1] . " " . $name_parts[0] : $user_name;
            } else {
                $formatted_name = null;
            }

            $candidates_by_position[$pos][] = [
                'candidate_id' => $candidate_id,
                'formatted_name' => $formatted_name,
                'program' => $program,
                'v_grade' => $v_grade,
                'image' => $img
            ];
        }

        foreach ($candidates_by_position as $position => $candidates) {
            echo "<header>
                    <h1>For $position</h1>
                  </header>";
            echo "<div class='flex-container'>"; 

            if (!empty($candidates[0]['candidate_id'])) {
                foreach ($candidates as $candidate) {
                    $img = isset($candidate['image']) ? 'data:image/jpeg;base64,' . base64_encode($candidate['image']) : 'assets/images/profile.png';
                    $candidate_id = $candidate['candidate_id'];
                    $formatted_name = $candidate['formatted_name'];
                    $program = $candidate['program'];
                    $v_grade = $candidate['v_grade'];

                    echo "<div class='flex-item'>"; 
                    echo "<a href='candidate_prof.php?id=$candidate_id'><img src='$img' alt='Image'></a>";
                    echo "<div class='details'>
                            <h2>$formatted_name</h2>
                            <p>{$program} {$v_grade}</p>
                          </div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No Candidates for this position</p>";
            }

            echo "</div>";
        }
    } else {
        echo "No Candidates";
    }
    ?>
</div>
        
    </main>
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