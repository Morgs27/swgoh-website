<?php 
include 'functions/class_init.php';
include 'classes/character_class.php';
include 'classes/user_new.php';
session_start();
ob_start();
include_once "header.php";

check_logged_in();
check_get_variable("i");

$plan_id = $_GET['i'];

if (filter_var($plan_id, FILTER_VALIDATE_INT) === false){
	?>
	<script>
		window.location.href = "farming_manager.php";
	</script>
	<?php
}

$username = $_SESSION['Username'];

$sql = "SELECT * FROM farming_plan WHERE plan_id = '$plan_id'";
$result = $conn->query($sql);
check_rows($result);
while( $data = $result->fetch_assoc()){
    $id = $data['plan_id'];
    $name = $data['plan_name'];
    $created = $data['created'];
}


?>

<script src = "script/farming_plan.js"></script>
<link rel="stylesheet" type="text/css" href="styles/farming_manager.css"/>

<div class="example_character">
    <div class = 'character' id = '$temp_id' onmouseover = 'toggle_options(this.id)' onmouseout = 'toggle_options(this.id)'>
        <div class = 'rating' >
        <i onclick = 'delete_occurance($temp_id)' class='fa-solid fa-circle-xmark'></i>
        </div>
        <div class = 'row'>
            <div class = 'sidebar'>
                <div class = 'arrow up' onclick = 'gear_character_double_up($temp_id)'><i class='fa-solid fa-circle-up'></i></div>
                <div class = 'arrow down' onclick = 'gear_character_double_down($temp_id)'><i class='fa-solid fa-circle-down'></i></div>

            </div>
            <div class = 'middle' id = '$alignment'>
                <img class = 'character_img' src = '$img'>
                <img class = 'gear_img' alignment = '$alignment' src = 'images/public/gear-icon-g1.png'>
                <div class = 'relic_number' id = 'no_show'>0</div>
            </div>
            <div class = 'sidebar'>
                    <div class = 'arrow up' onclick = 'gear_character_up($temp_id)'><i class='fa-solid fa-circle-chevron-up'></i></div>
                    <div class = 'arrow down' onclick = 'gear_character_down($temp_id)'><i class='fa-solid fa-circle-chevron-down'></i></div>
            </div>
        </div>
        <div class = 'name'>$name</div>
    </div>
</div>

