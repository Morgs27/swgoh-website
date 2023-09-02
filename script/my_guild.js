const { content } = require("html2canvas/dist/types/css/property-descriptors/content");

function view(loadout_id,event){
    if (event.target.closest(".loadout_options")){
    }
    else {
        window.location.href = "view_loadout.php?btg&i=" + loadout_id;
    }
}

function change_active(id){
    loadout = document.getElementById(id);
    rank = loadout.getAttribute("creator_rank");

    if (loadout.classList.contains("active")){

        // If request is to remove loadout from active

        loadout.classList.remove("active");

        loadouts = document.querySelector(".loadouts_container");
        example = loadouts.querySelector('[creator_rank=' + rank + ']');
        parent = example.parentElement;
        example.parentElement.insertBefore(loadout,example);

        active_container = document.querySelector(".active_loadout_container");
        active_container.innerHTML = "<div class = 'no_text'>No Active Loadout</div>";


        $.ajax({
            url: "includes/change_loadout_active.inc.php",
            method: "POST",   
            data: {id:id,active:'false'},
            success: function(data){
                console.log(data);
            },
            error: function(errMsg) {
                alert(JSON.stringify(errMsg));
            }
        });

        nav_tab = document.querySelector("#navbar_active_loadout");
        nav_tab.parentElement.style.display = "none";

        // Ajax It
    }
    else {

        // If request is to add loadout to active
        
       modal = document.querySelector(".confirmation_modal");
       modal.classList.add('active');
       var content = document.querySelector(".page_content");
       content.classList.add("hide");
       modal.querySelector(".continue").setAttribute("loadout",id);

       document.addEventListener("click", (event) =>{
            if (!event.target.closest(".confirmation_modal") && !event.target.closest(".option")){
                modal.classList.remove('active');
                content.classList.remove("hide");
            }
       })
        
    }
}

function add_loadout_active(id){
    active_container = document.querySelector(".active_loadout_container");
    console.log(active_container.innerHTML);
    if (active_container.querySelectorAll(".loadout").length > 0){
        active_loadout = active_container.querySelector(".loadout");
        remove_active(active_loadout.id);
    }

    active_container.innerHTML = "";
    loadout = document.getElementById(id);
    active_container.appendChild(loadout);
    loadout.classList.add("active");

    $.ajax({
        url: "includes/change_loadout_active.inc.php",
        method: "POST",   
        data: {id:id,active:'True'},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

    nav_tab = document.querySelector("#navbar_active_loadout");
    console.log(nav_tab);
    nav_tab.parentElement.style.display = "";

    modal = document.querySelector(".confirmation_modal");
    modal.classList.remove('active');
    var content = document.querySelector(".page_content");
    content.classList.remove("hide");
}

function remove_active(id){
    loadout = document.getElementById(id);
    rank = loadout.getAttribute("creator_rank");

    loadout.classList.remove("active");

    loadouts = document.querySelector(".loadouts_container");
    example = loadouts.querySelector('[creator_rank=' + rank + ']');
    parent = example.parentElement;
    example.parentElement.insertBefore(loadout,example);

    active_container = document.querySelector(".active_loadout_container");

    $.ajax({
        url: "includes/change_loadout_active.inc.php",
        method: "POST",   
        data: {id:id,active:'false'},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });

    nav_tab = document.querySelector("#navbar_active_loadout");
    nav_tab.parentElement.style.display = "none";
    // Ajax It
}

function change_favorite(id){
    loadout = document.getElementById(id);
    loadout.classList.toggle("favorite");
    if (loadout.classList.contains("favorite")){
        favorite = 'true';
    }
    else {
        favorite = 'false';
    }
    $.ajax({
        url: "includes/change_favorite_loadout.inc.php",
        method: "POST",   
        data: {id:id,favorite:favorite},
        success: function(data){
            console.log(data);
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
    // Ajax It
}

function toggle_settings(){
    const modal = document.querySelector(".settings_modal");
    modal.classList.toggle("show");
    const content = document.querySelector(".page_content");
    content.classList.toggle("hide");
    document.addEventListener("click",(event) =>{
        if (!event.target.closest(".settings") && !event.target.closest(".settings_modal")){
            toggle_settings();
        }
    })
}

function toggle_leave(){
    modal = document.querySelector(".confirmation_modal");
    modal.classList.add('active');
    var content = document.querySelector(".page_content");
    content.classList.add("hide");

    document.addEventListener("click", (event) =>{
        if (!event.target.closest(".confirmation_modal") && !event.target.closest(".settings")){
            modal.classList.remove('active');
            content.classList.remove("hide");
        }
   })
}

function leave_guild(username,id){
    $.ajax({
        url: "includes/leave_guild.inc.php",
        method: "POST",   
        data: {username:username,guild_id:id},
        success: function(data){
            console.log(data);
            window.location.href = "join_guild.php";
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}

function change_rank(direction,username,rank){
    console.log(direction + username + rank);

    if (rank == "officer" && direction == "down"){
        new_rank = "member";
        txt = "Demote";
    }
    else if (rank == "officer" && direction == "up"){
        new_rank = "leader";
        txt = "Promote";
    }
    else if (rank == "member" && direction == "up"){
        new_rank = "officer";
        txt = "Promote";
    }
    else if (rank == "member" && direction == "down"){
        new_rank = "removed";
        txt = "Remove";
    }

    modal = document.querySelector(".rank_modal")
    var content = document.querySelector(".page_content");
    msg = modal.querySelector(".msg");
    button = modal.querySelector(".continue");

    if (new_rank == "removed"){
        msg.innerHTML = "Are you sure you would like to remove " + username + " from the guild?";
        button.innerHTML = txt + '<i class="fa-solid fa-arrow-right"></i>';
        button.onclick = () => {confirm_rank(username,new_rank)}

    }
    else {
        msg.innerHTML = "Are you sure you would like to " + txt + " " + username + " to the rank of " + new_rank + "?";
        button.innerHTML = txt + '<i class="fa-solid fa-arrow-right"></i>';
        button.onclick = () => {confirm_rank(username,new_rank)}
    }

    modal.classList.add("active");
    content.classList.add("hide");

   
    document.addEventListener("click", (event) =>{
        if (!event.target.closest(".rank_modal") && !event.target.closest(".user_promotion_options")){
            modal.classList.remove('active');
            content.classList.remove("hide");
            // this.removeEventListener('click',arguments.callee,false);
        }
   })

    
}

function confirm_rank(username,new_rank){
    $.ajax({
        url: "includes/update_user_rank.inc.php",
        method: "POST",   
        data: {username:username,rank:new_rank},
        success: function(data){
            console.log(data);
            window.location.href = 'myGuild.php';
        },
        error: function(errMsg) {
            alert(JSON.stringify(errMsg));
        }
    });
}

function follow_profile(event,username){
    if (!event.target.closest(".user_promotion_options")){
        window.location.href="profile.php?" + username;
    }
}

