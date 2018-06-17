<?php require("assets/init.php");  ?>
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
		<style type="text/css"> body { background:url('<?php echo $site["custom"]["theme_location"]; ?>background.jpg') no-repeat center center fixed;-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover; } </style>
		<link rel="apple-touch-icon" sizes="250x250" href="<?php echo $site["custom"]["appleicon_location"]; ?>">
		<link rel="icon" href="<?php echo $site["custom"]["favicon_location"]; ?>" type="image/gif">
		
		<!-- Scripts and Libraries -->
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
		<script type="text/javascript">
			// Fallback to local copies if vital Libraries are unreachable
			if (!window.jQuery) { 
				console.log("{Error}: Loading jQuery failed, using local copy"); 
				document.write('<script src="assets/libs/jquery-2.1.4.min.js" type="text/javascript"><\/script>'); 
			} else {
				console.log("{Info}: jQuery loaded successfully :)");
				$(document).ready(function(){
					console.log("  _____      _       _           \r\n |  __ \\    | |     | |          \r\n | |__) |_ _| |_ ___| |__  _   _ \r\n |  ___\/ _` | __\/ __| \'_ \\| | | |\r\n | |  | (_| | || (__| | | | |_| |\r\n |_|   \\__,_|\\__\\___|_| |_|\\__, |\r\n                            __\/ |\r\n                           |___\/  <?php echo $version . '\n\n' . $githubLink; ?>\n\nMade By Jake-Cryptic\nWebsite: http://absolutedouble.co.uk");
				});
			}
		</script>
		
	</head>
	<body>
		
		<!--
			Running Patchy 3.2 (Darkness)
			
			Creator: Jake-Cryptic
			Website: http://absolutedouble.co.uk
			Github: http://github.com/jake-cryptic/patchy2
			Ultrapowa: http://www.ultrapowa.com/forum/members/xx_cryptic_xx.13591/
		-->
		
		<?php
			// Show different page depending on if user is logged in
			if(accountLoggedIn()) {
				if (!empty($_GET["p"])) {
					if ($_GET["p"] == "acc"){
						include("assets/php/views/account.php");
					} elseif ($_GET["p"] == "svr"){
						include("assets/php/views/servers.php");
					} elseif($_GET["p"] == "upl") {
						include("assets/php/views/upload.php");
					} elseif($_GET["p"] == "hlp") {
						include("assets/php/views/help.php");
					} elseif($_GET["p"] == "lgo") {
						 include("assets/php/scripts/logout.php");
					} elseif($_GET["p"] == "nfo") {
						include("assets/php/views/patchy.php");
					} else {
						include("assets/php/views/loggedIn.php");
					}
				} else {
					include("assets/php/views/loggedIn.php");
				}
			} else {
				include("assets/php/views/default.php");
			}
		?>
		
		<script type="text/javascript">
			$("#response_message").click(function(){
				$("#response_message").fadeOut(500);
			});
		</script>
		
	</body>
</html>