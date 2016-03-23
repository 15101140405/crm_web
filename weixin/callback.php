<?php
/**
 * Created by PhpStorm.
 * User: keqiaow
 * Date: 2016/1/7
 * Time: 2:27
 */

$corpid = "wxf29be229f32800a9";
$secret = "RN76xV99TOvqwIH4v8MfZf6k9w6_ZofopXjjuAJuiU9hVwBWoKIKYsw82jUGqTWG";
$code = $_GET["code"];

//$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$corpid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code'; //公众号
$get_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid='.$corpid.'&corpsecret='.$secret; //企业号


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_token_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);
$json_obj = json_decode($res, true);

//根据openid和access_token查询用户信息
$access_token = $json_obj['access_token'];
$agentid = 14;
//$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN'; //公众号
$get_user_info_url = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$access_token.'&code='.$code.'&agentid='.$agentid;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_user_info_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);

//解析json
$user_obj = json_decode($res, true);
$_SESSION['user'] = $user_obj;
print("\r\n"."Welcome~"."\r\n\r\n");
print_r($user_obj);

$user_id = $user_obj['UserId'];

//$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN'; //公众号
$get_user_url = 'https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.'&userid='.$user_id;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_user_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$res = curl_exec($ch);
curl_close($ch);

//解析json
$user_weixin_obj = json_decode($res, true);
$_SESSION['user'] = $user_weixin_obj;
print("\r\n\r\n\r\n"."Hei~"."\r\n\r\n\r\n");
print_r($user_weixin_obj);

