<?php
require_once "dbconf.php";
ini_set("max_execution_time", 30000);
error_reporting(E_ERROR);

$cookieVal = "1a0f20aef333228ddbb3d60f8c9cbcd96a276b09";
$logh = fopen("./logs/crawler".date("d-M-Y").".log", "a+");

function quickLog($msg, $code="INFO") {
	global $logh;
	$str = date("d-M-Y H:i:s")." | $code | ".$msg.chr(10);
	fwrite($logh, $str);
}

$teamUrl = APP_PATH."teams.php";
$teams = json_decode(file_get_contents($teamUrl), TRUE);
quickLog("Fetching teams from: ".$teamUrl);
$memberNames = array();
foreach($teams as $team) {
	$memberNames = array_merge($memberNames, $team["memberNames"]);
}

foreach($memberNames as $i => $memberName) {
	// Crawl armory for this member
	$url = "https://eu.api.battle.net/wow/character/Frostmane/".$memberName."?fields=items,talents,stats&apikey=9yjc2p74n9gsscsxu8qumbcy429aj8ca";
	$data = json_decode(file_get_contents($url), TRUE);
	if($data) {
		$datemodified = $data["lastModified"];
		
		$count = R::count("profile", "name=? and last_modified=?", array($memberName, $datemodified));
		if($count < 1) {
			// get class
			$profile = R::dispense("profile");
			
			// create flat arrqy
			$flat = array();
			$flat["itemLevelEquipped"] = intval($data["items"]["averageItemLevelEquipped"]); 
			$flat["itemLevel"] = intval($data["items"]["averageItemLevel"]);
			foreach($data as $key => $value) {
				// default fields

				if(is_array($value)) {
					$flat[$key] = json_encode($value);
				} else if(is_object($value)) {
					// ignore
				} else {
					$flat[$key] = $value;
				}
			} 
			if(isset($flat["feed"])) {
				unset($flat["feed"]);
			}


			$profile->import($flat);
			R::store($profile);
			
			echo("Contacting wow armory for character ".$memberName." [".($i+1)." of ".count($memberNames)."] -- UPDATED");
			quickLog("Contacting wow armory for character ".$memberName." [".($i+1)." of ".count($memberNames)."] -- UPDATED");
			$updated++;
		} else {
			quickLog("Contacting wow armory for character ".$memberName." [".($i+1)." of ".count($memberNames)."] -- IGNORE", "DEBUG");
		}
	} else {
		quickLog("Contacting wow armory for character ".$memberName." [".($i+1)." of ".count($memberNames)."] -- ERROR", "ERROR");
		quickLog("No data found for character at ".$url);
		$success = false;
	}
}

quickLog("================== CRAWL ENDED =================================");
fclose($logh);

$data = array(
	"success" => $success,
	"updated" => $updated,
	"membersNames" => $memberNames
);
echo json_encode($data);
