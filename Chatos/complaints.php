<?php
    session_start();
    $_SESSION["idc"] = -1;

    require_once "inc/dbh.inc.php";
    require_once "inc/functions.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Chatos</title>
</head>
<body>
    <?php require_once "micro/navClassic.php"; ?>
    <main>
        <?php
            if($_SESSION["role"] != "admin") {
                echo "
                    <form action='inc/sendComplaint.inc.php' method='post'>
                        <input type='text' name='complaint' class='complaintText' placeholder='Write a complaint...'>
                        <input type='submit' name='submit' class='complaintSubmit' value='Send'>
                    </form>
                ";
            }
            else {

                $resultData = $database->getComplaints();

                if($row = mysqli_fetch_assoc($resultData)) {
                    do {
                        echo "<h2>" . $row["user"] . "</h2>" . $row["message"] . "<br><br>";
                    }while($row = mysqli_fetch_assoc($resultData));
                }
                else {
                    echo "There are no complaints for You admin.";
                }
            }
        ?>
    </main>
</body>
</html>