<?php 
	include ("config.php");
	include ("device.php");
	include ("user_device.php");
	include ("sync.php");
	include ("user.php");
?>
<?php 
	if($_SERVER['REQUEST_METHOD'] == "GET") {
		$cmd = $_GET['cmd'];
	}
	else if($_SERVER['REQUEST_METHOD'] == "POST") {
		$cmd = $_POST['cmd'];
	}
	switch ($cmd) {
	case 'addDevice':
		add_device($db);
		break;
	case 'updateDevice':
		update_device($db);
		break;
	case 'deleteDevice':
		delete_device($db);
		break;
	case 'locationOn':
		turn_location_on($db);
		break;
	case 'locationOff':
		turn_location_off($db);
		break;
	case 'measureHRate':
		measure_hrate($db);
	       break;	
	case 'newDevice':
		newDevice($db);
		break;
	case 'removeDevice':
		removeDevice($db);
		break;
	case 'syncAll':
		sync_all($db);
		break;
	case 'syncSingle':
		sync_single($db);
		break;
	case 'getPositionForUser':
		get_position_for_user($db);
		break;
	case 'getVerifyCode':
		get_verify_code($db);
		break;
	case 'changePassword':
		change_password($db);
		break;

	}

	$db->close();

	function sync_all($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$user = $_GET['uname'];
			$start = $_GET['start'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$user = $_POST['uname'];
			$start = $_POST['start'];
		}
		Sync::sync_data_all($db, $user, $start);
	}

	function sync_single($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$user = $_GET['id'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$user = $_POST['id'];
		}
		Sync::sync_data_single($db, $user);
	}

	
	function add_device($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new UserDevice($_GET['uname'], $_GET['id']);
			$device->name = $_GET['name'];
			$device->sex = $_GET['sex'];
			$device->age = $_GET['age'];
			$device->mac = $_GET['mac'];
			$device->weight = $_GET['weight'];
			$device->height = $_GET['height'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new UserDevice($_POST['uname'], $_POST['id']);
			$device->name = $_POST['name'];
			$device->sex = $_POST['sex'];
			$device->age = $_POST['age'];
			$device->mac = $_POST['mac'];
			$device->weight = $_POST['weight'];
			$device->height = $_POST['height'];
		}
		$device->add($db);
	}	

	function update_device($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new UserDevice($_GET['uname'], $_GET['id']);
			$device->mac = $_GET['mac'];
			$device->name = $_GET['name'];
			$device->sex = $_GET['sex'];
			$device->age = $_GET['age'];
			$device->weight = $_GET['weight'];
			$device->height = $_GET['height'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new UserDevice($_POST['uname'], $_POST['id']);
			$device->mac = $_POST['mac'];
			$device->name = $_POST['name'];
			$device->sex = $_POST['sex'];
			$device->age = $_POST['age'];
			$device->weight = $_POST['weight'];
			$device->height = $_POST['height'];
		}
		$device->update($db);
	}

	function delete_device($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new UserDevice($_GET['uname'], $_GET['id']);
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new UserDevice($_POST['uname'], $_POST['id']);
		}
		$device->delete($db);
	}

	function newDevice($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], $_GET['mac']);
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device= new Device($_POST['id'], $_POST['mac']);
		}
		$device->add($db);
	}

	function removeDevice($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], $_GET['mac']);
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], $_POST['mac']);
		}
		$device->delete($db);
	}

	function measure_hrate($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], $_GET['mac']);
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], $POST['mac']);
		}
		$device->measure_hrate($db);
	}

	function turn_location_on($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], $_GET['mac']);
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], $_POST['mac']);
		}
		$device->turn_location_on($db);
	}


	function turn_location_off($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], $_GET['mac']);
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], $_POST['mac']);
		}
		$device->turn_location_off($db);
	}

	function get_position_for_user($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$user = $_GET['uname'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$user = $_POST['uname'];
		}
		Sync::get_latest_position_for($db, $user);
	}

	function get_verify_code($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$name = $_GET['uname'];
		}
		else if($_SERVER['REQUES_METHOD'] == "POST") {
			$name = $_POST['uname'];
		}
		User::get_verify_code($db, $name);
	}

	function change_password($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$name = $_GET['uname'];
			$password = $_GET['pw'];
			$vcode = $_GET['code'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$name = $_POST['uname'];
			$password = $_POST['pw'];
			$vcode = $_POST['code'];
		}
		$user = new User($name, $password);
		$user->change_password($db, $vcode);
	}
?>
