<?php 
include '../functions/db_connect.php';


$loadout_id = $_POST['id'];

$sql = "DELETE from loadouts WHERE loadoutID = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);