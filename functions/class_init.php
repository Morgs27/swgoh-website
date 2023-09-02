<?php

function get_token(){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.swgoh.help/auth/signin',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'username=Morgs27&password=Morg%3D2708&grant_type=password&client_id=abc&client_secret=123',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Cookie: connect.sid=s%3A1zQAkVvKLSrCA_dSauSIFf8ZnF83eePI.15EgDM2DWGbdldJZDu43OXIkyd%2BRE2tBWClDU50onec'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response);
if (isset($response->error)){
	return null;
}
else {
return $response->access_token;
}
}


function get_player_data($allyCodes){
	$curl = curl_init();
	$token = get_token();
	if ($token == null){
		return null;
	}
	else {
	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://api.swgoh.help/swgoh/player',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{
		"allycodes": ['.$allyCodes.'],
		"languege":"eng_us",
		"enums": false,
		"structure": false
	}',
	CURLOPT_HTTPHEADER => array(
		'Authorization: Bearer ' .$token,
		'Content-Type: application/json',
		'Cookie: connect.sid=s%3A1zQAkVvKLSrCA_dSauSIFf8ZnF83eePI.15EgDM2DWGbdldJZDu43OXIkyd%2BRE2tBWClDU50onec'
	),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return (json_decode($response));
	}
}

function refresh_player_data($allycode,$username,$conn){
	$data = get_player_data($allycode);
	calculate_player_data($data,$username);

}

function calculate_player_data($data,$username){
	$data = json_encode($data);
	$username = json_encode($username);
	?>
	<script>
	var myHeaders = new Headers();
	myHeaders.append("Content-Type", "application/json");

	var raw = JSON.stringify(<?php print_r($data); ?>);

	var requestOptions = {
	method: 'POST',
	headers: myHeaders,
	body: raw,
	redirect: 'follow'
	};



	fetch("https://swgoh-stat-calc.glitch.me/api?flags=calcGP,percentVals", requestOptions)
	.then(response => response.text())
	.then(result => convert_to_php(result,<?php echo $username; ?>))
	.catch(error => console.log('error', error));

	

	function convert_to_php(result,username){
		$.ajax({
		url: "includes/refresh_user_data.inc.php",
		method: "POST",   
		data: {json: result, data_u: username},
		success: function(data){console.log(data);},
		error: function(errMsg) {
			alert(JSON.stringify(errMsg));
		}
		});

	}

	</script>
	<?php

}


function getPlayerInfo_new($username,$conn){
	echo 'getting player info...';
	$sql = "SELECT * FROM user_character_data WHERE Username = '$username'";
	$result = $conn->query($sql);
	$characters = array();
	while ($data = $result->fetch_assoc()) {
		$character = new character_new;
		// $json_stats_base = $data['stats_base'];
		// $stats = json_decode($json_stats_base);
		// $stats_base = new stats;
		// $stats_base->set_stats($stats->)
		print_r($data);
		
		$character->set_values_character($data['id'],$data['defId'],$data['nameKey'],$data['rarity'],$data['level'],$data['gear'],$data['gp'],$data['relic'],$data['zetas'],$data['omicrons'],
		$data['stats_base'],$data['stats_mods'],$data['mods'],$data['speed'],$data['health'],$data['protection'],$data['tenacity'],$data['potency'],$data['special_damage'],$data['physical_damage'],$data['ultimate']);
		

		print_r($characters);
		array_push($characters,$character);
		
	}
	print_r($characters);
	return $characters;
}

function getPlayerInfo_ship($username,$conn){
	$sql = "SELECT * FROM user_ship_data WHERE Username = '$username'";
	$result = $conn->query($sql);
	$ships = array();
	while ($data = $result->fetch_assoc()) {
		$ship = new ship_new;
		$ship->set_values_ship($data['id'],$data['defId'],$data['rarity'],$data['level'],$data['gp'],$data['crew']);
		
		array_push($ships,$ship);
		
	}
	return $ships;
}

function total_stat($base,$mods,$property){
	$total = 0;
	if (isset($base->$property)){
		 $total += $base->$property;
	}
	if (isset($mods->$property)){
		$total += $mods->$property;
	}
	return $total;
}


