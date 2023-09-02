<?php 

function check_pro($username,$conn){

    $sql = "SELECT * FROM users WHERE Username = '$username' AND pro = 'true'";
    $result = $conn->query($sql);

    $pro = "false";
    $guild_id = get_guild($conn,$username);

    while ($data = $result->fetch_assoc()){
        $payed_by = $data['payed_by'];
        $to_notify = $data['to_notify'];
        $pro = $data['pro'];
        $guild_id = $data['guild_id'];
    }

    if ($pro == 'true'){
        // User Is Pro
        echo "user is pro";

        if ($payed_by != NULL){
            $search_username = $payed_by;
            $payed_by_id = get_guild($conn,$payed_by);
            if ($guild_id != $payed_by_id){
                $sql_u = "UPDATE users SET pro = '' , payed_by = '', to_notify = '' WHERE Username = '$username'";
                $result_u = $conn->query($sql_u);
                exit();
            }
        }
        else {
            $search_username = $username;
        }

        $is_pro = false;

        $sql = "SELECT * FROM paypal_subscriptions WHERE Username = '$search_username'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            update_subscription_info($data['subscription_id'],$conn);
        }
        $sql_c = "SELECT * FROM paypal_subscriptions WHERE Username = '$search_username'";
        $result_c = $conn->query($sql_c);
        while($data_c = $result_c->fetch_assoc()){   

            if ($data_c['status'] != 'ACTIVE'){
                echo "Checking not active";

                $sub_id = $data_c['subscription_id'];

                $last_payment = json_decode($data_c['last_payment']);
                $time = $last_payment->time;
                
                $time = str_replace("T"," ",$time);
                $time = str_replace("Z","",$time);
                $time = strtotime($time);
                $end_time = date('Y-m-d H:i:s', strtotime("+1 month", $time));

                date_default_timezone_set("GMT");

                $current_date = date('Y-m-d H:i:s');
                
                
                if ($current_date > $end_time){
                    // Pro subscription is no longer valid
                    $sql_d = "DELETE FROM paypal_subscriptions WHERE subscription_id = '$sub_id'";
                    $result_d = $conn->query($sql_d);
                    echo "Deleted: " . $sub_id . "</br";

                }
                else{
                    echo "pro=true";
                    $is_pro = true;
                }
            }
            else {
                echo "pro=true";
                $is_pro = true;
            }
        }
        echo $is_pro;
        if ($is_pro != true){
            echo "not pro";
            $sql_u = "UPDATE users SET pro = '' , payed_by = '', to_notify = '' WHERE Username = '$username'";
            $result_u = $conn->query($sql_u);
            check_pro($username,$conn);
            echo $sql_u;
        }
            
    }
    else {
        
        $payed_by = array();
        $is_pro = false;
        //User is not pro
        //Check if someone in guild is pro(guild version)
        $sql = "SELECT * FROM users WHERE guild_id = '$guild_id' AND pro = 'true'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $sql_p = "SELECT * FROM paypal_subscriptions WHERE Username = '{$data['Username']}' AND guild = 'true' ";
            $result_p = $conn->query($sql_p);
            while ($data_p = $result_p->fetch_assoc()){
                update_subscription_info($data_p['subscription_id'],$conn);
               
            }
            $sql_c = "SELECT * FROM paypal_subscriptions WHERE Username = '{$data['Username']}' AND guild = 'true' ";
            $result_c = $conn->query($sql_c);
            while($data_c = $result_c->fetch_assoc()){
                if ($data_c['status'] != 'ACTIVE'){
                    echo "Checking not active";

                    $sub_id = $data_c['subscription_id'];

                    $last_payment = json_decode($data_c['last_payment']);
                    $time = $last_payment->time;
                    
                    $time = str_replace("T"," ",$time);
                    $time = str_replace("Z","",$time);
                    $time = strtotime($time);
                    $end_time = date('Y-m-d H:i:s', strtotime("+1 month", $time));

                    date_default_timezone_set("GMT");

                    $current_date = date('Y-m-d H:i:s');
                    
                    
                    if ($current_date > $end_time){
                        // Pro subscription is no longer valid
                        $sql_d = "DELETE FROM paypal_subscriptions WHERE subscription_id = '$sub_id'";
                        $result_d = $conn->query($sql_d);
                        echo "Deleted: " . $sub_id . "</br";

                        //** */
                        // Could go through everyone else in guild to also check if they are still pro
                        //** */
                    }
                    else{
                        echo "pro=true";
                        $is_pro = true;
                        array_push($payed_by,$data_c['Username']);
                    }
                    
                }
                else {
                    echo "pro=true";
                    $is_pro = true;
                    array_push($payed_by,$data_c['Username']);
                }
            }
        }
        if ($is_pro == true){
            $payed_by = $payed_by[0];
            $sql_u = "UPDATE users SET pro = 'true' , payed_by = '$payed_by', to_notify = 'true' WHERE Username = '$username'";
            $result_u = $conn->query($sql_u);
            echo $sql_u;
        }
        else {
            echo "not pro";
        }
    }
}

function clear_all_pro($conn){
    $sql = "UPDATE users SET pro = '',payed_by = '',to_notify = ''";
    $result = $conn->query($sql);
    echo $sql;
}

function update_subscription_info($subscription_id,$conn){
    $pal = new pay_pal('1','2');

    $data = $pal->get_subscription_info($subscription_id);

    $data = json_decode($data);

    $status = $data->status;
    $status_update_time = $data->status_update_time;
    $start_time = $data->start_time;
    $outstanding_balance = $data->billing_info->outstanding_balance->value;
    $cycles_completed = $data->billing_info->cycle_executions[0]->cycles_completed;
    $last_payment = json_encode($data->billing_info->last_payment);

    $sql = "UPDATE paypal_subscriptions 
    SET status = '$status', status_update_time = '$status_update_time', start_time = '$start_time', 
    outstanding_balance = '$outstanding_balance', cycles_completed = '$cycles_completed', 
    last_payment = '$last_payment'
    WHERE subscription_id = '$subscription_id'";

    $result = $conn->query($sql);

}

function to_notify($username,$conn){

    $sql = "SELECT * FROM users WHERE Username = '$username'";
    $result = $conn->query($sql);
    while( $data = $result->fetch_assoc()){
        $pro = $data['pro'];
        $payed_by = $data['payed_by'];
        $to_notify = $data['to_notify'];
    }
    if (($pro == "true") and ($to_notify == "true")){
        return true;
    }
    else {
        return false;
    }
}

function is_pro($username,$conn){
    $sql = "SELECT * FROM users WHERE Username = '$username'";
    $result = $conn->query($sql);
    while( $data = $result->fetch_assoc()){
        return $data['pro'];
    }
}

function is_payed_by($username,$conn){
    $sql = "SELECT * FROM users WHERE Username = '$username'";
    $result = $conn->query($sql);
    while( $data = $result->fetch_assoc()){
        return $data['payed_by'];
    }
}

function get_subscription_id($username,$conn){
    $sql = "SELECT * FROM paypal_subscriptions WHERE Username = '$username'";
    $result = $conn->query($sql);
    while( $data = $result->fetch_assoc()){
        return $data['subscription_id'];
    }
}

function get_subscription_state($subscription_id,$conn){
    $sql = "SELECT * FROM paypal_subscriptions WHERE subscription_id = '$subscription_id'";
    $result = $conn->query($sql);
    while( $data = $result->fetch_assoc()){
        return $data['status'];
    }
}