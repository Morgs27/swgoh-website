<?php 
// include_once '../header.php';
include '../functions/db_connect.php';

$allycode = $_POST['allycode'];

$users = array(
    array("username" => "Guest_Info__","ally_code" => "741324657"),
    array("username" => "Guest_Info___","ally_code" => "939729166"),
    array("username" => "Guest_Info____","ally_code" => "882145491"),
    array("username" => "Guest_Info_____","ally_code" => "616485783"),
    array("username" => "Guest_Info______","ally_code" => "644744399"),
    array("username" => "Guest_Info_______","ally_code" => "479461123"),
    array("username" => "Guest_Info________","ally_code" => "771957566"),
    array("username" => "Guest_Info_________","ally_code" => "528234451")
);

foreach($users as $user){
    if ($user['ally_code'] == $allycode){
        $guest_username = $user['username'];
    }
}


$code = false;

while($code == false){
    $code = true;

    $guest_code = rand();

    $sql = "SELECT * FROM guests WHERE guest_id = '$guest_code'";
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0){
        $code = false;
    }
}

$guest_name = "Guest_" . $guest_code;

$expiry = date("Y-m-d H-i-s",strtotime("+ 1 day"));

$sql = "INSERT INTO guests (guest_id,ally_code,guest_name,guest_expiry,guest_username) VALUES ('$guest_code','$allycode','$guest_name','$expiry','$guest_username')";


$result = $conn->query($sql);


$counter = $conn->insert_id;

$guild_id_no = ceil($counter/50);

$guild_name = "Guest_Guild_2708_" . $guild_id_no;
$sql = "SELECT * FROM guilds WHERE guild_name = '$guild_name'";
$result = $conn->query($sql);

if (mysqli_num_rows($result) == 0){
    $sql_i = "INSERT INTO guilds (guild_name) VALUES ('$guild_name')";
    $result_i = $conn->query($sql_i);
    $guild_id = $conn->insert_id;
}
else {
    while ($data = $result->fetch_assoc()){
        $guild_id = $data['guild_id'];
    }
}

$sql = "UPDATE guests SET guest_guild = '$guild_id' WHERE counter = '$counter'";
$result = $conn->query($sql);


echo $guest_code;

