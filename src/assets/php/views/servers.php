<div id="main">
	
	<div id="navigation">
		<?php @include("navigation.php"); ?>
	</div>
	
	<div id="content">
		
		<div id="header">
			<h1>Patch Server Control</h1>
		</div>
		
		<?php
			if (!empty($_GET["msg"])) {
				if (is_numeric($_GET["msg"])) {
					$msg = trim($_GET["msg"]);
					switch ($msg){
						case 1:
							echo "<span class=\"statusMsg\" id=\"response_message\">Patch deletion successful";
							break;
						case 2:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">Patch couldn't be deleted";
							break;
						case 3:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">You can't delete what isn't there";
							break;
						case 4:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">Please <a href='assets/php/scripts/update.php'>reload your account</a> and try again, if that doesn't work contact support";
							break;
						case 5:
							echo "<span class=\"statusMsgErr\" id=\"response_message\">Nothing valid was sent";
							break;
						default:
							echo "<span class=\"statusMsg\" id=\"response_message\">Something happened";
							break;
					}
					echo "</span><br /><br />";
				}
			}
			
			echo '<button class="button" onclick="window.location.href=\'?p=upl\';">Upload a Patch</button>';
			
			$serverCheck = createServer($_SESSION["username"]);
			if ($serverCheck == 0) {
				if (readPatchFolder("patch/".$_SESSION["username"]."/") == False) {
					echo '<br /><br /><span class="serverMessage">No patches found</span>';
				} else {
					echo readPatchFolder("patch/".$_SESSION["username"]."/");
				}
			} elseif ($serverCheck == 1) {
				echo '<br /><br /><span class="serverMessage">Patch server now ready for use!</span>';
			} else {
				echo '<br /><br /><span class="serverMessage">Couldn\'t create your server, contact support</span>';
			}
		?>
		
	</div>
	
</div>