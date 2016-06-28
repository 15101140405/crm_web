<?php
// include('/school/crm_web/library/taobao-sdk-PHP-auto_1455552377940-20160505/TopSdk.php');
include_once('WPRequest.php');

echo "test:";

$servicelist['host']=array();
$servicelist['video']=array();
$servicelist['camera']=array();
$servicelist['makeup']=array();
$servicelist['other']=array();
// var_dump($servicelist);
// print_r($_POST);

// $touser = 2222222;
// $content = "开单了";
// $corpid = "wxee0a719fd467c364";
// $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";
// $result = WPRequest::getAccessToken($corpid,$corpsecret);
// $result = WPRequest::sendMessage_Text($touser, $toparty, $content,$corpid,$corpsecret);
// var_dump($result);

$appId = "FSAID_131515e";
$appSecret = "d78a157317794b558d6638c9a6d28938";
$permanentCode = "FDB18BBF17DFB6F7CFAABDFC13841DFE";
// // $toUser = array("FSUID_6C1FF482960507E189C0D14CB19D7FF6","FSUID_6C1FF482960507E189C0D14CB19D7FF6");
$content = array(
    "content"	=> "消息。",
    );
$openUserId = WPRequest::idlist();
// // $departmentId = 1008;
// // $fetchChild = true;
// // $openUserId = array('FSUID_459E85AA5C2C23316709285CBED22B91');
// // $result = WPRequest::getCorpAccessToken($appId,$appSecret,$permanentCode);
// $result = WPRequest::fxiaokesendMessage($appId,$appSecret,$permanentCode,$content);
$result = WPRequest::fxiaokedisendMessage($appId,$appSecret,$permanentCode,$content,$openUserId);
// $result = WPRequest::getdepartmentlist($appId,$appSecret,$permanentCode);
// $result = WPRequest::getuserlist($appId,$appSecret,$permanentCode,$departmentId,$fetchChild);
// $result = WPRequest::getalluserlist($appId,$appSecret,$permanentCode,$fetchChild);
// $result = WPRequest::idlist();
var_dump($result);

?>
<!-- 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="../portal/css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="../portal/css/layout.css" />
</head>

<body>
	<div class="button_box" id="create">套系价：&yen;<span id="final_price_show">0</span>下一步</div>
            <span class="tip tip2 hid" id="list_tip">请为套系选择内容</span>
            <span class="tip tip2" id="price_tip">请输入套系总价</span>
	</div>
<script type="text/javascript" src="../portal/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="../portal/js/select.js"></script>
<script type="text/javascript" src="../portal/js/login.js"></script>
<script>
$(function(){
	$("#list_tip").addClass("hid");



    // $("[supplier-type-id='20']").removeClass("hid");
 
})
</script>
</body>

</html> -->