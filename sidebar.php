<?php 
	include 'session.php';
	include 'db.php'; 
	include 'check_login.php';
	
	$userProfilePic = $_SESSION['user_profile_pic'] ?? null;
	$user_id = $_SESSION['userID'];

	$que = "SELECT date_start, date_end FROM e_period LIMIT 1";
    $result = mysqli_query($conn, $que);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $date_start = $row['date_start'];
        $date_end = $row['date_end'];
    } else {
        $date_start = $date_end = null;
    }

	$currentDate = new DateTime("now", new DateTimeZone('Asia/Hong_Kong'));
    $startDate = new DateTime($date_start, new DateTimeZone('Asia/Hong_Kong'));
    $endDate = new DateTime($date_end, new DateTimeZone('Asia/Hong_Kong'));

?>
<!DOCTYPE html>
<html>
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <!-- <link rel="stylesheet" type="text/css" href="sidebar_style.css?v=<?php echo time(); ?>"> -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>EMVS</title>
    </head>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: "Poppins" , sans-serif;
		}

		body {
			position: relative;
			min-height: 100vh;
			width: 100%;
			overflow: hidden;
		}

		.sidebar {
			position: fixed;
			top: 0;
			left: 0;
			height: 100%;
			width: 78px;
			background: #0079c2;
			padding: 6px 14px;
			transition: all 0.5s ease;
		}

		.sidebar.active {
			width: 240px;
		}

		.sidebar .logo_content .logo {
			color: #fff;
			display: flex;
			height: 50px;
			width: 100%;
			align-items: center;
			opacity: 0;
			pointer-events: none;
		}

		.sidebar.active .logo_content .logo {
			opacity: 1;
			pointer-events: none;
		}

		.logo_content .logo {
			font-size: 28px;
			margin-right: 5px;
		}

		.logo_content .logo .logo_name {
			font-size: 20px;
			font-weight: 400;
		}

		.sidebar #btn {
			position: absolute;
			color: #fff;
			left: 55%;
			top: 6px;
			font-size: 20px;
			height: 50px;
			width: 50px;
			text-align: center;
			line-height: 50px;
			transform: translate(-50%);
		}

		.sidebar.active #btn {
			left: 90%;
		}

		.sidebar ul {
			margin-top: 20px;
		}

		.sidebar ul li {
			position: relative;
			height: 60px;
			width: 100%;
			margin: 0 5px;
			list-style: none;
			line-height: 50px;
		}

		.sidebar ul li a {
			color: #fff;
			display: flex;
			align-items: center;
			text-decoration: none;
			transition: all 0.4s ease;
			border-radius: 12px;
			white-space: nowrap;
		}

		.sidebar ul li a:hover {
			color: #11101d;
			background: yellow;
		}

		.sidebar ul li i {
			height: 50px;
			min-width: 50px;
			border-radius: 12px;
			line-height: 50px;
			text-align: center;
			font-size: 20px;
		}

		.sidebar .links_name {
			opacity: 0;
			pointer-events: none;
		}

		.sidebar.active .links_name {
			opacity: 1;
			pointer-events: auto;
		}

		.sidebar .profile_content {
			position: absolute;
			color: black;
			bottom: 0;
			left: 0;
			width: 100%;
		}

		.sidebar .profile_content .profile {
			position: relative;
			padding: 10px 6px;
			height: 60px;
			background: yellow;
		}

		.profile_content .profile .profile_details {
			display: flex;
			align-items: center;
			opacity: 0;
			pointer-events: none;
			white-space: nowrap;
		}

		.sidebar.active .profile .profile_details {
			opacity: 1;
			pointer-events: auto;
		}

		.profile .profile_details img {
			height: 45px;
			width: 45px;
			object-fit: cover;
			border-radius: 100%;
			margin-right: 10px;
		}

		.profile .profile_details .name {
			font-size: 10px;
			font-weight: 400;
		}

		.profile #log_out {
			position: absolute;
			bottom: 5px;
			left: 50%;
			transform: translateX(-50%);
			min-width: 50px;
			line-height: 50px;
			font-size: 20px;
			border-radius: 12px;
			text-align: center;
		}

		.sidebar.active .profile #log_out {
			left: 88%;
		}


		.sidebar ul li a i {
			height: 50px;
			min-width: 50px;
			border-radius: 12px;
			line-height: 50px;
			text-align: center;
		}

		.sidebar ul li .tooltip {
			position: absolute;
			left: 122px;
			top: 0;
			transform: translate(-50% , -50%);
			border-radius: 6px;
			height: 35px;
			width: 122px;
			background: #fff;
			line-height: 35px;
			text-align: center;
			box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
			transition: 0s;
			opacity: 0;
			pointer-events: none;
		}

		.sidebar.active ul li .tooltip {
			display: none;
		}

		.sidebar ul li:hover .tooltip {
			transition: all 0.5s ease;
			opacity: 1;
			top: 50%;
		}

		.button {
			z-index: 10;
			line-height: 1;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			gap: .75rem;
			background-color: #0079c2;
			color: #ffffff;
			border-radius: 10rem;
			font-weight: 600;
			padding: .75rem 1.5rem;
			padding-left: 20px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			transition: background-color .3s;
			position: fixed;
			bottom:60px;
			right:70px;
		}
		
		.button__icon-wrapper {
			flex-shrink: 0;
			width: 25px;
			height: 25px;
			position: relative;
			color: rgb(139, 128, 0);
			background-color: #fff;
			border-radius: 50%;
			display: grid;
			place-items: center;
			overflow: hidden;
		}
		
		.button:hover {
			background-color: #d9e901;
		}
		
		.button:hover .button__icon-wrapper {
			color: #0079c2;
		}
		
		.button__icon-svg--copy {
			position: absolute;
			transform: translate(-150%, 150%);
		}
		
		.button:hover .button__icon-svg:first-child {
			transition: transform .3s ease-in-out;
			transform: translate(150%, -150%);
		}
		
		.button:hover .button__icon-svg--copy {
			transition: transform .3s ease-in-out .1s;
			transform: translate(0);
		}

		a:hover{
			text-decoration: none;
		}

		

	</style>
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
				<a href="https://forms.office.com/r/Bymn3Q9czS?origin=lprLink">
					<i class='bx bx-user-voice'></i>
					<span class="links_name">Feedback</span>
				</a>
				<span class="tooltip">Feedback</span>
			</li>
			<li hidden>
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
            	<img src="data:image/jpeg;base64,<?php echo $userProfilePic; ?>" alt="Profile Picture" class="profile-pic" onclick="location.href='profile.php'">
				<?php endif; ?>
					<div class="name"><?php echo htmlspecialchars($_SESSION['displayName']); ?></div>
				</div>
				<i class='bx bx-log-out' id="log_out" onclick="location.href='logout.php'"></i>
			</div>
		</div>
	</div>
	<?php 

			$qry1 = "SELECT vote_status FROM voters WHERE user_id = '$user_id'";
			$result = mysqli_query($conn, $qry1);

			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$vote_status = $row['vote_status'];

				if ($startDate && $endDate) {
					if ($currentDate >= $startDate && $currentDate <= $endDate) {
						if ($vote_status == 'NO') {
							echo '<a style="--clr: #7808d0" class="button" href="#exampleModalLong" data-toggle="modal">
									<span class="button__icon-wrapper">
										<svg width="10" class="button__icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 15">
											<path fill="currentColor" d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"></path>
										</svg>
										<svg class="button__icon-svg  button__icon-svg--copy" xmlns="http://www.w3.org/2000/svg" width="10" fill="none" viewBox="0 0 14 15">
											<path fill="currentColor" d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"></path>
										</svg>
									</span>Vote now!
								</a>';
						}
					}
				} 
			} 
	
	?>

<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Important Voting Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Thank you for taking the time to vote for the STI College Iligan Council of Leaders. Your participation is vital in shaping the future of our college.</p>
        <p><b>Before you finalize your vote:</b></p>
        <ul style="padding-left:20px;">
          <li><b>Review Your Choices:</b> Double-check that you have selected the candidates you support.</li>
          <li><b>Confirm Your Vote:</b> Ensure that your choices are correct before submitting. Once submitted, changes cannot be made.</li>
        </ul> 
        <p>Your vote matters and will help us build a stronger and more responsive student leadership.</p>
        <p>Thank you for being an active part of the STI College Iligan Community!</p>
      </div>
      <div class="modal-footer">
	  	<button type="button" class="btn btn-primary" onclick="location.href='ballot.php'">Proceed</button>
      </div>
      </div>
    </div>
  </div>
</div>

	
	<script type="text/javascript">
		let btn = document.querySelector("#btn");
		let sidebar = document.querySelector(".sidebar");

		sidebar.classList.add("active");

		btn.onclick = function () {
			sidebar.classList.toggle("active");
		}

	</script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
