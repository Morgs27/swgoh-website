<?php
ob_start();
session_start();
include_once 'header.php';
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';

check_not_logged_in();

?>


<div class="team_builder_bar">
    <img src="images/tw_map_2.jpg" alt="" >

    <div  class = 'anti-small-title'>Sign Up</div>
    

</div>


<?php
if (isset($_GET['done'])){
	$user_info = (getPlayerInfo_new($username,$conn));
	$ship_info = (getPlayerInfo_ship($username,$conn));

	$_SESSION['user_info_ship'] = $ship_info;
	$_SESSION['user_info_class'] = $user_info;

	$username = $_SESSION['Username'];
	header("Location: profile.php?$username");
}

else if (isset($_GET['sucess'])){
	?>
	<div class="spinner_signup">
	<div class="spinner_container" >
		<div class="spinner_circle"></div>
		<div class="spinner_circle outer_1"></div>
		<div class="spinner_circle outer_2"></div>
		<div class="spinner_circle plannet_1"></div>
		<div class="spinner_circle plannet_2"></div>
	</div>
	<div class = 'load_link_text'>Setting up your account...</div>
	</div>
	<?php
	$username = $_GET['sucess'];

    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username))
        {
            ?>
            <script>
                window.location.href = "error.php?special"
            </script>
            <?php 
            exit();       
        }

	$_SESSION['Username'] = $username;

	$ally_code = get_ally_code($conn,$username);
	?>
	<script>
        
		username = '<?php echo $username?>';
		allycode = '<?php echo $ally_code?>';
		
        $.ajax({
            url: "includes/refresh_user_data.inc.php",
            method: "POST",    
            data: {username: username, ally_code: allycode},
            success: function(data){
                window.location.href = "signup.php?done";
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });

	</script>
	<?php

}
else {
?>

<div class="signup_error" style = 'transform: translateY(8px)'>
<?php
	if(isset($_GET["error"])){
		if($_GET["error"] == "emptyField"){
			echo "<p>* Fill in all fields *</p>" ;
		}
		else if($_GET["error"] == "passwordNotMatch"){
			echo "<p>* Passwords do not match *</p>" ;
		}
		else if($_GET["error"] == "usernameTaken"){
			echo "<p>* Username is already taken *</p>" ;
		}
		else if($_GET["error"] == "ally_code"){
			echo "<p>* Could not find ally code *</p>" ;
		}
		else if($_GET["error"] == "invalidemail"){
			echo "<p>* Invalid Email *</p>" ;
		}
	}
?>

</div>

<div class = "signup-form" style = 'margin-top: 0px;padding-top: 0px;'>

<form action="includes/signup.inc.php"  method="post">
    
    <input onkeypress="return /[0-9a-zA-Z\s]/i.test(event.key)"  type="text" autocomplete = 'off' placeholder="Enter In-game Username" name="newUsername" >

    <input type="password" autocomplete = 'off' placeholder="Enter Password" name="newPassword" >
	
    <input type="password" autocomplete = 'off' placeholder="Re-enter Password" name="pwdrepeat" >

	<input type="text" autocomplete = 'off' placeholder="Enter e-mail Address" name="email" >
	
	<input onkeypress="return /[0-9a-zA-Z]/i.test(event.key)"  type="text" autocomplete = 'off' maxlength = '9'  placeholder="Enter Ally Code" name="ally_code" >

    <button id = 'submit' type="submit"  name="submit" class="signupbtn" style = 'display: none'></button>
	<label for = 'submit' style = 'margin-top: 40px;' class="sign_up_button"><i class="fa-solid fa-arrow-right"></i></label>

</form>


</div>
<?php
}
?>
