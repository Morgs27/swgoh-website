<?php
ob_start();
include '../functions/class_init.php';
include '../functions/db_connect.php';
include '../functions/functions.php';
require '../includes/httpful-master/httpful-master/bootstrap.php';
include '../classes/character_class.php'
?>
<p id='data'>
<?php


if (!isset($_GET['key'])){
    echo (json_encode("No key"));
    exit();
}

$key = $_GET['key'];

$sql = "SELECT * FROM api_key WHERE token = '$key'";
$result = $conn->query($sql);

if (mysqli_num_rows($result) == 0){
    echo(json_encode("Incorrect Key"));
    exit();
}

if(!isset($_GET['type'])){
    echo (json_encode("Type Not Provided"));
    exit();
}

$type = $_GET['type'];

if ($type == "facebook_token_get"){
    $sql = "SELECT * FROM facebook_acess_token";
    $result = $conn->query($sql);
    while($data = $result->fetch_assoc()){
        echo json_encode($data['token']);
    }
}

if ($type == "user"){
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE Username = '$id'";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) == 0){
        echo(json_encode("Invalid Id"));
        exit();
    }
    while($data = $result->fetch_assoc()){
        $display_data = array(
            'name' => $data['Username'],
            'ally_code' => $data['ally_code'],
            'guild_id' => $data['guild_id']
        );
        echo (json_encode($display_data));
    }
}

if ($type == "refresh_recruitment"){
    echo (json_encode("here"));
     include_once '../includes/update_recruitment_posts.php';
}

if ($type == "update_all_users"){
    echo (json_encode("Update All"));
   
    $url = 'https://swgohteammanager.com/includes/update_all_users.php';

    $response = \Httpful\Request::post($url)
        ->send();
    
    return $response;
}

if ($type == "tw_unchecked"){
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE Username = '$id'";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) == 0){
        echo(json_encode("Invalid Id"));
        exit();
    }
    
    $guild_id = get_guild($conn,$id);
    $active_loadout_id = get_active_loadout_id($conn,$guild_id);
    if ($active_loadout_id == 'none'){
         echo (json_encode("No Active Loadout"));
        exit();
    }
    $active_loadout_name = get_active_loadout_name($conn,$active_loadout_id);
    
    $territories = array("T1","T2","T3","T4","B1","B2","B3","B4","F1","F2");
    
    $sql = "SELECT * FROM loadouts WHERE loadoutID = '$active_loadout_id'";
    $result = $conn->query($sql);
    $return_info = [];

    while ($data = $result->fetch_assoc()){
        foreach($territories as $territory){
            $teams = explode(",",$data[$territory]);
            foreach($teams as $team){
                if ($territory == "F1" OR $territory == "F2"){
                $sql_c = "SELECT * FROM ship_team WHERE ship_team_id = '$team' AND checked = 'false'";
                }
                else{
                $sql_c  = "SELECT * FROM teams WHERE team_id  = '$team' AND checked = 'false'";
                }
    		    $result_c = $conn->query($sql_c);
    		    while ($data_c = $result_c->fetch_assoc()){
    		        $username = $data_c['Username'];
    		        if (isset($return_info[$username])){
    		            array_push($return_info[$username],$territory);
    		        }
    		        else{
    		            $return_info[$username] = [$territory];
    		        }
    		    }
            }
        }
    }
    echo (json_encode($return_info));
}

if ($type == "guild"){
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    $id = $_GET['id'];
    $sql = "SELECT * FROM guilds WHERE guild_id = '$id'";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) == 0){
        echo(json_encode("Invalid Id"));
        exit();
    }
    while($data = $result->fetch_assoc()){
        $display_data = array(
            'name' => $data['guild_name'],
            'code' => $data['guild_code']
        );
        echo (json_encode($display_data));
    }
}

if ($type == "guild_names"){
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    $id = $_GET['id'];
    $guild_id = get_guild($conn,$id);
    $usernames = get_usernames_in_guild($guild_id,$conn);
    echo (json_encode($usernames));
    
}

