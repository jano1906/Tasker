<?php 
include_once('paths.php'); 
if(! session_start()){
	echo "Couldn't start session";
}
?>


<form action="<?= $RegisterCheck ?>" method="POST">
	<input type="text" name="login"> Login <br>
	<input type="password" name="password"> Password <br>
	<input type="text" name="email"> Email <br>
	<input type="submit" name="submit">
	<?= $_SESSION["registerError"]?>
	

</form>