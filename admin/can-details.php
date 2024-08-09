<?php
include 'admin-sidebar.php';
include 'db.php';


$usere = $prof = $usern = $grade = $program = $voter_id = $gender = $club = $votestatus = $acadyr = $dateregis = "";
$can_id = $can_details = $can_plat = $can_party = $date_applied = $can_status = $position = "";
$image_count = 0;
$images = [];


if (isset($_GET['candidate_id'])) {
    $candidate_id = $_GET['candidate_id'];

    if (isset($_FILES['images']['name'][0]) && !empty($_FILES['images']['name'][0])) {
        $max_files = 2;
        if (count($_FILES['images']['name']) > $max_files) {
            echo "You can upload a maximum of $max_files images.";
            exit;
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file = fopen($tmp_name, 'rb');
            $image = fread($file, filesize($tmp_name));
            fclose($file);
            $image = mysqli_real_escape_string($conn, $image);

            $imgqry = "INSERT INTO images (candidate_id, image, date_uploaded) VALUES ('$candidate_id', '$image', NOW())";
            mysqli_query($conn, $imgqry);
        }
    }

    if (isset($_POST['delete_image'])) {
        $image_id = $_POST['image_id'];
        $deleteQuery = "DELETE FROM images WHERE id = $image_id";
        mysqli_query($conn, $deleteQuery);

    }

    $countQuery = "SELECT COUNT(*) as image_count FROM images WHERE candidate_id = $candidate_id";
    $countResult = mysqli_query($conn, $countQuery);
    if ($countResult->num_rows > 0) {
        $countRow = $countResult->fetch_assoc();
        $image_count = $countRow['image_count'];
    }

    $query = "SELECT u.user_email, u.user_profile, u.user_name, v.voter_grade, v.program_code, v.voter_id, v.voter_gender, v.voter_club, v.academic_year, v.date_registered, v.vote_status, c.candidate_id, c.candidate_details, c.platform, c.party_code, c.date_applied, c.status, p.position_name
              FROM users u
              INNER JOIN voters v ON u.user_id = v.user_id
              INNER JOIN candidate c ON v.voter_id = c.voter_id
              LEFT JOIN position p ON c.position_id = p.position_id
              WHERE c.candidate_id = $candidate_id";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usere = $row['user_email'];
            $prof = $row['user_profile'];
            $usern = $row['user_name'];
            $grade = $row['voter_grade'];
            $program = $row['program_code'];
            $voter_id = $row['voter_id'];
            $club = $row['voter_club'];
            $gender = $row['voter_gender'];
            $acadyr = $row['academic_year'];
            $dateregis = $row['date_registered'];
            $votestatus = $row['vote_status'];
            $can_id = $row['candidate_id'];
            $can_details = $row['candidate_details'];
            $can_plat = $row['platform'];
            $can_party = $row['party_code'];
            $date_applied = $row['date_applied'];
            $can_status = $row['status'];
            $position = $row['position_name'];
            
        }
    }


    $imageQuery = "SELECT id, image FROM images WHERE candidate_id = $candidate_id";
    $imageResult = mysqli_query($conn, $imageQuery);
    if ($imageResult->num_rows > 0) {
        while ($imageRow = $imageResult->fetch_assoc()) {
            $images[] = [
                'id' => $imageRow['id'],
                'url' => 'data:image/jpeg;base64,' . base64_encode($imageRow['image'])
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width-device-width; initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="admin-style/candidate_style.css?v=<?php echo time(); ?>">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
        <title>EMVS</title>
    </head>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background-image: url("assets/images/background.jpg");
            overflow-y: auto;
            box-sizing: border-box;
            font-family: "Poppins" , sans-serif;
        }

        .main-content {
            position: absolute;
            top: 0;
            left: 80px;
            transition: all 0.5s ease;
            width: calc(100% - 78px);
            display: flex;
            flex-wrap: wrap;
            padding: 0.3rem;
            
        }

        .sidebar.active ~ .main-content {
            width: calc(100% - 240px);
            left: 240px;
        }

        .student-profile .card {
            border-radius: 10px;
            text-align: left;
        }

        .main-content .student-profile .card .card-header .profile-pic {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin: 10px auto;
            border-radius: 50%;
        }

        .main-content .student-profile .card .card-header img {
            width: 150px;
            height: 150px;
            margin: 10px auto;
        }

        .student-profile .card h3 {
            font-size: 20px;
            font-weight: 700;
        }

        .main-content .student-profile .card .card-header .profile_img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin: 10px auto;
            border-radius: 50%;
        }

        .student-profile .card p {
            font-size: 16px;
            color: #000;
        }

        .student-profile .table th,
        .student-profile .table td {
            font-size: 14px;
            padding: 5px 10px;
            color: #000;
        }

        .back-button {
            width: 40px;
            padding-left: 8px;
            font-size: 25px;
            margin-left: 15px;
        }

        .back-button:hover {
            color: blue;
        }
    </style>
    <body>
        <div class="main-content">
            <div class="student-profile py-4">
            <div class="back-button">
                <i class='bx bx-arrow-back' onclick="location.href='admin-candidate.php'"></i>
            </div>
                <div class="container">
                    <div class="row">
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                        <div class="card-header bg-transparent text-center">
                            <?php 

                                if (!$prof == NULL || !$prof == '' ) {
                                    echo "<img class='profile_img' src='data:image/jpeg;base64, $prof' alt='Profile'>";
                                } else {
                                    echo "<img src='admin-img/profile.png' alt='Image 1'>";
                                }
                            
                            
                            ?>

                            <h3><?php echo $usern; ?></h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><strong class="pr-1">Email:</strong><?php echo $usere; ?></p>
                            <p class="mb-0"><strong class="pr-1">Grade/Year Level:</strong><?php echo $grade; ?></p>
                            <p class="mb-0"><strong class="pr-1">Program:</strong></strong><?php echo $program; ?></p>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h3 class="mb-0"><i class="far fa-clone pr-1"></i>Voter Information</h3>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table table-bordered">
                            <tr>
                                <th width="30%">Voter ID</th>
                                <td width="2%">:</td>
                                <td><?php echo $voter_id; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Gender</th>
                                <td width="2%">:</td>
                                <td><?php echo $gender; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Club</th>
                                <td width="2%">:</td>
                                <td><?php echo $club; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Vote Status</th>
                                <td width="2%">:</td>
                                <td><?php echo $votestatus; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Academic Year</th>
                                <td width="2%">:</td>
                                <td><?php echo $acadyr; ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Date Registered</th>
                                <td width="2%">:</td>
                                <td><?php echo $dateregis; ?></td>
                            </tr>
                            </table>
                        </div>
                        </div>
                        <div style="height: 26px"></div>
                            <div class="card shadow-sm">
                                <div class="card-header bg-transparent border-0">
                                    <h3 class="mb-0"><i class="far fa-clone pr-1"></i>Candidacy Information</h3>
                                </div>
                                <div class="card-body pt-0">
                                    <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Candidate ID</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $can_id; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Position</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $position; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Details</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $can_details; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Platform</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $can_plat; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Partylist</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $can_party; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Image Uploaded</th>
                                        <td width="2%">:</td>
                                        <td><a>
                                        <?php if ($image_count > 0): ?>
                                                <a href="#" data-toggle="modal" data-target="#imageModal"><?php echo $image_count; ?> Image(s)</a>
                                            <?php else: ?>
                                                <a href="#" data-toggle="modal" data-target="#imageModal">Add Image</a>
                                            <?php endif; ?>
                                        </a></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Date Applied</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $date_applied; ?></td>
                                    </tr>
                                    <tr>
                                        <th width="30%">Candidacy Status</th>
                                        <td width="2%">:</td>
                                        <td><?php echo $can_status; ?></td>
                                    </tr>
                                </table>
                                </div>
                                    
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Images</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (!empty($images)): ?>
                    <ul class="list-group">
                        <?php foreach ($images as $image): ?>
                            <li class="list-group-item">
                                <img src="<?php echo $image['url']; ?>" alt="Candidate Image" class="img-thumbnail" width="150">
                                <form method="post" action="">
                                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                    <button type="submit" name="delete_image" class="btn btn-danger btn-sm float-right">Delete</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No images uploaded.</p>
                <?php endif; ?>

                <hr>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="images">Upload Images:</label>
                        <input type="file" name="images[]" id="images" multiple class="form-control-file">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    <script>
    </script>
</html>