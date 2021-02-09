<?php
if(! session_start()) {
	echo "Couldn't start session";
}
	include_once('paths.php');
	include_once($ConnScript);
?>

<?php
function evalT($r){
	return "(tasks.name IN (SELECT tasks.name
		FROM tasks
		START WITH task_id = get_task_id('$r', '$_SESSION[tname]')
		CONNECT BY PRIOR tasks.task_id = tasks.father_id))";}



function evalU($r){
	$res.="(";
	$list = explode(",", $r);

	foreach($list as $name){
		$name = trim($name);
		$res.="users.name like '$name' OR ";
	}
	$res = substr($res, 0, -3);
	return $res.")";
} 
function evalDPIT($r){
	$res.="(";

	$res.="TO_DATE('$r', 'yyyy-mm-dd hh24:mi') BETWEEN events.e_from AND events.e_to";

	return $res.")";	
}
function evalDRIT($r){
	$res.="(";
	$arr = explode(",", $r);
	$arr[0] = trim($arr[0]);
	$arr[1] = trim($arr[1]);
	
	$res.="(TO_DATE('$arr[0]', 'yyyy-mm-dd hh24:mi') BETWEEN events.e_from AND events.e_to)";
	$res.= " AND ";
	$res.="(TO_DATE('$arr[1]', 'yyyy-mm-dd hh24:mi') BETWEEN events.e_from AND events.e_to)";

	return $res.")";
}

function evalTIDR($r){
	$res.="(";
	$arr = explode(",", $r);
	$arr[0] = trim($arr[0]);
	$arr[1] = trim($arr[1]);

	$res.="(events.e_from BETWEEN TO_DATE('$arr[0]', 'yyyy-mm-dd hh24:mi') AND TO_DATE('$arr[1]', 'yyyy-mm-dd hh24:mi') )";
	$res.= " AND ";
	$res.="(events.e_to BETWEEN TO_DATE('$arr[0]', 'yyyy-mm-dd hh24:mi') AND TO_DATE('$arr[1]', 'yyyy-mm-dd hh24:mi') )";

	return $res.")";
}

function evalExpr($expr){
	$logic = [
		'&' => ' AND ',
		'|' => ' OR ',
		'^' => ' XOR '
	];
	if(array_key_exists($expr, $logic)){
		return $logic[$expr];
	}
	$arr = explode("?", $expr);
	$l = trim($arr[0]);
	$r = trim($arr[1]);

	switch($l){
		case 'T':
			return evalT($r);
		case 'U':
			return evalU($r);		
		case 'DPIT':
			return evalDPIT($r);	
		case 'DRIT':
			return evalDRIT($r);	
		case 'TIDR':
			return evalTIDR($r);
	}

	return "";
}

function parseToSQL($input){
	$input = trim($input);
	if($input == ""){
		return "";
	}
$base = "
SELECT DISTINCT events.event_id, tasks.name, TO_CHAR(events.e_from, 'yyyy-mm-dd hh24:mi'), TO_CHAR(events.e_to, 'yyyy-mm-dd hh24:mi'), events.e_from, events.e_to
FROM users
JOIN user_event ON users.user_id = user_event.user_id
JOIN events ON user_event.event_id = events.event_id
JOIN tasks ON events.task_id = tasks.task_id
JOIN teams ON tasks.team_id = teams.team_id
WHERE teams.name = :tname_bv AND ";
	
	$res = $base;
	$larr = explode("[", $input);
	foreach($larr as $l){
		$arr = explode("]", $l);
		foreach ($arr as $expr) {
			$expr = trim($expr);
			if($expr == 'ALL'){
				return substr($base, 0, -5);
			}
			$res.=evalExpr($expr);
		}
	}
	return $res;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<a href="<?= $EventCreatorSite?>"> New Event </a> <br>
<?php
	if($_SESSION['isAdmin']){
		echo "<a href=$TasksSettingsSite> Tasks Settings </a> <br>";		
	}
?>
<br>
<a href="<?= $TeamSite?>"> Back to team site </a> <br>
<form action="" method="POST">
	<input style="width: 700px" name="search" type="text" placeholder="Search..."> <br>
</form>
<br>

<?php
	$sql = parseToSQL($_POST['search']);
	if($sql != ""){
		$sql.=" ORDER BY events.e_from, events.e_to";
		
		$stid = oci_parse($conn, $sql);
		oci_bind_by_name($stid, ':tname_bv', $_SESSION["tname"]);
		if(! oci_execute($stid)){
			echo "bad syntax";
		} else {
			echo "<table>";
			echo "<tr>";
			echo "<th></th>";
			echo "<th> event id </th>";
			echo "<th> task name </th>";
			echo "<th> from </th>";
			echo "<th> to </th>";
			echo "</tr>";
			while(($row = oci_fetch_array($stid, OCI_NUM)) != false){
				echo "<tr>";
				echo "<td>";
				echo "<form action='' method=POST>";
	  			echo "<input type=submit value=join name=join>";
	  			echo "<input type=submit value=view name=view>";
	  			echo "<input type=hidden value=$row[0] name=eid>";
				echo "</td>";
				echo "<td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td>";
				echo "</form>";

				echo "</tr>";
			}
			echo "</table>";
		}
	}

	if($_POST['join']){
		$stid = oci_parse($conn, "begin
			add_user_to_event(:uname_bv, :eid_bv);
			end;");
		oci_bind_by_name($stid, ':uname_bv', $_SESSION['uname']);
		oci_bind_by_name($stid, ':eid_bv', $_POST['eid']);
		if(oci_execute($stid)){
			echo "Joined event $_POST[eid]!";
		}
	}
	if($_POST['view']){
		$_SESSION['eid'] = $_POST['eid'];
		header("Location: $EventSite");
	}
?>

</body>
</html>