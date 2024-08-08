<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';
    include 'db.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles/council_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>EMVS</title>
    </head>
    <body>
    <div class="main-content">
        <div class="header">
            <p>The Council of Leaders</p>
        </div>
    <div id="CandidatesContent" class="content">
        <div class="position">
            <h3>PRESIDENT</h3>
            <div class="image-container">
                <?php 
                
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'PRES'";
                    $res1 = mysqli_query ($conn, $qry1);

                    if ($res1 -> num_rows > 0) {
                        while ($rows = $res1 -> fetch_assoc()) {
                            $fname = htmlspecialchars($rows['first_name'], ENT_QUOTES, 'UTF-8');
                            $lname = htmlspecialchars($rows['last_name'], ENT_QUOTES, 'UTF-8');
                            $position = htmlspecialchars($rows['position_name'], ENT_QUOTES, 'UTF-8');
                            $imgData = $rows['image'];
                            $fullname = $fname . " " . $lname;

                            $imgBase64 = base64_encode($imgData);
                            $imgSrc = "data:image/jpeg;base64,$imgBase64";

                            echo "<div class='card'>";
                            echo "<div class='image'><span class='image-wrapper'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</span>";
                            echo "</div>";
                            echo "<p class='title'>$fullname</p>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";

                        }
                    }
                
                
                ?>   
            </div>     
        </div>
        <div class="position">
            <h3>VICE PRESIDENT</h3>
            <div class="image-container">
                <?php 
                    
                    $qry2 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'TERVP' OR co.position_id = 'SHVP'";
                    $res2 = mysqli_query ($conn, $qry2);

                    if ($res2 -> num_rows > 0) {
                        while ($rows = $res2 -> fetch_assoc()) {
                            $fname = htmlspecialchars($rows['first_name'], ENT_QUOTES, 'UTF-8');
                            $lname = htmlspecialchars($rows['last_name'], ENT_QUOTES, 'UTF-8');
                            $position = htmlspecialchars($rows['position_name'], ENT_QUOTES, 'UTF-8');
                            $imgData = $rows['image'];
                            $fullname = $fname . " " . $lname;

                            $imgBase64 = base64_encode($imgData);
                            $imgSrc = "data:image/jpeg;base64,$imgBase64";

                            echo "<div class='card'>";
                            echo "<div class='image'><span class='image-wrapper'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</span>";
                            echo "</div>";
                            echo "<p class='title'>$fullname</p>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";

                        }
                    }
                
                
                ?>     
            </div>
        </div>
    </div>
    </body>
</html>
