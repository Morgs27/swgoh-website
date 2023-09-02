<?php 
include '../functions/db_connect.php';



$plan_id = $_POST['id'];
$temp_id = $_POST['temp_id'];

$sql = "INSERT INTO stages (plan_id,stage_name,stage_description,temp_id) VALUES ('$plan_id','New Stage','New Stage Description','$temp_id')";
echo $sql;
$result = $conn->query($sql);