<?php 
include '../functions/db_connect.php';


$username = $_POST['username'];

$sql = "SELECT * FROM user_stat_instance WHERE Username = '$username'";
$result = $conn->query($sql);

$dates = array();
$dates_str = array();
$gps = array();
$ship_gps = array();
$char_gps = array();
$skill_ratings = array();
$arena_ranks = array();
$ship_arena_ranks = array();

$omicrons = array();
$zetas = array();
$reliced = array();

while($data = $result->fetch_assoc()){
    $date = $data['date'];
    $date_str = date("jS F",strtotime($date));
    $gp = $data['gp'];
    $char_gp = $data['char_gp'];
    $ship_gp = $data['ship_gp'];
    $skill_rating = $data['skill_rating'];
    $arena_rank = $data['arena_rank'];
    $ship_arena_rank = $data['ship_arena_rank'];

    $omicron = $data['omicrons'];
    $zeta = $data['zetas'];
    $reliced_instance = $data['reliced'];

    array_push($dates,$date);
    array_push($gps,$gp);
    array_push($ship_gps,$ship_gp);
    array_push($char_gps,$char_gp);
    array_push($skill_ratings,$skill_rating);
    array_push($arena_ranks,$arena_rank);
    array_push($ship_arena_ranks,$ship_arena_rank);

    array_push($omicrons,$omicron);
    array_push($zetas,$zeta);
    array_push($reliced,$reliced_instance);
}

if (count($dates) > 10){
    $number = ceil(count($dates)/10);
    $length = count($dates);
    for ($x = 0; $x < $length; $x++){
        if ($x % $number != 0){
            unset($dates[$x]);
            unset($gps[$x]);
            unset($ship_gps[$x]);
            unset($char_gps[$x]);
            unset($skill_ratings[$x]);
            unset($arena_ranks[$x]);
            unset($ship_arena_ranks[$x]);

            unset($omicrons[$x]);
            unset($zetas[$x]);
            unset($reliced[$x]);
        }
    }
}

$dates = array_values($dates);
$gps = array_values($gps);
$ship_gps = array_values($ship_gps);
$char_gps = array_values($char_gps);
$skill_ratings = array_values($skill_ratings);
$arena_ranks = array_values($arena_ranks);
$ship_arena_ranks = array_values($ship_arena_ranks);

$omicrons = array_values($omicrons);
$zetas = array_values($zetas);
$reliced = array_values($reliced);

print_r(json_encode(array($dates,$gps,$ship_gps,$char_gps,$skill_ratings,$arena_ranks,$ship_arena_ranks,$omicrons,$zetas,$reliced)));

