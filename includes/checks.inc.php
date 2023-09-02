<?php

    function check_logged_in(){
        if (isset($_SESSION['Username'])){

        }
        else {
            ?>
            <script>
                window.location.href = "error.php?u"
            </script>
            <?php
        }
    }
    function check_not_logged_in(){
        if (isset($_SESSION['Username'])){
            ?>
            <script>
                window.location.href = "error.php?un"
            </script>
            <?php
        }
        else {
            
        }
    }

    function check_logged_guild($conn){
        
        if (isset($_SESSION['Username'])){
            $in_guild = get_guild($conn,$_SESSION['Username']);
            if ($in_guild == 0){
                ?>
                <script>
                    window.location.href = "error.php?g"
                </script>
                <?php
            }
        }
        else {
            ?>
            <script>
                window.location.href = "error.php?u"
            </script>
            <?php
        }
    }
    function check_not_logged_guild($conn){
        if (isset($_SESSION['Username'])){
            $in_guild = get_guild($conn,$_SESSION['Username']);
            if ($in_guild == 0){

            }
            else {
                ?>
                <script>
                    window.location.href = "error.php?gt"
                </script>
                <?php
            }
        }
        else {
            ?>
            <script>
                window.location.href = "error.php?u"
            </script>
            <?php
        }
    }
    function check_get_variable($variable){
        if (isset($_GET[$variable])){

        }
        else {
            ?>
            <script>
                window.location.href = "error.php?v"
            </script>
            <?php
        }
    }

    function check_rows($result){
        if (mysqli_num_rows($result) == 0){
            ?>
            <script>
                window.location.href = "error.php?nf"
            </script>
            <?php
        }
    }

    function check_right_guild($conn,$username,$id){
        $sql = "SELECT * FROM loadouts WHERE loadoutID = '$id'";
        $result = $conn->query($sql);

        check_rows($result);
        while($data = $result->fetch_assoc()){
            $guild_id = $data['guild_id'];
        }

        $sql_u = "SELECT * FROM users WHERE Username = '$username'";
        $result_u = $conn->query($sql_u);
        while($data_u = $result_u->fetch_assoc()){
            $guild_id_u = $data_u['guild_id'];
        }

        if ($guild_id_u != $guild_id){
            ?>
            <script>
                window.location.href = "error.php?nrg"
            </script>
            <?php
        }
    }
    function check_right_user($conn,$loadout_id,$username){
        $sql = "SELECT * FROM ga_loadouts WHERE loadout_id = '$loadout_id'";
        $result = $conn->query($sql);
        check_rows($result);
        while($data = $result->fetch_assoc()){
            $loadout_username = $data['username'];
            if ($loadout_username != $username){
                ?>
                <script>
                    window.location.href = "error.php?nru"
                </script>
                <?php
            }
        }
    }
    function check_special($string){
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string))
        {
            ?>
            <script>
                window.location.href = "error.php?special"
            </script>
            <?php        
            }
    }
    function check_number($string){
        if (filter_var($string, FILTER_VALIDATE_INT) === false){
            ?>
            <script>
                window.location.href = "error.php?nn";
            </script>
            <?php
        }
    }
?>

<!-- onkeypress="return /[0-9a-zA-Z\s]/i.test(event.key)" -->