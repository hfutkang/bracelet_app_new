<?php 
	class UserDevice {
		public $user_id;
		public $id;
		public $mac;
		public $name;
		public $sex;
		public $age;
		public $weight;
		public $height;
		public $power;
		public $image;
		public $time;

		public function __construct($userid, $deviceid) {
			$this->user_id = $userid;
			$this->id = $deviceid;

			$dateTime = new DateTime();
			$this->time = $dateTime->format('Y-m-d H:i');
			$this->image = $userid."_".$deviceid.".png";
		}

		public function add($db) {
			$queryStr = "insert into user_bracelet (user_id, device_id, device_mac, device_name,
					device_sex, device_age, device_weight, device_height, image, add_time) 
					values ('$this->user_id', '$this->id', '$this->mac', '$this->name', '$this->sex',
					'$this->age', '$this->weight', '$this->height', '$this->image', '$this->time')";
			if($db->query($queryStr)) {
				$queryStr = "insert into bracelet (device_id, mac)
                                        values('$this->id', '$this->mac')";
				$db->query($queryStr);
				$res = 100;
			}
			else {
				//echo $db->errno." ".$db->error;
				if($db->errno == 1026)
					$res = 400;
				else
					$res = 300;
			}
			header("Content-type:text/xml");
			printf("<addDevice>\n");
			printf("<res>%d</res>\n", $res);
			printf("</addDevice>\n");
		}

		public function update($db) {
			$queryStr = "update user_bracelet set device_name='$this->name',device_sex='$this->sex', 
				device_age='$this->age', device_weight='$this->weight', device_height='$this->height'
				 where user_id='$this->user_id' and device_id='$this->id'";
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				$res = 300;
			}
			header("Content-type:text/xml");
			printf("<updateDevice>\n");
			printf("<res>%d</res>\n", $res);
			printf("</updateDevice>\n");
		}

		public function delete($db) {
			$queryStr = "delete from user_bracelet where user_id='$this->user_id' and device_id='$this->id'";
			if($db->query($queryStr)) {
				$res = 100;
			}
			else {
				$res = 300;
			}

			$imagePath = "images/".$this->user_id."_".$this->id.".png";
			if(file_exists($imagePath)) {
				unlink($imagePath);
			}
			header("Content-type:text/xml");
			printf("<deleteDevice>\n");
			printf("<res>%d</res>\n", $res);
			printf("</deleteDevice>\n");
		}

	}

?>
