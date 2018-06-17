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
		
		if ($site["security"]["debug"] == true) {
			$err[] = "Account Creation has been temporarily disabled as the site is in debug mode";
		} else {
			// Time to clean the data so SQL injection isnt possible
			$em = htmlspecialchars($_POST["createEmail"]);
			$un = htmlspecialchars($_POST["createUsername"]);
			$pw = htmlspecialchars($_POST["createPassword"]);
			
			// Validate client IP
			if ($site["security"]["validate_ip"] == true) {
				$createIP = htmlspecialchars($_SERVER["REMOTE_ADDR"]); // Just being safe..
				if (filter_var($createIP, FILTER_VALIDATE_IP) === false) {
					$err[] = "The computer you are using isn't trusted";
				}
			}
			
			// Check if the email is valid and if it's being used
			if (filter_var($em, FILTER_VALIDATE_EMAIL) === false) {
				$err[] = "Not a valid email";
			} else {
				$checkEmail = "SELECT * FROM users WHERE email='" . $em . "'";
				$queryEmail = $conn->query($checkEmail);
				$emailRowCheck = $queryEmail->num_rows;
				if ($emailRowCheck > 0) {
					$err[] = "Email is in use";
				}
			}
			
			// Check if username is valid and if it's being used
			if (preg_match("/[^a-zA-Z0-9\_]/",$un)) {
				// Had illegal characters
				$err[] = "Username must be alphanumeric with underscores";
			} else {
				// Check if username is taken don't want any hacking/collisions
				$checkUser = "SELECT * FROM users WHERE username='" . strtolower($un) . "'"; // Strtolower in case of windows servers
				$queryUser = $conn->query($checkUser);
				$userRowCheck = $queryUser->num_rows;
				if ($userRowCheck > 0) {
					$err[] = "Username has been taken";
				}
			}
			
			// Check if username email and password are all correct length
			if (strlen($un) > 50) {
				$err[] = "Username too long";
			}
			if (strlen($un) < 5) {
				$err[] = "Username too short";
			}
			
			if (strlen($em) > 255) {
				$err[] = "Email too long";
			}
			
			if (strlen($pw) < 5) {
				$err[] = "Password too short";
			}
		}
		
		// If we have had no errors so far we shall continue
		if (count($err) == 0) {
			// Create the salts and hash the password
			$uniqueSalt = createUniqueSalt();
			usleep(500000); // .5 seconds
			$otherUniqueSalt = createUniqueSalt();
			$hashedPass = hashPassword($pw,$uniqueSalt,$otherUniqueSalt);
			
			// Escape the data for more security
			$mus = $conn->real_escape_string(trim($uniqueSalt));		// Main salt
			$ous = $conn->real_escape_string(trim($otherUniqueSalt));	// Other salt
			$hpw = $conn->real_escape_string(trim($hashedPass));		// Hashed Pass
			$eml = $conn->real_escape_string(trim($em));				// Email
			$usr = $conn->real_escape_string(trim($un));				// Username
			$aip = $conn->real_escape_string(trim($createIP));			// IP
			
			// Create the query in random order for added security
			$createAccount = "INSERT INTO users (accountCreatedIP, uniqueSalt, otherUniqueSalt, password, email, username)
				VALUES ('$aip','$mus','$ous','$hpw','$eml','$usr')";
				
			// Execute and check query
			if ($conn->query($createAccount)) {
				echo "Account Created!";
			} else {
				echo "Uh Oh.. Something went very wrong<br />" . $conn->error;
			}
			$conn->close();
		} else {
			foreach ($err as $error) {
				echo '<span class="formError">' . $error . '</span><br />';
			}
			die();
		}
	}
}
?>