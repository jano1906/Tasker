<?php 
include_once('paths.php'); 
if(! session_start()){
	echo "Couldn't start session";
}
?>

<a href = <?= $HelloSite ?> > Back to hello site </a>
<form action="<?= $TeamCreatorCheck ?>" method="POST">
	<input type="text" name="tname"> Name <br>
	<input type="password" name="tpassword"> Password <br>
	<input type="submit" name="submit"> <br>

	<?= $_SESSION["teamCreatorError"] ?>
	

</form>