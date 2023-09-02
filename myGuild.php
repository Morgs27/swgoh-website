<?php
session_start();
ob_start();

include_once 'header.php';

check_logged_guild($conn);

$username = $_SESSION['Username'];
$viewer_rank = get_rank($conn,$username);

if (isset($_SESSION['guest'])){
    $guest_id = $_SESSION['guest'];
    $sql  = "SELECT * FROM guests WHERE guest_id = '$guest_id'";
    $result = $conn->query($sql);
    while($data = $result->fetch_assoc()){
        $guild_id = $data['guest_guild'];
        $guest_name = $data['guest_name'];
    }

    $guest_users = array();

    $sql = "SELECT * FROM users WHERE username IN (
    'Guest_Info__',
    'Guest_Info___',
    'Guest_Info____',
    'Guest_Info_____',
    'Guest_Info______',
    'Guest_Info_______',
    'Guest_Info________',
    'Guest_Info_________'
    )";
    $result = $conn->query($sql);
    while($data = $result->fetch_assoc()){
        $guest_user = array(
            $data['Username'],
            $data['guild_rank'],
            $data['ally_code'],
            $data['gp'],
            $data['portrait_image'],
            $data['char_gp'],
            $data['ship_gp'],
            $data['league_img'],
            $data['division_img']
        );
        array_push($guest_users,$guest_user);
    }

    $sql = "SELECT * FROM guests WHERE guest_guild = '$guild_id'";
    $result = $conn->query($sql);
    $guests = array();
    while($data = $result->fetch_assoc()){
        $guest = array($data['guest_id'],$data['ally_code']);
        array_push($guests,$guest);
    }
    $guild_name = "Guest Guild";

}
else {
    $guild_id = get_guild($conn,$username);
    $guild_code = get_guild_code($conn,$guild_id);
    $guild_name = get_guild_name($conn,$guild_id);

    $users = array();

    $sql = "SELECT * FROM users WHERE guild_id = '$guild_id'
    ORDER BY CASE WHEN guild_rank = 'leader' THEN '1'
              WHEN guild_rank = 'officer' THEN '2'
              ELSE '3'
              END ASC, gp DESC";
    $result = $conn->query($sql);
    while($data = $result->fetch_assoc()){
        $user = array(
            $data['Username'],
            $data['guild_rank'],
            $data['ally_code'],
            $data['gp'],
            $data['portrait_image'],
            $data['char_gp'],
            $data['ship_gp'],
            $data['league_img'],
            $data['division_img']
        );
        array_push($users,$user);
    }
    $guild_count = count($users);

}

?>

<link rel="stylesheet" href="styles/my_guild.css" />

<script src='script/my_guild.js'></script>

<div class="team_builder_bar" style = 'position:relative'>


<img src="images/tw_map_2.jpg" alt="" >
<div class="title"><?php echo $guild_name;?></div>

<!-- <div class="settings" style = 'position:absolute;left: 15px;' >
<i class="fa-solid fa-code"></i>
</div> -->

<?php
if (!isset($_SESSION['guest'])){
    ?>
<div class="settings" style = 'position:absolute;right: 15px;' onclick = 'toggle_leave()' >
    <i class="fa-solid fa-arrow-right-from-bracket"></i>
</div>
    <?php
}
?>
                                                 
</div>

<div class="confirmation_modal">
    <div class="close" onclick = 'this.parentElement.classList.remove("active");document.querySelector(".page_content").classList.remove("hide")'></div>
    <div class="important" style = 'color: #be7e7e'><i class="fa-solid fa-circle-exclamation"></i></div>
    <div class="msg">Are you sure you would like to leave <?php echo $guild_name;?></div>
    <div class="continue" onclick = "leave_guild('<?php echo $username;?>','<?php echo $guild_id?>')" >Leave Guild <i class="fa-solid fa-arrow-right"></i></div>
</div>

<div class="confirmation_modal rank_modal">
    <div class="close" onclick = 'this.parentElement.classList.remove("active");document.querySelector(".page_content").classList.remove("hide")'></div>
    <div class="important" ><i class="fa-solid fa-circle-exclamation"></i></div>
    <div class="msg">Are you sure you would like to leave <?php echo $guild_name;?></div>
    <div class="continue">Continue<i class="fa-solid fa-arrow-right"></i></div>
</div>

<div class="page_content">

<!-- <div class="guild_footer">
<img src="images/tw_map_2.jpg" alt="" >
</div> -->

<?php
if (isset($_SESSION['guest'])){
    ?>
    <style>
        .guild_options{
            pointer-events: none;
            opacity: 0.3;
        }
    </style>
    <?php
}
?>
<div class="seperator"></div>

<div class="guild_options">
    <div class="guild_option" onclick = 'window.location.href="myGuild_progress.php"'>
        <!-- <img src="images/farm.png" alt=""> -->
        <div class="guild_option_background"></div>
        <div class="option_text">Progress</div>
    </div>
    <?php 
        $active_loadout = get_active_loadout_id($conn,$guild_id);

        if ($active_loadout !== 'none'){
            ?>
            <div class="guild_option pulse" onclick = 'window.location.href="myGuild_loadouts.php"'>
                <!-- <div class="img_container"><img src="images/tw_background.jpeg" style = 'transform:scale(1.5)' alt=""></div> -->
                <div class="guild_option_background"></div>
                <div class="option_text">Loadouts</div>
            </div>
            <?php
        }
        else {
            ?>
            <div class="guild_option" onclick = 'window.location.href="myGuild_loadouts.php"'>
                <!-- <img src="images/tw_background.jpeg" style = 'transform:scale(1.5)' alt=""> -->
                <div class="guild_option_background"></div>
                <div class="option_text">Loadouts</div>
            </div>
            <?php
        }
        ?>
    <div class="guild_option" onclick = 'window.location.href="myGuild_info.php"'>
        <!-- <img src="images/tactical_droid.webp" style = 'transform:translateY(5px);'  alt=""> -->
        <div class="guild_option_background"></div>
        <div class="option_text">Info</div>
    </div>
