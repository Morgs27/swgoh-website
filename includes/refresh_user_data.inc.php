<?php
set_time_limit(0);
ignore_user_abort();

include '../functions/db_connect.php';

// Get Username From Post Data
$username = $_POST['username'];
$ally_code = $_POST['ally_code'];

echo $username;
echo $ally_code;

// Write to database progress
$str = $username . ":Fetching Data";
$sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
$result = $conn->query($sql);

// Set default timezone
date_default_timezone_set('UTC');

// Fetch User Data from swgoh.gg
$data = json_decode(file_get_contents("http://api.swgoh.gg/player/" . $ally_code . "/"));

// Create Time Variables
$last_updated = $data->data->last_updated;
$last_updated = str_replace("T"," ",$last_updated);
$time = str_replace("T"," ",$last_updated);
$time = strtotime($time);
$end_time = date('Y-m-d H:i:s', strtotime("+1 day", $time));
$current_date = date('Y-m-d H:i:s');

// If swgoh.gg data is more than 1 day old trigger refresh
if ($current_date > $end_time){

    // Setup Curl POST Request
    $curl = curl_init();
    $url = 'http://api.swgoh.gg/players/' . $ally_code .'/trigger-sync/';
    echo $url;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    // Fetch New Update Info from swgoh.gg
    $data = json_decode(file_get_contents("http://api.swgoh.gg/player/" . $ally_code . "/"));

}

if ($data == null){
    
    // Write to database progress
    $str = $username . ": Fetch Failed";
    $sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
    $result = $conn->query($sql);

    echo "Fetch Failed";

    return null;
}

// Get Base Ability Data from SWGOH.gg
$ability_data = json_decode(file_get_contents("http://api.swgoh.gg/abilities"));

// Delete Existing Character + Ship data from database
$sql_c_d = "DELETE FROM user_character_data WHERE Username = '$username'";
$sql_s_d = "DELETE FROM user_ship_data WHERE Username = '$username'";
$result_c_d = $conn->query($sql_c_d);
$result_s_d = $conn->query($sql_s_d);

// Set Counters
$total_omicrons = 0;
$total_zetas = 0;
$total_gear = 0;

// Set Base Variables
$units = $data->units;
$user_data = $data->data;
$mods = $data->mods;


foreach($units as $unit){

    $combat_type = $unit->data->combat_type;

    $nameKey = $unit->data->name;
    $nameKey = str_replace("'"," ",$nameKey);

    if ($combat_type == 1){

        $character = $unit->data;

        $defId = $character->base_id;
        $rarity = $character->rarity;
        $level = $character->level;
        $gear = $character->gear_level;
        $gp = $character->power;
        $relic = $character->relic_tier;
        $zetas = count($character->zeta_abilities);
        $omicrons = count($character->omicron_abilities);
        $stats = $character->stats;
        $equiped = json_encode($character->gear);
        $legend = $character->is_galactic_legend;
        $ultimate = $character->has_ultimate;

        // Get Alignment From Base Character Data
        $sql_alignment = "SELECT * FROM character_data WHERE base_id = '$defId'";
        $result_alignment = $conn->query($sql_alignment);
        
        if ($result_alignment->num_rows > 0) {

            while( $row = $result_alignment->fetch_assoc() ) {
                $alignment = $row['alignment'];
            }

        } 
        else {
            $alignment = "Dark Side";
        }


        $stats = (array)$stats;
        $speed = $stats[5];
        $health = $stats[1];
        $protection = $stats[28];
        $physical_damage = $stats[6];
        $special_damage = $stats[7];
        $potentcy = $stats[17];
        $tenacity = $stats[18];

        // echo "Speed " . $speed;

        $stats = json_encode($stats);

        $sql_c = "INSERT INTO user_character_data 
                    (Username,defId,nameKey,rarity,level,gear,gp,relic,zetas,
                    omicrons,stats_base,speed,health,protection,
                    physical_damage,special_damage,potency,tenacity,equiped,
                    legend,ultimate,alignment)
                VALUES ('$username','$defId','$nameKey','$rarity',
                    '$level','$gear','$gp','$relic','$zetas','$omicrons','$stats',
                    '$speed','$health','$protection','$physical_damage',
                    '$special_damage','$potentcy','$tenacity','$equiped','$legend','$ultimate',
                    '$alignment')";
        echo '<div>' . $sql_c . '</div></br>';
        $result_c = $conn->query($sql_c);
        // print_r($result_c);
        if ($gear == 13){
            $total_gear += 1;
        }

        $total_omicrons += $omicrons;
        $total_zetas += $zetas;

    }
    if ($combat_type == 2){

        $ship = $unit->data;

        $defId = $ship->base_id;
        $rarity = $ship->rarity;
        $level = $ship->level;
        $gp = $ship->power;

        $crew = array();
        // Loop Through Ships Abilities
        foreach($ship->ability_data as $ability){
            // Loop Through All Base Abilities
            foreach ($ability_data as $base_ability){
                // Find Match
                if ($base_ability->base_id == $ability->id){
                    // If Ability is a crew ability add member to crew
                    if ($base_ability->type == 5){

                        array_push($crew,$base_ability->character_base_id);

                    }
                }
            }

        }
        $crew = implode(",",$crew);
        echo $crew;

        $sql_s = "INSERT INTO user_ship_data 
                    (Username,defId,rarity,level,gp,crew)
                  VALUES ('$username','$defId','$rarity','$level','$gp'
                    ,'$crew')";

        echo $sql_s;
        $result_s = $conn->query($sql_s);


    }
    
}

