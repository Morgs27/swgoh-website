<?php

include '../header.php';



$team = $_POST['team'];
$gp = $_POST['gp'];
$type = $_POST['type'];
$combat = $_POST['combat'];
$username = $_POST['username'];



$current_team = $team;

if (isset($_POST['guest'])){
    $guest_id = $_POST['guest'];
}
else {
    $guest_id = 0;
}

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

    $sql = "INSERT INTO teams (team_id, guest_id, Username, LeaderID, Character2ID, Character3ID, Character4ID, Character5ID,team_gp,factions_contained,characters_contained,type) 
    VALUES (NULL, '$guest_id' , '$username','$current_team[0]','$current_team[1]','$current_team[2]','$current_team[3]','$current_team[4]','$gp','$team_classes_string','$team_names_string','$type')  ";
    $result = $conn->query($sql);
    echo $sql;

}
else {
    $capital = $_POST['capital'];

    $test = "test";

    array_unshift($current_team,$test,$capital);

    $ships = implode("," ,$current_team);
	
    $sql = "INSERT INTO ship_team (guest_id, Username,ships,gp,type) VALUES ('$guest_id','$username','$ships','$gp','$type')";

    $result = $conn->query($sql);
}
