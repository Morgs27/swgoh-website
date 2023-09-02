<?php 
include '../functions/db_connect.php';



$stage_id = $_POST['id'];

$sql = "DELETE from stages WHERE stage_id = '$stage_id'";
$result = $conn->query($sql);