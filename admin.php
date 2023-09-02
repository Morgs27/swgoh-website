<?php
include 'functions/class_init.php';

ob_start();
session_start();
include 'header.php';
include 'functions/upload_to_database.php';
if ($_SESSION["Username"] == "Morgs27"){
    ?>
    <style>
        form {
            width: 300px;
            display: flex;
            flex-direction: row;
            /*background: red;*/
            float: left;
            margin-right: 10px;
        }
        
    </style>

    <div class = "admin_buttons">
    <a href = 'paypal_admin.php'>Paypal Admin</a>
    <a href = 'google.php'>Google Admin</a>
    <a href = 'admin.php?refresh'>Refresh All Player Data</a>
    <a href = 'admin.php?refresh_guests'>Refresh Guests</a>
    <a href = 'admin.php?insert_guests'>Insert Guests</a>
    <a href = 'admin.php?recruitment'>Refresh Recruitment</a>
    <a href = 'admin.php?team'>Refresh All Player Character Team Data</a>
    <a href = 'admin.php?ship_team'>Refresh All Player Ship Team Data</a>
    <a href = 'admin.php?characters'>Refresh Character Data</a>
    <a href = 'admin.php?ships'>Refresh Ship Data</a>
    <a href = 'admin.php?gear'>Refresh Gear</a>
    <a href = 'admin.php?log'>Show Change Log</a>
    <a href = 'admin.php?clear_log'>Clear Log</a>
    <a href = 'admin.php?get_gl_requirements'>Insert GL requirements</a>
    <a href = 'memsec.php'>Memsec</a>

    </div>
    <form action="admin.php" method = "get">
        
    <input type="text" placeholder="Refresh Player Info" name="Username_refresh" >
    <button class='admin_button'  type="submit"  name="submit" >></button>
    </input>
    </form>
    
    <form action="admin.php" method = "get">
    <input type="text" placeholder="Refresh Player Team Gp's" name="Username_refresh_team" >	
    <button  class='admin_button' type="submit"  name="submit" >></button>
    </input>
    </form>
    
    <form action="admin.php" method = "get">
    <input type="text" placeholder="Refresh Player Ship Team Gp's" name="Username_refresh_team_ship" >	
    <button class='admin_button' type="submit"  name="submit" >></button>
    </input>
    </form>
    
     <form action="admin.php" method = "get">
        
    <input type="text" placeholder="Refresh Facebook Token" name="token" >
    <button class='admin_button'  type="submit"  name="submit" >></button>
    </input>
    </form>
    
    <?php
    echo "<div class = 'admin_results'>";
    if (isset($_GET['log'])){
        $sql = "SELECT * FROM change_log ORDER BY change_id DESC";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            echo $data['change_info'];
            echo "</br>";
        }
        ?>
        <script>
            setTimeout(() => {
                window.location.href = window.location.href;
            }, 6000);
        </script>
        <?php
        // sleep(5);
        // header("loaction: admin.php?log");

    }
    if (isset($_GET['clear_log'])){
        $sql = "DELETE FROM change_log";
        $result = $conn->query($sql);
    }
     if (isset($_GET['token'])){
            $curl = curl_init();
            
            $acess_token = $_GET['token'];
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://graph.facebook.com/v14.0/oauth/access_token?grant_type=fb_exchange_token&client_id=5463163363765318&client_secret=4cec8d32361bb6ec2c5fcf386196450c&fb_exchange_token=' . $acess_token,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            echo $response;
            
            $response = json_decode($response);
            
            $access_token = $response->access_token;
            
            $sql = "DELETE FROM facebook_acess_token";
            $result = $conn->query($sql);
            
            echo $sql;
            
            $sql = "INSERT INTO facebook_acess_token (token) VALUES ('$access_token')";
            $result = $conn->query($sql);
            echo $sql;
    }
    
    if (isset($_GET['refresh'])){
        ?>
        <script>
            $.ajax({
                url: "includes/update_all_users.php",
                method: "POST",   
                data: {username:window.username},
                success: function(info){
                    console.log(info)
                },
                error: function(errMsg) {
                    alert(JSON.stringify(errMsg));
                }
            });
        </script>
        <?php
        //  header("location:admin.php");
    }
    if (isset($_GET['insert_guests'])){
       insert_guests($conn);
    }
    if (isset($_GET['refresh_guests'])){
        ?>
        <script>
            $.ajax({
                url: "includes/update_guest_accounts.inc.php",
                method: "POST",   
                data: {},
                success: function(info){
                    console.log(info)
                },
                error: function(errMsg) {
                    alert(JSON.stringify(errMsg));
                }
            });
        </script>
        <?php
        //  header("location:admin.php");
    }
    if (isset($_GET['recruitment'])){
        include_once 'includes/update_recruitment_posts.php';
    }
    if (isset($_GET['gear'])){
        insert_gear_data($conn);
        //  header("location:admin.php");
    }
    if (isset($_GET['characters'])){
        insert_character_data($conn);
        //  header("location:admin.php");
    }
    if (isset($_GET['ships'])){
        insert_ship_data($conn) ;
        //  header("location:admin.php");
    }
    if (isset($_GET['team'])){
        update_team_gp_all($conn);
        // header("location:admin.php");
    }
    if (isset($_GET['ship_team'])){
        update_team_gp_all_ship(); 
        // header("location:admin.php");

    }
    if (isset($_GET['get_gl_requirements'])){
        insert_gl_requirements($conn);
        // header("location:admin.php");

    }
     if (isset($_GET['Username_refresh'])){
         
        $username = $_GET['Username_refresh'];
        echo $username;
        $ally_code = get_ally_code($conn,$username);
        // echo "   ";
        // echo $ally_code;
        // refresh_player_data($ally_code,$username,$conn);
        //  header("location:admin.php");
        ?>
        <script>
        let username = '<?php echo $username; ?>';
        let allycode = '<?php echo $ally_code; ?>';
        console.log('Sending to ', username , ally_code)
        $.ajax({
            url: "includes/refresh_user_data.inc.php",
            method: "POST",    
            data: {username: username, ally_code: allycode},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                console.log(errMsg)
            }
        });
        </script>
        <?php
    }
     if (isset($_GET['Username_refresh_team'])){
        $username = $_GET['Username_refresh_team'];
        $user_info = (getPlayerInfo_new($username,$conn));
        print_r($user_info);
        update_team_gp($conn,$user_info,$username);
        //  header("location:admin.php");
    }
     if (isset($_GET['Username_refresh_team_ship'])){
        $username = $_GET['Username_refresh_team_ship'];
        $user_info = (getPlayerInfo_new($username,$conn));
		$ship_info = (getPlayerInfo_ship($username,$conn));
		update_team_gp_ship($conn,$user_info,$ship_info,$username);
        //  header("location:admin.php");
    }
  
    echo "</div>";
}
else {
    header("location:index.php");
}
?>