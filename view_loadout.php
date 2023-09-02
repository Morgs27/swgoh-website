<?php 
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';

session_start();
ob_start();

include_once "header.php";

check_logged_in();
check_get_variable("i");

if (isset($_SESSION['Guest'])){
	?>
	<script>
		window.location.href = "error.php?twe";
	</script>
	<?php
	exit();
}

$username = $_SESSION['Username'];
$rank = get_rank($conn,$_SESSION['Username']);
$territories = array("T1","T2","T3","T4","B1","B2","B3","B4","F1","F2");
$guild_id = get_guild($conn,$_SESSION['Username']);
$loadout_id = $_GET['i'];

if (filter_var($loadout_id, FILTER_VALIDATE_INT) === false){
	?>
	<script>
		window.location.href = "error.php?twe";
	</script>
	<?php
	exit();
}

check_right_guild($conn,$_SESSION['Username'],$loadout_id);

$in_guild = check_in_guild($conn,$_SESSION['Username']);


if ($in_guild == "false"){
	$usernames = array($_SESSION['Username']);
	$userStr = $_SESSION['Username'];
	$active = false;
}
else {
$guild = get_guild($conn,$_SESSION["Username"]);
$usernames = get_usernames_in_guild($guild,$conn);
$userStr = implode("', '", $usernames);

$active_loadout = get_active_loadout_id($conn,$guild_id);

if ($active_loadout == $loadout_id){
	$active = true;
}
else {
	$active = false;
}

}



$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
$result = $conn->query($sql);
check_rows($result);
while ($data = $result->fetch_assoc()){
	$loadout_name = $data['loadout_name'];
	$created_by = $data['created_by'];
	$loadout_guild_id = $data['guild_id'];
	$favorite = $data['favorite'];
}


?>

<!-- <link rel="stylesheet" type="text/css" href="styles/tw_loadout_view.css"/>
<link rel="stylesheet" type="text/css" href="styles/farming_manager.css"/> -->
<!-- <link rel="stylesheet" type="text/css" href="styles/ga_loadout.css"/> -->


<style>
  .export{
    width: 100px;
    height: 70%;
    background: red;
    border-radius: 5px;
    background: rgba(255,255,255,0.1);
    /* border: 1px solid rgba(255,255,255,0.4); */
    transition: 0.2s;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    font-family: 'Raleway','Sans-serif';
  }
  .export:hover{
    cursor: pointer;
    background: rgba(255,255,255,0.3);
  }
  .export_open_text{
    display: inline-block;
  }
  .export i{
    margin-left: 5px;
  }
  .pulse_green::after{
	  top: -10px;
	  right: -15px;
  }
  @media (max-width: 580px){
	.export {
    width: 30px;
	}
	.export_open_text{
		display: none;
	}
	.export i{
		margin-left: 0px;
	}
  }
  .team_container:hover{
	  cursor: pointer;
	  background: rgba(255,255,255,0.1);
  }
</style>

<script>
    window.username = '<?php echo $username;?>'
</script>

<div class="team_builder_bar" style = 'position:relative'>
<img src="images/tw_map_2.jpg" alt="" >
<?php 
if ($active == true){
	?>
	<div class="title pulse_green"><?php echo $loadout_name;?></div>
	<?php
}
else {
	?>
	<div class="title"><?php echo $loadout_name;?></div>
	<?php
}
?>


<?php
if (isset($_GET['nbt'])){

}
else {
	?>
	<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='edit_loadout_new.php?i=<?php echo $loadout_id;?>'>
        <i class="material-icons">arrow_back</i>
    </a>
	</div>
	<?php
}
?>



<div onclick = 'window.location.href="assignments_by_player.php?i=" + "<?php echo $loadout_id?>"' class="tw_settings export" style = 'position:absolute;right: 5px;display: flex;justify-content: center;align-items:center' >
    <div>
        <div class = 'export_open_text'>Users</div> <i style = 'transform:translate(0px);' class="fa-solid fa-user"></i>
    </div>
</div>

</div>

<div class="toggle_team loadout" onclick = 'toggle_teams()'>
Map
<div class="toggle_arrow">
<span></span>
<span></span>
<span></span>
</div>	
</div>

