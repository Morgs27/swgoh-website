<?php
include '../functions/db_connect.php';
include '../functions/functions.php';
include '../functions/class_init.php';
include '../classes/user_new.php';
include '../classes/character_class.php';
require 'httpful-master/httpful-master/bootstrap.php';

set_time_limit(0);
ignore_user_abort();

$users = array(array());

// $sql = "SELECT * FROM users";
// $result = $conn->query($sql);
// $counter = 0;
// $index = 0;
// while($data = $result->fetch_assoc()){
    
//     $counter += 1;
//     if ($counter == 51){
//         $index += 1;
//         $counter = 1;
//         $users[$index] = array();
//     }
    
//     $user = array("username" => $data['Username'],"ally_code" => $data['ally_code']);
//     array_push($users[$index],$user);

// }


$users = array(array(
    array("username" => "Guest_Info__","ally_code" => "741324657"),
    array("username" => "Guest_Info___","ally_code" => "939729166"),
    array("username" => "Guest_Info____","ally_code" => "882145491"),
    array("username" => "Guest_Info_____","ally_code" => "616485783"),
    array("username" => "Guest_Info______","ally_code" => "644744399"),
    array("username" => "Guest_Info_______","ally_code" => "479461123"),
    array("username" => "Guest_Info________","ally_code" => "771957566"),
    array("username" => "Guest_Info_________","ally_code" => "528234451")
));



foreach($users as $group){
    // print_r($group);
    $allycodes = array();
    foreach ($group as $user){
        array_push($allycodes,$user['ally_code']);
    }

    $data = json_decode(get_player_data_codes(json_encode($allycodes)));
    // print_r($data);

    $str = json_encode($allycodes) . ": Got Data";
    $sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
    $result = $conn->query($sql);

    for ($x = 0; $x < count($data); $x++){

        if ($data[$x]->roster == NULL){
            $str = json_encode($data[$x]->allyCode) . ": NULL Data";
            $sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
            $result = $conn->query($sql);
        }
        else {
            $ally_code = $data[$x]->allyCode;
            $position = array_search($ally_code,$allycodes);
            $positions = array();
            for ($y = 0; $y < count($allycodes); $y++){
                if ($allycodes[$y] == $ally_code){
                    array_push($positions,$y);
                }
            }
            foreach($positions as $position){
                $username = $group[$position]['username'];
                $roster = calculate_data(json_encode($data[$x]->roster));
                // print_r($roster);
                upload_data($username,$ally_code,$roster,$conn);
            }
        }
    }

}


function get_player_data_codes($allyCodes){
	$curl = curl_init();
	$token = get_token();
	if ($token == null){
		return null;
	}
	else {
	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://api.swgoh.help/swgoh/player',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
		"allycodes": '.$allyCodes.',
		"languege":"eng_us",
		"enums": false,
		"structure": false
	}',
	CURLOPT_HTTPHEADER => array(
		'Authorization: Bearer ' .$token,
		'Content-Type: application/json',
		'Cookie: connect.sid=s%3A1zQAkVvKLSrCA_dSauSIFf8ZnF83eePI.15EgDM2DWGbdldJZDu43OXIkyd%2BRE2tBWClDU50onec'
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
	}
}


function calculate_data($roster){

    $url = 'https://swgoh-stat-calc.glitch.me/api?flags=calcGP,percentVals';

    $response = \Httpful\Request::post($url)
        ->addHeader('Content-Type' , 'application/json')
        ->body($roster)
        ->send();
    
    return $response;

}

// function total_stat($base,$mods,$property){
// 	$total = 0;
// 	if (isset($base->$property)){
// 		 $total += $base->$property;
// 	}
// 	if (isset($mods->$property)){
// 		$total += $mods->$property;
// 	}
// 	return $total;
// }

// function upload($username,$ally_code,$data,$conn){
	
//     $data = json_encode(array($data->body));

//     $curl = curl_init();

