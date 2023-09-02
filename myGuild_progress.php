<?php
session_start();
ob_start();

include_once 'header.php';

check_logged_guild($conn);

$username = $_SESSION['Username'];

if (isset($_SESSION['guest'])){

}
else {
    $guild_id = get_guild($conn,$username);
    $guild_code = get_guild_code($conn,$guild_id);
    $guild_name = get_guild_name($conn,$guild_id);
}

?>

<link rel="stylesheet" href="styles/my_guild.css" />

<script src='script/my_guild.js'></script>

<div class="team_builder_bar" style = 'position:relative'>

<img src="images/tw_map_2.jpg" alt="" >
<div class="title">Guild Progress</div>

<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='myGuild.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>
                                       
</div>

This feature will be available shortly.    