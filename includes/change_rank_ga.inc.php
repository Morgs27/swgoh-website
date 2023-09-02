<?php 
include '../functions/db_connect.php';



$loadout_id = $_POST['id'];
$rank = $_POST['rank'];
$teams = json_encode($_POST['teams']);

$sql = "UPDATE ga_loadouts SET rank = '$rank',teams = '$teams' WHERE loadout_id = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);