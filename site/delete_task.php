<?php
if(! session_start()) {
	echo "Couldn't start session";
}
	include_once('paths.php');
	include_once($ConnScript);

$stid = oci_parse($conn, "begin
	delete_task(:name_bv);
	end;");
oci_bind_by_name($stid, ':name_bv', $_POST['name']);

if(!oci_execute($stid)){
	$_SESSION['deleteTaskError'] = 'Oops! Something went wrong';
	header("Location: $TasksSettingsSite");
} else {
	$_SESSION['deleteTaskError'] = '';
	header("Location: $TasksSettingsSite");
}
?>