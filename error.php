<head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200&display=swap" rel="stylesheet">

</head>
<style>
    html{
        background: RGB(64, 68, 69);
    }
    .error_container{
        max-width: 500px;
        width: calc(90% - 60px);
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        padding: 30px;
        color: white;
        font-family: 'Verdana';
        display: flex;
        justify-content: center;
        align-items:center;
        flex-direction: column;
        text-align: center;
        
    }
    .sign_up_button {
        width: 200px;
        height: 40px;
        margin: 10px auto;
        margin-top: 20px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        transition: transform 0.5s;
        color: white;
        font-family: 'Raleway';
        font-size: 18px;
        text-decoration: none;
    }
    .sign_up_button i{
        margin-left: 10px;
    }
</style>
<div class = 'error_container'>
<img src='images/logo.png' style = 'filter: invert(100%);margin-bottom: 10px;width: 200px;'>
<div style = 'width: 80%;height: 1px;background:  RGBa(64, 68, 69,0.6);margin: 20px'></div>
<?php

if (isset($_GET['u'])){
    echo "ERROR: You must be logged in to view this content!";
}

if (isset($_GET['un'])){
    echo "ERROR: Players logged in cannot view this content!";
}


if (isset($_GET['g'])){
    echo "ERROR: You must be part of a guild to view this content!";
}

if (isset($_GET['gt'])){
    echo "ERROR: Players already in a guild cannot view this content!";
}

if (isset($_GET['p'])){
    echo "ERROR: You must have pro version to view this content!";
}

if (isset($_GET['v'])){
    echo "ERROR: Too few arguments passed to page!";
}

if (isset($_GET['f'])){
    echo "ERROR: Player not found!";
}

if (isset($_GET['nf'])){
    echo "ERROR: Argument not found!";
}

if (isset($_GET['nrg'])){
    echo "ERROR: You are attempting to view a loadout that does not belong to your guild!";
}

if (isset($_GET['nru'])){
    echo "ERROR: You are attempting to view a loadout that does not belong to you!";
}

if (isset($_GET['special'])){
    echo "ERROR: Please do not include any special characters in your input!";
}

if (isset($_GET['nn'])){
    echo "ERROR: Invalid Characters Detected";
}

if (isset($_GET['nud'])){
    echo "ERROR: No user data found. This may be because you left the page during the signup process.";
    
}

if (isset($_GET['twe'])){
    echo "ERROR: Error Viewing Loadout";
    
}


?>
<div style = 'width: 80%;height: 1px;background:  RGBa(64, 68, 69,0.6);margin: 20px'></div>


<a class = 'sign_up_button' href = 'index.php'>Home <i class="fa-solid fa-arrow-right"></i></a>

</div>