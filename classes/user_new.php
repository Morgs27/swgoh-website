<?php
class user_new{
	public $allyCode;
	public $id;
	public $name;
	public $level;
	public $guildRefId;
	public $guildName;
	public $gp;
	public $charactergp;
	public $shipgp;
	public $characters;
	
	function set_values_user($allyCode,$id,$name,$level,$guildRefId,$guildName,$gp,$charactergp,$shipgp,$characters){
		$this->allyCode = $allyCode;
		$this->id = $id;
		$this->name= $name;
		$this->level = $level;
		$this->guildRefId = $guildRefId;
		$this->guildName = $guildName;
		$this->gp = $gp;
		$this->charactergp = $charactergp;
		$this->shipgp = $shipgp;
		$this->characters = $characters;
		
	}
	
}