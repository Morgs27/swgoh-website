<?php 
include '../functions/db_connect.php';

$loadout_id = $_POST['id'];
$favorite = $_POST['favorite'];

$sql = "UPDATE loadouts SET favorite = '$favorite' WHERE loadoutID = '$loadout_id'";
$result = $conn->query($sql);
echo $sql;