function display_character_new($conn,$character,$current_team){

	echo 'Here I am here';

	print_r($character);

	$edit = false;
	if (isset($current_team[1])){
		if ($current_team[1] == "edit"){
			$edit = true;
	}
}	
	if ($current_team[0] == "CurrentTeam"){
		$style = "Current";
	}
	else if ($current_team[0] == "Team"){
		$style = "team";
	}
	else {
		$style = "NONE";
	}
	$in_team = false;
	$defID = $character->defId;
	foreach ($current_team as $check_id){
		if ($check_id == $defID){
			$in_team = true;
		}
	}

	$in_teams_b = 'false';
	$in_teams = get_characters_in_team($_SESSION['Username'],$conn);
	foreach ($in_teams as $team_character_id){
		if ($team_character_id == $defID){
			$in_teams_b = 'true';
		}
	}
	

	$sql = "SELECT * FROM character_data WHERE base_id = '$defID'";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()) {

		print_r($data);

		$name = $data['name'];
		$img = $data['img'];
		$alignment = $data['alignment'];
		$categories = $data['categories'];
		// echo "<img src='$img'>";
	}



	if(strpos($categories, "Galactic Legend") !== false){
		$legend = true;
	}
	else {
		$legend = false;
	}
	if (isset ($img)){
		
	}
	else {
		exit();
	}
	$rarity = $character->rarity;
	$level = $character->level;
	$gear = $character->gear;
	$gp = $character->gp;
	$zetas = $character->zetas;
	$relic = $character->relic;
	$omicrons = $character->omicrons;
	$ultimate = $character->ultimate;
	$gear_none = "";
	if ($gear == 0){
		$gear_none = "gear_none";
	}
	$x = 0;
	while ($x < 8){
		if ($rarity == $x){
		$rarity_img = "images/public/rarity$x.png";
		
		
		}
	$x = $x + 1;
	}
	$x = 0;
	while ($x < 14){
		if ($gear == $x){
		$gear_img = "images/public/gear-icon-g$x.png";
		
		
		}
	$x = $x + 1;
	}
	$relic_image = false;
	if ($relic > 1){
		if ($alignment == "Dark Side"){
			$gear_img = "images/public/gear-icon-g13-ds.png";
		}
		if ($alignment == "Light Side"){
			$gear_img = "images/public/gear-icon-g13-ls.png";
		}
	}
	if ($relic >2){
		$relic_image = true;
		if ($alignment == "Dark Side"){
			if ($ultimate == 'true'){
				$gear_img = "images/public/gear-icon-g13-ds-relic-ulti.png";
			}
			else {
			$gear_img = "images/public/gear-icon-g13-ds-relic.png";
			}
		}
		if ($alignment == "Light Side"){
			if ($ultimate == 'true'){
				$gear_img = "images/public/gear-icon-g13-ls-relic-ulti.png";
			}
			else {
			$gear_img = "images/public/gear-icon-g13-ls-relic.png";
			}
		}
	}
	
	$zeta_img = false;
	if ($zetas > 0){
		$zeta_img = true;
	}
	$display_relic = $relic - 2;
	echo "<div id = '$defID'>";



	if ($style == "NONE"){
		if ($legend == true ){
		echo "<div $gear_none class='character_profile_full galactic_legend $in_teams_b' id = 'app_char_{$defID}_gp_$gp' onclick = 'add_char_team(this.id)'>";
		}
		else{
		echo "<div $gear_none class='character_profile_full $in_teams_b' id = 'app_char_{$defID}_gp_$gp' onclick = 'add_char_team(this.id)'>";
		}
	}
	else {
		echo "<div $gear_none class='character_profile_full'>";
	}

	// }
	?>
	<script>
		function add_char_team(id){
			var gls = ['SUPREMELEADERKYLOREN','GLREY','LORDVADER','JEDIMASTERKENOBI','GRANDMASTERLUKE','SITHPALPATINE' ];
			var current_team = window.team;
			let new_id = id.replace("app","rem");
			let class_name = document.getElementById(id).className;
			if (class_name.length > 30){
				for (let i = 0; i < current_team.length; i++) {
					if (gls.indexOf(current_team[i]) !== -1){
						return;
					}
				}
				x = document.getElementsByClassName('galactic_legend');
				var i;
				for (i = 0; i < x.length; i++) {
					if (x[i].id === id){}
					else if(x[i].id === new_id){}
					else{
					x[i].style.opacity = "0.3";
					
					}
				}

			}
			var number= window.number;
			if (number > 4){
				document.getElementById('error_container').innerHTML  = "You have reached the maximum number of characters in a team";
				setTimeout(function(){ document.getElementById('error_container').innerHTML  = ""; }, 3000);
			}
			else{
			
			var appended = window.appended;
			let position = id.search('gp');
			var char_id = id.slice(9,(position-1));
			var gp = parseInt(id.slice((position + 3)));
			var content = ((document.getElementById(char_id).innerHTML).replace("app","rem")).replace("add_char_team","delete_char_team");
			document.getElementById(char_id).style.display = "none";

			if (appended.includes(char_id) === true){
				display_id = id.replace("app","rem");
				document.getElementById(display_id).style.display = "block";
			}	
			else {

				window.appended.push(char_id);
				$('#team_char_container').append(content);
			}	
			window.team.push(char_id);
			var new_gp = window.gp + gp;
			window.gp = new_gp;
			window.number += 1;
			document.getElementById('gp').innerHTML = "GP: " + new_gp;
			}

		}
		function delete_char_team(id){
			let class_name = document.getElementById(id).className;
			if (class_name.length > 25){
				x = document.getElementsByClassName('galactic_legend');
				var i;
				for (i = 0; i < x.length; i++) {
				x[i].style.opacity = "1";
				}
			}
			var current_team = window.team;
			let position = id.search('gp');
			var gp = parseInt(id.slice((position + 3)));
			var char_id = id.slice(9,(position-1));
			document.getElementById(char_id).style.display = "block";
			document.getElementById(id).style.display = "none";
			var index_of_team = current_team.indexOf(char_id);
			current_team.splice(index_of_team,1);
			window.team = current_team;
			var new_gp = window.gp - gp;
			window.gp = new_gp;
			document.getElementById('gp').innerHTML = "GP: " + new_gp;
			window.number -= 1;
		}
	</script>
	<?php
	if ($style == "NONE"){
		echo "<div class=''>";
		
	}
	else if ($style =="team"){
		echo "<div class=''>";
		
	}
	else {
		echo "<div class='current_team_new'>";
	}

	echo "<div class='character_profile_profile'>";
	echo "<img loading = 'lazy' alt = '' id = 'character_img' src = '$img' >";
	echo "<img loading = 'lazy' alt = '' id = 'gear_img'  src = '$gear_img'>";
	if ($relic_image == true){
		echo "<img loading = 'lazy' alt = '' id = 'rarity_img' src = '$rarity_img'>";
		
	}
	else{
			if ($zeta_img == true){
				echo "<img loading = 'lazy' alt = '' id = 'rarity_img_current'src = '$rarity_img'>";
			}
			else {
			echo "<img loading = 'lazy' alt = '' id = 'rarity_img'  src = '$rarity_img'>";
			}
		
	}
	if ($relic_image == true){
		echo "<div id = 'display_relic'>$display_relic</div>";
		if ($zeta_img == true){
		echo "<img loading = 'lazy' alt = '' id = 'zeta_img_relic' src = 'images/public/tex.skill_zeta.png'><div id = 'zeta_number_relic'>$zetas</div></img>";

		}
	}
	else {
		if ($zeta_img == true){
		echo "<img loading = 'lazy' alt = '' id = 'zeta_img' src = 'images/public/tex.skill_zeta.png'><div id = 'zeta_number'>$zetas</div></img>";

	}
	}

	if ($omicrons > 0){
		if ($relic_image ==true){
			if ($zeta_img != true){
				echo "<img loading = 'lazy' alt = '' id = 'omicron_img_nr' src = 'images/omicron.png'>";
			    echo "<div class = 'omicron_number nr'>$omicrons</div>";
			}
			else {
			echo "<img loading = 'lazy' alt = '' id = 'omicron_img' src = 'images/omicron.png'>";
			echo "<div class = 'omicron_number'>$omicrons</div>";
			}
		}
		else {
			if ($zeta_img != true){
				echo "<img loading = 'lazy' alt = '' id = 'omicron_img_nz_nr' src = 'images/omicron.png'>";
			    echo "<div class = 'omicron_number nr_nz'>$omicrons</div>";
			}
			else {
			echo "<img loading = 'lazy' alt = '' id = 'omicron_img_nr' src = 'images/omicron.png'>";
			echo "<div class = 'omicron_number nr'>$omicrons</div>";
			}
		}
	}



	echo "</div>";
	
	if ($edit == true){
	}
	else {
	echo "<div id = 'character_name' class = 'character_name'>$name</div>";
	echo "<div class = 'factions_contained' style = 'display:none'>$categories</div>";
	}

	$stats_base =  json_decode($character->stats_base);
    $stats_mods =  json_decode($character->stats_mods);

	$potency = (round($character->potency * 100) . "%");
    $tenacity = (round($character->tenacity * 100) . "%");

	$hp = number_format($character->health + $character->protection);
	


	print_r($stats_base);
	print_r($stats_mods);

	if (isset($stats_base->Armor)){
		if (isset($stats_mods->Armor)){
			$armor = $stats_mods->Armor + $stats_base->Armor;
		}
		else {
			$armor = $stats_base->Armor;
		}
	}
	else{
		if (isset($stats_mods->Armor)){
			$armor = $stats_mods->Armor;
		}
		else {
			$armor = 0;
		}
	}

	$str = 'Physical Critical Chance';
	if (isset($stats_base->$str)){
		if (isset($stats_mods->$str)){
			$cc = $stats_mods->$str + $stats_base->$str;
		}
		else {
			$cc = $stats_base->$str;
		}
	}
	else{
		if (isset($stats_mods->$str)){
			$cc = $stats_mods->$str;
		}
		else {
			$cc = 0;
		}
	}

	$str = 'Critical Damage';
	if (isset($stats_base->$str)){
		if (isset($stats_mods->$str)){
			$cd = $stats_mods->$str + $stats_base->$str;
		}
		else {
			$cd = $stats_base->$str;
		}
	}
	else{
		if (isset($stats_mods->$str)){
			$cd = $stats_mods->$str;
		}
		else {
			$cd = 0;
		}
	}

	$cc = round($cc* 100) . "%";

	$cd = round($cd* 100) . "%";

	$armor = round($armor * 100) . "%";

	$offence = round($character->physical_damage / 1000,1) . "K / " . round($character->special_damage / 1000,1) . "K";

	if(strpos($categories, "Support") !== false){
		?>
		<div class="character_stats_popup">
			
			<div class="character_stat"><i class="fa-solid fa-person-running"></i><div class="stat_number"><?php echo $character->speed;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-heart"></i><div class="stat_number"><?php echo $hp;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-crosshairs"></i><div class="stat_number"><?php echo $potency;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-hand-back-fist"></i><div class="stat_number"><?php echo $tenacity;?></div></div>
		</div>
		<?php
	}
	if(strpos($categories, "Tank") !== false){
		?>
		<div class="character_stats_popup">
			
			<div class="character_stat"><i class="fa-solid fa-person-running"></i><div class="stat_number"><?php echo $character->speed;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-heart"></i><div class="stat_number"><?php echo $hp;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-shield"></i><div class="stat_number"><?php echo $armor;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-hand-back-fist"></i><div class="stat_number"><?php echo $tenacity;?></div></div>
		</div>
		<?php
	}
	if(strpos($categories, "Attacker") !== false){
		?>
		<div class="character_stats_popup">
			
			<div class="character_stat"><i class="fa-solid fa-person-running"></i><div class="stat_number"><?php echo $character->speed;?></div></div>
			<div class="character_stat"><div class = 'stat_img'><img src = 'images/offence_mod.png'></div><div class="stat_number offence"><?php echo $offence;?></div></div>
			<div class="character_stat"><div class = 'stat_img'><img src = 'images/crit_chance_mod.png'></div><div class="stat_number"><?php echo $cc;?></div></div>
			<div class="character_stat"><div class = 'stat_img'><img src = 'images/crit_damage_mod.png'></div><div class = 'stat_number'><?php echo $cd;?></div></div>
		</div>
		<?php
	}
	if(strpos($categories, "Healer") !== false){
		?>
		<div class="character_stats_popup">
			
			<div class="character_stat"><i class="fa-solid fa-person-running"></i><div class="stat_number"><?php echo $character->speed;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-heart"></i><div class="stat_number"><?php echo $hp;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-crosshairs"></i><div class="stat_number"><?php echo $potency;?></div></div>
			<div class="character_stat"><i class="fa-solid fa-hand-back-fist"></i><div class="stat_number"><?php echo $tenacity;?></div></div>
		</div>
		<?php
	}
	
	echo "</div>";
	echo "</div>";
	echo "</div>";
	
		
}

