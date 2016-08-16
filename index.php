<?php
	session_start();
?>
<?php
if(isset($_SESSION['username']))
{
	echo "Welcome ".$_SESSION['username'];
?>
<a href="logout.php">Logout</a>
<?php
}else {
	header("location:login.php");
	exit();
}
?>
