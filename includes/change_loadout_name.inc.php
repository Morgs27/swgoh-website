<?php 
include '../functions/db_connect.php';



$loadout_id = $_POST['id'];
$name = $_POST['name'];


$sql = "UPDATE loadouts SET loadout_name = '$name' WHERE loadoutID = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);

