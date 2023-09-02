
function remove(e_id){

    let saved = false;
    if (e_id.charAt(0) == "s"){
        e_id = e_id.slice(2);
        console.log(e_id);
        saved = true;
    }
    console.log("remove = >" + e_id);
    let territory = window.active_territory;
    var outer = "outer_" + e_id;

    var content = document.getElementById(outer).innerHTML;

    var content = content.replace("in_loadout_left","not_in_loadout_left");
    var content = content.replace("remove","move_team_loadout_left");

    if (saved == true){
        var content = content.replace("move_team_loadout","move_team_loadout_left")
    }

    
    document.getElementById(e_id).style.display= "none";

    if (territory === "F1" || territory === "F2"){
        document.getElementById(e_id).className = 'team_container team_container_ships'
    }
    else{
         document.getElementById(e_id).className = 'team_container'
    }
    // document.getElementById(e_id).className = 'team_container';
    if (territory === 'F1' || territory ==='F2'){
        $('#eln_ship_option_container').append(content);
    }
    else {
        $('#eln_character_option_container').append(content);
    }
    document.getElementById(e_id).onclick = function onclick(event){move_team_loadout_left(this.id)};
    var remove_id = e_id.replace("in_loadout_left_","");
    remove_id = remove_id.replace("not_in_loadout_left_","");
    (window.remove_values)[territory].push(remove_id);
    var number = (window.number)[territory];
    new_number = number - 1;
    (window.number)[territory] = new_number;
    document.getElementById("number_of_teams").innerHTML = new_number;
    // re_order();
    window.changes = true;
}
function move_team_loadout_left(id){
    let territory = window.active_territory;

    if (document.getElementById(id).classList.contains("team_container_ships")){
        let list = document.getElementById(id).classList;
        if (list[2] === "team_container_ships"){
            element_territory = (document.getElementById(id).className).slice(15,17);
        }
        else {
            element_territory = (document.getElementById(id).className).slice(36,38);

        }
        nt = (document.getElementById(id).className).slice(39,41);
    }
    else {
        element_territory = (document.getElementById(id).className).slice(15,17);
        nt = (document.getElementById(id).className).slice(18,20);
    }
    console.log( territory + "   " + element_territory)
    console.log(document.getElementById(id).className )

    

    var letter = id.charAt(0);
    var new_id = "";
  
    if (letter === 'i'){
        if (nt === "nt"){
            console.log("not_territory");
            new_id = id.replace("in_loadout_left","not_in_loadout_left");
            document.getElementById(id).style.display = "none";
            if (territory === "F1" || territory === "F2"){
                document.getElementById(id).className = 'team_container team_container_ships'
            }
            else{
                 document.getElementById(id).className = 'team_container'
            }
            // document.getElementById(id).className = 'team_container';
            document.getElementById(new_id).style.display = "block";
            var remove_id = id.replace("in_loadout_left_","");
            remove_id = remove_id.replace("not_in_loadout_left_","");
            // (window.remove_values)[territory].push(remove_id);
            var number = (window.number)[territory];
            new_number = number - 1;
            (window.number)[territory] = new_number;
            document.getElementById("number_of_teams").innerHTML = new_number;
            
            let add_special = window.add_values_special;
            for (let i = 0;i < add_special[territory].length ;i++){
                if (add_special[territory][i] === remove_id){
                    (window.add_values_special)[territory].splice(i,1);
                }
            }


        }
        else{
            console.log("territory");
            new_id = id.replace("in_loadout_left","not_in_loadout_left");
            document.getElementById(id).style.display = "none";
            if (territory === "F1" || territory === "F2"){
                document.getElementById(id).className = 'team_container team_container_ships'
            }
            else{
                 document.getElementById(id).className = 'team_container'
             }
            // document.getElementById(id).className = 'team_container';
            document.getElementById(new_id).style.display = "block";
            var remove_id = id.replace("in_loadout_left_","");
            remove_id = remove_id.replace("not_in_loadout_left_","");
            (window.remove_values)[territory].push(remove_id);
            var number = (window.number)[territory];
            new_number = number - 1;
            (window.number)[territory] = new_number;
            document.getElementById("number_of_teams").innerHTML = new_number;
            console.log(window.remove_values);
            let add_special = window.add_values_special;
            for (let i = 0;i < add_special[territory].length ;i++){
                if (add_special[territory][i] === remove_id){
                    (window.add_values_special)[territory].splice(i,1);
                }
            }
        }
       
        
    }
    if (letter === 'n'){
        if (territory !== element_territory){
            new_id = id.replace("not_in_loadout_left","in_loadout_left");
            document.getElementById(id).style.display = "none";
            document.getElementById(new_id).style.display = "block";

            if (territory === "F1" || territory === "F2"){
               document.getElementById(new_id).className = 'team_container team_container_ships '+ territory + " nt";
            }
            else{
                document.getElementById(new_id).className = 'team_container '+ territory + " nt";
            }

            var len = (window.remove_values)[element_territory].length;
            var remove_id = id.replace("not_in_loadout_left_","");
            remove_id = remove_id.replace("in_loadout_left_","");
            
            (window.add_values_special)[territory].push(remove_id);
  
            
        }
        else {
        new_id = id.replace("not_in_loadout_left","in_loadout_left");
        document.getElementById(id).style.display = "none";
        document.getElementById(new_id).style.display = "block";

        if (territory === "F1" || territory === "F2"){
            document.getElementById(new_id).className = 'team_container team_container_ships '+ territory
        }
        else{
            document.getElementById(new_id).className = 'team_container '+ territory
        }

        var len = (window.remove_values)[territory].length;
        var remove_id = id.replace("not_in_loadout_left_","");
        remove_id = remove_id.replace("in_loadout_left_","");
        
        for (let i = 0;i < len;i++){
            if ((window.remove_values)[territory][i] === remove_id){
                (window.remove_values)[territory].splice(i,1);
            }
        }
       
        }
        var number = (window.number)[territory];
        new_number = number + 1;
        (window.number)[territory] = new_number;
        document.getElementById("number_of_teams").innerHTML = new_number;
        
    }
    console.log("remove");
    console.log(window.remove_values);
    console.log("add");
    console.log(window.add_values_special);
    // re_order();
    window.changes = true;
}

