
function set_rank(rank){
    const base = {'T1':{'total': 0,'in':[]}, 'T2':{'total': 0,'in':[]}, 'B1':{'total': 0,'in':[]},  'B2':{'total': 0,'in':[]}}
    ranks = { 
        'carbonite' : {'T1':{'total': 1,'in':[null]}, 'T2':{'total': 1,'in':[null]}, 'B1':{'total': 1,'in':[null]},  'B2':{'total': 1,'in':[null]}},
        'bronzium' : {'T1':{'total': 2,'in':[null]}, 'T2':{'total': 1,'in':[null]}, 'B1':{'total': 2,'in':[null]},  'B2':{'total': 1,'in':[null]}},
        'chromium' : {'T1':{'total': 3,'in':[null]}, 'T2':{'total': 2,'in':[null]}, 'B1':{'total': 2,'in':[null]},  'B2':{'total': 2,'in':[null]}},
        'aurodium' : {'T1':{'total': 3,'in':[null]}, 'T2':{'total': 2,'in':[null]}, 'B1':{'total': 3,'in':[null]},  'B2':{'total': 3,'in':[null]}},
        'kyber' : {'T1':{'total': 4,'in':[null]}, 'T2':{'total': 3,'in':[null]}, 'B1':{'total': 4,'in':[null]},  'B2':{'total': 3,'in':[null]}}
    }

    data = ranks[rank];
    window.data = data;
    return data;
    console.log(data);
}

function update_numbers(){
    data = window.data;
    territories = ["T1","T2","B1","B2"];
    territories.forEach(territory => {
        container = document.getElementById(territory);
        filled = data[territory]['in'].length;
        if (data[territory]['in'][0] == null){
            filled = 0;
        }
        total = data[territory]['total'];
        string = filled + "/" + total;
        stat = container.querySelector(".stat");
        stat.innerHTML = string;
    })
}

