<?php

include_once "WXBizMsgCrypt.php";
include_once "sha1.php";
include_once "xmlparse.php";
include_once "pkcs7Encoder.php";
include_once "errorCode.php";

//������������Ϣ������ƽ̨
$encodingAesKey = "FDNScf7SS7GvTZGjLXc7LXr3dWqu34GVLJKocUJntaA";
$token = "xingfu128";
$corpId = "wxf29be229f32800a9";

//���ںŷ���������
$sReqMsgSig = $sVerifyMsgSig = $_GET['msg_signature'];
$sReqTimeStamp = $sVerifyTimeStamp = $_GET['timestamp'];
$sReqNonce = $sVerifyNonce = $_GET['nonce'];
$sReqData =  file_get_contents('php://input');
$sVerifyEchoStr = $_GET['echostr'];

$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);

if($sVerifyEchoStr) {
    $sEchoStr = ""; //��Ҫ���ص�����
    $errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);

    if ($errCode == 0) {
        print($sEchoStr);
    } else {
        print($errCode . "\n\n");
    }
    exit;
}

//decrypt
/*$sMsg = "";  //����֮�������
$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
if ($errCode == 0) {
    $xml = new DOMDocument();
    $xml->loadXML($sMsg);
    $reqToUserName = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;
    $reqFromUserName = $xml->getElementsByTagName('FromUserName')->item(0)->nodeValue;
    $reqCreateTime = $xml->getElementsByTagName('CreateTime')->item(0)->nodeValue;
    $reqMsgType = $xml->getElementsByTagName('MsgType')->item(0)->nodeValue;
    $reqContent = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
    $reqMsgId = $xml->getElementsByTagName('MsgId')->item(0)->nodeValue;
    $reqAgentID = $xml->getElementsByTagName('AgentID')->item(0)->nodeValue;
    switch($reqContent){
        case "����":
            $mycontent="���ã����ƣ���֪���������˰���Ͱͣ�";
            break;
        case "����":
            $mycontent="���ã����ڣ���֪�����������۹���";
            break;
        case "ʷ����":
            $mycontent="���ã�ʷ��������֪���������˾������磡";
            break;
        default :
            $mycontent="����˭������һ������ȥ��";
            break;
}
$sRespData = "<xml><ToUserName><![CDATA[".$reqFromUserName."]]></ToUserName><FromUserName><![CDATA[".$corpId."]]></FromUserName><CreateTime>".sReqTimeStamp."</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[".$mycontent."]]></Content></xml>";
$sEncryptMsg = ""; //xml��ʽ������
$errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
    if ($errCode == 0) {
        //file_put_contents('smg_response.txt', $sEncryptMsg); //debug:�鿴smg
print($sEncryptMsg);
    } else {
        print('error');
        print($errCode . "\n\n");
}
} else {
    print($errCode . "\n\n");
}*/

