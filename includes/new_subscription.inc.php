<?php
include 'class_init.php';
session_start();
ob_start();
include_once 'header.php';
include_once 'upload_to_database.php';
include_once 'paypal_class_init.php';



$subscription_id = $_POST['id'];
$plan = $_POST['plan'];
$u_name = $_POST['username'];
$guild = $_POST['guild'];

echo ($subscription_id . "   ". $plan);

$pal = new pay_pal('1','2');

$data = $pal->get_subscription_info($subscription_id);

$data = json_decode($data);

$status = $data->status;
$status_update_time = $data->status_update_time;
$start_time = $data->start_time;
$outstanding_balance = $data->billing_info->outstanding_balance->value;
$cycles_completed = $data->billing_info->cycle_executions[0]->cycles_completed;
$last_payment = json_encode($data->billing_info->last_payment);

$sql = "INSERT INTO paypal_subscriptions (subscription_id,Username, plan_id,status,status_update_time,start_time,outstanding_balance,cycles_completed,last_payment,guild) 
VALUES ('$subscription_id', '$u_name', '$plan','$status','$status_update_time','$start_time','$outstanding_balance','$cycles_completed','$last_payment','$guild')";
$result = $conn->query($sql);


$sql = "UPDATE users SET pro = 'true',to_notify = 'true' WHERE Username = '$u_name'";
$result = $conn->query($sql);

if ($guild == "true"){
    $guild_id = get_guild($conn,$u_name);
    $usernames = get_usernames_in_guild($guild_id,$conn);
    foreach($usernames as $user_name){
        if ($user_name != $u_name){
            $sql = "UPDATE users SET pro = 'true',payed_by = '$u_name',to_notify = 'true' WHERE Username = '$user_name' AND pro != 'true'";
            $result = $conn->query($sql);
        }
    }
}
