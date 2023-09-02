
<?php

function get_ally_code($conn,$username) {
    $sql = "SELECT * FROM users WHERE Username = '$username'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()) {
       $ally_code = $data['ally_code'];
   return $ally_code;
   }
}


function showClass($class,$conn,$current_team) {
   echo "<div class='class_text'>{$class}</div>";
   echo "<div class = 'class_container'>";
   echo "<div class='characterProfile'>";

   $sql = "SELECT * FROM characters WHERE class LIKE '%$class%' ORDER BY CharacterName ASC" ;
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()) {
       $in_team = false;
       foreach ($current_team as $x) {
           if ($data['CharacterID'] == $x){
               $in_team = true;
           }
       }
       
       if ($in_team !== true){
           
           echo "<a  onclick='SetDivPosition()' href='team_builder.php?character={$data['CharacterID']}'>
           <div class='btn from-top'>
           
           <img src= '{$data['CharacterImage']}'/>
           </div>
           </a>";
           
       }
       else {
           
           echo "<img style='opacity:0.3;border:none;margin-right: 5px;'src= '{$data['CharacterImage']}'/>";
           
       }
       
   }	
   echo "</div></div>";


}
function add_char_to_team($current_team) {
   $characterID = $_GET['character'];
   if (in_array($characterID, $current_team)){
       ?><div class = 'error_container' onclick="this.style.display='none'" ><?php
       echo "<div class = 'team_error'>Selected Character is already in your team!</div></div>";
   }
   else {
       echo "<style></style>";
       array_push($current_team,$characterID);	
       $_SESSION["current_team"] = $current_team ;
       if (isset($_GET["keyword"])){
           $keyword = $_GET["keyword"];
           header('location:team_builder.php?keyword=$keyword');
       }
       else{
           header("location:team_builder.php");
       }
   }
}

function showTeam ($current_team,$team_count, $conn, $character_details){
   $dummy_array = array("CurrentTeam");
   if ($team_count == '1') {
       echo "";
   }
   else {
       foreach ($character_details as $character){
           if ($character->defId == $current_team[1]){
               display_character_new($conn,$character,$dummy_array);
           }
       }
       foreach ($character_details as $character){
           if ($character->defId == $current_team[1]){
           }
           else{
               foreach ($current_team as $id){
                   if ($character->defId == $id){
                       display_character_new($conn,$character,$dummy_array);
                       
                   } 
               }
           }
       }
       
   }
   
   
}

function saveTeam ($current_team, $team_count, $conn, $username,$user_characters,$gp){

   $team_classes = array();
   $team_classes = get_classes (($current_team[0]),$conn,$team_classes);
   $team_classes = get_classes (($current_team[1]),$conn,$team_classes);
   $team_classes = get_classes (($current_team[2]),$conn,$team_classes);
   $team_classes = get_classes (($current_team[3]),$conn,$team_classes);
   $team_classes = get_classes (($current_team[4]),$conn,$team_classes);
   $team_classes_string = implode(",",$team_classes);
   $team_names = array();
   $team_names = get_names (($current_team[0]),$conn,$team_names);
   $team_names = get_names (($current_team[1]),$conn,$team_names);
   $team_names = get_names (($current_team[2]),$conn,$team_names);
   $team_names = get_names (($current_team[3]),$conn,$team_names);
   $team_names = get_names (($current_team[4]),$conn,$team_names);
   $team_names_string = implode(",",$team_names);
   $sql = "INSERT INTO teams (team_id, Username, LeaderID, Character2ID, Character3ID, Character4ID, Character5ID,team_gp,factions_contained,characters_contained) 
   VALUES (NULL, '$username','$current_team[0]','$current_team[1]','$current_team[2]','$current_team[3]','$current_team[4]','$gp','$team_classes_string','$team_names_string')  ";
   if ($conn->query($sql) === TRUE) {
       $_SESSION["current_team"] = array ();
       header("location: team_builder.php?saved");
   }
   else{
       ?><div class = 'error_container' onclick="this.style.display='none'" ><?php
       echo "<div class = 'team_error'>Unable to save team!</div></div>";
   }
       
       
   
}

function calculate_gp($current_team,$user_characters){
   $gp = 0;
   foreach ($user_characters as $character){
       foreach ($current_team as $id){
           if ($id == "test"){
               
           }
           else {
           if ($character->defId == $id){
               $gp = $gp + $character->gp;
                       
           } 
           }
       }
   }
   return $gp;
}


function get_classes ($character_id,$conn,$team_classes) {
   $sql= "SELECT * FROM character_data WHERE base_id = '$character_id'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()){
       $classes = $data["categories"];
   }
   $classes_split = preg_split("/[,]+/", "$classes"); 
   $team_classes_new = array_merge($classes_split,$team_classes);
   return $team_classes_new;
   
}


function get_names ($character_id,$conn,$team_names) {
   $sql= "SELECT * FROM character_data WHERE base_id = '$character_id'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()){
       $names = $data["name"];
   }
   $names_split = preg_split("/[,]+/", "$names"); 
   $team_names_new = array_merge($names_split,$team_names);
   return $team_names_new;
   
}





function delete_char_from_team ($character,$current_team,$team_count){
   $position = -1;
   foreach ($current_team as $x) {
       $position= $position + 1;
       if ($x == $character){
           array_splice($current_team,$position,1);
           $_SESSION["current_team"] = $current_team ;
           $team_count = $team_count - 1;
           if (isset($_GET["keyword"])){
               header("location: search_keyword.php?keyword={$_GET['keyword']}");
           }
           else{
               header("location: team_builder.php");
           
           }
           return $team_count;
           
       }
   }
   
   
}




