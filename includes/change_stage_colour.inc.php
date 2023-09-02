<?php 
include '../functions/db_connect.php';


$stage_id = $_POST['id'];
$colour = $_POST['colour'];

$sql = "UPDATE stages SET background = '$colour' WHERE stage_id = '$stage_id'";
echo $sql;
$result = $conn->query($sql);