<?php 
if(! session_start()) {
	echo "Couldn't start session";
}
include_once('paths.php'); 
include_once($ConnScript);

$res = 0;

$stid = oci_parse($conn, 
	"begin 
	:res_bv := team_cnt(:tname_bv, :tpass_bv); 
	end;");
oci_bind_by_name($stid, ':tname_bv', $_POST["tname"]);
oci_bind_by_name($stid, ':tpass_bv', $_POST["password"]);
oci_bind_by_name($stid, ':res_bv', $res, -1, SQLT_INT);
oci_execute($stid);

if($res > 0){
	$stid = oci_parse($conn, 
		"begin 
		add_user_to_team(:uname_bv, :tname_bv); 
		end;");
	oci_bind_by_name($stid, ':uname_bv', $_SESSION["uname"]);
	oci_bind_by_name($stid, ':tname_bv', $_POST["tname"]);
	oci_execute($stid);

	header("Location: $HelloSite");
} else {
	$_SESSION["joinTeamError"] = "failed to join team, try again";
	header("Location: $JoinTeamSite");
}

?>