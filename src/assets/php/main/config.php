<?php
/*
 * Patchy 3.2 created by Jake Mcneill
 * Licensed under the BSD 2-clause license
 * https://github.com/jake-cryptic/patchy2
 */

// Keeps current protocol instead of forcing a change
if ($_SERVER["SERVER_PORT"] == 443) {
	$protocol = "https";
} else {
	$protocol = "http";
}

$site = array(
	"info" => array(
		"name" => "SITE_NAME",
		"author" => "",
		"content" => "text/html; charset=UTF-8",
		"language" => "en",
		"keywords" => "",
		"description" => "",
		"shortDesc" => "UPDATE YOUR CONFIG FILE"
	),
	
	"security" => array(
		"debug" => false, // Only set to true when diagnosing errors
		"debug_level" => 4, // Level of error verbosity when debug == true
		"validate_ip" => true // Checks if the users IP is valid
	),
	
	"custom" => array(
		"theme_location" => "assets/themes/patchy1/", // Must contain 3 files (background.jpg, global.css, home.css)
		"favicon_location" => "assets/images/patchy.png",
		"appleicon_location" => "assets/images/patchy-apple-icon.png"
	),
	
	"features" => array(
		"allowAccountReload" => true // Allows account reloading account data without needing to re-login
	),
	
	"limits" => array(
		"defaultPatchAmount" => 15,
		"premiumPatchAmount" => 50,
		"defaultPatchMaxSize" => 10240000, // 10MB
		"premiumPatchMaxSize" => 15360000, // 15MB
		"defaultPatchMaxSizeStr" => "10MB",
		"premiumPatchMaxSizeStr" => "15MB"
	),
	
	"server" => array(
		"host" => "$protocol://patchserver.localhost/", // Set this to your domain must end with a / and start with http:// or https://
		"mainPage" => "index.php", // Change only if you renamed the index.php file
		"pathToMainPage" => "" // Only change if it is in a folder not the server root e.g. http://website/FOLDER_HERE/index.php
	)
);

$fullPathToHome = $site["server"]["host"] . $site["server"]["pathToMainPage"] . $site["server"]["mainPage"];
$fullPathToRoot = $site["server"]["host"] . $site["server"]["pathToMainPage"];

GLOBAL $site,$fullPathToHome,$fullPathToRoot;

?>