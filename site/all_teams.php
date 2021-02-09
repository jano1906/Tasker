<?php
include_once('paths.php');
include_once($ConnScript);
$_SESSION['tname'] = "";
$_SESSION["joinTeamError"] = "";
?>

<a href = <?= $HelloSite ?> > Back to hello site </a>

<?php
$stid = oci_parse($conn, 
	"SELECT teams.name FROM teams
	ORDER BY teams.name");
if(! oci_execute($stid)){
	echo "wtf wrong!";
}
while(($row = oci_fetch_array($stid, OCI_NUM)) != false){
	echo "<form action=$JoinTeamSite method=POST>";
	echo "<input type=hidden name=tname value=$row[0]> <br>";
	echo "<input type=submit name=submit value=$row[0]> <br>";

	echo "</form>";

}

?>