function display_ship($conn,$ship,$current_team,$user_characters,$username,$capital){

	$defID = $ship->defId;

	$in_teams_b = 'false';
	$in_teams = get_ships_in_team($_SESSION['Username'],$conn);
	foreach ($in_teams as $team_character_id){
		if ($team_character_id == $defID){
			$in_teams_b = 'true';
		}
	}

	$style = 'none';
	$link = true;
	if ($capital == "small"){
		$style = "small";
		$link = false;
	}
	if ($capital == "team"){
		$link = false;
	}
	$init = substr($defID,0,7);
	if ($init == "CAPITAL"){
		$capital = true;
	}
	else {
		$capital = false;
	}
		$sql = "SELECT * FROM ship_data WHERE base_id = '$defID'";
		$result = $conn->query($sql);
		while ($data = $result->fetch_assoc()) {
			$img = $data['img'];
			$name = $data['name'];
			$categories = $data['categories'];
			// $name = str_replace("#","'",$name);
		}
		$rarity = $ship->rarity;
		$level = $ship->level;
		$gp = $ship->gp;
		$crew = $ship->crew;
		$x = 1;
		while ($x < 8){
			if ($rarity == $x){
				$rarity_img = "images/public/ship_rarity$x.png";
			}
		$x = $x + 1;
		}
		$pilots = explode(",",$crew);
		
		if($capital == true){
			echo "<div class = 'capital' id = '$defID'>";
		}
		else {
			echo "<div id = '$defID'>";
		}
		if ($link == false){
			echo "<div class = 'ship_profile $in_teams_b'>";
		}
		else {
			if ($capital == true){
			echo "<div class = 'ship_profile capital rarity_$rarity $in_teams_b'  id = 'app_char_{$defID}_gp_$gp' onclick = 'add_capital_team(this.id)'>";
			}
			else {
			echo "<div class = 'ship_profile $in_teams_b'  id = 'app_char_{$defID}_gp_$gp' onclick = 'add_char_team(this.id)'>";
			}
		}
		echo "<div style = 'display:none' class = 'character_name'>$name</div>";
		echo "<div style = 'display:none' class = 'categories'>$categories</div>";
		?>
		<script>
		function add_char_team(id){

			var capital = window.capital;
			if (capital === "none"){
				document.getElementById('error_container').innerHTML  = "You must select a capital ship first!";
				setTimeout(function(){ document.getElementById('error_container').innerHTML  = ""; }, 3000);
			}
			else {
			
			var current_team = window.team;
			var number= window.number;
			var max_number = window.max_number;
			if (number > max_number){
				document.getElementById('error_container').innerHTML  = "You have reached the maximum number of ships in a team";
				setTimeout(function(){ document.getElementById('error_container').innerHTML  = ""; }, 3000);
			}
			else{
			var appended = window.appended;
			let position = id.search('gp');
			var char_id = id.slice(9,(position-1));
			var gp = parseInt(id.slice((position + 3)));
			var content = ((document.getElementById(char_id).innerHTML).replace("app","rem")).replace("add_char_team","delete_char_team");
			document.getElementById(char_id).style.display = "none";

			if (appended.includes(char_id) === true){
				display_id = id.replace("app","rem");
				document.getElementById(display_id).style.display = "block";
			}	
			else {

				window.appended.push(char_id);
				$('#ship_char_container').append(content);
			}	
			window.team.push(char_id);
			var new_gp = window.gp + gp;
			window.gp = new_gp;
			window.number += 1;
			document.getElementById('gp').innerHTML = "GP: " + new_gp;
			}
			}

		}
		function add_capital_team(id){
			var class_name = document.getElementById(id).className;
			let rarity = class_name.slice(28,29);
			if (rarity === '7'){
				window.max_number = 6;
			}
			if (rarity === '6'){
				window.max_number = 5;
			}
			if (rarity === '5'){
				window.max_number = 5;
			}
			if (rarity === '4'){
				window.max_number = 4;
			}
			var capital = window.capital;
			if (capital === "none"){
				let position = id.search('gp');
				var char_id = id.slice(9,(position-1));
				var gp = parseInt(id.slice((position + 3)));
				var content = ((document.getElementById(char_id).innerHTML).replace("app","rem")).replace("add_capital_team","clear_team");
				document.getElementById(char_id).style.display = "none";
				let ships = document.getElementsByClassName('ship_profile');
				for ( let i = 0; i < ships.length; i++) {
					if (ships[i].classList.contains("capital")){
						ships[i].style.opacity = '0.3';
					}
					else {
						ships[i].style.opacity = '1';
					}
				}
				$('#ship_char_container').append(content);
				window.capital = char_id;
				var new_gp = window.gp + gp;
				window.gp = new_gp;
				document.getElementById('gp').innerHTML = "GP: " + new_gp;
			}
		}

		function delete_char_team(id){
			var current_team = window.team;
			let position = id.search('gp');
			var gp = parseInt(id.slice((position + 3)));
			var char_id = id.slice(9,(position-1));
			document.getElementById(char_id).style.display = "block";
			document.getElementById(id).style.display = "none";
			var index_of_team = current_team.indexOf(char_id);
			current_team.splice(index_of_team,1);
			window.team = current_team;
			var new_gp = window.gp - gp;
			window.gp = new_gp;
			window.number -= 1;
			document.getElementById('gp').innerHTML = "GP: " + new_gp;
			
		}
	</script>
	<?php
				echo "<div class = 'ship_name_tb' id = '$name' style = 'display: none;'></div>";
				echo "<div class = ''>";
					echo "<img class = 'ship_img_profile' src='$img'>";
					echo "</img>";
					echo "<div class = 'character_profile_ship'>";
						foreach ($pilots as $pilot){
							if ($pilot !== ""){
							$pilot_info = get_pilot_info($conn,$pilot,$user_characters,$username);
							$pilot_gear = $pilot_info[0];
							$pilot_alignment = $pilot_info[2];
							if ($pilot_gear == 13){
								if ($pilot_alignment == "Light Side"){
									$gear_img = "images/public/gear-icon-g13-ls.png";
								}
								else {
									$gear_img = "images/public/gear-icon-g13-ds.png";
								}
							}
							else {
							$x = 1;
							while ($x < 13){
								if ($pilot_gear == $x){
									$gear_img = "images/public/gear-icon-g$x.png";
								}
							$x = $x + 1;
							}
							}
							$pilot_img = $pilot_info[1];
							echo "<div class = 'character_profile_profile_ship'>";
							echo "<img src='$pilot_img'>";
							echo "<img class = 'pilot_gear_img' src='$gear_img'>";
							echo "</img>";
								echo "</div>";
							}
						}
					echo "</div>";
					echo "<img id = 'rarity_img_ship'  src = '$rarity_img'>";

		
			echo "</div>";
		echo "</div>";
		echo "</div>";
	

}

function get_pilot_info($conn,$pilot_id,$user_characters,$username){
	foreach ($user_characters as $character){
		if ($character->defId == $pilot_id){
			$pilot_gear = $character->gear;
		}
	}
	$sql = "SELECT * FROM character_data WHERE base_id = '$pilot_id' ";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()) {
		$pilot_img = $data['img'];
		$pilot_alignment = $data['alignment'];
	}
	$pilot_info = array();
	array_push($pilot_info,$pilot_gear);
	array_push($pilot_info,$pilot_img);
	array_push($pilot_info,$pilot_alignment);
	return $pilot_info;
	
}

function add_ship_to_team($current_team){
	$shipID = $_GET['add_ship'];
	if (in_array($shipID, $current_team)){
		?><div class = 'error_container' onclick="this.style.display='none'" ><?php
		echo "<div class = 'team_error'>Selected ship is already in your team!</div></div>";
	}
	else {
		array_push($current_team,$shipID);	
		$_SESSION["current_team_ship"] = $current_team ;
		if (isset($_GET["keyword"])){
			header("location: search_keyword.php?keyword={$_GET['keyword']}");
		}
		if (isset($_GET["ships"])){
			header("location: team_builder.php?ships");
		}
		else{
			header("location: team_builder.php");
		}
	}
}

function show_current_ship_team($current_team,$conn,$user_characters,$user_ships){
	$dummy_array = array("CurrentTeam");
	$team_count = count($current_team);
	if ($team_count == '1') {
		echo "";
	}
	else {
		foreach ($current_team as $ship_id){
			$init = substr($ship_id,0,7);
			if ($init == "CAPITAL"){
			foreach ($user_ships as $ship){
				if ($ship->defId == $ship_id){
					display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"current");
				}
			}
			}
		}
		foreach ($current_team as $ship_id){
			$init = substr($ship_id,0,7);
			if ($init !== "CAPITAL"){
			foreach ($user_ships as $ship){
				if ($ship->defId == $ship_id){
					display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"current");
				}
			}
			}
		}
		
		
	}
	
}

