<?php 
include '../functions/db_connect.php';



$plan_id = $_POST['id'];

$sql = "DELETE from farming_share_link WHERE plan_id = '$plan_id'";
$result = $conn->query($sql);

$sql = "DELETE from farming_plan WHERE plan_id = '$plan_id'";
$result = $conn->query($sql);