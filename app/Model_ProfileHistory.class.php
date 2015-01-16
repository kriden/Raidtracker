<?PHP

class Model_ProfileHistory {
	
	public function __construct($profileList) {
		$this->profiles = $profileList;
	}

	public function dto() {
		$timeline = array();
		$prevProfile = null;
		foreach($this->profiles as $profile) {
			if(!$prevProfile) $prevProfile = $profile;

			$historyDTO = array(
				"timestamp" => floatval($profile->lastModified),
				"averageItemLevel" => $profile->getItemLevel(),
				"averageItemLevelEquipped" => $profile->getEquippedItemLevel(),
				//"averageItemLevelDelta" => $profile->getItemLevel() - $prevProfile->getItemLevel(),
				//"averageItemLevelEquippedDelta" => $profile->getEquippedItemLevel() - $prevProfile->getEquippedItemLevel(),
				"itemsDiff" => $this->arrayRecursiveDiff($profile->getItems(), $prevProfile->getItems()),
				"formattedDate" => date("d-M-Y", floatval($profile->lastModified)/1000)
			);
			$timeline[] = $historyDTO;
			$prevProfile = $profile;
		}

		// group history dto's by date

		$grouped = $this->groupByDate($timeline);
		$mergedTimeline = $this->mergeDatapoints($grouped);
		return $mergedTimeline;
	}

	private function groupByDate(array $timeline) {
		$timelineByDate = array();
		foreach($timeline as $datapoint) {
			$date = $datapoint["formattedDate"];

			if(!isset($timelineByDate[$date])) {
				$timelineByDate[$date] = array();
			}
			$timelineByDate[$date][] = $datapoint;
		}
		return $timelineByDate;
	}

	private function mergeDatapoints(array $groupedTimeline) {
		$timeline = array();
		$previousMergedPoint = null;
		foreach($groupedTimeline as $date => $points) {
			$maxilvl = 0;
			$maxilvlequipped = 0;
			$itemsDiff = array();			
			foreach($points as $datapoint) {
				if($datapoint["averageItemLevel"] > $maxilvl) {
					$maxilvl = $datapoint["averageItemLevel"];
				}
				if($datapoint["averageItemLevelEquipped"] > $maxilvlequipped) {
					$maxilvlequipped = $datapoint["averageItemLevelEquipped"];
				}
				$itemsDiff = array_merge($datapoint["itemsDiff"],$itemsDiff);
			}


			$currentMergedPoint = array(
					"averageItemLevel" => $maxilvl,
					"averageItemLevelEquipped" => $maxilvlequipped,
					"formattedDate" => $date,
					"itemsDiff" => $itemsDiff,
					"timestamp" => floatval(strtotime($date)*1000)
			);
			if(!$previousMergedPoint) {
				$previousMergedPoint = $currentMergedPoint;
			}

			$currentMergedPoint["averageItemLevelDelta"] = $currentMergedPoint["averageItemLevel"] -$previousMergedPoint["averageItemLevel"];
			$currentMergedPoint["averageItemLevelEquippedDelta"] = $currentMergedPoint["averageItemLevelEquipped"] -$previousMergedPoint["averageItemLevelEquipped"];

			$timeline[] = $currentMergedPoint;
			$previousMergedPoint = $currentMergedPoint;
		}
		return $timeline;
	}
		
	private function arrayRecursiveDiff($aArray1, $aArray2) { 
	    $aReturn = array(); 
	   
	    foreach ($aArray1 as $mKey => $mValue) { 
	        if (array_key_exists($mKey, $aArray2)) { 
	            if (is_array($mValue)) { 
	                $aRecursiveDiff = $this->arrayRecursiveDiff($mValue, $aArray2[$mKey]); 
	                if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; } 
	            } else { 
	                if ($mValue != $aArray2[$mKey]) { 
	                    $aReturn[$mKey] = $mValue; 
	                } 
	            } 
	        } else { 
	            $aReturn[$mKey] = $mValue; 
	        } 
	    } 
	   
	    return $aReturn; 
	} 

}