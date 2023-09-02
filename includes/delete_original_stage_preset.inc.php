<?php 
include '../functions/db_connect.php';



$stage_id = $_POST['stage_id'];
$sql_d = "DELETE FROM farming_character_occurance WHERE stage_id = '$stage_id'";
$result_d = $conn->query($sql_d);