<?php 
include '../functions/db_connect.php';

$username = $_POST['username'];
$new_rank = $_POST['rank'];

if ($new_rank == "removed"){

        $sql = "SELECT * FROM users WHERE Username = '$username'";
        $result = $conn->query($sql);
        while ($data = $result->fetch_assoc()){
            $guild_id = $data['guild_id'];
        }
    
        $teams = array();

        $sql = "SELECT * FROM teams WHERE Username = '$username'";
        $result = $conn->query($sql);
        while ($data = $result->fetch_assoc()){
            $team_id = $data['team_id'];
            array_push($teams,$team_id);
        }

        
        $ship_teams = array();
        $sql = "SELECT * FROM ship_team WHERE Username = '$username'";
        $result = $conn->query($sql);
        while ($data = $result->fetch_assoc()){
            $team_id = $data['ship_team_id'];
            array_push($ship_teams,$team_id);
        }

        
        $territories = array("T1","T2","T3","T4","B1","B2","B3","B4");
        $ship_territories = array("F1","F2");

        $loadouts = array();

        $sql = "DELETE FROM loadouts WHERE created_by = '$username'";
        $result = $conn->query($sql);

        $sql = "SELECT * FROM loadouts WHERE guild_id = '$guild_id'";
        $result = $conn->query($sql);
        while ($data = $result->fetch_assoc()){
            $loadout_id = $data['loadoutID'];
            foreach ($territories as $territory){
                $list = $data[$territory];
                $list = explode(",",$list);
                $changes = false;
                foreach($list as $item){
        
                    foreach($teams as $team){
                        if ($item == $team){
                            $changes = true;
                            $position = array_search($item,$list);
                            echo $position;
                            array_splice($list,$position,1);
                        }
                    }
                }
                print_r($list);
                if ($changes == true){
                    $new_list = implode(",",$list);
                    $sql_u = "UPDATE loadouts SET $territory = '$new_list' WHERE loadoutID = '$loadout_id'";
                    echo $sql_u;
                    $result_u = $conn->query($sql_u);
                }
            }
            foreach($ship_territories as $territory){
                $list = $data[$territory];
                $list = explode(",",$list);
                $changes = false;
                foreach($list as $item){
        
                    foreach($ship_teams as $team){
                        if ($item == $team){
                            $changes = true;
                            $position = array_search($item,$list);
                            echo $position;
                            array_splice($list,$position,1);
                        }
                    }
                }
                if ($changes == true){
                    $new_list = implode(",",$list);
                    $sql_u = "UPDATE loadouts SET $territory = '$new_list' WHERE loadoutID = '$loadout_id'";
                    echo $sql_u;
                    $result_u = $conn->query($sql_u);
                }
            }
        }


        $sql = "UPDATE users SET guild_id = 0 WHERE username = '$username'";
        $result = $conn->query($sql);
        echo $sql;
}
else {
    $sql = "UPDATE users SET guild_rank = '$new_rank' WHERE username = '$username'";
    $result = $conn->query($sql);
    echo $sql;
}
