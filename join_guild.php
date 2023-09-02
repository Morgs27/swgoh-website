<?php
ob_start();
session_start();
include_once 'header.php';

check_not_logged_guild($conn);
?>

<link rel="stylesheet" type="text/css" href="styles/join_guild.css"/>

<div class="team_builder_bar" style = 'position:relative'>

<img src="images/tw_map_2.jpg" alt="" >
<div class="title">My Guild</div>
                                                 
</div>

<div class="content">
<div class="content_container">

<div class="starter_content">

<div class="area_container">

<div class="title_container">
	<div class="title1">Enter Guild Code</div>
	<div class="title2">Enter a code to join your guild. </div>
</div>






<form action="includes/join_guild.inc.php"  method="post">
    
    <input maxLength = "8" id = "input_code" type="text"  name="guild_code" >
	
	<script>
		let input = document.getElementById("input_code");
		input.focus();

		setTimeout(() => {
			let error = document.querySelector(".error_container");
			error.style.display = "none";
		}, 5000);
	</script>

	<input style = 'display:none' id = 'submit' name = 'submit' type="submit" >
	<label class = 'input_arrow' for="submit">
		<i class="fa-solid fa-arrow-right"></i>
	</label>
</form>

<div class="error">
<?php
	if(isset($_GET["error"])){
		if($_GET["error"] == "emptyField"){
		echo "<div class = 'team_error'>Code Not Entered!</div>" ;
		}	
	}
	if(isset($_GET["error"])){
		if($_GET["error"] == "incorrectData"){
			echo "<div class = 'team_error'>Code not Valid!</div>" ;
		}	
	}
	if(isset($_GET["error"])){
		if($_GET["error"] == "characters"){
			echo "<div class = 'team_error'>Code must be 8 characters long!</div>" ;
		}	
	}
?>
</div>

</div>

<div class="seperator" style = 'margin-top: 25px'></div>
<div class="seperator"  style = 'margin-bottom: 25px'></div>

<div class="area_container">

<div class="title_container">
	<div class="title1">Sign Up Your Guild</div>
	<div class="title2">Enable your fellow guildmates to join you using a code.</div>
</div>

<div class="sign_up_button" onclick = 'sign_up_form()'>
<i class="fa-solid fa-arrow-right"></i>
</div>

<script>
	function sign_up_form(){
		content = document.querySelector(".signup_guild_content");
		content.classList.toggle("active");
		starter = document.querySelector(".starter_content");
		starter.classList.toggle("active");
	}
</script>

</div>
</div>
<div class="signup_guild_content">
	<div class="title1" style = 'margin-top: 50px;'>
		Sign Up Your Guild
	</div>
	<div class="title2">Enter the name of your guild.</div>
	<form action="includes/create_guild.inc.php"  method="post">
		
		<input id = 'input_name' maxLength = "50" type="text"  name="guild_name" >


		<input style = 'display:none' id = 'submit_name' name = 'submit' type="submit" >
		<label class = 'input_arrow' for="submit_name">
			<i class="fa-solid fa-arrow-right"></i>
		</label>

	</form>

	<div class="error">
	<?php
		if(isset($_GET["error"])){
			if($_GET["error"] == "emptyField"){
				echo "<p>Fill in all fields! </p>" ;
			}
			else if($_GET["error"] == "passwordNotMatch"){
				echo "<p>Passwords do not match!</p>" ;
			}
			else if($_GET["error"] == "usernameTaken"){
				echo "<p>Username is already taken!</p>" ;
			}
			else if($_GET["error"] == "none"){
				echo "<p>Thank you for signing up!</p>" ;
				exit();
			}
			
		}
	?>
	</div>

	<div class="sign_up_button" onclick = 'sign_up_form()' style = 'position: fixed;bottom: 20px;left:calc(50% - 100px);'>
	<i class="fa-solid fa-arrow-left"></i>
	</div>
</div>

</div>
</div>