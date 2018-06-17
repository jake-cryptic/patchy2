<?php
/*
 * File: recount.php
 * Description: Counts patches user has uploaded and resets value if incorrect
*/

require("../../init.php");
require("../main/db.php");

if (accountLoggedIn()) {
	// Count the amount of patches user currently has
	$currentdir = getcwd();
	if (strpos($currentdir,"\\") !== false) {
		$dirs = array_filter(glob($currentdir . '\..\..\..\patch\\' . $_SESSION["username"] . '\*'), 'is_dir');
	} else {
		$dirs = array_filter(glob($currentdir . '/../../../patch/' . $_SESSION["username"] . '/*'), 'is_dir');
	}
	$patches = count($dirs);
	
	if ($_SESSION["usedPatchInt"] == $patches) { // If the current amount of patches in db is the same as there actually is
		header("Location: $fullPathToRoot?p=acc&msg=6");
		die("Redirecting");
	} else {
		$updatePatchCount = "UPDATE users SET usedPatches = {$patches} WHERE username='" . $_SESSION["username"] . "'";
		if ($conn->query($updatePatchCount)) {
			$conn->close();
			$_SESSION["usedPatchInt"] = $patches;
			
			header("Location: $fullPathToRoot?p=acc&msg=5");
			die("Success!");
		} else {
			$conn->close();
			
			header("Location: $fullPathToRoot?p=acc&msg=4");
			die("Fail!");
		}
	}
} else {
	header("Location: $fullPathToRoot?p=upl&msg=15");
	die("Redirecting");
}
?>