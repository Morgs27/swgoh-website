<?php
session_start();
ob_start();
include 'header.php';

check_logged_in();

$in_guild = check_in_guild($conn,$_SESSION['Username']);

print_r($_SESSION['user_info_class'])

if (isset($_GET['to_notify'])){
	?>
	<div class="pro_notify_popup">
		<div class="close_plan" style = "opacity: 1;" onclick = "window.location.href = 'team_builder.php'"></div>
		<div class="pnp_content">
			<?php
			$username = $_SESSION['Username'];
			$sql = "SELECT * FROM users WHERE Username = '$username'";
			$result = $conn->query($sql);
			while($data = $result->fetch_assoc()){
				if ($data['payed_by'] != NULL){
					echo "<div class = 'pnp_name_text'> " . $data['payed_by'] . "</div>";
					echo "<div class = 'pnp_text'> Has kindly subscribed on your behalf to grant you the use of <a href = 'pro.php' class = 'link_pro_features'>pro features</a></div>";
				}
				else {
					echo "<div style = 'font-size: 20px;' class = 'pnp_name_text'>Thank you for signing up for pro version!</div>";
					echo "<div class = 'pnp_text'>View the features that are now available to you <a href = 'pro.php' class = 'link_pro_features'>here!</a></div>";
				}
			}
			$sql = "UPDATE users SET to_notify = '' WHERE Username = '$username'";
			$result = $conn->query($sql);
			
			?>
		</div>
	</div>
	<?php
}
else{

?>

<link rel="stylesheet" type="text/css" href="styles/teams_style.css"/>

<script>

    function toggleoptions(id){
        let element = document.getElementById(id);
        element.classList.toggle("active");
        
    }

    function toggle_option(id){
        let element = document.getElementById(id);
        element.classList.toggle("active");
    }


</script>




<div class="content_container">


<div id = "basic" class="options">
    <div class="options_watermark ga"><img src = 'images/favicon.png'></div>

    <div class="theoryTitle">
        <div class="titletext">Territory War </div>
        <div class="toggle_arrow" onclick = "toggleoptions('basic')">
            <i class="material-icons">arrow_upward</i>
        </div>
        

    </div>

    <div class="description">Build your TW squads | Construct loadouts with your guilds teams.</div>


    <div  class="toggle_container">
        <div id = "tw1" class="option_container" onclick = "window.location.href = 'team_manager.php?characters&tw'" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            

            <img src = "images/tw_character_battle.jpg" style = "transform: translateX(0%) scale(1.3)">

            <div class="options_title">Character </br> Squads
                
            </div>

            
        </div>

        <div id = "tw2" class="option_container"  onclick = "window.location.href = 'team_manager.php?ships&tw'" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <img src = "images/ship_battle.png" style = " transform:translateX(0)">
            <div class="options_title">Ship </br> Squads

            </div>
  
        </div>

        <?php 
        if ($in_guild == 'false'){
            ?>

            <div onclick = "window.location.href = 'tw_loadout_manager.php'" id = "b1" class="option_container full_width" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
                <img src = "images/tw_map_2.jpg" style = "height: 740%;width: 100%;">
                <div class = "full_width_title">Build a TW Loadout

                </div>
            
             </div>
            <?php
        }
        else {
            ?>
        <div onclick = "window.location.href = 'tw_loadout_manager.php'" id = "b1" class="option_container full_width" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <img src = "images/tw_map_2.jpg" style = "height: 740%;width: 100%;">
            <div class = "full_width_title">Build a TW Loadout

            </div>
            
        </div>

        <?php 
        }
        ?>
      
    </div>

</div>



<div id = "theory" class="options">
    <div class="options_watermark tc"><i class="fa-solid fa-pen-ruler"></i></div>

    <div class="theoryTitle">
        <div class="titletext">Theorycrafting</div>
        <div class="toggle_arrow" onclick = "toggleoptions('theory')">
            <i class="material-icons">arrow_upward</i>
        </div>
    </div>

    <div class="description">Plan out your future teams and gear.</div>


    <div  class="toggle_container">
        <div onclick = "window.location.href = 'team_manager.php?characters&tc'" id = "tw5" class="option_container" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <img src = "images/character_battle_2.jpg" style = "transform:  scale(1.4);">
            <div class="options_title">Character </br> Squads
        </div>
           
        </div>

        <div onclick = "window.location.href = 'team_manager.php?ships&tc'" id = "tw6" class="option_container" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <img src = "images/ship_battle_3.png" style = "transform: translateX(0%) scale(1.1);">
            <div class="options_title">Ship </br> Squads

            </div>
           
        </div>

        <div id = "b3" onclick = "window.location.href = 'farming_manager.php'" class="option_container full_width" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <img src = "images/farm.png" style = "height: 300%;width: 100%;">
            <div class = "full_width_title">Build a Farming Plan

            </div>
            
        </div>
    </div>

</div>

 
<div id = "ga" class="options">
    <div class="options_watermark ga"><img src = 'images/sabre.png'></div>

    <div class="theoryTitle">
        <div class="titletext">Grand Arena
            
        </div>
        <div class="toggle_arrow" onclick = "toggleoptions('ga')">
            <i class="material-icons">arrow_upward</i>
        </div>
    </div>

    <div class="description">Build your GA squads | Build loadout plans with your teams.</div>


    <div  class="toggle_container">
        <div onclick = "window.location.href = 'team_manager.php?characters&ga'" id = "tw3" class="option_container" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <img src = "images/character_battle.png" style = "transform: translateX(0)">

            <div class="options_title">Character </br> Squads
            </div>
            
        </div>

        <div onclick = "window.location.href = 'team_manager.php?ships&ga'" id = "tw4" class="option_container" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <!-- <img src = "images/ship1.jpg" style = "transform: translateX(0);"> -->
            <img src = "images/th.jpg" style = "transform: translateX(0) scale(1.2);">

            <div class="options_title">Ship </br> Squads

            </div>
            
        </div>

        <div onclick = "window.location.href = 'ga_loadouts.php'" id = "b2" class="option_container full_width" onmouseover = "toggle_option(this.id)" onmouseout = "toggle_option(this.id)">
            <img src = "images/ga_map.jpg" style = "height: 500%;width: 100%;">
            <div class = "full_width_title">Build a GA Loadout

            </div>
            
        </div>

    </div>

</div>


</div>

<script>
    let width = screen.width;
    if (width < 740){
        
        // let options = document.getElementsByClassName("options");
        // for (let i = 0; i < options.length; i++){
        //     console.log("here");
        //     options[i].classList.toggle("active");
            
        // }
        document.querySelector(".info_container").style.display = "block";
        document.querySelector(".content_container").style.height = "calc((var(--vh, 1vh) * 100) - 220px);"

    }
</script>

<?php
}