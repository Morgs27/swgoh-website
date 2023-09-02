<?php
	ob_start();
    include 'functions/class_init.php';
    include 'classes/character_class.php';
    include 'classes/user_new.php';

    session_start();

	include_once 'header.php';

    check_logged_in();

    if (isset($_GET['ships'])){
        echo "<script src = 'script/team_builder_ships.js' ></script>";
    }
    else {
    echo "<script src = 'script/team_builder_characters.js' ></script>";
    }

    echo "<script src = 'script/team_builder.js' defer></script>";
	
	echo "<div class = 'load_screen'>";
	echo "<div class = 'loader'>";
	echo "</div>";
	echo "<div id = 'loader_text'>Fetching User Data...";
	echo "</div>";
	echo "</div>";

    if (isset($_SESSION['guest'])){
        $guest_id = $_SESSION['guest'];
        ?>
        <script>
            window.guest_id = '<?php echo $guest_id;?>';
        </script>
        <?php
    }

	if (isset($_GET['refresh'])){
		?>
		<script>
		let load_screen = document.querySelector('.load_screen');
		load_screen.style.display = "block";
		document.getElementById('loader_text').innerHTML = "Saving User Data..."
		</script>
		<?php
	}

    if (isset($_GET['refresh'])){
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $username = $_SESSION['Username'];
        $sql = "UPDATE users SET refresh_date = '$date', refresh_time = '$time' WHERE username = '$username'";
        $result = $conn->query($sql);
        $ally_code = get_ally_code($conn,$_SESSION["Username"]);
        refresh_player_data($ally_code,$_SESSION['Username'],$conn);
        ?>
        <script>
        // window.alert("here");
        setTimeout(function() {
        var ref = window.location.href;
        ref = ref.replace("?refresh","");
        window.location.href = ref + "?data";
        }, 5000);
        </script>
        <?php
    }

    $username = $_SESSION['Username'];
    $user_characters = $_SESSION['user_info_class'];
    $user_ships = $_SESSION['user_info_ship'];
    
    
    if ($user_characters == Null || $user_ships == Null){
        $user_characters = (getPlayerInfo_new($_SESSION["Username"],$conn));
		$user_ships = (getPlayerInfo_ship($_SESSION["Username"],$conn));
		
		 if ($user_characters == Null || $user_ships == Null){
		     header("Location: error.php?nud");
		 }
		 else {
		    $_SESSION['user_info_ship'] = $user_ships;
		    $_SESSION['user_info_class'] = $user_characters;
		 }

    }

    usort($user_ships,function($first,$second){
        return $first->gp < $second->gp;
    });

    usort($user_characters,function($first,$second){
        return $first->gp < $second->gp;
    });

    if (isset($_GET['tw'])){
        $text = "Territory War";
        $img = "images/tw_map_2.jpg";
        $style = "";
        $link = "tw";
        ?>
        <style>
            [gear_none]{
                display: none;
            }
        </style>
        <?php
    }
    else if (isset($_GET['ga'])){
        $text = "Grand Arena";
        $img = "images/ga_map.jpg";
        $style = "transform:translateY(-20%);";
        $link = "ga";
        ?>
        <style>
            [gear_none]{
                display: none;
            }
        </style>
        <?php
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
    }

    if (isset($_GET['edit'])){
        $text_edit = "Editor"; 

        if (filter_var($_GET['edit'], FILTER_VALIDATE_INT) === false){
            ?>
            <script>
                window.location.href = "teams.php";
            </script>
            <?php
        }

        ?>
        <script>
            window.edit_team = '<?php echo $_GET["edit"];?>';
        </script>
        <?php
    }
    else {
        $text_edit = "Builder";
    }

?>

<div class="team_builder_bar">
    <img src="<?php echo $img;?>" alt="" style = "<?php echo $style; ?>">

    <div class="title"> <?php echo $text;?> Team <?php echo $text_edit;?> </div>
    
    <div class="tw_settings" style = 'position:absolute;left: 5px;' >
        <?php
        if (isset($_GET['ships'])){
            ?>
        <a href='team_manager.php?ships&<?php echo $link;?>'>
            <?php
        }
        else {
            ?>
        <a href='team_manager.php?characters&<?php echo $link;?>'>
            <?php
        }
        ?>
            <i class="material-icons">arrow_back</i>
        </a>
    </div>


</div>


<div class = "right_sidebar">
<div class="right_sidebar_content">
<div class = "right_sidebar_inner">


<div class='current_gp' id = 'gp'>GP: 0</div>



