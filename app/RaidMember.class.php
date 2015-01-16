<?php

class RaidMember {
	
	protected $member;

	public function __construct($member) {
		$this->member = $member;
	}

	public function getJSON() {
		$memberData = array(
				"name" => $this->member["name"],
				"level" => $this->member["level"],
				"itemLevel" => $this->getEquipedItemLevel(),
				"altItemLevel" => $this->getItemLevel(),
				"class" => $this->getClassName(),
				"spec" => $this->member["talents"][0]["spec"]["name"],
				"specRole" =>  $this->member["talents"][0]["spec"]["role"],
				"altSpec" => $this->member["talents"][1]["spec"]["name"],
				"altSpecRole" =>  $this->member["talents"][1]["spec"]["role"],
				"armorType" => $this->getArmorType(),
				"armorToken" => $this->getArmorToken(),
				"thumbnail" => $this->member["thumbnail"]
			);
		return $memberData;
	}

	public function getClassname() {
		return $this->member["class"]["name"];
	}

	public function getEquipedItemLevel() {
		return $this->member["items"]["averageItemLevelEquipped"];
	}


	public function getItemLevel() {
		return $this->member["items"]["averageItemLevel"];
	}

	private function getArmorType() {
		$armors = array(
			"Warrior" => "plate",
			"Paladin" => "plate",
			"Death Knight" => "plate",
			"Priest" => "cloth",
			"Mage" => "cloth",
			"Warlock" => "cloth",
			"Druid" => "leather",
			"Rogue" => "leather",
			"Monk" => "leather",
			"Hunter" => "mail",
			"Shaman" => "mail",
		);
		return $armors[$this->getClassName()];
	}

	private function getArmorToken() {
		$armors = array(
			"Warrior" => "Protector",
			"Paladin" => "Conqueror",
			"Death Knight" => "Vanquisher",
			"Priest" => "Conqueror",
			"Mage" => "Vanquisher",
			"Warlock" => "Conqueror",
			"Druid" => "Vanquisher",
			"Rogue" => "Vanquisher",
			"Monk" => "Protector",
			"Hunter" => "Protector",
			"Shaman" => "Protector",
		);
		return $armors[$this->getClassName()];
	}
}