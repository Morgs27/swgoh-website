<?php 
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';
session_start();
ob_start();
include_once "header.php";

check_logged_in();
check_get_variable("i");

$loadout_id = $_GET['i'];

if (filter_var($loadout_id, FILTER_VALIDATE_INT) === false){
	?>
	<script>
		window.location.href = "ga_loadouts.php";
	</script>
	<?php
}

$username = $_SESSION['Username'];

check_right_user($conn,$loadout_id,$username);

$user_characters = $_SESSION['user_info_class'];
$user_ships = $_SESSION['user_info_ship'];

$territories = ['T1','T2','B1','B2'];

$sql = "SELECT * FROM ga_loadouts WHERE loadout_id = '$loadout_id'";

$result = $conn->query($sql);
check_rows($result);
while( $data = $result->fetch_assoc()){
    $id = $data['loadout_id'];
    $name = $data['name'];
    $created = $data['created'];
    $rank = $data['rank'];
    $teams = json_decode($data['teams']);
}

$char_teams_id = array();
$ship_teams_id = array();
foreach($territories as $territory){
    $ids = $teams->$territory->in;
    foreach($ids as $id){
        if ($territory == "T2"){
            array_push($ship_teams_id,$id);
        }
        else {
            array_push($char_teams_id,$id);
        }
    }
}
?>

<script>window.loadout = '<?php echo $loadout_id;?>'</script>
<script src ="script/ga.js"></script>


<link rel="stylesheet" type="text/css" href="styles/farming_manager.css"/>
<link rel="stylesheet" type="text/css" href="styles/ga_loadout.css"/>

<div class="team_builder_bar" style = 'position: relative'>
<img src="images/ga_map.jpg" alt="" >

<input id = "<?php echo $id;?>" onkeyup = "edit_title(this.id)" class="edit_name_input title" value = "<?php echo $name;?>"></input>

<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='ga_loadouts.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>

<div id = '' onclick = 'change_rank_modal()' class="tw_settings export" style = 'position:absolute;right: 5px;' >
    <div>
        <div class = 'export_open_text'>Rank</div> <img id = 'rank_img' src ="https://game-assets.swgoh.gg/tex.league_icon_<?php echo $rank;?>_blank.png">
    </div>
</div>

</div>


<div class="ga_container">

<div class="content_cover"></div>

<div class="rank_selector_container" id = '<?php echo $loadout_id;?>'>
    <div class="close" onclick = 'close_change_rank()' style = 'transform: scale(0.6);top:5px;right: 5px'></div>
    <div class="rank_background"><img src = "images/ga_map.jpg"></div>
    <div  id = 'carbonite' class="rank" onclick = 'change_rank(this.id,this.parentElement.id)'>
        <img src ="https://game-assets.swgoh.gg/tex.league_icon_carbonite_blank.png">
        <div class="rank_title">Carbonite</div>
    </div>
    <div id = 'bronzium' class="rank" onclick = 'change_rank(this.id,this.parentElement.id)'>
        <img src ="https://game-assets.swgoh.gg/tex.league_icon_bronzium_blank.png">
        <div  class="rank_title">Bronzium</div>
    </div>
    <div id = 'chromium' class="rank" onclick = 'change_rank(this.id,this.parentElement.id)'>
        <img src ="https://game-assets.swgoh.gg/tex.league_icon_chromium_blank.png">
        <div  class="rank_title">Chromium</div>
    </div>
    <div  id = 'aurodium' class="rank" onclick = 'change_rank(this.id,this.parentElement.id)'>
        <img src ="https://game-assets.swgoh.gg/tex.league_icon_aurodium_blank.png">
        <div  class="rank_title">Aurodium</div>
    </div>
    <div id = 'kyber' class="rank" onclick = 'change_rank(this.id,this.parentElement.id)'>
        <img src ="https://game-assets.swgoh.gg/tex.league_icon_kyber_blank.png">
        <div  class="rank_title">Kyber</div>
    </div>
    <script>
        window.rank = '<?php echo $rank;?>';
        let rank = document.getElementById(window.rank);
        rank.classList.toggle("active")
    </script>
