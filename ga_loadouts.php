<?php
    ob_start();
    include 'functions/class_init.php';
    include 'classes/character_class.php';
    include 'classes/user_new.php';

    session_start();

    include_once 'header.php';

    check_logged_in();

    $username = $_SESSION['Username'];

?>
<script src = "script/ga.js"></script>
<?php

    if (isset($_GET['plan_name'])){
        $plan_name = $_GET['plan_name'];
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $plan_name))
        {
            ?>
            <script>
                window.location.href = "error.php?special"
            </script>
            <?php        
        }
        else {
        $current_date = date("d/m/Y");
        $sql = "SELECT * FROM users WHERE Username = '$username'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $rank = strtolower($data['skill_league']);
        }
        $ranks = array(
            'carbonite' => '{"T1":{"total":"1","in":[""]},"T2":{"total":"1","in":[""]},"B1":{"total":"1","in":[""]},"B2":{"total":"1","in":[""]}}',
            'bronzium' => '{"T1":{"total":"2","in":[""]},"T2":{"total":"1","in":[""]},"B1":{"total":"2","in":[""]},"B2":{"total":"1","in":[""]}}',
            'chromium' => '{"T1":{"total":"3","in":[""]},"T2":{"total":"2","in":[""]},"B1":{"total":"2","in":[""]},"B2":{"total":"2","in":[""]}}',
            'aurodium' => '{"T1":{"total":"3","in":[""]},"T2":{"total":"2","in":[""]},"B1":{"total":"3","in":[""]},"B2":{"total":"3","in":[""]}}',
            'kyber' => '{"T1":{"total":"4","in":[""]},"T2":{"total":"3","in":[""]},"B1":{"total":"4","in":[""]},"B2":{"total":"3","in":[""]}}'
        );
        // $teams = array('T1' => array('total'=> 1,'in'=>[NULL]), 'T2'=>array('total'=> 1,'in'=>[null]), 'B1'=>array('total'=> 1,'in'=>[null]),  'B2'=>array('total'=> 1,'in'=>[null]));
        $teams = $ranks[$rank];
        if (isset($_SESSION['guest'])){
            $guest_id = $_SESSION['guest'];
            $sql = "INSERT INTO ga_loadouts (name,guest_id,username,created,rank,teams) VALUES ('$plan_name','$guest_id','$username','$current_date','$rank','$teams')";

        }
        else {
            $sql = "INSERT INTO ga_loadouts (name,username,created,rank,teams) VALUES ('$plan_name','$username','$current_date','$rank','$teams')";

        }
        $result = $conn->query($sql);
        
		header("location:ga_loadouts.php?new");
        }
	}
    if (isset($_GET['new'])){
        $sql = "SELECT * FROM ga_loadouts WHERE username = '$username'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $id = $data['loadout_id'];
        }
        header("location:edit_ga_loadout.php?i=$id");
    }
    ?>

<script src = "script/tw_loadout_manager.js" defer></script>

<link rel="stylesheet" type="text/css" href="styles/team_manager_style.css"/>
<link rel="stylesheet" type="text/css" href="styles/tw_loadout_manager.css"/>
<link rel="stylesheet" type="text/css" href="styles/farming_manager.css"/>

    
<div class="team_builder_bar">
<img src="images/ga_map.jpg" alt="" >
<div class="title">Grand Arena Loadout Plans</div>

<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='teams.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>


<div class="tw_settings" style = 'position:absolute;right: 5px;' >
    <a href='info.php'>
        <i class="material-icons">info</i>
    </a>
</div>
</div>



<div  onclick = "create_loadout()" class="create_loadout" >
<i class="fa-regular fa-plus"></i> Create New Loadout
<form>
    <input onkeypress="return /[0-9a-zA-Z\s]/i.test(event.key)" name = "plan_name" placeholder = "Enter New Loadout Name" type = "text" class="create_input"></input>
    <button style = ''type = "submit" class = "create_button"><div class="material-icons">arrow_forward</div></button>
</form>
</div>


<div class="seperator"></div>


<div class="plans_container">
<?php
    if(isset($_SESSION['guest'])){
        $guest_id = $_SESSION['guest'];
        $sql = "SELECT * FROM ga_loadouts WHERE username = '$username' AND guest_id = '$guest_id'";

    }
    else {
        $sql = "SELECT * FROM ga_loadouts WHERE username = '$username'";

    }
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) == 0){

		echo "<div class = 'no_results_msg'>You currently have no GA loadouts!</br>Create a new loadout above</div>";
	}
    while($data = $result->fetch_assoc()){
        $id = $data['loadout_id'];
        $name = $data['name'];
        $created = $data['created'];
        ?>

        <div id = '<?php echo $id;?>' onclick = 'edit_ga_loadout(this.id,event.target)' class='farming_plan'>
            <img src = "images/ga_defence.png" style = 'transform: translatey(-30%)'>
            <!-- <img src = "images/tw_background.jpeg" style = "transform:translate(0,-30%)"> -->
            <div class="loadout_name"><?php echo $name;?></div>
            <div class="loadout_dates">Created: <?php echo $created;?></div>
            <div class="delete_plan" onclick = 'delete_ga_loadout(this.parentElement.id)'><i class="fa-solid fa-xmark"></i></div>
        </div>
    <?php

    }
?>
</div>
