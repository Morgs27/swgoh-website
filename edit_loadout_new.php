<?php 
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';
session_start();
ob_start();

include_once "header.php";

check_logged_in();
check_get_variable("i");

$loadout_id = $_GET["i"];

if (filter_var($loadout_id, FILTER_VALIDATE_INT) === false){
	?>
	<script>
		window.location.href = "tw_loadout_manager.php";
	</script>
	<?php
}

echo "<script src = 'script/edit_loadout.js'></script>";

check_right_guild($conn,$_SESSION['Username'],$loadout_id);

$loadout_name = get_active_loadout_name($conn,$loadout_id);
$territories = array("T1","T2","T3","T4","B1","B2","B3","B4","F1","F2");
$in_guild = check_in_guild($conn,$_SESSION['Username']);

if (isset($_SESSION['guest'])){
    $guest_id = $_SESSION['guest'];
    $sql  = "SELECT * FROM guests WHERE guest_id = '$guest_id'";
    $result = $conn->query($sql);
    while($data = $result->fetch_assoc()){
        $guild_id = $data['guest_guild'];
        $guest_name = $data['guest_name'];
    }

    $sql = "SELECT * FROM guests WHERE guest_guild = '$guild_id'";
    $result = $conn->query($sql);
    $usernames = array();
    $ids = array();
    $guests = array();
    while($data = $result->fetch_assoc()){
        $guest = array($data['guest_name'],$data['guest_username']);
        array_push($guests,$guest);
        array_push($ids,$data['guest_id']);
        array_push($usernames,$data['guest_name']);
    }
    $userStr = implode("', '", $usernames); 
    $IdsStr = implode("', '", $ids); 
}
else {
if ($in_guild == "false"){
    $usernames = array($_SESSION['Username']);
    $userStr = $_SESSION['Username'];
}
else {
$guild = get_guild($conn,$_SESSION["Username"]);
$usernames = get_usernames_in_guild($guild,$conn);

$userStr = implode("', '", $usernames);
}
}

?>
<link rel="stylesheet" type="text/css" href="styles/team_manager_style.css"/>

<link rel="stylesheet" type="text/css" href="styles/tw_loadout_view.css"/>



