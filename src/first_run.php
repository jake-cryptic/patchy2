<?php
namespace Patchy\Plugins;

session_name("PatchySession");
session_set_cookie_params(7200,"/","",false,true); // Session only lasts 30 minutes!

session_start();

class PatchyAssistant {
	private function checkInstall() {
		$filesNotFound = array();
		
		if (!file_exists("assets/php/main/config.php")) $filesNotFound[] = "assets/php/main/config.php";
		if (!file_exists("assets/php/main/db.php")) $filesNotFound[] = "assets/php/main/db.php";
		if (!file_exists("assets/php/main/funcs.php")) $filesNotFound[] = "assets/php/main/funcs.php";
		if (!file_exists("assets/php/main/session.php")) $filesNotFound[] = "assets/php/main/session.php";
		if (!file_exists("assets/php/main/version.php")) $filesNotFound[] = "assets/php/main/version.php";
		if (!file_exists("assets/init.php")) $filesNotFound[] = "assets/init.php";
		
		if (count($filesNotFound) > 0) {
			echo '<div id="titleBox"><span id="PatchyInstallerTitle">Missing Files!</span><br />';
			foreach($filesNotFound as $fileNotFound) {
				echo '<br />' . $fileNotFound;
			}
			echo '<br /><button class="button" onclick="window.location.reload()">Fixed!</button></div>';
			return false;
		} else {
			return true;
		}
		//require("assets/php/main/funcs.php") or die('<div id="titleBox"><span id="PatchyInstallerTitle">Error!</span><br /><span id="PatchyInstallerVersion">Functions file not found!</span><button class="button" onclick="window.location.reload()">Fixed!</button></div>');
	}
	
