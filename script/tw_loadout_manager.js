function toggleLoadouts(id){

    let container = document.getElementById(id);
    let loadouts = container.querySelector(".loadouts")
    let arrow = container.querySelector(".toggle_arrow");

    loadouts.classList.toggle("active");
    arrow.classList.toggle("active");

    
}

let width = screen.width;
if (width < 550){
    let navigations = document.getElementsByClassName("loadout_navigation");

    for (let i = 1; i < navigations.length; i++){
        navigations[i].onclick = function(){
            navigations[i].parentElement.classList.toggle("active");
            navigations[i].classList.toggle("active");
            
        }
    }

}


document.addEventListener( "click",
    function(event) {
    if (!event.target.closest(".loadout_navigation.active")) {
        let loadouts = document.getElementsByClassName("loadout_navigation");

        for (let i = 1; i < loadouts.length; i++){
            if (width < 550){
                loadouts[i].parentElement.classList.remove("active");
            }
            loadouts[i].classList.remove("active");
 
        }
    }
    if (!event.target.closest(".create_loadout")) {
        let create_loadout = document.querySelector(".create_loadout");
        create_loadout.classList.remove("active");
    }
    },
    false
)


  
// function check(el) {
//     var curOverf = el.style.overflow;
      
//     var isOverflowing = el.clientWidth < el.scrollWidth
//         || el.clientHeight < el.scrollHeight;
      
//     el.style.overflow = curOverf;
      
//     return isOverflowing;
// }

// let element = document.querySelector('.loadouts');
// window.alert(check(element));


function create_loadout(){
    let create_loadout = document.querySelector(".create_loadout");
    let input = create_loadout.querySelector(".create_input");

    if (create_loadout.classList.contains("active")){

    }
    else {
        input.value = "";

    }

    create_loadout.classList.add("active");
    input.focus();
    
}

