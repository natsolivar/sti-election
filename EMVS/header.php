<!DOCTYPE html>
<h>
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="script.js"></script>
        <title>Election Management System</title>
        <style>
@import url('https://fonts.googleapis.com/css?family=Montserrat:500');


ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

nav {
    position: absolute;
    top: 0;
    bottom: 0;
    height: 100%;
    left: 0;
    background: #0079c2;
    width: 90px;
    overflow: hidden;
    transition: width 0.2s linear;
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0);
}


a {
    position: relative;
    color: aliceblue;
    font-size: 14px;
    display: table;
    width: 300px;
    padding: 10px;
    text-decoration: none;
}

.fas{
    position: relative;
    width: 70px;
    height: 40px;
    top: 14px;
    font-size: 20px;
    text-align: center
}

.logo {
    text-align: center;
    display: flex;
    transition: all 0.5s ease;
    margin: 10px 0 0 10px;
}

.logo img {
    width: 45px;
    height: 45px;
    border-radius: 50px;
}

.logo span {
    font-weight: bold;
    padding-left: 25px;
    font-size: 18px;
    text-transform: uppercase;
    color: black;
}

.nav-item {
    position: relative;
    top: 12px;
    margin-left: 10px;
}

nav:hover {
    width: 280px;
    transition: all 0.5s ease;
}

.logout {
    position: absolute;
    bottom: 0;
}

.header {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    padding: 10px 10%;
    background-color: #0079c2;
    font-family: "Montserrat", sans-serif;
    overflow: auto;
}

.name h2{
    font-weight: bolder;
    color: black;
}
        </style>
    </head>
      </head>
      <body>
        <nav>
            <ul>
                <li><a href="homepage.php" class="logo"><img src="assets/logo/STI-LOGO.png" alt=""><span>EMVS</span></i>
                <li><a href="#"><i class="fas fa-home"></i>
                <span class="nav-item">Home</span></a></li>
                <li><a href="#"><i class="fas fa-home"></i>
                <span class="nav-item">Home</span></a></li>
                <li><a href="#"><i class="fas fa-home"></i>
                <span class="nav-item">Home</span></a></li>
                <li><a href="#"><i class="fas fa-home"></i>
                <span class="nav-item">Home</span></a></li>
                <li><a href="#" class="logout"><i class="fas fa-home"></i>
                <span class="nav-item">Home</span></a></li>
            </ul>
        </nav>
      </body>
</html>