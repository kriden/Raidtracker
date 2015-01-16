<?PHP

require_once 'Model_StatSummary.class.php';

global $lifeCycle;
class Model_Profile extends RedBeanPHP\SimpleModel {
	
	protected $items = null;
	protected $talents = null;
	protected $stats = null;

	public function open() {
		$this->items = json_decode($this->bean->items, TRUE);
		$this->talents = json_decode($this->bean->talents, TRUE);
		$this->stats = json_decode($this->bean->stats, TRUE);
		$this->statsObj = new Model_StatSummary($this->stats);
	}

	public function json() {
		return json_encode($this->dto());
	}

	public function dto() {
		$data = $this->bean->export();
		// json_decode values starting with {
		foreach($data as $key => $values) {
			if(!is_object($values)) {
				$firstChar = substr($values,0,1);
				if($firstChar === "{" || $firstChar === "[") {
					$data[$key] = json_decode($values, TRUE);
				}
			}
		}
		return $this->buildDTO($data);
	}

	public function buildDTO($data) {
			$json = array(
					"name" => $data["name"],
					"level" => intval($data["level"]),
					"itemLevel" => $this->getEquippedItemLevel(),
					"altItemLevel" => $this->getItemLevel(),
					"class" => $this->getClassName($data),
					"spec" => $this->talents[0]["spec"]["name"],
					"specRole" =>  $this->talents[0]["spec"]["role"],
					"specActive" => (@$this->talents[0]["selected"] === true),
					"altSpec" => $this->talents[1]["spec"]["name"],
					"altSpecRole" =>  $this->talents[1]["spec"]["role"],
					"altSpecActive" => (@$this->talents[1]["selected"] === true),
					"armorType" => $this->getArmorType($data),
					"armorToken" => $this->getArmorToken($data),
					"thumbnail" => $data["thumbnail"],
					"stats" => $this->statsObj->dto()
				);
			return $json;
		}

		public function getItems() {
			return $this->items;
		}

		public function getStats() {
			return $this->stats;
		}

		public function getClassname($data) {
			if(intval($data['class']) > 0) {
				return $this->getClassFromInt($data["class"]);
			} else if(isset($data["class"]["name"])) {
				return $data["class"]["name"];
			} else {
				return "";
			}
		}

		private function getClassFromInt($integer) {
	   		$classes = array("Warrior", "Paladin","Hunter","Rogue","Priest","Death Knight","Shaman","Mage","Warlock","Monk","Druid");
	   		return $classes[$integer-1];
		}

		public function getEquippedItemLevel() {
			return $this->items["averageItemLevelEquipped"];
		}


		public function getItemLevel() {
			return $this->items["averageItemLevel"];
		}

		private function getArmorType($data) {
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
			return @$armors[$this->getClassName($data)];
		}

		private function getArmorToken($data) {
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
			return @$armors[$this->getClassName($data)];
		}

}