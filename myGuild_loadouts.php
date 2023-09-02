<?php
session_start();
ob_start();

include_once 'header.php';

check_logged_guild($conn);

$username = $_SESSION['Username'];

$viewer_rank = get_rank($conn,$username);

if (isset($_SESSION['guest'])){

}
else {
    $guild_id = get_guild($conn,$username);
    $guild_code = get_guild_code($conn,$guild_id);
    $guild_name = get_guild_name($conn,$guild_id);

   

}

?>

<link rel="stylesheet" href="styles/my_guild.css" />


<script src='script/my_guild.js'></script>

<div class="team_builder_bar" style = 'position:relative'>

<?php
if ($viewer_rank != 'officer' && $viewer_rank != 'leader'){
    ?>
    <style>
        .loadout_options{
            display: none !important;
        }
    </style>
    <?php
}
?>

<img src="images/tw_map_2.jpg" alt="" >
<div class="title">Guild TW Loadouts</div>
    
<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='myGuild.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>

<?php if ($viewer_rank == 'Leader'){
?>
<!-- <div class="settings" style = 'position:absolute;right: 15px;' onclick = 'toggle_settings()' >
    <i class="fa-solid fa-gear"></i>
</div> -->
<?php
}
?>

</div>

<div class="settings_modal">
    <div class="top">Permissions</div>
    <div class="seperator" style = 'width: 100%'></div>
    <div class="selection_title">Broadcast Loadouts</div>
    <div class="selection_options">
        <div class="selection active">Leader</div>
        <div class="selection active">Officers</div>
        <div class="selection">Members</div>
    </div>
     <div class="seperator" style = 'width: 100%;margin-top: 20px;'></div>
     <div class="selection_title">View Guild Loadouts</div>
     <div class="selection_options">
         <div class="selection active">Leader</div>
         <div class="selection active">Officers</div>
        <div class="selection">Members</div>
     </div>
     <div class="seperator" style = 'width: 100%;margin-top: 20px;'></div>
     <div class="selection_title">Create Guild Loadouts</div>
     <div class="selection_options">
         <div class="selection active">Leader</div>
        <div class="selection active">Officers</div>
        <div class="selection">Members</div>
   </div>
</div>

<div class="confirmation_modal">
    <div class="close" onclick = 'this.parentElement.classList.remove("active");document.querySelector(".page_content").classList.remove("hide")'></div>
    <div class="important"><i class="fa-solid fa-circle-exclamation"></i></div>
    <div class="msg">You are about to broadcast this loadout to everyone in your guild.</div>
    <div class="continue" onclick = 'add_loadout_active(this.getAttribute("loadout"))'>Continue <i class="fa-solid fa-arrow-right"></i></div>
</div>

<div class="page_content">
<div class="over"></div>
<div class="seperator"></div>
<div style = 'text-align: center;width: 100%;color: rgba(255,255,255,0.8);margin-top: 20;'>Active Loadout</div>
<div class="active_loadout_container">
    <?php
    $sql = "SELECT * FROM loadouts,users WHERE loadouts.guild_id = '$guild_id' AND loadouts.created_by = users.username AND loadouts.active = 'True'";
    $result = $conn->query($sql);
    if ((mysqli_num_rows($result)) == 0){
        echo "<div class = 'no_text'>No Active Loadout</div>";
    }
    else {
    while ($data = $result->fetch_assoc()) {
        $loadout_id = $data['loadoutID'];
        $loadout_name = $data['loadout_name'];
        $created_by = $data['created_by'];
        $user_rank = $data['guild_rank'];
        ?>
        <div id = '<?php echo $loadout_id;?>' creator_rank = '<?php echo $user_rank;?>' class="loadout active" onclick = "view('<?php echo $loadout_id;?>',event)">
        <div class="loadout_img"><img src="images/tw_background.jpeg" style="transform: translateY(-30%)"></div>
        <div class="loadout_options">
            <div class="option" onclick = 'change_active("<?php echo $loadout_id;?>")'><i class="fa-solid fa-satellite-dish"></i></div>
            <div class="option" onclick = 'change_favorite("<?php echo $loadout_id;?>")'><i class="fa-solid fa-star"></i></div>
        </div>
        <div class="loadout_name"><?php echo $loadout_name;?></div>
        <div class="loadout_dates"><?php echo $created_by;?></div>
        </div>
        <?php
        
    }
    }
    ?>
</div>

<div class="seperator"></div>

