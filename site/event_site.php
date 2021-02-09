<?php
if(!session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php');
include_once($ConnScript);
?>

<a href=<?= $TeamCallendar ?>> Back to Callendar </a> <br>

<?php
$stid = oci_parse($conn, 
	"SELECT users.name, users.email FROM users
    JOIN user_event ON users.user_id = user_event.user_id
    WHERE user_event.event_id = :eid_bv");
oci_bind_by_name($stid, ':eid_bv', $_SESSION['eid']);
if(! oci_execute($stid)){
	echo "Oops! Something went wrong";
} else {

	while(($row = oci_fetch_array($stid, OCI_NUM)) != false){
		echo "$row[0]";
		echo "<br>";
	}
}

?>