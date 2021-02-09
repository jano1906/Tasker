<?php
if(! session_start()) {
	echo "Couldn't start session";
}
	include_once('paths.php');
	include_once($ConnScript);

$stid = oci_parse($conn, "begin
	rename_task(:fName_bv, :tName_bv);
	end;");
oci_bind_by_name($stid, ':fName_bv', $_POST['fname']);
oci_bind_by_name($stid, ':tName_bv', $_POST['tname']);

if(!oci_execute($stid)){
	$_SESSION['renameTaskError'] = 'Oops! Something went wrong';
	header("Location: $TasksSettingsSite");
} else {
	$_SESSION['renameTaskError'] = '';
	header("Location: $TasksSettingsSite");
}
?>