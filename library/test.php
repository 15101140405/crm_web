<?php
// include('/school/crm_web/library/taobao-sdk-PHP-auto_1455552377940-20160505/TopSdk.php');
include_once('WPRequest.php');

echo "test:";
// print_r($_POST);

// $touser = 2222222;
// $content = "开单了";
// $corpid = "wxee0a719fd467c364";
// $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";
// $result = WPRequest::sendMessage_Text($touser, $toparty, $content,$corpid,$corpsecret);

$appId = "FSAID_1315070";
$appSecret = "460c2c7b5e2b40ae954c5b65d6ab75d8";
$permanentCode = "C6D8B5803D7B2CDC25D2694EC1FB2305";
$toUser = array("FSUID_6C1FF482960507E189C0D14CB19D7FF6","FSUID_6C1FF482960507E189C0D14CB19D7FF6");
$content = array(
    "content"	=> "今晚夜间将有中到大雨，22-18℃。
此条消息为测试消息。",
    );

$departmentId = 1008;
$fetchChild = true;

// $result = WPRequest::getCorpAccessToken($appId,$appSecret,$permanentCode);
$result = WPRequest::fxiaokesendMessage($appId,$appSecret,$permanentCode,$content);
// $result = WPRequest::getdepartmentlist($appId,$appSecret,$permanentCode);
// $result = WPRequest::getuserlist($appId,$appSecret,$permanentCode,$departmentId,$fetchChild);
// $result = WPRequest::getalluserlist($appId,$appSecret,$permanentCode,$fetchChild);

var_dump($result);

?>