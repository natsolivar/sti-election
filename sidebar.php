<?php 
?>
<!DOCTYPE html>
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="sidebar_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <title>EMVS</title>
    </head>
        <body>

            <div class="sidebar">
                <div class="top">
                    <div class="logo">
                        <i class="bx bxl-codepen"></i>
                        <span>EMVS</span>
                    </div>
                    <i class="bx bx-menu" id="btn"></i>
                    <div class="user">
                        <img src="assets/images/profile.png" alt="" class="user-img">
                        <div>
                            <p class="bold"><?php echo htmlspecialchars($_SESSION['user_info']['displayName']); ?></p>
                        </div>
                    </div>
                        <ul>
                            <li>
                                <a href="homepage.php"><i class="bx bxs-home-alt-2"></i>
                                <span class="nav-item">Homepage</span>
                                </a>
                                <span class="tooltip">Homepage</span>
                            </li>
                            <li>
                                <a href="candidate.php"><i class="bx bxs-face"></i>
                                <span class="nav-item">Candidate</span>
                                </a>
                                <span class="tooltip">Candidate</span>
                            </li>
                            <li>
                                <a href="council.php"><i class="bx bxs-group"></i>
                                <span class="nav-item">COLs</span>
                                </a>
                                <span class="tooltip">Council of Leaders</span>
                            </li>
                            <li>
                                <a href="poll.php"><i class="bx bxs-bar-chart-square"></i>
                                <span class="nav-item">Polls</span>
                                </a>
                                <span class="tooltip">Polls</span>
                            </li>
                            <li>
                                <a href="#"><i class="bx bxs-grid-alt"></i>
                                <span class="nav-item">Page6</span>
                                </a>
                                <span class="tooltip">Page6</span>
                            </li>
                        </ul>
                </div>
            </div>
        </body>
        <script>
            let btn = document.querySelector('#btn')
            let sidebar = document.querySelector('.sidebar')

            btn.onclick = function () {
                sidebar.classList.toggle('active')
            }
        </script>
</html>