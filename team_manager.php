<?php
    ob_start();

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    include 'functions/class_init.php';
    include 'classes/character_class.php';
    include 'classes/user_new.php';

    session_start();

    include_once 'header.php';

	check_logged_in();

    $username = $_SESSION["Username"];
	$rank = get_rank($conn,$username);
	$guild_id = get_guild($conn,$username);

    $user_characters = $_SESSION['user_info_class'];
    $user_ships = $_SESSION['user_info_ship'];


    if (isset($_GET['tw'])){
        $text = "Territory War";
        $img = "images/tw_map_2.jpg";
        $style = "";
        $link = "tw";
    }
    else if (isset($_GET['ga'])){
        $text = "Grand Arena";
        $img = "images/ga_map.jpg";
        $style = "transform:translateY(-20%);";
        $link = "ga";
    }
    else if (isset($_GET['tc'])){
        $text = "Theory Crafting";
        $img = "images/farm.png";
        $style = "transform:translateY(10%);";
        $link = "tc";
    }
    else {
        $img = "";
        $style = "";
        $link = "";
        $text = "";
    }

    if (array_key_exists('team_delete', $_GET)) {
			
        $team_id = $_GET['team_delete'];
		if (filter_var($team_id, FILTER_VALIDATE_INT) === false){
            ?>
            <script>
                window.location.href = "error.php?nn";
            </script>
            <?php
        }
		else {
        $sql_d = "DELETE FROM teams WHERE team_id = $team_id";
        $result_d = $conn->query($sql_d);
		
		if (isset($_GET['tw'])){
			$territories = array("T1","T2","T3","T4","B1","B2","B3","B4");
			$sql = "SELECT * FROM loadouts WHERE guild_id = '$guild_id'";
			$result = $conn->query($sql);
			while($data = $result->fetch_assoc()){
				$loadout_id = $data['loadoutID'];
				foreach($territories as $territory){
					$changes = false;
					$tw_data = explode(",",$data[$territory]);
					foreach($tw_data as $tw_id){
						if ($tw_id == $team_id){
							$position = array_search($team_id,$tw_data);
							array_splice($tw_data,$position,1);
							$changes = true;
						}
					}
					if ($changes == true){
						if (count($tw_data) == 0){
							$tw_data = "";
						}
						else {
							$tw_data = implode(",",$tw_data);
						}
						$sql_u = "UPDATE loadouts SET $territory = '$tw_data' WHERE loadoutID = '$loadout_id'";
						$result_u = $conn->query($sql_u);
					}
				}
			}
		}
		if (isset($_GET['ga'])){
			$sql = "SELECT * FROM ga_loadouts WHERE username = '$username'";
			$result = $conn->query($sql);
			$territories = array("T1","B1","B2");
			while($data = $result->fetch_assoc()){
				$ga_data = json_decode($data['teams']);
				
				foreach($territories as $territory){
					foreach($ga_data->$territory->in as $data_id){
						
						if ($data_id == $team_id){
							$position = array_search($data_id,$ga_data->$territory->in);
							array_splice($ga_data->$territory->in,$position,1);
						}
					}
					if (count($ga_data->$territory->in) == 0){
						$ga_data->$territory->in = array(NULL);
					}
				}
				$ga_data = json_encode($ga_data);
				$ga_loadout_id = $data['loadout_id'];
				$sql_u = "UPDATE ga_loadouts SET teams = '$ga_data' WHERE loadout_id = '$ga_loadout_id'";
				$result_u = $conn->query($sql_u);
			}
		}

		header("location: team_manager.php?characters&" . $link );
		}	

    }

    if (array_key_exists('team_delete_ships', $_GET)) {
		echo "ship";
        $team_id = $_GET['team_delete_ships'];
		if (filter_var($team_id, FILTER_VALIDATE_INT) === false){
            ?>
            <script>
                window.location.href = "error.php?nn";
            </script>
            <?php
        }
		else {
        $sql_d = "DELETE FROM ship_team WHERE ship_team_id = $team_id";
        $result_d = $conn->query($sql_d);

		if (isset($_GET['tw'])){
			$territories = array("F1","F2");
			$sql = "SELECT * FROM loadouts WHERE guild_id = '$guild_id'";
			$result = $conn->query($sql);
			while($data = $result->fetch_assoc()){
				$loadout_id = $data['loadoutID'];
				foreach($territories as $territory){
					$changes = false;
					$tw_data = explode(",",$data[$territory]);
					foreach($tw_data as $tw_id){
						if ($tw_id == $team_id){
							$position = array_search($team_id,$tw_data);
							array_splice($tw_data,$position,1);
							$changes = true;
						}
					}
					if ($changes == true){
						if (count($tw_data) == 0){
							$tw_data = "";
						}
						else {
							$tw_data = implode(",",$tw_data);
						}
						$sql_u = "UPDATE loadouts SET $territory = '$tw_data' WHERE loadoutID = '$loadout_id'";
						$result_u = $conn->query($sql_u);
					}
				}
			}
		}
		if (isset($_GET['ga'])){
			$sql = "SELECT * FROM ga_loadouts WHERE username = '$username'";
			$result = $conn->query($sql);
			$territories = array("T2");
			while($data = $result->fetch_assoc()){
				$ga_data = json_decode($data['teams']);
				
				foreach($territories as $territory){
					foreach($ga_data->$territory->in as $data_id){
						
						if ($data_id == $team_id){
							$position = array_search($data_id,$ga_data->$territory->in);
							array_splice($ga_data->$territory->in,$position,1);
						}
					}
					if (count($ga_data->$territory->in) == 0){
						$ga_data->$territory->in = array(NULL);
					}
				}
				$ga_data = json_encode($ga_data);
				$ga_loadout_id = $data['loadout_id'];
				$sql_u = "UPDATE ga_loadouts SET teams = '$ga_data' WHERE loadout_id = '$ga_loadout_id'";
				$result_u = $conn->query($sql_u);
			}
		}

        header("location: team_manager.php?ships&" . $link );
		}

    }

    echo "<script src = 'script/team_manager.js' ></script>";


