<div id="main">
	
	<div id="navigation">
		<?php @include("navigation.php"); ?>
	</div>
	
	<div id="content">
		
		<div id="header">
			<h1><?php echo $site["info"]["name"]; ?></h1>
			<h2>Welcome, <?php echo $_SESSION["username"]; ?></h2>
			<h3>Your Patch Server: <?php echo $site["server"]["host"]."patch/".$_SESSION["username"]."/"; ?></h3>
		</div>
		
		<div id="acc-info">
			<h3>You have used <?php echo $_SESSION["usedPatchInt"] . " out of " . $_SESSION["allowedPatchInt"]; ?> patches</h3>
		</div>
		
		<div id="whats-new">
			<a href="<?php echo $fullPathToRoot; ?>?p=bupl" class="white-link">Beta uploader</a>
		</div>
		
	</div>
	
</div>