<div class="loadouts_container">
    <div style = 'text-align: center;width: 100%;color: rgba(255,255,255,0.8);margin-top: 15px; margin-bottom: 10px;'>Officers Loadouts</div>


    <div creator_rank = "officer" ></div>
    <div creator_rank = "leader" ></div>
    <?php
    $sql = "SELECT * FROM loadouts,users WHERE loadouts.guild_id = '$guild_id' AND loadouts.created_by = users.username AND (users.guild_rank = 'leader' OR users.guild_rank = 'officer') AND loadouts.active = 'False' ORDER BY CASE WHEN loadouts.favorite = 'true' THEN '1' END DESC, str_to_date(creation_date, '%d/%m/%Y') DESC";
    $result = $conn->query($sql);
    if ((mysqli_num_rows($result)) == 0){
        echo "<div class = 'no_text'>No loadouts created by officers.</br> Create a loadout in the team manager page.</div>";
    }
    else {
    while ($data = $result->fetch_assoc()) {
        $loadout_id = $data['loadoutID'];
        $loadout_name = $data['loadout_name'];
        $creation_date = $data['creation_date'];
        $created_by = $data['created_by'];
        $favorite = $data['favorite'];
        $user_rank = $data['guild_rank'];
        if ($favorite == 'true'){
            echo '<div id =  "' . $loadout_id . '" creator_rank = "' . $user_rank . '" class="loadout favorite" onclick = "view(' . $loadout_id . ',event)">';
        }
        else {
            echo '<div id =  "' . $loadout_id . '" creator_rank = "' . $user_rank . '" class="loadout"  onclick = "view(' . $loadout_id . ',event)">';
        }
        ?>
        <div class="loadout_img"><img src="images/tw_background.jpeg" style="transform: translateY(-30%)"></div>
        <div class="loadout_options">
            <div class="option" onclick = 'change_active("<?php echo $loadout_id;?>")'><i class="fa-solid fa-satellite-dish"></i></div>
            <div class="option" onclick = 'change_favorite("<?php echo $loadout_id;?>")'><i class="fa-solid fa-star"></i></div>
        </div>
        <div class="loadout_name"><?php echo $loadout_name;?></div>
        <div class="loadout_dates"><?php echo $created_by . '  -  ' . $creation_date;?></div>
        </div>
        <?php
    }
    }
    ?>
    
    <div class="list_break">
        <div class="line"></div>
    </div>
    
    <div class = "list_break" style = 'text-align: center;width: 100%;color: rgba(255,255,255,0.8);margin-bottom: 15px;transform: translateY(2.5px);height: 20px;'>Members Loadouts</div>

    <div creator_rank = "member"></div>
    

    <?php
        $sql = "SELECT * FROM loadouts,users WHERE loadouts.guild_id = '$guild_id' AND loadouts.created_by = users.username AND users.guild_rank != 'officer' AND users.guild_rank != 'leader' AND loadouts.active = 'False' ORDER BY CASE WHEN loadouts.favorite = 'true' THEN '1' END DESC, str_to_date(creation_date, '%d/%m/%Y') DESC";
        $result = $conn->query($sql);
        if ((mysqli_num_rows($result)) == 0){
            // echo "<div class = 'no_text'>No loadouts created by members.</div>";
        }
        else {
        while ($data = $result->fetch_assoc()) {
            $loadout_id = $data['loadoutID'];
            $loadout_name = $data['loadout_name'];
            $created_by = $data['created_by'];
            $favorite = $data['favorite'];
            $user_rank = $data['guild_rank'];
            $creation_date = $data['creation_date'];
            if ($favorite == 'true'){
                echo '<div id =  "' . $loadout_id . '" creator_rank = "' . $user_rank . '"   class="loadout favorite" onclick = "view(' . $loadout_id . ',event)">';
            }
            else {
                echo '<div id =  "' . $loadout_id . '" creator_rank = "' . $user_rank . '"   class="loadout"  onclick = "view(' . $loadout_id . ',event)">';
            }
            ?>
            <div class="loadout_img"><img src="images/tw_background.jpeg" style="transform: translateY(-30%)"></div>
            <div class="loadout_options">
                <div class="option" onclick = 'change_active("<?php echo $loadout_id;?>")'><i class="fa-solid fa-satellite-dish"></i></div>
                <div class="option" onclick = 'change_favorite("<?php echo $loadout_id;?>")'><i class="fa-solid fa-star"></i></div>
            </div>
            <div class="loadout_name"><?php echo $loadout_name;?></div>
            <div class="loadout_dates"><?php echo $created_by . '  -  ' . $creation_date;?></div>
            </div>
            <?php
    }
    }
    ?>
</div>
</div>