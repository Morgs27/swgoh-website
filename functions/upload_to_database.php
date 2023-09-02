<?php
function insert_character_data($conn) {
	$sql_d = "DELETE FROM character_data";
	$result_d = $conn->query($sql_d);
	$url = 'http://api.swgoh.gg/characters/';


	$data = file_get_contents($url);
	$data = json_decode($data);

	foreach ($data as $character){
		$name = $character->name;
		$name = str_replace("'"," ",$name);
		// echo $name;
		$description = $character->description;
		$description = str_replace("'"," ",$description);
		$base_id = $character->base_id;
		$image = $character->image;
		$alignment= $character->alignment;
		$categories = $character->categories;
		$categories = implode(",",$categories);
		$gear_levels = json_encode($character->gear_levels);
		$sql = "INSERT INTO character_data (name,base_id,img,alignment,categories,description,gear_levels) VALUES ('$name','$base_id','$image','$alignment','$categories','$description','$gear_levels')";
		$result = $conn->query($sql);

		// $filenameIn  = $image;
		// $to = "C:/xampp/htdocs/updated/images/character_images/ " . $base_id . ".png";
		// $filenameOut = __DIR__ . '../images/character_images/' . $base_id . ".png";
		// echo $filenameOut;
		// $contentOrFalseOnFailure   = file_get_contents($filenameIn);
		// $byteCountOrFalseOnFailure = file_put_contents($to, $contentOrFalseOnFailure);
		
	}
}

function insert_ship_data($conn) {
	$sql_d = "DELETE FROM ship_data";
	$result_d = $conn->query($sql_d);
	$url = 'http://api.swgoh.gg/ships/';
	$data = file_get_contents($url);
	$data = json_decode($data);


	foreach ($data as $character){
		$name = $character->name;
		$name = str_replace("'","#",$name);
		echo $name;
		$base_id = $character->base_id;
		$image = $character->image;
		$alignment= $character->alignment;
		$categories = $character->categories;
		$categories = implode(",",$categories);
		$sql = "INSERT INTO ship_data (base_id,img,alignment,categories,name) VALUES ('$base_id','$image','$alignment','$categories','$name')";
		$result = $conn->query($sql);
	}
}


function insert_gear_data($conn){
	$sql_d = "DELETE FROM gear";
	$result_d = $conn->query($sql_d);
	$url = "http://api.swgoh.gg/gear";
	$data = json_decode(file_get_contents($url));
	foreach($data as $piece){
		$base_id = $piece->base_id;

		$name = $piece->name;
		$img = $piece->image;
		echo "<img src = '$img' style = 'width:20px;height:20px'>";
		$ingredients = json_encode($piece->ingredients);
		$cost = $piece->cost;
		$tier = $piece->tier;
		$sql = "INSERT INTO gear
		(base_id,name,img,ingredients,cost,tier) VALUES
		('$base_id','$name','$img','$ingredients','$cost','$tier')";
		$result = $conn->query($sql);
	}
}

function insert_gl_requirements($conn){
	$galactic_legend_data = json_decode(file_get_contents("http://api.swgoh.gg/gl-checklist/"));
	// print_r ($galactic_legend_data->units);
	foreach($galactic_legend_data->units as $legend){
		$required = json_encode($legend->requiredUnits);
		echo "<div id = '' class = 'preset_item' onclick = 'activate_preset(this.id)'>";
		echo "<div class = 'preset_img_container'><img src = '$legend->image' class = 'preset_img'></div>";
		echo "<div class = 'preset_name'>$legend->unitName</div>";
		echo "<div class = 'required_units' style = 'display: none;'>$required</div>";
		echo "</div>";
		$sql = "DELETE FROM presets WHERE preset_name = '$legend->unitName'";
		$result = $conn->query($sql);
		$sql = "INSERT INTO presets (characters,preset_img,preset_name)
		VALUES ('$required','$legend->image','$legend->unitName')";
		echo $sql;
		$result = $conn->query($sql);
	}
}

function insert_guests($conn){
	$users = array(
		array("username" => "Guest_Info__","ally_code" => "741324657"),
		array("username" => "Guest_Info___","ally_code" => "939729166"),
		array("username" => "Guest_Info____","ally_code" => "882145491"),
		array("username" => "Guest_Info_____","ally_code" => "616485783"),
		array("username" => "Guest_Info______","ally_code" => "644744399"),
		array("username" => "Guest_Info_______","ally_code" => "479461123"),
		array("username" => "Guest_Info________","ally_code" => "771957566"),
		array("username" => "Guest_Info_________","ally_code" => "528234451")
	);

	foreach ($users as $user){
		$username = $user['username'];
		$ally_code = $user['ally_code'];
		$sql = "INSERT INTO users (Username,ally_code,pro,perma_pro,guild_id)
		VALUES ('$username','$ally_code','true','true',9999999)";
		echo $sql;
		$result = $conn->query($sql);
	}
}