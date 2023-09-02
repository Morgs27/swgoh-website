<?php 
include '../functions/db_connect.php';



$stage_id = $_POST['id'];
$char_ids = $_POST['to_add'];
$temp_ids = $_POST['temps'];

echo $stage_id;

$x = 0;
foreach($char_ids as $char_id){
    $temp_id = $temp_ids[$x];
    $sql = "INSERT INTO farming_character_occurance
    (stage_id,char_id,rating,gear,relic,complete,temp_id) VALUES
    ('$stage_id','$char_id',0,1,0,'false','$temp_id')";
    echo $sql;
    $result = $conn->query($sql);
    $x += 1;
}
