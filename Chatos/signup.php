<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <title>Sign Up</title>
</head>
<body>
    <?php require_once "micro/navClassic.php"; ?>
    <main>
        <div class="log_sig">
        <h1 class="login-title">Create account</h1>
       
        <form action="inc/signup.inc.php" method = "post">
            <div class="input">
                        <input class="input__field_2" name="uid" type="text" required />
                        <label for="username" class="input__label">Username</label>
                        <br>
                    </div>
                    <div class="input">
                        <input class="input__field" name="pwd" type="password"  required />
                        <label for="password" class="input__label">Password</label>
                         <br>
                    </div>
                     <div class="input">
                         
                        <input class="input__field" name="pwd2" type="password"  required />
                        <label for="password" class="input__label">Repeat password</label>
                        
                        
                    </div>
            
            <?php require "inc/errors.inc.php"; ?>
            <br>
            <input type="submit" name="submit" class="login-button" value="Create Account">
        
      </form>
        </div>
    </main>
</body>
</html>