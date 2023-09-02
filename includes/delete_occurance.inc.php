<?php 
include '../functions/db_connect.php';



$loadout_id = $_POST['id'];

$sql = "DELETE from farming_character_occurance WHERE occurance_id = '$loadout_id' OR temp_id = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);