function delete_loadout(id){

    let span = document.getElementById(id);
    let navigation = span.parentElement;
    let loadout = span.parentElement.parentElement;

    if (navigation.classList.contains("active")){
        $.ajax({
            url: "includes/delete_loadout.inc.php",
            method: "POST",   
            data: {id: id},
            success: function(data){
                console.log(data);
                loadout.style.display = "none";
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
}
function delete_loadout_guild(id){
    console.log(id);
    let actuall_id = id.slice(2);
    let guild_span = document.getElementById(id);
    let navigation = guild_span.parentElement;
    let guild_loadout = guild_span.parentElement.parentElement;

    let span = document.getElementById(actuall_id);
    let loadout = span.parentElement.parentElement;


    if (navigation.classList.contains("active")){
        $.ajax({
            url: "includes/delete_loadout.inc.php",
            method: "POST",   
            data: {id: actuall_id},
            success: function(data){
                console.log(data);
                loadout.style.display = "none";
                guild_loadout.style.display = "none";
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }

}

function make_visible(id){
    let span = document.getElementById(id);
    let navigation = span.parentElement;
    let loadout = navigation.parentElement;

    let guild_id = "r_" + id;
    let guild_span = document.getElementById(guild_id);
    let guild_loadout = guild_span.parentElement.parentElement;
    if (navigation.classList.contains("active")){
        $.ajax({
            url: "includes/make_visible.inc.php",
            method: "POST",   
            data: {id,id},
            success: function(data){
                console.log(data);
                loadout.classList.add("visible");
                guild_loadout.classList.remove("hide");
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
}

function remove_visible(id){
    let actuall_id = id.slice(2);
    let original = document.getElementById(actuall_id);

    let original_loadout = original.parentElement.parentElement;

    let span = document.getElementById(id);
    let navigation = span.parentElement;
    let loadout = navigation.parentElement;
    if (navigation.classList.contains("active")){
        console.log(actuall_id);
        $.ajax({
            url: "includes/remove_visible.inc.php",
            method: "POST",   
            data: {id:actuall_id},
            success: function(data){
                console.log(data);
                loadout.classList.add("hide");
  
                original_loadout.classList.remove("visible");

            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
}

function remove_visible_officer(id){
    let actuall_id = id.slice(2);
    
    let span = document.getElementById(id);
    let navigation = span.parentElement;
    let loadout = navigation.parentElement;
    if (navigation.classList.contains("active")){
        console.log(actuall_id);
        $.ajax({
            url: "includes/remove_visible.inc.php",
            method: "POST",   
            data: {id:actuall_id},
            success: function(data){
                console.log(data);
                loadout.classList.add("hide");

            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });
    }
}

function favorite_loadout(id){
    let actuall_id = id.slice(2);
    
    let span = document.getElementById(id);
    let navigation = span.parentElement;
    let loadout = navigation.parentElement;
    if (navigation.classList.contains("active")){
       $.ajax({
        url: "includes/favorite_loadout.inc.php",
        method: "POST",   
        data: {id:actuall_id},
        success: function(data){
            console.log(data);
            loadout.classList.toggle("favorite");
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
    }
}

function edit_loadout(id){
    let actuall_id = id.slice(2);
    let span = document.getElementById(id);
    let navigation = span.parentElement;
    let loadout = navigation.parentElement;
    if (navigation.classList.contains("active")){
        window.location.href = "edit_loadout_new.php?i=" + actuall_id;
    }
}


function view_loadout(id){
    let actuall_id = id.slice(2);

    let span = document.getElementById(id);
    let navigation = span.parentElement;
    let loadout = navigation.parentElement;
    if (navigation.classList.contains("active") || span.classList.contains("justeye")){
        window.location.href = "view_loadout.php?i=" + actuall_id;
    }
}


// Farmin Plan


function delete_plan(id){
    e_id = "e_" + id;
    let loadout = document.getElementById(e_id);
    loadout.style.display = "none";
    $.ajax({
        url: "includes/delete_plan.inc.php",
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

function edit_plan(id,target){
    if (!target.closest(".fa-xmark")&& !target.closest(".delete_plan")){
        let actuall_id = id.slice(2);
        let loadout = document.getElementById(id);
        window.location.href = "edit_farming_plan.php?i=" + actuall_id;
    }
    
}

function open_import(){
    plans = document.querySelector(".plans_container");
    plans.style.display = "none";

    create = document.querySelector(".create_loadout");
    seperator = document.querySelector(".seperator");

    create.style.display ="none";
    seperator.style.display = "none";

    let link_container = document.querySelector('.link_container');
    link_container.style.display = "";

    let spinner = link_container.querySelector(".spinner_container");
    let link_area = link_container.querySelector(".link_area");
    let text = link_container.querySelector(".load_link_text");
    spinner.style.display = "none";
    link_area.classList.add("active");
    text.style.display = "none";

    input = link_area.getElementsByTagName('input')[0];
    input.value = "";
    input.focus();

    document.addEventListener(
        "click",
        function(event) {
        if (
        !event.target.closest(".export") && !event.target.closest(".link_area")
        ) {
            close_import();

        }
        },
        false
    )

}

function close_import(){
    plans = document.querySelector(".plans_container");
    plans.style.display = "";

    let link_container = document.querySelector('.link_container');
    link_container.style.display = "none";

    create = document.querySelector(".create_loadout");
    seperator = document.querySelector(".seperator");

    create.style.display ="";
    seperator.style.display = "";
}

function import_plan(){

    let link_container = document.querySelector('.link_container');

    let spinner = link_container.querySelector(".spinner_container");
    let link_area = link_container.querySelector(".link_area");
    let text = link_container.querySelector(".load_link_text");

    spinner.style.display = "";
    text.style.display = "";
    link_area.style.transition = "0s";
    link_area.classList.remove("active");
    setTimeout(() => {
        link_area.style.transition = "0.5s";
    },100)

    document.removeEventListener("click",
    function(event) {
    if (
    !event.target.closest(".export") && !event.target.closest(".link_area")
    ) {
        close_import();

    }
    },
    false)

    input = link_area.getElementsByTagName('input')[0];
    code = input.value;

    icon = link_area.querySelector(".fa-link");
    input_area = link_area.querySelector(".link_link");
    text_stuff = link_area.querySelector(".link_title_bit");

    $.ajax({
        url: "includes/import_plan.inc.php",
        method: "POST",   
        data: {username:window.username,code:code},
        success: function(data){
            
            console.log(data);
            data = JSON.parse(data)
            console.log(data[0])
            if (data['result'] == "sucess"){
                console.log("sucess");

                new_loadout_id = data['id'];
                console.log(new_loadout_id);

                example_plan = document.querySelector('.example_plan');
                new_plan = example_plan.innerHTML.replaceAll("$id_here",new_loadout_id);
                new_plan = new_plan.replaceAll("$name_here",data['name']);
                new_plan = new_plan.replaceAll("$created_here",data['created']);

                farming_plans = document.querySelector(".plans_container");
                farming_plans.innerHTML = farming_plans.innerHTML + new_plan;

                setTimeout(() => {
                    spinner.style.display = "none";
                    text.style.display = "none";
                    link_area.classList.add("active");

                    icon.classList.remove("fa-link");
                    icon.classList.add("fa-check");
                    icon.style.background = "rgba(0,255,0,0.3)";

                   
                    input_area.style.display = "none";
                    
                    text_stuff.innerHTML = "Farming plan inported successfully!";

                    

                    setTimeout(() => {
                        close_import();
                        input_area.style.display = "";
                        icon.classList.remove("fa-check");
                        icon.classList.add("fa-link");
                        icon.style.background = "";
                        text_stuff.innerHTML = "Enter a code to import a plan."
                    },1500)
                },500)
            }
            else {
                console.log("fail");
                setTimeout(() => {
                    spinner.style.display = "none";
                    text.style.display = "none";
                    link_area.classList.add("active");

                    icon.classList.remove("fa-link");
                    icon.classList.add("fa-xmark");
                    icon.style.background = "rgba(255,0,0,0.3)";

                   
                    input_area.style.display = "none";
                    
                    text_stuff.innerHTML = "Failed to import farming plan! </br></br> Code not Valid!";

                    // add loadout
                    
                    setTimeout(() => {
                        close_import();
                        input_area.style.display = "";
                        icon.classList.remove("fa-xmark");
                        icon.classList.add("fa-link");
                        icon.style.background = "";
                        text_stuff.innerHTML = "Enter a code to import a plan."
                    },4000)
                },500)
            }
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

}

