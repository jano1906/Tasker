<?php 
include_once('paths.php'); 
if(! session_start()){
	echo "Couldn't start session";
}
if($_SESSION["loggedIn"]){
	header("Location: $HelloSite");
}
?>

<form action="<?= $LogInCheck ?>" method="POST">
	<input type="text" name="login"> Login <br>
	<input type="password" name="password"> Password <br>
	<input type="submit" name="submit"> <br>
	<?= $_SESSION["logInError"] ?>
	

</form>
