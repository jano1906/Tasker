<?php
$file_path = '/home/students/inf/j/jo418361/db_passes.php';
if(file_exists($file_path)){
	include_once($file_path);
	$conn = oci_connect($uname, $passw, $db);
	if (!$conn) {
    	$e = oci_error();
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}	
} else {
	echo "error, file with passes to db not found";
}
?>