<script>
 
    let territories = ["T1","T2","T3","T4","B1","B2","B3","B4","F1","F2"];
    window.territory = 'T1';
    const two_d_array = {};
    const numbers = {};
    const add = {};
    const add_special = {};
    for (var i = 0; i < territories.length; i++) {
        two_d_array[territories[i]] = [];
        numbers[territories[i]] = 0;
        add[territories[i]] = [];
        add_special[territories[i]] = [];
    }
    window.add_values = add;
    window.add_values_special = add_special;
	window.remove_values = two_d_array;
    window.number = numbers;
    window.active_territory = 'T1';
    window.hidden_search = [];
   

    function clear_territory(){
        console.log("clear_territory->")
        let territory = window.active_territory;
        let container = document.getElementById("in_territory");
        let teams = container.getElementsByClassName(territory);
        var length = teams.length;
        let team_ids = [];
        for (var i = 0; i < length; i++) {
            team_ids.push(teams[i].id);
            console.log(teams[i].id);
            console.log(teams[i].className);
        }

        for (var i = 0; i < length; i++) {
            var element_id = team_ids[i];
            console.log(element_id);
            var element = document.getElementById(element_id);
            if (element.style.display !== "none"){
                var onclick = element.onclick;
                onclick = onclick.toString();
                onclick = onclick.replace("function onclick(event) {","");
                onclick = onclick.replace("function onclick(event){","");
                onclick = onclick.replace("}","");
                onclick = onclick.replace(" ","");
                onclick = onclick.replace(/[\r\n]/gm, '');

                if (onclick === "move_team_loadout(this.id)"){
                    move_team_loadout(element_id);
                }
                else if(onclick === "remove(this.id)"){
                    remove(element_id);
                }
                else if(onclick === "move_team_loadout_left(this.id)"){
                    move_team_loadout_left(element_id);
                }

            }
        }
    }

    function filter_teams_territory(){
        console.log("filter_teams_territory")
        let territory = window.active_territory;
        let container = document.getElementById("in_territory");
        let container_right = document.getElementById("options");
        let teams_not_in = container.getElementsByClassName("team_container");
        let teams = container.getElementsByClassName(territory);
        for (var i = 0; i < teams_not_in.length; i++) {
            teams_not_in[i].style.display = "none";
        }
       
        for (var i = 0; i < teams.length; i++) {
            teams[i].style.display = "";
            console.log(teams[i]);
        }
        if (territory === 'F1' || territory ==='F2'){
           document.getElementById("eln_character_option_container").style.display = "none";
           document.getElementById("eln_ship_option_container").style.display = "";

        }
        else{
            document.getElementById("eln_ship_option_container").style.display = "none";
            document.getElementById("eln_character_option_container").style.display = "";

        }
        hide_excluded();
    }

    function search_character(){
        
        let territory = window.active_territory;
        
		let filter = document.getElementById("search_character_input_tb").value.toUpperCase();
		
		console.log("search_character->" + filter)
		
		let container = document.getElementById("options");
		
		console.log(territory);
		
		var team_container;
		
		if (territory == 'F1' || territory =='F2'){
		    team_container = container.querySelector('#eln_ship_option_container');
 
		}
		else {
		    team_container = container.querySelector('#eln_character_option_container');
		}
		
		console.log(team_container);
		
		let teams = team_container.getElementsByClassName("team_container");
		
		for (i = 0; i < teams.length; i++) {
		    
            let team = teams[i];
            
            let username = team.querySelector(".username").innerHTML.toUpperCase();
            
            let team_id = team.id;
            
            var show = 'false';
            
            if (username.indexOf(filter) > -1){
                    show = 'true';
            }
            
            let names = team.getElementsByClassName("character_name");
            for (x = 0; x < names.length; x++) {
                if (names[x].innerHTML.toUpperCase().indexOf(filter) > -1){
                    show = 'true';
                }
            }
            
            if (territory == 'F1' || territory =='F2'){
                var factions_contained = "";
                var ship_categories = team.getElementsByClassName("categories");
                for (s = 0; s < ship_categories.length; s++) {
                   factions_contained = factions_contained + ship_categories[s].innerHTML;
                }
                         console.log("here2");
            }
            else {
                if (team.getElementsByClassName("factions_contained")[0] = null){return;}
                var factions_contained = team.getElementsByClassName("factions_contained")[0].innerHTML;
                         console.log("here3");
            }  
            
            if (factions_contained.toUpperCase().indexOf(filter) > -1){
                show = 'true';
            }
            if (show === 'true'){
                if (document.getElementById(team_id).style.display === "none"){
                    let teams_hidden =  window.hidden_search;
                    for (y = 0; y < teams_hidden.length; y++) {
                        if (team_id === teams_hidden[y]){
                            document.getElementById(team_id).style.display = "";
                            teams_hidden.splice(y,1);
                            window.teams_hidden = teams_hidden;
                        }
                    }
                }
                else {
                    document.getElementById(team_id).style.display = "";
                }
               
            }	
            else {
                if (document.getElementById(team_id).style.display === "none"){

                }
                else {
                    document.getElementById(team_id).style.display = "none";
                    window.hidden_search.push(team_id);
                }
            }

	    }
        hide_excluded();
    }

    function save_changes(){
        console.log("save changes->")

        var loadout_id = <?php echo $loadout_id;?>;
        loadout_id = JSON.stringify(loadout_id);
        var add_values = JSON.stringify(window.add_values);
        var add_values_special = JSON.stringify(window.add_values_special);
        var remove_values = JSON.stringify(window.remove_values);
        console.log(add_values);
        console.log(add_values_special);
        console.log(remove_values);
        $.ajax({
            url: "includes/save_changes_tw_loadout.inc.php",
            method: "POST",   
            data: {lid: loadout_id, avs: add_values_special, av: add_values, rv: remove_values},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });

        
        let values = window.add_values;
        for (const territory in values){
            teams = values[territory];
            teams.forEach(team_id =>{
                div_id = "in_loadout_" + team_id;
                let new_id = "in_loadout_left_" + team_id;
                let outer_id = "outer_team_id_" + team_id;

                div = document.getElementById(div_id);

                // div.style.background = "Red";
                div.id = new_id

                let previous_div = document.getElementById(outer_id);
                let previous_outer = previous_div.parentElement;
                previous_outer.removeChild(previous_div);

                var parent = div.parentNode;
                var wrapper = document.createElement('div');
                wrapper.id = "outer_in_loadout_left_" + team_id;

                parent.replaceChild(wrapper, div);
                wrapper.appendChild(div);

                div.onclick = function(){
                    
                    remove("s_" + new_id);
                    // console.log("remove init_>" + div_id + "   " + new_id)
                }
                console.log(div.onclick)
            })
        }


        values = window.remove_values;

        special = window.add_values_special;

        for (const territory in values){
            teams = values[territory];
            teams.forEach(team_id =>{
                let is_special = true;
                for (const special_t in special){
                    special[special_t].forEach(special_id =>{
                        if (special_id === team_id){
                            is_special = false;
                        }
                    })
                }
                if (is_special === true){
                    div_id = "not_in_loadout_left_" + team_id;
                    let new_id = "team_id_" + team_id;
                    let outer_id = "outer_in_loadout_left_" + team_id;

                    let previous_div = document.getElementById(outer_id);
                    let previous_outer = previous_div.parentElement;
                    previous_outer.removeChild(previous_div);

                    div = document.getElementById(div_id);

                    // div.style.background = "green";
                    div.id = new_id
                    // div.clas

                    var parent = div.parentNode;
                    var wrapper = document.createElement('div');
                    wrapper.id = "outer_team_id_" + team_id;

                    parent.replaceChild(wrapper, div);
                    wrapper.appendChild(div);

                    div.onclick = function(){

                        add_team_loadout("s_" + new_id);
                        console.log(div_id)
                    }
                }
            })
        }
        
        console.log("here");

        let territories = ["T1","T2","T3","T4","B1","B2","B3","B4","F1","F2"];
        const two_d_array = {};
        const numbers = {};
        const add = {};
        const add_special = {};
        for (var i = 0; i < territories.length; i++) {
            two_d_array[territories[i]] = [];
            numbers[territories[i]] = 0;
            add[territories[i]] = [];
            add_special[territories[i]] = [];
        }
        window.add_values = add;
        window.add_values_special = add_special;
        window.remove_values = two_d_array;

        let message = document.querySelector(".save_error");
        message.style.display = "";
        window.changes = false;
		
        let save_btn = document.querySelector(".edit_loadout_save_btn");
        save_btn.classList.add("active");
        save_btn.innerHTML = "Save Sucesfull";
        setTimeout(() =>{
            save_btn.classList.remove("active");
            save_btn.innerHTML = "Save Changes";
        },2000)
    }