function get_char_info ($conn, $user_characters,$character_id){
   foreach($user_characters as $character){
       if ($character->defId == $character_id){
           return $character;
       }
   }
}

function delete_team ($conn, $team_id){
   $sql = "DELETE FROM teams WHERE team_id = $team_id";
   $result = $conn->query($sql);
   header("location: myTeams.php");
}


function check_in_guild($conn,$username){
   $sql = "SELECT guild_id FROM users WHERE Username = '$username'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()) {
       if (($data['guild_id'] == "NULL" )or($data['guild_id'] == "0" ) )
           $guild = "false";
       
       else {
           $guild = "true";
       }
   }
   return $guild;
}

function get_guild($conn,$username){
   $sql = "SELECT * FROM users WHERE Username = '$username'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()) {
       $guild_id = $data['guild_id'];
   return $guild_id;
   }
}

function get_guild_code($conn,$guild_id){
   $sql = "SELECT * FROM guilds WHERE guild_id = '$guild_id'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()) {
       $guild_code = $data['guild_code'];
   return $guild_code;
   }
}

function get_guild_name($conn,$guild_id){
   $sql = "SELECT * FROM guilds WHERE guild_id = '$guild_id'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()) {
       $guild_name = $data['guild_name'];
   return $guild_name;
   }
}

function get_usernames_in_guild($guild,$conn){
   $usernames = array();
   $sql = "SELECT * FROM users WHERE guild_id = '$guild'";
   $result = mysqli_query($conn, $sql);
   
   while ($data = $result->fetch_assoc()) {
       $username = $data['Username'];
       array_push($usernames,$username);

   }
   return ($usernames);
}

function get_number_teams($usernames,$conn){
   $userStr = implode("', '", $usernames);
   $sql = "SELECT * FROM teams WHERE username IN ('$userStr')" ;
   $result = $conn->query($sql);
   $teams = mysqli_num_rows($result);
   return $teams;
}

function get_number_teams_nonarray($username,$conn){
   $sql = "SELECT * FROM teams WHERE username = '$username'" ;
   $result = $conn->query($sql);
   $teams = mysqli_num_rows($result);
   $sql_s = "SELECT * FROM ship_team WHERE username = '$username'" ;
   $result_s = $conn->query($sql_s);
   $teams_s = mysqli_num_rows($result_s);
   $teams = $teams + $teams_s;
   return $teams;
}

function get_rank($conn,$username){
   $sql = "SELECT * FROM users WHERE username = '$username'";
   $result = $conn->query($sql);
   while ($data = $result->fetch_assoc()) {
       $rank = $data['guild_rank'];

   }
   return $rank;
}

function promote($conn,$username,$rank,$viewer_username){
   if ($rank == "member"){
       $sql = "UPDATE users SET guild_rank = 'officer' WHERE username = '$username'";
       $result = $conn->query($sql);
   }
   else if ($rank == "officer"){
       $sql = "UPDATE users SET guild_rank = 'leader' WHERE username = '$username'";
       $sql_u = "UPDATE users SET guild_rank = 'officer' WHERE username = '$viewer_username'";
       $result = $conn->query($sql);
       $result_u = $conn->query($sql_u);
   }
   

}

function demote($conn,$username,$rank){
   if ($rank == "officer"){
       $sql = "UPDATE users SET guild_rank = 'member' WHERE username = '$username'";
       $result = $conn->query($sql);
   }

}

function remove($conn,$username){
   $sql = "UPDATE users SET guild_id = '0' WHERE username = '$username'";
   $result = $conn->query($sql);
}

function unset_loadout($conn,$loadout_id){
   $sql = "UPDATE loadouts SET active = 'False' WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
}
function set_loadout($conn,$loadout_id){
   $sql = "UPDATE loadouts SET active = 'True' WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
}

function uncheck_all($conn,$loadout_id){
   $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   
   while ($data = $result ->fetch_assoc()) {
       $teams_char = $data['T1'] . "," . $data['T2'] . "," . $data['T3'] . "," . $data['T4'] . "," . $data['B1'] . "," . $data['B2'] . "," . $data['B3'] . "," . $data['B4'];	
       $teams_ship = $data['F1'] . "," . $data['F2'];

   }

   $team_chars = explode(",", $teams_char);
   $team_ships = explode(",", $teams_ship);

   foreach ($team_chars as $char_id){
       $sql_c = "UPDATE teams SET checked = 'false' WHERE team_id = '$char_id'";
       $result = $conn->query($sql_c);
   }
   foreach ($team_ships as $ship_id){
       $sql_s = "UPDATE ship_team SET checked = 'false' WHERE ship_team_id = '$ship_id'";
       $result = $conn->query($sql_s);
   }
}

function get_active_loadout_id($conn,$guild_id){
   $sql = "SELECT * FROM loadouts WHERE guild_id = '$guild_id' AND active = 'True'";
   $result = $conn->query($sql);
   if ((mysqli_num_rows($result)) == 0){
       $active_loadout_id = 'none';
   }
   else {
       while ($data = $result ->fetch_assoc()) {
       $active_loadout_id = $data['loadoutID'];
       }
   }
   
   return $active_loadout_id;

}

function  get_active_loadout_name($conn,$loadout_id){
   $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   if ((mysqli_num_rows($result)) == 0){
       $name = 'none';
   }
   while ($data = $result ->fetch_assoc()) {
       $name = $data['loadout_name'];
   }
   return $name;
}