<div class="example_stage" style = "display:none" >
    
        <div class='stage_container' id = 'example_id' onmouseover = 'toggle_delete(this.id)' onmouseout = 'toggle_delete(this.id)'>
         <div class="stage_delete" onclick = 'delete_stage(this.parentElement.id)'><i class="fa-solid fa-circle-xmark"></i></div>

            <input type = "text" onkeyup = "change_stage_name(this.parentElement.id,'old')" class="stage_title" placeholder = "New Stage"></input>
            <div class="stage_description">

                <div id = "gear" class="option" onclick = "change_gear(this.parentElement.parentElement.id)" ><i class="fa-solid fa-gear"></i> Needed</div>

                <div id = "colour" class="option" onclick = "change_colour(this.parentElement.parentElement.id)"><i class="fa-solid fa-palette"></i>Colour
                <div class = "colour_options">
                    <div class="colour_option" style = 'background: rgba(255,0,0)'></div>
                    <div class="colour_option" style = 'background: rgba(0,255,0)'></div>
                    <div class="colour_option" style = 'background: rgba(0,0,255)'></div>
                    <div class="colour_option" style = 'background: rgb(119, 46, 179)'></div>
                    <div class="colour_option" style = 'background: rgba(0,0,0)'></div>
                </div>
                </div>

                <div id = "preset" class="option" onclick = "change_preset(this.parentElement.parentElement.id)"><i class="fa-solid fa-file-import"></i></i>Use Preset</div>

            </div>
            <div class="stage_characters">
            <div class="gear_needed gear">
                    <div class="back_arrow" onclick = "this.parentElement.parentElement.parentElement.classList.toggle('gear')"><i class="fa-solid fa-xmark"></i></div>
                    <div class="gear_title">
                        <div onclick = "this.parentElement.parentElement.classList.toggle('relic')" class="gear_option gear"><i class="fa-solid fa-gear"></i></div>
                        <div onclick = "this.parentElement.parentElement.classList.toggle('relic')" class="gear_option relic"><img src = 'images/jawa.png'></div>
                    </div>
                    <div class = 'gear_container'>
                    <div class="fp_spinner_container" >
                        <div class="spinner_container">
                            <div class="spinner_circle"></div>
                            <div class="spinner_circle outer_1"></div>
                            <div class="spinner_circle outer_2"></div>
                            <div class="spinner_circle plannet_1"></div>
                            <div class="spinner_circle plannet_2"></div>
                        </div>
                        Loading Required Gear...
                    </div>
                    <?php
                        $sql_ge = "SELECT * FROM gear;";
                        $result_ge = $conn->query($sql_ge);
                        echo "<div class = 'gear_items'>";
                        while ($gear = $result_ge->fetch_assoc()){
                            $gear_name = $gear['name'];
                            $gear_img = $gear['img'];
                            $gear_base_id = $gear['base_id'];
                            $tier = $gear['tier'];
                            if ($tier == 12){
                                $outline = "gold";
                            }
                            else if ($tier == 1 ){
                                $outline = "grey";
                            }
                            else if ($tier == 2 ){
                                $outline = "green";
                            }
                            else if ($tier == 7 || $tier == 11){
                                $outline = "purple";
                            }
                            else if ($tier == 4 ){
                                $outline = "blue";
                            }

                            echo "
                            <div id = 'g_$gear_base_id' class = 'gear_item gear_item_gear' style = 'display: none'>
                                <div class='gear_stat'>
                                        <div class = 'gear_img_container'>
                                        <img style = 'border: 1px solid $outline' src = '$gear_img'>
                                        </div>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'gear_name_container'>
                                    $gear_name
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    
                                    <div class = 'gear_progress_bar'>
                                        <div class = 'gear_progress' style = 'width:30%' >
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'remaining'>1000</div>
                                </div>
                            </div>
                            ";
                        }
                        echo "</div>";
                    ?>
                    </div>
                    <div class = 'relic_container'>
                    <div class="fp_spinner_container_relic" >
                        <div class="spinner_container">
                            <div class="spinner_circle"></div>
                            <div class="spinner_circle outer_1"></div>
                            <div class="spinner_circle outer_2"></div>
                            <div class="spinner_circle plannet_1"></div>
                            <div class="spinner_circle plannet_2"></div>
                        </div>
                        Loading Required Gear...
                    </div>
                    <?php
                        $sql_r = "SELECT * FROM relic;";
                        $result_r = $conn->query($sql_r);
                        echo "<div class = 'gear_items'>";
                        ?>
                            <div id = "no_relics" style = 'display: none' class = 'no_results_msg'>No Relics Required</div>
                        <?php
                        while ($relic = $result_r->fetch_assoc()){
                            $relic_name = $relic['name'];
                            $relic_img = $relic['img'];
                            $relic_id = $relic['relic_id'];
                            echo "
                            <div id = 'r_$relic_id' class = 'gear_item gear_item_relic' style = 'display: none'>
                                <div class='gear_stat'>
                                        <img src = '$relic_img'>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'gear_name_container'>
                                    $relic_name
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    
                                    <div class = 'gear_progress_bar'>
                                        <div class = 'gear_progress' style = 'width:30%' >
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'remaining'>1000</div>
                                </div>
                            </div>
                            ";
                        }
                        echo "</div>";
                    ?>
                    </div>
                </div>
                <div class="characters_container">
                    <div class="new_character_container">
                        <div id = "example_id" class="new_character" onclick = "add_character(this.id)">
                            <div style = 'position:absolute;transform:translateY(-20px)' class="new_text">Add</br>Character</div>
                            <div  style = 'transform:translateY(30px)' class="new_add"><span></span><span></span></div>
                        </div>
                    </div>
                </div>
                <div class="preset_container">
                    <div class="back_arrow left" onclick = "this.parentElement.parentElement.parentElement.classList.toggle('preset')"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="presets_title">Select a Preset...</div>
                    <div class="presets" id = "example_id">
                            <?php

                                $sql = "SELECT * FROM presets";
                                $result = $conn->query($sql);
                                while($data_p = $result->fetch_assoc()){
                                    
                                    $preset_img = $data_p['preset_img'];
                                    $preset_name = $data_p['preset_name'];
                                    $preset_characters = $data_p['characters'];
                                    echo "<div id = 'req_id' class = 'preset_item' onclick = 'activate_preset(this.id)'>";
                                    echo "<div class = 'preset_img_container'><img src = '$preset_img' class = 'preset_img'></div>";
                                    echo "<div class = 'preset_name'>$preset_name</div>";
                                    echo "<div class = 'required_units' style = 'display: none;'>$preset_characters</div>";
                                    echo "</div>";
                                }
                                
                            ?>
                    </div>
                </div>
            </div>
            
        </div>