?>

<link rel="stylesheet" type="text/css" href="styles/team_manager_style.css"/>


<div class="team_builder_bar">
<img src="<?php echo $img;?>" alt="" style = "<?php echo $style; ?>">
<div class="title"> My <?php echo $text;?> Teams </div>

<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='teams.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>

<?php
if (isset($_GET['ships'])){
    ?>
    <div class="type_selector">
        <a title = "Character Teams" href = "team_manager.php?characters&<?php echo $link;?>" class="type"><img src="images/dv_icon.png"></img></a>
        <div class="middle_bar"></div>
        <a title = "Ship Teams" class="type active"><img src="images/star-wars.png"></img></a>
    </div>
    <?php
}
else {
    ?>
    <div class="type_selector">
    <a title = "Character Teams" class="type active"><img src="images/dv_icon.png"></img></a>
    <div class="middle_bar"></div>
    <a title = "Ship Teams" href = "team_manager.php?ships&<?php echo $link;?>" class="type"><img src="images/star-wars.png"></img></a>
    </div>
    <?php
}
?>

</div>

<?php

if (isset($_GET['ships'])){
	?>
    <a href = "team_builder.php?ships&<?php echo $link; ?>" class="create_team" >
    Create New Team
    </a>
    <?php
	$combat = "ships";
}
else {
    ?>
    <a href = "team_builder.php?characters&<?php echo $link; ?>" class="create_team" >
    Create New Team
    </a>
    <?php
	$combat = "characters";
}

if (isset($_GET['saved'])){
	?>
	<script>

		show_saved('<?php echo $combat; ?>','<?php echo $link; ?>');

	</script>
	<?php
}


