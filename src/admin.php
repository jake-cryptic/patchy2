<?php
require("assets/init.php");

// Kick them off if they aren't an admin
if(accountLoggedIn()) {
	if ($_SESSION["accountType"] != 4) {
		header("Location: /");
		die("This is a local admin script for local admins, we don't want your kind here");
	} else {
		session_regenerate_id(); 
	}
} else {
	header("Location: /");
	die("This is a local admin script for local admins, we don't want your kind here");
}

if (isset($_POST["action"])) { // SHOULD ONLY BE USED FOR AJAX REQUESTS
	require("assets/php/main/db.php");
	$err = array();
	switch($_POST["action"]) {
		case "commit":
			if (!isset($_POST["commit"]) || !isset($_POST["userID"]) || !isset($_POST["change"])) {
				die("<div class='statusMsgErr'>No data was sent</div>");
			} else {
				$DataChangeDirty = trim(base64_decode($_POST["change"]));
				$NewDataDirty = trim(base64_decode($_POST["commit"]));
				$UserIDDirty = trim(base64_decode($_POST["userID"]));
				
				if (!is_numeric($UserIDDirty)) {
					die("<div class='statusMsgErr'>Invalid User: {$UserIDDirty}</div>");
				}
				if ($DataChangeDirty == "emChange") {
					$DataChangeDirty = "email";
				} elseif ($DataChangeDirty == "idChange") {
					$DataChangeDirty = "user_id";
				} elseif ($DataChangeDirty == "tpChange") {
					$DataChangeDirty = "isAccountType";
				} elseif ($DataChangeDirty == "acChange") {
					$DataChangeDirty = "isActive";
				} else {
					die("<div class='statusMsgErr'>You can't change that</div>");
				}
				
				$DataChange = $conn->real_escape_string($DataChangeDirty);
				$NewData = $conn->real_escape_string($NewDataDirty);
				$UserID = $conn->real_escape_string($UserIDDirty);
				
				$commitChangeQuery = "UPDATE users SET {$DataChange}='{$NewData}' WHERE user_id={$UserID}";
				if ($conn->query($commitChangeQuery) === TRUE) {
					echo "<div class='statusMsg'>Change committed</div>";
				} else {
					echo "<div class='statusMsgErr'>Couldn't commit data: [" . $conn->error . "]</div>";
				}
			}
			$conn->close();
			die();
		break;
		default:
			die("There was an error performing your request");
			break;
	}
}

$_adminNavBar = '<a href="admin.php" class="nav_link"><div class="nav_item left">Home</div></a>
				<a href="index.php" class="nav_link"><div class="nav_item left">Site</div></a>
				<a href="admin.php?c=usr" class="nav_link"><div class="nav_item left">Users</div></a>
				<a href="admin.php?c=cnf" class="nav_link"><div class="nav_item left">Site Config</div></a>
				<a href="admin.php?c=hlp" class="nav_link"><div class="nav_item left">Admin Help</div></a>
				<a href="index.php?p=lgo" class="nav_link"><div class="nav_item right">Logout</div></a>';

