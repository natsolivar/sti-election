<?php 
$userProfilePic = $_SESSION['user_profile_pic'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="sidebar_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <title>EMVS</title>
    </head>
    <body>
	<div class="sidebar">
		<div class="logo_content">
			<div class="logo">
                <i class='bx bxl-edge'></i>
				<div class="logo_name">EMVS</div>
			</div>
			<i class='bx bx-menu' id="btn"></i>
		</div>
		<ul class="nav_list">
			<li>
				<a href="homepage.php">
                    <i class='bx bx-home-alt'></i>
					<span class="links_name">Homepage</span>
				</a>
				<span class="tooltip">Homepage</span>
			</li>
			<li>
				<a href="candidate.php">
                    <i class='bx bx-male-female'></i>
					<span class="links_name">Candidates</span>
				</a>
				<span class="tooltip">Candidates</span>
			</li>
			<li>
				<a href="council.php">
                    <i class='bx bx-group'></i>
					<span class="links_name">COLs</span>
				</a>
				<span class="tooltip">Council of Leaders</span>
			</li>
			<li>
				<a href="poll.php">
                    <i class='bx bx-bar-chart-alt'></i>
					<span class="links_name">Poll</span>
				</a>
				<span class="tooltip">Poll</span>
			</li>
		</ul>
		<div class="profile_content">
			<div class="profile">
				<div class="profile_details">
				<?php if ($userProfilePic): ?>
            <img src="data:image/jpeg;base64,<?php echo $userProfilePic; ?>" alt="Profile Picture" class="profile-pic">
        <?php else: ?>
            <p>No profile picture available</p>
        <?php endif; ?>
					<div class="name"><?php echo htmlspecialchars($_SESSION['displayName']); ?></div>
				</div>
				<i class='bx bx-log-out' id="log_out" onclick="location.href='logout.php'"></i>
			</div>
		</div>
	</div>
    <a style="--clr: #7808d0" class="button" href="#myModal">
      <span class="button__icon-wrapper">
        <svg width="10" class="button__icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 15">
          <path fill="currentColor" d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"></path>
        </svg>
        <svg class="button__icon-svg  button__icon-svg--copy" xmlns="http://www.w3.org/2000/svg" width="10" fill="none" viewBox="0 0 14 15">
            <path fill="currentColor" d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"></path>
          </svg>
      </span>Vote now!
    </a>
	<script type="text/javascript">
		let btn = document.querySelector("#btn");
		let sidebar = document.querySelector(".sidebar");

		btn.onclick = function () {
			sidebar.classList.toggle("active");
		}

	</script>
</body>
</html>
