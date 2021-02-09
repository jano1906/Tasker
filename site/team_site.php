<?php
if(!session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php');
include_once($ConnScript);

$stid = oci_parse($conn, "begin 
	:res_bv := is_admin(:uname_bv, :tname_bv);
	end;");
oci_bind_by_name($stid, ':res_bv', $_SESSION['isAdmin'], -1, SQLT_INT);
oci_bind_by_name($stid, ':uname_bv', $_SESSION['uname']);
oci_bind_by_name($stid, ':tname_bv', $_SESSION['tname']);
if(!oci_execute($stid)){
	echo "Oops! Something went wrong";
}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?= $_SESSION['tname'] ?> <br><br>
<a href="<?= $TeamUsers?>"> Users </a> <br>
<a href="<?= $TeamCallendar?>"> Callendar </a> <br>
<br>
<a href="<?= $YourTeams?>"> Back to your teams </a> <br>



</body>
</html>