<?php

class Model_StatSummary {
	
	private $fullStats;
	private $summaryStats = array();
	private $statKeys = array(
		"versatility",
		"multistrikeRating",
		"masteryRating",
		"speedRating",
		"hasteRating",
		"leechRating",
		"critRating",
		"bonusArmor"
	);

	public function __construct($stats) {
		$this->fullStats = $stats;
		$this->createSummary();
	}

	private function createSummary() {
		$maxValue = 0;
		$summaryStats = array();
		if(count($this->fullStats) > 0) {
			foreach($this->fullStats as $key => $value) {
				if(in_array($key, $this->statKeys)) {
					if($value > $maxValue) {
						$maxValue = $value;
					}
					$summaryStats[$key] = $value;
				}
			}

			foreach($summaryStats as $key => $value) {
				$summaryStats[$key."Normalized"] = round(floatval($value) / floatval($maxValue),2);
			}
			$this->summaryStats = $summaryStats;
		}
	}

	public function dto() {
		return $this->summaryStats;
	}


}