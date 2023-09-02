<?php 
include '../functions/db_connect.php';



$plan_id = $_POST['id'];
$type = $_POST['type'];

if ($type == 'remove'){
    $sql = "UPDATE stages SET complete = 'false' WHERE temp_id = '$plan_id' OR stage_id = '$plan_id'";
}
else {
    $sql = "UPDATE stages SET complete = 'true' WHERE temp_id = '$plan_id' OR stage_id = '$plan_id'";
}
echo $sql;
$result = $conn->query($sql);