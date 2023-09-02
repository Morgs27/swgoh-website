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

$new_teams = $teams;

// print_r($new_teams) ;

$new_territory = $teams->$territory->in;


$index = array_search($team_id,$new_territory);
array_splice($new_territory,$index,1);

if (count($new_territory) == 0){
    $new_territory = array(NULL);
}

$new_teams->$territory->in = $new_territory;

$teams = json_encode($new_teams);

// print_r($new_teams) ;

$sql = "UPDATE ga_loadouts SET teams = '$teams' WHERE loadout_id = '$loadout_id'";
echo $sql;
$result = $conn->query($sql);