<?php 
include_once('paths.php'); 
if(! session_start()){
	echo "Couldn't start session";
}
?>

<?php 
	if($_POST['tname']){
		$_SESSION['tname'] = $_POST['tname'];
	}
?>
<?= $_SESSION['tname']?>
<form action="<?= $JoinTeamCheck ?>" method="POST">
	<input type="password" name="password"> Password <br>
	<input type=hidden name=tname value=<?= $_SESSION['tname']?>>
	<input type="submit" name="submit"> <br>
	<?= $_SESSION["joinTeamError"] ?>
</form>

<a href=<?= $AllTeams?> > Back to all teams </a>
