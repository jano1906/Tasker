<?php 
if(! session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php'); 
include_once($ConnScript);

$res = 0;

$stid = oci_parse($conn, 
	"begin 
	:res_bv := user_cnt(:uname_bv, :upass_bv); 
	end;");
oci_bind_by_name($stid, ':uname_bv', $_POST["login"]);
oci_bind_by_name($stid, ':upass_bv', $_POST["password"]);
oci_bind_by_name($stid, ':res_bv', $res, -1, SQLT_INT);
oci_execute($stid);

if($res > 0){
	$_SESSION["loggedIn"] = true;
	$_SESSION["logInError"] = "";
	$_SESSION["uname"] = $_POST["login"];
	header("Location: $HelloSite");
} else {
	$_SESSION["logInError"] = "failed to log in, try again";
	header("Location: $LogInSite");
}


?>