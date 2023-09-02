const text = require("body-parser/lib/types/text");

function active_filter(id){
    const  filter = document.getElementById(id);
    filter.classList.add("show");
    filter.style.zIndex = "10";
    clearTimeout(timeout);
}


function deactive_filter(id){
    const  filter = document.getElementById(id);
    filter.classList.remove("show");
    const timeout = setTimeout(() => {
        filter.style.zIndex = "";
    }, 1000)

    filter.addEventListener("mouseover",() => {
        clearTimeout(timeout);
    });
    
}


function toggle_sub(id){
    console.log(id);
    array = id.split(" ");
    if (array.length > 1){
        str = "." + array[0];
    }
    else{
        str = "." + id;
    }
    console.log(array);
    sub = document.getElementById(id);
    
    const posts = document.querySelectorAll(str);

    if (sub.classList.contains("active")){
        // Display Posts
        posts.forEach(post => {
           post.classList.remove("hidden")
        })
    }
    else {
        // Hide Posts
        posts.forEach(post => {
            post.classList.add("hidden");
        })
    }
    sub.classList.toggle("active");
}


function toggle_groups(id){
    console.log(id);
    array = id.split(" ");
    if (array.length > 1){
        str = "." + array[0];
    }
    else{
        str = "." + id;
    }
    console.log(array);
    sub = document.getElementById(id);
    
    const posts = document.querySelectorAll(str);

    if (sub.classList.contains("active")){
        // Display Posts
        posts.forEach(post => {
           post.classList.remove("hide")
        })
    }
    else {
        // Hide Posts
        posts.forEach(post => {
            post.classList.add("hide");
        })
    }
    sub.classList.toggle("active");
    console.log(sub.classList);
}

function open_new_post(){
    console.log("open");
    
    var content = document.querySelector('.recruitment_content');
    var modal = document.querySelector('.recruitment_modal');
    var filters = document.querySelector('.filter_container');
    filters.classList.add('hidden');

    content.classList.add("hidden");
    modal.classList.add("active");

    document.addEventListener("click", (event) => {
        if (!event.target.closest(".export") && !event.target.closest(".modal_content")){
           close_new_post();
            
        }
    })
    filters.getElementsByTagName
}

function close_new_post(){
    var content = document.querySelector('.recruitment_content');
    var modal = document.querySelector('.recruitment_modal');
    var filters = document.querySelector('.filter_container');
    filters.classList.remove('hidden');

    content.classList.remove("hidden");
    modal.classList.remove("active");
}

function render_image(){
    var file = document.getElementById('upload').files[0];
    var reader  = new FileReader();
    reader.onload = function(e)  {
        var image = document.createElement("img");
        image.src = e.target.result;

        display_image = document.getElementById("display-image");
        display_image.innerHTML = "";
        display_image.appendChild(image);
        display_image.classList.add("active");
     }
     reader.readAsDataURL(file);
     console.log(document.getElementById('upload').files)
}

function post_form(){
    options = document.querySelectorAll(".option");
    input = document.querySelector("#type_input");
    options.forEach(option => {
        if (option.hasAttribute('checked')){
            input.value = option.id;
        }
    })
    post = true;
    if (input.value == "none"){
        document.getElementById("no_type").classList.add("active");
        post = false;
    }
    title = document.getElementById("input_title");
    if (title.value == ""){
        document.getElementById("no_title").classList.add("active");
        post = false;
    }
    if (post == true){
    document.getElementsByTagName("form")[0].submit()
    }

}

function check_pro(){
    if (window.pro == "true"){
    }
    else {
        box = document.querySelector("#pro_checkbox");
        box.checked = false;
        var text = document.querySelector(".premium_required");
        text.classList.add("active");
    }
}

function render_next(){
    posts = document.querySelectorAll(".post");
    breakpoint = false;
    posts.forEach(post =>{
        if (!post.classList.contains("not_shown") && post.nextElementSibling.classList.contains("not_shown")){
            breakpoint = true;
            start = post.nextElementSibling.id;
        }
    })
    if (breakpoint == false){
        start = 0;
    }
    end = parseInt(start) + 20; 
    console.log(end);
    for (let i = start; i < end; i++){
        posts[i].classList.remove("not_shown");
    }
}

function check_render(){
    var container = document.querySelector(".recruitment_content");
    var height = container.scrollHeight - 1000;

    if (container.scrollTop > height){
        render_next();
    }
}