<?php

session_start();

require 'vendor/autoload.php';
include 'db.php';
include 'session.php';
require 'config.php';
require 'check_login.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

$uniqueString = uniqid('event_', true);

$builder = new Builder(
    writer: new PngWriter(),
    writerOptions: [],
    validateResult: false,
    data: $uniqueString,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::High,
    logoPath: __DIR__.'/assets/logo/STI-LOGO2.png',
    logoResizeToWidth: 50,
    logoPunchoutBackground: true,
    size: 300,
    margin: 10,
    roundBlockSizeMode: RoundBlockSizeMode::Margin,
);

    $result = $builder->build();

    $qrCodeData = $result->getString();
    $base64QrCode = base64_encode($qrCodeData);

if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];

    $result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM voters WHERE user_id = '$user_id'");
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result);
    if ($row['count'] > 0) {
        header('Location: selection');
        exit();
    }
}

function getSchoolYear($currentDate) {
    $currentMonth = (int) date('m', strtotime($currentDate));
    $currentYear = (int) date('Y', strtotime($currentDate));
    
    if ($currentMonth >= 8) {
        $startYear = $currentYear;
        $endYear = $currentYear + 1;
    } else {
        $startYear = $currentYear - 1;
        $endYear = $currentYear;
    }
    return "$startYear-$endYear";
    }

    $voter_name = $_SESSION['displayName'];
    $voter_email = $_SESSION['userEmail'];
    $student_num = "";
    $voter_grade = "";
    $voter_gender = "";
    $voter_program = "";
    $voter_num = "";
    $voter_pass = "";
    $currentDate = date('Y-m-d');
    $schoolYear = getSchoolYear($currentDate);

