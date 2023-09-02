<?php
ob_start();
include 'header.php';


if (isset($_POST['reset_request_submit'])){

    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = "https://swgohteammanager.com/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);

    $expires = date("U") + 1800;

    $userEmail = $_POST['email'];

    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        echo "There was an error";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO pwdReset (pwdResetEmail,pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        echo "There was an error";
        exit();
    } else {
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);


    $to = $userEmail;

    $subject = 'Reset your password for SWGOH Team Manager';

    $message = '<p> We recieved a password reset request. The link to reset your password is below. If you didnt make this request, you can ignore this email</p>';
    $message .= '<p>Here is your password reset link: </br>';
    $message .= '<a href = "' . $url. '">' . $url. '</a></p>';

    $headers = "From: SWGOH Team Manager <swgohteammanager@gmail.com>\r\n";
    $headers .= "Reply-To: swgohteammanager@gmail.com\r\n";
    $headers .= "Content-type: text/html\r\n";


    mail($to, $subject, $message, $headers);
    //  echo $to;
    // echo $subject;
    // echo $message;
    // echo $headers;
    header("location: reset_password.php?reset=success");



}
else{
    header("location: index.php");
}