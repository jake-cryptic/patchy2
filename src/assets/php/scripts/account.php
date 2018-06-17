<?php
require '../../init.php';
require '../main/db.php';
// Currently only passwords can be done...

// Check if the user is logged in
if (!accountLoggedIn()) {
	die("Error: You aren't logged in");
} else {
	// Ok, lets get started
	if (empty($_POST)) {
		// If nothing was sent return an error
		die("Error: Nothing was set");
	} else {
		// Make the errors array
		$err = array();
		
		// Time to clean the data so SQL injection isnt possible
		$id = htmlspecialchars($_SESSION["username"]);
		$pw = htmlspecialchars($_POST["verifyPassword"]);
		
		// If we have had no errors so far we shall continue
		if (count($err) == 0) {
			
			// Escape the data for more security
			$nid = $conn->real_escape_string(trim($id));		// ID
			$pwd = $conn->real_escape_string(trim($pw));		// Password
			
			// Get the salts and hash the password to check it against the db
			$getSalts = "SELECT * FROM users WHERE username='" . $nid . "'";
			
			$results = $conn->query($getSalts);
			
			while($item = $results->fetch_object()) {
				$hashedPass = hashPassword($pwd,$item->uniqueSalt,$item->otherUniqueSalt);
				$databaseHash = $item->password;
				if ($hashedPass != $databaseHash) {
					die("Couldn't log you in");
				} else {
					if ($item->isActive == 1) {
						if ($item->isAccountType == 4) {
							echo "Passed security checks, logged in";
							$_SESSION["isVerifiedAdmin"] = true;
						} else {
							die("You aren't an admin");
						}
						echo "<script> location.href='{$fullPathToRoot}admin.php'; </script>";
					} else {
						die("Account isn't active, contact support for help");
					}
				}
			}
			
			$conn->close();
			die();
		} else {
			
		}
	}
}
?>