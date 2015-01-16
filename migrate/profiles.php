<?PHP
require_once "../app/rb.php";
ini_set("max_execution_time", 6000);

// DB Layout
R::setup('mysql:host=localhost;dbname=guild_armory','root','');

$client = new MongoClient();
$db = $client->selectDB("guild-armory");
$collection = $db->armorydata;

$teams = json_decode(file_get_contents("http://localhost/scripts/wowraidtracker/teams.php"), TRUE);
$memberNames = array();
foreach($teams as $team) {
	$memberNames = array_merge($memberNames, $team["memberNames"]);
}

foreach($memberNames as $name) {
	$sort = array("modifiedDate" => -1);
	$cursor = $collection->find(array("name" => $name))->sort($sort);
	$next = $cursor->next();
	$member = $cursor->current();
	while($cursor->hasNext()) {
		$cursor->next();
		$obj = $cursor->current();

		$profile = R::dispense("profile");
		unset($obj["_id"]);
		unset($obj["class"]["_id"]);

		// create flat arrqy
		$flat = array();
		$flat["itemLevelEquipped"] = intval($obj["items"]["averageItemLevelEquipped"]); 
		$flat["itemLevel"] = intval($obj["items"]["averageItemLevel"]);
		foreach($obj as $key => $value) {
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
		echo "Imported ".$profile->name."<br/>";
	}
}
?>