</div>

<div class="modal_cover"></div>

<div class="add_team_modal">
    
    <div onclick = "hide_options()" class="close close_options" style = 'transform: scale(0.8);top:5px;right: 5px'></div>
    <!-- <div class="modal_title">Add Characters to Stage 1</div> -->
    <div class="add_team_title">Add Teams 
        <!-- <img src = 'images/ga_map.jpg'> -->
    </div>
    <div class="add_team_teams">
        <div class="ship_options">
            <?php
            $sql = "SELECT * FROM ship_team WHERE Username = '$username' AND type = 'ga' ORDER BY gp DESC" ;
        
            $result = $conn->query($sql);

            $dummy_array = array("Team");

            echo "<div class = 'teams'>";
            
            while ($data = $result->fetch_assoc()) {
                if (array_search($data['ship_team_id'],$ship_teams_id) > -1){

                }
                else {
                $ships = $data['ships'];
                $ships = explode(",",$ships);
                array_shift($ships);
                echo "<div id = 'ti_{$data['ship_team_id']}' onclick = 'add_ship_territory(this.id)' class='team_container team_container_ships hover ' >";
                echo "<div class = 'team_background ships'><img src='images/Capture.png'></div>";
                echo "<div class='team_info_container' >";
                echo "<div class = 'team_info'>{$data['gp']}</div>";
                echo "<div style = 'display:none' onclick = 'remove_from_territory(this.id)' id = '{$data['ship_team_id']}'  class = 'delete_team_link' ><i class='fa-solid fa-xmark'></i></div>";
                echo "</div>";

                $current_team = array();
                echo "<div class='team_characters team_characters_ships' style='margin-top:10px;'>";
                foreach ($ships as $ship_id){
                    $init = substr($ship_id,0,7);
                    if ($init == "CAPITAL"){
                    foreach ($user_ships as $ship){
                        if ($ship->defId == $ship_id){
                            echo"<div class = 'capital_ship_team'>";
                            display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"team");
                            echo "</div>";
                        }
                    }
                    }
                }
                $x = 0;
                foreach ($ships as $ship_id){
                    $init = substr($ship_id,0,7);
                    if ($init !== "CAPITAL"){
                    foreach ($user_ships as $ship){
                        if ($ship->defId == $ship_id){
                            $x += 1;
                            if ($x > 3){
                                $y = "super_small";
                            }
                            else {
                                $y = "small";
                            }
                            echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
                            display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"small");
                            echo "</div>";

                        }
                    }
                    }
                }
                
                echo "</div>";
                echo "</div>";
                
            }
            }
            echo "</div>";
            ?>
        </div>
        <div class = 'char_options'>
            <?php
                $sql = "SELECT * FROM teams WHERE Username = '$username' AND type = 'ga' ORDER BY team_gp DESC" ;
            
                $result = $conn->query($sql);
                
                if (mysqli_num_rows($result) == 0){
                    echo "No Character Options";
                }
                else {
                $dummy_array = array("Team");
                echo "<div class = 'teams' >";
                while ($data = $result->fetch_assoc()) {
                    if (array_search($data['team_id'],$char_teams_id) > -1){

                    }
                    else {
                    
                    echo "<div id = 'ti_{$data['team_id']}' onclick = 'add_char_territory(this.id)' class='team_container hover'>";
                    echo "<div class = 'team_background chars_ga'><img src='images/battlega.PNG'></div>";

                    echo "<div class = 'factions_contained' style = 'display:none;'>";
                        echo $data['factions_contained'];
                        echo "</div>";
                    echo "<div class='team_info_container' >";
                    echo "<div class = 'team_info'>{$data['team_gp']}</div>";
                    echo "<div style = 'display:none' onclick = 'remove_from_territory(this.id)' id = '{$data['team_id']}'  class = 'delete_team_link' ><i class='fa-solid fa-xmark'></i></div>";
                    echo "</div>";
                    
                    

                    echo "<div class='team_characters' >";
                    echo"<div class = 'char_1'>";
                    display_character_new($conn,get_char_info ($conn, $user_characters, $data['LeaderID'] ),$dummy_array);
                    echo"</div><div class = 'char_2'>";
                    display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character2ID'] ),$dummy_array);
                    echo"</div><div class = 'char_3'>";
                    display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character3ID'] ),$dummy_array);
                    echo"</div><div class = 'char_4'>";
                    display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character4ID'] ),$dummy_array);
                    echo"</div><div class = 'char_5'>";
                    display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character5ID'] ),$dummy_array);
                    echo "</div></div>";
                    
                    echo "</div>";
                    
                }
                }
                echo "</div>";
            }
             
            ?>
        </div>
    </div>
    
