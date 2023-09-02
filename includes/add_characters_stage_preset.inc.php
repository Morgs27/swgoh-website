<?php 
include '../functions/db_connect.php';


$stage_id = $_POST['stage_id'];
$char_id = $_POST['base_id'];
$gear = $_POST['gear'];
$relic = $_POST['relic'];
$temp_id = $_POST['temp_id'];


$sql = "INSERT INTO farming_character_occurance
(stage_id,char_id,rating,gear,relic,complete,temp_id) VALUES
('$stage_id','$char_id',0,$gear,$relic,'false','$temp_id')";
echo $sql;
$result = $conn->query($sql);



