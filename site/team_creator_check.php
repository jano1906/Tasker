<?php 
if(! session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php'); 
include_once($ConnScript);

$stid = oci_parse($conn, 
	"begin 
	new_team(:tname_bv, :uname_bv, :tpassw_bv); 
	end;");
oci_bind_by_name($stid, ':tname_bv', $_POST["tname"]);
oci_bind_by_name($stid, ':uname_bv', $_SESSION["uname"]);
oci_bind_by_name($stid, ':tpassw_bv', $_POST["tpassword"]);
if(!oci_execute($stid)){
	$_SESSION["teamCreatorError"] = "Couldn't create team, pick different name";
	header("Location: $TeamCreatorSite");
} else {
	header("Location: $HelloSite");
}


?>