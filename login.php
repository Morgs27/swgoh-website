<?php
	ob_start();
	session_start();

	include_once 'header.php';
	
	check_not_logged_in();
?>

<div class="team_builder_bar">
    <img src="images/tw_map_2.jpg" alt="" >

    <div class = 'anti-small-title'>Log In</div>
    

</div>

<div class="signup_error">
<?php

if(isset($_GET["error"])){
	if($_GET["error"] == "emptyField"){
		echo "* Fill in all fields *" ;
	}	
}
if(isset($_GET["error"])){
	if($_GET["error"] == "incorrectData"){
		echo "* Incorrect login information *" ;
	}	
}
 

?>
</div>

<div class = "signup-form" style = 'margin-top: 10px;padding: 10px;'>

<form action="includes/login.inc.php" method = "post" >
    
    <input onkeypress="return /[0-9a-zA-Z\s]/i.test(event.key)" type="text" placeholder="Enter Username" name="enteredUsername" >
	<br>
    <input type="password" placeholder="Enter Password" name="enteredPassword" >
	<br>
	<button id = 'submit' type="submit"  name="submit" class="signupbtn" style = 'display: none'></button>
	<label for = 'submit' style = 'margin-top: 40px;' class="sign_up_button"><i class="fa-solid fa-arrow-right"></i></label>

</form>

<?php
if (isset($_GET['newpwd'])){
	if ($_GET['newpwd'] == "passwordupdated"){
		echo "Password Sucesfully Reset!";
	}
}
?>
<div class="seperator"></div>
<a style = 'color: rgba(255,255,255,0.7);' href = "reset_password.php">Forgot your password?</a>



</div>