function create_loadout($conn,$loadout_name,$guild_id){
   $sql = "INSERT INTO loadouts (loadout_name,guild_id,active) VALUES ('$loadout_name','$guild_id','false')";
   echo "<div style='position:absolute; left:0;top:0;'>$sql</div>";
   $result = $conn->query($sql);
}

function display_teams_in_territory($conn,$territory,$loadout_id,$sort,$keyword){
   $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   
   
   
   while ($data = $result ->fetch_assoc()) {
       $teams = $data[$territory];
   }
   // if ($teams == ""){
   // 	echo "<div class='no_teams_in_territory'>There are currently no teams assigned to this territory</div>";
   // }
   
   $team_parts = explode(",", $teams);
   
   $number_of_teams = count($team_parts);
   foreach ($team_parts as $part){
       if ($part == null){
           $number_of_teams = $number_of_teams - 1;
       }
   }
   echo "<div class = 'number_of_teams_container'>";
   echo "<div id = 'number_of_teams' class = 'number_of_teams'></div>";
   echo "</div>";

   ?>
   <script>
   
   var number = <?php echo $number_of_teams; ?>;
   window.number = number;
   document.getElementById("number_of_teams").innerHTML = number;
   </script>
   <?php

   if ($territory == "F1" OR $territory == "F2"){
       echo "<div class = 'teams teams_loadout_ships' id = 'ship_div1' style='transform:translateX(-20px);height: calc(100vh - 300px);'>";
       foreach ($team_parts as $team_id){
       $sql_s = "SELECT * FROM ship_team WHERE ship_team_id = '$team_id'";
       $result_s = $conn->query($sql_s);
       while ($data_s = $result_s->fetch_assoc()) {
           $user_characters = getPlayerInfo_new($data_s['Username'],$conn);
           $user_ships = getPlayerInfo_ship($data_s['Username'],$conn);
           $ships = $data_s['ships'];
           $ships = explode(",",$ships);
           array_shift($ships);
           echo "<div id = 'outer_in_loadout_left_$team_id'>";
           echo "<div class='team_container' id = 'in_loadout_left_$team_id' onclick = 'remove(this.id)''>";
           ?>
           <script>
               function remove(id){
                   var outer = "outer_" + id;
                   var content = document.getElementById(outer).innerHTML;
                   var content = content.replace("in_loadout_left","not_in_loadout_left");
                   var content = content.replace("remove","move_team_loadout_left");
                   document.getElementById(id).style.display= "none";
                   $('#ship_div2').append(content);
                   document.getElementById(id).onclick = function onclick(event){move_team_loadout_left(this.id)};
                   var remove_id = id.replace("in_loadout_left_","");
                   remove_id = remove_id.replace("not_in_loadout_left_","");
                   window.remove_values.push(remove_id);
                   var number = window.number;
                   var new_number = number - 1;
                   window.number = new_number;
                   document.getElementById("number_of_teams").innerHTML = new_number;
               }
               function move_team_loadout_left(id){
                   var letter = id.charAt(0);
                   var new_id = "";
                   if (letter === 'i'){
                       new_id = id.replace("in_loadout_left","not_in_loadout_left");
                       document.getElementById(id).style.display = "none";
                       document.getElementById(new_id).style.display = "block";
                       var remove_id = id.replace("in_loadout_left_","");
                       remove_id = remove_id.replace("not_in_loadout_left_","");
                       window.remove_values.push(remove_id);
                       var number = window.number;
                       var new_number = number - 1;
                       window.number = new_number;
                       document.getElementById("number_of_teams").innerHTML = new_number;
                   }
                   if (letter === 'n'){
                       new_id = id.replace("not_in_loadout_left","in_loadout_left");
                       document.getElementById(id).style.display = "none";
                       document.getElementById(new_id).style.display = "block";
                       var len = window.remove_values.length;
                       var remove_id = id.replace("not_in_loadout_left_","");
                       remove_id = remove_id.replace("in_loadout_left_","");
                       
                       for (let i = 0;i < len;i++){
                           if (window.remove_values[i] === remove_id){
                               window.remove_values.splice(i,1);
                           }
                       }
                       var number = window.number;
                       var new_number = number + 1;
                       window.number = new_number;
                       document.getElementById("number_of_teams").innerHTML = new_number;
                   }
               }
               
           </script>
           
           <?php
           echo "<div class='team_info_container' >";
           echo "<div class = 'team_info'>{$data_s['gp']}</div>";
           echo "<div class = 'team_info_right'>{$data_s['Username']}</div>";
           echo "</div>";
       
           $current_team = array();
           echo "<div class='team_characters' style='margin-top:10px;'>";
           foreach ($ships as $ship_id){
               $init = substr($ship_id,0,7);
               if ($init == "CAPITAL"){
               foreach ($user_ships as $ship){
                   if ($ship->defId == $ship_id){
                       display_ship($conn,$ship,$current_team,$user_characters,$data_s['Username'],"team");
                   }
               }
               }
           }
           foreach ($ships as $ship_id){
               $init = substr($ship_id,0,7);
               if ($init !== "CAPITAL"){
               foreach ($user_ships as $ship){
                   if ($ship->defId == $ship_id){
                       display_ship($conn,$ship,$current_team,$user_characters,$data_s['Username'],"small");
                   }
               }
               }
           }
       
           echo "</div>";
           echo "</div>";
           echo "</div>";
       
       }
       
       }
       
       
       }
       
       else{
       echo "<div class = 'teams_loadout' id = 'div1' style = 'transform:translateX(-20px);height: calc(100vh - 300px);'>";
       foreach ($team_parts as $team_id){
       $sql_t = "SELECT * FROM teams WHERE team_id = '$team_id'";
       $result_t = $conn->query($sql_t);
       $dummy_array = array("Team","edit");
       while ($data_t = $result_t->fetch_assoc()) {
           $team_id = $data_t['team_id'];
           $user_characters = (getPlayerInfo_new($data_t['Username'],$conn));
           echo "<div id = 'outer_in_loadout_left_$team_id'>";
           echo "<div class='team_container' id = 'in_loadout_left_$team_id'  onclick = 'remove(this.id)'>";
           ?>
           <script>
               function remove(id){
                   var outer = "outer_" + id;
                   var content = document.getElementById(outer).innerHTML;
                   var content = content.replace("in_loadout_left","not_in_loadout_left");
                   var content = content.replace("remove","move_team_loadout_left");
                   document.getElementById(id).style.display= "none";
                   $('#div2').append(content);
                   document.getElementById(id).onclick = function onclick(event){move_team_loadout_left(this.id)};
                   var remove_id = id.replace("in_loadout_left_","");
                   remove_id = remove_id.replace("not_in_loadout_left_","");
                   window.remove_values.push(remove_id);
                   var number = window.number;
                   var new_number = number - 1;
                   window.number = new_number;
                   document.getElementById("number_of_teams").innerHTML = new_number;
               }
               function move_team_loadout_left(id){
                   var letter = id.charAt(0);
                   var new_id = "";
       
                   if (letter === 'i'){
                       new_id = id.replace("in_loadout_left","not_in_loadout_left");
                       document.getElementById(id).style.display = "none";
                       document.getElementById(new_id).style.display = "block";
                       var remove_id = id.replace("in_loadout_left_","");
                       remove_id = remove_id.replace("not_in_loadout_left_","");
                       window.remove_values.push(remove_id);
                       var number = window.number;
                       var new_number = number - 1;
                       window.number = new_number;
                       document.getElementById("number_of_teams").innerHTML = new_number;
                       
                   }
                   if (letter === 'n'){
                       new_id = id.replace("not_in_loadout_left","in_loadout_left");
                       document.getElementById(id).style.display = "none";
                       document.getElementById(new_id).style.display = "block";
                       var len = window.remove_values.length;
                       var remove_id = id.replace("not_in_loadout_left_","");
                       remove_id = remove_id.replace("in_loadout_left_","");
                       
                       for (let i = 0;i < len;i++){
                           if (window.remove_values[i] === remove_id){
                               window.remove_values.splice(i,1);
                           }
                       }
                       var number = window.number;
                       var new_number = number + 1;
                       window.number = new_number;
                       document.getElementById("number_of_teams").innerHTML = new_number;
                   }
               }
               
           </script>
           
           <?php
           echo "<div class='team_info_container' >";
           echo "<div class = 'team_info'>{$data_t['team_gp']}</div>";
           echo "<div class = 'team_info_right'>{$data_t['Username']}</div>";
           echo "</div>";
       

           echo "<div class='team_characters' >";
           display_character_new($conn,get_char_info ($conn, $user_characters, $data_t['LeaderID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data_t['Character2ID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data_t['Character3ID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data_t['Character4ID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data_t['Character5ID'] ),$dummy_array);
           echo "</div>";

       echo "</div>";
       echo "</div>";
       
       
       }
       
       
   

       }
   }
   echo "</div>";
   
}


function delete_loadout($conn,$loadout_id){
   $sql = "DELETE FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   
}

function change_name_loadout($conn,$loadout_id,$new_name,$territory){
   $sql = "UPDATE loadouts SET loadout_name = '$new_name' WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);

}

function show_team_options($conn,$userStr,$loadout_id,$sort,$keyword){
   $teams_already_in_use = get_teams_already_in_use($conn,$loadout_id);
   $territory = $_GET['territory'];
   if (isset($_GET['team_filter'])){
       $team_filter = $_GET['team_filter'];
   }
   else {
       $team_filter = "none";
   }
   if (isset($_GET['sort'])){
       $sort = $_GET['sort'];
   }
   else {
       $sort = "none";
   }
   
   
   echo "<div class = 'team_sorter_loadouts'>";
       ?>
       <form method="get" action=""> 
           <input type = "text" style = 'width: 80%;'placeholder="Search for a team or faction" class = "searchChar_input" name="team_filter"></input>
           <input type='hidden' name='i' value='<?php echo $loadout_id; ?>'/>
           <input type='hidden' name='territory' value='<?php echo $territory; ?>'/>
       </form>
       
       <div class="arrow_buttons arrow_buttons_loadout">
       <a href="edit_loadout.php?i=<?php echo $loadout_id; ?>&sort=up&territory=<?php echo $territory; ?>&team_filter=<?php echo $keyword; ?>"> 
       <button class = "arrow_button" >
           <i class="material-icons">arrow_upward</i>
       </button>
       </a>
       <a href="edit_loadout.php?i=<?php echo $loadout_id; ?>&sort=down&territory=<?php echo $territory; ?>&team_filter=<?php echo $keyword; ?>"> 
       <button class = "arrow_button" >
           <i class="material-icons">arrow_downward</i>
       </button>
       </a>
       </div>
       <?php
   echo "</div>";
   
   $sql = "SELECT * FROM teams WHERE username IN ('$userStr')  ORDER BY team_gp DESC" ;
   
   if (isset($_GET["team_filter"])){
       $filter = $_GET['team_filter'];
       if ($filter == "none"){
       }
       else {
       $keywordfromform_untrimed = $filter;
       $keywordfromform = rtrim($keywordfromform_untrimed, "s");
       $keywordfromform = rtrim($keywordfromform, " ");
       $sql = "SELECT * FROM teams WHERE username IN ('$userStr') AND (factions_contained LIKE '%" . $keywordfromform . "%' OR characters_contained LIKE '%" . $filter . "%')";
       echo "<div style='width: 100%;padding-left: 30px;display:flex;flex-direction:row;margin-top:5px;'><a href='edit_loadout.php?i=$loadout_id&territory=$territory' style='height: 100%;display:flex;align-items:center;text-decoration:none;'><i class='material-icons'>arrow_back</i></a>Search results for: $filter</div>";
       }
   }

   if (isset($_GET["sort"])){
       $direction = $_GET["sort"];
       if ($direction == "none"){
           
       }
       else if ($direction == "up"){
           if (isset($_GET["team_filter"])){
               $filter = $_GET['team_filter'];
               if ($filter == "none"){
                   $sql = "SELECT * FROM teams WHERE username IN ('$userStr') ORDER BY team_gp ASC";
               }
               else {
                   $keywordfromform_untrimed = $filter;
                   $keywordfromform = rtrim($keywordfromform_untrimed, "s");
                   $keywordfromform = rtrim($keywordfromform, " ");
                   $sql = "SELECT * FROM teams WHERE Username IN ('$userStr') AND (factions_contained LIKE '%" . $keywordfromform . "%' OR characters_contained LIKE '%" . $filter . "%') ORDER BY team_gp ASC";
                   
               }
           }
           else {
            $sql = "SELECT * FROM teams WHERE username IN ('$userStr') ORDER BY team_gp ASC";
           }
       }
       else if ($direction == "down"){
           if (isset($_GET["team_filter"])){
               $filter = $_GET['team_filter'];
               if ($filter == "none"){
                   $sql = "SELECT * FROM teams WHERE username IN ('$userStr') ORDER BY team_gp DESC";
               }
               else {
                   $keywordfromform_untrimed = $filter;
                   $keywordfromform = rtrim($keywordfromform_untrimed, "s");
                   $keywordfromform = rtrim($keywordfromform, " ");
                   $sql = "SELECT * FROM teams WHERE Username IN ('$userStr') AND factions_contained LIKE '%" . $keywordfromform . "%' OR characters_contained LIKE '%" . $filter . "%' ORDER BY team_gp DESC";
               }
           }
           else{
           $sql = "SELECT * FROM teams WHERE username IN ('$userStr') ORDER BY team_gp DESC";
           }
       }
   }
   else {
       
   }
   
   
   
   $result = $conn->query($sql);
   
   if (mysqli_num_rows($result) == 0){
       echo "<div class = 'no_team'>Your guild currently has no teams.</br> Try creating a team in the Team Builder page.</div>";
   }
   echo "<div class =' teams_loadout' id = 'div2'>";
   while ($data = $result->fetch_assoc()) {
       $in_team = "false";
   
       foreach ($teams_already_in_use as $team_id){
           $team_id_strip = trim($team_id);
           if ($team_id_strip == $data['team_id']){
               $in_team = "true";
           }
       
       }
       
       if ($in_team == "false"){
           $dummy_array = array("Team","edit");
           $team_id = $data['team_id'];
           $user_characters = (getPlayerInfo_new($data['Username'],$conn));
           echo "<div id = 'outer_team_id_$team_id'>";
           echo "<div class='team_container' id = 'team_id_$team_id'  onclick = 'add_team_loadout(this.id)'>";
           ?>
           <script>
           
               function add_team_loadout(id){
               
                   var outer = "outer_" + id;
                   var content = document.getElementById(outer).innerHTML;
                   var content = content.replace("team_id","in_loadout");
                   var content= content.replace("add_team_loadout","move_team_loadout");
                   document.getElementById(id).style.display= "none";
                   $('#div1').append(content);
                   document.getElementById(id).onclick = function onclick(event){move_team_loadout(this.id)};
                   var add_id = id.replace("team_id_","");
                   add_id = add_id.replace("in_loadout_","");
                   window.add_values.push(add_id);
                   var number = window.number;
                   var new_number = number + 1;
                   window.number = new_number;
                   document.getElementById("number_of_teams").innerHTML = new_number;
                   
               }
               
               function move_team_loadout(id){
                   var letter = id.charAt(0);
                   var new_id = "";
                   if (letter === 'i'){
                       new_id = id.replace("in_loadout","team_id");
                       document.getElementById(id).style.display = "none";
                       document.getElementById(new_id).style.display = "block";
                       var len = window.add_values.length;
                       for (let i = 0;i < len;i++){
                           var add_id = id.replace("team_id_","");
                           add_id = add_id.replace("in_loadout_","");
                           if (window.add_values[i] === add_id){
                               window.add_values.splice(i,1);
                           }
                       }
                       var number = window.number;
                       var new_number = number - 1;
                       window.number = new_number;
                       document.getElementById("number_of_teams").innerHTML = new_number;
                   }
                   if (letter === 't'){
                       new_id = id.replace("team_id","in_loadout");
                       document.getElementById(id).style.display = "none";
                       document.getElementById(new_id).style.display = "block";
                       var add_id = id.replace("team_id_","");
                       add_id = add_id.replace("in_loadout_","");
                       window.add_values.push(add_id);
                       var number = window.number;
                       var new_number = number + 1;
                       window.number = new_number;
                       document.getElementById("number_of_teams").innerHTML = new_number;
                   }
               
               }
               
           </script>
           <?php
           echo "<div class='team_info_container' >";
           echo "<div class = 'team_info'>{$data['team_gp']}</div>";
           echo "<div class = 'team_info_right'>{$data['Username']}</div>";
           echo "</div>";
       

           echo "<div class='team_characters' >";
           display_character_new($conn,get_char_info ($conn, $user_characters, $data['LeaderID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character2ID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character3ID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character4ID'] ),$dummy_array);
           display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character5ID'] ),$dummy_array);
           echo "</div>";
       
       // echo "<a class = 'add_team_container' href = 'edit_loadout.php?i=$loadout_id&territory=$territory&team_filter=$team_filter&sort=$sort&add='>";
       // echo "Hello</a>";

       echo "</div>";
       echo "</div>";
       }
   
       
       
       
       
   }
   echo "</div>";
   
}


function get_teams_already_in_use($conn,$loadout_id){
   $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   while ($data = $result ->fetch_assoc()) {
       $teams_T1 = $data['T1'];
       $teams_T2 = $data['T2'];
       $teams_T3 = $data['T3'];
       $teams_T4 = $data['T4'];
       $teams_B1 = $data['B1'];
       $teams_B2 = $data['B2'];
       $teams_B3 = $data['B3'];
       $teams_B4 = $data['B4'];
       $teams_F1 = $data['F1'];
       $teams_F2 = $data['F2'];
   }
   $team_parts_T1 = explode(",", $teams_T1);
   $team_parts_T2 = explode(",", $teams_T2);
   $team_parts_T3 = explode(",", $teams_T3);
   $team_parts_T4 = explode(",", $teams_T4);
   $team_parts_B1 = explode(",", $teams_B1);
   $team_parts_B2 = explode(",", $teams_B2);
   $team_parts_B3 = explode(",", $teams_B3);
   $team_parts_B4 = explode(",", $teams_B4);
   $team_parts_F1 = explode(",", $teams_F1);
   $team_parts_F2 = explode(",", $teams_F2);
   $team_parts = array_merge($team_parts_T1,$team_parts_T2,$team_parts_T3,$team_parts_T4,$team_parts_B1,$team_parts_B2,$team_parts_B3,$team_parts_B4,$team_parts_F1,$team_parts_F2  );
   
   return $team_parts;
}

function add_team_loadout($conn,$team_id,$territory,$loadout_id){
   $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   while ($data = $result ->fetch_assoc()) {
       $teams = $data[$territory];
   }
   $team_parts = explode(",", $teams);
   array_push($team_parts, "$team_id");
   $new_team_str = implode(",",$team_parts);
   $sql_n = "UPDATE loadouts SET $territory = '$new_team_str' WHERE loadoutID = '$loadout_id'";
   $result_n = $conn->query($sql_n);
   

}

function remove_team_loadout($conn,$team_id,$territory,$loadout_id){
   $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   while ($data = $result ->fetch_assoc()) {
       $teams = $data[$territory];
   }
   $team_parts = explode(",", $teams);
   $new_team = remove_element($team_parts,$team_id);

   $new_team_str = implode(",",$new_team);

   $sql_n = "UPDATE loadouts SET $territory = '$new_team_str' WHERE loadoutID = '$loadout_id'";

   $result_n = $conn->query($sql_n);
   
}

function remove_element($array,$value) {
 return array_diff($array, (is_array($value) ? $value : array($value)));
}


function display_teams_in_territory_loadout_page($conn,$territory,$loadout_id,$show,$username){
   $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
   $result = $conn->query($sql);
   
   
   if ($territory == ""){
       echo "<div class='no_teams_in_territory'>Please Select a territory</div>";
   }
   else{
       while ($data = $result ->fetch_assoc()) {
           $teams = $data[$territory];
   
       }
   if ($teams == ""){
       echo "<div class='no_teams_in_territory'>There are currently no teams assigned to this territory</div>";
   }
   $team_parts = explode(",", $teams);
   echo "<div class = 'teams territory' style = 'margin-top: 10px'>";
   foreach ($team_parts as $team_id){
       $sql_t = "SELECT * FROM teams WHERE team_id = '$team_id'";
       if ($show == "my"){
           $sql_t = "SELECT * FROM teams WHERE team_id = '$team_id' AND username = '$username'";
       }
       $dummy_array = array("Team");
       $result_t = $conn->query($sql_t);
       while ($data_t = $result_t->fetch_assoc()) {
               $check = check_team_checked($conn,$team_id);
               $user_info = getPlayerInfo_new($data_t['Username'],$conn);
               echo "<div class='team_container' >";
               echo "<div class='team_info_container' style = 'overflow:hidden;'>";
               echo "<div class = 'team_info'>{$data_t['team_gp']}</div>";
               ?>
               <div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>
               <?php
               if ($check == "false"){
                   echo "<a href='tw_loadout.php?T=$territory&show=$show&check=$team_id'><i class='material-icons'>	check_box_outline_blank</i></a>";
               }
               else {
                   echo "<a class='un_check' href='tw_loadout.php?T=$territory&show=$show&uncheck=$team_id'><i class='material-icons'>check_box</i></a>";
               }
               ?>
               </div>
               <?php
               echo "<div class = 'team_info_right'>{$data_t['Username']}</div>";
               echo "</div>";
               
       
               echo "<div class='team_characters' >";
           echo"<div class = 'char_1'>";
           display_character_new($conn,get_char_info ($conn, $user_info, $data_t['LeaderID'] ),$dummy_array);
           echo"</div><div class = 'char_2'>";
           display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character2ID'] ),$dummy_array);
           echo"</div><div class = 'char_3'>";
           display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character3ID'] ),$dummy_array);
           echo"</div><div class = 'char_4'>";
           display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character4ID'] ),$dummy_array);
           echo"</div><div class = 'char_5'>";
           display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character5ID'] ),$dummy_array);
           echo "</div></div>";
               
               echo "</div>";
           }
           
       
       }
   
       echo "</div>";
   }
}

function check_team_checked($conn,$team_id){
   $sql = "SELECT * FROM teams WHERE team_id = '$team_id'";
   $result = $conn->query($sql);
   while ($data = $result ->fetch_assoc()) {
       $checked = $data['checked'];
   }
   return $checked;
}

function ships_check_team_checked($conn,$team_id){
   $sql = "SELECT * FROM ship_team WHERE ship_team_id = '$team_id'";
   $result = $conn->query($sql);
   while ($data = $result ->fetch_assoc()) {
       $checked = $data['checked'];
   }
   return $checked;
}

function check_team($conn,$team_id){
   $sql = "UPDATE teams SET checked = 'true' WHERE team_id = '$team_id'";
   $result = $conn->query($sql);
}

function uncheck_team($conn,$team_id){
   $sql = "UPDATE teams SET checked = 'false' WHERE team_id = '$team_id'";
   $result = $conn->query($sql);
}

function check_team_ship($conn,$team_id){
   $sql = "UPDATE ship_team SET checked = 'true' WHERE ship_team_id = '$team_id'";
   $result = $conn->query($sql);
}

function uncheck_team_ship($conn,$team_id){
   $sql = "UPDATE ship_team SET checked = 'false' WHERE ship_team_id = '$team_id'";
   $result = $conn->query($sql);
}

function user_profile($username,$rank,$conn){
   $random = rand();
   $img = get_img_profile($username,$conn);
   $ship_teams = number_ship_teams($conn,$username);
   $char_teams = number_teams($conn,$username);
   ?>
   <a href = 'user_info.php?username_info=<?php echo $username;?>' >
   <div id = '<?php echo $random;?>' onmouseover = 'show_profile_info(this.id)' onmouseout = 'show_profile_info(this.id)' class = 'profile_container profile_container_user'>
   <?php
   echo "<div class = 'rank_img'>";
   if ($rank == 'leader'){
       echo "<img src='images/royal-crown.png'>";
   }
   else if ($rank == 'officer'){
       echo "<img src='images/officer.png'>";
   }
   else {

   }
   echo "</div>";
   ?>
   
   <img class="profile_continer_img" src = '<?php echo $img;?>'>

   <div class="profile_container_name">
       <?php echo $username;?>
   </div>
   <div class="profile_content_hidden">
       <i class="fa fa-group" ></i>  <?php echo $char_teams;?>
   </br>
       <i class='fa fa-fighter-jet' style = 'margin-top:5px;transform:rotate(-20deg)'></i> <?php echo $ship_teams;?>
   </div>

   <script>
       function show_profile_info(id){
           document.getElementById(id).classList.toggle('active');
       }

   </script>

   <?php
   echo "</div>";
}

function get_img_profile($username,$conn){
   $sql = "SELECT * FROM user_character_data WHERE Username = '$username'";
   $result = $conn->query($sql);
   $ids = [];
   $gps = [];
   while ($data = $result->fetch_assoc()){
       array_push($ids, $data['defId']);
       array_push($gps, $data['gp']);
   }
   $maxs = array_keys($gps, max($gps));
   $position = $maxs[0];
   $id = $ids[$position];
   $sql_u = "SELECT * FROM character_data WHERE base_id = '$id'";
   $result = $conn->query($sql_u);
   while($data = $result->fetch_assoc()){
       return $data['img'];
       
   }
}

function number_ship_teams($conn,$username){
   $sql = "SELECT * FROM ship_team WHERE Username = '$username' ";
   $result = $conn->query($sql);
   $teams = mysqli_num_rows($result);
   return $teams;
}

function number_teams($conn,$username){
   $sql = "SELECT * FROM teams WHERE Username = '$username' ";
   $result = $conn->query($sql);
   $teams = mysqli_num_rows($result);
   return $teams;
}

function get_guild_characters($conn,$usernames){
   $userStr = implode("', '", $usernames);
   $sql = "SELECT COUNT(defId), defId FROM user_character_data WHERE username IN ('$userStr') GROUP BY defId";
   $result = $conn->query($sql);
   $count = [];
   $id = [];
   while($data = $result->fetch_assoc()){
       array_push($count, $data['COUNT(defId)']);
       array_push($id, $data['defId']);	
   }
   return array($id,$count);

}

function user_profile_character($defId, $number,$conn){
   $random = rand();
   $sql_u = "SELECT * FROM character_data WHERE base_id = '$defId'";
   $result = $conn->query($sql_u);
   while($data = $result->fetch_assoc()){
       $img = $data['img'];
       $name = $data['name'];
   }

   ?>
   <a href = 'guild_character_info.php?character_info=<?php echo $defId;?>' >
   <div id = '<?php echo $random;?>' onmouseover = 'show_profile_info(this.id)' onmouseout = 'show_profile_info(this.id)' class = 'profile_container profile_container_character'>

   <img class="profile_continer_img" src = '<?php echo $img;?>'>

   <div class="profile_container_name" style = 'height:auto;font-size: 14px;margin-bottom: 5px;'><?php echo $name;?></div>

   <div class="profile_content_hidden"  >
   <i class="material-icons" style = 'transform:translateY(6px);'>person</i>  <?php echo $number;?>
</div>

   <script>
       function show_profile_info(id){
           document.getElementById(id).classList.toggle('active');
       }

   </script>

   </div>
   </a>
   <?php
}

function get_guild_ships($conn,$usernames){
   $userStr = implode("', '", $usernames);
   $sql = "SELECT COUNT(defId), defId FROM user_ship_data WHERE Username IN ('$userStr') GROUP BY defId";
   $result = $conn->query($sql);
   $count = [];
   $id = [];
   while($data = $result->fetch_assoc()){
       array_push($count, $data['COUNT(defId)']);
       array_push($id, $data['defId']);	
   }
   return array($id,$count);

}

function user_profile_ship($defId, $number,$conn){
   $random = rand();
   $sql_u = "SELECT * FROM ship_data WHERE base_id = '$defId'";
   $result = $conn->query($sql_u);
   while($data = $result->fetch_assoc()){
       $img = $data['img'];
       $name = $data['name'];
   }

   $name = str_replace("#","'",$name);

   ?>
   <a href = 'guild_ship_info.php?ship_info=<?php echo $defId;?>' >
   <div id = '<?php echo $random;?>' onmouseover = 'show_profile_info(this.id)' onmouseout = 'show_profile_info(this.id)' class = 'profile_container profile_container_ship'>

   <img class="profile_continer_img" src = '<?php echo $img;?>'>

   <div class="profile_container_name" style = 'height:auto;font-size: 14px;margin-bottom: 5px;'><?php echo $name;?></div>

   <div class="profile_content_hidden"  >
   <i class="material-icons" style = 'transform:translateY(6px);'>person</i>  <?php echo $number;?>
</div>

   <script>
       function show_profile_info(id){
           document.getElementById(id).classList.toggle('active');
       }

   </script>

   </div>
   </a>
   <?php
}

function get_characters_in_team($username,$conn){
    if (isset($_GET['ga'])){
        $sql = "SELECT * FROM teams WHERE Username = '$username' AND type = 'ga'";

    }
    else if (isset($_GET['tw'])){
        $sql = "SELECT * FROM teams WHERE Username = '$username' AND type = 'tw'";

    }
    else {
        $sql = "SELECT * FROM teams WHERE Username = '$username' ";

    }

   $result = $conn->query($sql);
   $in_team = array();
   while($data = $result->fetch_assoc()){
       array_push($in_team,$data['LeaderID'],$data['Character2ID'],$data['Character3ID'],$data['Character4ID'],$data['Character5ID']);
   }
   return $in_team;
}

function get_ships_in_team($username,$conn){
    if (isset($_GET['ga'])){
        $sql = "SELECT * FROM ship_team WHERE Username = '$username' AND type = 'ga'";

    }
    else if (isset($_GET['tw'])){
        $sql = "SELECT * FROM ship_team WHERE Username = '$username' AND type = 'tw'";

    }
    else {
        $sql = "SELECT * FROM ship_team WHERE Username = '$username' ";

    }
   $result = $conn->query($sql);
   $in_team = array();
   while($data = $result->fetch_assoc()){
       $ships = $data['ships'];
       $ships = explode(",",$ships);

       $in_team = array_merge($in_team,$ships);
   }
   return $in_team;
}

function get_user_list_unchecked($conn,$character_teams,$ship_teams){
   $users = array();
   foreach($character_teams as $team_id){
       $sql  = "SELECT * FROM teams WHERE team_id  = '$team_id' AND checked = 'false'";
       $result = $conn->query($sql);
       while ($data = $result->fetch_assoc()){
           array_push($users,$data['Username']);
       }
   }
   $users = array_unique($users);
   return $users;
}

function get_guild_unit_data($conn,$usernames){
   $guild_info = array();
   foreach($usernames as $username){
       $user_info = getPlayerInfo_new($username,$conn);
       $user_ships = getPlayerInfo_ship($username,$conn);
       $user_profile = array("Ships" => $user_ships, "Characters" => $user_info);
       $username_upper = trim(strtoupper($username));
       $guild_info[$username_upper] = $user_profile;

   }

   return($guild_info);

}


function get_display_date($last_refresh){
    date_default_timezone_set('UTC');

    $current_date = date("Y-m-d H:i:s");

    $date1 = strtotime($last_refresh);
    $date2 = strtotime($current_date);
    // Formulate the Difference between two dates
    $diff = abs($date2 - $date1);
    
    $years = floor($diff / (365*60*60*24));
    
    $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
    
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    
    $hours = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24)/ (60*60));
    
    $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
    
    if ($months > 0){
        $display_date = $months . " months ago";
    }
    else if ($days > 0){
        $display_date = $days . " days ago";
    }
    else if ($hours > 0){
        $display_date = $hours . " hours ago";
    }
    else if ($minutes > 0){
        $display_date =  $minutes . " minutes ago";
    }
    else {
        $display_date = "1 minute ago";
    }
    return $display_date;
}
?>





