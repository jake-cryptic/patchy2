<?php 
	if (!isset($_SESSION["accountType"])) {
		die();
	} else{
		if($_SESSION["accountType"] == 4){
			echo '
		<a href="' . $fullPathToRoot . '?p=home" class="nav_link"><div class="nav_item left">Home</div></a>
		<a href="' . $fullPathToRoot . '?p=svr" class="nav_link"><div class="nav_item left">Servers</div></a>
		<a href="' . $fullPathToRoot . '?p=acc" class="nav_link"><div class="nav_item left">Account</div></a>
		<a href="' . $fullPathToRoot . '?p=lgo" class="nav_link"><div class="nav_item right">Logout</div></a>
		<a href="' . $fullPathToRoot . '?p=hlp" class="nav_link"><div class="nav_item right">Help</div></a>
		<a href="' . $fullPathToRoot . '?p=nfo" class="nav_link"><div class="nav_item right">About</div></a>
		<a href="' . $fullPathToRoot . 'admin.php" class="nav_link"><div class="nav_item right">Admin</div></a>
		';
		} else {
			echo '
		<a href="' . $fullPathToRoot . '?p=home" class="nav_link"><div class="nav_item left">Home</div></a>
		<a href="' . $fullPathToRoot . '?p=svr" class="nav_link"><div class="nav_item left">Servers</div></a>
		<a href="' . $fullPathToRoot . '?p=acc" class="nav_link"><div class="nav_item left">Account</div></a>
		<a href="' . $fullPathToRoot . '?p=lgo" class="nav_link"><div class="nav_item right">Logout</div></a>
		<a href="' . $fullPathToRoot . '?p=hlp" class="nav_link"><div class="nav_item right">Help</div></a>
		<a href="' . $fullPathToRoot . '?p=nfo" class="nav_link"><div class="nav_item right">About</div></a>
		';
		}
	}
?> 