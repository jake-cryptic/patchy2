<?php
require("../../init.php");

if (accountLoggedIn()) { // Only if account is logged in
	// Check if they can upload a patch
	if (checkPatchAmount()) {
		// Did they upload the file
		if(@$_FILES['theFile']['name']) {
			
			// Check for errors
			if(!$_FILES['theFile']['error']) {
				
				// Set the file name for later use
				$currentdir = getcwd();
				if (strpos($currentdir,"\\") !== false) {
					$target = $currentdir . '\..\..\..\patch\\' . stripslashes($_SESSION["username"]) . '\\' . basename($_FILES['theFile']['name']);
					$destination = $currentdir . '\..\..\..\patch\\' . stripslashes($_SESSION["username"]) . '\\';
				} else{
					$target = $currentdir . '/../../../patch/' . stripslashes($_SESSION["username"]) . '/' . basename($_FILES['theFile']['name']);
					$destination = $currentdir . '/../../../patch/' . stripslashes($_SESSION["username"]) . '/';
				}
				
				// Check the file is a zip archive
				$explodedFileName = explode(".",strtolower($_FILES["theFile"]["name"]));
				$fileExtension = end($explodedFileName);
				if ($fileExtension == "zip") {
					$new_file_name = strtolower($_FILES['theFile']['tmp_name']); // Rename the file
					if($_FILES['theFile']['size'] > ($_SESSION["allowedPatchSize"])) { // Must be smaller than is allowed for accountType 
						$valid_file = false;
					} else { 
						$valid_file = true;
					}
					
					// If the file is OK
					if($valid_file) {
						if (file_exists($target)) {
							if (!unlink($target)) {
								die('<span class="statusMsgErr">File already exists, please contact support for help</span>');
							}
						}
						if (move_uploaded_file($_FILES['theFile']['tmp_name'], $target)) {
							$_SESSION["fileUploaded"] = $target; // Set so unzip.php knows what to target
							$_SESSION["fileDestination"] = $destination; // Set so unzip.php knows other stuff
							header("Location: unzip.php");
							die("Redirecting");
						} else {
							die('<span class="statusMsgErr">Files couldn\'t be moved, contact support</span>');
						}
					} else {
						die('<span class="statusMsgErr">This patch breaks our file size limit of ' . $_SESSION["allowedPatchSizeStr"] . '</span>');
					}
				} else {
					die('<span class="statusMsgErr">The file uploaded was not in .zip format!</span>');
				}
			} else {
				// Any more problems?
				die('<span class="statusMsgErr">General file upload error, contact support</span>');
			}
		} else {
			die('<span class="statusMsgErr">Sorry, We only accept files</span>');
		}
	} else {
		die('<span class="statusMsgErr">We have a limit of ' . $_SESSION["allowedPatchInt"] . ' patches</span>');
	}
} else {
	header("Location: $fullPathToRoot");
	die('You are\'nt logged in</span>');
}
?>
