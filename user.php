<?php

	require_once("mail.php");
	define(RANDOM_MIN, 1000);
	define(RANDOM_MAX, 9999);
	define(EMAIL_SUBJECT, "优松验证码");
	define(EMAIL_CONTENT, "你的验证码是:");

	class User {
		private $name;
		private $password;
		public $email;
		private $resgiterTime;

		function __construct($name, $pw) {
			$this->name = $name;
			$this->password = $pw;

			$timeNow = new DateTime();
			$dateStr = $timeNow->format('Y-m-d H:i');
			$this->registerTime = $dateStr;
		}


		function register($db) {
			$queryStr = "insert into user (phone_number, password, email, register_time)
					values ('$this->name', '$this->password', '$this->email', '$this->registerTime')";
			header("Content-type:text/xml");
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				if($db->errno == 1062){
					$res = 400;
				}
				else {
					$res = 300;
				}
			}
			printf("<register>\n");
			printf("<res>%d</res>\n", $res);
			printf("</register>\n");
		}

		function login($db) {
			$queryStr = "select phone_number,password from user where phone_number = '$this->name'";
			$result = $db->query($queryStr);
			header("Content-type:text/xml");
			if($result->num_rows > 0) {
				$row = $result->fetch_row();
				if($row[1] == $this->password) {
					$res = 100;
					printf("<login>\n");
					printf("<res>%d</res>", $res);

					$this->query_devices($db);
				
					printf("</login>\n");
				}
				else {
					$res = 400;
					printf("<login>\n");
					printf("<res>%d</res>", $res);
					printf("</login>\n");
				}
			}
			else if($result->num_rows == 0) {
				$res = 300;
				printf("<login>\n");
				printf("<res>%d</res>", $res);
				printf("</login>\n");
			}
		}
	
		function query_devices($db) {
			$queryStr = "select user_bracelet.device_id,device_mac,device_name,device_sex,device_age,
					device_weight,device_height,image,power from user_bracelet,bracelet
					where user_id = '$this->name' and user_bracelet.device_id = bracelet.device_id";
			$result = $db->query($queryStr);
			$row_num = $result->num_rows;
			for($i=0; $i<$row_num; $i++) {
				$row = $result->fetch_row();
				printf("<device>\n");
				printf("<id>%s</id>\n", $row[0]);
				printf("<mac>%s</mac>\n", $row[1]);
				printf("<name>%s</name>\n", $row[2]);
				printf("<sex>%s</sex>\n", $row[3]);
				printf("<age>%d</age>\n", $row[4]);
				printf("<weight>%d</weight>\n", $row[5]);
				printf("<height>%d</height>\n", $row[6]);
				printf("<power>%d</power>\n", $row[8]);
				printf("<image>%s</image>\n", $row[7]);
				printf("</device>\n");
			}
		}

		function change_password($db, $code) {
			$queryCode = "select vcode from user where phone_number = '$this->name'";
			$result = $db->query($queryCode);
			$row_num = $result->num_rows;
			header("Content-type:text/xml");
			if($row_num == 1) {
				$row = $result->fetch_row();
				if($code == $row[0]) {
					$db->query("update user set password = '$this->password' where phone_number = '$this->name'");
					printf("<changePassword>\n");
					printf("<res>%d</res>\n", 100);
					printf("</changePassword>\n");
					return;
				}
			}
			printf("<changePassword>\n");
			printf("<res>%d</res>\n", 400);
			printf("</changePassword>\n");
		}

		public static function get_verify_code($db, $name) {
			$queryStr = "select email from user where phone_number = '$name'";
			$result = $db->query($queryStr);
			$row_num = $result->num_rows;
			//header("Content-type:text/xml");
			if($row_num == 0) {
				printf("<getVerifyCode>\n");
				printf("<res>%d</res>\n", 400);
				printf("</getVerifyCode>\n");
				return;	
			}
			else {
				$row = $result->fetch_row();
				$email = $row[0];
				$code = rand(RANDOM_MIN, RANDOM_MAX);
				$res = send_mail($email, EMAIL_SUBJECT, EMAIL_CONTENT.$code);
			}
			if($res == 1) {
				$queryStr = "update user set vcode = '$code' where phone_number = '$name'";
				$db->query($queryStr);
			}
			printf("<getVerifyCode>\n");
			printf("<res>%d</res>\n", 100);
			printf("</getVerifyCode>\n");
		}

	}
	
?>
