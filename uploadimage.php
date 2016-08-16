<?php
 //上传文件类型列表  
	$uptypes=array(  
		'image/jpg',  
		'image/jpeg',  
		'image/png',  
		'image/pjpeg',  
		'image/gif',  
		'image/bmp',  
		'image/x-png'  
	);
	$max_file_size=2000000;	 //上传文件大小限制, 单位BYTE  
	$destination_folder="images/"; //上传文件路径  
	$imgpreview=1;	  //是否生成预览图(1为生成,其他为不生成);  
	$imgpreviewsize=1/2;	//缩略图比例  
?>
<?php  
if ($_SERVER['REQUEST_METHOD'] == 'POST')  
{  
	$uname = $_POST["uname"];
	$deviceId = $_POST['deviceId'];

	if (!is_uploaded_file($_FILES["image"]["tmp_name"]))  
	//是否存在文件  
	{  
		$res = 300;
	}  
  
	$file = $_FILES["image"];  
	if($max_file_size < $file["size"])  
	//检查文件大小  
	{  
		$res = 300;
	}  
  
	if(!in_array($file["type"], $uptypes))  
	//检查文件类型  
	{  
		$res == 300;
	}  

	if(!file_exists($destination_folder))  
	{  
		mkdir($destination_folder);  
	}  
  
	$filename=$file["tmp_name"];  
	$pinfo=pathinfo($file["name"]);  
	$ftype=$pinfo['extension'];  
	$destination = $destination_folder.$uname."_".$deviceId.".".$ftype;  
	if(move_uploaded_file ($filename, $destination))  
	{  
		$res = 100;
	}  
	else {
		$res = 300;
	}

	header("Content-type:text/xml");
	printf("<uploadImage>\n");
	printf("<res>%d</res>\n", $res);
	printf("</uploadImage>");
  
}
?>
