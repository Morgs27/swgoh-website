<?php
include '../functions/class_init.php';
include '../classes/character_class.php';
include '../classes/user_new.php';

ob_start();
include_once '../header.php';
include 'classes/paypal_class_init.php';


//Check that user got to this page using the form
if (isset($_POST["submit"])) {
	
	//recieve values user inputed for signup
	$newUsername = $_POST["newUsername"];
	$newPassword = $_POST["newPassword"];
	$pwdRepeat = $_POST["pwdrepeat"];
	$email = $_POST["email"];
	$ally_code = $_POST["ally_code"];
	
	//Validate for empty inputs
	if (empty($newUsername) || empty($newPassword) || empty($pwdRepeat) || empty($email)){
		header("location: ../signup.php?error=emptyField");
		exit();
	}
	
	$url = "http://api.swgoh.gg/player/" . $ally_code . "/";

	// Check for ally code
// 	$url = 'https://api.swgoh.help/swgoh/player/'.$ally_code.'/';
    $check_data = json_decode(@file_get_contents($url));
	if(property_exists($check_data, 'data')){
		echo "success";
	}
	else {
        header("location: ../signup.php?error=ally_code");
		exit();
	}
	
	
	// Check for username already in database
	$sql_u = "SELECT * FROM users WHERE username = '$newUsername'";
	$res_u = mysqli_query($conn, $sql_u);
	
	
	
	//Validate for non-matching passwords
	if ($newPassword !== $pwdRepeat){
		header("location: ../signup.php?error=passwordNotMatch");
		exit();
	}

	// Validate email
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL )){
		header("location: ../signup.php?error=invalidemail");
		exit();
	}
	
	//Validate for Username already taken
	else if (mysqli_num_rows($res_u) > 0) {
		header("location: ../signup.php?error=usernameTaken");
		exit();
	}
	
	//Insert User if all validation is passed
	else {
		$sql = "INSERT INTO users (Username, ally_code, Password, email) VALUES ('$newUsername', '$ally_code','$newPassword','$email')";
		
		if ($conn->query($sql) === TRUE) {
		
		header("location: ../signup.php?sucess=$newUsername");	
		} 
		else{
			header("location: ../signup.php?error=unexpected");
		}
	}	
}

else {
	header("location: ../signup.php");
	exit();
}

?>
