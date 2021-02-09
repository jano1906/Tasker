<?php
if(!session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php');
include_once($ConnScript);
?>

<a href = <?= $HelloSite ?> > Back to hello site </a>

<?php
$stid = oci_parse($conn, 
	"SELECT teams.name FROM teams
    JOIN user_team ON teams.team_id = user_team.team_id
    JOIN users ON user_team.user_id = users.user_id
	WHERE users.name = :uname_bv");
oci_bind_by_name($stid, ':uname_bv', $_SESSION['uname']);
if(! oci_execute($stid)){
	echo "Oops! Something went wrong";
}

while(($row = oci_fetch_array($stid, OCI_NUM)) != false){
	echo "<form action='' method=POST>";
	echo "<input type=hidden name=tname value=$row[0]> <br>";
	echo "<input type=submit name=submit value=$row[0]> <br>";
	echo "</form>";
}

$_SESSION['tname'] = $_POST['tname'];
if($_SESSION['tname']){
	header("Location: $TeamSite");
}

?>