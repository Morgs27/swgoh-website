<?php 
include '../functions/db_connect.php';



$temp_id = $_GET['temp_id'];
$base_id = $_GET['base_id'];

$sql = "SELECT * FROM farming_character_occurance WHERE temp_id = '$temp_id'";

$result = $conn->query($sql);


while ($data = $result->fetch_assoc()){
    echo $data['occurance_id'];
}