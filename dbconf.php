<?php
/**
*	DATABASE Configuration
*/
ini_set("max_execution_time", 6000);

// DB Layout
require_once "app/rb.php";
require_once "app/Model_Profile.class.php";
require_once "app/Model_ProfileHistory.class.php";

define("APP_PATH", "<app_url>");


define("DB_HOST", "<db_host>");
define("DB_NAME", "<db_passw");
define("DB_USERNAME", "<db_username>");
define("DB_PASSWORD", "<db_password>");



R::setup('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USERNAME,DB_PASSWORD);
R::freeze(FALSE);




define("APP_LOCATION", "");