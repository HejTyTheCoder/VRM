<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link
            href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css"
            rel="stylesheet"
            />
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>Log In</title>
    </head>
    <body>
        <?php require_once "micro/navClassic.php"; ?>
        <main>
            <div class="log_sig">
                <h1 class="login-title">Log In</h1>
                <form action="inc/login.inc.php" method="post">
                    <div class="input">
                        <input class="input__field_2" name="uid" type="text" required />
                        <label for="username" class="input__label">Username</label>


                        <br>
                    </div>
                    <div class="input">
                        <input class="input__field" name="pwd" type="password"  required />
                        <label for="password" class="input__label">Password</label>

                        <span class="input__icon-wrapper">
                            <i class="input__icon ri-eye-off-line"></i>
                        </span>
                         
                    </div>
                    <?php require "inc/errors.inc.php"; ?>
                    <br>
                    <input type="submit" name="submit" class="login-button" value="Log In">
                </form>
            </div>

            <script>
                const inputIcon = document.querySelector('.input__icon')
                const inputPassword = document.querySelector('.input__field')

                inputIcon.addEventListener('click', () => {
                    inputIcon.classList.toggle('ri-eye-off-line')
                    inputIcon.classList.toggle('ri-eye-line')
                    inputPassword.type =
                            inputPassword.type === 'password' ? 'text' : 'password'
                })
            </script>
        </main>
    </body>
</html>