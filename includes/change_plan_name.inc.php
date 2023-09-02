<?php 
include '../functions/db_connect.php';



$loadout_id = $_POST['id'];
$name = $_POST['name'];

$sql = "UPDATE farming_plan SET plan_name = '$name' WHERE plan_id = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);