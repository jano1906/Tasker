<?php 
if(! session_start()){
	echo "Couldn't start session";
}
include_once('paths.php'); 
include_once($ConnScript);

$stid = oci_parse($conn, 
	"begin 
	new_user(:uname_bv, :upass_bv, :email_bv); 
	end;");
oci_bind_by_name($stid, ':uname_bv', $_POST["login"]);
oci_bind_by_name($stid, ':upass_bv', $_POST["password"]);
oci_bind_by_name($stid, ':email_bv', $_POST["email"]);
if(oci_execute($stid)){
	$_SESSION["loggedIn"] = true;
	$_SESSION["registerError"] = "";
	$_SESSION["uname"] = $_POST["login"];
	header("Location: $HelloSite");
} else {
	$_SESSION["registerError"] = "failed to register, try again";
	header("Location: $RegisterSite");
}


?>