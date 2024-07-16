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
        <link rel="stylesheet" type="text/css" href="styles/candidate_style.css?v=<?php echo time(); ?>">
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
            <div class="flex-item">
                <img src="assets/images/sample-images.png" alt="Image 1">
                <div class="details">
                    <h2>Renato Olivar</h2>
                    <p>Description ni renato</p>
                </div>
            </div>
            <div class="flex-item">
                <img src="assets/images/sample-images.png" alt="Image 2">
                <div class="details">
                    <h2>Rapha Victor Tubio</h2>
                    <p>Description ni rapha</p>
                </div>
            </div>
            <div class="flex-item">
                <img src="assets/images/sample-images.png" alt="Image 3">
                <div class="details">
                    <h2>Sammy Summertime</h2>
                    <p>Description ni sammy</p>
                </div>
            </div>
        </div>
        </main> 
        <header>
            <h1>For VICE PRESIDENT</h1>
        </header>
        <main>
        <div class="flex-container">
            <div class="flex-item">
                <img src="assets/images/sample-images.png" alt="Image 1">
                <div class="details">
                    <h2>Renato Olivar</h2>
                    <p>Description ni renato.</p>
                </div>
            </div>
            <div class="flex-item">
                <img src="assets/images/sample-images.png" alt="Image 2">
                <div class="details">
                    <h2>Rapha Victor Tubio</h2>
                    <p>Description ni rapha</p>
                </div>
            </div>
            <div class="flex-item">
                <img src="assets/images/sample-images.png" alt="Image 3">
                <div class="details">
                    <h2>Sammy Summertime</h2>
                    <p>Description ni sammy</p>
                </div>
            </div>
            <div class="flex-item">
                <img src="assets/images/sample-images.png" alt="Image 3">
                <div class="details">
                    <h2>Sammy Summertime</h2>
                    <p>Description ni sammy</p>
                </div>
            </div>
        </div>
        </main> 
    </div>
    </body>
    <script>
                            document.addEventListener('DOMContentLoaded', function () {
            // Push a state to the history to detect back navigation
            history.pushState(null, null, location.href);

            // Intercept the popstate event to detect back navigation
            window.addEventListener('popstate', function (event) {
                if (confirm("Navigating back will log you out. Do you want to proceed?")) {
                    window.location.href = "logout.php?backnav=true";
                } else {
                    // Prevent the user from navigating back by pushing the state again
                    history.pushState(null, null, location.href);
                }
            });
        });
    </script>
</html>