<?php 
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';

session_start();

include_once "header.php";

if (isset($_SESSION['Username'])){
    $username = $_SESSION['Username'];

    $pro = is_pro($username,$conn);
}
else {
    $username = "";
    $pro = "";
}

function url_to_clickable_link($plaintext) {
    return preg_replace(
    '%(https?|ftp)://([-A-Z0-9-./_*?&;=#]+)%i', 
    '<a target="blank" rel="nofollow" href="$0" target="_blank">$0</a>', $plaintext);
}

if (isset($_GET['successful'])){
    ?>
    <script>
        
    </script>
    <?php
}

?>
<head>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1324112804210478"
     crossorigin="anonymous"></script>
</head>


<script>
window.username = '<?php echo $username;?>';
window.pro = '<?php echo $pro;?>';
console.log(window.pro);
</script>
<link rel = 'stylesheet' href = 'styles/recruitment.css'></link>
<script src = 'script/recruitment.js'></script>



<div class="team_builder_bar" style = 'position:relative'>

<img src="images/tw_map_2.jpg" alt="" >
<div class="title">SWGOH Recruitment Hub</div>

<!-- <div onclick = 'open_new_post()' class="tw_settings export" >
    <div>
        <div class = 'export_open_text'>New Post</div> <i style = 'transform:translate(0px);' class="fa-solid fa-plus"></i>
    </div>
</div> -->

</div>

<div class="recruitment_modal">
    <div class="modal_content">
        <div onclick = 'close_new_post()' class="close"></div>
        <div class="modal_title">Create Advert</div>
        <?php
        if (!isset($_SESSION['Username']) || isset($_SESSION['guest'])){
            ?>
            <div style = 'font-family: "Raleway";width: 80%;text-align: center;font-size: 22px;line-height: 1.6;margin: 30px auto;' >You must be logged in to a non-guest account to post your own guild or personal adverts.</div>
            <?php
        }
        else {
        ?>
        <div class="form_container">
            <form action = 'includes/create_post.inc.php' method = 'post' enctype = 'multipart/form-data'>
                <input type = 'hidden' name = 'Username' value = '<?php echo $username;?>'>
                <div class="form_error" id = 'no_type'>* Type not selected</div>
                <div class="form_row">
                    <div id = 'type_options' class="row_content">
                        <div id = 'personal_option' onclick = 'this.toggleAttribute("checked");this.nextElementSibling.removeAttribute("checked");' class="option">Personal<i class="fa-solid fa-user"></i> </div>
                        <div id = 'guild_option' onclick = 'this.toggleAttribute("checked");this.previousElementSibling.removeAttribute("checked");'class="option">Guild<i class="fa-solid fa-users"></i></div>
                        <input type = "hidden" name = "type" value = "none" id = "type_input">
                    </div>
                </div>

                <div class="form_error" id = 'no_title'>* Title is required</div>
                <div class="form_row">
                   <div class="row_content">
                       <input id = 'input_title' maxlength = "200" required name = 'title' class = 'form_input' type = 'text' placeholder = 'Post Title'></input>
                   </div>
                </div>
                

                <div class="form_row">
                   <div class="row_content">
                       <textarea maxlength = "2000" name = 'content' class = 'form_input content' type = 'text' placeholder = 'Post Content'></textarea>
                   </div>
                </div>
                <div class="form_row">
                   <div class="row_content" style = 'flex-direction: column;'>
                       <label class="file_button" for="upload">Choose Image <i class="fa-solid fa-image"></i></label>
                       <input name = 'img' onchange = 'render_image()' accept="image/jpeg, image/png, image/jpg" id = 'upload' class = 'file_upload'  text = 'Upload Image' type = "file"></input>
                       <div id="display-image"></div>
                    </div>
                </div> 
                <div class="form_row">
                   <div class="row_content pro ">
                       <div onclick = 'check_pro()' class="promote_box">Promote <input style = '' id = 'pro_checkbox' name = 'promoted' type = 'checkbox'></input> <label class = 'pro_checkbox' for="pro_checkbox"></label></div>
                       <div class="premium_required">* Pro Version Required!</div>
                       <div class="promote_after_text">Boost your advert to the top!</div>
                    </div>
                </div>
        </div>
        <div class="modal_title bottom"><div type = "submit" onclick = 'post_form()' class="save_button">Post<i class="fa-solid fa-paper-plane"></i></div></div>
        </form>
        <?php
        }
        ?>
    </div>
</div>