</div>

<div class="seperator"></div>
    <div style = 'width: 100%; text-align: center; margin: 20px auto;color: rgba(255,255,255,0.8)'>Guild Code <i style = 'margin: 0 10px' class = "fa-solid fa-code"></i> <?php echo $guild_code; ?></div>
<div class = "seperator"></div>

<div class="guild_users_container">
<?php

if (isset($_SESSION['guest'])){
    foreach($guests as $guest){
        $guest_name =  "Guest_" . $guest[0];
        $ally_code = $guest[1];
        
        foreach($guest_users as $user){
            if ($user[2] == $ally_code){
                $username = $user[0];
                $profile_img = $user[4];
                $guild_rank = $user[1];
                $ally_code = $user[2];
                $ally_code_str = substr($ally_code,0,3) . "-" . substr($ally_code,3,3) . "-" .substr($ally_code,6,3);
                $gp = $user[3];
                $char_gp = $user[5];
                $ship_gp = $user[6];
                $league_img = $user[7];
                $division_img = $user[8];
                break;
            } 
        }

        ?>
        <div class="user_row" onclick = 'window.location.href="profile.php?<?php echo $username;?>&id=<?php echo $guest[0];?>"'>
            <div class="holder"></div>
            <div class="user_rank">
                <?php
                if ($guild_rank == "leader"){
                    echo '<i class="fa-solid fa-crown"></i>';
                }
                else if ($guild_rank == "officer"){
                    echo '<div class = "user_rank_img" ><img src = "images/officer.png" alt = "bla" ></img></div>';
                }
                else {
                    echo '<i style = "font-size: 18px" class="fa-solid fa-user"></i>';
                }
                ?>
            </div>
            <div class="user_img">
                <img src="<?php echo $profile_img;?>" alt="">
            </div>
            <div class="user_name">
                <div class="name"><?php echo $guest_name;?></div>
                <div class="code"><?php echo $ally_code_str;?></div>
            </div>
            <div class="user_power">
                <i class="fa-solid fa-users"></i>
                <div class="number"><?php echo number_format($gp);?></div>
            </div>
            <div  class="user_power char">
                <i class="fa-solid fa-user"></i>
                <div class="number"><?php echo number_format($char_gp);?></div>
            </div>
            <div class="user_power ship">
                <i class="fa-solid fa-jet-fighter"></i>
                <div class="number"><?php echo number_format($ship_gp);?></div>
            </div>
            <div class="user_ga_rank">
                <img src="<?php echo $league_img;?>" alt="">
                <img src="<?php echo $division_img;?>" alt="">
            </div>
           
        </div>
        <?php
    }
}
else {
    foreach($users as $user){
            $username = $user[0];
            $profile_img = $user[4];
            $guild_rank = $user[1];
            $ally_code = $user[2];
            $ally_code_str = substr($ally_code,0,3) . "-" . substr($ally_code,3,3) . "-" .substr($ally_code,6,3);
            $gp = $user[3];
            $char_gp = $user[5];
            $ship_gp = $user[6];
            $league_img = $user[7];
            $division_img = $user[8];
        ?>
        <div class="user_row" onclick = 'follow_profile(event,"<?php echo $username;?>")' >
            <?php 
            if (($viewer_rank == "leader" && $guild_rank != "leader") || ($viewer_rank == "officer" && $guild_rank == "member")){
            ?>
            <div class="user_promotion_options">
                <div onclick = 'change_rank("up","<?php echo $username;?>","<?php echo $guild_rank;?>")' class="p_option"><i class="fa-solid fa-angles-up"></i></div>
                <div onclick = 'change_rank("down","<?php echo $username;?>","<?php echo $guild_rank;?>")' class="p_option"><i class="fa-solid fa-angles-down"></i></div>
            </div>
            <?php
            }
            ?>
            <div class="holder"></div>
            <div class="user_rank">
                <?php
                if ($guild_rank == "leader"){
                    echo '<i class="fa-solid fa-crown"></i>';
                }
                else if ($guild_rank == "officer"){
                    echo '<div class = "user_rank_img" ><img src = "images/officer.png" alt = "bla" ></img></div>';
                }
                else {
                    echo '<i style = "font-size: 18px" class="fa-solid fa-user"></i>';
                }
                ?>
            </div>
            <div class="user_img">
                <img src="<?php echo $profile_img;?>" alt="">
            </div>
            <div class="user_name">
                <div class="name"><?php echo $username;?></div>
                <div class="code"><?php echo $ally_code_str;?></div>
            </div>
            <div class="user_power">
                <i class="fa-solid fa-users"></i>
                <div class="number"><?php echo number_format($gp);?></div>
            </div>
            <div  class="user_power char">
                <i class="fa-solid fa-user"></i>
                <div class="number"><?php echo number_format($char_gp);?></div>
            </div>
            <div class="user_power ship">
                <i class="fa-solid fa-jet-fighter"></i>
                <div class="number"><?php echo number_format($ship_gp);?></div>
            </div>
            <div class="user_ga_rank">
                <img src="<?php echo $league_img;?>" alt="">
                <img src="<?php echo $division_img;?>" alt="">
            </div>

            
           
        </div>
        <?php
    }
}

?>
</div>
</div>
