<?php
if (!empty($_POST)) {
	$msg = htmlspecialchars($_POST["messageSupport"]) . "\n\n";
	$help = fopen("../../../help_tmp.txt","a+") or die("Error (400)");
	fwrite($help, $msg);
	fclose($help);
	header("Location: ../../../?p=hlp&msg=1");
} else {
	die("Error (400)#1");
}
?>