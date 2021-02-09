<?php
if(! session_start()) {
	echo "Couldn't start session";
}
	include_once('paths.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
Welcome <?= $_SESSION['uname'] ?>! <br>
<a href="<?= $AllTeams?>"> All Teams </a> <br>
<a href="<?= $YourTeams?>"> Your Teams </a> <br>
<a href="<?= $TeamCreatorSite?>"> New Team </a> <br>
<a href="<?= $LogOut?>"> Log-out </a> <br>


</body>
</html>