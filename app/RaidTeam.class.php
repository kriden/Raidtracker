<?php

class RaidTeam {
	
	private $name;
	private $armory;
	private $members;
	private $coreRank;
	private $trialRank;
	private $raidMemberData;

	public function __construct($armory, $name, $members) {
		$this->members = $members;
		$this->name = $name;
		$this->armory = $armory;
	}

	public function setCoreRank($rank) {
		$this->coreRank = $rank;
	}

	public function getName() { 
		return $this->name;
	}

	public function setTrialRank($rank) {
		$this->trialRank = $rank;
	}

	public function getMembers() {
		if(!$this->raidMemberData) {
			foreach($this->members as $member) {
				$this->raidMemberData[] = $this->armory->getCharacter($member);
			}
		}
		return $this->raidMemberData;
	}

	public function getMembersByAvgItemLevel() {
		$sorted = $this->getMembers();
		usort($sorted, array($this, 'sortByILevel'));
		return $sorted;
	}

	private function sortByILevel($a, $b) {
		$lvlA = $a->getGear()["averageItemLevel"];
		$lvlB = $b->getGear()["averageItemLevel"];
		if($lvlA > $lvlB) {
			return -1;
		} else if($lvlB > $lvlA) {
			return 1;
		}
		return 0;
	}

	public function getAverageItemLevel() {
		$members = $this->getMembers();
		$itemleveltotal = 0;
		$validmembers = 0;
		foreach($members as $member) {
			$itemlevel = intval($member->getGear()["averageItemLevel"]);
			if($itemlevel > 0) {
				$validmembers++;
				$itemleveltotal += $itemlevel;
			}
		}
		return floor($itemleveltotal/$validmembers);
	}

	public function getHighestItemLevel() {
		$sorted = $this->getMembersByAvgItemLevel();
		return $sorted[0]->getGear()["averageItemLevel"];
	}

	public function getLowestItemLevel() {
		$sorted = $this->getMembersByAvgItemLevel();
		$last = count($sorted) -1;

		while($last > 0) {
			$ilvl = intval($sorted[$last]->getGear()["averageItemLevel"]);
			if($ilvl > 0) {
				return $ilvl;
			} 
			$last--;
		}
		return 0;
	}
}