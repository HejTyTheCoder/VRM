<nav>
    <div class="left">
        <a href="index.php" class="backArrow"><</a>
    </div>
    <div class="right">
        <ul>
            <?php
                if(isset($_SESSION["username"])) {
                    echo "<li><a href='profile.php'>Profile</a></li>";
                    echo "<li><a href='inc/logout.inc.php'>Log Out</a></li>";  
                }
                else {
                    echo "<li><a href='signup.php'>Sign Up</a></li>"; 
                    echo "<li><a href='login.php'>Log In</a></li>";                        
                }
            ?>
        </ul>
    </div>
</nav>