if ($type == "characters"){
    
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    
    $id = $_GET['id'];
    $filter = $_GET['filter'];
    
    $data = getPlayerInfo_new($id,$conn);
    
    $filter = "%" . $filter ."%";
    $sql = "SELECT * FROM character_data WHERE name LIKE '$filter'";
    
    $result = $conn->query($sql);
    $match_ids = [];
    while ($data_q = $result->fetch_assoc()){
        $id = $data_q['base_id'];
        array_push($match_ids,$id);
    }
    $characters = [];
    foreach ($data as $character){
        $data_id = $character->defId;
        foreach ($match_ids as $id){
            if ($data_id == $id){
                array_push($characters,$character);
            }
        }
    }
    foreach($characters as $character){
        $sql = "SELECT * FROM character_data WHERE base_id = '$character->defId'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $img = $data['img'];
            $character->img = $img;
        }
        
    }
    echo (json_encode($characters));
    
        
    
}

if ($type == "char_owners"){
    
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    
    $id = $_GET['id'];
    $guildId = get_guild($conn, $id);
    
    $filter = $_GET['filter'];
    $filter = explode(",",substr($filter, 0, -1));
    print_r($filters);
    
    $character_name = $_GET['name'];
    
    $sql = "SELECT * FROM user_character_data, users WHERE user_character_data.nameKey LIKE '%$character_name%' AND users.guild_id = '$guildId' AND users.Username = user_character_data.Username";
    $result = $conn->query($sql);
    
    $returnData = [];
    
    while($data = $result->fetch_assoc()){
        $username = $data['Username'];
        $name = $data['nameKey'];
        // Put filter checks here if are any
        if (array_key_exists($name, $returnData)){
            array_push($returnData[$name], [$username,$data['gear'],$data['relic'],$data['rarity'],$data['omicrons']]);
        }
        else{
            $returnData[$name] = [ [$username,$data['gear'],$data['relic'],$data['rarity'],$data['omicrons'] ]];
        }
    }
    
    echo (json_encode($returnData));
    
        
    
}

if ($type == "characters_guild"){
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    $id = $_GET['id'];
    $filter = $_GET['filter'];
    $data = getPlayerInfo_new($id,$conn);
    $filter = "%" . $filter ."%";
    $sql = "SELECT * FROM character_data WHERE name LIKE '$filter'";
    $result = $conn->query($sql);
    $match_ids = [];
    while ($data_q = $result->fetch_assoc()){
        $id = $data_q['base_id'];
        array_push($match_ids,$id);
    }
    $characters = [];
    foreach ($data as $character){
        $data_id = $character->defId;
        foreach ($match_ids as $id){
            if ($data_id == $id){
                array_push($characters,$character);
            }
        }
    }
    foreach($characters as $character){
        $sql = "SELECT * FROM character_data WHERE base_id = '$character->defId'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $img = $data['img'];
            $character->img = $img;
        }
        
    }
    echo (json_encode($characters));
    
        
    
}

if ($type == "team"){
    if(!isset($_GET['id'])){
        echo (json_encode("Id Not Provided"));
        exit();
    }
    $id = $_GET['id'];
    $filter = $_GET['filter'];
    $data = getPlayerInfo_new($id,$conn);
    $filter = "%" . $filter ."%";
    $sql = "SELECT * FROM teams WHERE Username = '$id' AND characters_contained LIKE '$filter'";
    $result = $conn->query($sql);
    $return = [];
    while ($data_q = $result->fetch_assoc()){
        $characters = [];
        $id = $data_q['team_id'];
        $in_team = [$data_q['Character2ID'],$data_q['Character3ID'],$data_q['Character4ID'],$data_q['Character5ID']];
        foreach ($data as $character){
            $data_id = $character->defId;
            if ($data_id == $data_q['LeaderID']){
                array_push($characters,$character);
            }
            
        }
        foreach ($data as $character){
            $data_id = $character->defId;
            foreach ($in_team as $id){
                if ($data_id == $id){
                    array_push($characters,$character);
                }
            }
        }
        array_push($return,[$data_q['team_id'],$characters,$data_q['team_gp']]);
    }
    echo (json_encode($return));
    
        
}
?>
</p>
</body>



