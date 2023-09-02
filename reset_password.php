<?php

include 'header.php';

?>

<div class = "signup-form">

<form action="reset_request.php" method = "post" >

<input type="text" placeholder="Enter your e-mail address..." name="email" >
</br>
<button type="submit"  name="reset_request_submit" class="signupbtn" style = 'width: 150px;font-size: 14px;'>Reset My Password</button>

</form>

<?php
if (isset($_GET['reset'])){
    if ($_GET['reset'] == "success"){
        echo '<p>Check your e-mail!</p>';
    }
}
?>


</div>