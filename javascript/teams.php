<?php
require_once "dbconf.php";

Header("Content-Type: application/json");
/**
*	Returns TEAMS in JSON format
*/
$rbwMembers = array(
"Santeri",
"Iliae",
"Fero",
"Briecalypse",
"Beerkeg",
"Foxinsox",
"Shammachi",
"Zeniva",
"Bárry",
"Sloefke",
"Cesna",
"Greenthy",
"Fahdrek",
"Flavez",
"Chouffie",
"Clippe",
"Borduk",
"Colombian",
"Eldania",
"Vimlashh",
"Groentje",
"Tivuna",
"Hesparios",
"Aep",
"Luxodon",
"Thzaaki",
"Ysaria",
"Hëvÿ");

$ftMembers = array(
"Ihavehotdots",
"Fabulistic",
"Lunainin",
"Smatjé",
"Prìde",
"Kayus",
"Agátha",
"Hurriquack",
"Getrektnubzz",
"Claerin",
"Juliuscaesar",
"Rebeliertje",
"Orthaddae",
"Rulïv",
"Rebelmage",
"Aetheralis",
"Hopsakee",
"Noscore",
"Raguss",
"Innetic",
"Harleybeer",
"Yuzia",
"Darkzoo",
"Thunderdaz",
"Elishar",
"Magicbjørn",
"Chiwhoefy"
);


$trMembers = array("Kraator",
"Therer",
"Karantia",
"Rayvén",
"Offendo",
"Ludissk",
"Miqa",
"Butserr",
//"Taylian",
"Louarn",
"Thoorer",
"Marw",
"Hyse",
"Selador",
"Juliusw",
"Snookik",
"Badtoady",
"Boozë",
"Fírespell",
"Talrashaa",
//"Wík",
"Máradar",
"Yòu",
"Blowmycrit",
"Eyll",
"Firoz",
"Grackus",
"Jonko",
"Gibso");


$TEAMS = array(
	array(
		"name" => "Rainbow Warriors",
		"id" => "RBW",
		"memberNames" => $rbwMembers
	),
	array(
		"name" => "Team Rocket",
		"id" => "TR",
		"memberNames" => $trMembers
	),
	array(
		"name" => "Fairy Tail",
		"id" => "FT",
		"memberNames" => $ftMembers
	),
);

if(empty($_GET["averages"])) {
	Header("Content-Type: application/json, charset=UTF-8");
	echo json_encode($TEAMS);
} else {
	$timestamp = $_GET["averages"];
	$member = @$_GET["member"];

	if($timestamp==="auto") {
		$today = strtotime("00:00:00");
		$timestamps = array();
		$numDays = 31;
		for($i=0;$i<$numDays;$i++) {
			$timestamps[] = strtotime("-$i day", $today)*1000;
		}
		$timestamp = implode($timestamps, ",");

	}

	if(isset($_GET["member"]))  {
		$memberTeams = array();
		foreach($TEAMS as $info) {
			if(in_array($member, $info["memberNames"])) {
				$memberTeams[] = $info;
			}
		}
	} else {
		$memberTeams = $TEAMS;
	}

	mysql_connect(DB_HOST,DB_USERNAME, DB_PASSWORD);
	mysql_select_db(DB_NAME);

	foreach($memberTeams as $i => &$memberTeam) {
		//var_dump("Loading data for ".$memberTeam["name"]);
		$queryIn = array();
		foreach($memberTeam["memberNames"] as $memberName) {
			$queryIn[] = "'".$memberName."'";
		}
		$in = implode(",", $queryIn);

		$timestamps = explode(",",$timestamp);
		sort($timestamps);
		$datapoints = array();
		foreach($timestamps as $last_modified) { 
			$innerQuery = "SELECT MAX(id) as 'id',name FROM profile WHERE name IN ($in) AND level=100 AND last_modified <= $last_modified GROUP BY name ORDER BY `profile`.`item_level_equipped` ASC";
			$sql = "SELECT AVG(item_level_equipped) as 'averageItemLevelEquipped' FROM profile p JOIN ($innerQuery) as p2 ON p.id=p2.id ";
		
			$res = mysql_query($sql) or die(mysql_error());
			$row = mysql_fetch_assoc($res);
			$row['timestamp'] = $last_modified;
			$row['averageItemLevelEquipped'] = round($row['averageItemLevelEquipped']);
			$row['formattedDate'] = date("d-M-Y", (floatval($last_modified)/1000));
			$datapoints[] = $row;
		}
		$memberTeam["history"] = $datapoints;
		//$memberTeams[$i]["history"] = $datapoints;
		//var_dump($datapoints);
	}

	if(count($memberTeams) == 1) {
		echo json_encode($memberTeam);
	} else {
		echo json_encode($memberTeams);
	}
}