</div>
  
<div class="team_builder_bar" style = 'position: relative'>
<img src="images/farm.png" alt="" >

<input id = "<?php echo $id;?>" onkeyup = "edit_title(this.id)" class="edit_name_input title" value = "<?php echo $name;?>"></input>


<div class="tw_settings" style = 'position:absolute;left: 5px;' >
    <a href='farming_manager.php'>
        <i class="material-icons">arrow_back</i>
    </a>
</div>

<div id = '<?php echo $plan_id;?>' onclick = 'toggle_export_modal(this.id)' class="tw_settings export" style = 'position:absolute;right: 5px;display: flex;justify-content: center;align-items:center' >
    <div>
        <div class = 'export_open_text'>Export</div> <i style = 'transform: translate(0px)'class="fa-solid fa-file-export"></i>
    </div>
</div>


</div>


<div class="container" >
<div class="container_cover"></div>
<script>
    get_rows()
</script>
<?php
    $sql = "SELECT * FROM stages WHERE plan_id = '$plan_id'";
    $result = $conn->query($sql);
    while ($data = $result->fetch_assoc()){
        $complete = $data['complete'];
        $stage_id = $data['stage_id'];
        $background = $data['background'];
        if ($background != null){
            $border_colour = "1px solid " . str_replace(")",",0.4)",$background);
            $background_colour = str_replace(")",",0.06)",$background);
        }
        else {
            $border_colour = "1px solid rgba(0,0,0,0.4)";
            $background_colour = "rgba(0,0,0,0.06)";
        }
        if ($complete == "true"){
            echo "<div  class='stage_container complete' id = '$stage_id' onmouseover = 'toggle_delete(this.id)' onmouseout = 'toggle_delete(this.id)'>";
        }
        else {
            echo "<div style = 'background:$background_colour;border:$border_colour' class='stage_container' id = '$stage_id' onmouseover = 'toggle_delete(this.id)' onmouseout = 'toggle_delete(this.id)'>";        
        }
        ?>

            <div data-html2canvas-ignore class="stage_delete" onclick = 'delete_stage(this.parentElement.id)'><i class="fa-solid fa-circle-xmark"></i></div>
            <input type = "text"  onkeyup = "change_stage_name(this.parentElement.id,'old')" class="stage_title" value = "<?php echo $data['stage_name'];?>"></input>
            <div class="stage_description" data-html2canvas-ignore>
                <div id = "gear" class="option" onclick = "change_gear(this.parentElement.parentElement.id)" ><i class="fa-solid fa-gear"></i> Needed</div>
                <div id = "colour" class="option" onclick = "change_colour(this.parentElement.parentElement.id)"><i class="fa-solid fa-palette"></i>Colour
                <div class = "colour_options">
                    <div class="colour_option" style = 'background: rgba(255,0,0)'></div>
                    <div class="colour_option" style = 'background: rgba(0,255,0)'></div>
                    <div class="colour_option" style = 'background: rgba(0,0,255)'></div>
                    <div class="colour_option" style = 'background: rgb(119, 46, 179)'></div>
                    <div class="colour_option" style = 'background: rgba(0,0,0)'></div>
                </div>
                </div>

                <div id = "preset" class="option" onclick = "change_preset(this.parentElement.parentElement.id)"><i class="fa-solid fa-file-import"></i></i>Use Preset</div>

            </div>
            <div class="stage_characters">
                <div class="gear_needed gear" data-html2canvas-ignore>
                    <div class="back_arrow" onclick = "this.parentElement.parentElement.parentElement.classList.toggle('gear')"><i class="fa-solid fa-xmark"></i></div>
                    <div class="gear_title">
                        <div onclick = "this.parentElement.parentElement.classList.toggle('relic')" class="gear_option gear"><i class="fa-solid fa-gear"></i></div>
                        <div onclick = "this.parentElement.parentElement.classList.toggle('relic')" class="gear_option relic"><img src = 'images/jawa.png'></div>
                    </div>
                    <div class = 'gear_container'>

                    <div class="fp_spinner_container" >
                        <div class="spinner_container">
                            <div class="spinner_circle"></div>
                            <div class="spinner_circle outer_1"></div>
                            <div class="spinner_circle outer_2"></div>
                            <div class="spinner_circle plannet_1"></div>
                            <div class="spinner_circle plannet_2"></div>
                        </div>
                        Loading Required Gear...
                    </div>
                    <?php
                        $sql_ge = "SELECT * FROM gear;";
                        $result_ge = $conn->query($sql_ge);
                        echo "<div class = 'gear_items'>";
                        while ($gear = $result_ge->fetch_assoc()){
                            $gear_name = $gear['name'];
                            $gear_img = $gear['img'];
                            $gear_base_id = $gear['base_id'];
                            $tier = $gear['tier'];
                            if ($tier == 12){
                                $outline = "gold";
                            }
                            else if ($tier == 1 ){
                                $outline = "grey";
                            }
                            else if ($tier == 2 ){
                                $outline = "green";
                            }
                            else if ($tier == 7 || $tier == 11){
                                $outline = "purple";
                            }
                            else if ($tier == 4 ){
                                $outline = "blue";
                            }

                            echo "
                            <div id = 'g_$gear_base_id' class = 'gear_item gear_item_gear' style = 'display: none'>
                                <div class='gear_stat'>
                                        <div class = 'gear_img_container'>
                                        <img loading = 'lazy' style = 'border: 1px solid $outline' src = '$gear_img'>
                                        </div>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'gear_name_container'>
                                    $gear_name
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    
                                    <div class = 'gear_progress_bar'>
                                        <div class = 'gear_progress' style = 'width:30%' >
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'remaining'>1000</div>
                                </div>
                            </div>
                            ";
                        }
                        echo "</div>";
                    ?>
                    </div>
                    <div class = 'relic_container'>
                    <div class="fp_spinner_container_relic" >
                        <div class="spinner_container">
                            <div class="spinner_circle"></div>
                            <div class="spinner_circle outer_1"></div>
                            <div class="spinner_circle outer_2"></div>
                            <div class="spinner_circle plannet_1"></div>
                            <div class="spinner_circle plannet_2"></div>
                        </div>
                        Loading Required Gear...
                    </div>
                    <?php
                        $sql_r = "SELECT * FROM relic;";
                        $result_r = $conn->query($sql_r);
                        echo "<div class = 'gear_items'>";
                        ?>
                           <div id = "no_relics" style = 'display: none' class = 'no_results_msg'>No Relics Required</div>
                        <?php
                        while ($relic = $result_r->fetch_assoc()){
                            $relic_name = $relic['name'];
                            $relic_img = $relic['img'];
                            $relic_id = $relic['relic_id'];
                            echo "
                            <div id = 'r_$relic_id' class = 'gear_item gear_item_relic' style = 'display: none'>
                                <div class='gear_stat relic'>
                                        <img src = '$relic_img'>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'gear_name_container'>
                                    $relic_name
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    
                                    <div class = 'gear_progress_bar'>
                                        <div class = 'gear_progress' style = 'width:30%' >
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class='gear_stat'>
                                    <div class = 'remaining'>1000</div>
                                </div>
                            </div>
                            ";
                        }
                        echo "</div>";
                    ?>
                    </div>
                </div>
                <div class="characters_container">
                    <?php
                    $sql_g = "SELECT * FROM farming_character_occurance WHERE stage_id = '$stage_id'";
                    $result_g = $conn->query($sql_g);
                    while($data_g = $result_g->fetch_assoc()){
                        $occurance = $data_g['occurance_id'];
                        $char_id = $data_g['char_id'];
                        $rating = $data_g['rating'];
                        $gear = $data_g['gear'];
                        $relic = $data_g['relic'];
                        $sql_i = "SELECT * FROM character_data WHERE base_id = '$char_id'";
                        $result_i = $conn->query($sql_i);
                        while($data_i = $result_i->fetch_assoc()){
                            $img = $data_i['img'];
                            $alignment = $data_i['alignment'];
                            $name = $data_i['name'];
                        }
                        if ($gear == 13){
                            if ($alignment == "Light Side"){
                                $gear = "13-ls";

                            }
                            else{
                                $gear = "13-ds";

                            }
                        }
                        if ($relic > 0){
                            $gear = $gear . "-relic";
                            $show_relic = "show_relic";
                        }
                        else {
                            $show_relic = "now_show";
                        }
                        $blank = 7 - $rating;
                        echo "<div class = 'character' id = '$occurance' onmouseover = 'toggle_options(this.id)' onmouseout = 'toggle_options(this.id)'>
                            <div class = 'rating' data-html2canvas-ignore>
                            <i onclick = 'delete_occurance(this.parentElement.parentElement.id)' class='fa-solid fa-circle-xmark'></i>
                            </div>
                            <div class = 'row'>
                                <div class = 'sidebar' data-html2canvas-ignore>
                                <div class = 'arrow up' onclick = 'gear_character_double_up($occurance)'><i class='fa-solid fa-circle-up'></i></div>
                                <div class = 'arrow down' onclick = 'gear_character_double_down($occurance)'><i class='fa-solid fa-circle-down'></i></div>
                                </div>
                                <div class = 'middle' id = '$alignment'>
                                    <img class = 'character_img' src = '$img'>
                                    <img class = 'gear_img' alignment = '$alignment' src = 'images/public/gear-icon-g$gear.png'>
                                    <div class = 'relic_number' id = '$show_relic'>$relic</div>
                                </div>
                                <div class = 'sidebar' data-html2canvas-ignore>
                                        <div class = 'arrow up' onclick = 'gear_character_up($occurance)'><i class='fa-solid fa-circle-chevron-up'></i></div>
                                        <div class = 'arrow down' onclick = 'gear_character_down($occurance)'><i class='fa-solid fa-circle-chevron-down'></i></div>
                                </div>
                            </div>
                            <div class = 'name'>$name</div>
                        </div>";
                    }
                    ?>
                    
                    <div class="new_character_container">
                        <div id = "<?php echo $stage_id;?>" class="new_character" onclick = "add_character(this.id)">
                            <div style = 'position:absolute;transform:translateY(-20px)' class="new_text">Add</br>Character</div>
                            <div  style = 'transform:translateY(30px)' class="new_add"><span></span><span></span></div>
                        </div>
                    </div>
                </div>
                <div class="preset_container" data-html2canvas-ignore>
                    <div class="back_arrow right" onclick = "this.parentElement.parentElement.parentElement.classList.toggle('preset')"><i class="fa-solid fa-xmark"></i></i></div>
                    <div class="presets" id = "<?php echo $stage_id;?>">
                            <?php
                                $sql_p = "SELECT * FROM presets";
                                $result_p = $conn->query($sql_p);
                                while($data_p = $result_p->fetch_assoc()){
                                    $req_id = rand();
                                    $preset_img = $data_p['preset_img'];
                                    $preset_name = $data_p['preset_name'];
                                    $preset_characters = $data_p['characters'];
                                    echo "<div id = '$req_id' class = 'preset_item' onclick = 'activate_preset(this.id)'>";
                                    echo "<div class = 'preset_img_container'><img src = '$preset_img' class = 'preset_img'></div>";
                                    echo "<div class = 'preset_name'>$preset_name</div>";
                                    echo "<div class = 'required_units' style = 'display: none;'>$preset_characters</div>";
                                    echo "</div>";
                                }
                                // $galactic_legend_data = json_decode(file_get_contents("https://swgoh.gg/api/gl-checklist/"));
                                // // print_r ($galactic_legend_data->units);
                                // foreach($galactic_legend_data->units as $legend){
                                //     $required = json_encode($legend->requiredUnits);
                                //     $req_id = rand();
                                //     echo "<div id = '$req_id' class = 'preset_item' onclick = 'activate_preset(this.id)'>";
                                //     echo "<div class = 'preset_img_container'><img src = '$legend->image' class = 'preset_img'></div>";
                                //     echo "<div class = 'preset_name'>$legend->unitName</div>";
                                //     echo "<div class = 'required_units' style = 'display: none;'>$required</div>";
                                //     echo "</div>";
                                // }
                                
                            ?>
                    </div>
                </div>
            </div>
            
        </div>
 
        <?php
    }

