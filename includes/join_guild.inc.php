<?php
ob_start();
session_start();
include_once '../header.php';
include '../classes/paypal_class_init.php';

foreach($_POST as $variable){
    print_r($variable);
    if (is_array($variable) == false){
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $variable)){ 
            exit();
        }
    }
    else{
        foreach($variable as $part){
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $part)){ 
                exit();
            }
        }
    }
}

if (isset($_POST["submit"])) {
	
	$guild_code = $_POST["guild_code"];
	$username = $_SESSION["Username"];
	
	
	//Validate for empty inputs
	if (empty($guild_code)){
		header("location:../join_guild.php?error=emptyField");
		exit();
	}
	
	if (strlen($guild_code) < 8 ){
		header("location:../join_guild.php?error=characters");
		exit();
	}

	$sql = "SELECT * FROM guilds WHERE guild_code ='$guild_code'";
	$result = $conn->query($sql);
	

	if (mysqli_num_rows($result) > 0){
		while ($data = $result->fetch_assoc()) {
		$guild_id = $data['guild_id'];
		$sql_u = " UPDATE users SET guild_id = '$guild_id', guild_rank = 'member' WHERE users.Username = '$username' ";
		$res_u = mysqli_query($conn, $sql_u);
		
		check_pro($_SESSION['Username'],$conn);

		$notify = to_notify($_SESSION['Username'],$conn);

		if ($notify == true){
			header("location: ../myGuild.php?to_notify");
		}
		else {
		header("location: ../myGuild.php");
		}
		}
	
	}
	else{
		header("location: ../join_guild.php?error=incorrectData");
		exit();

	}
}
else {
	header ("location: ../join_guild.php");
	exit();
}

?>