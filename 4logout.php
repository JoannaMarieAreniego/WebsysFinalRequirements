<?php require("0conn.php")?>

<?php
session_start();
session_destroy();
echo "You just logged out...";
header("Refresh: 2, url=3login.php");
?>
