<?php 
    ob_start();
    include 'db.php';
    include 'sidebar.php';

    $voter_id = $_SESSION['voter_id'];

    $qry = "SELECT u.user_name, p.position_name, c.candidate_id FROM users u
            LEFT JOIN voters v ON u.user_id = v.user_id
            LEFT JOIN candidate c ON v.voter_id = c.voter_id
            LEFT JOIN position p ON c.position_id = p.position_id
            WHERE v.voter_id = '$voter_id'";
    $res =  mysqli_query($conn, $qry);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $usern = $row['user_name'];
        $pos_name = $row['position_name'];
        $can_id = $row['candidate_id'];

        $usern = str_replace("(Student)", "", $usern);
        $name_parts = explode(", ", trim($usern));
            if (count($name_parts) == 2) {
                $formatted_name1 = $name_parts[1] . " " . $name_parts[0];
                $fname = $name_parts[1];
            } else {
                $formatted_name1 = $usern;
            }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $action = $_POST['action'];
        $voterId = $_POST['id'];
    

        $voterId = intval($voterId); 
    
        if ($action == 'accept') {
            $query = "UPDATE candidate SET status = 'Accepted' WHERE voter_id = $voterId";
    
            if ($conn->query($query) === TRUE) {
                $_SESSION['message'] = "Position accepted successfully.";
                header('Location: homepage');
                exit();
            } else {
                $_SESSION['message'] = "Error accepting invitation: " . $conn->error;
            }
        } elseif ($action == 'reject') {
            try {
                
                $query1 = "DELETE FROM images WHERE candidate_id = $can_id";
                $conn->query($query1);

                $query2 = "DELETE FROM candidate WHERE voter_id = $voterId";
                $conn->query($query2);
        
                $query3 = "DELETE FROM council WHERE voter_id = $voterId";
                $conn->query($query3);
        
                $conn->commit();
                $_SESSION['message'] = "Position rejected successfully.";
                header('Location: homepage');
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error deleting records: " . $e->getMessage();
            }
        }
    }
    ob_end_flush();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width-device-width; initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EMVS</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        body {
            font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-y: auto;
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            grid-template-columns: auto 1fr;
        }

        .main-container {
            padding: 5em;
        }

        .main-container .container {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 15px;
        }

        .main-container .container .grid {
            padding: 0.5em;
        }

        .main-container .container #header {
            border-bottom: 1px solid grey;
        }

        .main-container .container #letter {
            text-align: justify;
        }

        .main-container .container #btn {
            display: flex;
            justify-content: center;
        }

        .main-container .container .grid #accept, #confirmButton {
            appearance: none;
            background-color: #2ea44f;
            border: 1px solid rgba(27, 31, 35, .15);
            border-radius: 6px;
            box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-family: -apple-system,system-ui,"Segoe UI",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
            font-size: 14px;
            font-weight: 600;
            line-height: 20px;
            padding: 6px 16px;
            text-align: center;
            text-decoration: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            white-space: nowrap;
            margin-right: 10px;
        }

        .main-container .container .grid #reject, #cancelButton {
            appearance: none;
            background-color: #880808;
            border: 1px solid rgba(27, 31, 35, .15);
            border-radius: 6px;
            box-shadow: rgba(27, 31, 35, .1) 0 1px 0;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
            line-height: 20px;
            padding: 6px 16px;
            text-align: center;
            text-decoration: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            white-space: nowrap;
        }

        .main-container .container .grid #accept:focus:not(:focus-visible):not(.focus-visible) {
            box-shadow: none;
            outline: none;
        }

        .main-container .container .grid #reject:focus:not(:focus-visible):not(.focus-visible) {
            box-shadow: none;
            outline: none;
        }

        .main-container .container .grid #accept:hover {
            background-color: #2c974b;
        }

        .main-container .container .grid #reject:hover {
            background-color: #800020;
        }

        .main-container .container .grid #accept:focus {
            box-shadow: rgba(46, 164, 79, .4) 0 0 0 3px;
            outline: none;
        }

        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.5); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            width: 40%; 
        }

        .modal-content #modalMessage {
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="back-button">
                <i class='bx bx-arrow-back' onclick="location.href='javascript:history.go(-1)'"></i>
            </div>
            <div class="grid" id="header"><h2>Subject: Invitation to Join the Council of Leaders</h2></div>
            <div class="grid" id="title"><p>Dear <?php echo $fname; ?>,</p></div>
            <div class="grid" id="letter">
                <p>We are pleased to offer you the position of <b><?php echo $pos_name; ?></b> on the Council of Leaders. Your leadership skills, academic achievements, and dedication to our school community make you an ideal candidate for this role.</p>
                <p>We believe that your unique perspective and talents will be a valuable asset to our team. If you are interested in this position please click the accept button.</p>
                <p>We look forward to working with you.</p>
            </div>
            <div class="grid" id="closing">
                <p>Sincerely,</p><br>
                <p><i>Mr. Phillip Edgar Sabayle</i></p>
                <p>Student Affairs Officer</p>
            </div>
            <div class="grid" id="btn">
                <form id="acceptForm" action="" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="accept">
                    <input type="hidden" name="id" value="<?php echo $voter_id; ?>">
                    <button type="button" class="button-3" id="accept">Accept</button>
                </form>

                <form id="rejectForm" action="" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="reject">
                    <input type="hidden" name="id" value="<?php echo $voter_id; ?>">
                    <button type="button" class="button-3" id="reject">Reject</button>
                </form>
            </div>
        </div>
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <p id="modalMessage"></p>
                <button id="confirmButton">Confirm</button>
                <button id="cancelButton">Cancel</button>
            </div>
        </div>
    </div>
    <script>
         document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById("confirmationModal");
        const modalMessage = document.getElementById("modalMessage");
        const acceptButton = document.getElementById("accept");
        const rejectButton = document.getElementById("reject");
        const confirmButton = document.getElementById("confirmButton");
        const cancelButton = document.getElementById("cancelButton");
        const closeModal = document.getElementById("closeModal");

        acceptButton.onclick = function () {
            console.log("Accept button clicked."); 
            modalMessage.innerText = "Are you sure you want to accept this position?";
            modal.style.display = "block";
            confirmButton.onclick = function () {
                console.log("Confirming accept.");
                document.getElementById("acceptForm").submit();
            };
        };

        rejectButton.onclick = function () {
            console.log("Reject button clicked.");
            modalMessage.innerText = "Are you sure you want to reject this position?";
            modal.style.display = "block";
            confirmButton.onclick = function () {
                console.log("Confirming reject."); 
                document.getElementById("rejectForm").submit();
            };
        };

        closeModal.onclick = function () {
            modal.style.display = "none";
        };

        cancelButton.onclick = function () {
            modal.style.display = "none";
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    });

    setTimeout(() => {
    document.getElementById("successMessage").style.display = "none";
    }, 5000);
    </script>
</body>
</html>