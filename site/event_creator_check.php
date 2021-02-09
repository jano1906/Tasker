<?php 
if(! session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php'); 
include_once($ConnScript);


$stid = oci_parse($conn, 
	"begin 
	new_event(TO_DATE(:fdate_bv, 'yyyy-mm-dd hh24:mi'), TO_DATE(:tdate_bv, 'yyyy-mm-dd hh24:mi'), :ename_bv, :uname_bv);     
	end;");

$fdate = date_create($_POST["fdate"]);
$tdate = date_create($_POST["tdate"]);
$fdate = date_format($fdate,"Y-m-d H:i");
$tdate = date_format($tdate,"Y-m-d H:i");

oci_bind_by_name($stid, ':fdate_bv', $fdate);
oci_bind_by_name($stid, ':tdate_bv', $tdate);
oci_bind_by_name($stid, ':ename_bv', $_POST["ename"]);
oci_bind_by_name($stid, ':uname_bv', $_SESSION["uname"]);


if(oci_execute($stid)){
	$_SESSION["eventCreatorError"] = "";
	header("Location: $TeamCallendar");
} else {
	$_SESSION["eventCreatorError"] = "failed to create event";
	header("Location: $EventCreatorSite");
}

echo "lul";
?>