function delete_ship_from_team($ship_id,$current_team){
	$position = -1;
	foreach ($current_team as $x) {
		$position= $position + 1;
		if ($x == $ship_id){
			array_splice($current_team,$position,1);
			$_SESSION["current_team_ship"] = $current_team ;
			if (isset($_GET["keyword"])){
				header("location: search_keyword.php?keyword={$_GET['keyword']}");
			}
			else{
				header("location: team_builder.php?ships");
			
			}
			
		}
	}
}

function saveTeam_ship($current_team,$conn,$username,$user_characters,$user_ships,$gp){
	$ships = implode("," ,$current_team);
	

	$sql = "INSERT INTO ship_team (Username,ships,gp) VALUES ('$username','$ships','$gp')";
	if ($conn->query($sql) === TRUE) {
		$_SESSION["current_team_ship"] = array ();
		header("location: team_builder.php?ships&saved");
	}
	else{
		?><div class = 'error_container' onclick="this.style.display='none'" ><?php
		echo "<div class = 'team_error'>Unable to save team!</div></div>";
	}
	
}

function delete_team_ship($conn,$team_id){
	$sql = "DELETE FROM ship_team WHERE ship_team_id = $team_id";
	$result = $conn->query($sql);
	header("location: myTeams.php?ships");
}

function update_team_gp($conn,$user_characters,$username){
	$sql = "SELECT * FROM teams WHERE username = '$username'";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()) {
		$team = array( $data['LeaderID'], $data['Character2ID'] , $data['Character3ID'] , $data['Character4ID'] , $data['Character5ID']);
		$gp = calculate_gp($team,$user_characters);
		$team_id = $data['team_id'];
		$sql_u = "UPDATE teams SET team_gp = '$gp' WHERE team_id = '$team_id'";
		$result_u = $conn->query($sql_u);
	}
}


function update_team_gp_ship($conn,$user_characters,$user_ships,$username){
	$sql = "SELECT * FROM ship_team WHERE username = '$username'";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()) {
		$team = $data['ships'];
		$team = explode(",",$team);
		$gp = calculate_gp($team,$user_ships);
		$team_id = $data['ship_team_id'];
		$sql_u = "UPDATE ship_team SET gp = '$gp' WHERE ship_team_id = '$team_id'";
		$result_u = $conn->query($sql_u);
	}
}




function update_team_gp_all($conn){

	$sql = "SELECT * FROM teams";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()) {
		$username = $data['Username'];
		$allycode = $ally_code = get_ally_code($conn,$username);
		$user_characters =  getPlayerInfo_new($username,$conn);
		$team = array( $data['LeaderID'], $data['Character2ID'] , $data['Character3ID'] , $data['Character4ID'] , $data['Character5ID']);
		$gp = calculate_gp($team,$user_characters);
		$team_id = $data['team_id'];
		$sql_u = "UPDATE teams SET team_gp = '$gp' WHERE team_id = '$team_id'";
		$result_u = $conn->query($sql_u);
	}
}

function refresh_all_player_data($conn){
	$sql  = "SELECT * FROM users";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()) {
		$username = $data['Username'];
		$allycode = $data['ally_code'];
		echo $username;
		echo "     ";
		refresh_player_data($allycode,$username,$conn);
	}
}


function show_fleet_options($conn,$userStr,$loadout_id,$sort,$keyword){
	$teams_already_in_use = get_teams_already_in_use($conn,$loadout_id);
	$territory = $_GET['territory'];
	if (isset($_GET['team_filter_ship'])){
		$team_filter_ship = $_GET['team_filter_ship'];
	}
	else {
		$team_filter_ship= "none";
	}
	if (isset($_GET['sort'])){
		$sort = $_GET['sort'];
	}
	else {
		$sort = "none";
	}
	
	
	echo "<div class = 'team_sorter_loadouts'>";
		?>
		<form method="get" action=""> 
			<input type = "text" style = 'width: 80%;'placeholder="Search for a ship" class = "searchChar_input" name="team_filter_ship"></input>
			<input type='hidden' name='i' value='<?php echo $loadout_id; ?>'/>
			<input type='hidden' name='territory' value='<?php echo $territory; ?>'/>
		</form>
		
		<div class="arrow_buttons arrow_buttons_loadout">
		<a href="edit_loadout.php?i=<?php echo $loadout_id; ?>&sort=up&territory=<?php echo $territory; ?>&team_filter_ship=<?php echo $keyword; ?>"> 
		<button class = "arrow_button" >
			<i class="material-icons">arrow_upward</i>
		</button>
		</a>
		<a href="edit_loadout.php?i=<?php echo $loadout_id; ?>&sort=down&territory=<?php echo $territory; ?>&team_filter_ship=<?php echo $keyword; ?>"> 
		<button class = "arrow_button" >
			<i class="material-icons">arrow_downward</i>
		</button>
		</a>
		</div>
		<?php
	echo "</div>";
	
	$sql = "SELECT * FROM ship_team WHERE username IN ('$userStr')" ;
	
	if (isset($_GET["team_filter_ship"])){
		$filter = $_GET['team_filter_ship'];
		if ($filter == "none"){
		}
		else {
		$sql = "SELECT * FROM ship_team WHERE username IN ('$userStr') AND ships LIKE '%" . $filter . "%'";
		echo "<div style='width: 100%;padding-left: 30px;display:flex;flex-direction:row;margin-top:5px'><a href='edit_loadout.php?i=$loadout_id&territory=$territory' style='height: 100%;display:flex;align-items:center;text-decoration:none;'><i class='material-icons'>arrow_back</i></a>Search results for: $filter</div>";
		}
	}

	if (isset($_GET["sort"])){
		$direction = $_GET["sort"];
		if ($direction == "none"){
			$sql = $sql . "ORDER BY gp DESC";
		}
		else if ($direction == "up"){
			$sql = $sql . "ORDER BY gp ASC";
		}
		else if ($direction == "down"){
			$sql = $sql . "ORDER BY gp DESC";
		}
		else{
			$sql = $sql . "ORDER BY gp DESC";
		}
	}
	else {
		$sql = $sql . "ORDER BY gp DESC";
	}
	
	
	
	
	
	
	$result = $conn->query($sql);
	
	if (mysqli_num_rows($result) == 0){
		echo "<div class = 'no_team'>Your guild currently has no  ship teams.</br> Try creating a team in the Team Builder page.</div>";
	}
	echo "<div class = 'teams teams_loadout_ships' id = 'ship_div2'>";
	while ($data = $result->fetch_assoc()) {
		$in_team = "false";

		foreach ($teams_already_in_use as $team_id){
			$team_id_strip = trim($team_id);
			if ($team_id_strip == $data['ship_team_id']){
				$in_team = "true";
			}
		
		}
		
		if ($in_team == "false"){
			$team_id = $data['ship_team_id'];
			$user_characters = getPlayerInfo_new($data['Username'],$conn);
			$user_ships = getPlayerInfo_ship($data['Username'],$conn);
			$ships = $data['ships'];
			$ships = explode(",",$ships);
			array_shift($ships);
			echo "<div id = 'outer_team_id_$team_id'>";
			echo "<div class='team_container' id = 'team_id_$team_id' onclick = 'add_team_loadout(this.id)' >";
			?>
			<script>
			
				function add_team_loadout(id){
					var outer = "outer_" + id;
					var content = document.getElementById(outer).innerHTML;
					var content = content.replace("team_id","in_loadout");
					var content= content.replace("add_team_loadout","move_team_loadout");
					document.getElementById(id).style.display= "none";
					$('#ship_div1').append(content);
					document.getElementById(id).onclick = function onclick(event){move_team_loadout(this.id)};
					var add_id = id.replace("team_id_","");
					add_id = add_id.replace("in_loadout_","");
					window.add_values.push(add_id);
					var number = window.number;
					var new_number = number + 1;
					window.number = new_number;
					document.getElementById("number_of_teams").innerHTML = new_number;
					
				}
				
				function move_team_loadout(id){
					var letter = id.charAt(0);
					var new_id = "";
					if (letter === 'i'){
						new_id = id.replace("in_loadout","team_id");
						document.getElementById(id).style.display = "none";
						document.getElementById(new_id).style.display = "block";
						var len = window.add_values.length;
						for (let i = 0;i < len;i++){
							var add_id = id.replace("team_id_","");
							add_id = add_id.replace("in_loadout_","");
							if (window.add_values[i] === add_id){
								window.add_values.splice(i,1);
							}
						}
						var number = window.number;
						var new_number = number - 1;
						window.number = new_number;
						document.getElementById("number_of_teams").innerHTML = new_number;
					}
					if (letter === 't'){
						new_id = id.replace("team_id","in_loadout");
						document.getElementById(id).style.display = "none";
						document.getElementById(new_id).style.display = "block";
						var add_id = id.replace("team_id_","");
						add_id = add_id.replace("in_loadout_","");
						window.add_values.push(add_id);
						var number = window.number;
						var new_number = number + 1;
						window.number = new_number;
						document.getElementById("number_of_teams").innerHTML = new_number;
					}
				
				}
				
			</script>
			<?php
			echo "<div class='team_info_container' >";
			echo "<div class = 'team_info'>{$data['gp']}</div>";
			echo "<div class = 'team_info_right'>{$data['Username']}</div>";
			echo "</div>";
		
			$current_team = array();
			echo "<div class='team_characters' style='margin-top:10px;'>";
			foreach ($ships as $ship_id){
				$init = substr($ship_id,0,7);
				if ($init == "CAPITAL"){
				foreach ($user_ships as $ship){
					if ($ship->defId == $ship_id){
						display_ship($conn,$ship,$current_team,$user_characters,$data['Username'],"team");
					}
				}
				}
			}
			foreach ($ships as $ship_id){
				$init = substr($ship_id,0,7);
				if ($init !== "CAPITAL"){
				foreach ($user_ships as $ship){
					if ($ship->defId == $ship_id){
						display_ship($conn,$ship,$current_team,$user_characters,$data['Username'],"small");
					}
				}
				}
			}
		
		echo "</div>";
		echo "</div>";
		echo "</div>";
		}	
	}
	echo "</div>";
	
}

