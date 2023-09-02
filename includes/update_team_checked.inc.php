<?php 
include '../functions/db_connect.php';

$team_id = $_POST['team_id'];
$checked = $_POST['checked'];
$type  = $_POST['type'];
$username = $_POST['username'];

if ($type == "characters"){
    $sql = "UPDATE teams SET checked = '$checked',checked_by = '$username' WHERE team_id = $team_id";
    $result = $conn->query($sql);
    echo $sql;
}
else {
    $sql = "UPDATE ship_team SET checked = '$checked',checked_by = '$username' WHERE ship_team_id = $team_id";
    $result = $conn->query($sql);
    echo $sql;
}
