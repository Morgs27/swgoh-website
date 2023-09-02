<?php 
include '../functions/db_connect.php';


$plan_id = $_POST['id'];
$name = $_POST['name'];
$type = $_POST['type'];

if ($type == 'new'){
    $sql = "UPDATE stages SET stage_name = '$name' WHERE temp_id = '$plan_id' OR stage_id = '$plan_id'";
}
else {
    $sql = "UPDATE stages SET stage_name = '$name' WHERE stage_id = '$plan_id' OR temp_id = '$plan_id'";
}
echo $sql;
$result = $conn->query($sql);