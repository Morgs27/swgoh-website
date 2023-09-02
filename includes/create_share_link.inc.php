<?php 
include '../functions/db_connect.php';



$plan_id = $_POST['id'];

// Create Link Instance 
$token = openssl_random_pseudo_bytes(16);
$token = bin2hex($token);
$sql = "INSERT INTO farming_share_link (plan_id,token) VALUES ('$plan_id','$token')";
$result = $conn->query($sql);

echo $token;