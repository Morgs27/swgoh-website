<?php
    ob_start();
    include 'functions/class_init.php';
    include 'classes/character_class.php';
    include 'classes/user_new.php';
    session_start();
    include_once 'header.php';

    check_logged_in();

    $username = $_SESSION['Username'];
    

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
        if (isset($_SESSION['guest'])){
            $guest_id = $_SESSION['guest'];
            $sql = "INSERT INTO farming_plan (plan_name,guest_id,username,created) VALUES ('$plan_name','$guest_id','$username','$current_date')";
        }
        else {
            $sql = "INSERT INTO farming_plan (plan_name,username,created) VALUES ('$plan_name','$username','$current_date')";
        }
        $result = $conn->query($sql);
		header("location:farming_manager.php?new");
        }
	}
    if (isset($_GET['new'])){
        $sql = "SELECT * FROM farming_plan WHERE username = '$username'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $id = $data['plan_id'];
        }
        header("location:edit_farming_plan.php?i=$id&new");
    }
    ?>

<script>
    window.username = '<?php echo $username;?>';
</script>

<script src = "script/tw_loadout_manager.js" defer></script>
<link rel="stylesheet" type="text/css" href="styles/team_manager_style.css"/>
<link rel="stylesheet" type="text/css" href="styles/tw_loadout_manager.css"/>
<link rel="stylesheet" type="text/css" href="styles/farming_manager.css"/>

<div class="example_plan" style = 'display:none'>
    <div id = 'e_$id_here' onclick = 'edit_plan(this.id,event.target)' class='farming_plan'>
        <img src = "images/journey.PNG">
        <div class="loadout_name">$name_here</div>
        <div class="loadout_dates">Created: $created_here</div>
        <div id = '$id_here' class="delete_plan" onclick = 'delete_plan(this.id);'><i class="fa-solid fa-xmark"></i></div>
    </div>
</div>

<div class="team_builder_bar" style = 'position:relative'>
<img src="images/farm.png" alt="" >
<div class="title">Farming Plans</div>

<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='teams.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>


<div onclick = 'open_import()' class="tw_settings export" style = 'position:absolute;right: 5px;display: flex;justify-content: center;align-items:center' >
    <div>
        <div class = 'export_open_text'>Import</div> <i style = "transform: translateX(-3px)"class="fa-solid fa-file-import"></i>
    </div>
</div>

</div>


<div class="link_container" style = 'display:none'>
    <div class="spinner_container" >
        <div class="spinner_circle"></div>
        <div class="spinner_circle outer_1"></div>
        <div class="spinner_circle outer_2"></div>
        <div class="spinner_circle plannet_1"></div>
        <div class="spinner_circle plannet_2"></div>
    </div>
    <div class = 'load_link_text'>Importing Plan ...</div>
    <div class = 'link_area'>
        <div class = 'close' onclick = 'close_import()'></div>
        <i class="fa-solid fa-link link_i"></i>
        <div class="link_title_bit " style = 'font-family:verdana'>Enter a code to import a plan.</div>
        <div class="link_link">
            <input  type = 'text' style = 'font-family:verdana;background:transparent;outline:none;border:0px;color:white' class="link_link_text" ></input>
            <div class="copy_link" onclick = 'import_plan()'><i class="fa-solid fa-file-import"></i></div>
        </div>
    </div>
</div>



<div  onclick = "create_loadout()" class="create_loadout" >
<i class="fa-regular fa-plus"></i> New Farming Plan
<form>
    <input onkeypress="return /[0-9a-zA-Z\s]/i.test(event.key)" name = "plan_name" placeholder = "Enter New Plan Name" type = "text" class="create_input"></input>
    <button style = ''type = "submit" class = "create_button"><div class="material-icons">arrow_forward</div></button>
</form>
</div>


<div class="seperator"></div>


<div class="plans_container">
<?php
    if (isset($_SESSION['guest'])){
        $guest_id = $_SESSION['guest'];
        $sql = "SELECT * FROM farming_plan WHERE username = '$username' AND guest_id = '$guest_id'";

    }
    else {
        $sql = "SELECT * FROM farming_plan WHERE username = '$username'";
    }
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) == 0){

		echo "<div class = 'no_results_msg'>You currently have no Farming Plans!</br>Create a new plan above</div>";
	}
    while($data = $result->fetch_assoc()){
        $id = $data['plan_id'];
        $name = $data['plan_name'];

        $created = $data['created'];
        ?>

        <div id = 'e_<?php echo $id;?>' onclick = 'edit_plan(this.id,event.target)' class='farming_plan'>
            <img src = "images/journey.PNG">
            <!-- <img src = "images/tw_background.jpeg" style = "transform:translate(0,-30%)"> -->
            <div class="loadout_name"><?php echo $name;?></div>
            <div class="loadout_dates">Created: <?php echo $created;?></div>
            <div id = '<?php echo $id;?>' class="delete_plan" onclick = 'delete_plan(this.id);'><i class="fa-solid fa-xmark"></i></div>
        </div>
    <?php

    }
?>
</div>
