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

$appId = "FSAID_131515e";
$appSecret = "d78a157317794b558d6638c9a6d28938";
$permanentCode = "FDB18BBF17DFB6F7CFAABDFC13841DFE";
// $toUser = array("FSUID_6C1FF482960507E189C0D14CB19D7FF6","FSUID_6C1FF482960507E189C0D14CB19D7FF6");
$content = array(
    "content"	=> "消息。",
    );

// $departmentId = 1008;
// $fetchChild = true;
// $openUserId = array('FSUID_459E85AA5C2C23316709285CBED22B91');
// $result = WPRequest::getCorpAccessToken($appId,$appSecret,$permanentCode);
$result = WPRequest::fxiaokesendMessage($appId,$appSecret,$permanentCode,$content);
// $result = WPRequest::getdepartmentlist($appId,$appSecret,$permanentCode);
// $result = WPRequest::getuserlist($appId,$appSecret,$permanentCode,$departmentId,$fetchChild);
// $result = WPRequest::getalluserlist($appId,$appSecret,$permanentCode,$fetchChild);

var_dump($result);

?>