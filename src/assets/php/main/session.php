<?php

/*
* Controls user's session
*/

session_name("PatchyID");

session_set_cookie_params(0,"/","",false,true); // Array ( [lifetime] => 0 [path] => / [domain] => [secure] => [httponly] => 1 )

session_start();

if (!isset($_SESSION["accountType"])) {
	$_SESSION["loginSet"] = false;
}

?>