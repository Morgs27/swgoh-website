<?php
    include_once '../header.php';


    $loadout_id = $_POST['lid'];

    $territories = array("T1","T2","T3","T4","B1","B2","B3","B4","F1","F2");

    $add_values = $_POST['av'];
    $add_values_special = $_POST['avs'];
    $remove_values = $_POST['rv'];

    echo $add_values;
    echo $add_values_special;
    echo $remove_values;

    $add_values = json_decode($add_values);
    $add_values_special = json_decode($add_values_special);
    $remove_values = json_decode($remove_values);

    // print_r($add_values);
    // print_r($add_values_special);
    // print_r($remove_values);

    $existing_data = array();

    $sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
    $result = $conn->query($sql);
    while ($data = $result->fetch_assoc()){
        foreach($territories as $territory){
            $inner_data = explode(",",$data[$territory]);
            $existing_data[$territory] = $inner_data;
            // echo $territory;
            // print_r ($inner_data);
        }
    }

    $new_data = array();

    foreach($territories as $territory){
        $existing_territory_data = $existing_data[$territory];
        print_r($existing_territory_data);
        $remove_values_territory = $remove_values->$territory;
        print_r($remove_values_territory);
        $add_values_special_territory = $add_values_special->$territory;
        $add_values_territory = $add_values->$territory;
        
        foreach($existing_territory_data as $exist_id){
           
            foreach($remove_values_territory as $remove_id){
                if ($exist_id == $remove_id){
                    $position = array_search($exist_id,$existing_territory_data);
                    array_splice($existing_territory_data,$position,1);
                    echo $x;
                    print_r($existing_territory_data);
                }
               
            }
        }

        print_r($existing_territory_data);

        // foreach($territories as $territory_s){
        //     foreach($add_values_special->$territory_s as $special_id){
        //         foreach ($existing_territory_data as $exist_id){
        //             if ($exist_id == $special_id){
        //                 $position = array_search($exist_id,$existing_territory_data);
        //                 array_splice($existing_territory_data,$position,1);
        //             }

        //         }
        //     }
        // }

        $new_data_territory = array_merge($existing_territory_data,$add_values_special_territory,$add_values_territory);

        if ($new_data_territory[0] == null){
            array_splice($new_data_territory,0,1);
        }
 
        $new_data[$territory] = $new_data_territory;

        $data_str = implode(",",$new_data_territory);
        $sql_t = "UPDATE loadouts SET $territory = '$data_str' WHERE loadoutID = '$loadout_id'";
        $result_t = $conn->query($sql_t);

        
    }



?>