<?php
if (isset($_GET['ships'])){
    ?>
    <div class='profile_containers_full_current_outer' style = 'padding-top:3px;'>
    <div class='profile_containers_full_current' id = 'ship_char_container'>
    </div>
    </div>
    <?php
}
else {
    echo "<div class='profile_containers_full_current_outer' >";
    echo "<div class = 'current_team_new'>";
    echo "<div class='profile_containers_full_current' id = 'team_char_container'>";
    echo "</div></div></div>";
}
?>

<div class = "right_sidebar_buttons">
    <div class="team_button" onclick = "clear_team()">
        <div class="clear_btn" >Clear</div>
    </div>
    <?php
    if (!isset($_GET['ships'])){
        if (isset($_SESSION['guest'])){
            ?>
            <div class="team_button" onclick = "save_team_guest('<?php echo $link; ?>','<?php echo $username; ?>','<?php echo $text_edit;?>')" >
                <div class="save_btn"  >Save</div>
            </div>
            <?php
        }
        else {
            ?>
            <div class="team_button" onclick = "save_team('<?php echo $link; ?>','<?php echo $username; ?>','<?php echo $text_edit;?>')" >
                <div class="save_btn"  >Save</div>
            </div>
            <?php
        }
    }
    else {
        if (isset($_SESSION['guest'])){
            ?>
            <div class="team_button" onclick = "save_team_ship_guest('<?php echo $link; ?>','<?php echo $username; ?>','<?php echo $text_edit;?>')" >
                <div class="save_btn"  >Save</div>
            </div>
            <?php
        }
        else {
            ?>
            <div class="team_button" onclick = "save_team_ship('<?php echo $link; ?>','<?php echo $username; ?>','<?php echo $text_edit;?>')" >
                <div class="save_btn"  >Save</div>
            </div>
            <?php
        }
    }
    ?>
</div>

</div>
</div>
</div>


<div class="toggle_team" onclick = 'toggle_team()'>
	Team
	<div class="toggle_arrow">
	<span></span>
	<span></span>
	<span></span>
	</div>	
</div>

<div class="allChar_container" >
<div class = "allChar" id="divTest">

<?php



if (isset($_GET['ships'])){
    // Ships Mode
?>

    <div class="guild_characters_search_container" style = 'margin-bottom: 10px;'>
	<div class="guild_characters_search">
		<input type="text" id="search_ship_input_tb"  onkeyup="search_ship()" placeholder="Search for a Ship..." title="Type in a name">
	
	</div>
	<!-- <div class="show_unique_characters" onclick = 'show_unique_ship()'>
	Show Unique
	</div> -->
	</div>



<div class='profile_containers_full_ship'>


<?php
    $in_team = get_ships_in_team($_SESSION['Username'],$conn);

    if (isset($_GET['tc'])){
        $in_team = array();
    }

    if(isset($_GET['edit'])){
        $id = $_GET['edit'];
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $id))
            {
                ?>
                <script>
                    window.location.href = "error.php?special"
                </script>
                <?php   
                exit();     
            }
        else{
        $sql = "SELECT * FROM ship_team WHERE ship_team_id = '$id'";
        $result = $conn->query($sql);
        check_rows($result);
        while($data = $result->fetch_assoc()){
           $ships = explode(",",$data['ships']);
        }
        foreach($ships as $edit_char){
            foreach ($in_team as $team_char){
                if ($edit_char == $team_char){
                    $position = array_search($edit_char,$in_team);
                    array_splice($in_team,$position,1);
                }
            }
        }
        }
    }

	 $loaded_ships = array();

	foreach ($user_ships as $ship){
        if (in_array($ship->defId, $loaded_ships)){
            // Ship Already Loaded
        }
        else {
            $init = substr($ship->defId,0,7);
            if ($init == "CAPITAL"){
                $display = true;
                foreach($in_team as $char_in){
                    if ($char_in == $ship->defId){
                        $display = false;
                    }
                }
                if ($display == true){
                    display_ship($conn,$ship,array("test"),$user_characters,$_SESSION['Username'],"CAPITAL");
                    array_push($loaded_ships, $ship->defId);
                }
            }
        }
    
	}

	foreach ($user_ships as $ship){
        if (in_array($ship->defId, $loaded_ships)){
            // Ship Already Loaded
        }
        else {
            $init = substr($ship->defId,0,7);
            if ($init == "CAPITAL"){
                
            }
            else {
                $display = true;
                foreach($in_team as $char_in){
                    if ($char_in == $ship->defId){
                        $display = false;
                    }
                }
                if ($display == true){
                    display_ship($conn,$ship,array('test'),$user_characters,$_SESSION['Username'],"NONE");
                    array_push($loaded_ships, $ship->defId);
                }
            }
        
        }
	   
	}


	echo "</div>";
    ?>
    <script>
        ship_startup();
    </script>
    <?php

}
else {
    // Character Mode

	?>

    <div class="guild_characters_search_container" style = 'margin-bottom: 10px;'>
    <div class="guild_characters_search">
        <input type="text" id="search_character_input_tb"  onkeyup="search_character()" placeholder="Search for a Character..." title="Type in a name">

    </div>
    <!-- <div class="show_unique_characters" onclick = 'show_unique()'>
    Show Unique
    </div> -->
    </div>


	
	
	<?php
    echo "<div class='profile_containers_full'>";

    if (isset($_GET['ga'])){
        $in_team = get_characters_in_team($_SESSION['Username'],$conn);
    }
    else if (isset($_GET['tw'])){
        $in_team = get_characters_in_team($_SESSION['Username'],$conn);
    }
    else {
        $in_team = array();
    }

    if(isset($_GET['edit'])){
        $id = $_GET['edit'];
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $id))
            {
                ?>
                <script>
                    window.location.href = "error.php?special"
                </script>
                <?php   
                exit();     
            }
        else{
        $sql = "SELECT * FROM teams WHERE team_id = '$id'";
        $result = $conn->query($sql);
        check_rows($result);
        $in_edit = array();
        while($data = $result->fetch_assoc()){
           
            $leader_id = $data['LeaderID'];
            $c2 = $data['Character2ID'];
            $c3 = $data['Character3ID'];
            $c4 = $data['Character4ID'];
            $c5 = $data['Character5ID'];
            array_push($in_edit,$leader_id,$c2,$c3,$c4,$c5);
        }

        foreach($in_edit as $edit_char){
            foreach ($in_team as $team_char){
                if ($edit_char == $team_char){
                    $position = array_search($edit_char,$in_team);
                    array_splice($in_team,$position,1);
                }
            }
        }
        }
    }
    
     $loaded = array();
    foreach ($user_characters as $character){

        if (in_array($character->defId,$loaded)){
            // Character Already Loaded
        }
        else {
            $display = true;
            foreach($in_team as $char_in){
                if ($char_in == $character->defId){
                    $display = false;
                }
            }
            if ($display == true){
                if (isset($_GET['ga']) || isset($_GET['tw'])){
                    if ($character->rarity == 0){
    
                    }
                    else {
                        display_character_new($conn,$character,array('test'));
                        array_push($loaded, $character->defId);
                    }
                }
                else {
                    display_character_new($conn,$character,array('test'));
                    array_push($loaded, $character->defId);
                }
            }
            
        }
    
    }
    
    ?>
    </div>
    <?php

}