?>
<div class="stage_container new_stage" id = "<?php echo $id;?>" onclick = "new_stage(this.id)" >
    <div class="new_text">New</br>Stage</div>
    <div class="new_add"><span></span><span></span></div>
</div>

</div>


<div class="add_character_modal">
    <div onclick = "close_modal(true)" class="close"></div>
    <!-- <div class="modal_title">Add Characters to Stage 1</div> -->
    <div class="filter_bar">
        <input id = "input_filter" type = "text" placeholder = "Search for a Character..." onkeyup = "filter_characters()">
    </div>
    <div class="character_options">
        <?php
            $sql_o = "SELECT * FROM character_data";
            $result_o = $conn->query($sql_o);
            while ($data_o = $result_o->fetch_assoc()){
                $character_name = $data_o['name'];
                $img = $data_o['img'];
                $alignment = $data_o['alignment'];
                $categories = $data_o['categories'];
                $id = $data_o['base_id'];
                echo "<div id = '$id' class='character_option' onclick = 'toggle_add_character(this.id)'>
                <div class = 'character_option_name'>
                    $character_name
                </div>
                <img loading = 'lazy' src = '$img' class = 'character_option_img $alignment'>
                <div class = 'character_option_categories'>$categories</div>
                <div class = 'tick_container'><i class='fa-solid fa-circle-check'></i></div>
                </div>";
            }
        ?>

    </div>
    <div class="add_button_container">
            <div id = "" class="add_button" onclick = "add_characters(this.id)">Add Characters</div>
    </div>
