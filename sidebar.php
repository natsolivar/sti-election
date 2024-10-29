<?php 
	include 'session.php';
	include 'db.php'; 
	include 'check_login.php';
	
	$userProfilePic = $_SESSION['user_profile_pic'] ?? null;
	$user_id = $_SESSION['userID'];

	$isLoggedIn = isset($_SESSION['userID']);

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
		<link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <!-- <link rel="stylesheet" type="text/css" href="sidebar_style.css?v=<?php echo time(); ?>"> -->
        <title>EMVS</title>
    </head>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
		:root{
			--base-clr: #11121a;
			--line-clr: #42434a;
			--hover-clr: #222533;
			--text-clr: #e6e6ef;
			--accent-clr: #5e63ff;
			--secondary-text-clr: #b0b3c1;
		}

		*{
			margin: 0;
			padding: 0;
		}

		html{
			font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		.sidebar-body {
			min-height: 100vh;
			min-height: 100dvh;
			display: grid;
			grid-template-columns: auto 1fr;
		}

		#sidebar{
			box-sizing: border-box;
			height: 100vh;
			width: 250px;
			padding: 5px 1em;
			background-color: #0079c2;
			border-right: 1px solid var(--line-clr);
			position: sticky;
			top: 0;
			align-self: start;
			transition: 300ms ease-in-out;
			overflow: hidden;
			text-wrap: nowrap;
		}

		#sidebar.close{
			padding: 5px;
			width: 60px;
		}

		#sidebar ul{
			list-style: none;
		}

		#sidebar > ul > li:first-child{
			display: flex;
			justify-content: flex-end;
			margin-bottom: 16px;
			.logo{
				font-weight: 600;
				pointer-events: none;
			}
		}

		#sidebar a, #sidebar .dropdown-btn, #sidebar .logo{
			border-radius: .5em;
			padding: .85em;
			text-decoration: none;
			color: var(--text-clr) !important;
			display: flex;
			align-items: center;
			gap: 1em;
		}

		.dropdown-btn{
			width: 100%;
			text-align: left;
			background: none;
			border: none;
			font: inherit;
			cursor: pointer;
		}

		#sidebar svg{
			flex-shrink: 0;
			fill: var(--text-clr);
		}

		#sidebar a span, #sidebar .dropdown-btn span{
			flex-grow: 1;
		}

		#sidebar a:hover, #sidebar .dropdown-btn:hover{
			background-color: yellow !important;
			color: black !important;
			fill: black;
			> svg {
				fill: black;
			}
		}


		#sidebar .sub-menu{
			display: grid;
			grid-template-rows: 0fr;
			transition: 200ms ease-in-out;
			> div{
				overflow: hidden;
			}
		}

		#sidebar .sub-menu.show{
			grid-template-rows: 1fr;
		}

		.dropdown-btn svg{
			transition: 200ms ease;
		}

		.rotate svg:last-child{
			rotate: 180deg;
		}

		#sidebar .sub-menu a{
			padding-left: 2em;
		}

		#toggle-btn{
			margin-left: auto;
			padding: 1em;
			border: none;
			border-radius: .5em;
			background: none;
			cursor: pointer;
			svg{
				transition: rotate 150ms ease;
			}
		}

		main{
			padding: min(25px, 7%);
		}

		.button {
			z-index: 10;
			position: fixed;
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
			color: black;
		}

		.modal {
			display: none;
			position: fixed;
			z-index: 1000;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5); 
			justify-content: center;
			align-items: center;
			}

		.modal-dialog {
			background-color: white;
			border-radius: 5px;
			width: 80%;
			max-width: 600px;
			position: relative;
			}

		.modal-header, .modal-footer {
			padding: 15px;
			border-bottom: 1px solid #e5e5e5;
			}

		.modal-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			}

		.modal-title {
			margin: 0;
			}

		.modal-close {
			cursor: pointer;
			font-size: 24px;
			border: none;
			background: none;
			}

		.modal-body {
			padding: 20px;
			font-size: 14px;
			line-height: 1.5;
			}

		.modal-footer {
			text-align: right;
			border-top: 1px solid #e5e5e5;
			}

		.modal-footer button {
			padding: 8px 12px;
			background-color: #007bff;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			}

		.modal-footer button:hover {
			background-color: #0056b3;
			}

		.modal-body ul {
			list-style-type: disc;
			padding-left: 20px;
			}

		@media(max-width: 800px){
			body{
				grid-template-columns: 1fr;
			}
			main{
				padding: 2em 1em 60px 1em;
				flex-wrap: wrap;
			}

			.button {
				display: none;
			}

			#sidebar{
				height: 60px;
				width: 100%;
				border-right: none;
				border-top: 1px solid var(--line-clr);
				padding: 0;
				position: fixed;
				top: unset;
				bottom: 0;

				> ul{
					padding: 0;
					display: grid;
					grid-auto-columns: 60px;
					grid-auto-flow: column;
					align-items: center;
					justify-content: center;
					overflow-x: scroll;
				}

				ul li{
					height: 100%;
				}

				ul a, ul .dropdown-btn{
					width: 60px;
					height: 60px;
					padding: 0;
					border-radius: 0;
					justify-content: center;
				}

				ul li span, ul li:first-child, .dropdown-btn svg:last-child{
					display: none;
				}

				ul li .sub-menu.show{
					position: fixed;
					bottom: 60px;
					left: 0;
					box-sizing: border-box;
					height: 60px;
					width: 100%;
					background-color: var(--hover-clr);
					border-top: 1px solid var(--line-clr);
					display: flex;
					justify-content: center;
					> div{
						overflow-x: auto;
					}
					li{
						display: inline-flex;
					}
					a{
						box-sizing: border-box;
						padding: 1em;
						width: auto;
						justify-content: center;
					}
				}
			}
		}
		

	</style>
    <body>
	<div class="sidebar-body">
	<nav id="sidebar">
		<ul>
		<li>
			<span class="logo">EMVS</span>
			<button onclick=toggleSidebar() id="toggle-btn">
			<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
			</button>
		</li>
		<li>
			<a href="homepage">
			<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
			<span>Home</span>
			</a>
		</li>
		<li>
			<a href="candidate">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113ZM120-240h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0 320Zm0-400Z"/></svg>
			<span>Candidates</span>
			</a>
		</li>
		<li>
			<a href="council">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q32-20 76.5-30t96.5-10q53 0 97.5 10t76.5 30q32 20 49 46.5t17 58.5v65H240Zm540 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780Zm-455-80h311q-10-20-55.5-35T480-370q-55 0-100.5 15T325-320ZM160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440Zm640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480Zm0-80q17 0 28.5-11.5T520-600q0-17-11.5-28.5T480-640q-17 0-28.5 11.5T440-600q0 17 11.5 28.5T480-560Zm1 240Zm-1-280Z"/></svg>
			<span>COLs</span>
			</a>
		</li>
		<li>
			<button onclick=toggleSubMenu(this) class="dropdown-btn">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z"/></svg>
			<span>Profile</span>
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
			</button>
			<ul class="sub-menu">
				<div>
					<li><a href="profile">Details</a></li>
					<li><a href="logout">Logout</a></li>
				</div>
			</ul>
		</li>
		<li>
			<a href="https://forms.office.com/pages/responsepage.aspx?id=Z-ihBmp-mUKIGll1e56iwydlealrhEBCk4iz2p9u6hlURTlLWDVDV1RQQkRMNVhVMEs0WVNHTERITC4u&origin=lprLink&route=shorturl" target="_blank">
				<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/></svg>
			<span>Feedback</span>
			</a>
		</li>
		</ul>
	</nav>
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
							echo '<a style="--clr: #7808d0" class="button" href="#" id="triggerModal">
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
	<div id="customModal" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Important Voting Information</h5>
			<button type="button" class="modal-close" aria-label="Close">&times;</button>
		</div>
		<div class="modal-body">
			<p>Thank you for taking the time to vote for the STI College Iligan Council of Leaders. Your participation is vital in shaping the future of our college.</p>
			<p><b>Before you finalize your vote:</b></p>
			<ul>
			<li><b>Review Your Choices:</b> Double-check that you have selected the candidates you support.</li>
			<li><b>Confirm Your Vote:</b> Ensure that your choices are correct before submitting. Once submitted, changes cannot be made.</li>
			</ul> 
			<p>Your vote matters and will help us build a stronger and more responsive student leadership.</p>
			<p>Thank you for being an active part of the STI College Iligan Community!</p>
		</div>
		<div class="modal-footer">
			<button type="button" onclick="location.href='ballot.php'">Proceed</button>
		</div>
		</div>
	</div>
	</div>

		<script type="text/javascript" defer>

			const toggleButton = document.getElementById('toggle-btn')
			const sidebar = document.getElementById('sidebar')

			function toggleSidebar(){
			sidebar.classList.toggle('close')
			toggleButton.classList.toggle('rotate')

			closeAllSubMenus()
			}

			function toggleSubMenu(button){

			if(!button.nextElementSibling.classList.contains('show')){
				closeAllSubMenus()
			}

			button.nextElementSibling.classList.toggle('show')
			button.classList.toggle('rotate')

			if(sidebar.classList.contains('close')){
				sidebar.classList.toggle('close')
				toggleButton.classList.toggle('rotate')
			}
			}

			function closeAllSubMenus(){
			Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
				ul.classList.remove('show')
				ul.previousElementSibling.classList.remove('rotate')
			})
	}

			const modal = document.getElementById('customModal');
			const triggerModal = document.getElementById('triggerModal');
			const closeModalBtn = document.querySelector('.modal .modal-close');

			triggerModal.onclick = function(event) {
				event.preventDefault(); 
				modal.style.display = 'flex';
			};

			closeModalBtn.onclick = function() {
				modal.style.display = 'none';
			};

	</script>
	</body>
</html>