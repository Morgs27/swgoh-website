<?php
class character_new{
	public $id;
	public $defId;
	public $nameKey;
	public $rarity;
	public $level;
	public $gear;
	public $gp;
	public $relic;
	public $zetas;
	public $omicrons;
	public $stats_base;
	public $stats_mods;
	public $mods;
	public $speed;
	public $health;
	public $protection;
	public $tenacity;
	public $potency;
	public $special_damage;
	public $physical_damage;
	public $ultimate;

	function set_values_character($id,$defId,$nameKey,$rarity,$level,
	$gear,$gp,$relic,$zetas,$omicrons,$stats_base,$stats_mods,
	$mods,$speed,$health,$protection,$tenacity,$potency,$special_damage,$physical_damage,$ultimate){
		$this->id = $id;
		$this->defId = $defId;
		$this->nameKey= $nameKey;
		$this->rarity = $rarity;
		$this->level = $level;
		$this->gear = $gear;
		$this->gp = $gp;
		$this->relic = $relic;
		$this->zetas = $zetas;
		$this->omicrons = $omicrons;
		$this->stats_base = $stats_base;
		$this->stats_mods = $stats_mods;
		$this->mods = $mods;
		$this->speed = $speed;
		$this->health = $health;
		$this->protection = $protection;
		$this->tenacity = $tenacity;
		$this->potency = $potency;
		$this->special_damage = $special_damage;
		$this->physical_damage = $physical_damage;
		$this->ultimate = $ultimate;
	}
	
}

class ship_new{
	public $id;
	public $defId;
	public $rarity;
	public $level;
	public $gp;
	public $crew;


	
	function set_values_ship($id,$defId,$rarity,$level,$gp,$crew){
		$this->id = $id;
		$this->defId = $defId;
		$this->rarity = $rarity;
		$this->level = $level;
		$this->gp = $gp;
		$this->crew = $crew;


		
	}
	
}


class stats{
	public $health;
	public $speed;
	public $physical_damage;
	public $special_damage;
	public $armor;
	public $armor_penetration;
	public $dodge_chance;
	public $physical_critical_chance;
	public $special_critical_chance;
	public $critical_damage;
	public $potentcy;
	public $tenacity;
	public $health_steal;
	public $protection;
	public $resistance;

	
	function set_stats($health,$speed,$physical_damage,$special_damage,$armor,$armor_penetration,$dodge_chance,$physical_critical_chance,$special_critical_chance,$critical_damage,$potentcy,$tenacity,$health_steal,$protection,$resistance){
		$this->health = $health;
		$this->speed = $speed;
		$this->physical_damage= $physical_damage;
		$this->special_damage = $special_damage;
		$this->armor = $armor;
		$this->armor_penetration = $armor_penetration;
		$this->dodge_chance = $dodge_chance;
		$this->physical_critical_chance = $physical_critical_chance;
		$this->special_critical_chance = $special_critical_chance;
		$this->critical_damage = $critical_damage;
		$this->potentcy = $potentcy;
		$this->tenacity = $tenacity;
		$this->health_steal = $health_steal;
		$this->protection = $protection;
		$this->resistance = $resistance;
	}
	
}