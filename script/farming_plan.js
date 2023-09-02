
function get_rows(){
 
    let container = document.querySelector(".container");
    let height = container.offsetHeight - 105;
    let rows = Math.floor(height / 180);
    console.log(height + "   " + rows);
    document.documentElement.style.setProperty('--rows', `${rows}`);

}

window.addEventListener('resize', () => {
    get_rows()

});

function update_column(stage_id){
    let container = document.getElementById(stage_id);
    let width = container.offsetWidth - 105;
    let columns = Math.floor(width / 320);
    console.log(width + "   " + columns);
    document.documentElement.style.setProperty('--column', `${columns}`);
    if (columns == 0){
        document.documentElement.style.setProperty('--width', `280px`);
    }
    else {
        nwidth = (320 * columns) + 10
        document.documentElement.style.setProperty('--width', `${nwidth}px`);
    }
    
}


function edit_title(id){
    let input = document.querySelector(".edit_name_input");
    let name = input.value;
    console.log(name);
    if (name.length > 1){
        $.ajax({
            url: "includes/change_plan_name.inc.php",
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


function new_stage(id){
    let temp_id = Math.floor(Math.random() * 10000000) + 1;
    console.log(temp_id);
    let container = document.querySelector(".container")
    let new_button = document.querySelector(".new_stage");
    let stage_example = document.querySelector(".example_stage")
    let new_stage = stage_example.innerHTML;
    $.ajax({
        url: "includes/new_stage.inc.php",
        method: "POST",   
        data: {id:id,temp_id:temp_id},
        success: function(data){
            $.ajax({
                url: "includes/get_stage_id.inc.php",
                method: "POST",   
                data: {temp_id:temp_id},
                success: function(data){
                    stage_id = data;
                    console.log("Stage Id = " + stage_id);
                    new_stage = new_stage.replaceAll("example_id", stage_id);
                    new_stage = new_stage.replaceAll("req_id", () => '#' + Math.floor(Math.random()*16777215).toString(16));
                    console.log(new_stage);
                    new_button.insertAdjacentHTML( 'beforebegin' , new_stage)
                   
                    let input = document.getElementById(stage_id).querySelector(".stage_title");
                    input.focus();
                },
                error: function(errMsg) {
                    alert(JSON.stringify(errMsg));
                }
            });
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
     
}


function change_stage_name(id,type){
    let continer = document.getElementById(id);
    let input = continer.querySelector(".stage_title");
    let name = input.value;
    console.log(name);
    if (name.length > 1){
        $.ajax({
            url: "includes/change_stage_name.inc.php",
            method: "POST",   
            data: {id:id,type:type,name:name},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
}


function change_gear(id){
    let container = document.getElementById(id);
    container.classList.remove("preset");
    container.classList.toggle("gear");

    spinner = container.querySelector(".fp_spinner_container");
    items_container = container.querySelector(".gear_items");

    spinner_relic = container.querySelector(".fp_spinner_container_relic");
    items_container_relic = container.getElementsByClassName("gear_items")[1];

    spinner.style.display = "flex";
    items_container.style.display = "none";
    spinner_relic.style.display = "flex";
    items_container_relic.style.display = "none";
   
    let characters = container.querySelectorAll(".character");

    if (characters.length == 0 ){
        spinner.style.display = "none";
        items_container.style.display = "";
        spinner_relic.style.display = "none";
        items_container_relic.style.display = "";
        container.querySelector(".gear_items").innerHTML = "<div class = 'no_results_msg'>No Gear Required</div>";
        container.querySelector("#no_relics").style.display = "";
    }

    const array = [];
    characters.forEach(character =>{
        if (character.style.display != "none"){
        var relic = character.querySelector(".relic_number").innerHTML;
        var gear_str = character.querySelector(".gear_img").src;
        gear = gear_str.split("-g")[1];
        gear = gear.split("-")[0];
        gear = gear.split(".")[0];
        var id = character.id;
        const char_array = [id,relic,gear];
        array.push(char_array);
        }
    })
    console.log(array);
    if (array.length == 0) {
        const gear_items = container.querySelectorAll(".gear_item_gear");
        gear_items.forEach(item =>{
            item.style.display = "none";
        })
        const relic_items = container.querySelectorAll(".gear_item_relic");
            relic_items.forEach(item =>{
                item.style.display = "none";
        })

    }
    else {
    $.ajax({
        url: "includes/calculate_required_gear.inc.php",
        method: "POST",   
        data: {characters:array},
        success: function(data){
            console.log(data);
            data = JSON.parse(data);
            gear_data = data[1];
            relic_data = data[0];
            var not_complete = [];
            var complete_array = [];
            const gear_items = container.querySelectorAll(".gear_item_gear");
            var some_gear = false;
            var no_needed = true;
            gear_items.forEach(item =>{
                
                var item_id = item.id.substring(2)
                var item_info = gear_data[item_id];
                var total = item_info['total'];
                var complete = item_info['complete'];
                var percent = (complete/total) * 100;
                var remaining = total - complete;
                if (total !== complete){
                    no_needed = false;
                }
                if (total != 0){
                    if (total == complete){
                        item.style.display = "none";
                        complete_array.push(item);
                    }
                    else {
                        item.style.display = "";
                        item.querySelector(".remaining").innerHTML = remaining;
                        item.querySelector(".gear_progress").style.width = percent + "%";
                        not_complete.push(item);
                        some_gear = true;
                    }
 
                }
            })
            // console.log(complete);
            // Sort Items

            container.querySelector(".gear_items").innerHTML = "";

            if (no_needed == true){
                container.querySelector(".gear_items").innerHTML = "<div class = 'no_results_msg'>No Gear Required</div>";
            }

            not_complete.sort((a,b) => {
                var compA = a.querySelector(".remaining").innerHTML;
                var compB = b.querySelector(".remaining").innerHTML;
                return compB - compA;
            });

            not_complete.forEach(element => {
                container.querySelector(".gear_items").appendChild(element);
            });

            complete_array.forEach(element => {
                container.querySelector(".gear_items").appendChild(element);
            });

           

            // Relic Stuff
            const relic_items = container.querySelectorAll(".gear_item_relic");
            var no_relics_needed = true;
            relic_items.forEach(item =>{
                var item_id = item.id.substring(2)
                var item_info = relic_data[item_id];
                var total = item_info['total'];
                var complete = item_info['complete'];
                var percent = (complete/total) * 100;
                var remaining = total - complete;
                console.log("Total " + total + " Complete " + complete);
                if (total !== complete){
                    no_relics_needed = false;
                }
                if (total != 0){
                    if (total == complete){
                        item.style.display = "none";
                    }
                    else {
                        item.style.display = "";
                        item.querySelector(".remaining").innerHTML = remaining;
                        item.querySelector(".gear_progress").style.width = percent + "%";
                        
                    }
 
                }
                else {
                    item.style.display = "none"; 
                }
            })

            if (no_relics_needed == true){
                container.querySelector("#no_relics").style.display = "";
            }
            else {
                container.querySelector("#no_relics").style.display = "none";

            }
            spinner.style.display = "none";
            items_container.style.display = "";
            spinner_relic.style.display = "none";
            items_container_relic.style.display = "";

            
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
    }

   

}

function change_preset(id){
    update_column(id)

    let all_containers = document.querySelectorAll(".stage_container");
    all_containers.forEach(unit =>{
        unit.classList.remove("preset");
    })
    
    let container = document.getElementById(id);
    container.classList.remove("gear");

    container.classList.toggle("preset");

}


function toggle_complete(id){
    let container = document.getElementById(id);
    
    let button = container.querySelector("#complete");
    
    if (container.classList.contains("complete")){
        button.innerHTML = '<i class="fa-solid fa-circle-check"></i> Complete'
        type = "remove";
    }
    else {
        button.innerHTML = '<i class="fa-solid fa-xmark"></i>'
        type = "add";
    }

    container.classList.toggle("complete");

    $.ajax({
        url: "includes/toggle_complete.inc.php",
        method: "POST",   
        data: {id:id,type:type},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}


function add_character(id){
    console.log("add " + id)
    let container = document.querySelector(".container");
    let modal = document.querySelector(".add_character_modal");
    container.classList.add("hide");
    modal.style.display = "block";
    let cover = container.querySelector(".container_cover");
    cover.style.display = "block";
    let title = modal.querySelector(".modal_title");
    let stage_name = document.getElementById(id).querySelector(".stage_title").value;

    let input = document.querySelector("#input_filter")
    input.focus();
    
    let add_button = document.querySelector(".add_button");
    add_button.id = id;
    console.log(add_button.id)

    modal = document.querySelector(".add_character_modal");
    let characters = modal.querySelectorAll(".character_option")
    const to_add = [];
    for (let i = 0; i < characters.length; i++){
        if (characters[i].classList.contains('active')){
            characters[i].classList.remove("active");
        }
    }
}

function close_modal(extra){
    let container = document.querySelector(".container");
    let modal = document.querySelector(".add_character_modal");
    modal.style.display = "";
    let cover = container.querySelector(".container_cover");
    cover.style.display = "";
    if (extra === true){
        let container = document.querySelector(".container");
        container.classList.remove("hide");
    }
}

document.addEventListener(
    "click",
    function(event) {
    console.log(event.target)
    if (
    !event.target.closest(".add_character_modal") &&
    !event.target.closest(".new_character_container") &&
    !event.target.closest(".link_area") &&
    !event.target.closest(".export") &&
    !event.target.closest(".copy_link") &&
    !event.target.closest(".copy_icon")
    ) {
        let container = document.querySelector(".container");
        container.classList.remove("hide");
        close_modal()
        close_link();
    }
    if (event.target.closest(".export")){
        close_modal();
        
    }
    },
    false
)

function filter_characters(){
    let filter = document.querySelector("#input_filter").value.toUpperCase();
    console.log(filter);
    let modal = document.querySelector(".add_character_modal");
    let characters = modal.querySelectorAll(".character_option")
    console.log(characters);
    for (let i = 0; i < characters.length; i++){
        const name = characters[i].querySelector(".character_option_name").innerHTML;
        const categories = characters[i].querySelector(".character_option_categories").innerHTML;
        console.log(name);
        console.log(categories);
        if ((name.toUpperCase().indexOf(filter) > -1) || (categories.toUpperCase().indexOf(filter) > -1)){
            characters[i].style.display = "";
        }
        else {
            
            characters[i].style.display = "none";
        }
    }
}

function toggle_add_character(id){
    let character = document.getElementById(id);
    character.classList.toggle("active");

}

function add_characters(id){
    get_rows()
    console.log(id);
    let modal = document.querySelector(".add_character_modal");
    let characters = modal.querySelectorAll(".character_option")
    const to_add = [];
    const temp_ids = [];
    for (let i = 0; i < characters.length; i++){
        if (characters[i].classList.contains('active')){
            const temp_id = Math.random();
            temp_ids.push(temp_id);
            to_add.push(characters[i].id);
        }
    }
    console.log(to_add);

    $.ajax({
        url: "includes/add_characters_stage.inc.php",
        method: "POST",   
        data: {id:id,to_add:to_add,temps:temp_ids},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });


    let example_character = document.querySelector(".example_character").innerHTML;
    let container = document.getElementById(id).querySelector(".characters_container");
    
    let x = 0;
    to_add.forEach(char_id => {
        console.log(char_id);

        temp_id = temp_ids[x];
        console.log(temp_id);

        let character = document.getElementById(char_id);
        let img = character.querySelector(".character_option_img").src;
        let name = character.querySelector(".character_option_name").innerHTML;
        let alignment = character.querySelector(".character_option_img").classList[1];
        let alignment_text = alignment + " Side";
        console.log(alignment);
        new_character = example_character.replaceAll("$img",img);
        new_character = new_character.replaceAll("$name",name);
        new_character = new_character.replaceAll("$temp_id",temp_id)
        new_character = new_character.replaceAll("$alignment",alignment_text);
        console.log(temp_id);

        let characters_instage = container.getElementsByClassName("character");
        if (characters_instage.length == 0){
            let text = container.innerHTML;
            text = new_character + text;
            container.innerHTML = text;
        }
        else {
            let last = characters_instage[characters_instage.length - 1];
            last.insertAdjacentHTML( 'afterend' , new_character)
        }
        x += 1;
    })

    container = document.querySelector(".container");
    container.classList.remove("hide");
    close_modal();

    
}

function gear_character_up(occurance_id){
    let character = document.getElementById(occurance_id);
    let gear_class = character.querySelector(".gear_img");
    let img_src = gear_class.src;
    console.log(img_src);

    let part = img_src.split("-g")[1];
    let gear_number = part.split(".png")[0];
    console.log(gear_number);

    new_gear_number = parseInt(gear_number) + 1;
    let change_number = new_gear_number;

    let relic_level = character.querySelector(".relic_number");
    let relic_number = relic_level.innerHTML;

    if (new_gear_number == 13){
        let alignment = gear_class.parentElement.id;
        if (alignment == "Light Side"){
            abrv = "ls";
        }   
        else {
            abrv = "ds";
        }
        new_gear_number = new_gear_number + "-" + abrv;
        console.log(new_gear_number);
        console.log(alignment);
    }
    else if (gear_number.startsWith("13")){
        if (gear_number.indexOf("-relic") > -1){
            new_gear_number = gear_number;
            new_relic_number = parseInt(relic_number) + 1;
            if (new_relic_number > 9){
                return;
            }
            relic_level.style.display = "flex";
            relic_level.innerHTML = new_relic_number;
        }
        else {
            new_gear_number = gear_number + "-relic";
            relic_level.style.display = "flex";
            relic_level.innerHTML = "1";
        }
        change_number = 13;
    }
 

    let new_src = img_src.replace(gear_number,new_gear_number)

    character.querySelector(".gear_img").src = new_src;

    console.log("New Gear: " + change_number)
    console.log("New Relic: " +  relic_level.innerHTML);

    $.ajax({
        url: "includes/alter_character_gear.inc.php",
        method: "POST",   
        data: {id:occurance_id,gear:change_number,relic:relic_level.innerHTML},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

}

function gear_character_double_up(occurance_id){
    let character = document.getElementById(occurance_id);
    let gear_class = character.querySelector(".gear_img");
    let img_src = gear_class.src;
    console.log(img_src);

    let part = img_src.split("-g")[0];
    let gear = img_src.split("-g")[1];
    gear = gear.split("-")[0];
    gear = gear.split(".")[0];
    console.log(gear);

    let alignment = gear_class.parentElement.id;
    if (alignment == "Light Side"){
        abrv = "ls";
    }   
    else {
        abrv = "ds";
    }

    let relic_level = character.querySelector(".relic_number");

    if (gear == 13){
        var relic = 9;
        var new_src = part + "-g13" + "-" + abrv + "-relic.png";
        relic_level.style.display = "flex";
        relic_level.innerHTML = "9";
    }
    else {
        var relic = 0;
        var new_src = part + "-g13" + "-" + abrv + ".png";
        relic_level.style.display = "none";
    }
    
    console.log(new_src);

    character.querySelector(".gear_img").src = new_src;

    
    

    $.ajax({
        url: "includes/alter_character_gear.inc.php",
        method: "POST",   
        data: {id:occurance_id,gear:"13",relic:relic},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}


function gear_character_double_down(occurance_id){
    let character = document.getElementById(occurance_id);
    let gear_class = character.querySelector(".gear_img");
    let img_src = gear_class.src;
    console.log(img_src);

    let part = img_src.split("-g")[0];
    let gear = img_src.split("-g")[1];
    gear = gear.split("-")[0];
    gear = gear.split(".")[0];
    console.log(gear);

    // let new_src = part + "-g1.png";

    // character.querySelector(".gear_img").src = new_src;

    // relic_level.style.display = "none";

    let alignment = gear_class.parentElement.id;
    if (alignment == "Light Side"){
        abrv = "ls";
    }   
    else {
        abrv = "ds";
    }

    let relic_level = character.querySelector(".relic_number");

    if (gear == 13 && (img_src.indexOf("-relic") > -1)){
        var new_gear = 13;
        var new_src = part + "-g13" + "-" + abrv + ".png";
    }
    else {
        var new_gear = 1;
        var new_src = part + "-g1.png";
    }

    relic_level.style.display = "none";
    
    console.log(new_src);

    character.querySelector(".gear_img").src = new_src;

    $.ajax({
        url: "includes/alter_character_gear.inc.php",
        method: "POST",   
        data: {id:occurance_id,gear:new_gear,relic:"0"},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}

function check_character(occurance_id){
    let character = document.getElementById(occurance_id);
    character.classList.toggle("checked");
}

function gear_character_down(occurance_id){
    let character = document.getElementById(occurance_id);
    let gear_class = character.querySelector(".gear_img");
    let img_src = gear_class.src;
    console.log(img_src);

    let part = img_src.split("-g")[1];
    let gear_number = part.split(".png")[0];
    console.log(gear_number);

    new_gear_number = parseInt(gear_number) - 1;
    let change_number = new_gear_number;

    let relic_level = character.querySelector(".relic_number");
    let relic_number = relic_level.innerHTML;

    if (new_gear_number < 1){
        return;
    }

    if (gear_number.startsWith("13")){
        if (gear_number.indexOf("-relic") > -1){
            new_relic_number = parseInt(relic_number) - 1;
            if (new_relic_number  ==  0){
                new_gear_number = gear_number.replace("-relic","");
                relic_level.style.display = "none";
                relic_level.innerHTML = "0";
                change_number = "13";
            }
            else {
                new_gear_number = gear_number;
                relic_level.style.display = "flex";
                relic_level.innerHTML = new_relic_number;
                change_number = "13";
            }
 
        }

    }
    
    console.log(gear_number);
    console.log(new_gear_number);


    let new_src = img_src.replace(gear_number,new_gear_number)

    character.querySelector(".gear_img").src = new_src;

    console.log("New Gear: " + change_number)
    console.log("New Relic: " +  relic_level.innerHTML);

    $.ajax({
        url: "includes/alter_character_gear.inc.php",
        method: "POST",   
        data: {id:occurance_id,gear:change_number,relic:relic_level.innerHTML},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

}


function delete_occurance(occurance_id){
    character = document.getElementById(occurance_id);
    character.style.display = "none";
    $.ajax({
        url: "includes/delete_occurance.inc.php",
        method: "POST",   
        data: {id:occurance_id},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}

function toggle_options(occurance_id){
    character = document.getElementById(occurance_id);
    character.classList.toggle("active");
}

function toggle_delete(id){
    stage = document.getElementById(id);
    stage.classList.toggle("delete");
}


function delete_stage(id){
    let stage = document.getElementById(id);
    stage.style.display = "none";
    $.ajax({
        url: "includes/delete_stage.inc.php",
        method: "POST",   
        data: {id:id},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}


function activate_preset(id){
    console.log("activet" + id)
    const req = document.getElementById(id);
    let stage_id = req.parentElement.id;
    console.log(stage_id);
    let pre_name = req.querySelector('.preset_name').innerHTML;
    let required = req.querySelector(".required_units").innerHTML;

    let stage_container = document.getElementById(stage_id);
    stage_container.classList.remove("preset");
    let input = stage_container.querySelector(".stage_title");
    input.value = pre_name;
    change_stage_name(stage_id,"old");

    required_json = JSON.parse(required)
    console.log(required_json)

    let original_characters = stage_container.querySelectorAll(".character");
    original_characters.forEach(character_old =>{
        character_old.style.display = "none";
    })

    let example_character = document.querySelector(".example_character").innerHTML;
    let container = document.getElementById(stage_id).querySelector(".characters_container");

    let modal = document.querySelector(".add_character_modal");
    let characters = modal.querySelectorAll(".character_option")

    $.ajax({
        url: "includes/delete_original_stage_preset.inc.php",
        method: "POST",   
        data: {stage_id:stage_id},
        success: function(data){
            console.log(data);
            const temp_ids = [];
            required_json.forEach(requirement =>{
                let base_id = requirement['baseId'];
                let gear = requirement['gearLevel'];
                let relic = requirement['relicTier'];
                const temp_id = Math.random();
                temp_ids.push(temp_id);
                $.ajax({
                    url: "includes/add_characters_stage_preset.inc.php",
                    method: "POST",   
                    data: {stage_id:stage_id,base_id:base_id,gear:gear,relic:relic,temp_id:temp_id},
                    success: function(data){
                        console.log(data);
                    },
                    error: function(errMsg) {
                        alert(JSON.stringify(errMsg));
                    }
                });
                
            })
        
            let x = 0;
            required_json.forEach(requirement =>{
                let base_id = requirement['baseId'];
                let gear = requirement['gearLevel'];
                let relic = requirement['relicTier'];
                var temp_id_current = temp_ids[x];
                x = x + 1;
                $.ajax({
                    url: "includes/get_occurance_id.inc.php?temp_id=" + temp_id_current + "&base_id=" + base_id,
                    method: "GET",   
                    success: function(data){
                        console.log([data]);
                        if(data != ''){
                            let character = document.getElementById(base_id);
                            let img = character.querySelector(".character_option_img").src;
                            let name = character.querySelector(".character_option_name").innerHTML;
                            let alignment = character.querySelector(".character_option_img").classList[1];
                            let alignment_text = alignment + " Side";
                            gear_text = gear;
                            if (alignment == "Dark"){
                                abrv = "ds";
                            }
                            else {
                                abrv = "ls";
                            }
                            if (gear == 13){
                                gear_text = "g" + "13-" + abrv
                            }
                            
                            new_character = example_character.replaceAll("$img",img);
            
                            if (relic > 0){
                                gear_text = gear_text + "-relic";
                                new_character = new_character.replaceAll("no_show","show_relic");
                            }
            
                            new_character = new_character.replaceAll("$name",name);
                            new_character = new_character.replaceAll("$temp_id",data)
                            new_character = new_character.replaceAll("$alignment",alignment_text);
                            new_character = new_character.replaceAll("g1",gear_text);
                            new_character = new_character.replaceAll(">0<",">" + relic + "<");
            
                            let characters_instage = container.getElementsByClassName("character");
                            if (characters_instage.length == 0){
                                let text = container.innerHTML;
                                text = new_character + text;
                                container.innerHTML = text;
                            }
                            else {
                                let last = characters_instage[characters_instage.length - 1];
                                last.insertAdjacentHTML( 'afterend' , new_character)
                            }
                        }
            
                    },
                    error: function(errMsg) {
                        alert(JSON.stringify(errMsg));
                    }
                });
            
                
            })
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

   

    console.log(stage_id + pre_name + required);
}


function change_colour(id){
    let container = document.getElementById(id);
    let colours = container.querySelector(".colour_options")
    let colour = container.querySelector("#colour");
    colour.classList.add("active");
    colours.classList.add("active");
    const options = colours.querySelectorAll(".colour_option");
    options.forEach(option => {
        option.addEventListener("click", () => {
            console.log(option.style.background);
            var rgb = option.style.background;
            let border_colour = "1px solid " + rgb.replace(")",",0.4)")
            set_colour = rgb.replace(")",",0.06)");
            console.log(border_colour);
            console.log(set_colour);
            container.style.background = set_colour;
            container.style.border = border_colour;
            console.log(container.style.border);
            $.ajax({
                url: "includes/change_stage_colour.inc.php",
                method: "POST",   
                data: {id:id,colour:rgb},
                success: function(data){
                    console.log(data);
                },
                error: function(errMsg) {
                    alert(JSON.stringify(errMsg));
                }
            });
        })
    })
    
    document.addEventListener(
        "click",
        function(event) {
        if (
        event.target != colours &&
        event.target != colour
        ) {
            colour.classList.remove("active");
            colours.classList.remove("active");

        }
        },
        false
    )
}



// function toggle_export_modal(){
//     console.log("export")
//     let container = document.querySelector(".container");
//     let modal = document.querySelector(".modal_export");
//     let cover = container.querySelector(".container_cover");
//     cover.classList.add("active");
//     // container.style = "filter:blur(8px) brightness(40%)";
//     container.style.opacity = "0.3";
//     modal.style.display = "block";
//     console.log(modal.style.display);
//     var options = document.querySelector(".export_options");
//     options.style.display = "";
//     let title = document.querySelector(".export_title");
//     title.style.display = "";
//     var furthers = document.querySelectorAll(".further_info");
//     furthers.forEach(further => {
//         further.classList.remove("active");
//     })
    
// }

function toggle_export_modal(id){
    let container = document.querySelector(".container");
    container.classList.add("hide");
    let link_container = document.querySelector('.link_container');
    link_container.style.display = "";
    let spinner = link_container.querySelector(".spinner_container");
    let link_area = link_container.querySelector(".link_area");
    let text = link_container.querySelector(".load_link_text");
    spinner.style.display = "";
    link_area.classList.remove("active");
    text.style.display = "";
    $.ajax({
        url: "includes/create_share_link.inc.php",
        method: "POST",   
        data: {id:id},
        success: function(data){
            let token = data;
            let link = token;
            document.querySelector(".link_link_text").innerHTML = link;
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
    setTimeout(() => {
        // Generate Link With Ajax
        // Display Link
        // Tick animation with Loader
        spinner.style.display = "none";
        link_area.classList.add("active");
        text.style.display = "none";
    },1000)
}

function close_link(){
    console.log("here");
    let link_container = document.querySelector('.link_container');
    link_container.style.display = "none";
    let container = document.querySelector(".container");
    container.classList.remove("hide");
    let copy = document.querySelector(".copy_link");
    copy.classList.remove("active");
    copy.innerHTML = "<i class='fa-solid fa-copy copy_icon'></i>";
}

function copy_link(){
    let link = document.querySelector(".link_link_text");
    navigator.clipboard.writeText(link.innerHTML);
    let copy = document.querySelector(".copy_link");
    copy.classList.add("active");
    copy.innerHTML = "<i class='fa-solid fa-check copy_icon'></i>";
}

function selectText(containerid) {
    window.getSelection().selectAllChildren(
        document.getElementById(containerid)
    );
}

// function close_modal_export(){
//     let container = document.querySelector(".container");
//     let modal = document.querySelector(".modal_export");
//     container.style.opacity = "";
//     modal.style.display = "";
//     let cover = container.querySelector(".container_cover");
//     cover.classList.remove("active");
// }

// document.addEventListener(
//     "click",
//     function(event) {
//     if (
//     !event.target.closest(".modal_export") &&
//     !event.target.closest(".export")
//     ) {
//         close_modal_export()
//     }
//     },
//     false
// )




// function toggle_export_option(id){
//     let active_option = document.getElementById(id);
//     var options = document.querySelector(".export_options");
//     options.style.display = "none";
//     let title = document.querySelector(".export_title");
//     title.style.display = "none";
//     let further_id = "f_" + id;
//     let further_info = document.getElementById(further_id);
//     further_info.classList.add("active");
//     if (id == "eo_image"){
//         setTimeout(() => {
//             create_image_plan();
//         },100)
//     }
//     else if (id == "eo_link"){
//         create_link_plan();
//     }
//     else if (id == "eo_csv"){
//         create_csv_plan();
//     }
// }

// function colapse_further(){
//     var options = document.querySelector(".export_options");
//     options.style.display = "";
//     let title = document.querySelector(".export_title");
//     title.style.display = "";
//     var furthers = document.querySelectorAll(".further_info");
//     furthers.forEach(further => {
//         further.classList.remove("active");
//     })
// }

// function create_image_plan(){
//     let container = document.getElementById("f_eo_image");
    
//     let loadout = document.querySelector(".container");

//     // Creat New Div With Propper Styling

//     const stages = loadout.querySelectorAll('.stage_container');
    
//     // var image = document.createElement('div');
//     // stages.forEach(stage => {
//     //     stage_div = document.createElement('div');
//     //     const characters = stage.querySelectorAll('.character');
//     //     console.log(characters);
//     //     characters.forEach(character => {
//     //         character_div = document.createElement('div');
//     //         img_src = character.querySelector('.character_img').src;
//     //         character_div_img = document.createElement('img');
//     //         character_div_img.src = img_src;
//     //         character_div.appendChild(character_div_img);
//     //         stage_div.appendChild(character_div);
//     //     })
//     //     image.appendChild(stage_div);
//     // })
    
//     // console.log(image.innerHTML);

//     stages.forEach(stage => {
//         var stage_name = stage.querySelector(".stage_title").value;
//         const stage_description = stage.querySelector(".stage_description");
//         const backgroundColor = stage.style.background.split("(")[1];
//         const parts = backgroundColor.split(",");
//         const hex = "#" + fullColorHex(parts[0],parts[1],parts[2])
       
//         var options = {
//             backgroundColor : hex,
//             width: stage.scrollWidth,
//             height: stage.scrollHeight
//         }
//         html2canvas(stage,options).then(function(canvas) {
//             let downloadLink = document.createElement('a');
//             downloadLink.setAttribute('download', (stage_name + '.png'));
//             let dataURL = canvas.toDataURL('image/png');
//             let url = dataURL.replace(/^data:image\/png/,'data:application/octet-stream');
//             downloadLink.setAttribute('href', url);
//             downloadLink.click();
//             container.querySelector(".spinner_container").style.display = "none";
//         });
//     })
    

    

// }

// var rgbToHex = function (rgb) { 
//     var hex = Number(rgb).toString(16);
//     if (hex.length < 2) {
//             hex = "0" + hex;
//     }
//     return hex;
// };

// var fullColorHex = function(r,g,b) {   
//     var red = rgbToHex(r);
//     var green = rgbToHex(g);
//     var blue = rgbToHex(b);
//     return red+green+blue;
// };


function create_link_plan(){
    // Create Link Instance in database
    // Let user copy Link to clipboard

}

function create_csv_plan(){
    // Create CSV file
    // When done hide loader and download file
}





