<?php
function send_mail($to, $subject = 'subject', $body) {
    $loc_host = "localhost";                 //主机名，随意
    //$smtp_acc = "hky@sctek.cn";        //你的邮箱
    //$smtp_pass="txqyy2014.";              //密码
    //$smtp_host="smtp.exmail.qq.com";    //这里是腾讯企业云的设置方法
    $smtp_acc = "hfutkang@aliyun.com";
    $smtp_pass = "aly2014";
    $smtp_host="smtp.mail.aliyun.com";
    //$from="hky@sctek.cn";              //你的邮箱
    $from = "hfutkang@aliyun.com";
    $headers = "Content-Type: text/plain; charset=\"utf-8\"\r\nContent-Transfer-Encoding: base64";
    $lb="\r\n";                         //linebreak

    $hdr = explode($lb,$headers);     
    if($body) {
        $bdy = preg_replace("/^\./","..",explode($lb,$body));
    }

    $smtp = array(

        array("EHLO ".$loc_host.$lb,"220,250","HELO error: "),

        array("AUTH LOGIN".$lb,"334","AUTH error:"),

        array(base64_encode($smtp_acc).$lb,"334","AUTHENTIFICATION error : "),

        array(base64_encode($smtp_pass).$lb,"235","AUTHENTIFICATION error : ")
    );

    $smtp[] = array("MAIL FROM: <".$from.">".$lb,"250","MAIL FROM error: ");

    $smtp[] = array("RCPT TO: <".$to.">".$lb,"250","RCPT TO error: ");

    $smtp[] = array("DATA".$lb,"354","DATA error: ");

    $smtp[] = array("From: ".$from.$lb,"","");

    $smtp[] = array("To: ".$to.$lb,"","");

    $smtp[] = array("Subject: ".$subject.$lb,"","");

    foreach($hdr as $h) {$smtp[] = array($h.$lb,"","");}

    $smtp[] = array($lb,"","");

    if($bdy) {foreach($bdy as $b) {$smtp[] = array(base64_encode($b.$lb).$lb,"","");}}

    $smtp[] = array(".".$lb,"250","DATA(end)error: ");

    $smtp[] = array("QUIT".$lb,"221","QUIT error: ");


    $fp = @fsockopen($smtp_host, 25);
    if (!$fp) echo "Error: Cannot conect to ".$smtp_host."";
    while($result = @fgets($fp, 1024)){
	    echo $result;
        if(substr($result,3,1) == " ") { break; }
    }

    $result_str="";

    foreach($smtp as $req){

        @fputs($fp, $req[0]);

        if($req[1]){

            while($result = @fgets($fp, 1024)){
		    echo $result.'\n\n';
                if(substr($result,3,1) == " ") { break; }
            };
            if (!strstr($req[1],substr($result,0,3))){
                $result_str.=$req[2].$result."";
            }
        }
    }

    @fclose($fp);
    return 1;
}
