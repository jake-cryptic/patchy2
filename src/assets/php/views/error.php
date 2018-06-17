<div id="main">
	
	<div id="navigation">
		<?php @include("navigation.php"); ?>
	</div>
	
	<div id="content">
		
		<div id="header">
			<h1>Error</h1>
		</div>
		
		<?php
			if (!empty($_GET["msg"])) {
				if (is_numeric($_GET["msg"])) {
					$msg = trim($_GET["msg"]);
					switch ($msg){
						case 1:
							echo '<span class="statusMsgErr">File exists, please contact support for help';
							break;
						case 2:
							echo '<span class="statusMsgErr">This patch breaks our file size limit of ' . $_SESSION["allowedPatchSizeStr"];
							break;
						case 3:
							echo '<span class="statusMsgErr">The file uploaded was not in .zip format!';
							break;
						case 4:
							echo '<span class="statusMsgErr">Files couldn\'t be moved, contact support';
							break;
						case 5:
							echo '<span class="statusMsg">File was uploaded successfully! <a href="?p=svr" title="Continue">Click to Continue</a>';
							break;
						case 6:
							echo '<span class="statusMsgErr">General file upload error, contact support asap';
							break;
						case 7:
							echo '<span class="statusMsgErr">Unable to locate the file to verify an upload success';
							break;
						case 8:
							echo '<span class="statusMsgErr">File may be corrupt or an invalid .zip archive';
							break;
						case 9:
							echo '<span class="statusMsgErr">Couldn\'t register your patch with the server';
							break;
						case 10:
							echo '<span class="statusMsgErr">We have a limit of ' . $_SESSION["allowedPatchInt"] . ' patches';
							break;
						case 11:
							echo '<span class="statusMsgErr">File uploaded cannot be read';
							break;
						default:
							echo '<span class="statusMsgErr">Unknown error #2';
							break;
					}
					echo "</span>";
				}
			}
		?>
		
	</div>
	
</div>