?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		
		<!-- Title and Metadata -->
		<title><?php echo $sitePrefix . $site["info"]["name"]; ?></title>
		<meta http-equiv="content-type" content="<?php echo $site["info"]["content"]; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta http-equiv="content-language" content="<?php echo $site["info"]["language"]; ?>" />
		<meta name="author" content="<?php echo $site["info"]["author"]; ?>" />
		<meta name="google" content="notranslate" />
		<meta name="description" content="<?php echo $site["info"]["description"]; ?>" />
		<meta name="keywords" content="<?php echo $site["info"]["keywords"]; ?>" />
		
		<!-- Styles and Graphics -->
		<link rel="stylesheet" type="text/css" href="<?php echo $site["custom"]["theme_location"]; ?>global.css" media="screen" />
		<style type="text/css">  body { background:url('<?php echo $site["custom"]["theme_location"]; ?>background.jpg') no-repeat center center fixed;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover; } </style>
		<link rel="apple-touch-icon" sizes="250x250" href="<?php echo $site["custom"]["appleicon_location"]; ?>">
		<link rel="icon" href="<?php echo $site["custom"]["favicon_location"]; ?>" type="image/gif">
		
		<!-- Scripts and Libraries -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="assets/libs/hashes.min.js"></script>
		<script>
			// Fallback to local copies if vital Libraries are unreachable
			if (!window.jQuery) { 
				console.log("Failed to connect to JQuery CDN, using a local copy"); 
				document.write('<script src="assets/libs/jquery-2.1.4.min.js" type="text/javascript"><\/script>'); 
			} else {
				console.log("JQuery loaded successfully :)");
			}
		</script>
		
	</head>
	<body>
		
		<div id="main">
			<?php 
				if (!isset($_SESSION["isVerifiedAdmin"])) {
			?>
			<div id="navigation">
				<a href="index.php?p=home" class="nav_link"><div class="nav_item left">Home</div></a>
				<a href="index.php?p=lgo" class="nav_link"><div class="nav_item right">Logout</div></a>
			</div>
			
			<div id="verifyMessage" class="hidden overlay-page">Checking your password</div>
			
			<div id="content">
				
				<div id="notificationArea">&nbsp;</div>
				
				<div id="header">
					<div class="a-cp-tarea">
						<h1><?php echo $site["info"]["name"] . " - $version - AdminCP"; ?></h1>
						<h2>To continue as <?php echo $_SESSION["username"]; ?>, enter your password</h2>
						<form action="#" method="post" name="verificationAdmin" id="verificationAdmin">
							<input type="password" class="formElement" placeholder="Password" name="verifyPassword" id="verifyPassword" /><br />
							<input type="submit" id="verifyButton" value="Login" class="button" />
						</form>
					</div>
				</div>
				
			</div>
			
			<script src="assets/js/adminPageAjax.js" type="text/javascript"></script>
			
			<?php 
				} elseif ($_SESSION["isVerifiedAdmin"] == true) {
					if (empty($_GET["c"])) {
			?>
			
			<div id="navigation">
				<?php echo $_adminNavBar; ?>
			</div>
			
			<div id="content">
				
				<div id="notificationArea">&nbsp;</div>
				
				<div id="header">
					<div class="a-cp-tarea">
						<h1><?php echo $site["info"]["name"] . " - $version - AdminCP"; ?></h1>
						<h2>Logged in as <?php echo $_SESSION["username"]; ?></h2>
						<h4><?php if (!checkPatchyVersion()) { echo "<span id=\"update-me\">A newer version of Patchy is available <a href='https://github.com/jake-cryptic/patchy2'>here</a></span>"; } else { echo "Patchy is up to date"; } ?></h4>
					</div>
				</div>
				
			</div>
			
			<?php
					} else {
						if ($_GET["c"] == "usr") {
			?>
			
			<div id="navigation">
				<?php echo $_adminNavBar; ?>
			</div>
			
			<div id="content">
				
				<div id="notificationArea">&nbsp;</div>
				
				<div id="header">
					<div class="a-cp-tarea">
						<h1><?php echo $site["info"]["name"] . " - $version - Users"; ?></h1>
					</div>
					
					<script type="text/javascript">
					function editUser(url) {
						window.location.href = "<?php echo $fullPathToRoot; ?>" + url;
					}
					</script>
					
					<table class="table table-small">
						<thead>
							<tr>
								<th>UserID</th>
								<th>Username</th>
								<th>Email</th>
								<th>Is Active</th>
								<th>Account Type</th>
								<th>Create IP</th>
								<th>Used Patches</th>
								<th>Sign Up Date</th>
							</tr>
						</thead>
						<tbody>
							<?php
							require "assets/php/main/db.php";
							$getUsers = "SELECT user_id, username, email, isActive, isAccountType, accountCreatedIP, usedPatches, signUpDate FROM users";
							$listUsers = $conn->query($getUsers);
							
							while($item = $listUsers->fetch_object()) {
								if($item->isActive == 1) { 
									$activeState = "Active"; 
								} else { 
									$activeState = "Inactive";
								}
								
								switch($item->isAccountType) {
									case 1:
										$accountType = "User";
										break;
									case 2:
										$accountType = "Special";
										break;
									case 3:
										$accountType = "Semi-Admin";
										break;
									case 4:
										$accountType = "Admin";
										break;
									default:
										die("Unknown Account type for user " . $item->username . " (ID: " . $item->user_id . ")");
										break;
								}
								
								echo "<tr onclick='editUser(\"admin.php?c=acc&i=" . $item->user_id . "&u=" . $item->username . "\")'>
									<td>" . $item->user_id . "</td>
									<td>" . $item->username . "</td>
									<td>" . $item->email . "</td>
									<td>" . $activeState . "</td>
									<td>" . $accountType . "</td>
									<td>" . $item->accountCreatedIP . "</td>
									<td>" . $item->usedPatches . "</td>
									<td>" . $item->signUpDate . "</td>
								</tr>";
							}
							if ($site["security"]["debug"] == true) {
								echo $conn->error;
							}
							
							$conn->close();
							?>
						</tbody>
					</table>
				</div>
				
			</div>
			
			<?php
						} elseif ($_GET["c"] == "acc") {
			?>
			
			<div id="navigation">
				<?php echo $_adminNavBar; ?>
			</div>
			
			<div id="content">
			
				<div id="notificationArea">&nbsp;</div>
				
				<?php
					if (empty($_GET["i"]) || !is_numeric($_GET["i"])) {
						echo '<div id="header">
					<div class="a-cp-tarea">
						<h1>Error</h1>
						<h2>Couldn\'t find that user</h2> 
					</div>
				</div>';
					} else {
						require "assets/php/main/db.php";
						$specifiedID = $conn->real_escape_string($_GET["i"]);
						$getUser = "SELECT user_id, username, email, isActive, isAccountType, accountCreatedIP, usedPatches, signUpDate FROM users WHERE user_id='{$specifiedID}'";
						$userDetails = $conn->query($getUser);
					
						while($user = $userDetails->fetch_object()) {
							if($user->isActive == 1) { 
								$activeState = "Active";
								$activeStateButton = "Deactivate";
							} elseif ($user->isActive == 2) { 
								$activeState = "<strong>Pending Deletion</strong>";
								$activeStateButton = "Delete";
							} else {
								$activeState = "Inactive";
								$activeStateButton = "Activate";
							}
							
							switch($user->isAccountType) {
								case 1:
									$accountType = "User";
									break;
								case 2:
									$accountType = "Special";
									break;
								case 3:
									$accountType = "Semi-Admin";
									break;
								case 4:
									$accountType = "Admin";
									break;
								default:
									die("Unknown Account type for user " . $user->username . " (ID: " . $user->user_id . ")");
									break;
							}
							$accountToUpdate = $user->user_id;
							$usernameToUpdate = $user->username;
							
							echo "
							<h1>User - " . $user->username . "</h1>
							<table id=\"user_details\">
								<tr><td>User ID</td><td id=\"user_detail_id\">" . $user->user_id . "</td></tr>
								<tr><td>Email</td><td id=\"user_detail_em\">" . $user->email . "</td></tr>
								<tr><td>State</td><td id=\"user_detail_as\">" . $activeState . "</td></tr>
								<tr><td>Type</td><td id=\"user_detail_at\">" . $accountType . "</td></tr>
								<tr><td>IP</td><td id=\"user_detail_ip\">" . $user->accountCreatedIP . "</td></tr>
								<tr><td>Used Patches</td><td id=\"user_detail_up\">" . $user->usedPatches . "</td></tr>
								<tr><td>Sign Up</td><td id=\"user_detail_su\">" . $user->signUpDate . "</td></tr>
							</table>
							<br /><br />
							<button class=\"button\" id=\"edit_user\">Edit User</button> <button class=\"button\" id=\"statechange_user\">$activeStateButton</button> <button class=\"button\" onclick=\"alert('This feature has not yet been implemented')\">Remove Account</button>";
							if ($user->username == "patchy_self_admin") {
								die("<script> $(\".button\").click(function(){alert('Changes to this user are not permitted');})</script></html>");
							}
						}
						if ($site["security"]["debug"] == true) {
							echo $conn->error;
						}
						
						$conn->close();
					}
				?>
			</div>
			
			<script type="text/javascript">
			var commitChange = function (type) {
				console.log("Updating detail " + type);
				//alert("Not yet available.");
				// Make ajax call with values to update
				
				var updateUser = "<?php echo $accountToUpdate; ?>";
				var changeStr = "action=commit&commit=" + btoa($("#" + type).val()) + "&userID=" + btoa(updateUser) + "&change=" + btoa(type);
				
				$.ajax({
					url: 'admin.php',
					type: 'POST',
					dataType: 'html',
					data: changeStr,
					beforeSend: function() {
						$("#" + type + "_button").prop('value', 'Committing'); // Indicate that the form is being processed
						$("#notificationArea").html("<div class='statusMsg'>Commiting Change...</div>");
						console.log("Updating user " + updateUser + " - Changing " + type + " to " + $("#" + type).val());
					},
					success: function(data) {
						$("#notificationArea").html(data);
						$("#" + type + "_button").prop('value', 'Commit');
					},
					error: function(e) {
						$("#notificationArea").html("<div class='statusMsgErr'>Couldn't commit data</div>");
						$("#" + type + "_button").prop('value', 'Commit');
						console.warn("Error"); // Log any errors
						console.log(e); // Log any errors
					}
				});
			};
			
			$("#edit_user").click(function(){
				if (window.edit_user_active == "undefined" || window.edit_user_active == null || window.edit_user_active != true) {
					window.edit_user_active = true;
					var newID = '<input type="text" id="idChange" value="' + $("#user_detail_id").html() + '" /> <button onclick="commitChange(\'idChange\')" id="idChange_button">Commit</button>';
					var newEM = '<input type="text" id="emChange" value="' + $("#user_detail_em").html() + '" /> <button onclick="commitChange(\'emChange\')" id="emChange_button">Commit</button>';
					var newTP = '<select id="tpChange" default="' + $("#user_detail_at").html() + '"><option value="1">User</option><option value="2">Moderator</option><option value="3">Admin</option></select> <button onclick="commitChange(\'tpChange\')" id="tpChange_button">Commit</button>';
					
					$("#user_detail_id").html(newID);
					$("#user_detail_em").html(newEM);
					$("#user_detail_at").html(newTP);
				} else {
					location.reload();
				}
			});
			
			$("#statechange_user").click(function() {
				var updateUser = "<?php echo $accountToUpdate; ?>";
				
				if ("<?php echo $activeStateButton; ?>" == "Deactivate") {
					var changeStateTo = "0";
					var acceptance = prompt("To deactivate user '<?php echo $usernameToUpdate; ?>' type in their username below", "");
				} else {
					var changeStateTo = "1";
					var acceptance = prompt("To active user '<?php echo $usernameToUpdate; ?>' type in their username below", "");
				}
				
				if (acceptance == "<?php echo $usernameToUpdate; ?>") {
					var changeStr = "action=commit&commit=" + btoa(changeStateTo) + "&userID=" + btoa(updateUser) + "&change=" + btoa("acChange");
				
					$.ajax({
						url: 'admin.php',
						type: 'POST',
						dataType: 'html',
						data: changeStr,
						beforeSend: function() {
							$("#statechange_user").prop('value', 'Updating'); // Indicate that the form is being processed
							$("#notificationArea").html("<div class='statusMsg'>Commiting Change...</div>");
							console.log("Changing activeState for user " + updateUser);
						},
						success: function(data) {
							$("#notificationArea").html(data);
							$("#statechange_user").prop('value', '');
							window.setTimeout(function() {
								location.reload();
							},1000);
						},
						error: function(e) {
							$("#notificationArea").html("<div class='statusMsgErr'>Couldn't commit data</div>");
							$("#statechange_user").prop('value', '');
							console.warn("Error"); // Log any errors
							console.log(e); // Log any errors
						}
					});
				} else {
					$("#notificationArea").html("<div class='statusMsgErr'>Action aborted</div>");
				}
			});
			</script>
			
			<?php
						} elseif ($_GET["c"] == "cnf") {
			?>
			<div id="navigation">
				<?php echo $_adminNavBar; ?>
			</div>
			
			<div id="content">
				
				<div id="notificationArea">&nbsp;</div>
				
				<div id="header">
					<div class="a-cp-tarea">
						<h1><?php echo $site["info"]["name"] . " - $version - Site Config"; ?></h1>
						<h2>A quick way to edit config.php</h2>
					</div>
				</div>
				
				<style type="text/css">
				#site_configuration { width:100%; margin-bottom:5%; }
				.adminCategory { background-color:rgba(0,0,0,0.8);;color:#fff; } .adminCategory:hover { background-color:rgba(0,0,0,0.4); color:#fff; }
				.adminSetting { background-color:rgba(255,255,255,0.8); } .adminSetting:hover { background-color:rgba(255,255,255,0.4); }
				</style>
				
				<table id="site_configuration">
					<thead>
						<tr><th>Option</th><th>Value</th><th>Actions</th></tr>
					</thead>
					<tbody>
						<?php
						foreach($site as $category=>$key) {
							echo "<tr class=\"adminCategory\"><td colspan=\"3\" id=\"e_sc_$category\">" . ucwords($category) . "</td></tr>";
							foreach ($key as $nm=>$vl) {
								if (!is_array($vl)) {
									// This is just to show booleans as t/f not 1/0
									if (gettype($vl) == "boolean") {
										if ($vl == "1"){
											$sVl = "True";
										} else {
											$sVl = "False";
										}
									} else {
										$sVl = $vl;
									}
									// Echo the results
									echo "<tr class=\"adminSetting\">
										<td id=\"e_si_$nm\">$nm</td>
										<td id=\"e_si_vl_$nm\">$sVl</td>
										<td>
											<button id=\"button_edit_$nm\" onclick=\"editSiteOption('$nm','$category')\">Edit</button>
											<button id=\"button_info_$nm\" onclick=\"viewSiteOption('$nm','$category')\">Info</button>
										</td>
									</tr>";
								}
							}
						}
					?></tbody>
				</table>
				
			</div>
			
			<script type="text/javascript">
			function editSiteOption(option, category) {
				alert("This will be working in the next version");
			}
			function viewSiteOption(option, category) {
				alert("This will be working in the next version");
			}
			</script>
			
			<?php
						} elseif ($_GET["c"] == "hlp") {
							echo "<h1>Loading Official Help</h1><script type='text/javascript'> window.location.href='http://patchy-a.co.nf/help.php'; </script>";
						} else {
							header("Location: admin.php");
						}
					}
				} else { die("This is a local shop for local people we don't want your kind here"); }
			?>
		</div>
		
		<script type="text/javascript">
			$("#notificationArea").click(function(){
				$("#notificationArea").fadeOut(500);
			});
		</script>
		
	</body>
</html>