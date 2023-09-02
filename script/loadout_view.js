

let team_toggle = document.querySelector('.toggle_team');
let team_toggle_arrow = document.querySelector('.toggle_arrow');
let container = document.querySelector('.overflow_container_loadouts');
let characters = document.querySelector('.tw_loadout_team_container');
function toggle_teams(){
  team_toggle.classList.toggle('active');
  team_toggle_arrow.classList.toggle('down');
  container.classList.toggle('active');
  characters.classList.toggle('active');
}	

function change_territory(territory){
    territory = territory.split(" ")[1];
    
    inner_text = "[territory_map = " + territory + "]";

    territory_map = document.querySelector(inner_text);
    
    territory_map.classList.add('active');
    
    const territories = ["T1","T2","T3","T4","B1","B2","B3","B4","F1","F2"]
    territories.splice(territories.indexOf(territory),1);
    
    territories.forEach(territory => {
        inner_text = "[territory_map = " + territory + "]";

        territory_map = document.querySelector(inner_text);
        
        territory_map.classList.remove('active');
        const containers = document.getElementsByClassName(territory);
        for (let i = 0; i < containers.length ;i++ ){
            if (containers[i].classList.contains("team_container")){
                containers[i].style.display = "none";
            } 
        }
    })

    const show_containers = document.getElementsByClassName(territory);
    for (let i = 0; i < show_containers.length ;i++ ){
        if (show_containers[i].classList.contains("team_container")){
            show_containers[i].style.display = "";
        } 
    }

    let no_teams = document.querySelector(".no_teams");
    if (show_containers.length == 1){
        console.log("here");
        no_teams.style.display = "";
    }
    else {
        no_teams.style.display = "none";
    }

    let title = document.querySelector(".team_title_text");
    title.innerHTML = territory;

    if (screen.width < 1550){
        toggle_teams();
    }
}

change_territory("a T1")
let tw_container = document.querySelector(".tw_container")
let loading = document.querySelector(".tw_container_loading");
tw_container.style.display = "";
loading.style.display = "none";

window.my = false;

function check_loadout(list,id){
    
    const container = document.querySelector('[team_id="' + id + '"]');
    
    username = window.username;
    
    team_username = container.querySelectorAll(".team_info")[2].innerHTML;
    
    if (username != team_username){
        return;
    }

    territory = container.getAttribute("territory");
    inner_text = "[territory_map = " + territory + "]";

    territory_map = document.querySelector(inner_text);

    number = territory_map.querySelector('.after_tw_title').innerHTML;

    territory_checked_number = number.split('/')[0];

    if (list.contains("checked")){
        territory_new_number = parseInt(territory_checked_number) - 1;
    }
    else {
        territory_new_number = parseInt(territory_checked_number) + 1;
    }

    new_innerText = number.replace(territory_checked_number,territory_new_number);

    territory_map.querySelector('.after_tw_title').innerHTML = new_innerText;
    
    if (window.my === true){
        other_number = territory_map.querySelector('.after_tw_title').getAttribute('other_no');
        territory_checked_number = other_number.split('/')[0];

        if (list.contains("checked")){
            territory_new_number = parseInt(territory_checked_number) - 1;
        }
        else {
            territory_new_number = parseInt(territory_checked_number) + 1;
        }
    
        new_innerText = other_number.replace(territory_checked_number,territory_new_number);
    
        territory_map.querySelector('.after_tw_title').setAttribute('other_no',new_innerText);
    }

    if (list.contains("checked")){
        container.classList.remove("checked");
        checked = 'false';
    }
    else {
        container.classList.add("checked");
        checked = 'true';
    }

    if (list.contains("team_container_ships")){
        type = "ships";
    }
    else {
        type = "characters";
    }

    $.ajax({
        url: "includes/update_team_checked.inc.php",
        method: "POST",   
        data: {team_id:id,checked:checked,type:type,username:username},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
    // Ajax
}

function check_all_assignments(){
    
    $.ajax({
        url: "includes/check_all_assignments.inc.php",
        method: "POST",   
        data: {username : window.username},
        success: function(data){
            console.log(data);
            window.location.href = window.location.href;
            
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
    
}

function toggle_my_teams(){
    toggle = document.querySelector(".vs_option")
    var territories = document.querySelectorAll(".after_tw_title");
    if (!toggle.classList.contains("active")){
        // Show My assignments
        toggle.classList.add("active");
        // toggle.innerHTML = '<i class="fa-solid fa-arrow-left"></i>Guild Assignments';
        document.documentElement.style.setProperty('--display', 'none');
        territories.forEach(territory =>{
            previous_no = territory.innerHTML;
            new_number = territory.getAttribute("other_no");
            territory.setAttribute("other_no",previous_no);
            territory.innerHTML = new_number;
        })
        window.my = true;
    }
    else {
        // show guild assignments
        toggle.classList.remove("active");
        // toggle.innerHTML = '<i class="fa-solid fa-filter"></i> My Assignments';
        document.documentElement.style.setProperty('--display', 'block');
        territories.forEach(territory =>{
            previous_no = territory.innerHTML;
            new_number = territory.getAttribute("other_no");
            territory.setAttribute("other_no",previous_no);
            territory.innerHTML = new_number;
        })
        window.my = false;
    }
}

function toggle_hide_checked(){
    toggle = document.querySelectorAll(".vs_option")[1];
    if (!toggle.classList.contains("active")){
        // Hide Checked Teams
        const checked_teams = document.querySelectorAll(".checked");
        checked_teams.forEach(team => {
            team.classList.add("hidden");
        })
        toggle.classList.add("active");
        // toggle.innerHTML = '<i class="fa-solid fa-eye"></i> Show Checked';
    }
    else {
        // Show Checked Teams
         const checked_teams = document.querySelectorAll(".checked");
        checked_teams.forEach(team => {
            team.classList.remove("hidden");
        })
        toggle.classList.remove("active");
        // toggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i> Hide Checked';
    }
}