function display_ship_teams_in_loadout($conn,$territory,$loadout_id,$show,$username){
	$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
	$result = $conn->query($sql);
	
	if ($territory == ""){
		echo "<div class='no_teams_in_territory'>Please Select a territory</div>";
	}
	else{
		while ($data = $result ->fetch_assoc()) {
			$teams = $data[$territory];
	
		}
	}
	if ($teams == ""){
		echo "<div class='no_teams_in_territory'>There are currently no teams assigned to this territory</div>";
	}
	$team_parts = explode(",", $teams);
	echo "<div class = 'teams territory' style = 'margin-top: 10px'>";
	foreach ($team_parts as $team_id){
		$sql_t = "SELECT * FROM ship_team WHERE ship_team_id = '$team_id'";
		if ($show == "my"){
			$sql_t = "SELECT * FROM ship_team WHERE ship_team_id = '$team_id' AND Username = '$username'";
		}
		$result_t = $conn->query($sql_t);
		while ($data_t = $result_t->fetch_assoc()) {
		$check = ships_check_team_checked($conn,$team_id);
		$user_characters = getPlayerInfo_new($data_t['Username'],$conn);
		$user_ships = getPlayerInfo_ship($data_t['Username'],$conn);
		$ships = $data_t['ships'];
		$ships = explode(",",$ships);
		array_shift($ships);
		echo "<div class='team_container team_container_ships'  >";
		echo "<div class='team_info_container' >";
		echo "<div class = 'team_info'>{$data_t['gp']}</div>";
		?>
		<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>
				<?php
				if ($check == "false"){
					echo "<a  href='tw_loadout.php?T=$territory&show=$show&check_ship=$team_id'><i class='material-icons'>	check_box_outline_blank</i></a>";
				}
				else {
					echo "<a class='un_check' href='tw_loadout.php?T=$territory&show=$show&uncheck_ship=$team_id'><i class='material-icons'>check_box</i></a>";
				}
				?>
			</div>
		<?php
		echo "<div class = 'team_info_right'>{$data_t['Username']}</div>";
		echo "</div>";
	
		$current_team = array();
		echo "<div class='team_characters  team_characters_ships' style='margin-top:10px;'>";
		foreach ($ships as $ship_id){
			$init = substr($ship_id,0,7);
			if ($init == "CAPITAL"){
			foreach ($user_ships as $ship){
				if ($ship->defId == $ship_id){
					echo"<div class = 'capital_ship_team'>";
					display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"team");
					echo "</div>";
				}
			}
			}
		}
		$x = 0;
		foreach ($ships as $ship_id){
			$init = substr($ship_id,0,7);
			if ($init !== "CAPITAL"){
			foreach ($user_ships as $ship){
				if ($ship->defId == $ship_id){
					$x += 1;
					if ($x > 3){
						$y = "super_small";
					}
					else {
						$y = "small";
					}
					echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
					display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"small");
					echo "</div>";
				}
			}
			}
		}
		
	

		echo "</div>";	
		echo "</div>";
		}
	
	}
	echo "</div>";
}

function section_team_number($conn,$show,$username,$territory,$loadout_id){
	$fleet = false;
	if ($territory == 'F1' or $territory == 'F2'){
		$fleet = true;
	}
	$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()) {
		$teams = $data[$territory];
	}
	$teams = explode(",",$teams);
	$max_num = 0;
	if ($show == "all"){
		$max_num = count($teams);
		foreach ($teams as $team){
			if ($team == null){
				$max_num = $max_num - 1;
				
			}
		}
	}
	else {
		foreach ($teams as $team_id){
			if ($fleet == true){
			$sql_a = "SELECT * FROM ship_team WHERE Username = '$username' AND ship_team_id = '$team_id'";

			}
			else{
			$sql_a = "SELECT * FROM teams WHERE Username = '$username' AND team_id = '$team_id'";
			}
			$result_a = $conn->query($sql_a);
			if (mysqli_num_rows($result_a) == 0){
			}
			else{
				$max_num = $max_num + 1;
			}
		}
	}
	
	$checked = array();
	foreach ($teams as $team_id){
		if ($show == "all"){
			if($fleet == true){
			$sql_c = "SELECT * FROM ship_team WHERE checked = 'true' AND ship_team_id = '$team_id' ";

			}
			else {
			$sql_c = "SELECT * FROM teams WHERE checked = 'true' AND team_id = '$team_id' ";
			}
		}
		else{
			if($fleet == true){
			$sql_c = "SELECT * FROM ship_team WHERE checked = 'true' AND ship_team_id = '$team_id' AND Username = '$username'";
			}
			else{
			$sql_c = "SELECT * FROM teams WHERE checked = 'true' AND team_id = '$team_id' AND Username = '$username'";
			}
		}
		$result_c = $conn->query($sql_c);
		if (mysqli_num_rows($result_c) == 0){
		}
		else{
			array_push($checked,$team_id);
		}
	}
	$num = count($checked);
	
	if ($max_num !== 0){
	$return = $num . "/" . $max_num;
	return $return;
	}
	else{
		return "";
	}
}

function check_refresh_date($conn){
	$username = $_SESSION['Username'];
	$sql = "SELECT * FROM users WHERE username = '$username'";
	$result = $conn->query($sql);
	while($data = $result->fetch_assoc()){
		$date = $data['refresh_date'];
		$time = $data['refresh_time'];
	}

	$timestamp = strtotime($date . ' ' . $time);

	// getting current date 
	$cDate = strtotime(date('Y-m-d H:i:s'));

	// Getting the value of old date + 24 hours
	$oldDate = $timestamp + 86400; // 86400 seconds in 24 hrs

	if($oldDate > $cDate)
	{
	$refresh_time = false;
	}
	else
	{
	$refresh_time = true;
	}
	if (($date == Null) or ($refresh_time == true)){
		return "true";
		
	}
	else {
		return "false";
	}
}