<div class="filter_container">
   

    <div id = "discord" onmouseout = 'deactive_filter(this.id)' class="filter_main left">
        <div  onmouseover = 'active_filter(this.parentElement.id)' class="filter_main_button left">
            <i class="fa-brands fa-discord"></i>
            <div class="arrow"><i class="material-icons">keyboard_arrow_down</i></div>
        </div>
        <div class="filter_sub_container">
            <div class="filter_subs" onmouseover = 'active_filter(this.parentElement.parentElement.id)'>
            <div id = 'AhnaldT101' onclick = 'toggle_sub(this.id)' class="filter_sub">
                <img src = 'data:image/jpeg;base64,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'>
                <div class="filter_title">AhnaldT101</div>
            </div>
            <div id = 'SWGOH Events' onclick = 'toggle_sub(this.id)' class="filter_sub">
            <img src = 'https://cdn.discordapp.com/icons/448866299322564659/a_d10cbf0669e17561ec68047b5d8ccf0b.webp?size=240'>
            </div>
            <div id = 'Hot Utils' onclick = 'toggle_sub(this.id)' class="filter_sub">
            <img src = 'https://cdn.discordapp.com/icons/470702742298689544/a_5b9367290b03fff32d2a2fc1ef9eff26.webp?size=240'>
            </div>
            <div id = 'AP Hub' onclick = 'toggle_sub(this.id)' class="filter_sub">
            <img src = 'https://cdn.discordapp.com/icons/709563683277242474/8dec2020ea948053afebd7c6a47419da.webp?size=240'>
            </div>
            <div id = 'The Gambit' onclick = 'toggle_sub(this.id)' class="filter_sub">
            <img src = 'https://cdn.discordapp.com/icons/340565575707262997/1cf2f0e96b95ec7787957f16b86624c0.webp?size=240'>
            </div>
            </div>
        </div>
    </div>

    <div id = "filters" onmouseout = 'deactive_filter(this.id)' class="filter_main right filters">
        <div  onmouseover = 'active_filter(this.parentElement.id)' class="filter_main_button">
            <i class="fa-solid fa-filter"></i>
            <div class="arrow"><i class="material-icons">keyboard_arrow_down</i></div>
        </div>
        <div class="filter_sub_container">
            <div class="filter_subs" onmouseover = 'active_filter(this.parentElement.parentElement.id)'>
            <div id = 'tm' onclick = 'toggle_sub(this.id)' class="filter_sub">
                <img src = 'images/favicon.ico'>
            </div>
            <div id = 'guild' onclick = 'toggle_groups(this.id)' class="filter_sub">
                <img src = 'images/people.png'>
            </div>
            <div id = 'personal' onclick = 'toggle_groups(this.id)' class="filter_sub">
                <img src = 'images/profile.png' style = 'width: 90%;height: 90%;left: 5%;'>
            </div>
            
            </div>
        </div>
    </div>
    
    <div id = "reddit" onmouseout = 'deactive_filter(this.id)' class="filter_main right">
        <div  onmouseover = 'active_filter(this.parentElement.id)' class="filter_main_button">
            <i class="fa-brands fa-reddit-alien"></i>
            <div class="arrow"><i class="material-icons">keyboard_arrow_down</i></div>
        </div>
        <div class="filter_sub_container">
            <div class="filter_subs" onmouseover = 'active_filter(this.parentElement.parentElement.id)'>
            <div id = 'SWGOHRecruiting' onclick = 'toggle_sub(this.id)' class="filter_sub">
                <img src = 'images/recruiting.png'>
            </div>
            <div id = 'swgoh_guilds' onclick = 'toggle_sub(this.id)' class="filter_sub">
                <img src = 'images/recruit_guilds.png'>
            </div>
            </div>
        </div>
    </div>

<!-- <div class="create_guild_advert">
    Create </br> Advert <i class="fa-solid fa-plus"></i>
</div> -->

</div>


