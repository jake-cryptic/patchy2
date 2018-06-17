<?php

if (file_exists("first_run.php")) {
	die('<title>Error</title><style> * { font-size:1.2em; font-family:sans-serif; font-weight:100; text-align:center; } h1 { font-size:2.1em; }</style><h1>Patchy</h1>If you have just upgraded Patchy or have just installed Patchy <a href="first_run.php">click here!</a><br /><br />Already done that? Delete first_run.php!');
}

// Include this file on all pages
require "php/main/version.php";
require "php/main/config.php";
require "php/main/funcs.php";
require "php/main/session.php";

// Put "Debug" in the page title
if ($site["security"]["debug"] == true) {
	$sitePrefix = "Debug | ";
} else {
	$sitePrefix = "";
	
	// In production mode URL checks are a must!
	
	$_CWF = getCWF();
	if (strpos($_CWF,"assets") !== false) {
		// It was funny when I was testing this and I got an error and had no clue why..
		// Then it was fairly easy to fix.. Yeah..
	} elseif(strpos($_CWF,"patch") !== false) {
		// This fixes another issue..
	} else {
		if ($fullPathToRoot != $_CWF || $fullPathToRoot."/" != $_CWF) {
			die('<title>Error</title><style> * { font-size:1.2em; font-family:sans-serif; font-weight:100; text-align:center; } h1 { font-size:2.1em; }</style><h1>Patchy</h1>The site is set to run at <a href="'.$fullPathToRoot.'">'.$fullPathToRoot.'</a><br /><br />But you are trying to access it at '.$_CWF);
		}
	}
}

?>