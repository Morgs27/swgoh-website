<?php 
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';

ob_start();
session_start();
include_once "header.php";

check_logged_guild($conn);
check_get_variable("i");

$guild_id = get_guild($conn,$_SESSION['Username']);
$loadout_id = $_GET['i'];

if (filter_var($loadout_id, FILTER_VALIDATE_INT) === false){
	?>
	<script>
		window.location.href = "error.php?twe";
	</script>
	<?php
}

$territories = array("T1","T2","T3","T4","B1","B2","B3","B4","F1","F2");

$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";

$result = $conn->query($sql);

$username = $_SESSION['Username'];

check_rows($result);

while ($data = $result->fetch_assoc()){
    $T1 = $data['T1']; $T1 = explode(",",$T1);
    $T2 = $data['T2']; $T2 = explode(",",$T2);
    $T3 = $data['T3']; $T3 = explode(",",$T3);
    $T4 = $data['T4']; $T4 = explode(",",$T4);
    $B1 = $data['B1']; $B1 = explode(",",$B1);
    $B2 = $data['B2']; $B2 = explode(",",$B2);
    $B3 = $data['B3']; $B3 = explode(",",$B3);
    $B4 = $data['B4']; $B4 = explode(",",$B4);
    $F1 = $data['F1']; $F1 = explode(",",$F1);
    $F2 = $data['F2']; $F2 = explode(",",$F2);
    $loadout_name = $data['loadout_name'];

}
$character_teams = array_merge($T1,$T2,$T3,$T4,$B1,$B2,$B3,$B4);
$ship_teams = array_merge($F1,$F2);


$viewer_username = $_SESSION['Username'];
?>

<!-- <link rel="stylesheet" type="text/css" href="styles/farming_manager.css"/>
<link rel="stylesheet" type="text/css" href="styles/tw_loadout_view.css"/> -->

<script>
window.viewer_username = '<?php echo $viewer_username;?>'
function check_loadout(list,id){
    
    username = window.viewer_username;
    console.log(username);
    const container = document.querySelector('[team_id="' + id + '"]');
    
    team_username = container.parentElement.querySelector(".team_title_text").innerHTML;
    console.log(container.parentElement)
    console.log(container.parentElement.querySelector(".team_title_text"))
    console.log(team_username)
    if (username != team_username){
        return;
    }
    console.log("here!");
    



    if (list.contains("checked")){
        container.classList.remove("checked");
        checked = 'false';
    }
    else {
        container.classList.add("checked");
        checked = 'true';
    }

    if (list.contains("team_container_ships")){
        type = "ships";
    }
    else {
        type = "characters";
    }

    $.ajax({
        url: "includes/update_team_checked.inc.php",
        method: "POST",   
        data: {team_id:id,checked:checked,type:type,username:username},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
    // Ajax
}
</script>

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
    window.username = '<?php echo $username?>'
</script>

<div class="team_builder_bar" style = 'position:relative'>
<img src="images/tw_map_2.jpg" alt="" >
<div class="title">Assignments Sorted By User</div>

<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='view_loadout.php?i=<?php echo $loadout_id;?>'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>

<div onclick = 'hide_profiles()' class="tw_settings export" style = 'position:absolute;right: 5px;display: flex;justify-content: center;align-items:center' >
    <div>
        <div class = 'export_open_text'>Hide </div> <i style = 'transform:translate(0px);' class="fa-solid fa-image-portrait"></i></i>
    </div>
</div>

</div>

<?php

?>

<div class="guild_characters_search_container" style = 'margin-bottom: 10px;'>
<div class="guild_characters_search">
	<input style = 'transform: none' type="text" id="search_user"  onkeyup="search_user()" placeholder="Search for a User..." >

</div>
<!-- <div class="show_unique_characters" onclick = 'hide_profiles()'>
Hide Profiles
</div> -->
</div>


<script>
function search_user(){
    let filter = document.querySelector("#search_user").value;
    let containers = document.getElementsByClassName("unchecked_territory_container");
    for (var i = 0; i < containers.length; i++) {
        username = containers[i].querySelector(".team_title_text").innerHTML;
        if (username.toUpperCase().indexOf(filter.toUpperCase()) > -1){
            containers[i].style.display = "";
        }
        else {
            containers[i].style.display = "none";
        }
    }
}
    function hide_profiles(){
       if (window.profiles === "show"){
               window.profiles = "hidden";
           }
           else {
           window.profiles = "show";
           }
       let profiles = document.getElementsByClassName("character_profile_full");
       let containers = document.getElementsByClassName("team_container");
       let gps = document.getElementsByClassName("team_info_gp");
       let name = document.getElementsByClassName("team_info_territory");

       if ( document.getElementsByClassName("export_open_text")[0].innerHTML === "Show"){
        for (var i = 0; i < profiles.length; i++) {
           profiles[i].style.display = "";
       }
       for (var i = 0; i < containers.length; i++) {
         containers[i].style.height = "";
       }
       for (var i = 0; i < gps.length; i++) {
         gps[i].style.display = "";
       }
       for (var i = 0; i < name.length; i++) {
         name[i].style.border = "";
         name[i].style.float = "";
         name[i].style.textAlign = '';
       }
       document.getElementsByClassName("export_open_text")[0].innerHTML = "Hide";
       
       }
       else{
       for (var i = 0; i < profiles.length; i++) {
           profiles[i].style.display = "none";
       }
       for (var i = 0; i < containers.length; i++) {
         containers[i].style.height = "30px";
       }
       for (var i = 0; i < gps.length; i++) {
         gps[i].style.display = "none";
       }
       for (var i = 0; i < name.length; i++) {
         name[i].style.border = "none";
         name[i].style.float = "none";
         name[i].style.textAlign = 'center';
       }
       document.getElementsByClassName("export_open_text")[0].innerHTML = "Show";
       }
    }
</script>


<div class="teams unchecked">
<?php



$in_guild = check_in_guild($conn,$_SESSION['Username']);
if ($in_guild == "false"){
  $users = array($_SESSION['Username']);
}
else {
  $guild_id = get_guild($conn,$_SESSION["Username"]);
  $users = get_usernames_in_guild($guild_id,$conn);
}

$active_loadout = get_active_loadout_id($conn,$guild_id);

if ($active_loadout == $loadout_id){
	$active = true;
}
else {
	$active = false;
}

foreach ($users as $user){
    unchecked_territory_user($conn,$user,$character_teams,$ship_teams,$loadout_id,false,$_SESSION['guild_data'],$active);
}


?>
</div>