// 	curl_setopt_array($curl, array(
// 	CURLOPT_URL => 'refresh_user_data.inc.php',
// 	CURLOPT_RETURNTRANSFER => true,
// 	CURLOPT_ENCODING => '',
// 	CURLOPT_MAXREDIRS => 10,
// 	CURLOPT_TIMEOUT => 0,
// 	CURLOPT_FOLLOWLOCATION => true,
// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// 	CURLOPT_CUSTOMREQUEST => 'POST',
// 	CURLOPT_POSTFIELDS =>'{
// 		"data_u": '.$username.',
// 		"json": '. $data .'
// 	}',
// 	));

// 	$response = curl_exec($curl);
//     echo $response;
// 	curl_close($curl);
	
//     echo "Done";
// }

function upload_data($username,$ally_code,$data,$conn){

    // print_r($data);

    $str = $username . ": Uploading Data";
    $sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
    $result = $conn->query($sql);

    date_default_timezone_set('UTC');

    $content = @file_get_contents("http://api.swgoh.gg/player/" . $ally_code . "/");
    if (strpos($http_response_header[0], "200")) { 
       $gg_data = json_decode($content);
    } else { 
    //    echo "FAILED";
       $str = $username . ": Could Not Find SWGOH.gg Data For User";
       $sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
       $result = $conn->query($sql);
       return;
    }


    
    // try{
    //     $gg_data = json_decode(file_get_contents("http://api.swgoh.gg/player/" . $ally_code . "/"));
    // }
    // catch (Exception $ex){
    //     echo "GG Data False";
    //     return;
    // }
    // $gg_data = json_decode(file_get_contents("http://api.swgoh.gg/player/" . $ally_code . "/"));
    // echo "GG Data:";
    // print_r($gg_data);
    // if ($gg_data === false || $gg_data === NULL){
    //     echo "false";
    //     return;
    // }

    $user_data = $gg_data->data;

    // print_r($gg_data);
    
    $last_updated = $user_data->last_updated;

    $last_updated = str_replace("T"," ",$last_updated);
    
    $time = str_replace("T"," ",$last_updated);
    $time = strtotime($time);
    $end_time = date('Y-m-d H:i:s', strtotime("+1 day", $time));
    
    date_default_timezone_set("UTC");
    
    $current_date = date('Y-m-d H:i:s');
    
    if ($current_date > $end_time){
    
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
        echo $response;
    
        $gg_data = json_decode(file_get_contents("http://api.swgoh.gg/player/" . $ally_code . "/"));
        $user_data = $gg_data->data;
    }
    
    $gg_units = $gg_data->units;
    
    
    $data = $data->body;
    $characters = array();
    
    $sql_c_d = "DELETE FROM user_character_data WHERE Username = '$username'";
    $sql_s_d = "DELETE FROM user_ship_data WHERE Username = '$username'";
    
    $result_c_d = $conn->query($sql_c_d);
    $result_s_d = $conn->query($sql_s_d);
    
    $sql_gc = "SELECT * FROM character_data";
    $result_gc = $conn->query($sql_gc);
    
    $characters_gc = array();
    while ($data_gc = $result_gc->fetch_assoc()){
        $gc_id = $data_gc['base_id'];
        $name = $data_gc['name'];
        array_push($characters_gc,$gc_id);
    }

    $total_omicrons = 0;
    $total_zetas = 0;
    $total_ultimates = 0;
    $speed_secondaries = array_fill(0,30,0);
    $mod_type = array_fill(0,9,0);
    $total_gear = 0;
    
    $gg_ids = array();
    foreach ($gg_units as $unit){
        array_push($gg_ids,$unit->data->base_id);
    }
    
    foreach ($data as $character){
    
        $index = array_search($character->defId,$gg_ids);
        if (!$index > -1){
            echo "No Data";
        }
        else {
        $gg_character = $gg_units[$index];
        
        $char_id = $character->id;
        $defId = $character->defId;
    
        $nameKey = $character->nameKey;
        $nameKey = str_replace("'"," ",$nameKey);
        if ($defId == "PADMEAMIDALA"){
            $nameKey = "Padme Amidala";
        }
        $rarity = $character->rarity;
        $level = $character->level;
        $gp = $character->gp;
        
        if ($character->combatType == 1){
    
            $position = array_search($defId,$characters_gc);
            array_splice($characters_gc,$position,1);
    
            $relic =  $character->relic->currentTier;
           
            $mods = json_encode($character->mods);
            $stats_base = $character->stats->base;
            $stats_mods = $character->stats->mods;
    
            // Unit Stat 5 = Speed
            foreach ($character->mods as $mod){
                foreach ($mod->secondaryStat as $stat){
                    if ($stat->unitStat == 5){
                        $speed_secondaries[$stat->value] += 1;
                    }
                }
                $set = $mod->set;
                $mod_type[$set] += 1;
            }
    
            
    
            $speed = total_stat($stats_base,$stats_mods,"Speed");
            $health = total_stat($stats_base,$stats_mods,"Health");
            $protection = total_stat($stats_base,$stats_mods,"Protection");
            $physical_damage = total_stat($stats_base,$stats_mods,'Physical Damage'); 
            $special_damage = total_stat($stats_base,$stats_mods,'Special Damage'); 
            $potentcy = total_stat($stats_base,$stats_mods,"Potency");
            $tenacity = total_stat($stats_base,$stats_mods,"Tenacity");
    
            $stats_base = json_encode($stats_base);
            $stats_mods = json_encode($stats_mods);
    
            $sql_a = "SELECT * FROM character_data WHERE base_id = '$defId'";
            $result_a = $conn->query($sql_a);
            while($data_a = $result_a->fetch_assoc()){
                $alignment = $data_a['alignment'];
            }
    
            $zetas = 0;
            $omicrons = 0;
    
            foreach($gg_character->data->ability_data as $skill){
                // print_r($skill);
                if ($skill->has_zeta_learned == 1){
                    $zetas += 1;
                    $total_zetas += 1;
                }
                if ($skill->has_omicron_learned == 1){
                    $omicrons += 1;
                    $total_omicrons += 1;
                }
            }
    
            $ultimate = 'false';
            if ($gg_character->data->has_ultimate == 1){
                $ultimate = 'true';
                $total_ultimates += 1;
            }
    
            $legend = 'false';
            if ($gg_character->data->is_galactic_legend == 1){
                $legend = 'true';
            }

            
            $gear = $character->gear;
            if ($gear == 13){
                $total_gear += 1;
            }
            $equiped = json_encode($character->equipped);
            $sql_c = "INSERT INTO user_character_data (Username,id,defId,nameKey,rarity,level,gear,gp,relic,zetas,omicrons,stats_base,stats_mods,mods,speed,health,protection,physical_damage,special_damage,potency,tenacity,equiped,legend,ultimate,alignment)
            VALUES ('$username','$char_id','$defId','$nameKey','$rarity','$level','$gear','$gp','$relic','$zetas','$omicrons','$stats_base','$stats_mods','$mods','$speed','$health','$protection','$physical_damage','$special_damage','$potentcy','$tenacity','$equiped','$legend','$ultimate','$alignment')";
            $result = $conn->query($sql_c);
            // print_r($result);
            // echo $sql_c;
        }
        
        else {
            $stats_base = $character->stats->base;
            $stats_crew = $character->stats->crew;
    
            $speed = total_stat($stats_base,$stats_crew,"Speed");
            $health = total_stat($stats_base,$stats_crew,"Health");
            $protection = total_stat($stats_base,$stats_crew,"Protection");
            $physical_damage = total_stat($stats_base,$stats_crew,'Physical Damage'); 
            $special_damage = total_stat($stats_base,$stats_crew,'Special Damage'); 
            $potency = total_stat($stats_base,$stats_crew,"Potency");
            $tenacity = total_stat($stats_base,$stats_crew,"Tenacity");
            $armor = total_stat($stats_base,$stats_crew,"Armor");
    
            // This is wrong
            $damage = $physical_damage;
    
            $stats_base = json_encode($stats_base);
            $stats_crew = json_encode($stats_crew);
    
            $crew = $character->crew;
            $pilots = array();
            foreach ($crew as $pilot){
                $unitId = $pilot->unitId;
                $pilot_string = $unitId;
                array_push($pilots,$pilot_string);
            }
            $crew = implode(",",$pilots);
            $sql_s = "INSERT INTO user_ship_data (Username,id,defId,rarity,level,gp,crew,speed,health,protection,damage,armor,potency,tenacity,stats_base,stats_crew)
            VALUES ('$username','$char_id','$defId','$rarity','$level','$gp','$crew','$speed','$health','$protection','$damage','$armor','$potency','$tenacity','$stats_base','$stats_crew')";
            $result = $conn->query($sql_s);
        }
    }
    }
    
    $mod_types = json_encode($mod_type);
    
    foreach ($characters_gc as $character_gc){
        $sql_ig = "INSERT INTO user_character_data (Username,defId,nameKey,rarity,level,gp,relic,zetas,omicrons,stats_base,stats_mods,mods,equiped)
        VALUES ('$username','$character_gc','This Name',0,0,0,0,0,0,'{}','{}','[]','[]')";
        $result_ig = $conn->query($sql_ig);
    }
    
    
    
    $mod_speeds = json_encode($speed_secondaries);
    
    $skill_rating = $user_data->skill_rating;
    $league_name = $user_data->league_name;
    $skill_division = $user_data->division_number;
    $portrait_image = $user_data->portrait_image;
    $title = $user_data->title;
    $guild_name = $user_data->guild_name;
    $guild_name = str_replace("'","#z#",$guild_name);
    $division_img = $user_data->division_image;
    $league_img = $user_data->league_blank_image;
    $char_gp = $user_data->character_galactic_power;
    $ship_gp = $user_data->ship_galactic_power;
    
    $current_date = date('Y-m-d H:i:s');
    
    $gp = $user_data->galactic_power;
    
    $sql = "UPDATE users SET mod_types = '$mod_types' ,mod_speeds = '$mod_speeds', zetas = $total_zetas, omicrons = $total_omicrons, ultimates = $total_ultimates, division_img = '$division_img', league_img = '$league_img', char_gp = '$char_gp', ship_gp = '$ship_gp', gp = '$gp', refresh_date = '$current_date', guildName = '$guild_name' ,title = '$title', portrait_image = '$portrait_image', skill_rating = '$skill_rating', skill_league= '$league_name',skill_division = '$skill_division' WHERE Username = '$username'";
    echo $sql;
    $result = $conn->query($sql);
    
    $arena_rank = $user_data->arena->rank;
    $fleet_arena_rank = $user_data->fleet_arena->rank;
    
    
    $sql = "INSERT INTO user_stat_instance (Username,date,gp,char_gp,
    ship_gp,skill_rating,arena_rank,ship_arena_rank,omicrons,zetas,reliced)
    VALUES ('$username','$current_date',$gp,$char_gp,$ship_gp,$skill_rating,
    $arena_rank,$fleet_arena_rank,$total_omicrons,$total_zetas,$total_gear)";
    $result = $conn->query($sql);

    $str = $username . ": Refresh Sucessful";
    $sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
    $result = $conn->query($sql);


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
        // echo $team_id;
	}

    // $sql = "SELECT * FROM ship_team WHERE username = '$username'";
	// $result = $conn->query($sql);
	// while ($data = $result->fetch_assoc()) {
	// 	$team = $data['ships'];
	// 	$team = explode(",",$team);
	// 	$gp = calculate_gp($team,$user_ships);
	// 	$team_id = $data['ship_team_id'];
	// 	$sql_u = "UPDATE ship_team SET gp = '$gp' WHERE ship_team_id = '$team_id'";
	// 	$result_u = $conn->query($sql_u);
	// }

    $str = $username . ": Team Gp Updated";
    $sql = "INSERT INTO change_log (change_info) VALUES ('$str')";
    // echo $sql;
    $result = $conn->query($sql);

}





