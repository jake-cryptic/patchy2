<div id="main">
	
	<div id="navigation">
		<?php @include("navigation.php"); ?>
	</div>
	
	<div id="content">
		
		<?php
		if (checkPatchAmount()) {
			?>
			<div id="header">
				<h1>Upload a patch</h1>
				<h3>Patch Server: <?php echo $site["server"]["host"]."patch/".$_SESSION["username"]."/"; ?></h3>
			</div>
			
			
			<script type="text/javascript">
			function uploadFile(){
				var file = document.getElementById("theFile").files[0];
				// alert(file.name+" | "+file.size+" | "+file.type);
				var formdata = new FormData();
				formdata.append("theFile", file);
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				ajax.addEventListener("error", errorHandler, false);
				ajax.addEventListener("abort", abortHandler, false);
				ajax.open("POST", "assets/php/scripts/upload.php");
				ajax.send(formdata);
			}
			function progressHandler(event){
				document.getElementById("uploaded").innerHTML = "Uploaded " + (event.loaded/1000); //event.total
				var percent = (event.loaded / event.total) * 100;
				document.getElementById("uploadProgess").value = Math.round(percent);
				document.getElementById("status").innerHTML = Math.round(percent) + "% uploaded... please wait";
			}
			function completeHandler(event){
				$("#status").html(event.target.responseText);
				document.getElementById("uploadProgess").value = 0;
			}
			function errorHandler(event){
				$("#status").html("<span class=\"statusMsgErr\">Upload Failed</span>");
			}
			function abortHandler(event){
				$("#status").html("<span class=\"statusMsgErr\">Upload Cancelled</span>");
			}
			</script>
			
			<style type="text/css">
			
			</style>
			
			<div id="uploadArea">
				<p>You can upload <?php
					if (($_SESSION["allowedPatchInt"]-$_SESSION["usedPatchInt"]) == 1) {
						echo "1 more patch";
					} else {
						echo ($_SESSION["allowedPatchInt"]-$_SESSION["usedPatchInt"]) . " more patches";
					}
					?></p>
				<p id="uploadSteps">
					Upload an <strong>individual</strong> patch<br />
					1) Archive the patch in .zip format<br />
					2) Press upload patch and select the .zip file you just made<br />
					3) Your patch should upload successfully and be usable
				</p>
				
				
				<form id="beta_uploader" method="post" enctype="multipart/form-data">
					<input type="file" name="theFile" id="theFile" /><br /><br />
					<input type="button" value="Upload Patch" class="button" onclick="uploadFile()"><br /><br />
					<progress id="uploadProgess" value="0" max="100" style="width:25%"></progress>
				</form>
				
				<p id="status"></p>
				<p id="uploaded"></p>
			</div>
			<?php
		} else {
			?>
			<div id="header">
				<h1>Patch limit reached</h1>
				<h2>You have used <?php echo $_SESSION["usedPatchInt"] . " of " . $_SESSION["allowedPatchInt"]; ?> patches</h2>
				<h3>Patch Server: <?php echo $site["server"]["host"]."patch/".$_SESSION["username"]."/"; ?></h3>
			</div>
			<?php
		}
		?>
		
		
	</div>
	
</div>