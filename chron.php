<?PHP
Header("Content-Type: text/plain");
require_once "dbconf.php";


$url = APP_PATH."crawl.php";

/**** RETURN SOMETHING QUICKLY */
while(ob_get_level()) ob_end_clean();
header('Connection: close');
ignore_user_abort();
ob_start();
echo('Connection Closed, executing:'.$url);
$size = ob_get_length();
header("Content-Length: $size");
ob_end_flush();
flush();

$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$data = curl_exec($ch);
curl_close($ch);