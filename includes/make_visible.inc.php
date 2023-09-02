<?php 
include '../functions/db_connect.php';



$loadout_id = $_POST['id'];

$sql = "UPDATE loadouts SET guild_view = 'true' WHERE loadoutID = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);