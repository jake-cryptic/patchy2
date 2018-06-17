<?php
/*
 * File: unzip.php
 * Description: Unzips patches
*/

require '../../init.php';
require '../main/db.php';

if (accountLoggedIn()) { // Only if account is logged in
	if (isset($_SESSION["fileUploaded"],$_SESSION["fileDestination"])) {
		// Set as local variables
		$file = $_SESSION["fileUploaded"];
		$dest = $_SESSION["fileDestination"];
		
		if (!is_readable($file)) {
			die('<span class="statusMsg">File was uploaded successfully! <a href="?p=svr" title="Continue">Click to Continue</a></span>');
		} else {
			// Create new ZipArchive
			$zip = new ZipArchive;
			$zip->open($file);
			
			// Now check the files in the zip archive to make sure it's an actual patch
			$invalidCount = 0;
			$validCount = 0;
			$lolC = 0;
			
			// We don't start at 0 otherwise the actual patch name will cause an error
			for($i = 1; $i < $zip->numFiles; $i++){
				$item = $zip->statIndex($i); 
				$nameArray = explode("/",$item['name']);
				$name = end($nameArray);
				$fileBreak = explode(".",$name); // Clean out directories
				$fileExt = end($fileBreak); // Clean out directories
				
				if ($name != "") { // When changing directories this happens..
					// Check for valid file endings
					if (strpos(strtolower($fileExt),'csv') !== false) { // CSV check
						$validCount = $validCount + 1;
					} elseif (strpos(strtolower($fileExt),'sc') !== false) { // SC check
						$validCount = $validCount + 1;
					} elseif (strpos(strtolower($fileExt),'json') !== false) { // JSON check
						$validCount = $validCount + 1;
					} else {
						$invalidCount = $invalidCount + 1;
					}
				}
			}
			// Only if the zip file contains forbidden files shall we die()
			if ($invalidCount >= 1) {
				$zip->close();
				unlink($file);
				die('<span class="statusMsgErr">Archive contains forbidden files</span>');
			}
		}

		// Attempt Extraction
		if ($zip->extractTo($dest)) {
			$zip->close();
			
			// Delete the file if it worked
			if (file_exists($file)) {
				if (!unlink($file)) {
					die('<span class="statusMsgErr">File already exists, please contact support for help</span>');
				} else {
					$_SESSION["usedPatchInt"] = $_SESSION["usedPatchInt"] + 1;
					if (checkPatchAmount()) {
						$updatePatchCount = "UPDATE users SET usedPatches = usedPatches + 1 WHERE username='" . $_SESSION["username"] . "'";
						if ($conn->query($updatePatchCount)) {
							$conn->close();
							die('<span class="statusMsg">File was uploaded successfully! <a href="?p=svr" title="Continue">Click to Continue</a></span>');
						} else {
							die('<span class="statusMsgErr">Couldn\'t register your patch with the server</span>');
						}
					} else {
						die('<span class="statusMsgErr">We have a limit of ' . $_SESSION["allowedPatchInt"] . ' patches</span>');
					}
				}
			} else {
				$_SESSION["usedPatchInt"] = $_SESSION["usedPatchInt"] + 1;
				if (checkPatchAmount()) {
					$updatePatchCount = "UPDATE users SET usedPatches = usedPatches + 1 WHERE username='" . $_SESSION["username"] . "'";
					if ($conn->query($updatePatchCount)) {
						$conn->close();
						die('<span class="statusMsg">File was uploaded successfully! <a href="?p=svr" title="Continue">Click to Continue</a></span>');
					} else {
						die('<span class="statusMsgErr">Couldn\'t register your patch with the server</span>');
					}
				} else {
					die('<span class="statusMsgErr">We have a limit of ' . $_SESSION["allowedPatchInt"] . ' patches</span>');
				}
			}
		} else {
			die('<span class="statusMsgErr">File may be corrupt or an invalid .zip archive</span>');
		}
	} else {
		die('<span class="statusMsgErr">Unable to locate the file to verify an upload success</span>');
	}
} else {
	header("Location: $fullPathToRoot");
	die('You are\'nt logged in</span>');
}
?>