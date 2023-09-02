<?php
include '../functions/db_connect.php';

if (isset($_POST['reset-password-submit'])){
    $selector = $_POST['selector'];
    $validator = $_POST['validator'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    
    if (empty($password) || empty($password_repeat)){
        header("Location: ../create-new-password.php?newpwd=empty&selector=$selector&validator=$validator");
        echo "here";
        exit();
    } 
    if ($password !== $password_repeat){
        header("Location: ../create-new-password.php?newpwd=pwdnotsame&selector=$selector&validator=$validator");
        echo "here1";
        exit();
    }

    $currentDate = date("U");

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        echo "There was an error 1";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if(!$row = mysqli_fetch_assoc($result)){
            echo "You need to re-submit your reset request.";
            exit();
        }
        else {
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);

            if ($tokenCheck === false){
                echo "You need to re-submit your reset request.";
                exit();

            }
            else if ($tokenCheck === true){
                $tokenEmail = $row['pwdResetEmail'];

                $sql = "SELECT * FROM users WHERE email = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt,$sql)){
                    echo "There was an error 2";
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if(!$row = mysqli_fetch_assoc($result)){
                        echo "There was an error! 3";
                        exit();
                    }
                    else {
                        $sql = "UPDATE users SET Password = ? WHERE email = ?";
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt,$sql)){
                            echo "There was an error 4";
                            exit();
                         } else {
                             //need to hash password here
                             //  $newPwdHash = password_hash($password,PASSWORD_DEFAULT);
                             $newPwdHash = $password;
                             mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
                             mysqli_stmt_execute($stmt);

                             $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
                             $stmt = mysqli_stmt_init($conn);
                             if (!mysqli_stmt_prepare($stmt,$sql)){
                                 echo "There was an error 5";
                                 exit();
                              } else {
                                  mysqli_stmt_bind_param($stmt,"s",$tokenEmail);
                                  mysqli_stmt_execute($stmt);
                                  header("Location: login.php?newpwd=passwordupdated");
                              }
                         }
                    }
                }


            }
        }
    }

} else {
    header("Location: index.php");
}



?>