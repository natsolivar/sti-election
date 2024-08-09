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
        <div class="container">
            <h1>PRESIDENT</h1>
            <div class="pres">
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>VICE PRESIDENT</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'TERVP' OR co.position_id = 'SHVP'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>SECRETARY</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'EXTSEC' OR co.position_id = 'INTSEC'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>TREASURER</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'TREA'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>AUDITOR</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'AUD'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>PUBLIC INFORMATION OFFICERS</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'PIO'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>GRADE 11 REPRESENTATIVES</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = '11ABMREP' OR co.position_id = '11HUMSSREP' OR co.position_id = '11STEMREP' OR co.position_id = '11CUARTREP' OR co.position_id = '11MAWDREP'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>GRADE 12 REPRESENTATIVES</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = '12ABMREP' OR co.position_id = '12HUMSSREP' OR co.position_id = '12STEMREP' OR co.position_id = '12CUARTREP' OR co.position_id = '12MAWDREP'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>BS in TOURISM MANAGEMENT REPRESENTATIVES</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'BSTM1AREP' OR co.position_id = 'BSTM1BREP' OR co.position_id = 'BSTM2REP' OR co.position_id = 'BSTM3REP' OR co.position_id = 'BSTM4REP'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
        <div class="container">
            <h1>BS in INFORMATION SYSTEM REPRESENTATIVES</h1>
            <div class="pres">
                <?php 
                    
                    $qry1 = "SELECT co.*, p.position_name 
                    FROM council_old co
                    INNER JOIN position p ON co.position_id = p.position_id
                    WHERE co.position_id = 'BSIS1REP' OR co.position_id = 'BSIS2REP' OR co.position_id = 'BSIS3REP' OR co.position_id = 'BSIS4REP'";
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

                            echo "<div class='flex' id='flex1'>";
                            echo "<div class='img-container'>";
                            echo "<img src=$imgSrc alt=''>";
                            echo "</div>";
                            echo "<div class='details'>";
                            echo "<h3 class='title'>$fullname</h3>";
                            echo "<p class='price'>$position</p>";
                            echo "</div>";
                            echo "</div>";

                        }
                    }
                ?> 
            </div>
        </div>
    </div>
    </body>
</html>