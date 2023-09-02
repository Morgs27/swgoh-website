<?php
ob_start();
session_start();
include_once 'header.php';
include 'classes/character_class.php';
include 'functions/class_init.php';

check_not_logged_in();

?>

<link rel="stylesheet" type="text/css" href="styles/join_guild.css"/>

<div class="team_builder_bar" style = 'position:relative'>

<img src="images/farm.png" alt="" >
<div class="title">Use Website As Guest</div>
                                                 
</div>

<?php 

if (isset($_GET['code'])){

    $users = array(
        array("username" => "Guest_Info__","ally_code" => "741324657"),
        array("username" => "Guest_Info___","ally_code" => "939729166"),
        array("username" => "Guest_Info____","ally_code" => "882145491"),
        array("username" => "Guest_Info_____","ally_code" => "616485783"),
        array("username" => "Guest_Info______","ally_code" => "644744399"),
        array("username" => "Guest_Info_______","ally_code" => "479461123"),
        array("username" => "Guest_Info________","ally_code" => "771957566"),
        array("username" => "Guest_Info_________","ally_code" => "528234451")
    );

    $code = $_GET['code'];
 

    $sql = "SELECT * FROM guests WHERE guest_id = $code";
    $result = $conn->query($sql);
    while($data = $result->fetch_assoc()){
        $allycode = $data['ally_code'];
    }

    foreach($users as $user){
        if ($user['ally_code'] == $allycode){
            $guest_acc_name = $user['username'];
        }
    } 

    $_SESSION['guest'] = $code;
    $_SESSION['Username'] = $guest_acc_name;
    $user_info = getPlayerInfo_new($guest_acc_name,$conn);
    $ship_info = getPlayerInfo_ship($guest_acc_name,$conn);
    $_SESSION['user_info_ship'] = $ship_info;
    $_SESSION['user_info_class'] = $user_info;

    header("location:profile.php?" . $guest_acc_name);
	
}
else {
?>
<div class="guest_title">
  Select a roster from bellow to get started.
</div>

 <div class="spinner" style = 'display: none;position:absolute;top:0;left:50%;right:0;bottom:0;margin:0 auto;padding:0;transform:translateX(-50%);z-index:10;text-align:center;'>
        <div class="spinner_container">
        <div class="spinner_circle"></div>
        <div class="spinner_circle outer_1"></div>
        <div class="spinner_circle outer_2"></div>
        <div class="spinner_circle plannet_1"></div>
        <div class="spinner_circle plannet_2"></div>
        </div>
        <div id = 'text' style = 'text-align:center'> Setting Up Guest Account... </div>
    </div>

<div class="guest_container">

    <div class="profiles">
    <div id = '741324657' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/ahnald.jpg" style = 'transform: translateX(-20px)' alt="">
        </div>
        <div class="guest_name">Ahnald T101</div>
    </div>

    <div id = '939729166' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/bit_dynasty.jpg" style = 'transform: translateX(-20px)' alt="">
        </div>
        <div class="guest_name">Bit Dynasty</div>
    </div>

    <div id = '882145491' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/cubsfanhan.png" style = 'transform: translateX(-20px)' alt="">
        </div>
        <div class="guest_name">CubsFan Han</div>
    </div>

    <div id = '616485783' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/ap_gains.png" style = 'transform: translateX(-20px)' alt="">
        </div>
        <div class="guest_name">AP Gains</div>
    </div>

    <div id = '644744399' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/hynsey.png" style = 'transform: translateX(-40px)' alt="">
        </div>
        <div class="guest_name">Hynesy</div>
    </div>

    <div id = '528234451' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/xaereth.png" style = 'transform: translateX(-50px)' alt="">
        </div>
        <div class="guest_name">Xaereth Prevails</div>
    </div>

    <div id = '479461123' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/ian.png" style = 'transform: translateX(-20px)' alt="">
        </div>
        <div class="guest_name">Its Just Ian</div>
    </div>

    <div id = '771957566' class="guest_profile" onclick = 'set_guest(this.id)'>
        <div class="guest_img">
            <img src="images/gridan.jpg" style = 'transform: translateX(-30px)' alt="">
        </div>
        <div class="guest_name">Gridan</div>
    </div>
</div>
   
   
    
    
</div>
<?php
}
?>

 


<script>
    function set_guest(id){
        element = document.getElementById(id);
        element.style.filter = "brightness(130%)";
        loading = document.querySelector(".spinner");
        profiles = document.querySelector(".profiles");
        profiles.classList.add("hide");
        loading.style.display = "flex";
        let text = document.getElementById("text");
        $.ajax({
            url: "includes/create_guest.inc.php",
            method: "POST",   
            data: {allycode:id},
            success: function(data){
                console.log(data);
                guest_code = data;
                setTimeout(() => {
                    window.location.href = "use_as_guest.php?code=" + guest_code;
                }, 2000);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }

</script>
