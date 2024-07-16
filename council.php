<?php 
    session_start();
    include 'sidebar.php';
    include 'session.php';

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
        <div id="president">
            <h3>PRESIDENT</h3>
            <div class="image-container">
            <div class="card">
                <div class="image"><span class="image-wrapper">
                <img src="assets/images/sample-images.png" alt="">
                </span>
                </div>
                <span class="title">Renato Olivar</span>
                <span class="price">President</span>
            </div>
            <div class="card">
                <div class="image"><span class="image-wrapper">
                <img src="assets/images/sample-images.png" alt="">
                </span>
            </div>
                <span class="title">Renato Olivar</span>
                <span class="price">President</span>
            </div>
        </div>
        </div>
        <div id="v-president">
        <h3>VICE PRESIDENT</h3>
            <div class="image-container">
            <div class="card">
                <div class="image"><span class="image-wrapper">
                <img src="assets/images/sample-images.png" alt="">
                </span>
                </div>
                <span class="title">Renato Olivar</span>
                <span class="price">President</span>
            </div>
            <div class="card">
                <div class="image"><span class="image-wrapper">
                <img src="assets/images/sample-images.png" alt="">
                </span>
            </div>
                <span class="title">Renato Olivar</span>
                <span class="price">President</span>
            </div>
            <div class="card">
                <div class="image"><span class="image-wrapper">
                <img src="assets/images/sample-images.png" alt="">
                </span>
            </div>
                <span class="title">Renato Olivar</span>
                <span class="price">President</span>
            </div>
        </div>
        </div>
        <div id="v-president">
        <h3>SECRETARY</h3>
            <div class="image-container">
            <div class="card">
                <div class="image"><span class="image-wrapper">
                <img src="assets/images/sample-images.png" alt="">
                </span>
                </div>
                <span class="title">Renato Olivar</span>
                <span class="price">President</span>
            </div>
            <div class="card">
                <div class="image"><span class="image-wrapper">
                <img src="assets/images/sample-images.png" alt="">
                </span>
            </div>
                <span class="title">Renato Olivar</span>
                <span class="price">President</span>
            </div>
        </div>
        </div>
    </div>
    </body>
</html>