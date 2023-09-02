<?php
session_start();
unset($_SESSION["Username"]);
unset($_SESSION['guest']);
unset($_SESSION['guild_data']);
unset($_SESSION['user_info_ship']);
unset($_SESSION['user_info_class']);


header("location: ../index.php");
exit();

?>