<div class="recruitment_content" onscroll = "check_render()">
    <?php
        $posts = array();
        $pormoted_posts = array();

        $sql = "SELECT * FROM reddit_posts";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $type = "Reddit";
            $subreddit = $data['subreddit'];
            $title = $data['title'];
            $text = $data['text'];
            $link = $data['link'];
            $author = $data['author'];
            $created = $data['created'];
            $img_url = $data['img_url'];
            $link_url = $data['link_url'];

            $post = array("Type" => $type,"Created" => $created,"Title" => $title, "Subreddit" => $subreddit, 
            "Text" => $text, "Link" => $link, "Author" => $author, "Img" => $img_url, "Link_url" => $link_url);
            
            array_push($posts,$post);
        }

        $sql = "SELECT * FROM discord_posts";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $type = "Discord";
            $server = $data['server'];
            $content = $data['content'];
            $gg_link = $data['gg_link'];
            $author = $data['author'];
            $created = $data['timestamp'];
            $img_url = $data['img_url'];
            $group = $data['type'];
            $post = array("Type" => $type,"Created" => $created,"Content" => $content, "Server" => $server, 
            "gg_link" => $gg_link, "Author" => $author, "Img" => $img_url, "Group" => $group);
            
            array_push($posts,$post);
        }

        $sql = "SELECT * FROM tm_posts WHERE promoted != 'on'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $type = "TM";
            $title = $data['title'];
            $text = $data['content'];
            $author = $data['author'];
            $created = $data['created'];
            $img_url = $data['image'];
            $group = $data['post_type'];
            $post = array("Type" => $type,"Created" => $created,"Title" => $title, 
            "Text" => $text, "Author" => $author, "Img" => $img_url, "Group" => $group);
            
            array_push($posts,$post);
        }

        $sql = "SELECT * FROM tm_posts WHERE promoted = 'on'";
        $result = $conn->query($sql);
        while($data = $result->fetch_assoc()){
            $type = "TM";
            $title = $data['title'];
            $text = $data['content'];
            $author = $data['author'];
            $created = $data['created'];
            $img_url = $data['image'];
            $group = $data['post_type'];
            $post = array("Type" => $type,"Created" => $created,"Title" => $title, 
            "Text" => $text, "Author" => $author, "Img" => $img_url, "Group" => $group);
            
            array_push($pormoted_posts,$post);
        }

        usort($posts,function($first,$second){
            return $first['Created'] < $second['Created'] ;
        });

        usort($pormoted_posts,function($first,$second){
            return $first['Created'] < $second['Created'] ;
        });

        // print_r($posts);

        $authors = array();

        $x = 0;
        foreach ($pormoted_posts as $post){
            $display_date = get_display_date($post['Created']);
            $post['Text'] = url_to_clickable_link($post['Text']);

                ?>
                <div id = '<?php echo $x;?>' class="post not_shown tm <?php echo $post['Group'];?>">
                    <div class="post_banner tm promoted"></div>
                    <div class="post_top">
                       
                        <div class="server_icon">
                            <img src="images/favicon.png" style = 'filter:invert(100%)'alt="">
                        </div>
                        <a class="server_name">SWGOH Team Manager <div class="promoted_text">Promoted</div></a>
                        
                    </div>
                    <div class = 'post_title'><?php echo str_replace("#z#","'",$post['Title']);?></div>
                    <?php
                    if ($post['Text'] !== ""){
                        ?>
                        <pre class="post_content discord"><?php echo str_replace("#z#","'",$post['Text']);?></pre>
                        <?php
                    }
                    ?>
                    <?php
                    if ($post['Img'] !== Null){
                        $img = $post['Img'];
                        echo "<img class = 'post_img' loading = 'lazy' src = '$img'>";
                    }
                    ?>
                    <div class="post_footer">
                        Posted By: <?php echo $post['Author'];?> </br>  <?php echo $display_date;?>
                    </div>
                </div>
                <?php
            array_push($authors,$post['Author']);
            $x += 1;
        }
        foreach ($posts as $post){
            if ((array_search($post['Author'],$authors)) == false && (array_search($post['Author'],$authors) !== 0)){
            if ($post['Type'] == "Reddit"){
                if ($post['Link_url'] == 'None'){
                    $display_date = get_display_date($post['Created']);
                    if ($post['Subreddit'] == 'SWGOHRecruiting'){
                        $server_img = "images/recruiting.png";
        
                    }
                    else {
                        $server_img = "images/recruit_guilds.png";
                    }
                ?>
                    <div id = '<?php echo $x;?>' class="post not_shown <?php echo $post['Subreddit'];?> guild">
                        <div class="post_banner"></div>
                        <div class="post_top">
                            <a target="_blank" href = '<?php echo $post['Link'];?>' class="post_link">
                            <i class="fa-solid fa-eye"></i>
                            </a>
                            <div class="server_icon">
                                <img src="<?php echo $server_img;?>" alt="">
                            </div>
                            <a target="_blank" href = 'https://reddit.com/r/<?php echo $post['Subreddit'];?>' class="server_name">r/<?php echo $post['Subreddit'];?></a>
                            
                        </div>
                        <div class = 'post_title'><?php echo str_replace("#z#","'",$post['Title']);?></div>
                        <div class="post_content"><?php echo htmlspecialchars_decode(str_replace("#z#","'",$post['Text']));?></div>
                        <?php
                        if ($post['Img'] !== "None"){
                            $img = $post['Img'];
                            echo "<img class = 'post_img' loading = 'lazy' src = '$img'>";
                        }
                        ?>
                        <div class="post_footer">
                            Posted By: <?php echo $post['Author'];?> </br>  <?php echo $display_date;?>
                        </div>
                    </div>

                <?php
                array_push($authors,$post['Author']);
                }
            }
            else if ($post['Type'] == "Discord"){
                $display_date = get_display_date($post['Created']);

                

                $post['Content'] = url_to_clickable_link($post['Content']);
                if ($post['Server'] == 'SWGOH Events'){
                    $server_img = "https://cdn.discordapp.com/icons/448866299322564659/a_d10cbf0669e17561ec68047b5d8ccf0b.webp?size=240";
                    $server_link = "https://discord.gg/swgoh";
                }
                else if ($post['Server'] == 'AhnaldT101'){
                    $server_img = "data:image/jpeg;base64,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";
                    $server_link = "https://discord.com/invite/77BDNsz";
                }
                else if ($post['Server'] == 'Hot Utils'){
                    $server_img = "https://cdn.discordapp.com/icons/470702742298689544/a_5b9367290b03fff32d2a2fc1ef9eff26.webp?size=240";
                    $server_link = "https://discord.com/invite/xxjyyZme";
                }
                else if ($post['Server'] == 'AP Hub'){
                    $server_img = "https://cdn.discordapp.com/icons/709563683277242474/8dec2020ea948053afebd7c6a47419da.webp?size=240";
                    $server_link = "https://discord.com/invite/xTFsx862Dw";
                }
                else if ($post['Server'] == 'The Gambit'){
                    $server_img = "https://cdn.discordapp.com/icons/340565575707262997/1cf2f0e96b95ec7787957f16b86624c0.webp?size=240";
                    $server_link = "";
                }
                else {
                    $server_img = "";
                }
                ?>
                <div id = '<?php echo $x;?>' class="post not_shown <?php echo $post['Server'];?> <?php echo $post['Group'];?>">
                    <div class="post_banner discord"></div>
                    <div class="post_top">
                       
                        <div class="server_icon">
                            <img src="<?php echo $server_img;?>" alt="">
                        </div>
                        <a target="_blank" href = '<?php echo $server_link;?>' class="server_name"><?php echo $post['Server'];?></a>
                        
                    </div>
                    <pre class="post_content discord"><?php echo str_replace("#z#","'",$post['Content']);?></pre>
                    <?php
                    if ($post['Img'] !== "None"){
                        $img = $post['Img'];
                        echo "<img class = 'post_img' loading = 'lazy' src = '$img'>";
                    }
                    ?>
                    <div class="post_footer">
                        Posted By: <?php echo $post['Author'];?> </br>  <?php echo $display_date;?>
                    </div>
                </div>

                <?php
                array_push($authors,$post['Author']);
                
            }
            else if ($post['Type'] == "TM"){
                $post['Text'] = url_to_clickable_link($post['Text']);
                $display_date = get_display_date($post['Created']);
                ?>
                <div id = '<?php echo $x;?>' class="post not_shown tm <?php echo $post['Group'];?>">
                    <div class="post_banner tm"></div>
                    <div class="post_top">
                       
                        <div class="server_icon">
                            <img src="images/favicon.png" style = 'filter:invert(100%)'alt="">
                        </div>
                        <a class="server_name">SWGOH Team Manager</a>
                        
                    </div>
                    <div class = 'post_title'><?php echo str_replace("#z#","'",$post['Title']);?></div>
                    <?php
                    if ($post['Text'] !== ""){
                        ?>
                        <pre class="post_content discord"><?php echo str_replace("#z#","'",$post['Text']);?></pre>
                        <?php
                    }
                    ?>
                    <?php
                    if ($post['Img'] !== Null){
                        $img = $post['Img'];
                        echo "<img class = 'post_img' loading = 'lazy' src = '$img'>";
                    }
                    ?>
                    <div class="post_footer">
                        Posted By: <?php echo $post['Author'];?> </br>  <?php echo $display_date;?>
                    </div>
                </div>
                <?php
                array_push($authors,$post['Author']);
            }
            $x += 1;
        }
        }
        
    ?>
    <script>render_next()</script>
</div>


