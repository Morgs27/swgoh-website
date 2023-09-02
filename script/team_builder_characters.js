window.team = [];
window.gp = 0;
window.appended = [];
window.number = 0;
window.capital = "none";
window.type = "character";


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
    window.max_number = 8;

    x = document.getElementsByClassName('galactic_legend');
    var i;
    for (i = 0; i < x.length; i++) {
    
    x[i].style.opacity = "1";
    }


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

    var len = team.length;
    for (let i = 0;i < len;i++){
        display_id = team[i];
        document.getElementById(display_id).style.display = "block";
    }

}

function save_team(type,username,edit){
    var team = window.team;
    var gp= window.gp;
    if (window.number < 5){
        document.getElementById('error_container').innerHTML  = "You must have 5 characters to save a team!";
        setTimeout(function(){ document.getElementById('error_container').innerHTML  = ""; }, 3000);
    }
    else {
        if (edit === "Editor"){
            console.log(window.edit_team);
            $.ajax({
                url: "includes/edit_team.inc.php",
                method: "POST",   
                data: {team: team, gp: gp, type:type, combat: "characters", username: username,team_id:window.edit_team},
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
                data: {team: team, gp: gp, type:type, combat: "characters", username: username},
                success: function(data){
                    console.log(data);
                },
                error: function(errMsg) {
                    alert(JSON.stringify(errMsg));
                }
            });
        }
       
        window.location.href = "team_manager.php?characters&saved&" + type
    }
}

function save_team_guest(type,username,edit){
    var team = window.team;
    var guest_id = window.guest_id;
    var gp= window.gp;
    if (window.number < 5){
        document.getElementById('error_container').innerHTML  = "You must have 5 characters to save a team!";
        setTimeout(function(){ document.getElementById('error_container').innerHTML  = ""; }, 3000);
    }
    else {
        if (edit === "Editor"){
            console.log(window.edit_team);
            $.ajax({
                url: "includes/edit_team.inc.php",
                method: "POST",   
                data: {team: team, gp: gp, type:type, combat: "characters", username: username,team_id:window.edit_team},
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
                data: {team: team, gp: gp, type:type, combat: "characters", username: username, guest:guest_id},
                success: function(data){
                    console.log(data);
                },
                error: function(errMsg) {
                    alert(JSON.stringify(errMsg));
                }
            });
        }
       
        window.location.href = "team_manager.php?characters&saved&" + type
    }
}


function hide_message(id){
    document.getElementById('error_container').innerHTML = "";
}