?>

</div>
</div>

<?php
    if(isset($_GET['edit'])){
        if (!isset($_GET['ships'])){
        $id = $_GET['edit'];
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $id))
            {
                ?>
                <script>
                    window.location.href = "error.php?special"
                </script>
                <?php   
                exit();     
            }
        else{
        $sql = "SELECT * FROM teams WHERE team_id = '$id'";
        $result = $conn->query($sql);
        check_rows($result);
        while($data = $result->fetch_assoc()){
            $leader_id = $data['LeaderID'];
            $c2 = $data['Character2ID'];
            $c3 = $data['Character3ID'];
            $c4 = $data['Character4ID'];
            $c5 = $data['Character5ID'];
        }
        ?>
        <script>
            add_char_team(document.getElementById('<?php echo $leader_id;?>').firstChild.id);
            add_char_team(document.getElementById('<?php echo $c2;?>').firstChild.id);
            add_char_team(document.getElementById('<?php echo $c3;?>').firstChild.id);
            add_char_team(document.getElementById('<?php echo $c4;?>').firstChild.id);
            add_char_team(document.getElementById('<?php echo $c5;?>').firstChild.id);
        </script>
        <?php
        }
        }
        else {
            $id = $_GET['edit'];
            if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $id))
                {
                    ?>
                    <script>
                        window.location.href = "error.php?special"
                    </script>
                    <?php   
                    exit();     
                }
            else{
            $sql = "SELECT * FROM ship_team WHERE ship_team_id = '$id'";
            $result = $conn->query($sql);
            check_rows($result);
            while($data = $result->fetch_assoc()){
               $ships = explode(",",$data['ships']);
            }
            $capital = $ships[1];
            array_splice($ships,0,2);
            ?>
                 <script>
                    capital_id = '<?php echo $capital;?>';
                    add_capital_team(document.getElementById(capital_id).firstChild.id);
                </script>
            <?php
            foreach($ships as $ship_id){
                ?>
                <script>
                    ship_id = '<?php echo $ship_id;?>';
                    add_char_team(document.getElementById(ship_id).firstChild.id);
                </script>
                <?php
            }
            }
        }
    }
?>

<script>
    let input = document.getElementsByTagName('input')[0];
    input.focus();

</script>


	