function add_team_loadout(id){
    let saved = false;
    if (id.charAt(0) == "s"){
        id = id.slice(2);
        console.log(id);
        saved = true;
    }

    console.log("add_team_loadout->" + id)
    let territory = window.active_territory;
    var outer = "outer_" + id;
    console.log(outer)
    var content = document.getElementById(outer).innerHTML;
    var content = content.replace("team_id","in_loadout");
    let new_id = id.replace("team_id","in_loadout");
    console.log(new_id);
    var content= content.replace("add_team_loadout","move_team_loadout");

    if (saved === true){
        var content = content.replace("move_team_loadout_left","move_team_loadout")
    }

    console.log(content)
    $('#in_territory').append(content);
    document.getElementById(id).onclick = function onclick(event){move_team_loadout(this.id)};
    var add_id = id.replace("team_id_","");
    add_id = add_id.replace("in_loadout_","");
    window.add_values[territory].push(add_id);
    var number = (window.number)[territory];
    new_number = number + 1;
    (window.number)[territory] = new_number;
    document.getElementById("number_of_teams").innerHTML = new_number;
    if (territory === "F1" || territory === "F2"){
            document.getElementById(new_id).className = "team_container team_container_ships " + territory;

    }
    else {
    document.getElementById(new_id).className = "team_container " + territory;
    }
    document.getElementById(id).style.display= "none";
    document.getElementById(new_id).style.display = "";
    console.log(window.add_values);
    // re_order();
    window.changes = true;
}
            
function move_team_loadout(id){
    let territory = window.active_territory;
    var letter = id.charAt(0);
    var new_id = "";
    if (letter === 'i'){
        new_id = id.replace("in_loadout","team_id");
        document.getElementById(id).style.display = "none";
        if (territory === "F1" || territory === "F2"){
            document.getElementById(new_id).className = "team_container team_container_ships "
        }
        else {
            document.getElementById(new_id).className = "team_container "
        }
        document.getElementById(id).className = "team_container";

        document.getElementById(new_id).style.display = "block";
        var len = window.add_values[territory].length;
        for (let i = 0;i < len;i++){
            var add_id = id.replace("team_id_","");
            add_id = add_id.replace("in_loadout_","");
            if (window.add_values[territory][i] === add_id){
                window.add_values[territory].splice(i,1);
            }
        }
        var number = (window.number)[territory];
        new_number = number - 1;
        (window.number)[territory] = new_number;

        document.getElementById("number_of_teams").innerHTML = new_number;

    }
    if (letter === 't'){
        new_id = id.replace("team_id","in_loadout");
        document.getElementById(id).style.display = "none";
        document.getElementById(new_id).style.display = "block";
        var add_id = id.replace("team_id_","");
        add_id = add_id.replace("in_loadout_","");
        window.add_values[territory].push(add_id);
        var number = (window.number)[territory];
        new_number = number + 1;
        (window.number)[territory] = new_number;
        document.getElementById("number_of_teams").innerHTML = new_number;
        if (territory === "F1" || territory === "F2"){
            document.getElementById(new_id).className = "team_container team_container_ships " + territory;

        }
        else {
        document.getElementById(new_id).className = "team_container " + territory;
        }

    }
    console.log(window.add_values);
    // re_order();
    window.changes = true;
}


