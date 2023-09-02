function add_char_hovers(){
	char_profiles = document.querySelectorAll(".character_profile_full");

	// console.log(window.location.href);
	
	char_profiles.forEach(char => {
	
	char.addEventListener("mouseover",() => {
		timeout = setTimeout(() => {
			popup = char.querySelector(".character_stats_popup");
			profile = char.querySelector(".character_profile_profile");
			namekey = char.querySelector(".character_name");
			// namekey.style.opacity = "0";
			profile.style.opacity = "0";
            profile.style.zIndex = "-1";
			popup.style.opacity = "1";
			clearTimeout(out_timeout);
		},1000)
	})

	char.addEventListener("mouseout",() => {
		out_timeout =  setTimeout(() => {
			popup = char.querySelector(".character_stats_popup");
			profile = char.querySelector(".character_profile_profile");
			profile.style.opacity = "";
            profile.style.zIndex = "";
			popup.style.opacity = "";
			namekey = char.querySelector(".character_name");
			namekey.style.opacity = "";
		},1000);
		clearTimeout(timeout);
	})

	if (window.location.href.indexOf("team_builder") > -1){
		char.addEventListener("click",() => {
			clear_hovers();
		})
	}

})
}

function clear_hovers(){
	char_profiles = document.querySelectorAll(".character_profile_full");
	char_profiles.forEach(char => {
		out_timeout =  setTimeout(() => {
			popup = char.querySelector(".character_stats_popup");
			profile = char.querySelector(".character_profile_profile");
			profile.style.opacity = "";
            profile.style.zIndex = "";
			popup.style.opacity = "";
			namekey = char.querySelector(".character_name");
			namekey.style.opacity = "";
		},1000);
		clearTimeout(timeout);
	})
}

add_char_hovers();

document.addEventListener("click", (event) => {
	if (event.target.closest(".team_container")){
		if (window.location.href.indexOf("edit_ga_loadout") > -1){
			clear_hovers();
		}
		else if (window.location.href.indexOf("edit_loadout_new") > -1){
			clear_hovers();
		}
		
	}
})