function unchecked_territory($conn,$territory,$teams){
    ?>
    <script> 
	var display_territory = <?php echo json_encode($territory); ?>;
	</script>
    <?php
    if ($territory == 'F1' OR $territory == 'F2'){
		$sql_t = "SELECT * FROM ship_team WHERE checked = 'false'";
		$result_t = $conn->query($sql_t);
		if (mysqli_num_rows($result_t) == 0){
            ?>
	      <script>
	         
	          document.getElementById(display_territory).style.display = "none";
	      </script>
            <?php
        }
    }
    else {
        $sql_t = "SELECT * FROM teams WHERE checked = 'false'";
		$result_t = $conn->query($sql_t);
		if (mysqli_num_rows($result_t) == 0){
            ?>
	      <script>
	         
	          document.getElementById(display_territory).style.display = "none";
	      </script>
            <?php
        }
    }
    ?>

	<div class="unchecked_territory_container" id = "<?php echo $territory; ?>">
	<div class= "team_title_text" style = 'margin-bottom: 10px;'><?php
		echo $territory;
		echo "</div>";
		foreach ($teams as $team_id){
			if ($territory == 'F1' OR $territory == 'F2'){
					$sql_t = "SELECT * FROM ship_team WHERE ship_team_id = '$team_id' AND checked = 'false'";
					$result_t = $conn->query($sql_t);
				
					while ($data_t = $result_t->fetch_assoc()) {
					$check = ships_check_team_checked($conn,$team_id);
					$user_characters = getPlayerInfo_new($data_t['Username'],$conn);
					$user_ships = getPlayerInfo_ship($data_t['Username'],$conn);
					$ships = $data_t['ships'];
					$ships = explode(",",$ships);
					array_shift($ships);
					echo "<div class='team_container team_container_ships' onclick = 'show_team(this)' >";
					echo "<div class='team_info_container' >";
					echo "<div class = 'team_info'>{$data_t['gp']}</div>";
					?>
					<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>
						</div>
					<?php
					echo "<div class = 'team_info_right'>{$data_t['Username']}</div>";
					echo "</div>";
				
					$current_team = array();
					echo "<div class='team_characters  team_characters_ships' style='margin-top:10px;'>";
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init == "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								echo"<div class = 'capital_ship_team'>";
								display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"team");
								echo "</div>";
							}
						}
						}
					}
					$x = 0;
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init !== "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								$x += 1;
								if ($x > 3){
									$y = "super_small";
								}
								else {
									$y = "small";
								}
								echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
								display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"small");
								echo "</div>";
							}
						}
						}
					}
					
				

					echo "</div>";	
					echo "</div>";
					}
				?>
				<script>
			    if (window.profiles === "hidden"){
			          let profiles = document.getElementsByClassName("character_profile_full");
                   let containers = document.getElementsByClassName("team_container");
                   let gps = document.getElementsByClassName("team_info");
                   let name = document.getElementsByClassName("team_info_right");
				    for (var i = 0; i < profiles.length; i++) {
                       profiles[i].style.display = "none";
                   }
                   for (var i = 0; i < containers.length; i++) {
                     containers[i].style.height = "30px";
                     
                   }
                   for (var i = 0; i < gps.length; i++) {
                     gps[i].style.display = "none";
                   }
                   for (var i = 0; i < name.length; i++) {
                     name[i].style.border = "none";
                     name[i].style.float = "none";
                     name[i].style.textAlign = 'center';
                   }
				}
				
				</script>
				<?php
			}
			else{
			$sql_t = "SELECT * FROM teams WHERE team_id = '$team_id' AND checked = 'false' ";

			$dummy_array = array("Team");
			$result_t = $conn->query($sql_t);
		
			while ($data_t = $result_t->fetch_assoc()) {
					$check = check_team_checked($conn,$team_id);
					$user_info = getPlayerInfo_new($data_t['Username'],$conn);
					echo "<div class='team_container' onclick = 'show_team(this)' >";
					echo "<div class='team_info_container' style = 'overflow:hidden;'>";
					echo "<div class = 'team_info'>{$data_t['team_gp']}</div>";
					?>
					<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>

					</div>
					<?php
					echo "<div class = 'team_info_right'>{$data_t['Username']}</div>";
					echo "</div>";
					
			
					echo "<div class='team_characters' >";
				echo"<div class = 'char_1'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['LeaderID'] ),$dummy_array);
				echo"</div><div class = 'char_2'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character2ID'] ),$dummy_array);
				echo"</div><div class = 'char_3'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character3ID'] ),$dummy_array);
				echo"</div><div class = 'char_4'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character4ID'] ),$dummy_array);
				echo"</div><div class = 'char_5'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character5ID'] ),$dummy_array);
				echo "</div></div>";
					
					echo "</div>";
									?>
				<script>
				  if (window.profiles === "hidden"){
			          let profiles = document.getElementsByClassName("character_profile_full");
                   let containers = document.getElementsByClassName("team_container");
                   let gps = document.getElementsByClassName("team_info");
                   let name = document.getElementsByClassName("team_info_right");
				    for (var i = 0; i < profiles.length; i++) {
                       profiles[i].style.display = "none";
                   }
                   for (var i = 0; i < containers.length; i++) {
                     containers[i].style.height = "30px";
                     
                   }
                   for (var i = 0; i < gps.length; i++) {
                     gps[i].style.display = "none";
                   }
                   for (var i = 0; i < name.length; i++) {
                     name[i].style.border = "none";
                     name[i].style.float = "none";
                     name[i].style.textAlign = 'center';
                   }
				}
				</script>
				<?php
				}
				
			
			}
		}
		echo "</div>";	
}



function  unchecked_territory_user($conn,$username,$character_teams,$ship_teams,$loadout_id,$checked,$guild_data,$active){

     ?>
    <script> 
	var display_user = <?php echo json_encode($username); ?>;
	</script>


	<div class="unchecked_territory_container">
	<div class= "team_title_text" style = 'margin-bottom:15px;margin-top: 5px;'><?php
		echo $username;
		echo "</div>";
		foreach ($character_teams as $team_id){
			$sql_t = "SELECT * FROM teams WHERE Username = '$username' AND team_id = '$team_id'";
			$dummy_array = array("Team");
			$result_t = $conn->query($sql_t);
			while ($data_t = $result_t->fetch_assoc()) {
				$team_id = $data_t['team_id'];
				$checked = $data_t['checked'];
				$username = strtoupper($username);
				$username = trim($username);
				$user_info = $guild_data[$username]['Characters'];

				if ($active == true){
					if ($checked == "true"){
						echo "<div team_id = $team_id class='team_container checked' onclick = 'check_loadout(this.classList,$team_id)'>";
						echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i></div>';
					}
					else {
						echo "<div team_id = $team_id class='team_container' onclick = 'check_loadout(this.classList,$team_id)'>";
						echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i></div>';
					}
				}
				else{
					echo "<div class='team_container' >";
				}
							
				echo "<div class = 'team_background chars_tw tw'><img src='images/battletw.PNG'></div>";

				echo "<div class='team_info_container' style = 'overflow:hidden;'>";
				echo "<div class = 'team_info team_info_gp'>{$data_t['team_gp']}</div>";
				?>
				<div class = 'team_info team_info_gp' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>

				</div>
				<?php
				$territory = territory_team($conn,$data_t['team_id'],$loadout_id);
				echo "<div class = 'team_info team_info_territory'>$territory</div>";
				echo "</div>";
					
			
					echo "<div class='team_characters' >";
				echo"<div class = 'char_1'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['LeaderID'] ),$dummy_array);
				echo"</div><div class = 'char_2'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character2ID'] ),$dummy_array);
				echo"</div><div class = 'char_3'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character3ID'] ),$dummy_array);
				echo"</div><div class = 'char_4'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character4ID'] ),$dummy_array);
				echo"</div><div class = 'char_5'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character5ID'] ),$dummy_array);
				echo "</div></div>";
					
					echo "</div>";
					
				
				?>
				<script>
				
			    if (window.profiles === "hidden"){
			        console.log("here");
			          var profiles = document.getElementsByClassName("character_profile_full");
                   var containers = document.getElementsByClassName("team_container");
                   var gps = document.getElementsByClassName("team_info");
                   var name = document.getElementsByClassName("team_info_right");
				    for (var i = 0; i < profiles.length; i++) {
                       profiles[i].style.display = "none";
                   }
                   for (var i = 0; i < containers.length; i++) {
                     containers[i].style.height = "30px";
                     
                   }
                   for (var i = 0; i < gps.length; i++) {
                     gps[i].style.display = "none";
                   }
                   for (var i = 0; i < name.length; i++) {
                     name[i].style.border = "none";
                     name[i].style.float = "none";
                     name[i].style.textAlign = 'center';
                   }
				}
				
				</script>
				<?php
				
				}
				
			
			
		}
		foreach ($ship_teams as $team_id){
			$sql_t = "SELECT * FROM ship_team WHERE Username = '$username' AND ship_team_id = '$team_id'";
			$result_t = $conn->query($sql_t);
				while ($data_t = $result_t->fetch_assoc()) {
					$team_id = $data_t['ship_team_id'];
					$checked = $data_t['checked'];
					$username = $data_t['Username'];
					$username = strtoupper($username);
					$username = trim($username);
					$user_characters = $guild_data[$username]['Characters'];
					$user_ships = $guild_data[$username]['Ships'];

					$ships = $data_t['ships'];
					$ships = explode(",",$ships);
					array_shift($ships);

					if ($active == true){
						if ($checked == "true"){
							echo "<div team_id = $team_id class='team_container team_container_ships checked' onclick = 'check_loadout(this.classList,$team_id)'>";
							echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i></div>';
						}
						else {
							echo "<div team_id = $team_id class='team_container team_container_ships' onclick = 'check_loadout(this.classList,$team_id)'>";
							echo '<div class="checked_box"><i class="fa-solid fa-circle-check"></i></div>';
						}
					}
					else{
						echo "<div class='team_container team_container_ships' >";
					}

					echo "<div class = 'team_background ships tw'><img src='images/Capture.png'></div>";

					echo "<div class='team_info_container' >";
					echo "<div class = 'team_info team_info_gp'>{$data_t['gp']}</div>";
					?>
					<div class = 'team_info team_info_gp' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>
						</div>
					<?php
					$territory = territory_team_ship($conn,$data_t['ship_team_id'],$loadout_id);
					echo "<div class = 'team_info team_info_territory'>$territory</div>";
					echo "</div>";
				
					$current_team = array();
					echo "<div class='team_characters  team_characters_ships' style='margin-top:10px;'>";
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init == "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								echo"<div class = 'capital_ship_team'>";
								display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"team");
								echo "</div>";
							}
						}
						}
					}
					$x = 0;
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init !== "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								$x += 1;
								if ($x > 3){
									$y = "super_small";
								}
								else {
									$y = "small";
								}
								echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
								display_ship($conn,$ship,$current_team,$user_characters,$data_t['Username'],"small");
								echo "</div>";
							}
						}
						}
					}
					
				

					echo "</div>";	
					echo "</div>";
					
					?>
				<script>
			    if (window.profiles === "hidden"){
			          let profiles = document.getElementsByClassName("character_profile_full");
                   let containers = document.getElementsByClassName("team_container");
                   let gps = document.getElementsByClassName("team_info");
                   let name = document.getElementsByClassName("team_info_right");
				    for (var i = 0; i < profiles.length; i++) {
                       profiles[i].style.display = "none";
                   }
                   for (var i = 0; i < containers.length; i++) {
                     containers[i].style.height = "30px";
                     
                   }
                   for (var i = 0; i < gps.length; i++) {
                     gps[i].style.display = "none";
                   }
                   for (var i = 0; i < name.length; i++) {
                     name[i].style.border = "none";
                     name[i].style.float = "none";
                     name[i].style.textAlign = 'center';
                   }
				}
				
				</script>
				<?php
					
					}
		}



		echo "</div>";	

}



