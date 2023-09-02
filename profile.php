<?php 
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';

session_start();
ob_start();

include_once "header.php";

check_logged_in();

$viewer_rank = is_pro($_SESSION['Username'],$conn);

$username = key($_GET);
if ($username == ""|| $username == NULL){
    header("Location: error.php?v");
}
if (!isset($_SESSION['guest'])){
$username = str_replace("_"," ",$username);
}

if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $username))
{
    ?>
    <script>
        window.location.href = "error.php?special"
    </script>
    <?php        
    exit();
}

$sql = "SELECT * FROM users WHERE Username = '$username'";
$result = $conn->query($sql);
if (mysqli_num_rows($result) == 0){
    header("Location: error.php?f");
}
while($data = $result->fetch_assoc()){
    $profile_img = $data['portrait_image'];
    $gp = $data['gp'];
    $char_gp = $data['char_gp'];
    $ship_gp = $data['ship_gp'];
    $ally_code = $data['ally_code'];
    $ally_code_str = substr($ally_code,0,3) . "-" . substr($ally_code,3,3) . "-" .substr($ally_code,6,3);
    $pro = $data['pro'];
    $last_refresh = $data['refresh_date'];
    $skill_league = $data['skill_league'];
    $skill_division = $data['skill_division'];
    $division_img = $data['division_img'];
    $league_img = $data['league_img'];
    $skill_rating = $data['skill_rating'];
    $title = $data['title'];
    $omicrons = $data['omicrons'];
    $zetas = $data['zetas'];
    $ultimates = $data['ultimates'];
    $mod_speeds = $data['mod_speeds'];
    $mod_types = $data['mod_types'];
    
}

$sql = "SELECT * FROM user_character_data WHERE rarity = '7' AND username = '$username'";
$result = $conn->query($sql);

$rarity_characters = $result->num_rows;

$sql = "SELECT * FROM user_ship_data WHERE rarity = '7' AND username = '$username'";
$result = $conn->query($sql);

$rarity_ships = $result->num_rows;


$sql = "SELECT * FROM user_character_data WHERE gear = '13' AND alignment = 'Light Side' AND username = '$username'";
$result = $conn->query($sql);

$light_side = $result->num_rows;

$sql = "SELECT * FROM user_character_data WHERE gear = '13' AND alignment = 'Dark Side' AND username = '$username'";
$result = $conn->query($sql);

$dark_side = $result->num_rows;

$user_characters = getPlayerInfo_new($username,$conn);
$user_ships = getPlayerInfo_ship($username,$conn);

usort($user_ships,function($first,$second){
    return $first->gp < $second->gp;
});

