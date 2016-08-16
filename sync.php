<?php 
	class Sync {
		public static function sync_data_all($db, $user, $start) {
			header("Content-type:text/xml");
			printf("<syncAll>\n");
			Sync::query_hrate_all($db, $user, $start);
			Sync::query_sports_all($db, $user, $start);
			Sync::query_sleep_all($db, $user, $start);
			Sync::query_position_all($db, $user, $start);
			Sync::query_message_all($db, $user, $start);	
			printf("</syncAll>\n");
		}

		public static function sync_data_single($db, $id) {
			header("Content-type:text/xml");
			printf("<syncSingle>\n");
			Sync::query_hrate_single($db, $id);
			Sync::query_sports_single($db, $id);
			Sync::query_sleep_single($db, $id);
			Sync::query_position_single($db, $id);
			Sync::query_message_single($db, $id);	
			printf("</syncSingle>\n");
			
		}

		public static function query_hrate_all($db, $user, $start) {
			$queryStr = "select heart_rate.device_id, time, rate, type from heart_rate inner join user_bracelet 
					on heart_rate.time > '$start' 
					and user_bracelet.device_id=heart_rate.device_id 
					and user_bracelet.user_id='$user'; 
			$result = $db->query($queryStr);
			printf("<heartrates>\n");
			while($row = $result->fetch_row()) {
				printf("<heartrate>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<rate>%d</rate>\n", $row[2]);
				printf("<type>%s</type>\n", $row[3]);
				printf("</heartrate>\n");	
			}
			printf("</heartrates>\n");
		}

		public static function query_hrate_single($db, $id) {
			$queryStr = "select device_id, time, rate, type from heart_rate 
					where device_id = '$id'";
			$result = $db->query($queryStr);
			printf("<heartrates>\n");
			while($row = $result->fetch_row()) {
				printf("<heartrate>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<rate>%d</rate>\n", $row[2]);
				printf("<type>%s</type>\n", $row[3]);
				printf("</heartrate>\n");	
			}
			printf("</heartrates>\n");
		}


		public static function query_sports_all($db, $user, $start) {
			$queryStr = "select sports.device_id, time, run_steps, walk_steps from sports inner join 
					on sports.time > '$start' 
					and sports.device_id=user_bracelet.device_id 
					and user_bracelet.user_id='$user'; 
			$result = $db->query($queryStr);
			printf("<sports>\n");
			while($row = $result->fetch_row()) {
				printf("<sport>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<run>%d</run>\n", $row[2]);
				printf("<walk>%d</walk>\n", $row[3]);
				printf("</sport>\n");
			}
			printf("</sports>\n");
		}

		public static function query_sports_single($db, $id) {
			$queryStr = "select device_id, time, run_steps, walk_steps from sports 
					where device_id = '$id'";
			$result = $db->query($queryStr);
			printf("<sports>\n");
			while($row = $result->fetch_row()) {
				printf("<sport>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<run>%d</run>\n", $row[2]);
				printf("<walk>%d</walk>\n", $row[3]);
				printf("</sport>\n");
			}
			printf("</sports>\n");
		}


		public static function query_sleep_all($db, $user, $start) {
			$queryStr = "select sleep.device_id, start_time, end_time, total_time, deep_time, shallow_time, wake_times from sleep inner join user_braclet
					on sleep.start_time > '$start' 
					and user_bracelet.device_id=sleep.device_id 
					and user_bracelet.user_id='$user'; 
			$result = $db->query($queryStr);
			printf("<sleeps>\n");
			while($row = $result->fetch_row()) {
				printf("<sleep>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<start>%s</start>\n", $row[1]);
				printf("<end>%s</end>", $row[2]);
				printf("<total>%d</total>\n", $row[3]);
				printf("<deep>%d</deep>\n", $row[4]);
				printf("<shallow>%d</shallow>\n", $row[5]);
				printf("<wake>%d</wake>\n", $row[6]);
				printf("</sleep>\n");
			}
			printf("</sleeps>\n");
		}

		public static function query_sleep_single($db, $id) {
			$queryStr = "select device_id, start_time, end_time, total_time, deep_time, shallow_time, wake_times from sleep 
					where device_id = '$id'";
			$result = $db->query($queryStr);
			printf("<sleeps>\n");
			while($row = $result->fetch_row()) {
				printf("<sleep>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<start>%s</start>\n", $row[1]);
				printf("<end>%s</end>", $row[2]);
				printf("<total>%d</total>\n", $row[3]);
				printf("<deep>%d</deep>\n", $row[4]);
				printf("<shallow>%d</shallow>\n", $row[5]);
				printf("<wake>%d</wake>\n", $row[6]);
				printf("</sleep>\n");
			}
			printf("</sleeps>\n");
		}


		public static function query_position_all($db, $user, $start) {
			$queryStr = "select position.device_id, time, latitude, longitude, mcc, mnc, lac, cid, type from position inner join user_bracelet 
					on positon.time > '$start'
					and user_bracelet.device_id=position.device_id 
					and user_bracelet.user_id='$user'; 
			$result = $db->query($queryStr);
			printf("<positions>\n");
			while($row = $result->fetch_row()) {
				printf("<position>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<lat>%s</lat>\n", $row[2]);
				printf("<lon>%s</lon>\n", $row[3]);
				printf("<mcc>%d</mcc>\n", $row[4]);
				printf("<mnc>%d</mnc>\n", $row[5]);
				printf("<lac>%d</lac>\n", $row[6]);
				printf("<cid>%d</cid>\n", $row[7]);
				printf("<type>%s</type>\n", $row[8]);
				printf("</position>\n");
			}
			printf("</positions>\n");
		}

		public static function query_position_single($db, $id) {
			$queryStr = "select device_id, time, latitude, longitude, mcc, mnc, lac, cid, type from position 
					where device_id = '$id'";
			$result = $db->query($queryStr);
			printf("<positions>\n");
			while($row = $result->fetch_row()) {
				printf("<position>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<lat>%s</lat>\n", $row[2]);
				printf("<lon>%s</lon>\n", $row[3]);
				printf("<mcc>%d</mcc>\n", $row[4]);
				printf("<mnc>%d</mnc>\n", $row[5]);
				printf("<lac>%d</lac>\n", $row[6]);
				printf("<cid>%d</cid>\n", $row[7]);
				printf("<type>%s</type>\n", $row[8]);
				printf("</position>\n");
			}
			printf("</positions>\n");
		}


		public static function query_message_all($db, $user, $start) {
			$queryStr = "select message.device_id, time, type,content from message inner join user_bracelet 
				on message.time>'$start' 
				and user_bracelet.device_id=message.device_id 
				and user_bracelet.user_id='$user'; 
			$result = $db->query($queryStr);
			printf("<messages>\n");
			while($row = $result->fetch_row()) {
				printf("<message>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<type>%s</type>\n", $row[2]);
				printf("<msg>%s</msg>\n", $row[3]);
				printf("</message>\n");
			}
			printf("</messages>\n");
		}

		public static function query_message_single($db, $id) {
			$queryStr = "select device_id, time, content, type from message 
				where device_id = '$id'";
			$result = $db->query($queryStr);
			printf("<messages>\n");
			while($row = $result->fetch_row()) {
				printf("<message>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<type>%s</type>\n", $row[2]);
				printf("<msg>%s</msg>\n", $row[3]);
				printf("</message>\n");
			}
			printf("</messages>\n");
		}


		public static function get_latest_position_for($db, $user) {
			$queryStr = "select p.device_id, max(p.time),p.latitude,p.longitude,p.mcc,p.mnc,p.lac,p.cid,p.type from 
				(select position.device_id, position.time,latitude,longitued,mcc,mnc,lac,cid,type from position inner join user_bracelet 
				on user_bracelet.device_id=position.device_id and user_bracelet.user_id='$user' order by device_id) as p group by device_id"; 
			$result = $db->query($queryStr);
			header("Content-type:text/xml");
			printf("<positions>\n");
			while($row = $result->fetch_row()) {
				printf("<position>\n");
				printf("<deviceId>%s</deviceId>\n", $row[0]);
				printf("<time>%s</time>\n", $row[1]);
				printf("<lat>%s</lat>\n", $row[2]);
				printf("<lon>%s</lon>\n", $row[3]);
				printf("<mcc>%d</mcc>\n", $row[4]);
				printf("<mnc>%d</mnc>\n", $row[5]);
				printf("<lac>%d</lac>\n", $row[6]);
				printf("<cid>%d</cid>\n", $row[7]);
				printf("<type>%s</type>\n", $row[8]);
				printf("</position>\n");
			}
			printf("</positions>\n");
		}
	}
?>
