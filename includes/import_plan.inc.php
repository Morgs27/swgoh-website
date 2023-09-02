<?php 
include '../functions/db_connect.php';



$username = $_POST['username'];
$code = $_POST['code'];

$sql = "SELECT * FROM farming_share_link WHERE token = '$code' ";
$result = $conn->query($sql);
if (mysqli_num_rows($result) == 0){
    print_r(json_encode(array("result" => "No Code")));
}
else {
while($data = $result->fetch_assoc()){
    $plan_id = $data['plan_id'];
}

$sql = "SELECT * FROM farming_plan WHERE plan_id = '$plan_id'";
$result = $conn->query($sql);
while ($data = $result->fetch_assoc()){
    $plan_name = $data['plan_name'];
    $created = $data['created'];
    $sql_i = "INSERT INTO farming_plan (plan_name,username,created) VALUES ('$plan_name','$username','$created')";
    $result_i = $conn->query($sql_i);
    $insert_id = $conn->insert_id;
}

$sql = "SELECT * FROM stages WHERE plan_id = '$plan_id'";
$result = $conn->query($sql);
while ($data = $result->fetch_assoc()){
    $stage_id = $data['stage_id'];
    $stage_name = $data['stage_name'];
    $background = $data['background'];
    $sql_s = "INSERT INTO stages (plan_id,stage_name,background) VALUES ('$insert_id','$stage_name','$background')";
    $result_s = $conn->query($sql_s);
    $new_stage_id = $conn->insert_id;

    $sql_o = "SELECT * FROM farming_character_occurance WHERE stage_id = '$stage_id' ";
    $result_o = $conn->query($sql_o);
    while($data_o = $result_o->fetch_assoc()){
        $char_id = $data_o['char_id'];
        $gear = $data_o['gear'];
        $relic = $data_o['relic'];
        $sql_z = "INSERT INTO farming_character_occurance (stage_id,char_id,gear,relic) VALUES ('$new_stage_id','$char_id','$gear','$relic')";
        $result_z = $conn->query($sql_z);
    }
}

print_r(json_encode(array("result" => "sucess", "id" => $insert_id, "name" => $plan_name, "created" => $created)));


}