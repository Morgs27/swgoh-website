
function delete_animation(id){
    var del_id = "d" + id;
    document.getElementById(del_id).classList.toggle('active');

}

function show_saved(combat,link){
    button = document.querySelector(".create_team")
    setTimeout(() => {
        button.classList.add("active");
        button.innerHTML = "Team Saved Succesfully";
        button.removeAttribute("href");
    }, 100)
    setTimeout(() => {
        button.classList.remove("active");
        button.innerHTML = "Create New Team";
        button.href = "team_builder.php?" + combat + "&" + link;

    }, 2000)
}