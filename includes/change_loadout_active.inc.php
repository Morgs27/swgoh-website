<?php 
include '../functions/db_connect.php';

$loadout_id = $_POST['id'];
$active = $_POST['active'];

$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
$result = $conn->query($sql);

while ($data = $result ->fetch_assoc()) {
    $teams_char = $data['T1'] . "," . $data['T2'] . "," . $data['T3'] . "," . $data['T4'] . "," . $data['B1'] . "," . $data['B2'] . "," . $data['B3'] . "," . $data['B4'];	
    $teams_ship = $data['F1'] . "," . $data['F2'];

}

$team_chars = explode(",", $teams_char);
$team_ships = explode(",", $teams_ship);

foreach ($team_chars as $char_id){
    $sql_c = "UPDATE teams SET checked = 'false' WHERE team_id = '$char_id'";
    $result = $conn->query($sql_c);
}
foreach ($team_ships as $ship_id){
    $sql_s = "UPDATE ship_team SET checked = 'false' WHERE ship_team_id = '$ship_id'";
    $result = $conn->query($sql_s);
}




$sql = "UPDATE loadouts SET active = '$active' WHERE loadoutID = '$loadout_id'";
$result = $conn->query($sql);
echo $sql;