function startup(){
    let container = document.querySelector("#elnc");
    let loader = document.querySelector(".tw_container_loading");
    let selector = document.querySelector(".territory_selector_container");
    let save = document.querySelector(".edit_loadout_save_btn");
    let type_selector = document.querySelector(".type_selector");
    type_selector.style.display = "";
    container.style.display = "";
    loader.style.display = "none";
    selector.style.display = "";
    save.style.display = "";

    window.changes = false;
}

function insertAfter(referenceNode, newNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
  }

function re_order(){
    // console.log("re_order")
    // territory = window.active_territory
    // if (territory === 'F1' || territory ==='F2'){
    //     sorted = false;
    //     while (sorted === false){
    //         sorted = true;
    //         let container = document.getElementById("options").querySelector('#eln_ship_option_container');
    //         let teams = container.getElementsByClassName("team_container");
    //         for (i = 1; i < teams.length; i++) {
    //             let team = teams[i];
    //             let previous_team = teams[i-1];
    //             let team_gp = team.querySelector(".team_info").innerHTML;
    
    //             let previous_team_gp = previous_team.querySelector(".team_info").innerHTML;

    //             if (team_gp > previous_team_gp){
    //                 sorted = false;
    //                 if (team.parentElement.id === ("outer_" + team.id)){
    //                     team = team.parentElement;
    //                 }
    //                 if (previous_team.parentElement.id === ("outer_" + previous_team.id)){
    //                     previous_team = previous_team.parentElement;
    //                 }
    //                 previous_team.parentElement.removeChild(previous_team);
    //                 insertAfter(team,previous_team)
    //             }
    //         }
    //     }
    // }
    // else {
    //     sorted = false;
    //     while (sorted === false){
    //         sorted = true;
    //         let container = document.getElementById("options").querySelector('#eln_character_option_container');
    //         let teams = container.getElementsByClassName("team_container");
    //         for (i = 1; i < teams.length; i++) {
    //             let team = teams[i];
    //             let previous_team = teams[i-1];
    //             let team_gp = team.querySelector(".team_info").innerHTML;
    
    //             let previous_team_gp = previous_team.querySelector(".team_info").innerHTML;

    //             if (team_gp > previous_team_gp){
    //                 sorted = false;
    //                 if (team.parentElement.id === ("outer_" + team.id)){
    //                     team = team.parentElement;
    //                 }
    //                 if (previous_team.parentElement.id === ("outer_" + previous_team.id)){
    //                     previous_team = previous_team.parentElement;
    //                 }
    //                 previous_team.parentElement.removeChild(previous_team);
    //                 insertAfter(team,previous_team)
    //             }
    //         }
    //     }
    // }
    
}


function check_changes_saved(){
    console.log(window.changes);
    if (window.changes === true){
        console.log("here");
        let message = document.querySelector(".save_error");
        message.style.display = "flex";

        window.changes = false;
        
    }
    else {
        window.location.href = "tw_loadout_manager.php";
    }
}

function edit_title(id){
    let input = document.querySelector(".edit_name_input");
    let name = input.value;
    console.log(name);
    if (name.length > 1){
        $.ajax({
            url: "includes/change_loadout_name.inc.php",
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

function toggle_map(){
    map_container = document.querySelector(".toggled_map");
    map_container.classList.add("active");
    left = document.querySelector(".edit_loadout_new_left");
    right = document.querySelector(".edit_loadout_new_right");
    left.classList.add("hidden");
    right.classList.add("hidden");

    document.addEventListener(
        "click",
        function(event) {
        if (
        !event.target.closest(".map_selector") &&
        !event.target.closest(".toggled_map_inner") 
        ) {
           close_map();
        }
        },
        false
    )
}

function close_map(){
    map_container = document.querySelector(".toggled_map");
    map_container.classList.remove("active");
    left = document.querySelector(".edit_loadout_new_left");
    right = document.querySelector(".edit_loadout_new_right");
    left.classList.remove("hidden");
    right.classList.remove("hidden");
}

