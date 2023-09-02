<?php 
include '../functions/db_connect.php';



$occurance_id = $_POST['id'];
$gear = $_POST['gear'];
$relic = $_POST['relic'];



$sql = "UPDATE farming_character_occurance SET gear = '$gear', relic = '$relic' WHERE occurance_id = '$occurance_id' OR temp_id = '$occurance_id'";
echo $sql;
$result = $conn->query($sql);
