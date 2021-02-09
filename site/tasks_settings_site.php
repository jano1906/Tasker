Task list: <br> <br>

<?php
if(! session_start()) {
	echo "Couldn't start session";
}
	include_once('paths.php');
	include_once($ConnScript);

$stid = oci_parse($conn, "
	WITH taskTree(id, name, num) AS (
    SELECT tasks.task_id, tasks.name, 0
    FROM tasks 
    WHERE father_id IS NULL 
    	AND team_id = (SELECT team_id FROM teams WHERE name = '$_SESSION[tname]')
    UNION ALL
    SELECT tasks.task_id, tasks.name, taskTree.num+1
    FROM tasks JOIN taskTree ON father_id = taskTree.id
    ) SELECT name, num FROM taskTree");
if(!oci_execute($stid)){
	echo "Oops! Something went wrong";
} else {
	$cur = 0;
	while(($row = oci_fetch_array($stid)) != false){
		if($row[1] > $cur){
			$cur = $row[1];
			echo "<br>";
		}
		echo "'$row[0]'"." ";
	}
}
?>
<br> <br>
<form action=<?= $RenameTask ?> method=POST>
	Rename task:
	<input name='fname' type='text' placeholder='From...'> 
	<input name='tname' type='text' placeholder='To...'> 
	<input name='submit' type='submit'> <br>
<?= $_SESSION['renameTaskError'] ?>
</form>
<form action=<?= $AddTask ?> method=POST>
	Add task:
	<input name='name' type='text' placeholder='Name...'> 
	<input name='fname' type='text' placeholder='Father...'> 
	<input name='sname' type='text' placeholder='Son...'> 
	<input name='submit' type='submit'> <br>
<?= $_SESSION['addTaskError'] ?>
</form>
<form action=<?= $DeleteTask ?> method=POST>
	Delete task:
	<input name='name' type='text' placeholder='Name...'> 
	<input name='submit' type='submit'> <br>
<?= $_SESSION['deleteTaskError'] ?>
</form>
<br>
<a href=<?= $TeamCallendar ?> > Back to callendar </a>