



// function show_unique(){
// 	let button = document.getElementsByClassName("show_unique_characters")[0];
// 	let container = document.getElementsByClassName("profile_containers_full")[0];
// 	let filter = document.getElementById("search_character_input_tb").value.toUpperCase();
// 	let in_team = container.getElementsByClassName("true");
// 	for (i = 0; i < in_team.length; i++) {
// 		let name = in_team[i].getElementsByClassName("character_name")[0].innerHTML;
// 		if (button.innerHTML === "Show All"){
// 			if (name.toUpperCase().indexOf(filter) > -1){
// 				in_team[i].style.display = "flex";
// 			}
// 			else {
// 				in_team[i].style.display = "none";
// 			}
// 		}
// 		else {
// 			in_team[i].style.display = "none";

// 		}
// 	}
// 	if (button.innerHTML === "Show All"){
// 		button.innerHTML = "Show Unique";
// 	}
// 	else {
// 		button.innerHTML = "Show All";
// 	}

// 	}


	function search_character(){
		// let button = document.getElementsByClassName("show_unique_characters")[0];
		let filter = document.getElementById("search_character_input_tb").value.toUpperCase();
		let container = document.getElementsByClassName("profile_containers_full")[0];
		let profiles = container.getElementsByClassName("character_profile_full");
		// for (i = 0; i < profiles.length; i++) {
		// let profile = profiles[i];
		// let name = profile.getElementsByClassName("character_name")[0].innerHTML;
		// if (name.toUpperCase().indexOf(filter) > -1){
		// 	if (button.innerHTML === "Show All"){
		// 		if (profile.classList.contains('true')){
		// 			profile.style.display = "none";
		// 		}
		// 		else {
		// 			profile.style.display = "flex";
		// 		}
		// 	}
		// 	else {
		// 	profile.style.display = "flex";
		// 	}
		// }	
		// else {
		// 	profile.style.display = "none";
		// }
		for (i = 0; i < profiles.length; i++) {
			let profile = profiles[i];
			let name = profile.getElementsByClassName("character_name")[0].innerHTML;
			let categories = profile.querySelector(".factions_contained").innerHTML;
			if ((name.toUpperCase().indexOf(filter) > -1) || (categories.toUpperCase().indexOf(filter) > -1)){
				profile.style.display = "";
			}	
			else {
				profile.style.display = "none";
			}
		}
	}

	// function show_unique_ship(){
	// let button = document.getElementsByClassName("show_unique_characters")[0];
	// let container = document.getElementsByClassName("profile_containers_full_ship")[0];
	// let filter = document.getElementById("search_ship_input_tb").value.toUpperCase();
	// let in_team = container.getElementsByClassName("true");
	// for (i = 0; i < in_team.length; i++) {
	// 	let name = in_team[i].getElementsByClassName("ship_name_tb")[0].id;
	// 	if (button.innerHTML === "Show All"){
	// 		if (name.toUpperCase().indexOf(filter) > -1){
	// 			in_team[i].style.display = "flex";
	// 		}
	// 		else {
	// 			in_team[i].style.display = "none";
	// 		}
	// 	}
	// 	else {
	// 		in_team[i].style.display = "none";

	// 	}
	// }
	// if (button.innerHTML === "Show All"){
	// 	button.innerHTML = "Show Unique";
	// }
	// else {
	// 	button.innerHTML = "Show All";
	// }

	// }


	function search_ship(){
		// let button = document.getElementsByClassName("show_unique_characters")[0];
		let filter = document.getElementById("search_ship_input_tb").value.toUpperCase();
		let container = document.getElementsByClassName("profile_containers_full_ship")[0];
		let profiles = container.getElementsByClassName("ship_profile");
		// for (i = 0; i < profiles.length; i++) {
		// let profile = profiles[i];
		// let name = profile.getElementsByClassName("ship_name_tb")[0].id;
		// if (name.toUpperCase().indexOf(filter) > -1){
		// 	if (button.innerHTML === "Show All"){
		// 		if (profile.classList.contains('true')){
		// 			profile.style.display = "none";
		// 		}
		// 		else {
		// 			profile.style.display = "flex";
		// 		}
		// 	}
		// 	else {
		// 	profile.style.display = "flex";
		// 	}
		// }	
		// else {
		// 	profile.style.display = "none";
		// }
		// }
		for (i = 0; i < profiles.length; i++) {
			let profile = profiles[i];
			let name = profile.getElementsByClassName("ship_name_tb")[0].id;
			if (name.toUpperCase().indexOf(filter) > -1){
				
				profile.style.display = "flex";
				
			}	
			else {
				profile.style.display = "none";
			}
		}
	}