if (isset($_POST['register'])) {
    $student_num = mysqli_real_escape_string($conn, string: $_POST['studentnum']);
    $voter_grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $voter_gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $voter_program = mysqli_real_escape_string($conn, $_POST['program']);
    $voter_num = mysqli_real_escape_string($conn, $_POST['num']);

    $qry2 = "SELECT user_id FROM users WHERE user_id = '$_SESSION[userID]'";
    $result = mysqli_query($conn, $qry2);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        $studentqry = "INSERT INTO students (user_id, student_num, student_grade, student_program, gender, academic_year, student_qr, qr_content, date_registered, status) VALUES ('$user_id', '$student_num', '$voter_grade', '$voter_program', '$voter_gender', '$schoolYear', '$base64QrCode', '$uniqueString', NOW(), 'ACTIVE')";
        $stu_result = mysqli_query($conn, $studentqry);

        $qry3 = "INSERT INTO voters (user_id, voter_gender, voter_num, voter_grade, program_code, academic_year, date_registered, vote_status, status) VALUES ('$user_id', '$voter_gender', '$voter_num', '$voter_grade', '$voter_program', '$schoolYear', NOW(), 'NO', 'ACTIVE')";
        $result_insert = mysqli_query($conn, $qry3);

            if ($result_insert) {
                $qry5 = "SELECT voter_id FROM voters WHERE user_id = '$userID'";
                $result_select = mysqli_query($conn, $qry5);
                if ($result_select->num_rows > 0) { 
                    while ($row = $result_select->fetch_assoc()) {
                        $voter_id = $row['voter_id'];
                        $_SESSION['voter_id'] = $voter_id;
                    }
                }
                header('location: selection');
                exit;
        } else {
            echo 'Error inserting voter: ' . mysqli_error($conn);
        }
    } else {
        echo 'Error fetching user_id: ' . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="shortcut icon" href="assets/logo/STI-LOGO.png" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <title>Register | EMVS</title>
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Poppins, 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            max-width: 650px;
            padding: 28px;
            margin:  0 28px;
            box-shadow: 0 15px 20px #ABB2B9;
        }

        h2 {
            font-size: 26px;
            font-weight: 600;
            text-align: left;
            color: #2f4f4f;
            padding-bottom: 8px;
            border-bottom: 1px solid silver;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px 0;
            position: relative;
        }

        .content .static-text {
            position: absolute;
            left: 10px; 
            top: 19%;
            transform: translateY(-50%); 
            color: rgba(0, 0, 0, 0.6);
            pointer-events: none; 
            font-size: 14px;
        }

        .content .static-number {
            position: absolute;
            left: 310px; 
            top: 66%;
            transform: translateY(-50%); 
            color: rgba(0, 0, 0, 0.6);
            pointer-events: none; 
            font-size: 14px;
        }

        .content #bottom-header input {
            padding: 10px 10px 10px 45px;
            box-sizing: border-box;
        }

        .content #top-header input {
            padding: 10px 10px 10px 60px;
            box-sizing: border-box;
        }

        .input-box {
            display: flex;
            flex-wrap: wrap;
            width: 50%;
            padding-bottom: 15px;
            display: block;
        }

        .input-box:nth-child(2n) {
            justify-content: start;
        }

        .input-box label:not(.static-text,.static-number), .gender-title {
            width: 100%;
            color: #2f4f4f;
            font-weight: bold;
            margin: 5px 0;
        }

        .gender-title {
            font-size: 16px;
        }

        .input-box input, select{
            height: 40px;
            width: 95%;
            padding: 0 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }
        
        .input-box input:is(:focus, :valid) {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
        }

        .gender-category {
            color: grey;
        }

        .gender-category label {
            padding: 0 20px 0 5px;
            font-size: 14px;
        }

        .gender-category label, .gender-category input {
            cursor: pointer;
        }

        .alert p {
            font-size: 14px;
            font-style: italic;
            color: dimgray;
            margin: 5px 0;
            padding: 10px;
        }

        .alert a {
            text-decoration: none;
        }

        .alert a:hover {
            text-decoration: underline;
        }

        .button-container {
            margin: 15px 0;
        }

        .button-container button {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            display: block;
            font-size: 20px;
            color: #fff;
            border: none;
            border-radius: 5px;
            background-image: linear-gradient(to right, #0079c2, yellow);
        }

        .button-container button:hover {
            background-image: linear-gradient(to right, yellow, #0079c2);
        
        }
        .validation-message {
            font-size: 14px;
            margin-top: 8px;
            display: none;
        }

        .validation-message span {
            display: block;
            margin-top: 5px;
        }

        .validation-message .valid {
            color: green;
            display: none;
        }

        .validation-message .invalid {
            color: red;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }

        @media(max-width:600px) {
            .container {
                min-width: 280px;
            }
            .content {
                max-height: 380px;
                overflow: auto;
            }
            .input-box {
                margin-bottom: 12px;
                width: 100%;
            }
            .input-box:nth-child(2n) {
                justify-content: space-between;
            }
            .gender-category {
                display: flex;
                justify-content: space-between;
                width: 60%;
            }
            .content::-webkit-scrollbar {
                width: 0;
            }
        }

        </style>
    </head>
    <body>
        <div class="container">
            <form action="register.php" method="post" id="register" novalidate>
                <h2>Student and Voter Registration</h2>
                    <div class="content">
                        <div class="input-box" id="top-header">
                            <label for="studentnum">Student Number</label>
                            <label for="studentnum" class="static-text">02000</label>
                            <input inputmode="numeric" placeholder="******" id="studentnum" name="studentnum" maxlength="6" autofocus required oninput="this.value = this.value.replace(/\D+/g, '')" required>
                        </div>
                        <div class="input-box">
                            <label for="name">Full Name</label>
                            <input type="text" placeholder="<?php echo htmlspecialchars($_SESSION['displayName']); ?>" name="name" readonly>
                        </div>
                        <div class="input-box">
                            <label for="Email">Email</label>
                            <input type="text" placeholder="<?php echo htmlspecialchars($_SESSION['userEmail']); ?>" name="email" readonly>
                        </div>
                        <div class="input-box">
                            <label for="grade">Grade/Year Level</label>
                            <select id="grade" name="grade" required>
                                <option value="g11">Grade 11</option>
                                <option value="g12">Grade 12</option>
                                <option value="1st year">1st Year</option>
                                <option value="2nd year">2nd Year</option>
                                <option value="3rd year">3rd Year</option>
                                <option value="4th year">4th Year</option>
                            </select>
                        </div>
                        <div class="input-box">
                            <label for="program">Program</label>
                            <select id="program" name="program" required>
                            </select>
                        </div>
                        <div class="input-box" id="bottom-header">
                            <label for="phonenumber">Phone number</label>
                            <label for="phonenumber" class="static-number">+63</label>
                            <input type="tel" placeholder="9*********" id="num" name="num" oninput="this.value = this.value.replace(/\D+/g, '')" maxlength="10" required>
                        </div>

                        <span class="gender-title">Gender</span>
                            <div class="gender-category">
                                <input type="radio" name="gender" id="male" value="Male" required>
                                <label for="gender">Male</label>
                                <input type="radio" name="gender" id="female" value="Female">
                                <label for="gender">Female</label>
                                <input type="radio" name="gender" id="other" value="Other">
                                <label for="gender">Other</label>
                            </div>
                    </div>
                    <div class="alert">
                        <p>This registration form is for informational purposes only. To officially enroll or register to vote, please contact the appropriate institution or election board.</p>
                    </div>
                    <div class="button-container">
                        <div id="form-error-message" class="error-message" style="display: none;">Password requirement not meet</div>
                        <button type="submit" name="register">Register</button>
                    </div>
            </form>
        </div>
    </body>
    <script>
        const programOptions = {
    'g11': [
      { value: 'ABM', text: 'Accountancy, Business, and Management(ABM)' },
      { value: 'HUMSS', text: 'Humanities and Social Sciences(HUMSS)' },
      { value: 'STEM', text: 'Science, Technology, Engineering, and Mathematics(STEM)' },
      { value: 'CUART', text: 'Culinary Arts(CUART)' },
      { value: 'MAWD', text: 'Mobile App & Web Development(MAWD)' }
    ],
    'g12': [
      { value: 'ABM', text: 'Accountancy, Business, and Management(ABM)' },
      { value: 'HUMSS', text: 'Humanities and Social Sciences(HUMSS)' },
      { value: 'STEM', text: 'Science, Technology, Engineering, and Mathematics(STEM)' },
      { value: 'CUART', text: 'Culinary Arts(CUART)' },
      { value: 'MAWD', text: 'Mobile App & Web Development(MAWD)' }
    ],
    '1st year': [
      { value: 'BSIS', text: 'BS in Information System(BSIS)' },
      { value: 'BSTM', text: 'BS in Tourism Management(BSTM)' }
    ],
    '2nd year': [
      { value: 'BSIS', text: 'BS in Information System(BSIS)' },
      { value: 'BSTM', text: 'BS in Tourism Management(BSTM)' }
    ],
    '3rd year': [
      { value: 'BSIS', text: 'BS in Information System(BSIS)' },
      { value: 'BSTM', text: 'BS in Tourism Management(BSTM)' }
    ],
    '4th year': [
      { value: 'BSIS', text: 'BS in Information System(BSIS)' },
      { value: 'BSTM', text: 'BS in Tourism Management(BSTM)' }
    ]
  };

  document.getElementById('grade').addEventListener('change', function() {
    const grade = this.value;
    const programSelect = document.getElementById('program');

    programSelect.innerHTML = '';

    if (programOptions[grade]) {
      programOptions[grade].forEach(function(program) {
        const option = document.createElement('option');
        option.value = program.value;
        option.textContent = program.text;
        programSelect.appendChild(option);
      });
    }
  });
  document.getElementById('grade').dispatchEvent(new Event('change'));

        document.getElementById('password').addEventListener('input', function() {
            var password = this.value;
            var validationMessage = document.getElementById('validation-message');
            var length = document.getElementById('length');
            var uppercase = document.getElementById('uppercase');
            var special = document.getElementById('special');
            var number = document.getElementById('number');

            validationMessage.style.display = 'block';

            var isLengthValid = password.length >= 8;
            if (isLengthValid) {
                length.classList.remove('invalid');
                length.classList.add('valid');
            } else {
                length.classList.remove('valid');
                length.classList.add('invalid');
            }

            var isUppercaseValid = /[A-Z]/.test(password);
            if (isUppercaseValid) {
                uppercase.classList.remove('invalid');
                uppercase.classList.add('valid');
            } else {
                uppercase.classList.remove('valid');
                uppercase.classList.add('invalid');
            }

            var isSpecialValid = /[\W_]/.test(password);
            if (isSpecialValid) {
                special.classList.remove('invalid');
                special.classList.add('valid');
            } else {
                special.classList.remove('valid');
                special.classList.add('invalid');
            }

            var isNumberValid = /\d/.test(password);
            if (isNumberValid) {
                number.classList.remove('invalid');
                number.classList.add('valid');
            } else {
                number.classList.remove('valid');
                number.classList.add('invalid');
            }

            var confirmPasswordInput = document.getElementById('confirm-password');
            if (isLengthValid && isUppercaseValid && isSpecialValid && isNumberValid) {
                confirmPasswordInput.disabled = false;
            } else {
                confirmPasswordInput.disabled = true;
                document.getElementById('confirm-password-validation-message').style.display = 'none';
            }

        });

        document.getElementById('confirm-password').addEventListener('input', function() {
        var confirmPassword = this.value;
        var password = document.getElementById('password').value;
        var confirmMessage = document.getElementById('confirm-message');
        
        var confirmValidationMessage = document.getElementById('confirm-password-validation-message');
        confirmValidationMessage.style.display = 'block';

        if (confirmPassword === password) {
            confirmMessage.classList.remove('invalid');
            confirmMessage.classList.add('valid');
        } else {
            confirmMessage.classList.remove('valid');
            confirmMessage.classList.add('invalid');
        }
        });

        document.getElementById('register').addEventListener('submit', function(event) {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm-password').value;
        var formErrorMessage = document.getElementById('form-error-message');
        var isValid = true;

        if (password.length < 8 || !/[A-Z]/.test(password) || !/[\W_]/.test(password) || !/\d/.test(password)) {
            isValid = false;
        }

        if (password !== confirmPassword) {
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
            formErrorMessage.style.display = 'block';
        } else {
            formErrorMessage.style.display = 'none';
        }
        }); 
</script>
</html>