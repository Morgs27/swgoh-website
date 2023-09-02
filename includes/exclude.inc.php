<?php
include '../functions/class_init.php';
include_once "../header.php";

$post = $_POST;

$loadout_id = json_decode($_POST['loadout']);
$exclude = $post['remove'];

$exclude = str_replace('"','',$exclude);
$exclude = str_replace("[","",$exclude);
$exclude = str_replace("]","",$exclude);

$sql_e = "UPDATE loadouts SET exclude = '$exclude' WHERE loadoutID = '$loadout_id'";
$result_e = $conn->query($sql_e);