</div>

<div class="ga_content">



<div class="left_section">
    <div class="ga_map_container">
        <div class="ccc"></div>
        <div class="border_polygon">
        
        <div class="inner_polygon">
        <div class="aaa"></div>
        <div class="bbb"></div>
        
        <div class="inner_inner_polygon">
            
            <div class="circle">
            <div class="inner_circle">
                <img src="https://swgohteammanager.com/images/ga2.PNG">
            </div>
            </div>
            <div id = 'T2' class="top_left ga_section" onclick = 'set_territory(this.id)'>
                <div class="territory_number">
                    <i class="fa-solid fa-jet-fighter"></i> 
                    <div class="middle_line_stat"></div>
                    <?php $total = $teams->T2->total; $number = count($teams->T2->in); if($teams->T2->in[0] == null){$number = 0;} ?>
                    <div class="stat"><?php echo $number . "/" . $total;?></div>    
                </div>
                    <?php
                        $t2 = $teams->T2->in;
                        if ($t2[0] == null || $t2[0] == "none"){
                            echo "<div class='territory_content none ships_$number'>";
                            // echo "<div class='add_msg'>Click to add teams<i class='fa-solid fa-plus'></i></div>";
                            echo "</div>";
                        }
                        else {
                        $number = count($t2);
                        echo "<div class='territory_content ships_$number'>";
                        foreach($t2 as $team){
                            $sql = "SELECT * FROM ship_team WHERE ship_team_id = '$team'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $ships = explode(",",$data['ships']);
                            }
                            $capital = $ships[1];
                            $sql = "SELECT * FROM ship_data WHERE base_id = '$capital'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $img = $data['img'];
                            }
                            echo "<img id = 's_$team' class = 'ship' src = '$img'>";
                        }
                        echo "</div>";
                        }
                    ?>
            </div>
            <div id = 'T1' class="top_right ga_section active" onclick = 'set_territory(this.id)'>
                <div class="territory_number">
                    <i class="fa-solid fa-users"></i>
                    <div class="middle_line_stat"></div>
                    <?php $total = $teams->T1->total; $number = count($teams->T1->in); if($teams->T1->in[0] == null){$number = 0;} ?>
                    <div class="stat"><?php echo $number . "/" . $total;?></div>   
                </div>
                    <?php
                        $t1 = $teams->T1->in;
                        if ($t1[0] == null || $t1[0] == "none"){
                            echo "<div class='territory_content none t1_chars'>";
                            // echo "<div class='add_msg'>Click to add teams<i class='fa-solid fa-plus'></i></div>";
                            echo "</div>";
                        }
                        else {
                        $number = count($t1);
                        echo "<div class='territory_content t1_chars'>";
                        foreach($t1 as $team){
                            $sql = "SELECT * FROM teams WHERE team_id = '$team'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $leader = $data['LeaderID'];
                            }
                            $sql = "SELECT * FROM character_data WHERE base_id = '$leader'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $img = $data['img'];
                            }
                            echo "<img id = 'c_$team' class = 'char' src = '$img'>";
                        }
                        echo "</div>";
                        }
                    ?>
                
            </div>
            <div id = 'B2' class="bottom_left ga_section" onclick = 'set_territory(this.id)'>
                    <?php
                        $B2 = $teams->B2->in;
                        if ($B2[0] == null || $B2[0] == "none"){
                            echo "<div class='territory_content  B2_chars none' >";
                            // echo "<div class='add_msg'>Click to add teams<i class='fa-solid fa-plus'></i></div>";
                            echo "</div>";
                        }
                        else {
                        $number = count($B2);
                        echo "<div class='territory_content B2_chars'>";
                        foreach($B2 as $team){
                            $sql = "SELECT * FROM teams WHERE team_id = '$team'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $leader = $data['LeaderID'];
                            }
                            $sql = "SELECT * FROM character_data WHERE base_id = '$leader'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $img = $data['img'];
                            }
                            echo "<img id = 'c_$team' class = 'char' src = '$img'>";
                        }
                        echo "</div>";
                        }
                    ?>
                <div class="territory_number">
                    <i class="fa-solid fa-users"></i>
                    <div class="middle_line_stat"></div>
                    <?php $total = $teams->B2->total; $number = count($teams->B2->in); if($teams->B2->in[0] == null){$number = 0;} ?>
                    <div class="stat"><?php echo $number . "/" . $total;?></div>    
                </div>
            </div>
            <div id = 'B1' class="bottom_right ga_section" onclick = 'set_territory(this.id)'>
                    <?php
                        $B1 = $teams->B1->in;
                        if ($B1[0] == null || $B1[0] == "none"){

                            echo "<div class='territory_content none t1_chars'>";
                            // echo "<div class='add_msg'>Click to add teams<i class='fa-solid fa-plus'></i></div>";
                            echo "</div>";
                        }
                        else {
                        $number = count($B1);
                        echo "<div class='territory_content t1_chars' style = 'margin-top: 5px;'>";
                        foreach($B1 as $team){
                            $sql = "SELECT * FROM teams WHERE team_id = '$team'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $leader = $data['LeaderID'];
                            }
                            $sql = "SELECT * FROM character_data WHERE base_id = '$leader'";
                            $result = $conn->query($sql);
                            while($data = $result->fetch_assoc()){
                                $img = $data['img'];
                            }
                            echo "<img id = 'c_$team' class = 'char' src = '$img'>";
                        }
                        echo "</div>";
                        }
                    ?>
                <div class="territory_number">
                    <i class="fa-solid fa-users"></i> 
                    <div class="middle_line_stat"></div>
                    <?php $total = $teams->B1->total; $number = count($teams->B1->in); if($teams->B1->in[0] == null){$number = 0;} ?>
                    <div class="stat"><?php echo $number . "/" . $total;?></div>  
                </div>
            </div>
            
        </div>
        </div>
    </div>
    </div>
    <div class="options_options">
    <div onclick = 'show_options()' class = 'toggle_options'><i style = 'margin-right: 5px' class="fa-solid fa-plus"></i>Add Teams</div>
    <div onclick = 'view_teams()' class = 'toggle_options view'><i style = 'margin-right: 5px' class="fa-solid fa-eye"></i>View Teams</div>
    </div>
