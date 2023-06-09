<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="css/general.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="bootstrap-5.3.0/dist/js/bootstrap.bundle.js"></script>
    <title>CHATOS</title>
</head>
<body>
    <nav>
        <div class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
        <a href="index.php" id="logo"><h1 >CHATOS</h1></a>
        <div class="right">
            <ul class="navbutton">
                <?php
                    if (isset($_SESSION["username"])) {
                ?>
                    <li><a href='profile.php' class='link'>Profile</a></li>
                    <li><a href='inc/logout.inc.php' class='link'>Log Out</a></li>
                <?php
                    } 
                    else {
                ?>
                    <li><a href='signup.php' class='link' >Sign Up</a></li>
                    <li><a href='login.php' class='link'>Log In</a></li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </nav>
<main>