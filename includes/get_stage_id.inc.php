<?php 
include '../functions/db_connect.php';



$temp_id = $_POST['temp_id'];

$sql = "SELECT * FROM stages WHERE temp_id = '$temp_id'";

$result = $conn->query($sql);
while ($data = $result->fetch_assoc()){
    echo $data['stage_id'];
}