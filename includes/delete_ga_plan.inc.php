<?php 
include '../functions/db_connect.php';



$loadout_id = $_POST['id'];

$sql = "DELETE from ga_loadouts WHERE loadout_id = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);