</script>

<style>
    .type_selector.no-show{
	    display: none !important;
    }   
</style>


<div class="team_builder_bar" style = "position: relative">

<img src="images/tw_map_2.jpg" alt="" >


<input id = "<?php echo $loadout_id;?>" onkeyup = "edit_title(this.id)" class="edit_name_input title" value = "<?php echo $loadout_name;?>"></input>


<div class="tw_settings" style = 'position:absolute;left: 5px;' >
        <i id = "arrow" onclick = "check_changes_saved()" class="material-icons">arrow_back</i>
</div>

<div class = "save_error"><div class="error_icon">!</div><div class="error_text">You have unsaved changes</div></div>

<?php
     if (isset($_SESSION['guest'])){
         ?>
        <div class="type_selector no-show" style = "display:none">
        <?php
     }
     else {
         ?>
          <div class="type_selector" style = "display:none">
         <?php
     }
    ?>
        <a style = "color:white;text-decoration:none;font-size: 20px" class = "type" href='view_loadout.php?i=<?php echo $loadout_id;?>'>
            <i title = "View Loadout" class="fa-regular fa-eye"></i>
        </a>   
        <div class="middle_bar"></div>
        <div onclick = 'show_exclude_users()' class = "type exclude_toggle">
            <i style = "transform:translate(0px)"  title = 'Exclude Users'  class="fa-solid fa-user-large-slash"></i>
        </div>     
    </div>

</div>

<div class="exclude_users_container"  style = 'display:none'>
    <div class="exclude_scroll_container">
        <?php

        $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
        $result = $conn->query($sql);
        while ($data = $result->fetch_assoc()){
            $excluded = explode(",",$data['exclude']);
        }

        foreach($usernames as $username){
            $show = true;
            foreach($excluded as $exclude){
                if ($exclude == $username){
                    $show = false;
                }
            }
            if ($show == true){
                echo "<div id = '$username' class = 'exclude_user' onclick = 'exclude_user(this.id)' >";
                echo $username;
                echo "</div>";
            }
            else {
                echo "<div style = 'opacity: 0.3' id = '$username' class = 'exclude_user' onclick = 'exclude_user(this.id)' >";
                echo $username;
                echo "</div>";
            }

        }
        $excluded_str = json_encode($excluded);
        ?>
    </div>
</div>

