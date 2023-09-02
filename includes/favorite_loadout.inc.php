<?php 
include '../functions/db_connect.php';


$loadout_id = $_POST['id'];

$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
$result = $conn->query($sql);
while($data = $result->fetch_assoc()){
    $favorite = $data['favorite'];
}
if ($favorite == "true"){
    $sql = "UPDATE loadouts SET favorite = '' WHERE loadoutID = '$loadout_id'";

}
else {
    $sql = "UPDATE loadouts SET favorite = 'true' WHERE loadoutID = '$loadout_id'";

}
$result = $conn->query($sql);