<?php
ob_start();
session_start();
include_once 'header.php';

check_logged_guild($conn);

$guild_id = get_guild($conn,$_SESSION["Username"]);
$guild_code = get_guild_code($conn,$guild_id);
$guild_name = get_guild_name($conn,$guild_id);

?>
<link rel="stylesheet" type="text/css" href="styles/join_guild.css"/>

<div class="team_builder_bar" style = 'position:relative'>

<img src="images/tw_map_2.jpg" alt="" >
<div class="title"><?php echo $guild_name;?></div>
                                                 
</div>

<div class="content_container" style = 'padding-bottom: 20px'>
<?php

echo "<div class = 'guild_code'>$guild_code</div>";
echo "<div class = 'confirmation_text'>Give people the code above so that they can join your guild.</div>";
?>

<a href = 'myGuild.php'>
<div class="close"></div>
</a>

</div>