function territory_team($conn,$team_id,$loadout_id){

	$territories = array("T1","T2","T3","T4","B1","B2","B3","B4");

	$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";

	$result = $conn->query($sql);

	while ($data = $result->fetch_assoc()){
		foreach ($territories as $territory){
			$teams = $data[$territory];
			$teams = explode(",",$teams);
			foreach ($teams as $team){
				if ($team == $team_id){
					return $territory;
				}
			}
		}
}

}

function territory_team_ship($conn,$team_id,$loadout_id){
	$territories = array("F1","F2");

	$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";

	$result = $conn->query($sql);

	while ($data = $result->fetch_assoc()){
		foreach ($territories as $territory){
			$teams = $data[$territory];
			$teams = explode(",",$teams);
			foreach ($teams as $team){
				if ($team == $team_id){
					return $territory;
				}
			}
		}
}
}


function eln_teams_territories($loadout_id,$conn,$guild_info){
	$territories = array("T1","T2","T3","T4","B1","B2","B3","B4");
	
	$territories_s = array("F1","F2");
	$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()){
		foreach($territories as $territory){
			$teams = $data[$territory];
			$teams = explode(",",$teams);
			$number_of_teams = count($teams);
			foreach ($teams as $part){
				if ($part == null){
					$number_of_teams = $number_of_teams - 1;
				}
			}
			$j_territory = $territory;
			?>
			<script>
				j_territory = "<?php echo $j_territory;?>";
				var j_number = <?php echo $number_of_teams; ?>;
				(window.number)[j_territory] = j_number;
			</script>
			<?php
			foreach ($teams as $team_id){
				$sql_t = "SELECT * FROM teams WHERE team_id = '$team_id'";
				$dummy_array = array("Team");
				$result_t = $conn->query($sql_t);
				while ($data_t = $result_t->fetch_assoc()) {
						$team_id = $data_t['team_id'];
						$username = $data_t['Username'];
						$username = strtoupper($username);
						$username = trim($username);
						$user_info = $guild_info[$username]['Characters'];
						echo "<div id = 'outer_in_loadout_left_$team_id'>";
						echo "<div style = 'display:none;' class='team_container $territory character_container' id = 'in_loadout_left_$team_id'  onclick = 'remove(this.id)'>";
						
						echo "<div class = 'team_background chars_tw tw'><img src='images/battletw.PNG'></div>";

						echo "<div class = 'factions_contained' style = 'display:none;'>";
						echo $data_t['factions_contained'];
						echo "</div>";
						echo "<div class = 'username' style = 'display:none;'>";
						if (isset($_SESSION['guest'])){
							echo "Guest_" . $data_t['guest_id'];
						}
						else {
							echo $username;
						}
						echo "</div>";
						echo "<div class='team_info_container' style = 'overflow:hidden;'>";
						echo "<div class = 'team_info'>{$data_t['team_gp']}</div>";
						?>
						<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>

						</div>
						<?php
						if (isset($_SESSION['guest'])){
							echo "<div class = 'team_info'>Guest_{$data_t['guest_id']}</div>";

						}
						else {
						echo "<div class = 'team_info'>{$data_t['Username']}</div>";
						}
						echo "</div>";
						
				
						echo "<div class='team_characters' >";
							echo"<div class = 'char_1'>";
							display_character_new($conn,get_char_info ($conn, $user_info, $data_t['LeaderID'] ),$dummy_array);
							echo"</div><div class = 'char_2'>";
							display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character2ID'] ),$dummy_array);
							echo"</div><div class = 'char_3'>";
							display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character3ID'] ),$dummy_array);
							echo"</div><div class = 'char_4'>";
							display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character4ID'] ),$dummy_array);
							echo"</div><div class = 'char_5'>";
							display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character5ID'] ),$dummy_array);
							echo "</div></div>";
						
						echo "</div></div>";
					}
			}
		}
		
		foreach($territories_s as $territory){
			$teams = $data[$territory];
			$teams = explode(",",$teams);
			$number_of_teams = count($teams);
			foreach ($teams as $part){
				if ($part == null){
					$number_of_teams = $number_of_teams - 1;
				}
			}
			$j_territory = $territory;
			?>
			<script>
				j_territory = "<?php echo $j_territory;?>";
				var j_number = <?php echo $number_of_teams; ?>;
				(window.number)[j_territory] = j_number;
			</script>
			<?php
			foreach ($teams as $team_id){
				$sql_s = "SELECT * FROM ship_team WHERE ship_team_id = '$team_id'";
				$result_s = $conn->query($sql_s);
				while ($data_s = $result_s->fetch_assoc()) {
					$username = $data_s['Username'];
					$username = strtoupper($username);
					$username = trim($username);
					$user_characters = $guild_info[$username]['Characters'];
					$user_ships = $guild_info[$username]['Ships'];
					$ships = $data_s['ships'];
					$ships = explode(",",$ships);
					array_shift($ships);
					echo "<div id = 'outer_in_loadout_left_$team_id'>";
					echo "<div class='team_container $territory team_container_ships' id = 'in_loadout_left_$team_id' onclick = 'remove(this.id)''>";
					
					echo "<div class = 'team_background ships tw'><img src='images/Capture.png'></div>";
					
					echo "<div class = 'username' style = 'display:none;'>";
					if (isset($_SESSION['guest'])){
						echo "Guest_" . $data_t['guest_id'];
					}
					else {
						echo $username;
					}
					echo "</div>";
					echo "<div class='team_info_container' >";
					echo "<div class = 'team_info'>{$data_s['gp']}</div>";
					?>
					<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>

					</div>
					<?php
					if (isset($_SESSION['guest'])){
						echo "<div class = 'team_info'>Guest_{$data_s['guest_id']}</div>";

					}
					else {
					echo "<div class = 'team_info'>{$data_s['Username']}</div>";
					}
					echo "</div>";
				
					$current_team = array();
					echo "<div class='team_characters team_characters_ships' style='margin-top:10px;'>";
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init == "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								echo"<div class = 'capital_ship_team'>";
								display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"team");
								echo "</div>";
							}
						}
						}
					}
					$x = 0;
					foreach ($ships as $ship_id){
						$init = substr($ship_id,0,7);
						if ($init !== "CAPITAL"){
						foreach ($user_ships as $ship){
							if ($ship->defId == $ship_id){
								$x += 1;
								if ($x > 3){
									$y = "super_small";
								}
								else {
									$y = "small";
								}
								echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
								display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"small");
								echo "</div>";

							}
						}
						}
					}
				    echo "</div>";
					echo "</div>";
                    echo "</div>";
				}
				
			}
	        

		}
	}
}

