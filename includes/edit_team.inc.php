<?php

include '../header.php';



$team = $_POST['team'];
$gp = $_POST['gp'];
$type = $_POST['type'];
$combat = $_POST['combat'];
$username = $_POST['username'];
$team_id = $_POST['team_id'];


$current_team = $team;


if ($combat == "characters"){

    $team_classes = array();
    $team_classes = get_classes (($current_team[0]),$conn,$team_classes);
    $team_classes = get_classes (($current_team[1]),$conn,$team_classes);
    $team_classes = get_classes (($current_team[2]),$conn,$team_classes);
    $team_classes = get_classes (($current_team[3]),$conn,$team_classes);
    $team_classes = get_classes (($current_team[4]),$conn,$team_classes);
    $team_classes_string = implode(",",$team_classes);
    $team_names = array();
    $team_names = get_names (($current_team[0]),$conn,$team_names);
    $team_names = get_names (($current_team[1]),$conn,$team_names);
    $team_names = get_names (($current_team[2]),$conn,$team_names);
    $team_names = get_names (($current_team[3]),$conn,$team_names);
    $team_names = get_names (($current_team[4]),$conn,$team_names);
    $team_names_string = implode(",",$team_names);

    $sql = "UPDATE teams SET 
    LeaderID = '$current_team[0]',
    Character2ID = '$current_team[1]',
    Character3ID = '$current_team[2]',
    Character4ID = '$current_team[3]',
    Character5ID = '$current_team[4]',
    factions_contained = '$team_classes_string',
    characters_contained = '$team_names_string',
    team_gp = '$gp'
    WHERE team_id = '$team_id'";

    $result = $conn->query($sql);

}
else {
    $capital = $_POST['capital'];

    $test = "test";

    array_unshift($current_team,$test,$capital);

    $ships = implode("," ,$current_team);
	
    // $sql = "INSERT INTO ship_team (Username,ships,gp,type) VALUES ('$username','$ships','$gp','$type')";

    $sql = "UPDATE ship_team SET ships = '$ships',gp = '$gp' WHERE ship_team_id = '$team_id'";
    $result = $conn->query($sql);
}
