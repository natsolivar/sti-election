<?php 

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
				<div class="logo_name">EMVS Admin</div>
			</div>
			<i class='bx bx-menu' id="btn"></i>
		</div>
		<ul class="nav_list">
			<li>
				<a href="index.php">
                    <i class='bx bx-home-alt'></i>
					<span class="links_name">Homepage</span>
				</a>
			</li>
			<li>
				<a href="admin-candidate.php">
                    <i class='bx bx-male-female'></i>
					<span class="links_name">Candidates</span>
				</a>
			</li>
			<li>
				<a href="admin-council.php">
                    <i class='bx bx-group'></i>
					<span class="links_name">COLs</span>
				</a>
			</li>
			<li>
				<a href="admin-voters.php">
                    <i class='bx bx-bar-chart-alt'></i>
					<span class="links_name">Voters</span>
				</a>
			</li>
            <li>
				<a href="admin-party.php">
                    <i class='bx bx-bar-chart-alt'></i>
					<span class="links_name">Partylist</span>
				</a>
			</li>
		</ul>
		<div class="profile_content">
			<div class="profile">
				<div class="profile_details">
					<div class="name"></div>
				</div>
				<i class='bx bx-log-out' id="log_out" onclick="location.href='logout.php'"></i>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		let btn = document.querySelector("#btn");
		let sidebar = document.querySelector(".sidebar");

		btn.onclick = function () {
			sidebar.classList.toggle("active");
		}

	</script>
</body>
</html>
