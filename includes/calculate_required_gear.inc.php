<?php 
session_start();
include '../functions/db_connect.php';


$array = $_POST['characters'];


$username = $_SESSION['Username'];

// Get Gear Array
$gear = array();

$sql = "SELECT * FROM gear";
$result = $conn->query($sql);
while ($data = $result->fetch_assoc()){
    $gear_id = $data['base_id'];
    $ingredients = $data['ingredients'];
    $gear[$gear_id] = array("ingredients" => $ingredients, "total" => 0, "complete" => 0);
}

$_SESSION['gear'] = $gear;

$relics = array();
$sql = "SELECT * FROM relic";
$result = $conn->query($sql);
while ($data = $result->fetch_assoc()){
    $relic_id = $data['relic_id'];
    $name = $data['name'];
    $img = $data['img'];
    $relics[$relic_id] = array("total" => 0, "complete" => 0);
}

$_SESSION['relic'] = $relics;

$base_relic_levels = array(
    "1" =>  array(array("1",40)),
    "2" =>  array(array("1",30),array("2",40),array("9",15)),
    "3" =>  array(array("1",30),array("2",40), array("3",20),array("9" ,20),array("10" , 15)),
    "4" =>  array(array("1",30),array("2",40), array("3",40),array("9" ,20),array("10" , 25)),
    "5" =>  array(array("1",30),array("2",40), array("3",30), array("4" , 20), array("9" ,20),array("10" , 25),array("11" , 15)),
    "6" =>  array(array("1",20),array("2",30), array("3",30), array("4" , 20), array("5" ,20), array("9",20),array("10" , 25),array("11" , 25)),
    "7" =>  array(array("1",20),array("2",30), array("3",20), array("4" , 20), array("5" ,20), array("6" , 10), array("9",20),array("10" , 25),array("11" , 35)),
    "8" =>  array(array("1",20),array("2",30), array("3",20), array("4" , 20), array("5" ,20), array("6" , 20), array("7" ,20), array("8" ,20), array("9",20),array("10" , 25),array("11" , 45)),
    "9" => array(array("1",20),array("2",30), array("3",20), array("4" , 20), array("5" ,20), array("6" , 20), array("7" ,20), array("8" ,20), array("9",30),array("10" , 30),array("11" , 55),array("12" , 20),array("13" , 20))
);


function loop_funtion($piece,$type){
    
    $gear = $_SESSION['gear'];

    // Get Ingerdients of Piece
    $ingredients = json_decode($gear[$piece]['ingredients']);


    
    if ($ingredients == ""){
        // echo "Null";
        return;
    }
    if (count($ingredients) == 0){
        // If Piece Has No Ingredients, Add 1 of that gear

        if ($type == "complete+total"){

            $_SESSION['gear'][$piece]['complete'] += 1;
            $_SESSION['gear'][$piece]['total'] += 1;

        }
        else {
            $_SESSION['gear'][$piece][$type] += 1;
            // echo $type;
        }

    }
    else {
        // If Piece Had Ingredients Loop Through Them
        foreach($ingredients as $ingredient){
            // Get Amount and Id of The Ingredient
            $amount = $ingredient->amount;
            $id = $ingredient->gear;
            // Loop for amount of the Ingredient
            for ($i = 0; $i < $amount;$i++){
                // Restart Loop For Ingredient
                loop_funtion($id,$type);
            }
        }
    }
}

foreach($array as $character){
    $character_oid = $character[0];
    $target_gear_level = $character[2];
    $target_relic = $character[1];
    $sql = "SELECT * FROM `character_data`,`farming_character_occurance` WHERE (farming_character_occurance.occurance_id = '$character_oid' OR farming_character_occurance.temp_id = '$character_oid') AND farming_character_occurance.char_id = character_data.base_id;";
    $result = $conn->query($sql);
    while($data = $result->fetch_assoc()){
        $base_id = $data['char_id'];
        $gear_levels = json_decode($data['gear_levels']);
        // echo $base_id;
    }
    if (mysqli_num_rows($result) == 0 ){
        $gear_levels = array();
    }
    // print_r($character);

    $sql = "SELECT * FROM user_character_data WHERE username = '$username' AND defID = '$base_id'";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) == 0 ){
        $current_gear = 1;
        $current_relic = 0;
        $current_equiped = [];
    }
    while($data = $result->fetch_assoc()){
        $current_gear = $data['gear'];
        $current_relic = $data['relic'] - 2;
        $current_equiped = json_decode($data['equiped']);
    }
    
    

    if ($current_gear < $target_gear_level){
        
        for($x = 0; $x < ($current_gear - 1); $x++){
            $gear_in_level = $gear_levels[$x]->gear;
    
            // Loop Though Each Piece Of gear in Level
            foreach($gear_in_level as $piece_id){
                loop_funtion($piece_id,"complete");
            }
        }
        for($b = 0; $b < ($target_gear_level - 1); $b++){
           
            $gear_in_level = $gear_levels[$b]->gear;
            
            // }
            // Loop Though Each Piece Of gear in Level
            foreach($gear_in_level as $piece_id){
                
                loop_funtion($piece_id,"total");
                
            }
        }
        // Sort out current phase for complete
        foreach($current_equiped as $equiped_item){
            $equipted_id = $equiped_item->equipmentId;
            loop_funtion($equipted_id,"complete");
        }
        
    }
    else if ($current_gear == $target_gear_level){
        for($x = 0; $x < ($current_gear - 1); $x++){
            $gear_in_level = $gear_levels[$x]->gear;
    
            // Loop Though Each Piece Of gear in Level
            foreach($gear_in_level as $piece_id){
                loop_funtion($piece_id,"complete+total");
            }
        }
       
    }
    else if ($current_gear > $target_gear_level){
        for($x = 0; $x < $target_gear_level; $x++){
       
            $gear_in_level = $gear_levels[$x]->gear;
    
            // Loop Though Each Piece Of gear in Level
            foreach($gear_in_level as $piece_id){
                loop_funtion($piece_id,"complete+total");
            }
        }
    }

    if ($target_gear_level == 13){
        // $target_relic
        for ($x = 1; $x <= $target_relic; $x++){

            $in_teir = $base_relic_levels[$x];
           
            foreach($in_teir as $relic_piece){
  
                $piece_id_index = $relic_piece[0];
                $relic_amount = $relic_piece[1];
                $_SESSION['relic'][$piece_id_index]['total'] += $relic_amount;
            }
            
            
        }
    }
    if ($current_gear == 13 && $current_gear == $target_gear_level){
        if ($target_relic < $current_relic){
            for ($x = 1; $x <= $target_relic; $x++){

                $in_teir = $base_relic_levels[$x];
               
                foreach($in_teir as $relic_piece){
      
                    $piece_id_index = $relic_piece[0];
                    $relic_amount = $relic_piece[1];
                    $_SESSION['relic'][$piece_id_index]['complete'] += $relic_amount;
                }
                
            }
        }
        else {
            for ($x = 1; $x <= $current_relic; $x++){

                $in_teir = $base_relic_levels[$x];
               
                foreach($in_teir as $relic_piece){
      
                    $piece_id_index = $relic_piece[0];
                    $relic_amount = $relic_piece[1];
                    $_SESSION['relic'][$piece_id_index]['complete'] += $relic_amount;
                }
                
            }
        }
    }


    
}

echo json_encode(array($_SESSION['relic'],$_SESSION['gear'])) ;

// print_r($_SESSION['gear']['113Salvage']);


