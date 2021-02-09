<?php
if(! session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php'); 
include_once($ConnScript);

$stid = oci_parse($conn, "begin
	make_admin( :uname_bv, :tname_bv);
	end;");
oci_bind_by_name($stid, ':uname_bv', $_POST['uname']);
oci_bind_by_name($stid, ':tname_bv', $_SESSION['tname']);
if(!oci_execute($stid)){
	echo "Oops! Something went wrong";
} else{
	header("Location: $TeamUsers");
}
?>