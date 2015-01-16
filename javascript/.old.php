<?php
/** 
*	Loads character relevant data
*/ 
ini_set("max_execution_time", 3000);
Header("Content-Type: application/json; charset=UTF-8");
error_reporting(E_ERROR);

require_once "app/RaidMember.class.php";

function arrayRecursiveDiff($aArray1, $aArray2) { 
    $aReturn = array(); 
   
    foreach ($aArray1 as $mKey => $mValue) { 
        if (array_key_exists($mKey, $aArray2)) { 
            if (is_array($mValue)) { 
                $aRecursiveDiff = arrayRecursiveDiff($mValue, $aArray2[$mKey]); 
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

$mongo = new MongoClient();
$db = $mongo->selectDb("guild-armory");
$armorydata = $db->armorydata;
$select = array("name" => $_GET["member"]);
$sort = array("lastModified" => -1);

$charDataCursor = $armorydata->find($select)->sort($sort);
$charDataCursor->next();
$charData = $charDataCursor->current();

$member = new RaidMember($charData);
if(!isset($_GET["history"])) {
	echo json_encode($member->getJSON());
} else {
	function sortByTimestamp($a,$b) {
		$timeA = $a['lastModified'];
		$timeB = $b['lastModified'];
		if($timeA > $timeB) {
			return 1;
		} else {
			return 0;
		}
	}


	$keys = array("items.averageItemLevelEquipped" => 1, "items.averageItemLevel" => 1, "lastModified" => 1, "items" => 1);
	$cond = array("condition" => array("name" => $_GET["member"]));
	$reduce = "function(obj,prv) { }";
	$initial = array("count" => "0");

	$data = $armorydata->group($keys, $initial, $reduce, $cond);
	
	$datapoints = array();
	$sortedData = $data['retval'];
	usort($sortedData, 'sortByTimestamp');
	foreach($sortedData as $datapoint) {
		if($lastItems == null) {
			$previousDataPoint = $datapoint;
			$lastItems = $datapoint["items"];
		}
		$datapoints[] = array(
			"averageItemLevel" => $datapoint["items.averageItemLevel"],
			"averageItemLevelDelta" => $datapoint["items.averageItemLevel"] - $previousDataPoint["items.averageItemLevel"],
			"averageItemLevelEquipped" => $datapoint["items.averageItemLevelEquipped"],
			"averageItemLevelEquippedDelta" => $datapoint["items.averageItemLevelEquipped"] - $previousDataPoint["items.averageItemLevelEquipped"],
			"timestamp" => $datapoint["lastModified"],
			"itemsDiff" => arrayRecursiveDiff($datapoint["items"], $lastItems),
			"formattedDate" => date("d-m-Y H:i", $datapoint["lastModified"]/1000),
		);

		$previousDataPoint = $datapoint;
		$lastItems = $datapoint["items"];
	}

	$response = array(
		"character" => $member->getJSON(),
		"history" => $datapoints
	);

	echo json_encode($response);
}