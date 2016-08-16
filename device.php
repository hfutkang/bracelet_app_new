<?php
	require_once("geTuiUtils.php");
	require_once("smpp/smppclass.php");
	class Device{
		public $id;
		public $mac;
		public $power;
		public $location_on;
		public $measure_hrate;

		const MIN_POWER = 10;

		public function __construct($id, $mac) {
			$this->id = $id;
			$this->mac = $mac;
		}

		public function add($db) {
			$queryStr = "insert into bracelet (device_id, mac) 
					values('$this->id', '$this->mac')";
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				if($db->errno == 1062)
					$res = 400;
				else
					$res = 300;
			}
			header("Content-type:text/xml");
			printf("<addDevice>\n");
			printf("<res>%d</res>", $res);
			printf("</addDevice>");
		}

		public function delete($db) {
			$queryStr = "delete from bracelet where device_id = '$this->id'";
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				$res = 300;
			}
			header("Content-type:text/xml");
			printf("<deleteDevice>\n");
			printf("<res>%d</res>\n", $res);
			printf("</deleteDevice>\n");
		}

		public function update_power($db) {
			$queryStr = "update bracelet set power='$this->power' where device_id='$this->id'";
			$db->query($queryStr);
			
			if($this->power <= self::MIN_POWER) {
				$time = new DateTime();
				$timeStr = $time->format('Y-m-d H:i');
				$msg = 'deviceId='.$this->id.'&type=low_power&'.'time='.$timeStr.'&msg='.$this->power;
				
				$queryStr = "insert into message (device_id, type, time, content) values ('$this->id', 'low_power', '$timeStr', '$this->power')
					on duplicate key update type = 'low_power', msg = '$this->power'";

				GeTuiUtils::pushMessageToApp($this->id, $msg);
			}

		}

		public function turn_location_on($db) {
			$result = $db->query("select runtime_location from bracelet where device_id='$this->id'");
			$row = $result->fetch_array();
			$this->location_on = $row[0] + 1;
			$queryStr = "update bracelet set runtime_location = $this->location_on where device_id='$this->id'";
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				$res = 300;
			}
			$this->send_smpp_msg("turn location on");
			header("Content-type:text/xml");
			printf("<locationOn>\n");
			printf("<res>%d</res>\n", $res);
			printf("</locationOn>\n");
		}

		public function turn_location_off($db) {
			$result = $db->query("select runtime_location from bracelet where device_id = '$this->id'");
			$row = $result->fetch_array();
			$this->location_on = $row[0] - 1;
			$queryStr = "update bracelet set runtime_location = $this->location_on where device_id = '$this->id'";
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				$res = 300;
			}
			header("Content-type:text/xml");
			printf("<locationOff>\n");
			printf("<res>%d</res>\n", $res);
			printf("</locationOff>\n");
		}

		public function measure_hrate($db) {
			$queryStr = "update bracelet set measure_hrate = 'yes' where device_id = '$this->id'";
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				$res = 300;
			}
			$this->send_smpp_msg("measure hearte rate");
			header("Content-type:text/xml");
			printf("<measureHRate>\n");
			printf("<res>%d</res>", $res);
			printf("</measureHRate>");
		}

		public function query_command($db) {
			$queryStr = "select runtime_location, measure_hrate from bracelet where device_id = '$this->id'";
			$res = 100;
			if($result = $db->query($queryStr)) {
				$row = $result->fetch_row();
				if($row[0] > 0 )
					$res += 1;
				if($row[1] == "yes") {
					$res += 2;
					$db->query("update bracelet set measure_hrate = 'no' where device_id='$this->id'");
				}		
			}
			return $res;
		}

		public function report_position($db, $position) {
			$queryStr = "insert into position (device_id, latitude, longitude, mcc, mnc, lac, cid, time, type) 
					values ('$this->id', '$position->latitude', '$position->longitude', '$position->mcc', 
					'$position->mnc', '$position->lac', '$position->cid', '$position->time', '$position->type') 
					on duplicate key update latitude = '$position->latitude', longitude = '$position->longitude', 
					mcc = '$position->mcc', mnc = '$position->mnc', lac = '$position->lac', cid = '$position->cid', 
					type = '$position->type'";
			
			$db->query($queryStr);
			
			$this->update_power($db);

			$res = $this->query_command($db);

			header("Content-type:text/xml");
			printf("<reportPosition>\n");
			printf("<res>%d</res>\n", $res);
			printf("</reportPosition>\n");
		}

		public function report_hrate($db, $hrate) {
			$queryStr = "insert into heart_rate (device_id, rate, time, type) values ('$this->id', '$hrate->rate', 
					'$hrate->time', '$hrate->type') on duplicate key update rate = '$hrate->rate', type = '$hrate->type'";
			
			$db->query($queryStr);

			$this->update_power($db);

			$res = $this->query_command($db);

			if($hrate->type == "App") {
				$msg = 'deviceId='.$this->id.' '.'&type=hrate&time='.$hrate->time.'&msg='.$hrate->rate;
				GeTuiUtils::pushMessageToApp($this->id, $msg);
			}

			header("Content-type:text/xml");
			printf("<reportHR>\n");
			printf("<res>%d</res>\n", $res);
			printf("</reportHR>\n");
		}

		public function report_sleep($db, $sleep) {
			$queryStr = "insert into sleep (device_id, total_time, deep_time, shallow_time, wake_times, start_time, end_time) 
					values ('$this->id', '$sleep->total', '$sleep->deep', '$sleep->shallow', '$sleep->wakeTimes', '$sleep->start', '$sleep->end') 
					on duplicate key update total_time = '$sleep->total', deep_time = '$sleep->deep', shallow_time = '$sleep->shallow', wake_times = 
					'$sleep->wakeTimes', end_time = '$sleep->end'";
			
			$db->query($queryStr);

			$this->update_power($db);

			$res = $this->query_command($db);

			header("Content-type:text/xml");
			printf("<reportSleep>\n");
			printf("<res>%d</res>\n", $res);
			printf("</reportSleep>\n");
		}

		public function report_sports($db, $sports) {
			$queryStr = "insert into sports (device_id, run_steps, walk_steps, time) values ('$this->id', '$sports->run', '$sports->walk', '$sports->time') 
					on duplicate key update run_steps = '$sports->run', walk_steps = '$sports->walk'";

			$db->query($queryStr);

			$this->update_power($db);

			$res = $this->query_command($db);

			header("Content-type:text/xml");
			printf("<reportSport>\n");
			printf("<res>%d</res>\n", $res);
			printf("</reportSport>");
		}

		public function report_sos($db, $msg) {
			$queryStr = "insert into message (device_id, type, time, content) values ('$this->id', '$msg->type', '$msg->time', '$msg->content')
					on duplicate key update type = '$msg->type', content = '$msg->content'";
			
			$db->query($queryStr);

			$msg = 'deviceId='.$this->id.'&type='.$msg->type.'&time='.$msg->time.'&msg='.$msg->conent;

			GeTuiUtils::pushMessageToApp($this->id, $msg);

			//header("Content-type:text/xml");
			printf("<sos>\n");
			printf("<res>0</res>\n");
			printf("</sos>\n");
		}

		private function send_smpp_msg($msg) {
			$smpp = new SMPPClass();
			$smpp->Start();
			$res = $smmp->Send_msg($this->id, $msg);
			$smpp->End();
			return $res;
		}

	}

	class Postion {
		
		public $latitude;
		public $longitude;
		public $mcc;
		public $mnc;
		public $lac;
		public $cid;
		public $time;
		public $type;

		public function __construct() {
			$time = new DateTime();
			$this->time = $time->format('Y-m-d H:i');

			$this->latitude = null;
			$this->longitude = null;
			$this->mcc = null;
			$this->mnc = null;
			$this->lac = null;
			$this->cid = null;
		}
	}

	class HeartRate {
		public $rate;
		public $time;
		public $type;

		public function __construct() {
			$time = new DateTime();
			$this->time = $time->format('Y-m-d H:i');
		}
	}

	class Sports {
		public $run;
		public $walk;
		public $time;
		
		public function __construct() {
			$time = new DateTime();
			$this->time = $time->format('Y-m-d');
		}
	}

	class Sleep {
		public $start;
		public $end;
		public $total;
		public $deep;
		public $shallow;
		public $wakeTimes;
	}

	class message {
		public $time;
		public $type;
		public $content;

		public function __construct() {
			$time = new DateTime();
			$this->time = $time->format('Y-m-d H:i');
			$this->content = null;
		}
	}
?>