<div class = "tw_container_loading" style = 'display: flex;justify-content: center;align-items:center;flex-direction: column;'>
<div class="spinner_container">
    <div class="spinner_circle"></div>
    <div class="spinner_circle outer_1"></div>
    <div class="spinner_circle outer_2"></div>
    <div class="spinner_circle plannet_1"></div>
    <div class="spinner_circle plannet_2"></div>
</div>
Loading Teams...
</div>

	
<script>
    window.excluded = <?php echo $excluded_str; ?>;

    function confirm_delete_loadout(){
		document.querySelector('.modal').style.display = "flex";
	}
	function close_delete_loadout(){
		document.querySelector('.modal').style.display = "none";
	}
    function edit_loadout_name(){
		document.querySelector('.change_loadout_name').style.display = "flex";
	}
	function close_change_loadout_name(){
		document.querySelector('.change_loadout_name').style.display = "none";
	}

    function change_loadout_name(){
        console.log("change loadout name->")
        var new_name = document.getElementById('loadout_name_new').value;
        var loadout_id = <?php echo $loadout_id;?>;

        new_name = JSON.stringify(new_name);
        loadout_id = JSON.stringify(loadout_id);

        $.ajax({
            url: "change_loadout_name.php",
            method: "POST",   
            data: {loadout: loadout_id, name: new_name},
            success: function(data){console.log(data);},
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
        document.getElementById('name_loadout').innerHTML = new_name;
        close_change_loadout_name();
    }

    function show_exclude_users(){
        if (document.getElementsByClassName("exclude_users_container")[0].style.display == "none"){
        document.getElementsByClassName("exclude_users_container")[0].style.display = "flex";
        }
        else {
            document.getElementsByClassName("exclude_users_container")[0].style.display = "none";
        }
    }

    document.addEventListener(
        "click",
        function(event) {
        if (
        !event.target.closest(".exclude_toggle") &&
        !event.target.closest(".exclude_users_container")
        ) {
            hide_exclude_users()
        }
        },
        false
    )

    function hide_exclude_users(){
        document.getElementsByClassName("exclude_users_container")[0].style.display = "none";
    }

    function exclude_user (id){
        console.log("exclude_user->" + id)
        let username = id.toUpperCase().trim();
        let container = document.getElementById('options');
        let teams = container.getElementsByClassName('team_container');
        for (var i = 0; i < teams.length; i++) {
            team_username = teams[i].getElementsByClassName('username')[0].innerHTML;
            if (username == team_username.trim()){
                if (document.getElementById(id).style.opacity === "0.3"){
                    teams[i].classList.remove("exclude");
                }
                else {
                    teams[i].classList.add("exclude");
                }
            }
        }

        if (document.getElementById(id).style.opacity === "0.3"){
            document.getElementById(id).style.opacity = "1";
        }
        else {
            document.getElementById(id).style.opacity = "0.3";
        }
        let users = document.getElementsByClassName("exclude_user");
        var exclude = [];
        for (var i = 0; i < users.length; i++) {
            if (users[i].style.opacity === "0.3"){
                var id = users[i].id;
                exclude.push(id);
            }
        }
        var exclude_str = JSON.stringify(exclude);
        window.excluded = exclude_str;
        console.log(exclude_str);
        var id = JSON.stringify(<?php echo $loadout_id;?>);
        $.ajax({
            url: "includes/exclude.inc.php",
            method: "POST",   
            data: {loadout: id, remove: exclude_str},
            success: function(data){console.log(data);},
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
        search_character();
    }

    function hide_excluded(){
        console.log("hide_excluded")
        console.log(window.excluded)
    
        let excluded = window.excluded;

        
        let container = document.getElementById('options');
        let teams = container.getElementsByClassName('team_container');
        for (var i = 0; i < teams.length; i++) {
            username = teams[i].getElementsByClassName('username')[0].innerHTML;
            for (var x = 0; x < excluded.length; x++) {
                if (excluded[x].toUpperCase().trim() === username.trim()){
                    teams[i].classList.add("exclude");
                    console.log(username);
                }
            }
        }
    }
    
</script>


</div>

<div class="cant_use_page" >
        Sorry this page cannot be viewed at this small a screen width.
</br>
</div>



<div style = "display:none" class = 'edit_loadout_save_btn' onclick = 'save_changes()'>Save Changes</div>



<div class="territory_selector_container" style = "display:none">
<?php
    foreach ($territories as $territory){
        ?>
        <div class="territory_selector_section" id = '<?php echo $territory;?>' onclick = 'change_territory(this.id)'>
            <?php echo $territory;?>
        </div>
        <?php
    }
    ?>
    <div onclick = 'toggle_map()' class = "territory_selector_section map_selector"><i class="fa-solid fa-map"></i></div>

<script>
    document.getElementById('T1').style.background = 'rgba(255,255,255,0.1)';

    function change_territory (territory) {
        close_map();
        
        console.log("change_territory->" + territory)
        let char_territories = ["T1","T2","T3","T4","B1","B2","B3","B4"];
        let fleet_territories = ["F1","F2"];
        old_territory = window.active_territory;
        if ((char_territories.includes(territory) && fleet_territories.includes(old_territory)) || (char_territories.includes(old_territory) && fleet_territories.includes(territory))){
            document.getElementById("search_character_input_tb").value = "";

           
        }
        let elements = document.getElementsByClassName("territory_selector_section");
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.background = '';
        }
        document.getElementById(territory).style.background = 'rgba(255,255,255,0.1)';
        document.getElementsByClassName("team_title_text")[0].innerHTML = territory;
        window.active_territory = territory;


        var new_number = window.number[territory];
        document.getElementById("number_of_teams").innerHTML = new_number;
        
        filter_teams_territory();
        search_character();

        
        
    }
</script>

</div>

<div id = 'elnc' class="edit_loadout_new_content_container" style = 'display:none'>

<div class="toggled_map">
    <div class="toggled_map_inner">
    <div class="close" onclick = "close_map()"></div>
    <div class="tw_map_container">
        <div class="tw_map_circle">
        <div class="tw_map">

        <?php
        foreach ($territories as $territory){
           
            if ($territory == "F1" || $territory == "F2"){
                $icon = "<i class='fa-solid fa-jet-fighter'></i>";
            }
            else {
                $icon = "<i class='fa-solid fa-users'></i>";
            }
            
            echo "
            <div id = '$territory' onclick = 'change_territory(this.id)' class='tw_section $territory' style ='grid-area:$territory'>
                <p>$territory
                    <div class = 'after_tw_title view'>
                        $icon
                    </div>
                </p>
            </div>";
        }
        ?>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="edit_loadout_new_left">

<div class = 'edit_loadout_title_container_new'>
    <div class= "team_title_text">T1</div>
</div>

<div class="eln_filter_bar">
    <div id = 'number_of_teams' class="eln_number_total"></div>
    <div class="eln_clear_territory"><i title = 'Clear Territory' onclick = 'clear_territory()' class="material-icons">clear</i></div>
</div>

<div class="eln_team_container"  id = 'in_territory'>
    <?php
    if (isset($_SESSION['guest'])){
        $guest_possible_usernames = array(
            "GUEST_INFO__",
            "GUEST_INFO___",
            "GUEST_INFO____",
            "GUEST_INFO_____",
            "GUEST_INFO______",
            "GUEST_INFO_______",
            "GUEST_INFO________",
            "GUEST_INFO_________"
        );
    if (isset($_SESSION['guild_data']) && isset($_SESSION['guild_data']['GUEST_INFO_________'])){
        $guild_data = $_SESSION['guild_data'];
    }
    else {

    $guild_data = get_guild_unit_data($conn,$guest_possible_usernames);

    }
    }
    else {
    if (isset($_SESSION['guild_data'])){ 
        $guild_data = $_SESSION['guild_data'];
    }
    else {
        $guild_data = get_guild_unit_data($conn,$usernames);
        $_SESSION['guild_data'] = $guild_data;
    }
    }
    eln_teams_territories($loadout_id,$conn,$guild_data);

    
    ?>
 
</div>

</div>


<div class="edit_loadout_new_right">

<div class = 'edit_loadout_title_container_new'>
    <div class= "team_title_text">Options</div>
</div>

<div class="eln_filter_bar">
<div class="guild_characters_search" style = 'background:transparent'>
	<input style = 'background:transparent;border-bottom:1px solid rgba(255,255,255,0.6);' type="text" id="search_character_input_tb"  onkeyup="search_character()" placeholder="Search..." title="Type in a name, faction or category">
</div>
</div>

<div class="eln_team_container" id = 'options'>
    <?php
   
    if (isset($_SESSION['guest'])){
        eln_team_options($loadout_id,$conn,$IdsStr,$guild_data);

    }
    else {
        eln_team_options($loadout_id,$conn,$userStr,$guild_data);
    }
    ?>
</div>

</div>
</div>

<script>
    startup();
    change_territory(window.active_territory);
    // hide_excluded();
    // change_territory(window.active_territory);
  
</script>

