<?php
session_unset("user");
@setcookie("PatchyID","",time()-3600);

echo "<h1>Logged out";
echo "<br /> Redirecting..</h1><script> window.location.href='$fullPathToRoot'; </script>";
die();
?>