if (isset($_GET['ships'])){

	if (isset($_SESSION['guest'])){
		$guest_id = $_SESSION['guest'];
		$sql = "SELECT * FROM ship_team WHERE Username = '$username' AND guest_id = '$guest_id'  AND type = '$link' ORDER BY gp DESC" ;

	}
	else {
    	$sql = "SELECT * FROM ship_team WHERE Username = '$username' AND type = '$link' ORDER BY gp DESC" ;
	}

	$result = $conn->query($sql);

	if (mysqli_num_rows($result) == 0){

		echo "<div class = 'no_results_msg'>You currently have no " . strtoupper($link) . " ship teams</div>";
	}

	$dummy_array = array("Team");

	echo "<div class = 'teams'  >";
	
	while ($data = $result->fetch_assoc()) {
		$ships = $data['ships'];
		$ships = explode(",",$ships);
		array_shift($ships);
		echo "<div class='team_container team_container_ships ' >";
		echo "<div class = 'team_background ships'><img src='images/Capture.png'></div>";
		echo "<div class='team_info_container' >";
		echo "<div class = 'team_info'>{$data['gp']}</div>";
		echo "<a id = '{$data['ship_team_id']}' href='team_manager.php?ships&team_delete_ships={$data['ship_team_id']}&$link' class = 'delete_team_link' ><i class='fa-solid fa-trash-can'></i></a>";
		echo "<a id = '{$data['ship_team_id']}' href='team_builder.php?ships&$link&edit={$data['ship_team_id']}' class = 'edit_team_link' ><i class='fa-solid fa-pen-to-square'></i></a>";
		echo "</div>";

		$current_team = array();
		echo "<div class='team_characters team_characters_ships' style='margin-top:10px;'>";
		foreach ($ships as $ship_id){
			$init = substr($ship_id,0,7);
			if ($init == "CAPITAL"){
			foreach ($user_ships as $ship){
				if ($ship->defId == $ship_id){
					echo"<div class = 'capital_ship_team'>";
					display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"team");
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
					display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"small");
					echo "</div>";

				}
			}
			}
		}
		
		echo "</div>";

		if (array_key_exists('team_delete_ship', $_GET)) {
			if (filter_var($_GET['team_delete_ship'], FILTER_VALIDATE_INT) === false){
				?>
				<script>
					window.location.href = "error.php?nn";
				</script>
				<?php
			}
			else {
			delete_team_ship ($conn, $_GET['team_delete_ship']);
			}
		}

		echo "<div id = 'd{$data['ship_team_id']}' class = 'delete_red ships' style = 'height:100%;'></div>";
		
		echo "</div>";
		
	}
	echo "</div>";
}
else {

	if (isset($_SESSION['guest'])){
		$guest_id = $_SESSION['guest'];
		$sql = "SELECT * FROM teams WHERE Username = '$username' AND guest_id = '$guest_id' AND type = '$link' ORDER BY team_gp DESC" ;

	}
	else {
	$sql = "SELECT * FROM teams WHERE Username = '$username' AND type = '$link' ORDER BY team_gp DESC" ;
	}

	$result = $conn->query($sql);

	print_r($result);

	if (mysqli_num_rows($result) == 0){

		echo "<div class = 'no_results_msg'>You currently have no " . strtoupper($link) . " character teams</div>";
	}
	
	if (mysqli_num_rows($result) == 0){
	}
    else {
	$dummy_array = array("Team");
	echo "<div class = 'teams' >";
	while ($data = $result->fetch_assoc()) {
		
		print_r($data) ;

		echo "<div class='team_container'>";
		echo "<div class = 'team_background chars_$link'><img src='images/battle$link.PNG'></div>";

		echo "<div class = 'factions_contained' style = 'display:none;'>";
			echo $data['factions_contained'];
			echo "</div>";
		echo "<div class='team_info_container' >";
		echo "<div class = 'team_info'>{$data['team_gp']}</div>";
		echo "<a id = '{$data['team_id']}' href='team_manager.php?ships&team_delete={$data['team_id']}&$link' class = 'delete_team_link' ><i class='fa-solid fa-trash-can'></i></a>";
		echo "<a id = '{$data['team_id']}' href='team_builder.php?characters&$link&edit={$data['team_id']}' class = 'edit_team_link' ><i class='fa-solid fa-pen-to-square'></i></a>";
		echo "</div>";
		
		print_r($user_characters);

		echo "<div class='team_characters' >";
		echo"<div class = 'char_1'>";
		echo 'Displaying Character 1';
		print_r(get_char_info($conn, $user_characters, $data['LeaderID']));
		display_character_new($conn,get_char_info ($conn, $user_characters, $data['LeaderID'] ),$dummy_array);
		echo"</div><div class = 'char_2'>";
		display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character2ID'] ),$dummy_array);
		echo"</div><div class = 'char_3'>";
		display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character3ID'] ),$dummy_array);
		echo"</div><div class = 'char_4'>";
		display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character4ID'] ),$dummy_array);
		echo"</div><div class = 'char_5'>";
		display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character5ID'] ),$dummy_array);
		echo "</div></div>";

		echo "<div id = 'd{$data['team_id']}' class = 'delete_red' ></div>";
		
		echo "</div>";
		
	}
	echo "</div>";
}
}
