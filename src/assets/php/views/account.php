<div id="main">
	
	<div id="navigation">
		<?php include("navigation.php"); ?>
	</div>

	<div id="content">
		
		<div id="header">
			<h1>Account Settings</h1>
		</div>
		<div id="notificationArea">&nbsp;</div>
		
		<?php
			if (!empty($_GET["msg"])) {
				if (is_numeric($_GET["msg"])) {
					$msg = trim($_GET["msg"]);
					switch ($msg){
						case 1:
							echo "<span class=\"statusMsg\" id=\"response_message\">Data synced from server successfully!";
							break;
						case 2:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">Your account seems to be inactive";
							break;
						case 3:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">This action has been disabled";
							break;
						case 4:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">Database error";
							break;
						case 5:
							echo "<span class=\"statusMsg\" id=\"response_message\">Patch count updated successfully";
							break;
						case 6:
							echo "<span class=\"statusMsg\" id=\"response_message\">Patch count is already accurate";
							break;
						case 7:
							echo "<span class=\"statusMsg\" id=\"response_message\">Password changed successfully";
							break;
						case 8:
							echo "<span class=\"statusMsg\" id=\"response_message\">Email changed successfully";
							break;
						default:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">Unknown error";
							break;
					}
					echo "</span>";
				}
			}
		?>
		
		<div id="accInfo">
			<h3>Current Info:</h3>
			<?php
				echo "<p>Username: <strong>" . $_SESSION["username"] . "</strong></p>";
				echo "<p>Email: <strong>" . $_SESSION["email"] . "</strong> <a onclick='changeEmail()' href='#' class=''>(Change)</a></p>";
				echo "<p>Sign Up Date: <strong>" . $_SESSION["signUpDate"] . "</strong></p>";
				echo "<p>Sign Up IP: <strong>" . $_SESSION["createdIP"] . "</strong></p>";
				if ($_SESSION["accountType"] == 1) {
					echo "<p>Account Type: <strong>Free</strong>";
				} elseif ($_SESSION["accountType"] == 2) {
					echo "<p>Account Type: <strong>Special</strong>";
				} elseif ($_SESSION["accountType"] == 3) {
					echo "<p>Account Type: <strong>Important</strong>";
				} elseif ($_SESSION["accountType"] == 4) {
					echo "<p>Account Type: <strong>Administrator</strong>";
				} else {
					echo "<p>Account Type: <strong>Error</strong>";
				}
				echo "<p>Your account allows " . $_SESSION["allowedPatchInt"] . " patches of " .  $_SESSION["allowedPatchSizeStr"] . " each</p>";
				echo '<button class="button-alt" onclick="window.location.href=\'assets/php/scripts/recount.php\'">Check Patches</button> ';
				if ($site["features"]["allowAccountReload"] == true) {
					echo '<button class="button-alt" onclick="window.location.href=\'assets/php/scripts/update.php\'" title="Reloads session data">Reload Session</button>';
				} else {
					echo '<button class="button-alt" onclick="window.location.href=\'assets/php/scripts/logout.php\'">Logout</button>';
				}
			?>
			
			<br /><br />
			<button class="button-alt" onclick="changePassword()">Change Password</button> 
			<!--<button class="button-alt" onclick="deleteAccount()">Delete Account</button>-->
		</div>
		
	</div>
	
	<script type="text/javascript" src="assets/libs/hashes.min.js"></script>
	<script type="text/javascript" defer>
	window.SHA512 = new Hashes.SHA512
		
	function changePassword() {
		var newHTML = '<a onclick="location.reload();" href="#">Back</a><br /><br /><input type="password" id="oldPassword" placeholder="Current Password" class="formElementAlt" required /><br /><br /><input type="password" id="newPassword" placeholder="New Password" class="formElementAlt" /><br /><input type="password" id="newPasswordVerify" placeholder="New Password Again" class="formElementAlt" /><br /><button id="update_password" class="button">Update Password</button>';
		$("#accInfo").html(newHTML);
		
		$("#update_password").click(function(){
			var oldPwd = window.SHA512.hex($("#oldPassword").val());
			var newPwd = window.SHA512.hex($("#newPassword").val());
			var newVpw = window.SHA512.hex($("#newPasswordVerify").val());
			
			if (newVpw != newPwd) {
				console.warn("Passwords didn't match");
			} else {
				var dataSet = "change=password&oldPassword=" + oldPwd + "&newPassword=" + newPwd;
				
				$.ajax({
					url: 'assets/php/scripts/settings.php',
					type: 'POST',
					dataType: 'html',
					data: dataSet,
					beforeSend: function() {
						$("#update_password").prop('value', 'Updating'); // Indicate that the form is being processed
						$("#notificationArea").html("Updating...");
					},
					success: function(data) {
						$("#update_password").prop('value', 'Update Password');
						$("#notificationArea").html(data);
						if (data.indexOf("statusMsgErr") == -1) {
							location.href = "<?php echo $fullPathToRoot; ?>?p=acc&msg=7";
						}
					},
					error: function(e) {
						console.log(e); // Log any errors
						$("#notificationArea").html("Error");
					}
				});
			}
		});
	}
	
	function changeEmail() {
		var newHTML = '<a onclick="location.reload();" href="#">Back</a><br /><br /><input type="password" id="oldPassword" placeholder="Current Password" class="formElementAlt" required /><br /><br /><input type="email" id="newEmail" placeholder="New Email" class="formElementAlt" /><br /><button id="update_email" class="button">Update Email</button>';
		$("#accInfo").html(newHTML);
		
		$("#update_email").click(function(){
			var oldPwd = window.SHA512.hex($("#oldPassword").val());
			var newEml = $("#newEmail").val();
			
			var dataSet = "change=email&oldPassword=" + oldPwd + "&newEmail=" + newEml;
				
			$.ajax({
				url: 'assets/php/scripts/settings.php',
				type: 'POST',
				dataType: 'html',
				data: dataSet,
				beforeSend: function() {
					$("#update_email").prop('value', 'Updating'); // Indicate that the form is being processed
					$("#notificationArea").html("Updating...");
				},
				success: function(data) {
					$("#update_email").prop('value', 'Update Email');
					$("#notificationArea").html(data);
					if (data.indexOf("statusMsgErr") != -1) {
						location.href = "<?php echo $fullPathToRoot; ?>?p=acc&msg=8";
					}
				},
				error: function(e) {
					console.log(e); // Log any errors
					$("#notificationArea").html("Error");
				}
			});
		});
	}
	</script>
	
</div>