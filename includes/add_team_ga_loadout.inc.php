<?php 
include '../functions/db_connect.php';


$loadout_id = $_POST['loadout_id'];
$team_id = $_POST['teamid'];
$territory = $_POST['territory'];

$sql = "SELECT * FROM ga_loadouts WHERE loadout_id = '$loadout_id'";
$result = $conn->query($sql);
while ($data = $result->fetch_assoc()){
    $teams = json_decode($data['teams']);

}

array_push($teams->$territory->in,$team_id);
if ($teams->$territory->in[0] == null){
    $teams->$territory->in = array($team_id);
}
$teams = json_encode($teams);

// print_r($teams);

$sql = "UPDATE ga_loadouts SET teams = '$teams' WHERE loadout_id = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);