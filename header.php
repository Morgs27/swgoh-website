
<!--Link to styl sheet-->
<link rel="stylesheet" type="text/css" href="styles/main_style.css"/>
<link rel="stylesheet" type="text/css" href="styles/home.css"/>
<head>

<!--Link to google icons-->
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<title>SWGOH Team Manager</title>

<link rel="shortcut icon" href="https://swgohteammanager.com/images/favicon.ico"/>
<!-- <link rel="png" href="images/favicon.png"> -->

<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
<meta charset="utf-8"/>
<meta name="description" content="Create Teams, Join Your Guild and create TW loadout plans."/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200&display=swap" rel="stylesheet">
<meta name='viewport' content='width=device-width, initial-scale=1'>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</head>

<!--Jquery Setup-->
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous">
</script>

<!-- Graph.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>

<script src='https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js'></script>

<script src = 'https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js'></script>


<!-- Font -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src = "script/script.js" defer></script>

<script src = "https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<!--Link to database-->
<?php
include "functions/db_connect.php";
include 'functions/functions.php';
include 'functions/pro_functions.php';
include 'includes/checks.inc.php';
?>


<!--Navigation Bar Setup-->

<?php


if(isset($_SESSION["Username"])){
	if(is_pro($_SESSION['Username'],$conn) == "true" || isset($_SESSION['guest'])){
	echo "<div style = 'border-bottom: 2px solid gold' class = 'topnav'>";
	}
	else {
		echo "<div class = 'topnav'>";
	}
}
else {
	echo "<div class = 'topnav'>";
}
?>

	<?php if(isset($_SESSION["Username"])){  
		?>
		<div class = "home"><a href = "index.php"><img src='images/logo.png'></a></div>
		<div class = "toggle">
			<span></span>
			<span></span>
			<span></span>
		</div>
		
		<div class="toggle_contents">
		<?php
			$guild = check_in_guild($conn,$_SESSION["Username"]);

			// if(!isset($_SESSION['guest'])){
			// 	if(is_pro($_SESSION['Username'],$conn) == "true"){
			// 	echo "<a href='pro.php' ><div class='nav_container' id='navbar_pro'>Pro Version</div></a>";
			// 	}
			// 	else {
			// 	echo "<a href='pro.php' style = 'color: gold'><div class='nav_container' id='navbar_pro'>Pro Version</div></a>";
			// 	}
			// }

			$username = $_SESSION['Username'];
            echo "<a href='teams.php' onclick='loader()' ><div class='nav_container' id='navbar_teams'>Team Manager<span></span></div></a>";

			echo "<a href='profile.php?$username' onclick='loader()' ><div class='nav_container' id='navbar_profile'>My Profile<span></span></div></a>";

			echo "<a href='recruitment.php' onclick='loader()' ><div class='nav_container' id='navbar_recruitment'>Recruitment Hub<span></span></div></a>";

			if ($_SESSION["Username"] == "Morgs27"){
			echo "<a class='custom-underline' href='admin.php'><div class='nav_container' id='navbar_admin'>Admin<span></span></div></a>";
			}

			if($guild == "true" || isset($_SESSION['guest'])){

				$guild_id = get_guild($conn,$_SESSION['Username']);
				$active_loadout = get_active_loadout_id($conn,$guild_id);

				if ($active_loadout !== 'none'){
					echo "<a href='view_loadout.php?i=$active_loadout&nbt' ><div class='nav_container pulse_green' id='navbar_active_loadout'>Active Loadout<span></span></div></a>";
				}
				else {
					echo "<a style = 'display:none' href='view_loadout.php?i=$active_loadout&nbt' ><div class='nav_container pulse_green' id='navbar_active_loadout'>Active Loadout<span></span></div></a>";

				}

				// echo "<a href='tw_loadout.php' ><div class='nav_container' id='navbar_tw_loadout'>TW Loadout<span></span></div></a>";

				echo "<a href='myGuild.php' ><div class='nav_container' id='navbar_myGuild'>My Guild<span></span></div></a>";


			}
			else{
				
				echo "<a href='join_guild.php' ><div class='nav_container ' id='navbar_join_guild'>Join Guild<span></span></div></a>";
			
			}

			// echo "<a href='scouting.php' onclick='loader()' ><div class='nav_container' id='navbar_scouting'>Scouting<span></span></div></a>";
			
			if (isset($_SESSION['guest'])){
				$guest_name = $_SESSION['Username'];

				$users = array(
					"Guest_Info__" => "images/ahnald.jpg",
					"Guest_Info___" => "images/bit_dynasty.jpg",
					"Guest_Info____" => "images/cubsfanhan.png",
					"Guest_Info_____" => "images/ap_gains.png",
					"Guest_Info______" => "images/hynsey.png",
					"Guest_Info_______" => "images/ian.png",
					"Guest_Info________" => "images/gridan.jpg",
					"Guest_Info_________" => "images/xaereth.png"
				);

				$img_src = $users[$guest_name];

				echo "<a href='includes/logout.inc.php' ><div class='nav_container' id='navbar_logout'>Exit Guest<span></span></div></a>";
				echo "<a class = 'a_guest'><img class = 'guest_profile_img' src = '$img_src'></a>";

			}
			else{
			echo "<a href='includes/logout.inc.php' ><div class='nav_container' id='navbar_logout'>Log Out<span></span></div></a>";
			}
		echo "</div>";
		}

		else {  
		?>

		<div class = "home"><a href = "index.php"><img src='images/logo.png'></a></div>

		<div class = "toggle">
			<span></span>
			<span></span>
			<span></span>
		</div>

		<div class="toggle_contents">



		<a href='use_as_guest.php' ><div class="nav_container"   id='navbar_use_as_guest'>Guest Mode</div></a>

		<?php
		echo "<a href='recruitment.php' onclick='loader()' ><div class='nav_container' id='navbar_recruitment'>Recruitment Hub<span></span></div></a>";
		?>

		<a href='signup.php' ><div class="nav_container"   id='navbar_signup'>Sign Up</div></a>


		<a href='login.php'><div class="nav_container "   id='navbar_login'>Log In</div></a>

		</div>

		<?php
		}
	    
		?>
	
	

