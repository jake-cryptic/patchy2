<?php
require '../../init.php';
require '../main/db.php';

// Check if the user is logged in
if (accountLoggedIn()) {
	 die("Error: You are logged in.");
} else {
	// Ok, lets get started
	if (empty($_POST)) {
		// If nothing was sent return an error
		die("Error: Nothing was set");
	} else {
		// Make the errors array
		$err = array();
		
		// Time to clean the data so SQL injection isnt possible
		$id = htmlspecialchars($_POST["loginIdentity"]);
		$pw = htmlspecialchars($_POST["loginPassword"]);
		
		// Check if the email is valid and if it's being used
		if (filter_var($id, FILTER_VALIDATE_EMAIL) === false) {
			$checkUserName = "SELECT * FROM users WHERE username='" . $id . "'";
			$queryUserName = $conn->query($checkUserName);
			$userNameRowCheck = $queryUserName->num_rows;
			if ($userNameRowCheck == 0) {
				$err[] = "Account doesn't exist";
			} else {
				$idIs = 1;
			}
		} else {
			$checkEmail = "SELECT * FROM users WHERE email='" . $id . "'";
			$queryEmail = $conn->query($checkEmail);
			$emailRowCheck = $queryEmail->num_rows;
			if ($emailRowCheck == 0) {
				$err[] = "Account doesn't exist";
			} else {
				$idIs = 0;
			}
		}
		
		
		// If we have had no errors so far we shall continue
		if (count($err) == 0) {
			
			// Escape the data for more security
			$nid = $conn->real_escape_string(trim($id));		// Email
			$pwd = $conn->real_escape_string(trim($pw));		// Username
			
			// Get the salts and hash the password to check it against the db
			if ($idIs == 0) {
				$getSalts = "SELECT * FROM users WHERE email='" . $nid . "'";
			} else {
				$getSalts = "SELECT * FROM users WHERE username='" . $nid . "'";
			}
			
			$results = $conn->query($getSalts);
			
			while($item = $results->fetch_object()) {
				$hashedPass = hashPassword($pwd,$item->uniqueSalt,$item->otherUniqueSalt);
				$databaseHash = $item->password;
				if ($hashedPass != $databaseHash) {
					die("<span class=\"formError\">Couldn't log you in</span>");
				} else {
					if ($site["security"]["debug"] == true && $item->isAccountType != 3 && $item->isAccountType != 4) {
						die("<span class=\"formError\">Logins have been temporarily disabled as the site is in debug mode</span><br />");
					}
					if ($item->isActive == 1) {
						echo "Logged in!";
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
						$_SESSION["username"] = $item->username;
						$_SESSION["email"] = $item->email;
						$_SESSION["createdIP"] = $item->accountCreatedIP;
						$_SESSION["signUpDate"] = $item->signUpDate;
						$_SESSION["loginSet"] = true;
						$_SESSION["usedPatchInt"] = $item->usedPatches;
						echo "<script> location.href='{$fullPathToRoot}?p=home'; </script>";
					} else {
						die("Account isn't active, contact support for help");
					}
				}
			}
			$conn->close();
			die();
		} else {
			foreach ($err as $error) {
				echo '<span class="formError">' . $error . '</span><br />';
			}
			die();
		}
	}
}
?>