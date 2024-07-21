<?php
session_start();
include 'session.php';
require 'config.php';

function getSchoolYear($currentDate) {
    $currentMonth = (int) date('m', strtotime($currentDate));
    $currentYear = (int) date('Y', strtotime($currentDate));
    
    if ($currentMonth >= 9) {
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
    $voter_grade = "";
    $voter_gender = "";
    $voter_program = "";
    $voter_club = "";
    $voter_num = "";
    $voter_pass = "";
    $currentDate = date('Y-m-d');
    $schoolYear = getSchoolYear($currentDate);

$sql = mysqli_connect('localhost', 'root', '', 'emvs');

if (isset($_POST['register'])) {
    $voter_grade = mysqli_real_escape_string($sql, $_POST['grade']);
    $voter_gender = mysqli_real_escape_string($sql, $_POST['gender']);
    $voter_program = mysqli_real_escape_string($sql, $_POST['program']);
    $voter_club = mysqli_real_escape_string($sql, $_POST['club']);
    $voter_num = mysqli_real_escape_string($sql, $_POST['num']);
    $voter_pass = mysqli_real_escape_string($sql, $_POST['confirm-pass']);
    
    $voter_pass_hashed = password_hash($voter_pass, PASSWORD_DEFAULT);

    $qry2 = "SELECT user_id FROM users WHERE user_email = '$_SESSION[userID]'";
    $result = mysqli_query($sql, $qry2);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        $qry3 = "INSERT INTO voters (user_id, voter_gender, voter_num, voter_grade, program_code, voter_club, academic_year, date_registered, vote_status) VALUES ('$user_id', '$voter_gender', '$voter_num', '$voter_grade', '$voter_program', '$voter_club', '$schoolYear', NOW(), 'NO')";
        $result_insert = mysqli_query($sql, $qry3);

        if ($result_insert) {
            $qry4 = "UPDATE users SET user_pw = '$voter_pass_hashed' WHERE user_email = '$voter_email'";
            $result_update = mysqli_query($sql, $qry4);

            if ($result_update) {
                $qry5 = "SELECT voter_id FROM voters WHERE user_id = '$userID'";
                $result_select = mysqli_query($sql, $qry5);
                if ($result_select->num_rows > 0) { 
                    while ($row = $result_select->fetch_assoc()) {
                        $voter_id = $row['voter_id'];
                        $SESSION['voter_id'] = $voter_id;
                    }
                }
                header('location: homepage.php');
                exit;
            } else {
                echo 'Error updating password: ' . mysqli_error($sql);
            }
        } else {
            echo 'Error inserting voter: ' . mysqli_error($sql);
        }
    } else {
        echo 'Error fetching user_id: ' . mysqli_error($sql);
    }
}
mysqli_close($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <title>Register | EMVS</title>
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins" , sans-serif;   
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
        }

        .input-box {
            display: flex;
            flex-wrap: wrap;
            width: 50%;
            padding-bottom: 15px;
        }

        .input-box:nth-child(2n) {
            justify-content: start;
        }

        .input-box label, .gender-title {
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
                <h2>Voter Registration</h2>
                    <div class="content">
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
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
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
                        <div class="input-box">
                            <label for="phonenumber">Phone number</label>
                            <input type="tel" placeholder="Enter phone number" id="num" name="num" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="11" required>
                        </div>
                        <div class="input-box">
                            <label for="club">Club</label>
                            <select id="club" name="club" required>
                                <option value="SINS">Society of Information System</option>
                                <option value="g12">to be added ..</option>
                                <option value="1st">to be added ..</option>
                                <option value="2nd">to be added ..</option>
                                <option value="3rd">to be added ..</option>
                                <option value="4th">to be added ..</option>
                            </select>
                        </div>
                        <div class="input-box">
                            <label for="password">Password</label>
                            <input type="password" id="password" placeholder="Enter password" name="pass" required>
                        </div>
                        <div class="input-box" id="confirm-pass">
                            <label for="confirm-password">Confirm Password</label>
                            <input type="password" id="confirm-password" placeholder="Confirm password" name="confirm-pass" required disabled>
                        </div>
                        <div id="validation-message" class="validation-message">
                                <span id="length" class="invalid">At least 8 characters</span>
                                <span id="uppercase" class="invalid">At least 1 uppercase letter</span>
                                <span id="special" class="invalid">At least 1 special character</span>
                                <span id="number" class="invalid">At least 1 number</span>
                        </div>
                        <div id="confirm-password-validation-message" class="validation-message">
                                <span id="confirm-message" class="invalid">Passwords do not match</span>
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
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolore eos exercitationem sequi hic totam magnam in nemo fugit sapiente eius.</p>
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
    'Grade 11': [
      { value: 'ABM', text: 'Accountancy, Business, and Management(ABM)' },
      { value: 'HUMMS', text: 'Humanities and Social Sciences(HUMSS)' },
      { value: 'STEM', text: 'Science, Technology, Engineering, and Mathematics(STEM)' },
      { value: 'CUART', text: 'Culinary Arts(CUART)' },
      { value: 'MAWD', text: 'Mobile App & Web Development(MAWD)' }
    ],
    'Grade 12': [
      { value: 'ABM', text: 'Accountancy, Business, and Management(ABM)' },
      { value: 'HUMMS', text: 'Humanities and Social Sciences(HUMSS)' },
      { value: 'STEM', text: 'Science, Technology, Engineering, and Mathematics(STEM)' },
      { value: 'CUART', text: 'Culinary Arts(CUART)' },
      { value: 'MAWD', text: 'Mobile App & Web Development(MAWD)' }
    ],
    '1st year': [
      { value: 'BSIS', text: 'Bachelor of Science in Information System(BSIS)' },
      { value: 'BSTM', text: 'Bachelor of Science in Tourism Management(BSTM)' }
    ],
    '2nd year': [
      { value: 'BSIS', text: 'Bachelor of Science in Information System(BSIS)' },
      { value: 'BSTM', text: 'Bachelor of Science in Tourism Management(BSTM)' }
    ],
    '3rd year': [
      { value: 'BSIS', text: 'Bachelor of Science in Information System(BSIS)' },
      { value: 'BSTM', text: 'Bachelor of Science in Tourism Management(BSTM)' }
    ],
    '4th year': [
      { value: 'BSIS', text: 'Bachelor of Science in Information System(BSIS)' },
      { value: 'BSTM', text: 'Bachelor of Science in Tourism Management(BSTM)' }
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