<?php
include '../functions/class_init.php';
include '../classes/character_class.php';
include '../classes/user_new.php';
session_start();
ob_start();
include_once '../header.php';
include '../classes/paypal_class_init.php';

if (isset($_POST["submit"])) {
	
	$enteredUsername = $_POST["enteredUsername"];
	$enteredPassword = $_POST["enteredPassword"];
	
	
	//Validate for empty inputs
	if (empty($enteredUsername) OR empty($enteredPassword)){
		header("location:../login.php?error=emptyField");
		exit();
	}

	$username = mysqli_real_escape_string($conn, $enteredUsername);

	$password = mysqli_real_escape_string($conn, $enteredPassword);

	$sql_u = "SELECT * FROM users WHERE username ='" . $username . "' AND password = '" . $password . "'";
	$res_u = mysqli_query($conn, $sql_u);

	if ((mysqli_num_rows($res_u) > 0)){
		session_start();

		$_SESSION["Username"] = $enteredUsername;

		$ally_code = get_ally_code($conn,$_SESSION["Username"]);

		$user_info = (getPlayerInfo_new($_SESSION["Username"],$conn));
		$ship_info = (getPlayerInfo_ship($_SESSION["Username"],$conn));

		$_SESSION['user_info_ship'] = $ship_info;
		$_SESSION['user_info_class'] = $user_info;

		update_team_gp($conn,$user_info,$_SESSION["Username"]);
		update_team_gp_ship($conn,$user_info,$ship_info,$_SESSION["Username"]);

		$notify = to_notify($_SESSION['Username'],$conn);
		
		unset($_SESSION['guild_data']);
		unset($_SESSION['guest']);


		if ($notify == true){
			header("location:../teams.php?to_notify");
		}
		else {
		header("location:../teams.php");
		}


	
	}
	else{
		header("location: ../login.php?error=incorrectData");
		exit();

	}
}
else {
	header ("location: ../login.php");
	exit();
}

?>


