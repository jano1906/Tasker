<?php
if(!session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php');
include_once($ConnScript);
?>

<a href=<?= $TeamSite?>> Back to team site </a> <br><br>

<?php
$stid = oci_parse($conn, 
	"SELECT users.name FROM teams
    JOIN user_team ON teams.team_id = user_team.team_id
    JOIN users ON user_team.user_id = users.user_id
	WHERE teams.name = :tname_bv");
oci_bind_by_name($stid, ':tname_bv', $_SESSION['tname']);
if(! oci_execute($stid)){
	echo "Oops! Something went wrong";
} else {

	while(($row = oci_fetch_array($stid, OCI_NUM)) != false){
		echo "$row[0]";
		
		$res = 0;
		$stid2 = oci_parse($conn, "begin 
				:res_bv := is_admin(:uname_bv, :tname_bv);
				end;");
		oci_bind_by_name($stid2, ':res_bv', $res, -1, SQLT_INT);
		oci_bind_by_name($stid2, ':uname_bv', $row[0]);
		oci_bind_by_name($stid2, ':tname_bv', $_SESSION['tname']);
		if(!oci_execute($stid2)){
			echo "Oops! Something went wrong";
		}
		
		if($res == 1){
			echo " (admin)";
		}

		if($_SESSION['isAdmin']){

			echo "<form action=$MakeAdmin method=POST>";
			echo "<input type=hidden name=uname value=$row[0]>";
			echo "<input type=submit name=submit value='Make Admin'>";
			echo "</form>";
		} else {
			echo "<br>";
		}
	}
}

?>