</div>



<!-- <div class="modal_export">
    <div onclick = "close_modal_export()" class="close"></div>
    <div class="export_inner">
        <div class="export_title">Choose an Export Format...</div>
        <div class="export_options">
            <div id = "eo_image" onclick = "toggle_export_option(this.id)" class="export_option">
                <img src = "images/boba.png">
                <div class="eot">Image</div>
                <div class="eoi">Export plan in image format</div>
            </div>
            <div id = "eo_link" onclick = "toggle_export_option(this.id)" class="export_option">
                <img src = "images/boba.png">
                <div class="eot">Link </div>
                <div class="eoi">Generate link to give to others to allow them to import your farming plan.</div>
            </div>
            <div id = "eo_csv" onclick = "toggle_export_option(this.id)" class="export_option">
                <img src = "images/boba.png">
                <div class="eot">CSV</div>
                <div class="eoi">Export stage gear requirements as a CSV file.</div>
            </div>
        </div>
        <div id = "f_eo_image" class="further_info">
            <div class="back_arrow left" onclick = "colapse_further()"><i class="fa-solid fa-arrow-left"></i></div>
            <div class="spinner_container">
                <div class="spinner_circle"></div>
                <div class="spinner_circle outer_1"></div>
                <div class="spinner_circle outer_2"></div>
                <div class="spinner_circle plannet_1"></div>
                <div class="spinner_circle plannet_2"></div>
            </div>
            Creating Image...
        </div>
        <div id = "f_eo_link" class="further_info">
        <div class="back_arrow left" onclick = "colapse_further()"><i class="fa-solid fa-arrow-left"></i></div>
        <div class="spinner_container">
                <div class="spinner_circle"></div>
                <div class="spinner_circle outer_1"></div>
                <div class="spinner_circle outer_2"></div>
                <div class="spinner_circle plannet_1"></div>
                <div class="spinner_circle plannet_2"></div>
            </div>
            Creating Link...
        </div>
        <div id = "f_eo_csv" class="further_info">
        <div class="back_arrow left" onclick = "colapse_further()"><i class="fa-solid fa-arrow-left"></i></div>
            <div class="spinner_container">
                <div class="spinner_circle"></div>
                <div class="spinner_circle outer_1"></div>
                <div class="spinner_circle outer_2"></div>
                <div class="spinner_circle plannet_1"></div>
                <div class="spinner_circle plannet_2"></div>
            </div>
            Creating File...
        </div>
    </div>
</div> -->

<div class="link_container" style = 'display: none'>
    <div class="spinner_container" >
        <div class="spinner_circle"></div>
        <div class="spinner_circle outer_1"></div>
        <div class="spinner_circle outer_2"></div>
        <div class="spinner_circle plannet_1"></div>
        <div class="spinner_circle plannet_2"></div>
    </div>
    <div class = 'load_link_text'>Creating Code ...</div>
    <div class = 'link_area'>
        <div class = 'close' onclick = 'close_link()'></div>
        <i class="fa-solid fa-link link_i"></i>
        <div class="link_title_bit " style = 'font-family:verdana'> Give people the following code to import your farming plan.</div>
        <div class="link_link">
            <div style = 'font-family:verdana' class="link_link_text" id="selectable" onclick="selectText('selectable')"></div>
            <div class="copy_link" onclick = 'copy_link()'><i class="fa-solid fa-copy copy_icon"></i></div>
        </div>
    </div>
</div>

<?php 

if (isset($_GET['new'])){

    ?>
    <script>
        var id = '<?php echo $plan_id?>'

        new_stage(id);
    </script>
    <?php
}

