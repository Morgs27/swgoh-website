<?php
ob_start();
session_start();

include 'header.php';

if (isset($_SESSION['Username'])){
    header ("Location: teams.php");
}

?>

<body>


<div class="welcomeText">
    Welcome 
    <div id = 'to'>to</div>  
    SWGOH Team Manager
    <div class = 'homeLinks'>
        <a href="signup.php" class="homeLink">Sign Up</a>
        <a href="login.php" class="homeLink">Log In</a>
        <a href= "https://discord.gg/JZdbfMhm94" class="homeLink">Discord</a>
    </div>  
</div>

<div class="refresh_modal homeLoader" >
        <div class="spinner_container" >
            <div class="spinner_circle"></div>
            <div class="spinner_circle outer_1"></div>
            <div class="spinner_circle outer_2"></div>
            <div class="spinner_circle plannet_1"></div>
            <div class="spinner_circle plannet_2"></div>
        </div> 
</div>





</body>
