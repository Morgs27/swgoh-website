<?php 
include '../functions/db_connect.php';

$username = $_POST['username'];

$sql = "UPDATE teams SET checked = 'true', checked_by = '$username' WHERE Username = '$username'";
$result = $conn->query($sql);

$sql = "UPDATE ship_team SET checked = 'true', checked_by = '$username' WHERE Username = '$username'";
$result = $conn->query($sql);