</div>

<div class="middle_line"></div>
<div class="right_section">
    <div class="right_title">Territory
        <div onclick = 'hide_teams()' class="close close_teams_view" style = 'transform: scale(0.8);top:5px;right: 5px'></div>
        <div class="territory_number_title">
            <i class="fa-solid fa-users"></i> 
            <div class="middle_line_stat"></div>
            <?php $total = $teams->T1->total; $number = count($teams->T1->in); if($teams->T1->in[0] == null){$number = 0;} ?>
            <div class="stat"><?php echo $number . "/" . $total;?></div>  
        </div>                
    </div>
    <div class="in_territory">
        <?php
        $territories = ['T1','T2','B1','B2'];
        $dummy_array = array("Team");
        foreach ($territories as $territory){
            echo "<div id = 't_$territory' class = 'teams teams_in'>";
            foreach ($teams->$territory->in as $team){
                if ($territory == "T2"){
                    $sql = "SELECT * FROM ship_team WHERE ship_team_id = '$team'";
                    $result = $conn->query($sql);
                    while($data = $result->fetch_assoc()){
                    $ships = $data['ships'];
                    $ships = explode(",",$ships);
                    array_shift($ships);
                    echo "<div class='team_container team_container_ships ' id = 'ti_{$data['ship_team_id']}' >";
                    echo "<div class = 'team_background ships'><img src='images/Capture.png'></div>";
                    echo "<div class='team_info_container' >";
                    echo "<div class = 'team_info'>{$data['gp']}</div>";
                    echo "<div onclick = 'remove_from_territory(this.id)' id = '{$data['ship_team_id']}'  class = 'delete_team_link' ><i class='fa-solid fa-xmark'></i></div>";
                    echo "</div>";

                    $current_team = array();
                    echo "<div class='team_characters team_characters_ships' style='margin-top:10px;'>";
                    foreach ($ships as $ship_id){
                        $init = substr($ship_id,0,7);
                        if ($init == "CAPITAL"){
                        foreach ($user_ships as $ship){
                            if ($ship->defId == $ship_id){
                                echo"<div class = 'capital_ship_team'>";
                                display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"team");
                                echo "</div>";
                            }
                        }
                        }
                    }
                    $x = 0;
                    foreach ($ships as $ship_id){
                        $init = substr($ship_id,0,7);
                        if ($init !== "CAPITAL"){
                        foreach ($user_ships as $ship){
                            if ($ship->defId == $ship_id){
                                $x += 1;
                                if ($x > 3){
                                    $y = "super_small";
                                }
                                else {
                                    $y = "small";
                                }
                                echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
                                display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"small");
                                echo "</div>";

                            }
                        }
                        }
                    }
                    
                    echo "</div>";                    
                    echo "</div>";
                }
                }
                else {
                    $sql = "SELECT * FROM teams WHERE team_id = '$team'";
                    $result = $conn->query($sql);
                    while($data = $result->fetch_assoc()){
                        echo "<div class='team_container' id = 'ti_{$data['team_id']}'>";
                        echo "<div class = 'team_background chars_ga'><img src='images/battlega.PNG'></div>";

                        echo "<div class = 'factions_contained' style = 'display:none;'>";
                            echo $data['factions_contained'];
                            echo "</div>";
                        echo "<div class='team_info_container' >";
                        echo "<div class = 'team_info'>{$data['team_gp']}</div>";
                        echo "<div onclick = 'remove_from_territory(this.id)' id = '{$data['team_id']}'  class = 'delete_team_link' ><i class='fa-solid fa-xmark'></i></div>";
                        // echo "<div id = '{$data['team_id']}'  class = 'edit_team_link' ><i class='fa-solid fa-pen-to-square'></i></div>";
                        echo "</div>";
                        
                        

                        echo "<div class='team_characters' >";
                        echo"<div class = 'char_1'>";
                        display_character_new($conn,get_char_info ($conn, $user_characters, $data['LeaderID'] ),$dummy_array);
                        echo"</div><div class = 'char_2'>";
                        display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character2ID'] ),$dummy_array);
                        echo"</div><div class = 'char_3'>";
                        display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character3ID'] ),$dummy_array);
                        echo"</div><div class = 'char_4'>";
                        display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character4ID'] ),$dummy_array);
                        echo"</div><div class = 'char_5'>";
                        display_character_new($conn,get_char_info ($conn, $user_characters, $data['Character5ID'] ),$dummy_array);
                        echo "</div></div>";                        
                        echo "</div>";
                    }

                }
            }
            echo "</div>";
        }
        ?>
    </div>

</div>
</div>

<script>
    set_territory('T1');

</script>


