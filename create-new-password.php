<?php
ob_start();
include_once 'header.php';
?>

<div class = "signup-form">

<?php

check_get_variable("selector");
check_get_variable("validator");

$selector = $_GET['selector'];
$validator = $_GET['validator'];

if (empty($selector) || empty($validator)){
    echo "Could not validate your request!";

} else {
    if (ctype_xdigit($selector)  !== false && ctype_xdigit($validator)  !== false){
        ?>
        
        <form action="includes/reset-password.inc.php" method = "post" >
            <input type = "hidden" name = "selector" value="<?php echo $selector;?>">
            <input type = "hidden" name = "validator" value="<?php echo $validator;?>">

            <input type="password" placeholder="Enter a new password..." name="password" >
            </br>
            <input type="password" placeholder="Repeat new password..." name="password_repeat" >
            </br>

            <button type="submit"  name="reset-password-submit" class="signupbtn" style = 'width: 150px;font-size: 14px;'>Reset Password</button>

        </form>

        <?php
    }
}

?>

<?php
if (isset($_GET['reset'])){
    if ($_GET['reset'] == "success"){
        echo '<p>Check your e-mail!</p>';
    }
}
if (isset($_GET['newpwd'])){
    if ($_GET['newpwd'] == "pwdnotsame"){
        echo '<p>Passwords do not match!</p>';
    }
}
?>


</div>