<?php
if(! session_start()) {
	echo "Couldn't start session";
}
	include_once('paths.php');
	include_once($ConnScript);

$stid = oci_parse($conn, "begin
	new_task(:taskName_bv, :teamName_bv, :fName_bv, :sName_bv);
	end;");
oci_bind_by_name($stid, ':taskName_bv', $_POST['name']);
oci_bind_by_name($stid, ':teamName_bv', $_SESSION['tname']);
oci_bind_by_name($stid, ':fName_bv', $_POST['fname']);
oci_bind_by_name($stid, ':sName_bv', $_POST['sname']);

if(!oci_execute($stid)){
	$_SESSION['addTaskError'] = 'Oops! Something went wrong';
	header("Location: $TasksSettingsSite");
} else {
	$_SESSION['addTaskError'] = '';
	header("Location: $TasksSettingsSite");
}
?>