</div>

<?php
if (!isset($_SESSION['Username'])){
    $in = "false";
    
} 
else {
    $in = "true";
    
}

$_SESSION['LAST_ACTIVITY'] = time();


?>
<script>
// Use This for Disable Console Logs
// console.log = function () {};

let vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty('--vh', `${vh}px`);

window.addEventListener('resize', () => {
  // We execute the same script as before
  let vh = window.innerHeight * 0.01;
  document.documentElement.style.setProperty('--vh', `${vh}px`);
});

// function yourFunction(){
//     $.ajax({
// 		url: "includes/check_activity.php",
// 		method: "POST",   
// 		data: {},
// 		success: function(data){
// 			console.log(data);
// 			if (data == "false"){
// 				window.location.href = "index.php";
// 			}
// 		},
// 		error: function(errMsg) {
// 			alert(JSON.stringify(errMsg));
// 		}
// 	});

//     setTimeout(yourFunction, 10000);
// }

// yourFunction();

// document.addEventListener("click", () =>{
// 	console.log("click");
// 	$.ajax({
// 		url: "includes/update_activity.php",
// 		method: "POST",   
// 		data: {},
// 		success: function(data){
// 			console.log(data);
			
// 		},
// 		error: function(errMsg) {
// 			alert(JSON.stringify(errMsg));
// 		}
// 	});
// })
    
function timeout_function(){
    window.location = 'index.php';
}

$(document).ready(function() {
	var sub_route = (window.location.pathname.split("/")[1]);
// 	var main_route = sub_route.split(".")[0];
console.log(sub_route);
	$('#navbar_' + sub_route).addClass('active_header');
});  

function updateDiv(){ 
    $('#here').load(document.URL +  ' #here');
}


let menutoggle = document.querySelector('.toggle');
let contents = document.querySelector('.toggle_contents');
menutoggle.onclick = function(){
	menutoggle.classList.toggle('active');
	contents.classList.toggle('show');
	
}

		
</script>

