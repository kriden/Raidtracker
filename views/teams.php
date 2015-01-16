<?php
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
"Xiandra",
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
"Barry",
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
"Taylian",
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
"Wík",
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

Header("Content-Type: application/json, charset=UTF-8");
echo json_encode($TEAMS);