// Handle Mods

$speed_secondaries = array_fill(0,30,0);
$mod_type = array_fill(0,9,0);

$mod_speeds = null;
foreach($mods as $mod){
    foreach ($mod->secondary_stats as $stat){
        if ($stat->name == "Speed"){
            $speed_secondaries[$stat->display_value] += 1;
        }
    }
    $mod_type[$mod->set] += 1;
}

// Set User Data
$skill_rating = $user_data->skill_rating;
$league_name = $user_data->league_name;
$skill_division = $user_data->division_number;
$portrait_image = $user_data->portrait_image;
$title = $user_data->title;
$guild_name = $user_data->guild_name;
$division_img = $user_data->division_image;
$league_img = $user_data->league_blank_image;
$char_gp = $user_data->character_galactic_power;
$ship_gp = $user_data->ship_galactic_power;
$gp = $user_data->galactic_power;
$arena_rank = $user_data->arena->rank;
$fleet_arena_rank = $user_data->fleet_arena->rank;

$mod_speeds = json_encode($speed_secondaries);
$mod_types = json_encode($mod_type);

// Upadate Users Table
$sql = "UPDATE users SET 
            mod_types = '$mod_types' ,
            mod_speeds = '$mod_speeds', 
            zetas = $total_zetas, 
            omicrons = $total_omicrons, 
            division_img = '$division_img', 
            league_img = '$league_img', 
            char_gp = '$char_gp', 
            ship_gp = '$ship_gp', 
            gp = '$gp', 
            refresh_date = '$current_date', 
            guildName = '$guild_name' ,
            title = '$title', 
            portrait_image = '$portrait_image', 
            skill_rating = '$skill_rating', 
            skill_league= '$league_name',
            skill_division = '$skill_division' 
        WHERE Username = '$username'";
echo $sql;
$result = $conn->query($sql);

// Insert into stat instance Table
$sql = "INSERT INTO user_stat_instance (Username,date,gp,char_gp,
ship_gp,skill_rating,arena_rank,ship_arena_rank,omicrons,zetas,reliced)
VALUES ('$username','$current_date',$gp,$char_gp,$ship_gp,$skill_rating,
$arena_rank,$fleet_arena_rank,$total_omicrons,$total_zetas,$total_gear)";

$result = $conn->query($sql);


// Update Database With Progress
$str = $username . ": Refresh Sucessful";
$sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
$result = $conn->query($sql);

// Update Team GP Values
$sql = "SELECT * FROM teams WHERE username = '$username'";
$result = $conn->query($sql);

while ($data = $result->fetch_assoc()) {
    $team = array( $data['LeaderID'], $data['Character2ID'] , $data['Character3ID'] , $data['Character4ID'] , $data['Character5ID']);
    $team = implode("','",$team);
    $gp = "SELECT SUM(gp) AS total_gp FROM user_character_data WHERE username = '$username' AND defId IN ('$team')";
    $result_gp = $conn->query($gp);
    while($data_gp = $result_gp->fetch_assoc()){
        $total_gp = $data_gp['total_gp'];
    }
    $team_id = $data['team_id'];
    $sql_u = "UPDATE teams SET team_gp = '$total_gp' WHERE team_id = '$team_id'";
    $result_u = $conn->query($sql_u);

}

// Update Progress To database
$str = $username . ": Character Teams Gp Updated";
$sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
$result = $conn->query($sql);
