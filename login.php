<?php   
	include("config.php"); //include the config
	include("user.php");
?>
<?php
	if($_SERVER['REQUEST_METHOD'] == "GET") {
		$name = $_GET['uname'];
		$pw = $_GET['pw'];
		$user = new User($name, $pw);
		$user->login($db);
	}
	else if($_SERVER['REQUEST_METHOD'] == "POST") {
		$name = $_POST['uname'];
		$pw = $_POST['pw'];
		$user = new User($name, $pw);
		$user->login($db);
	}
?>
