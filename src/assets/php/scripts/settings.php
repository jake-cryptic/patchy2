<?php
require '../../init.php';
require '../main/db.php';

// Check if the user is logged in
if (!accountLoggedIn()) {
	die("Error: You aren't logged in.");
} else {
	// Ok, lets get started
	if (empty($_POST)) {
		// If nothing was sent return an error
		die("Error: Nothing was set");
	} else {
		// Make the errors array
		$err = array();
		if (!isset($_POST["oldPassword"])) {
			$err[] = "Enter your current password";
		}
		if (!isset($_POST["newPassword"]) && !isset($_POST["newEmail"])) {
			$err[] = "Please fill out all the fields";
		}
		if (!isset($_SESSION["username"])) {
			$err[] = "Please re-login";
		}
		
		if (count($err) == 0) {
			$usr = $conn->real_escape_string(trim($_SESSION["username"]));		// Username
			$opw = $conn->real_escape_string(trim($_POST["oldPassword"]));		// Old password
			
			// Get data from database
			$getData = "SELECT * FROM users WHERE username='" . $usr . "'";
			
			$results = $conn->query($getData);
				
			while($item = $results->fetch_object()) {
				$salt1 = $item->uniqueSalt;
				$salt2 = $item->otherUniqueSalt;
				
				$databaseHash = $item->password;
			}
			
			// Validate user password
			$hashedPass = hashPassword($opw,$salt1,$salt2);
			
			if ($hashedPass != $databaseHash) {
				$conn->close();
				die('<span class="statusMsgErr" id="response_message">Current password incorrect</span><br />');
			} else {
				switch ($_POST["change"]) {
					case "password":
						// Escape this variable
						$pwd = $conn->real_escape_string(trim($_POST["newPassword"]));
						
						// Create the new password's hash with the same salts
						$newPasswordHash = hashPassword($pwd,$salt1,$salt2);
						
						$updateData = "UPDATE users SET password='{$newPasswordHash}' WHERE username='{$usr}'";
						
						if ($conn->query($updateData) === TRUE) {
							echo "<span class='statusMsg' id='response_message'>Password Changed!</span>";
						} else {
							echo "<span class='statusMsgErr' id='response_message'>Password couldn't be changed</span>" . $conn->error;
						}
						break;
					case "email":
						// Escape this variable
						$eml = $conn->real_escape_string(trim($_POST["newEmail"]));
						if (strlen($eml) > 255) {
							die("<span class='statusMsgErr' id='response_message'>Email too long</span>");
						}
						
						if (filter_var($eml, FILTER_VALIDATE_EMAIL) === false) {
							die("<span class='statusMsgErr' id='response_message'>Not a valid email</span>");
						} else {
							$checkEmail = "SELECT * FROM users WHERE email='" . $eml . "'";
							$queryEmail = $conn->query($checkEmail);
							$emailRowCheck = $queryEmail->num_rows;
							if ($emailRowCheck > 0) {
								die("<span class='statusMsgErr' id='response_message'>Email is in use</span>");
							}
						}
						
						$updateData = "UPDATE users SET email='{$eml}' WHERE username='{$usr}'";
						
						if ($conn->query($updateData) === TRUE) {
							echo "<span class='statusMsg' id='response_message'>Email Changed!</span>";
						} else {
							echo "<span class='statusMsgErr' id='response_message'>Email couldn't be changed</span>";
						}
						break;
					default:
						die("Unsure what you are trying to achieve..?");
						break;
				}
				
				$conn->close();
			}
		} else {
			foreach ($err as $error) {
				echo '<span class="statusMsgErr" id="response_message">' . $error . '</span><br />';
			}
			die();
		}
	}
}
?>