<div class = "tw_container_loading" style = 'display: flex;justify-content: center;align-items:center;flex-direction: column;'>
<div class="spinner_container">
    <div class="spinner_circle"></div>
    <div class="spinner_circle outer_1"></div>
    <div class="spinner_circle outer_2"></div>
    <div class="spinner_circle plannet_1"></div>
    <div class="spinner_circle plannet_2"></div>
</div>
Loading Teams...
</div>


<div class="tw_container" style = 'display:none'>



<div class="overflow_container_loadouts">
<div class="tw_loadout_team_container">


  <div class="teamInfo_container">

  <div class="tw_territory_title">
	<div class="team_title_text">T1</div>
  </div>
  <?php

	
	if (isset($_SESSION['guild_data'])){ 
		$guild_data = $_SESSION['guild_data'];
	}
	else {
		$guild_data = get_guild_unit_data($conn,$usernames);
		$_SESSION['guild_data'] = $guild_data;
	}

	$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
	$result = $conn->query($sql);
	while ($data = $result ->fetch_assoc()) {
		echo "<div class = 'teams territory' style = 'margin-top: 10px'>";
		echo "<div class = 'no_teams' style = 'display:none;text-align: center;margin-top:20px;'>There are no teams assigned to this territory.</div>";

		foreach ($territories as $territory){
			$teams = $data[$territory];
			if ($teams == ""){
				// No teams in territory
			}
			$team_parts = explode(",",$teams);
			
			if($territory == "F1" || $territory == "F2"){
				foreach ($team_parts as $team_id){
					
					$sql_t = "SELECT * FROM ship_team WHERE ship_team_id = '$team_id'";
					$result_t = $conn->query($sql_t);

					while ($data_t = $result_t->fetch_assoc()) {
					$team_id = $data_t['ship_team_id'];
					$checked = $data_t['checked'];
					$username = $data_t['Username'];
					$username = strtoupper($username);
					$username = trim($username);
					if (isset($guild_data[$username])){
					$user_characters = $guild_data[$username]['Characters'];
					$user_ships = $guild_data[$username]['Ships'];
					$checked_by = $data_t['checked_by'];
					$ships = $data_t['ships'];

					$ships = explode(",",$ships);

					array_shift($ships);
					
					if ($active == true){
					if ($checked == 'true'){
						if (strtoupper($username) == strtoupper($_SESSION['Username'])){
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory team_container_ships checked viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						else {
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory team_container_ships checked no-viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i> </div>';
				// 		echo '<div class = "checked_text">Checked By: ' . $checked_by .  '</div>';
					}
					else {
						if (strtoupper($username) == strtoupper($_SESSION['Username'])){
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory team_container_ships viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						else{
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory team_container_ships no-viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i></div>';
				// 		echo '<div class = "checked_text">Checked By: ' . $checked_by .  '</div>';
					}
					}
					else {
						echo "<div territory = '$territory' class='team_container $territory team_container_ships '>";

					}
					
					echo "<div class = 'team_background ships'><img src='images/Capture.png'></div>";
					
					echo "<div class='team_info_container' >";
					echo "<div class = 'team_info'>{$data_t['gp']}</div>";
					?>
					<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>
					</div>
					<?php
					echo "<div class = 'team_info'>{$data_t['Username']}</div>";
					echo "</div>";
				
					$current_team = array();
					echo "<div class='team_characters  team_characters_ships' style='margin-top:10px;'>";
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init == "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								echo"<div class = 'capital_ship_team'>";
								display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"team");
								echo "</div>";
							}
						}
						}
					}
					$x = 0;
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init !== "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								$x += 1;
								if ($x > 3){
									$y = "super_small";
								}
								else {
									$y = "small";
								}
								echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
								display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"small");
								echo "</div>";
							}
						}
						}
					}
					
				

					echo "</div>";	
					echo "</div>";
					}
					else {
						echo "User " . $username . " not found in guild data.";
					}
					}
				
				}
			}
			else {
			foreach ($team_parts as $team_id){
				$sql_t = "SELECT * FROM teams WHERE team_id = '$team_id'";
				$dummy_array = array("Team");
				$result_t = $conn->query($sql_t);
				while ($data_t = $result_t->fetch_assoc()) {
					$checked = $data_t['checked'];
					$username = $data_t['Username'];
					$username = strtoupper($username);
					$username = trim($username);
					$team_id = $data_t['team_id'];
					$user_info = $guild_data[$username]['Characters'];
					if ($active == true){
					if ($checked == 'true'){
						if (strtoupper($username) == strtoupper($_SESSION['Username'])){
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory checked viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						else {
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory checked no-viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i></div>';
					}
					else {
						if (strtoupper($username) == strtoupper($_SESSION['Username'])){
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						else{
							echo "<div territory = '$territory' team_id = $team_id class='team_container $territory no-viewer' onclick = 'check_loadout(this.classList,$team_id)'>";

						}
						echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i></div>';
					}
					}
					else {
						echo "<div territory = '$territory' class='team_container $territory'>";

					}
					
					echo "<div class = 'team_background chars_tw'><img src='images/battletw.PNG'></div>";

					echo "<div class='team_info_container' style = 'overflow:hidden;'>";
					echo "<div class = 'team_info'>{$data_t['team_gp']}</div>";
					?>
					<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>
					</div>
					<?php
					echo "<div class = 'team_info'>{$data_t['Username']}</div>";
					echo "</div>";
					
			
					echo "<div class='team_characters' >";
					echo"<div class = 'char_1'>";
					display_character_new($conn,get_char_info ($conn, $user_info, $data_t['LeaderID'] ),$dummy_array);
					echo"</div><div class = 'char_2'>";
					display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character2ID'] ),$dummy_array);
					echo"</div><div class = 'char_3'>";
					display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character3ID'] ),$dummy_array);
					echo"</div><div class = 'char_4'>";
					display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character4ID'] ),$dummy_array);
					echo"</div><div class = 'char_5'>";
					display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character5ID'] ),$dummy_array);
					echo "</div></div>";
						
						echo "</div>";
					}
					
				
				}
			}
			
			
			
		}
		echo "</div>";
	}
	

  ?>
</div>
</div>


<div class="map_left">
<?php if ($active == true){
?>
<div class="view_showing">

		<div class="vs_option" onclick = 'toggle_my_teams()'><i class="fa-solid fa-filter"></i> My Assignments</div>
		<div style = 'margin-left: 10px;' class="vs_option" onclick = 'check_all_assignments()'> <i class="fa-solid fa-check"></i> Check All</div>

</div>
<?php
}
?>
<div class="tw_map_container">
<div class="tw_map_circle">
<div class="tw_map">

<?php
foreach ($territories as $territory){
	if ($active == true){
		$number = section_team_number($conn,'all',$_SESSION['Username'],$territory,$loadout_id);
		$number_my = section_team_number($conn,'my',$_SESSION['Username'],$territory,$loadout_id);

		if ($number_my == ""){
			$number_my = "0/0";
		}
		if ($number == ""){
			$number = "0/0";
		}
		echo "
		<div territory_map = '$territory' onclick = 'change_territory(this.className)' class='tw_section $territory' style ='grid-area:$territory'>
			<p>$territory
				<div other_no = '$number_my' class = 'after_tw_title'>
					$number
				</div>
			</p>
		</div>";
	}
	else {
		$number = section_team_number($conn,'all',$_SESSION['Username'],$territory,$loadout_id);

		$number_my = section_team_number($conn,'my',$_SESSION['Username'],$territory,$loadout_id);

		$number = str_replace("0/","",$number);
		if ($territory == "F1" || $territory == "F2"){
			$icon = "<i class='fa-solid fa-jet-fighter'></i>";
		}
		else {
			$icon = "<i class='fa-solid fa-users'></i>";
		}
		if ($number == ""){
			$number = "0";
		}
		echo "
		<div  territory_map = '$territory' onclick = 'change_territory(this.className)' class='tw_section $territory' style ='grid-area:$territory'>
			<p>$territory
				<div class = 'after_tw_title view'>
					$icon $number
				</div>
			</p>
		</div>";
	}
}
?>
</div>
</div>
</div>
</div>

</div>
</div>


<script src = "script/loadout_view.js"></script>


