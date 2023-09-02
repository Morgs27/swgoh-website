<?php
ob_start();
session_start();



include_once '../header.php';

//Check that user got to this page using the form
if (isset($_POST["submit"])) {
	
	//recieve values user inputed for signup
	$guild_name = $_POST["guild_name"];
	$username = $_SESSION["Username"];
	
	$create_code = "False";
	while($create_code == "False"){
	
		$guild_code = rand(10000000,99999999);
	
		$sql_u = "SELECT * FROM guilds WHERE guild_code = '$guild_code'";
		$res_u = mysqli_query($conn, $sql_u);
		if (mysqli_num_rows($res_u) > 0) {
			$create_code = "False";
			echo "Hello";
		}
		else {
			$create_code = "True";
			echo $guild_code;
		}
	}

	//Validate for empty inputs
	if (empty($guild_name)){
		header("location: ../create_guild.php?error=emptyField");
		exit();
	}
	else if (strlen($guild_name) < 3 ){
		header("location: ../join_guild.php?error=characters");
		exit();
	}
	
	//create guild if validation passed
	else {
		$sql = "INSERT INTO guilds (guild_name,guild_code) VALUES ('$guild_name','$guild_code')";
		
		if ($conn->query($sql) === TRUE) {
			$sql_i = "SELECT guild_id FROM guilds WHERE guild_code = '$guild_code'";
			$result_i = mysqli_query($conn, $sql_i);
			while ($data = $result_i->fetch_assoc()) {
				$guild_id = $data['guild_id'];
			}
			$sql_j = "UPDATE users SET guild_id = '$guild_id', guild_rank = 'leader' WHERE users.Username = '$username'";
			mysqli_query($conn, $sql_j);
			header("location: ../guild_confirmation.php");	
		} 
		else{
			
		}
	}	
}

else {
	header("location: ../signup.php");
	exit();
}

?>