function delete_ga_loadout(id){
    let loadout = document.getElementById(id);
    loadout.style.display = "none";
    $.ajax({
        url: "includes/delete_ga_plan.inc.php",
        method: "POST",   
        data: {id: id},
        success: function(data){
            console.log(data);
            
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}



function edit_ga_loadout(id,target){
    if (!target.closest(".fa-xmark")&& !target.closest(".delete_plan")){
        window.location.href = "edit_ga_loadout.php?i=" + id;
    }
}

function delete_tw_loadout(id){
    let loadout = document.getElementById(id);
    loadout.style.display = "none";
    $.ajax({
        url: "includes/delete_loadout.inc.php",
        method: "POST",   
        data: {id: id},
        success: function(data){
            console.log(data);
            
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}



function edit_tw_loadout(id,target){
    if (!target.closest(".fa-xmark")&& !target.closest(".delete_plan")){
        window.location.href = "edit_loadout_new.php?i=" + id;
    }
}


function edit_title(id){
    let input = document.querySelector(".edit_name_input");
    let name = input.value;
    console.log(name);
    if (name.length > 1){
        $.ajax({
            url: "includes/change_ga_name.inc.php",
            method: "POST",   
            data: {id:id,name:name},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
}

function change_rank_modal(){
    let ranks = document.querySelector(".rank_selector_container");
    let content = document.querySelector(".ga_content");
    let cover = document.querySelector(".content_cover");
    cover.classList.toggle("active");
    content.classList.toggle("hidden");
    ranks.classList.toggle("active");
    
    document.addEventListener(
        "click",
        function(event) {
        if (
        
        !event.target.closest(".export") &&  !event.target.closest(".rank_selector_container")) {
            close_change_rank()
        }
        },
        false
    )
}

function close_change_rank() {
    let ranks = document.querySelector(".rank_selector_container");
    let content = document.querySelector(".ga_content");
    content.classList.remove("hidden");
    ranks.classList.remove("active");
    let cover = document.querySelector(".content_cover");
    cover.classList.remove("active");
}

function change_rank(rank,id){
    let old_rank = window.rank;
    window.rank = rank;
    var ranks = document.querySelectorAll(".rank");
    ranks.forEach(ranks_i => {
        ranks_i.classList.remove("active");
    })
    let rank_element = document.getElementById(rank);
    rank_element.classList.toggle("active")

    let rank_img = document.getElementById("rank_img");
    var new_src = rank_img.src.replace(old_rank,rank);
    rank_img.src = new_src;

    teams = set_rank(rank);

    $.ajax({
        url: "includes/change_rank_ga.inc.php",
        method: "POST",   
        data: {id:id,rank:rank,teams:teams},
        success: function(data){
            console.log(data);
            window.location.href = "edit_ga_loadout.php?i=" + id;
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

}

function clear_territories(){
    territories = ["T1","T2","B1","B2"];
    territories.forEach(territory => {
        none_text = "<div class='territory_content none t1_chars'><div class='add_msg'></div></div>"
        if(territory == "B2"){
            none_text = "<div class='territory_content none B2_chars'><div class='add_msg'></div></div>"

        }
        if (territory == "T2"){
            none_text = "<div class='territory_content none ships_0'><div class='add_msg'></div></div>"

        }
        container = document.getElementById(territory);
        content = container.querySelector(".territory_content");
        container.removeChild(content)
        inner = container.innerHTML;
        if (territory == "T1" || territory == "T2"){
            new_inner = inner + none_text;
        }
        else {
            new_inner = none_text + inner;
        }
        container.innerHTML = new_inner;
        // Clear Territory

    })
    set_territory('T1');
}

function set_territory(territory){
    console.log(territory);
    window.territory = territory;
    territories = document.querySelectorAll(".ga_section");
    territories.forEach(to_hide => {
        to_hide.classList.remove("active");
    })
    team_territories = document.querySelectorAll(".teams_in");
    team_territories.forEach(team_territory => {
        team_territory.style.display = "none";
    })
    team_territory_id = "t_" + territory;
    team_territory = document.getElementById(team_territory_id);
    team_territory.style.display = "";
    container = document.getElementById(territory);
    container.classList.add("active");

    title_number = document.querySelector(".territory_number_title")
    number = container.querySelector(".territory_number");
    title_number.innerHTML = number.innerHTML;

    any = check_options(territory);

    var content = container.querySelector(".territory_content");

}


function check_options(territory){
    add_button = document.querySelector(".toggle_options");
    
    if (territory == "T2" ){
        option_container = document.querySelector(".ship_options");
        teams = option_container.querySelectorAll(".team_container");
    }
    else {
        option_container = document.querySelector(".char_options");
        teams = option_container.querySelectorAll(".team_container"); 
    }

    if (teams.length == 0){
        // add_button.style.opacity = 0.3;
        add_button.innerHTML = "You have run out of squads!</br> Click to create more squads."
        add_button.classList.add("none");
        add_button.onclick = () => {window.location.href = "team_manager.php?characters&ga"};
        return "none";
    }
    else {
        // add_button.style.opacity = "";
        add_button.innerHTML = "<i style = 'margin-right: 5px' class='fa-solid fa-plus'></i>Add Teams";
        add_button.classList.remove("none");
        add_button.onclick = () => {show_options()}
        return "some";
    }
}

function construct_in_territory(){
    images = document.querySelectorAll(".char");
    char_in = [];
    images.forEach(image => {
        image_id = image.id.slice(2);
        char_in.push(image_id);
    })
    window.char_in = char_in;

    images = document.querySelectorAll(".ship");
    ship_in = [];
    images.forEach(image => {
        image_id = image.id.slice(2);
        ship_in.push(image_id);
    })
    window.ship_in = ship_in;
}

function show_options(){
    construct_in_territory();
    // Open Modal With all Team Options
    // Hide Already in Territory
    // IF team is clicked add to territory
    modal = document.querySelector(".add_team_modal");
    modal.style.display = "block";
    cover = document.querySelector(".modal_cover");
    cover.classList.add("active");
    ga_content = document.querySelector(".ga_content");
    ga_content.classList.add("hidden2");

    char_options = modal.querySelector(".char_options")
    ship_options = modal.querySelector(".ship_options")

    if (window.territory == "T2"){
        char_options.style.display = "none";
        ship_options.style.display = "";
    }
    else {
        char_options.style.display = "";
        ship_options.style.display = "none";
    }

    char_in = window.char_in;
    ship_in = window.ship_in;

    char_teams = char_options.querySelectorAll(".team_container");
    ship_teams = ship_options.querySelectorAll(".team_container");

    char_in.forEach(team_id => {
        char_teams.forEach(char_team => {
            char_team_id = char_team.id.replace("ti_","");
            if (char_team_id == team_id){
                char_team.remove();
            }
        })
    })

    ship_in.forEach(team_id => {
        ship_teams.forEach(char_team => {
            char_team_id = char_team.id.replace("ti_","");
            if (char_team_id == team_id){
                char_team.remove();
            }
        })
    })
    
    document.addEventListener(
        "click",
        function(event) {
        if (
        
        !event.target.closest(".add_team_title") &&  !event.target.closest(".toggle_options") && !event.target.closest(".team_container")) {
            hide_options();
        }
        },
        false
    )
}

function hide_options(){
    modal = document.querySelector(".add_team_modal");
    modal.style.display = "none";
    cover = document.querySelector(".modal_cover");
    cover.classList.remove("active");
    ga_content = document.querySelector(".ga_content");
    ga_content.classList.remove("hidden2");
}


function remove_from_territory(id){

    console.log("remove " + id);
    territory = window.territory;
    territory_name = "t_" + territory;
    const container = document.getElementById(territory_name);
    team_container = document.getElementById("ti_" + id);

    container.removeChild(team_container);

    map_section = document.getElementById(territory);

    if (territory == "T2"){
        images = map_section.querySelectorAll(".ship");
        team_options = document.querySelector(".ship_options").querySelector(".teams");

    }
    else {
        images = map_section.querySelectorAll(".char");
        team_options = document.querySelector(".char_options").querySelector(".teams");

    }


    team_container.classList.add("hover");
    setTimeout( () => {
        if (territory == "T2"){
            team_container.onclick = () => {add_ship_territory("ti_" + id)}

        }
        else {
            team_container.onclick = () => {add_char_territory("ti_" + id)}
        }
    },100)
    team_container.querySelector(".delete_team_link").style.display = "none";
    team_options.appendChild(team_container);
    team_container.id = "ti_" + id;

    console.log(images);
    images.forEach(image => {
        image_id = image.id.slice(2);
        if (image_id == id){
            image.remove();
        }
    })

    number = map_section.querySelector(".stat");
    new_number = number.innerHTML;
    current = new_number[0];
    new_number = new_number.replace(current,current - 1);
    console.log(new_number);
    number.innerHTML = new_number;

    title_number = document.querySelector(".territory_number_title")
    number = map_section.querySelector(".territory_number");
    title_number.innerHTML = number.innerHTML;

    $.ajax({
        url: "includes/remove_team_ga.inc.php",
        method: "POST",   
        data: {teamid:id,territory:territory,loadout_id:window.loadout},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

    any = check_options(territory);

    teams = container.querySelectorAll('.team_container');

    if ((teams.length == 0) && (screen.width < 1100)){
        hide_teams();
    }

}

function add_ship_territory(id){

    act_id = id.replace("ti_","");

    console.log("add " + id);

    territory = window.territory;

    teams_container = document.getElementById("t_" + territory);

    team_options = document.querySelector(".ship_options");

    map_territory = document.getElementById(territory);

    stat = map_territory.querySelector(".stat");
    str = stat.innerHTML;
    current = str[0];
    target = str[2];

    if (current === target){
        return;
    }

    team = document.getElementById(id);

    team_options.querySelector(".teams").removeChild(team);

    team.classList.remove("hover");
    team.onclick = () => {};
    team.querySelector(".delete_team_link").style.display = "";

    teams_container.appendChild(team);

    

    territory_content = map_territory.querySelector(".territory_content");

    territory_content.classList.remove("none");

    capital = team.querySelector(".capital_ship_team");
    img = capital.querySelector(".ship_img_profile").src;

    territory_content.innerHTML = territory_content.innerHTML + "<img id = 's_" + act_id +"'class = 'ship' src = '" + img +"'>";
    
   
    next = parseInt(current) + 1;
    new_number = str.replace(current,next);
    stat.innerHTML = new_number;

    title_number = document.querySelector(".territory_number_title")
    number = map_territory.querySelector(".territory_number");
    title_number.innerHTML = number.innerHTML;

    $.ajax({
        url: "includes/add_team_ga_loadout.inc.php",
        method: "POST",   
        data: {teamid:act_id,territory:territory,loadout_id:window.loadout},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

    any = check_options(territory);

    if (any == "none"){
        hide_options();
    }

}

function add_char_territory(id){
    act_id = id.replace("ti_","");

    console.log("add " + id);
    territory = window.territory;

    teams_container = document.getElementById("t_" + territory);

    team_options = document.querySelector(".char_options");

    map_territory = document.getElementById(territory);

    stat = map_territory.querySelector(".stat");
    str = stat.innerHTML;
    current = str[0];
    target = str[2];

    if (current === target){
        return;
    }

    team = document.getElementById(id);

    team_options.querySelector(".teams").removeChild(team);

    team.classList.remove("hover");
    team.onclick = () => {};
    team.querySelector(".delete_team_link").style.display = "";

    teams_container.appendChild(team);


    territory_content = map_territory.querySelector(".territory_content");

    territory_content.classList.remove("none");

    img = team.querySelector(".character_profile_profile").firstChild.src;

    territory_content.innerHTML = territory_content.innerHTML + "<img id = 'c_" + act_id +"'class = 'char' src = '" + img +"'>";
    
    next = parseInt(current) + 1;
    new_number = str.replace(current,next);
    stat.innerHTML = new_number;

    title_number = document.querySelector(".territory_number_title")
    number = map_territory.querySelector(".territory_number");
    title_number.innerHTML = number.innerHTML;

    $.ajax({
        url: "includes/add_team_ga_loadout.inc.php",
        method: "POST",   
        data: {teamid:act_id,territory:territory,loadout_id:window.loadout},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

    any = check_options(territory);
    if (any == "none"){
        hide_options();
    }
}


function clear_territory(territory){
    console.log("clear");

    teams = document.getElementById("t_" + territory).querySelectorAll(".team_container");
    teams.forEach(team => {
        console.log("loop " + team.id)
        remove_from_territory(team.id.replace("ti_",""));
    })
}

function view_teams(){
    left = document.querySelector(".left_section");
    right = document.querySelector(".right_section");

    left.style.display = "none";
    right.style.display = "block";

    document.addEventListener(
        "click",
        function(event) {
        if (
        
        !event.target.closest(".right_title") &&  !event.target.closest(".view") && !event.target.closest(".team_container")) {
            hide_teams();
        }
        },
        false
    )
}

function hide_teams(){
    left = document.querySelector(".left_section");
    right = document.querySelector(".right_section");

    left.style.display = "flex";
    right.style.display = "none";
}