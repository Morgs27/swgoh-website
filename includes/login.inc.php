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

	// Look the user up with a prepared statement, then check the password hash.
	$stmt = mysqli_prepare($conn, "SELECT Username, Password AS pwd FROM users WHERE Username = ?");
	mysqli_stmt_bind_param($stmt, "s", $enteredUsername);
	mysqli_stmt_execute($stmt);
	$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

	$loginOk = false;
	if ($row) {
		if (password_verify($enteredPassword, $row['pwd'])) {
			$loginOk = true;
			if (password_needs_rehash($row['pwd'], PASSWORD_DEFAULT)) {
				$upgradeHash = true;
			}
		} elseif (hash_equals((string)$row['pwd'], (string)$enteredPassword)) {
			// Legacy account with a plain-text password: accept once, then store a hash.
			$loginOk = true;
			$upgradeHash = true;
		}
		if ($loginOk && !empty($upgradeHash)) {
			$newHash = password_hash($enteredPassword, PASSWORD_DEFAULT);
			$up = mysqli_prepare($conn, "UPDATE users SET Password = ? WHERE Username = ?");
			mysqli_stmt_bind_param($up, "ss", $newHash, $row['Username']);
			mysqli_stmt_execute($up);
		}
		$enteredUsername = $row['Username'];
	}

	if ($loginOk){
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


