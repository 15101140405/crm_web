<?php


include_once('../library/WPRequest.php');
// include_once('protected/controllers/ReportController.php');


echo "test";
$touser="2222222";
//$content="中文怎么解决";
$title="1";
$agentid=9;
// $url="https://www.baidu.com/";
$thumb_media_id="1VIziIEzGn_YvRxXK3OxPQpylPHLUnnA2gJ5_v8Cus2la7sjhAWYgzyFZhIVI9UoS6lkQ-ZLuMPZgP8BOVIS-XQ";
$media_id="2n8jAkMtWj42qcBGih5M_hq0teff_17YKATQXYyLlLyAEN6Z_5mOgSyBUcKz7ebu9";

// $t=new ReportController;

$content = "恭喜 新订单 开单成功！";

// $content = $t->actionDayreport();
// print_r($content);die;

// $content=ReportController::actionDayreport();
$digest="描述";
//$media="C:\Users\Light\Desktop\life\65298b36.jpg";
// $media="@/var/www/html/school/crm_web/image/thumb.jpg";
// $type="image";

echo "</br>";
// $result=WPRequest::updateMpnews_Data($media_id,$title,$thumb_media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
// $result=WPRequest::addmpnews( $title,$media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
//print_r(WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur));
$result=WPRequest::sendMessage_Text($touser, $toparty, $content);
//$result=WPRequest::mediaupload($media,$type);
echo "result:";
print_r($result);
//$ge=array("Bill"=>"35","Steve"=>"37");
//print_r($ge);
?>