usort($user_characters,function($first,$second){
    return $first->gp < $second->gp;
});

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
 
  if ($days > 0){
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


$date = date("Y-m-d H:i:s", strtotime("- 1 day"));

if ($last_refresh < $date){
    $date = "refresh";
}
?>



<script>
    let username = '<?php echo $username?>';
    window.username = username;
    let allycode = '<?php echo $ally_code?>';


</script>

<link rel = 'stylesheet' href = 'styles/profile.css'></link>
<script src = 'script/profile.js'></script>

<div class="refresh_modal" >
        <div class="spinner_container" >
            <div class="spinner_circle"></div>
            <div class="spinner_circle outer_1"></div>
            <div class="spinner_circle outer_2"></div>
            <div class="spinner_circle plannet_1"></div>
            <div class="spinner_circle plannet_2"></div>
        </div>
        <div class = 'load_link_text'>Fetching User Data...</div>
        
</div>

<div class="refresh_modal load active" >
        <div class="spinner_container" >
            <div class="spinner_circle"></div>
            <div class="spinner_circle outer_1"></div>
            <div class="spinner_circle outer_2"></div>
            <div class="spinner_circle plannet_1"></div>
            <div class="spinner_circle plannet_2"></div>
        </div>
        <div class = 'load_link_text'>Loading User Data...</div>
        
</div>

<?php if ($pro == "true"){
    echo '<div class="team_builder_bar" style = "position: relative;">';
}
else {
    echo '<div class="team_builder_bar" style = "position: relative">';
}
?>
<div class="profile_background" style = 'background-image: url(<?php echo $profile_img;?>);background-position:center;'></div>
<div class = "profile_banner">
    <?php
    if (isset($_SESSION['guest'])){
        if (isset($_GET['id'])){
            echo '<div class="title">Guest_'. $_GET['id'] . '</div>';
        }
        else {
        $name = $_SESSION['guest'];
        echo '<div class="title">Guest_'. $name . '</div>';
        }
    }
    else {
    ?>

    <div class="title"><?php echo $username;?></div>
    <?php
    }
    ?>
</div>

<?php
//  if ($viewer_rank == "true"){ 
    ?>
    <div onclick = 'refresh_profile(username,allycode)' class="tw_settings export" style = 'position:absolute;right: 5px;display: flex;justify-content: center;align-items:center' >
    <div>
         <i style = 'transform: translate(0px);' class="fa-solid fa-arrow-rotate-right"></i><div class = 'export_open_text'></div>
    </div>
    </div>
    <?php
// }
?>


</div>

<div class="profile_content_container blur">
<div class="profile_selector_bar">
    <div class="navigation">
        <ul>
            <li id = 'stats' onclick = 'change_info(this.id)' class="list active">
                <span class="icon"><i class="fa-solid fa-chart-column"></i></span>
                <span class="text">Info</span>
            </li>
            <li id = 'characters' onclick = 'change_info(this.id)' class="list">
                <span class="icon"><i class="fa-solid fa-user"></i></span>
                <span class="text">Characters</span>
            </li>
            <li id = 'ships' onclick = 'change_info(this.id)' class="list">
                <span class="icon"><i class="fa-solid fa-jet-fighter"></i></span>
                <span class="text">Ships</span>
            </li>
            <li id = 'progress' onclick = 'change_info(this.id)' class="list">
                <span class="icon"><i class="fa-solid fa-chart-line"></i></span>
                <span class="text">Progress</span>
            </li>
            <li id = 'teams' onclick = 'change_info(this.id)' class="list">
                <span class="icon"><i class="fa-solid fa-users"></i></span>
                <span class="text">Teams</span>
            </li>
            <div class="indicator"></div>
        </ul>
    </div>
</div>

<div class="profile_content">
    <div id = 'c_stats' class="selector_content active">
        <div class="profile_info_outer">
        <div class="profile_info">
        <!-- <div class="player_title"><?php echo $title;?></div> -->
        <div class="info_seperator"></div>
            <div class="info_row"><div class="left">Ally Code</div><div class="middle"><i class="fa-solid fa-code"></i></div><div class="right"><?php echo $ally_code_str;?></div></div>
            
            <div class="info_seperator"></div>
            <div class="info_row title">Galactic Power</div>
            <div class="chart_info flipped">
                <div class="chart_container">
                    <canvas id = 'gp_pie' ></canvas>
                </div>
                <div class="chart_annotations">
                    <div class="chart_stat">
                        <div class="colour_box" style = 'background:rgba(255,255,255,0.3)'></div>
                        <div class="text"><?php echo number_format($gp);?></div>
                    </div>
                    <div class="chart_stat">
                        <div class="colour_box" style = 'background:#cd763e'></div>
                        <div class="text"><?php echo number_format($char_gp)?></div>
                    </div>
                    <div class="chart_stat">
                        <div class="colour_box" style = 'background:RGB(62, 149, 205)'></div>
                        <div class="text"><?php echo number_format($ship_gp)?></div>
                    </div>
                </div>
                <script>
                    var char_gp = '<?php echo $char_gp;?>';
                    var ship_gp = '<?php echo $ship_gp;?>';
                    pie_chart("gp_pie",ship_gp,char_gp,"Ships","Characters",'images/jet-white.png','images/users-white.png',"RGB(62, 149, 205)","#cd763e");
                </script>
            </div>
            <div class="info_seperator"></div>
            <div class="ga_info">
            <div class="ga_profile">
                <img src="<?php echo $league_img;?>" alt="">
                <img src="<?php echo $division_img;?>" alt="">
            </div>
            <div class="ga_text">
                <div class="title"><?php echo $skill_league . " " . $skill_division;?> </div>
                <div class="text">Skill Rating: <?php echo $skill_rating;?></div>
            </div>
            </div>

           
            
            <div class="info_seperator"></div>
                
            <div class="stat_row">
                <div class="stat"><img src="images/omicron.png" alt=""><div class="right"><?php echo $omicrons;?></div></div>
                <div class="stat_seperator"></div>
                <div class="stat"><img src="images/public/tex.skill_zeta.png" alt=""><div class="right"><?php echo $zetas;?></div></div>
                <div class="stat_seperator"></div>
                <div class="stat"><img src="images/ultimate.png" alt=""><div class="right"><?php echo $ultimates;?></div></div>
            </div>

            <div class="info_seperator"></div>
            <div class="info_row title">Rarity 7 Units</div>
            <div class="chart_info flipped small">
                <div class="chart_container">
                    <canvas id = 'rarity_pie' ></canvas>
                </div>
                <div class="chart_annotations">
                    <div class="chart_stat">
                        <div class="colour_box" style = 'background:rgba(255,255,255,0.3)'></div>
                        <div class="text"><?php echo ($rarity_characters + $rarity_ships);?></div>
                    </div>
                    <div class="chart_stat">
                        <div class="colour_box" style = 'background:#00B84D'></div>
                        <div class="text"><?php echo $rarity_characters;?></div>
                    </div>
                    <div class="chart_stat">
                        <div class="colour_box" style = 'background:#B000BB'></div>
                        <div class="text"><?php echo $rarity_ships;?></div>
                        
                    </div>
                </div>
                <script>
                    pie_chart("rarity_pie",2456,4567,"Ships","Characters",'images/jet-white.png','images/users-white.png',"#B000BB","#00B84D");
                </script>
            </div>
            <div class="info_seperator"></div>

            <div class="info_row title">Gear 13 Characters</div>
            <div class="chart_info not_flipped small">
                <div class="chart_container">
                    <canvas id = 'gear_pie' ></canvas>
                </div>
                <div class="chart_annotations">
                    <div class="chart_stat">
                        
                        <div class="text"><?php echo $dark_side + $light_side;?></div>
                        <div class="colour_box" style = 'background:rgba(255,255,255,0.3)'></div>
                    </div>
                    <div class="chart_stat">
                        <div class="text"><?php echo $light_side;?></div>
                        <div class="colour_box" style = 'background:RGB(62, 149, 205)'></div>
                    </div>
                    <div class="chart_stat">
                        <div class="text"><?php echo $dark_side;?></div>
                        <div class="colour_box" style = 'background:#ff264b'></div>
                    </div>
                </div>
                <script>
                    let dark = '<?php echo $dark_side;?>';
                    let light = '<?php echo $light_side;?>';
                    pie_chart_no_image("gear_pie",dark,light,"DS","LS",'images/jet-white.png','images/users-white.png',"#ff264b","RGB(62, 149, 205)");
                </script>
            </div>
            <div class="info_seperator"></div>
            <div class="info_row title">Mod Speed Distribution</div>
            <div class="mod_graph"><canvas id = 'mod_graph'></canvas></div>
            <script>
                let speeds = '<?php echo $mod_speeds;?>';
                mod_chart(speeds);
            </script>
            <div class="info_seperator"></div>
            <div class="info_row title">Mod Primary Stats</div>
            <div class="mod_graph"><canvas id = 'mod_graph_types'></canvas></div>
            <script>
                let types = '<?php echo $mod_types;?>';
                mod_type_chart(types);
            </script>
            <div class="info_seperator"></div>
        </div>
        </div>
    </div>
    <div id = 'c_characters' class="selector_content">
        <div class="profile_containers_full">
        <?php
            $loaded = array();
            foreach ($user_characters as $character){
                if (in_array($character->defId, $loaded)){
                    // Character already Loaded
                }
                else {
                    display_character_new($conn,$character,array('Team')); 
                    array_push($loaded, $character->defId);
                }
            }
        ?>
        </div>
    </div>
    <div id = 'c_ships' class="selector_content">
        <div class="profile_containers_full_ship">
        <?php
        $loaded_ships = array();

        foreach ($user_ships as $ship){
            if (in_array($ship->defId, $loaded_ships)){
                // Ship Already Loaded
            }
            else {
                $init = substr($ship->defId,0,7);
                if ($init == "CAPITAL"){
                    display_ship($conn,$ship,array(),$user_characters,$_SESSION['Username'],"team");
                    array_push($loaded_ships, $ship->defId);
                }
            }
        }
        foreach ($user_ships as $ship){
            if (in_array($ship->defId, $loaded_ships)){
                // Ship Already Loaded
            }
            else {
                $init = substr($ship->defId,0,7);
                if ($init == "CAPITAL"){
                    
                }
                else {
                    display_ship($conn,$ship,array(),$user_characters,$_SESSION['Username'],"team");
                    array_push($loaded_ships, $ship->defId);
                }
            }
        }
    ?>
    </div>
    </div>
    <div id = 'c_progress' class="selector_content">
        <div class="graphs">
        <div class="canvas_title">Total GP</div>
        <canvas id="gp" ></canvas>
        <div class="canvas_title">Character GP</div>
        <canvas id="char_gp" ></canvas>
        <div class="canvas_title">Ship GP</div>
        <canvas id="ship_gp" ></canvas>
        <div class="canvas_title">Skill Rating</div>
        <canvas id="skill_rating" ></canvas>
        <div class="canvas_title">Zetas</div>
        <canvas id="zetas" ></canvas>
        <div class="canvas_title">Gear 13 Characters</div>
        <canvas id="reliced" ></canvas>
        <div class="canvas_title">Omicrons</div>
        <canvas id="omicrons" ></canvas>
        <div class="canvas_title">Arena Rank</div>
        <canvas id="arena_rank" ></canvas>
        <div class="canvas_title">Ship Arena Rank</div>
        <canvas id="ship_arena_rank" ></canvas>

        <?php 
            $sql = "SELECT * FROM user_stat_instance WHERE Username = '$username'";
            $result = $conn->query($sql);
            while($data = $result->fetch_assoc()){
                $date = $data['date'];
                $gp = $data['gp'];
                $char_gp = $data['char_gp'];
                $ship_gp = $data['ship_gp'];
                $skill_rating = $data['skill_rating'];
                $arena_rank = $data['arena_rank'];
                $ship_arena_rank = $data['ship_arena_rank'];
            }
        ?>
            
        </div>
    </div>
    <div id = 'c_teams' class="selector_content">
        <?php
        function display_teams_type($link,$conn,$username,$user_characters,$user_ships){
            if (isset($_SESSION['guest'])){
                if (isset($_GET['id'])){
                    $guest_id = $_GET['id'];
                    if (filter_var($guest_id, FILTER_VALIDATE_INT) === false){
                        ?>
                        <script>
                            window.location.href = "error.php?nn";
                        </script>
                        <?php
                        exit();
                    }
                }
                else {
                $guest_id = $_SESSION['guest'];
                }
                $sql = "SELECT * FROM teams WHERE Username = '$username' AND guest_id = '$guest_id' AND type = '$link' ORDER BY team_gp DESC" ;

            }
            else {
                $sql = "SELECT * FROM teams WHERE Username = '$username' AND type = '$link' ORDER BY team_gp DESC" ;

            }
	
            $result = $conn->query($sql);
            
            if (mysqli_num_rows($result) == 0){
                $char_teams = false;
            }
            else {
            $char_teams = true;
            $dummy_array = array("Team");
            while ($data = $result->fetch_assoc()) {
                
                
                echo "<div class='team_container'>";
                echo "<div class = 'team_background chars_$link'><img src='images/battle$link.PNG'></div>";
        
                echo "<div class = 'factions_contained' style = 'display:none;'>";
                    echo $data['factions_contained'];
                    echo "</div>";
                echo "<div class='team_info_container' >";
                echo "<div class = 'team_info'>{$data['team_gp']}</div>";
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

	
            if (isset($_SESSION['guest'])){
                $guest_id = $_SESSION['guest'];
                $sql = "SELECT * FROM ship_team WHERE Username = '$username' AND guest_id = '$guest_id' AND type = '$link' ORDER BY gp DESC" ;

            }
            else {
                $sql = "SELECT * FROM ship_team WHERE Username = '$username' AND type = '$link' ORDER BY gp DESC" ;

            }
            $result = $conn->query($sql);

            if (mysqli_num_rows($result) == 0){
                $ship_teams = false;
            }
            else {
                $ship_teams = true;
            }

            if ($char_teams == false && $ship_teams == false){
                echo "<div class = 'no_results_msg'>This user currently has no " . strtoupper($link) . " teams</div>";
            }

            $dummy_array = array("Team");
            
            while ($data = $result->fetch_assoc()) {
                $ships = $data['ships'];
                $ships = explode(",",$ships);
                array_shift($ships);
                echo "<div class='team_container team_container_ships ' >";
                echo "<div class = 'team_background ships'><img src='images/Capture.png'></div>";
                echo "<div class='team_info_container' >";
                echo "<div class = 'team_info'>{$data['gp']}</div>";
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
        ?>
        <div class="teams">
        <div class="team_type">
            <div class="type_title">
                <img src = 'images/tw_map_2.jpg'>
                <div class="title_type">Territory War Teams</div>
                <div id = 'tw' onclick = 'toggle_content(this.id)' class="arrow"><i class="material-icons">arrow_upward</i></div>
            </div>
            <div class="type_content">
                <?php
                display_teams_type("tw",$conn,$username,$user_characters,$user_ships);
                ?>
            </div>
        </div>
        <div class="team_type">
            <div class="type_title">
                <img src = 'images/ga_map.jpg'>
                <div class="title_type">Grand Arena Teams</div>
                <div id = 'ga' onclick = 'toggle_content(this.id)' class="arrow"><i class="material-icons">arrow_upward</i></div>
            </div>
            <div class="type_content">
            <?php
                display_teams_type("ga",$conn,$username,$user_characters,$user_ships);
            ?>
            </div>
        </div>
        <!-- <div class="team_type">
            <div class="type_title">
                <img src = 'images/farm.PNG'>
                <div class="title_type">Theory Crafting Teams</div>
                <div id = 'tc' onclick = 'toggle_content(this.id)' class="arrow"><i class="material-icons">arrow_upward</i></div>
            </div>
            <div class="type_content">
            <?php
                // display_teams_type("tc",$conn,$username,$user_characters,$user_ships);
            ?> 
            </div>
        </div> -->
        </div>
    </div>
</div>


</div>

<script>
    modal = document.querySelector(".load")
    content = document.querySelector(".profile_content_container")
    modal.classList.remove("active");
    content.classList.remove("blur");
</script>
