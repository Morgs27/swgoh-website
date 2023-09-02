<?php
     ob_start();
     include 'functions/class_init.php';
     include 'classes/character_class.php';
     include 'classes/user_new.php';
 
     session_start();
 
     include_once 'header.php';

     check_logged_in();

     $username = $_SESSION['Username'];
    $guild_id = get_guild($conn,$_SESSION["Username"]);
    $guild_code = get_guild_code($conn,$guild_id);
    $guild_name = get_guild_name($conn,$guild_id);
    $rank = get_rank($conn,$_SESSION['Username']);

    if (isset($_GET['loadout_name'])){
        $loadout_name = $_GET['loadout_name'];
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $loadout_name))
        {
            ?>
            <script>
                window.location.href = "error.php?special"
            </script>
            <?php        
        }
        else {
        $current_date = date("d/m/Y");
        if (isset($_SESSION['guest'])){
            $guest_id =  $_SESSION['guest'] ;
            $sql = "INSERT INTO loadouts (guest_id,loadout_name,created_by,guild_id,active,creation_date) VALUES ('$guest_id','$loadout_name','$username','$guild_id','false','$current_date')";

        }
        else {
            $sql = "INSERT INTO loadouts (loadout_name,created_by,guild_id,active,creation_date) VALUES ('$loadout_name','$username','$guild_id','false','$current_date')";

        }
        $result = $conn->query($sql);
		// header("location:tw_loadout_manager.php");
        }
	}

?>



<script src = "script/tw_loadout_manager.js" defer></script>
<script src = "script/ga.js" defer></script>
<link rel="stylesheet" type="text/css" href="styles/team_manager_style.css"/>
<link rel="stylesheet" type="text/css" href="styles/tw_loadout_manager.css"/>
<link rel="stylesheet" type="text/css" href="styles/farming_manager.css"/>



<div class="team_builder_bar" >
<img src="images/tw_map_2.jpg" alt="" >
<div class="title">My TW Loadout Plans</div>

<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='teams.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>


<!--<div class="tw_settings" style = 'position:absolute;right: 5px;' >-->
<!--    <a href='info.php'>-->
<!--        <i class="material-icons">info</i>-->
<!--    </a>-->
<!--</div>-->



</div>


<div  onclick = "create_loadout()" class="create_loadout" >
<i class="fa-regular fa-plus"></i> Create New Loadout
<form>
    <input onkeypress="return /[0-9a-zA-Z\s]/i.test(event.key)" name = "loadout_name" placeholder = "Enter New Loadout Name" type = "text" class="create_input"></input>
    <button style = ''type = "submit" class = "create_button"><div class="material-icons">arrow_forward</div></button>
</form>
</div>


<div class="seperator"></div>


<div class="plans_container">
<?php
    if (isset($_SESSION['guest'])){
        $guest_id =  $_SESSION['guest'] ;
        $sql = "SELECT * FROM loadouts WHERE created_by = '$username' AND guest_id = '$guest_id' ORDER BY str_to_date(creation_date, '%d/%m/%Y') DESC";
    }
    else {
        $sql = "SELECT * FROM loadouts WHERE created_by = '$username' ORDER BY str_to_date(creation_date, '%d/%m/%Y') DESC";

    }
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) == 0){

		echo "<div class = 'no_results_msg'>You currently have no TW loadouts!</br>Create a new loadout above</div>";
	}

    while($data = $result->fetch_assoc()){
        $id = $data['loadoutID'];
        $name = $data['loadout_name'];
        $created = $data['creation_date'];
        ?>

        <div id = '<?php echo $id;?>' onclick = 'edit_tw_loadout(this.id,event.target)' class='farming_plan'>
            <img src = "images/tw_background.jpeg" style = 'transform: translatey(-30%)'>
            <!-- <img src = "images/tw_background.jpeg" style = "transform:translate(0,-30%)"> -->
            <div class="loadout_name"><?php echo $name;?></div>
            <div class="loadout_dates">Created: <?php echo $created;?></div>
            <div class="delete_plan" onclick = 'delete_tw_loadout(this.parentElement.id)'><i class="fa-solid fa-xmark"></i></div>
        </div>
    <?php

    }
?>
</div>



