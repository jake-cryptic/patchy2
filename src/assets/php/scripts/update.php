<?php
require '../../init.php';
require '../main/db.php';

// This script will update the session data

// Check if the user is logged in
if (accountLoggedIn()) {
	if ($site["features"]["allowAccountReload"] == false) {
		$conn->close();
		echo "<script> location.href='{$fullPathToHome}?p=acc&msg=3'; </script>";
		die("Error: This is not allowed");
	}
	
	$username = $conn->real_escape_string(trim($_SESSION["username"]));
	
	// Only select what we need
	$newData = "SELECT isActive,isAccountType,email,accountCreatedIP,signUpDate,usedPatches FROM users WHERE username='" . $username . "'";
	
	$results = $conn->query($newData);
	
	if (!$results) {
		$conn->close();
		echo "<script> location.href='{$fullPathToHome}?p=acc&msg=4'; </script>";
		die();
	}
	
	// Reset some of the session array
	while($item = $results->fetch_object()) {
		if ($item->isActive == 1) {
			
			// Set important information
			$_SESSION["accountType"] = $item->isAccountType;
			if ($_SESSION["accountType"] == 1) {
				$_SESSION["allowedPatchInt"] = $site["limits"]["defaultPatchAmount"];
				$_SESSION["allowedPatchSize"] = $site["limits"]["defaultPatchMaxSize"];
				$_SESSION["allowedPatchSizeStr"] = $site["limits"]["defaultPatchMaxSizeStr"];
			} else {
				$_SESSION["allowedPatchInt"] = $site["limits"]["premiumPatchAmount"];
				$_SESSION["allowedPatchSize"] = $site["limits"]["premiumPatchMaxSize"];
				$_SESSION["allowedPatchSizeStr"] = $site["limits"]["premiumPatchMaxSizeStr"];
			}
			$_SESSION["email"] = $item->email;
			$_SESSION["createdIP"] = $item->accountCreatedIP;
			$_SESSION["signUpDate"] = $item->signUpDate;
			$_SESSION["loginSet"] = true;
			$_SESSION["usedPatchInt"] = $item->usedPatches;
			
		} else {
			echo "<script> location.href='{$fullPathToHome}?p=acc&msg=2'; </script>";
			die("Error: Account isn't active, contact support for help");
		}
	}
	session_regenerate_id();
	$conn->close();
	echo "<script> window.location.href='{$fullPathToHome}?p=acc&msg=1'; </script>";
	die();
} else {
	$conn->close();
	die("Error: You aren't logged in.");
}
?>