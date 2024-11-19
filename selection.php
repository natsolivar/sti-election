<?php 
session_start();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <title>STI | Systems</title>
    </head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            overflow-y: auto;
            min-height: 100vh;
            min-height: 100dvh;
            background-image: url('assets/images/selection-bg2.jfif');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }

        .main .grid-container {
            display: grid;
            grid-template-columns: repeat(2, auto);
            justify-content: space-evenly;
            height: 100vh;
            place-items: center;
        }

        .main .grid-container .card .card-container {
            display: grid;
            grid-template-columns: repeat(1fr, 1fr);
            max-width: 300px;
            min-height: 250px;
            border-radius: 8%;
            padding: 1.5em;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            > a {
                text-decoration: none;
            }
        }

        .main .grid-container .card .card-container:hover {
            transform: scale(1.1);
            transition: transform 0.3s ease-in-out;
        }

        .main .grid-container .card #attendance {
            background-color: #E4D00A;
            border: 1px solid #DFFF00;
        }

        .main .grid-container .card #election {
            background-color: #0079c2;
            border: 1px solid #0096FF;
        }

        .main .grid-container .card .card-container .min-card .logo {
            margin-top: 30px;
        }

        .main .grid-container .card .card-container .min-card #att img {
            width: 85px;
        }
        .main .grid-container .card .card-container .min-card #elec img {
            width: 100px;
        }

        .main .grid-container .card .card-container .min-card {
            color: black
        }

        .main .grid-container .card .card-container .min-card .detail {
            margin-top: 10px;
        }

        .main .grid-container .card .card-container .min-card .detail p {
            font-size: small;
        }

        @media(max-width: 800px) {
            .main .grid-container {
                display: grid;
                grid-template-columns: repeat(1, auto);
            }
        }
        
    </style>
    <body>
        <div class="main">
            <div class="grid-container">
                <div class="card">
                    <div class="card-container" id="attendance">
                        <a href="attendance/aas-homepage">
                        <div class="min-card">
                            <div class="logo" id="att"><img src="assets/logo/aas-sti1.png" alt="EMVS" /></div>
                        </div>
                        <div class="min-card">
                            <div class="title"><h3>Event Attendance System</h3></div>
                        </div>
                        <div class="min-card">
                            <div class="detail"><p>An Automated Attendance System for STI College Iligan to replace the current paper-based sign-in and sign-out procedure.</p></div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-container" id="election">
                        <a href="homepage">
                        <div class="min-card">
                            <div class="logo" id="elec"><img src="assets/logo/emvs-sti1.png" alt="EMVS" /></div>
                        </div>
                        <div class="min-card">
                            <div class="title"><h3>Election Voting System</h3></div>
                        </div>
                        <div class="min-card">
                            <div class="detail"><p>A comprehensive system for the COL election that handles all aspects of elections, from nominations and campaigns to voting and tabulating results.</p></div>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>