<?php
	require_once(dirname(__FILE__) . '/getui/' . 'IGt.Push.php');
	require_once(dirname(__FILE__) . '/getui/' . 'igetui/IGt.AppMessage.php');
	require_once(dirname(__FILE__) . '/getui/' . 'igetui/IGt.APNPayload.php');
	require_once(dirname(__FILE__) . '/getui/' . 'igetui/template/IGt.BaseTemplate.php');
	require_once(dirname(__FILE__) . '/getui/' . 'IGt.Batch.php');
	require_once(dirname(__FILE__) . '/getui/' . 'igetui/utils/AppConditions.php');
	
	define('HOST','http://sdk.open.api.igexin.com/apiex.htm');
	//define('APPKEY','CxiMKHnOGO6uMawgKkJLg3');
	//define('APPID','03IHI3IoNb7kSdiWVfQ7eA');
	//define('MASTERSECRET','dKxa6ZEfEA9LU7zq6KEUP4');
	define('CID','');
	define('DEVICETOKEN','');
	define('Alias','请输入别名');
	
	define('APPKEY','AEisA2HQ3x8GAfyHEbFT99');
	define('APPID','DlWSVi1reU6YrShHYdNy27');
	define('MASTERSECRET','HulmVGSaMp6McwD93Jjmx');
	class GeTuiUtils {

		//多推接口案例
		public static function pushMessageToList($users, $msg) {
			if(count($users) == 0)
				return;

			putenv("gexin_pushList_needDetails=true");
			putenv("gexin_pushList_needAsync=true");

			$igt = new IGeTui(HOST, APPKEY, MASTERSECRET);
			//消息模版：
			// 1.TransmissionTemplate:透传功能模板
			$template = GeTuiUtils::IGtTransmissionTemplateDemo($msg);
			//个推信息体
			$message = new IGtListMessage();
			$message->set_isOffline(true);//是否离线
			$message->set_offlineExpireTime(3600 * 12 * 1000);//离线时间
			$message->set_data($template);//设置推送消息类型
			//$message->set_PushNetWorkType(1);	//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
			//$contentId = $igt->getContentId($message);
			$contentId = $igt->getContentId($message,"msg");	//根据TaskId设置组名，支持下划线，中文，英文，数字

			foreach($users as $user ) {
				$target = new IGtTarget();
				$target ->set_appId(APPID);
				$target->set_clientId(CID);
				$target->set_alias($user);
				
				$targetList[] = $target;
			}
			$rep = $igt->pushMessageToList($contentId, $targetList);
	
//			var_dump($rep);

		}

		
		//群推接口案例
		public static function pushMessageToApp($users, $msg){
			
			if(count($users) == 0)
				return;
			
			$igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
    			$template = GeTuiUtils::IGtTransmissionTemplateDemo($msg);
   			//个推信息体
    			//基于应用消息体
    			$message = new IGtAppMessage();
    			$message->set_isOffline(true);
    			$message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
    			$message->set_data($template);

    			$appIdList=array(APPID);
    			$phoneTypeList=array('ANDROID');
    			//$tagList=array($tag);

    			$cdt = new AppConditions();
   			$cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
    			$cdt->addCondition(AppConditions::TAG, $tagList);

    			$message->set_appIdList($appIdList);
    			$message->set_conditions($cdt->getCondition());

    			$rep = $igt->pushMessageToApp($message,$tag);
//			var_dump($rep);
		}

		public static function IGtTransmissionTemplateDemo($msg){
    			$template =  new IGtTransmissionTemplate();
    			$template->set_appId(APPID);//应用appid
    			$template->set_appkey(APPKEY);//应用appkey
    			$template->set_transmissionType(2);//透传消息类型
    			$template->set_transmissionContent($msg);//透传内容
    			//$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

			//APN高级推送
			$apn = new IGtAPNPayload();
			$alertmsg=new DictionaryAlertMsg();
			$alertmsg->body="body";
			$alertmsg->actionLocKey="ActionLockey";
			$alertmsg->locKey="LocKey";
			$alertmsg->locArgs=array("locargs");
			$alertmsg->launchImage="launchimage";
			//        IOS8.2 支持
			$alertmsg->title="Title";
			$alertmsg->titleLocKey="TitleLocKey";
			$alertmsg->titleLocArgs=array("TitleLocArg");

			$apn->alertMsg=$alertmsg;
			$apn->badge=7;
			$apn->sound="";
			$apn->add_customMsg("payload","payload");
			$apn->contentAvailable=1;
			$apn->category="ACTIONABLE";
			$template->set_apnInfo($apn);

    			return $template;
		}

	}
?>