function eln_team_options($loadout_id,$conn,$userStr,$guild_info){
	$sql = "SELECT * FROM loadouts WHERE loadoutID = '$loadout_id'";
	$result = $conn->query($sql);
	while ($data = $result->fetch_assoc()){
		$T1 = $data['T1']; $T1 = explode(",",$T1);
		$T2 = $data['T2']; $T2 = explode(",",$T2);
		$T3 = $data['T3']; $T3 = explode(",",$T3);
		$T4 = $data['T4']; $T4 = explode(",",$T4);
		$B1 = $data['B1']; $B1 = explode(",",$B1);
		$B2 = $data['B2']; $B2 = explode(",",$B2);
		$B3 = $data['B3']; $B3 = explode(",",$B3);
		$B4 = $data['B4']; $B4 = explode(",",$B4);
		$F1 = $data['F1']; $F1 = explode(",",$F1);
		$F2 = $data['F2']; $F2 = explode(",",$F2);

	}
	$character_teams = array_merge($T1,$T2,$T3,$T4,$B1,$B2,$B3,$B4);
	$ship_teams = array_merge($F1,$F2);

	// $userStr = str_replace("'","",$userStr);
	// $users_in_guild = explode(",",$userStr);

	// foreach ($users_in_guild as $temp_username){
	// 	foreach ($exclude as $exclude_username){
	// 		if (ltrim($temp_username) == ltrim($exclude_username)){
	// 			$pos = array_search($temp_username,$users_in_guild);
	// 			unset($users_in_guild[$pos]);
	// 		}
	// 	}

	// }

	// foreach($users_in_guild as $u_name){
	// 	$position = array_search($u_name,$users_in_guild);
	// 	$users_in_guild[$position] = ltrim($u_name);
	// }

	// $userStr = implode("', '",$users_in_guild);

	
	if (isset($_SESSION['guest'])){
		$sql = "SELECT * FROM teams WHERE guest_id IN ('$userStr') AND type = 'tw' ORDER BY team_gp DESC" ;
	}
	else {
		$sql = "SELECT * FROM teams WHERE Username IN ('$userStr') AND type = 'tw' ORDER BY team_gp DESC" ;
	}
	
	$result = $conn->query($sql);

	echo "<div id = 'eln_character_option_container'>";
	if (mysqli_num_rows($result) == 0){
		echo "<div class = 'no_team'>Your guild currently has no teams.</br> Try creating a team in the Team Builder page.</div>";
	}

	while ($data_t = $result->fetch_assoc()) {
		$in_team = "false";
	
		foreach ($character_teams as $team_id){
			$team_id_strip = trim($team_id);
			if ($team_id_strip == $data_t['team_id']){
				$in_team = "true";
			}
		
		}
		
		if ($in_team == "false"){
			$dummy_array = array("Team");
			$team_id = $data_t['team_id'];
			$username = $data_t['Username'];
			$username = strtoupper($username);
			$username = trim($username);
			$user_info = $guild_info[$username]['Characters'];
			echo "<div id = 'outer_team_id_$team_id'>";
			echo "<div class='team_container' id = 'team_id_$team_id'  onclick = 'add_team_loadout(this.id)'>";
			
			echo "<div class = 'team_background chars_tw tw'><img src='images/battletw.PNG'></div>";

			echo "<div class = 'factions_contained' style = 'display:none;'>";
			echo $data_t['factions_contained'];
			echo "</div>";
			echo "<div class = 'username' style = 'display:none;'>";
			if (isset($_SESSION['guest'])){
				echo "Guest_" . $data_t['guest_id'];
			}
			else {
				echo $username;
			}
			echo "</div>";
			echo "<div class='team_info_container' style = 'overflow:hidden;'>";
			echo "<div class = 'team_info'>{$data_t['team_gp']}</div>";
			?>
			<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>

			</div>
			<?php

			if (isset($_SESSION['guest'])){
				echo "<div class = 'team_info'>Guest_{$data_t['guest_id']}</div>";

			}
			else {
			echo "<div class = 'team_info'>{$data_t['Username']}</div>";
			}
			echo "</div>";
			
	
			echo "<div class='team_characters' >";
				echo"<div class = 'char_1'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['LeaderID'] ),$dummy_array);
				echo"</div><div class = 'char_2'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character2ID'] ),$dummy_array);
				echo"</div><div class = 'char_3'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character3ID'] ),$dummy_array);
				echo"</div><div class = 'char_4'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character4ID'] ),$dummy_array);
				echo"</div><div class = 'char_5'>";
				display_character_new($conn,get_char_info ($conn, $user_info, $data_t['Character5ID'] ),$dummy_array);
				echo "</div></div>";
			
			echo "</div></div>";
		}
	
		
		
		
		
	}
	echo "</div>";

	if (isset($_SESSION['guest'])){
	$sql = "SELECT * FROM ship_team WHERE guest_id IN ('$userStr') AND type = 'tw' ORDER BY gp DESC" ;

}
	else {
	$sql = "SELECT * FROM ship_team WHERE Username IN ('$userStr') AND type = 'tw' ORDER BY gp DESC" ;
	}
	$result = $conn->query($sql);
	
	echo "<div id = 'eln_ship_option_container'>";

	while ($data = $result->fetch_assoc()) {
		$in_team = "false";

		foreach ($ship_teams as $team_id){
			$team_id_strip = trim($team_id);
			if ($team_id_strip == $data['ship_team_id']){
				$in_team = "true";
			}
		
		}
		
		if ($in_team == "false"){
			$team_id = $data['ship_team_id'];
			$username = $data['Username'];
			$username = strtoupper($username);
			$username = trim($username);
			$user_characters = $guild_info[$username]['Characters'];
			$user_ships = $guild_info[$username]['Ships'];
			$ships = $data['ships'];
			$ships = explode(",",$ships);
			array_shift($ships);
			echo "<div id = 'outer_team_id_$team_id'>";
			echo "<div class='team_container team_container_ships' id = 'team_id_$team_id' onclick = 'add_team_loadout(this.id)' >";
			
			echo "<div class = 'team_background ships tw'><img src='images/Capture.png'></div>";

			echo "<div class = 'username' style = 'display:none;'>";
			if (isset($_SESSION['guest'])){
				echo "Guest_" . $data['guest_id'];
			}
			else {
				echo $username;
			}
			echo "</div>";
			echo "<div class='team_info_container' >";
			echo "<div class = 'team_info'>{$data['gp']}</div>";
			?>
			<div class = 'team_info' style = 'transform:translateY(3px);border-left:none;margin-left: 10px;'>

			</div>
			<?php
			if (isset($_SESSION['guest'])){
				echo "<div class = 'team_info'>Guest_{$data['guest_id']}</div>";

			}
			else {
			echo "<div class = 'team_info'>{$data['Username']}</div>";
			}
			echo "</div>";
		
			$current_team = array();
			echo "<div class='team_characters team_characters_ships' style='margin-top:10px;'>";
			foreach ($ships as $ship_id){
				$init = substr($ship_id,0,7);
				if ($init == "CAPITAL"){
				foreach ($user_ships as $ship){
					if ($ship->defId == $ship_id){
						echo"<div class = 'capital_ship_team'>";
						display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"team");
						echo "</div>";
					}
				}
				}
			}
			$x = 0;
			foreach ($ships as $ship_id){
				$init = substr($ship_id,0,7);
				if ($init !== "CAPITAL"){
				foreach ($user_ships as $ship){
					if ($ship->defId == $ship_id){
						$x += 1;
						if ($x > 3){
							$y = "super_small";
						}
						else {
							$y = "small";
						}
						echo"<div class = 'ship_team_small ship_team_$y ship_team_$x'>";
						display_ship($conn,$ship,$current_team,$user_characters,$_SESSION['Username'],"small");
						echo "</div>";

					}
				}
				}
			}
		
		
		echo "</div>";
		echo "</div>";
		echo "</div>";
		}	
	
	}

	
}


   
