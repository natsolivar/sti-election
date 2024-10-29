<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="utf-8">
 <title>EMVS Admin</title>
 <link href="assets/img/favicon.ico" rel="icon">
<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
  $(document).ready(function(){
    $("#myModal").modal('show');
  });
</script>
</head>
<?php 
    
    if(isset($_POST['check'])){
    if (count($_POST) > 0) {
        $code = $_POST['admincheck'];

        if ($code == 'emvs'){
            header('location: admin/admin-login.php');
        } else {
            echo '<script>';
            echo 'alert("Wrong code!")';
            echo '</script>';
        }
      }   
    } elseif (isset($_POST) < 3) 

?>
<body style="background-color: #0079c2">
<div id="myModal" class="modal hide" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">You found the admin page!</h5>
                <button type="button" class="close" data-dismiss="modal" onclick="location.href='index.php'">&times;</button>
            </div>
            <div class="modal-body">
        <p>This is an admin only page! It contains data and information that is not open for public! But if you are the admin, or have the permission to check this page, please write down the access code.</p>
        <form method="post" autocomplete="off" action="admincheck.php" id="form1">
                    <input type="password" id="code" name="admincheck" placeholder="****" maxlength="4" required autofocus style="width: 75px;"><br><br>
                    <input type="submit" name="check" class="btn btn-primary" onclick="validate()">
           </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>