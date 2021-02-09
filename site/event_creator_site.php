<?php 
include_once('paths.php'); 
include_once($ConnScript); 
if(! session_start()){
	echo "Couldn't start session";
}
?>

<form action="<?= $EventCreatorCheck ?>" method="POST">
	<input list="tasks" name="ename"> Name <br>
	<input type="datetime-local" name="fdate"> From <br>
	<input type="datetime-local" name="tdate"> To <br>
	<input type="submit" name="submit"> <br>
	<?= $_SESSION["eventCreatorError"] ?>


<?php
$stid = oci_parse($conn,
	"SELECT tasks.name 
	FROM tasks 
	JOIN teams ON tasks.team_id = teams.team_id
	START WITH teams.name = '$_SESSION[tname]' AND tasks.father_id IS NULL
	CONNECT BY PRIOR tasks.task_id = tasks.father_id");
if(!oci_execute($stid)){
	echo "Oops! Something went wrong";
}
?>

<datalist id="tasks">
	<?php
		while(($row = oci_fetch_array($stid, OCI_NUM)) != false){
			echo "<option value=$row[0]>";
		}
	?>
</datalist>

</form>