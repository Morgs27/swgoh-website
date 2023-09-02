window.team = [];
window.gp = 0;
window.appended = [];
window.number = 0;
window.capital = "none";
window.type = "ship";

function ship_startup(){
    let ships = document.getElementsByClassName('ship_profile');
    for ( let i = 0; i < ships.length; i++) {
        if (ships[i].classList.contains("capital")){

        }
        else {
            ships[i].style.opacity = '0.3';
        }
    }
}




function toggle_team(){
    
    let team_toggle = document.querySelector('.toggle_team');
    let team_toggle_arrow = document.querySelector('.toggle_arrow');
    let team_contents = document.querySelector('.right_sidebar');
    let characters = document.querySelector('.allChar');
    team_toggle.classList.toggle('active');
    team_toggle_arrow.classList.toggle('down');
    team_contents.classList.toggle('active');
    characters.classList.toggle('active');
}	

function clear_team(){
    var team = window.team;
    window.team = [];
    window.gp = 0;
    window.appended = [];
    window.number = 0;
    
    document.getElementById('gp').innerHTML = "GP: 0";
    if (window.type === 'character'){
    document.getElementById('team_char_container').innerHTML = "";
    }
    if (window.type === 'ship'){
    window.capital = "none";
    document.getElementById('ship_char_container').innerHTML = "";
    x = document.getElementsByClassName('capital');
    var i;
    for (i = 0; i < x.length; i++) {
        x[i].style.opacity = "1";
        x[i].style.display = "block";
    }
    }

    let ships = document.getElementsByClassName('ship_profile');
    for ( let i = 0; i < ships.length; i++) {
    if (ships[i].classList.contains("capital")){

    }
    else {
        ships[i].style.opacity = '0.3';
    }
}

    var len = team.length;
    for (let i = 0;i < len;i++){
        display_id = team[i];
        document.getElementById(display_id).style.display = "block";
    }
}


function save_team_ship(type,username,edit){
    // window.alert("here");
    var capital = window.capital;
    if (capital === 'none'){
        
    }
    else if (window.number < 1){
        
    }
    else {
    var team = window.team;
    var gp = window.gp

    if (edit === "Editor"){
        console.log(window.edit_team);
        $.ajax({
            url: "includes/edit_team.inc.php",
            method: "POST",   
            data: {team: team, gp: gp, type:type, combat: "ships", username: username,team_id:window.edit_team,capital:capital},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
    else {
        $.ajax({
            url: "includes/save_team.inc.php",
            method: "POST",   
            data: {team: team, gp: gp, type:type, combat: "ships", username: username, capital: capital},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }

    
    window.location.href = "team_manager.php?ships&saved&" + type
   

    }
}

function save_team_ship_guest(type,username,edit){
    // window.alert("here");
    var capital = window.capital;
    if (capital === 'none'){
        
    }
    else if (window.number < 1){
        
    }
    else {
    var team = window.team;
    var gp = window.gp

    var guest_id = window.guest_id;

    if (edit === "Editor"){
        console.log(window.edit_team);
        $.ajax({
            url: "includes/edit_team.inc.php",
            method: "POST",   
            data: {team: team, gp: gp, type:type, combat: "ships", username: username,team_id:window.edit_team,capital:capital},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
    else {
        $.ajax({
            url: "includes/save_team.inc.php",
            method: "POST",   
            data: {team: team, gp: gp, type:type, combat: "ships", username: username, capital: capital,guest: guest_id},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }

    
    window.location.href = "team_manager.php?ships&saved&" + type
   

    }
}

function hide_message(id){
    document.getElementById('error_container').innerHTML = "";
    setTimeout(function(){ document.getElementById('error_container').innerHTML  = ""; }, 3000);
}