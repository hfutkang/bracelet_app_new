<?php 
	include ("config.php");
	include ("device.php");

	if($_SERVER['REQUEST_METHOD'] == "GET") {
		$cmd = $_GET['cmd'];
	}
	else if($_SERVER['REQUEST_METHOD'] == "POST") {
		$cmd = $POST['cmd'];
	}

	switch ($cmd) {
		case 'reportPower':
			report_power($db);
			break;
		case 'queryCommand':
			$res = query_command($db);
			break;
		case 'reportPosition':
			report_position($db, 'gps');
			break;
		case 'reportLBS':
			report_position($db, 'base');
			break;
		case 'reportHR':
			report_hrate($db);
			break;
		case 'reportSport':
			report_sports($db);
			break;
		case 'reportSleep':
			report_sleep($db);
			break;
		case 'sos':
			report_sos($db);
			break;
	}

	$db->close();

	function report_power($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], $_GET['mac']);
			$device->power = $_GET['power'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], $_POST['mac']);
			$device->power = $_POST['power'];
		}
		$device->update_power($db);
	}

	function query_command($db) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], $_GET['mac']);
		}
		else {
			$device = new Device($_POST['id'], $_POST['mac']);
		}
		return $device->query_command($db);
	}

	function report_position($db, $type) {
		if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], null);
			$device->power = $_GET['voltage'];

			$position = new Postion();
			$position->type = $type;
			if($position->type == "gps") {
				$position->latitude = $_GET['lat'];
				$position->longitude = $_GET['lon'];
			}
			else {
				$position->mcc = $_GET['mcc'];
				$position->mnc = $_GET['mnc'];
				$position->lac = $_GET['lac'];
				$position->cid = $_GET['cid'];	
			}
		}
		else if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], null);
			$device->power = $_POST['voltage'];

			$position = new Position();
			$position->type = $type;
			if($position->type == "gps") {
				$position->latitude = $_POST['lat'];
				$position->longitude = $_POST['lon'];
			}
			else {
				$position->mcc = $_POST['mcc'];
				$position->mnc = $_POST['mnc'];
				$position->lac = $_POST['lac'];
				$position->cid = $_POST['cid'];
			}
		}
		$device->report_position($db, $position);
	}

	function report_hrate($db) {
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], null);
			$device->power = $_POST['voltage'];
			
			$hrate = new HeartRate();
			$hrate->rate = $_POST['heartrate'];
			$hrate->type = $_POST['type'];
			
		}
		else if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], null);
			$device->power = $_GET['voltage'];

			$hrate = new HeartRate();
			$hrate->rate = $_GET['heartrate'];
			$hrate->type = $_GET['type'];
		}
		$device->report_hrate($db, $hrate);
	}

	function report_sports($db) {
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], null);
			$device->power = $_POST['voltage'];

			$sport = new Sports();
			$sport->run = $_POST['run'];
			$sport->walk = $_POST['walk'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], null);
			$device->power = $_GET['voltage'];

			$sport = new Sports();
			$sport->run = $_GET['run'];
			$sport->walk = $_GET['walk'];
		}
		$device->report_sports($db, $sport);
	}

	function report_sleep($db) {
		if($_SERVER['REQUES_METHOD'] == "POST") {
			$device = new Device($_POST['id'], null);
			$device->power = $_POST['voltage'];

			$sleep = new Sleep();
			$sleep->start = $_POST['start'];
			$sleep->end = $_POST['end'];
			$sleep->total = $_POST['total'];
			$sleep->deep = $_POST['deep'];
			$sleep->shallow = $_POST['shallow'];
			$sleep->wakeTimes = $_POST['wake'];
		}
		else if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], null);
			$device->power = $_GET['voltage'];

			$sleep = new Sleep();
			$sleep->start = $_GET['start'];
			$sleep->end = $_GET['end'];
			$sleep->total = $_GET['total'];
			$sleep->deep = $_GET['deep'];
			$sleep->shallow = $_GET['shallow'];
			$sleep->wakeTimes = $_GET['wake'];
		}
		$device->report_sleep($db, $sleep);
	}

	function report_sos($db) {
		if($_SERVER['REQUEST_METHOD'] == "POST") {
			$device = new Device($_POST['id'], null);

			$msg = new Message();
			$msg->type = "sos";

		}
		else if($_SERVER['REQUEST_METHOD'] == "GET") {
			$device = new Device($_GET['id'], null);

			$msg = new Message();
			$msg->type = "sos";
		}
		
		$device->report_sos($db, $msg);
	}
?>