	private function showWelcome() {
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy Assistant 1.3</span><br />
			<button class="button" onclick="window.location.href=\'first_run.php?c=1\'">Install Patchy</button>
			<button class="button" onclick="window.location.href=\'first_run.php?u=1\'">Upgrade Patchy</button><br />
			<button class="button" onclick="window.location.href=\'first_run.php?z=1\'">Already done this</button>
			</div>
		';
	}
	private function upgrade_ShowWelcome() {
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy Upgrade</span><br />
			<span id="PatchyInstallerVersion">Intended for Patchy 3.2</span><br /><br />
			This tool should be compatible with all versions of patchy<br />
			<button class="button" onclick="window.location.href=\'first_run.php?u=2\'">Next</button>
			</div>
		';
	}
	private function install_ShowWelcome() {
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy Installer</span><br />
			<span id="PatchyInstallerVersion">Intended for Patchy 3.2</span><br /><br />
			This tool is used for a fresh install of patchy<br />
			<button class="button" onclick="window.location.href=\'first_run.php?c=2\'">Start Setup</button>
			</div>
		';
	}
	private function upgrade_ChangeLog() {
		$a = @file_get_contents("changes.log");
		$a = str_replace("__","<br /><br /><br /><br />",$a);
		$a = str_replace("---------------------------------------","",$a);
		$a = str_replace("-","<br />-",$a);
		$a = str_replace("##_","<strong style=\"font-size:1.1em\">-",$a);
		$d = str_replace("_##","</strong>",$a);
		
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy Upgrade</span><br />
			<span id="PatchyInstallerVersion">Change log</span><br />
			<button class="button" onclick="window.location.href=\'first_run.php?u=3\'">Start Upgrade</button><br /><br />
			<span id="PatchyUpgradeChangeLog">' . $d . '</span><br />
			</div>
		';
	}
	private function install_GetDbInfo() {
		$patchDir = 'patch/';
		if (!is_writable(dirname($patchDir))) {
			echo dirname($patchDir) . ' must be writeable';
		} else {
			echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Database</span><br />
			<span id="PatchyInstallerVersion">Enter your database logins</span><br />
			<form action="first_run.php?c=3" method="post" name="installForm">
				<input class="text-input" type="text" id="db-host" name="db-host" placeholder="Host (Database server; such as 127.0.0.1 or localhost)" /><br />
				<input class="text-input" type="text" id="db-port" name="db-port" placeholder="Port (Optional)" /><br />
				<input class="text-input" type="text" id="db-dbse" name="db-dbse" placeholder="Database Name (If it exists!)" /><br />
				<input class="text-input" type="text" id="db-user" name="db-user" placeholder="Username" /><br />
				<input class="text-input" type="password" id="db-pass" name="db-pass" placeholder="Password (Use is recommended)" /><br />
				Be aware that the db.php file in assets/php/main/ may be over written with new details<br />
				<input type="submit" class="button" value="Install Patchy" />
			</form>
			</div>
		';
		}
	}
	private function upgrade_GetDbInfo() {
		if (isset($_GET["x"])) {
			echo '<div id="titleBox">
				<span id="PatchyInstallerTitle">Database Upgrade</span><br />
				<span id="PatchyInstallerVersion">Enter your database logins</span><br />
				<form action="first_run.php?u=4" method="post" name="installForm">
					<input class="text-input" type="text" id="db-host" name="db-host" placeholder="Host (Database server; such as 127.0.0.1 or localhost)" required /><br />
					<input class="text-input" type="text" id="db-port" name="db-port" placeholder="Port (Optional)" /><br />
					<input class="text-input" type="text" id="db-dbse" name="db-dbse" placeholder="Database Name" required /><br />
					<input class="text-input" type="text" id="db-user" name="db-user" placeholder="Username" required /><br />
					<input class="text-input" type="password" id="db-pass" name="db-pass" placeholder="Password" /><br />
					<input type="submit" class="button" value="Upgrade Patchy" />
				</form>
				</div>
			';
		} else {
			$patchDir = 'patch/';
			if (!is_writable(dirname($patchDir))) {
				echo dirname($patchDir) . ' isn\' writable';
			}
			if (file_exists("assets/php/main/db.php")) {
				echo '<div id="titleBox">
				<span id="PatchyInstallerTitle">Upgrade</span><br />
				<span id="PatchyInstallerVersion">A database file has been found!</span><br />
				<button class="button" onclick="window.location.href=\'first_run.php?u=4&x=a\'">Use Database File</button>
				<button class="button" onclick="window.location.href=\'first_run.php?u=3&x=m\'">Manual Setup</button>';
			} else {
				echo '<div id="titleBox">
					<span id="PatchyInstallerTitle">Database Upgrade</span><br />
					<span id="PatchyInstallerVersion">Enter your database logins</span><br />
					<form action="first_run.php?u=4" method="post" name="installForm">
						<input class="text-input" type="text" id="db-host" name="db-host" placeholder="Host (Database server; such as 127.0.0.1 or localhost)" required /><br />
						<input class="text-input" type="text" id="db-port" name="db-port" placeholder="Port (Optional)" /><br />
						<input class="text-input" type="text" id="db-dbse" name="db-dbse" placeholder="Database Name" required /><br />
						<input class="text-input" type="text" id="db-user" name="db-user" placeholder="Username" required /><br />
						<input class="text-input" type="password" id="db-pass" name="db-pass" placeholder="Password" /><br />
						<input type="submit" class="button" value="Upgrade Patchy" />
					</form>
					</div>
				';
			}
		}
	}
	private function attemptInstall() {
		// Attempt an install
		echo '<div id="titleBox" style="margin:0 25% 0 25%;width:50%;border-bottom:1px dashed rgba(0,0,0,0.9);"><span id="PatchyInstallerTitle">Installing</span><br /></div><br />';ob_flush();flush();
		
		if ($this->checkInstall() == false) {
			die("<span style='color:red;font-size:1.2em;'>Patchy couldn't find all required files</span><br />");
		} else {
			echo "<span style='color:green;font-size:1.2em;'>All required files were found</span><br />"; ob_flush();flush();
		}
		if (!empty($_POST)) {
			echo "<span style='color:green;font-size:1.2em;'>Post isn't empty</span><br />"; ob_flush();flush();
			if (!empty($_POST["db-host"] || $_POST["db-user"] || $_POST["db-pass"])) {
				
				// Clean up the details.. For no reason in particular
				$host = filter_var($_POST["db-host"], FILTER_SANITIZE_URL);
				$user = filter_var($_POST["db-user"], FILTER_SANITIZE_SPECIAL_CHARS);
				$pass = trim($_POST["db-pass"]);
				if (!empty($_POST["db-port"])) {
					$port = filter_var($_POST["db-port"], FILTER_SANITIZE_NUMBER_INT);
				} else {
					echo "<span style='color:orange;font-size:1.2em;'>Port wasn't set, setting as 3306</span><br />"; ob_flush();flush();
					$port = 3306;
				}
				if (!empty($_POST["db-dbse"])) {
					$dbse = filter_var($_POST["db-dbse"], FILTER_SANITIZE_SPECIAL_CHARS);
					$shouldUseDB = true;
				} else {
					echo "<span style='color:orange;font-size:1.2em;'>Database wasn't set, setting as Patchy</span><br />"; ob_flush();flush();
					$dbse = "patchy";
					$shouldUseDB = false;
				}
				
				echo "<span style='color:green;font-size:1.2em;'>Data was accepted</span><br />"; ob_flush();flush();
				
				// Now attempt a connection
				if ($shouldUseDB) {
					$conn = new \mysqli($host,$user,$pass,$dbse,$port); // HOST, USER, PASS, DB, PORT, SOCKET
				} else {
					$conn = new \mysqli($host,$user,$pass,null,$port); // HOST, USER, PASS, DB, PORT, SOCKET
				}

				if ($conn->connect_error) {
					unset($conn);
					die("<span style='color:red;font-size:1.2em;'>Server connection failed - Aborting install</span><br />");
				} else {
					$conn->set_charset("utf8");
					echo "<br /><span style='color:green;font-size:1.25em;font-weight:bold;'>Connected to server " . $host . " on port " . $port . "</span><br /><br />"; ob_flush();flush();
				}
				
				// Create the queries!
				$createDatabase = "CREATE DATABASE IF NOT EXISTS $dbse";
				$createUserTable = "CREATE TABLE IF NOT EXISTS `users` (
								  `user_id` int(11) NOT NULL,
								  `isActive` int(1) NOT NULL DEFAULT '1',
								  `isAccountType` int(1) NOT NULL DEFAULT '1',
								  `uniqueSalt` varchar(512) NOT NULL,
								  `otherUniqueSalt` varchar(512) NOT NULL,
								  `username` varchar(50) NOT NULL,
								  `email` varchar(255) NOT NULL,
								  `password` varchar(384) NOT NULL,
								  `accountCreatedIP` varchar(15) NOT NULL,
								  `lastLoginIP` varchar(15) NOT NULL,
								  `signUpDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  `lastLoginDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  `usedPatches` int(11) NOT NULL DEFAULT '0'
								) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";
				$modifyUserTableUID = "ALTER TABLE `users` ADD PRIMARY KEY (`user_id`);";
				$modifyUserID = "ALTER TABLE `users` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;";
				$addTestAccount = "INSERT INTO `users` (`user_id`, `isActive`, `isAccountType`, `uniqueSalt`, `otherUniqueSalt`, `username`, `email`, `password`, `accountCreatedIP`, `signUpDate`, `usedPatches`) VALUES(1, 1, 4, '42b296da40f465f98f6ee9207ecb0c8f63351bd27a2dba50242988cde2849d5f65306d377577c447121d95944a672469cc23a9c03d50f9d9346b5882732ec09a', '428bfd1bd8134a7e8ea1efded1b5d6b6c38b4491f3c16321ae0b0faae56986ccf1d06809f90d50fd5b977cf716aa49d2dbc633f17cafd9e76ca122427c2705e4', 'patchy_self_admin', 'e4a3f6f7@opayq.com', '993cc243147d8366e1ce002848b2760adbcb7ad709079bd568f718ca45f756fa53a0368853db9c0071a335c9276f8394988096aa694b40430385c89b67453bc0', '::1', '2015-10-10 19:37:36', 0);";
				
				// Now to run the queries... Yay!
				if ($shouldUseDB) {
					$r = $conn->select_db($dbse);
					if ($r) {
						echo "<span style='color:green;font-size:1.2em;'>Database Selected</span><br /><br />"; ob_flush();flush();
					} else {
						die("<span style='color:red;font-size:1.2em;'>Database couldn't be selected - Aborting install</span><br />");
					}
				} else {
					$r = $conn->query($createDatabase);
					if ($r) {
						echo "<span style='color:green;font-size:1.2em;'>Database Created</span><br />"; ob_flush();flush();
						$r = $conn->select_db($dbse);
						if ($r) {
							echo "<span style='color:green;font-size:1.2em;'>Database Selected</span><br /><br />"; ob_flush();flush();
						} else {
							die("<span style='color:red;font-size:1.2em;'>Database couldn't be selected - Aborting install</span><br />");
						}
					} else {
						die("<span style='color:red;font-size:1.2em;'>Database couldn't be created - Aborting install</span><br />");
					}
				}
				
				$r = $conn->query($createUserTable);
				if ($r) {
					echo "<span style='color:green;font-size:1.2em;'>Table Created</span><br /><br />"; ob_flush();flush();
				} else {
					die("<span style='color:red;font-size:1.2em;'>Table creation failure - Aborting install</span><br />");
				}
				
				$r = $conn->query($modifyUserTableUID);
				if ($r) {
					echo "<span style='color:green;font-size:1.2em;'>Added primary key to user_id</span><br />"; ob_flush();flush();
				} else {
					die("<span style='color:red;font-size:1.2em;'>Primary Key couldn't be added to user_id - Aborting install</span><br />");
				}
				
				$r = $conn->query($modifyUserID);
				if ($r) {
					echo "<span style='color:green;font-size:1.2em;'>Special parameters added to user_id</span><br />"; ob_flush();flush();
				} else {
					die("<span style='color:red;font-size:1.2em;'>Special parameters couldn't be added to user_id - Aborting install</span><br /><br />");
				}
				
				$r = $conn->query($addTestAccount);
				if ($r) {
					echo "<span style='color:green;font-size:1.2em;'>Write Permissions OK; An account was successfully created</span><br /><br />"; ob_flush();flush();
				} else {
					die("<span style='color:orange;font-size:1.2em;'>Patchy may not be able to write to the database - Please try making an account</span><br /><br />");
				}
				
				$fileContents = "<?php\n/* Generated Using PatchyInstaller2 */\n\nDEFINE(\"db_host\",\"$host\");\nDEFINE(\"db_user\",\"$user\");\nDEFINE(\"db_pass\",\"$pass\");\nDEFINE(\"db_dbse\",\"$dbse\");\n\n\$conn = @new mysqli(db_host,db_user,db_pass,db_dbse);\n\nif (\$conn->connect_error) {\n\tunset(\$conn);\n\tdie(\"<h1 style=\\\"font-family:sans-serif\\\">Database server is unreachable</h1>\");\n} else {\n\t\$conn->set_charset(\"utf8\");\n}\n\n?>";
				$db_file = fopen("db.php","w");
				fwrite($db_file,$fileContents);
				fclose($db_file);
				echo "<span style='color:green;font-size:1.2em;'>Database (db.php) file was generated</span><br />"; ob_flush();flush();
				
				$r = $conn->close();
				if ($r) {
					echo "<span style='color:green;font-size:1.3em;'>Patchy Installed Successfully</span><br />"; ob_flush();flush();
					unset($r);
					sleep(3);
					echo "<script type='text/javascript'> window.location.href='first_run.php?c=4'; </script>";
				} else {
					die("<span style='color:green;font-size:1.3em;'>Patchy Installed :)</span><br />");
				}
			} else {
				header("Location: first_run.php?c=5");
			}
		} else {
			header("Location: first_run.php?c=5");
		}
	}
	private function attemptUpgrade() {
		// Attempt an upgrade
		echo '<div id="titleBox" style="margin:0 25% 0 25%;width:50%;border-bottom:1px dashed rgba(0,0,0,0.9);"><span id="PatchyInstallerTitle">Upgrading</span><br /></div><br />';ob_flush();flush();
		
		if ($this->checkInstall() == false) {
			die("<span style='color:red;font-size:1.2em;'>Patchy couldn't find all required files</span><br />");
		} else {
			echo "<span style='color:green;font-size:1.2em;'>All required files were found</span><br />"; ob_flush();flush();
		}
		
		if (isset($_GET["x"])) {
			if ($_GET["x"] == "a" && file_exists("assets/php/main/db.php")) {
				require("assets/php/main/db.php");
			}
		} else {
			if (!empty($_POST["db-host"] || $_POST["db-user"] || $_POST["db-pass"] || $_POST["db-dbse"])) {
				// Clean up the details.. For no reason in particular
				$host = filter_var($_POST["db-host"], FILTER_SANITIZE_URL);
				$user = filter_var($_POST["db-user"], FILTER_SANITIZE_SPECIAL_CHARS);
				$pass = trim($_POST["db-pass"]);
				if (!empty($_POST["db-port"])) {
					$port = filter_var($_POST["db-port"], FILTER_SANITIZE_NUMBER_INT);
				} else {
					echo "<span style='color:orange;font-size:1.2em;'>Port wasn't set, setting as 3306</span><br />"; ob_flush();flush();
					$port = 3306;
				}
				$dbse = filter_var($_POST["db-dbse"], FILTER_SANITIZE_SPECIAL_CHARS);
					
				echo "<span style='color:green;font-size:1.2em;'>Data was accepted</span><br />"; ob_flush();flush();
					
				$conn = new \mysqli($host,$user,$pass,$dbse,$port); // HOST, USER, PASS, DB, PORT, SOCKET
					
				if ($conn->connect_error) {
					unset($conn);
					die("<span style='color:red;font-size:1.2em;'>Server connection failed - Aborting install</span><br />");
				} else {
					$conn->set_charset("utf8");
					echo "<br /><span style='color:green;font-size:1.25em;font-weight:bold;'>Connected to server " . $host . " on port " . $port . "</span><br /><br />"; ob_flush();flush();
				}
			} else {
				die("Post was empty");
			}
		}
		
		$checkColumns = $conn->query("SELECT * FROM users LIMIT 1");
		$coulmns = $checkColumns->fetch_object();
		
		if(!isset($columns->lastLoginIP)) {
			$r = $conn->query("ALTER TABLE `users` ADD `lastLoginIP` varchar(15) NOT NULL");
			if ($r) {
				echo "<span style='color:green;font-size:1.2em;'>Column lastLoginIP created!</span><br />"; ob_flush();flush();
			} else {
				$errs = true;
				echo "<span style='color:red;font-size:1.2em;'>Couldn't create column lastLoginIP, it may already exist</span><br />";
			}
		} else {
			echo "<span style='color:orange;font-size:1.2em;'>Column lastLoginIP already exists</span><br />"; ob_flush();flush();
		}
		
		if(!isset($columns->lastLoginDate)) {
			$r = $conn->query("ALTER TABLE `users` ADD `lastLoginDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
			if ($r) {
				echo "<span style='color:green;font-size:1.2em;'>Column lastLoginDate created!</span><br />"; ob_flush();flush();
			} else {
				$errs = true;
				echo "<span style='color:red;font-size:1.2em;'>Couldn't create column lastLoginDate, it may already exist</span><br />";
			}
		} else {
			echo "<span style='color:orange;font-size:1.2em;'>Column lastLoginDate already exists</span><br />"; ob_flush();flush();
		}
		
		$r = $conn->close();
		if ($r) {
			unset($r);
			if ($errs == true) {
				echo "<span style='color:orange;font-size:1.3em;'>Upgrade successful but errors were present</span><br />"; ob_flush();flush();
				echo '<button class="button" onclick="window.location.href=\'index.php?upgradeSuccess\'">Finish</button>'; ob_flush();flush();
			} else {
				echo "<span style='color:green;font-size:1.3em;'>Upgrade was successful</span><br />"; ob_flush();flush();
				sleep(3);
				echo "<script type='text/javascript'> window.location.href='first_run.php?u=5'; </script>";
			}
		} else {
			unset($r);
			die("<span style='color:green;font-size:1.3em;'>Upgrade was successful</span><br />");
		}
	}
	private function installSuccess() {
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy 3.0 Installed Successfully</span><br />
			<span id="PatchyInstallerVersion">Make sure to delete this install file</span><br />
			<button class="button" onclick="window.location.href=\'index.php?installSuccess\'">Load patchy</button>
			</div>
		';
	}
	private function upgradeSuccess() {
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Upgrade Success</span><br />
			<span id="PatchyInstallerVersion">Make sure to delete this file!</span><br />
			<button class="button" onclick="window.location.href=\'index.php?upgradeSuccess\'">Load patchy</button>
			</div>
		';
	}
	private function installError() {
		if (empty($_GET["e"])){
			$_GET["e"] = 0;
		}
		if (is_numeric($_GET["e"])) {
			switch($_GET["e"]) {
				case 1:
					$errMsg = "Missing required information";
					break;
				case 2:
					$errMsg = "Couldn't connect to database";
					break;
				default:
					$errMsg = "Unknown Error";
					break;
			}
			echo '<div id="titleBox">
				<span id="PatchyInstallerTitle">Patchy Install Failed</span><br />
				<span id="PatchyInstallerVersion">' . $errMsg . '</span><br />
				<button class="button" onclick="history.go(-1)">Go Back</button>
				</div>
			';
		} else {
			echo "That's not an error..?";
		}
	}
	private function alreadyFinished() {
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy Assistant</span><br />
			<span id="PatchyInstallerVersion">Delete the file first_run.php once you have finished using it</span><br />
			<button class="button" onclick="history.go(-1)">Go Back</button>
			</div>
		';
	}
	private function sessionManager() {
		if (isset($_SESSION["validPeriod"])) {
			if ($_SESSION["validPeriod"] < time()) {
				$this->invalidSession();
			}
		} else {
			$_SESSION["validPeriod"] = time() + 3600;
			echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy Assistant</span><br />
			<span id="PatchyInstallerVersion">Your session has started</span><br />
			<button class="button" onclick="window.location.reload()">Proceed</button>
			</div>';
			die();
		}
	}
	private function invalidSession() {
		echo '<div id="titleBox">
			<span id="PatchyInstallerTitle">Patchy Assistant</span><br />
			<span id="PatchyInstallerVersion">Your session has expired, to renew your session clear cookies or wait another hour</span><br />
			<button class="button" onclick="window.location.href=\'https://www.google.co.uk/search?q=clear+cookies\'">How to clear cookies</button>
			</div>';
		die();
	}
	public function run() {
		$this->sessionManager();
		
		if (isset($_GET["c"]) && is_numeric($_GET["c"])) {
			switch($_GET["c"]) {
				case 1:
					if ($this->checkInstall() == false) { die(); }
					$this->install_ShowWelcome();
					break;
				case 2:
					$this->install_GetDbInfo();
					break;
				case 3:
					$this->attemptInstall();
					break;
				case 4:
					$this->installSuccess();
					break;
				case 5:
					$this->installError();
					break;
				default:
					header("Location: first_run.php?c=1");
					break;
			}
		} elseif (isset($_GET["u"]) && is_numeric($_GET["u"])){
			switch($_GET["u"]) {
				case 1:
					if ($this->checkInstall() == false) { die(); }
					$this->upgrade_ShowWelcome();
					break;
				case 2:
					$this->upgrade_ChangeLog();
					break;
				case 3:
					$this->upgrade_GetDbInfo();
					break;
				case 4:
					$this->attemptUpgrade();
					break;
				case 5:
					$this->upgradeSuccess();
					break;
				case 6:
					$this->upgradeError();
					break;
				default:
					header("Location: first_run.php?u=1");
					break;
			}
		} elseif (isset($_GET["z"]) && is_numeric($_GET["z"])) {
			$this->alreadyFinished();
		} else {
			$this->showWelcome();
		}
	}
}
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
	
		<title>Patchy</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="author" content="jake-cryptic" />
		<meta name="keywords" content="Patchy, Install, Upgrade, Assistant, Patchy2, GitHub" />
		
		<style type="text/css">
			* { margin-top:2em; text-align:center; font-family:sans-serif; }
			#titleBox { margin-top:2.1em; }
			#PatchyInstallerTitle { font-size:2.1em; }
			#PatchyInstallerVersion { font-size:1.2em; }
			.button {padding:6px 12px;font-size:.875em;font-weight:400;line-height:1.42857143;white-space: nowrap;cursor:pointer;user-select:none;background-color:#000;color:#FFF;border:1px solid transparent;border-radius:5px;}
			.text-input { margin:0 11.5% 0 11.5%;display:block;width:75%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:5px; }
		</style>
	
	</head>
	<body>
	
		<?php 
			$i = new PatchyAssistant;
			$i->run();
		?>
		
	</body>
</html>