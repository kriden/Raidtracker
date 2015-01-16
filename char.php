<?PHP
require_once "dbconf.php";

$params = array("member" => $_GET["member"]);

$member = R::findOne("profile"," name=:member ORDER BY last_modified DESC", $params);

$json = "{}";
if($member !== null && !isset($_GET['history'])) {
	$json = $member->json();
} else if(isset($_GET['history'])) {
	$profiles = R::find("profile", " name=:member ORDER BY last_modified", $params);
	$history = new Model_ProfileHistory($profiles);
	$jsonData = array("character" => $member->dto(), "history" => $history->dto());

	if(@$_GET["debug"] == true) {
		$jsonData["items"] = $member->getItems();
	}
	$json = json_encode($jsonData);
}

// output json
Header("Content-Type: application/json");
echo $json;
R::close();