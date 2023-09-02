<?php 
include '../functions/db_connect.php';



if (isset($_POST['type'])){
    $username = $_POST['Username'];
    $type = $_POST['type'];
    $title = $_POST['title'];
    if (isset($_POST['content'])){
        $content = $_POST['content'];
    }
    else {
        $content = "";
    }
    if (isset($_POST['promoted'])){
        $promoted = $_POST['promoted'];
    }
    else {
        $promoted = "";
    }

    $random = rand();

    if (isset($_FILES['img'])){
        $target_dir = "../images/post_images/";
        $target_file = $target_dir . $random .  $username . $_FILES['img']['name'];
        move_uploaded_file($_FILES["img"]["tmp_name"],$target_file);
        $target_file = str_replace("../","",$target_file);
        if ($_FILES['img']['name'] == ""){
            $target_file = ""; 
        }
    }
    else {
        $target_file = "";
    }

    date_default_timezone_set("UTC");

    $current_date = date('Y-m-d H:i:s');

    $type = str_replace("_option", "", $type);

    $sql = "DELETE FROM tm_posts WHERE author = '$username'";
    $result = $conn->query($sql);

    $sql = "INSERT INTO tm_posts (post_type,title,content,image,promoted,author,created) VALUES
    ('$type','$title','$content','$target_file','$promoted','$username','$current_date')";
    $result = $conn->query($sql);


    header("location:../recruitment.php?successful");
}
else {
    header("location:../recruitment.php");
}