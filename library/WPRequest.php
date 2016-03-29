<?php

/**
 * Created by PhpStorm.
 * User: chenbo
 * Date: 2016/2/27
 * Time: 12:03
 */
class WPRequest
{

    //基础方法：GET和POST请求
    public static function get($url, $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    public static function post($url, $post_data = '', $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    //微信接口
    public static $corpid = "wxee0a719fd467c364";
    public static $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";

    public static function getAccessToken()
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . WPRequest::$corpid . "&corpsecret=" . WPRequest::$corpsecret;
        //echo $url;
        $data = self::get($url);
        try {
            $obj = json_decode($data);
            return $obj->access_token;
        } catch (Excption $e) {
            return "";
        }

    }

    /*创建用户*/
    //department 格式 [1] 或 [1,2]
    public static function createUser($userid, $name, $department, $position, $mobile)
    {
        $access_token = self::getAccessToken();
        $obj = json_encode(array(
            'userid' => $userid,
            'name' => $name,
            'department' => $department,
            'position' => $position,
            'mobile' => $mobile
        ), JSON_UNESCAPED_UNICODE);
        $creatSuccess = false;
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token=" . $access_token;
        $rtnobj = self::post($url, $obj);
        //echo "<br>" . $rtnobj;
        try {
            if (json_decode($rtnobj)->errcode == 0)
                $creatSuccess = true;
        } catch (Excption $e) {
        }
        return $creatSuccess;
    }

    /*获取登陆用户信息*/
    public static function getUserId($code)
    {
        $access_token = self::getAccessToken();
        $url = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$access_token.'&code='.$code;
        $userId = self::get($url);
        return $userId;
    }

    /*发送消息（通用）*/
    public static function sendMessage($data)
    {
        $access_token = self::getAccessToken();
        $sendSuccess = false;
        $url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=" . $access_token;
        $rtnobj = self::post($url, $data);
        // echo "<br>" . $rtnobj;
        // echo "<br>"."rtnob:";
	    // print_r($rtnobj);
	    // echo "<br>"."end";
	try {
            if (json_decode($rtnobj)->errcode == 0)
                $sendSuccess = true;
        } catch (Excption $e) {
        }
	return $rtnobj;
       // return $sendSuccess;
    }


    /*发送消息（text消息）*/
    //成员ID列表（消息接收者，多个接收者用‘|’分隔，最多支持1000个）。特殊情况：指定为@all，则向关注该企业应用的全部成员发送
    //部门ID列表，多个接收者用‘|’分隔，最多支持100个。当touser为@all时忽略本参数
    public static function sendMessage_Text($touser, $toparty, $content)
    {
        $obj = json_encode(array(
            'touser' => $touser,
            'toparty' => $toparty,
            'totag' => "",
            'msgtype' => "text",
            'agentid' => 0,
            'text' => array('content' => $content)
        ), JSON_UNESCAPED_UNICODE);
        return self::sendMessage($obj);
    }


    // 发送消息（news消息）
    // touser      成员ID列表（消息接收者，多个接收者用‘|’分隔，最多支持1000个）。特殊情况：指定为@all，则向关注该企业应用的全部成员发送
    // toparty     部门ID列表，多个接收者用‘|’分隔，最多支持100个。当touser为@all时忽略本参数
    // totag       标签ID列表，多个接收者用‘|’分隔。当touser为@all时忽略本参数
    // msgtype     消息类型，此时固定为：news
    // agentid     企业应用的id，整型。可在应用的设置页面查看
    // article     图文消息，一个图文消息支持1到10条图文
    // title       标题
    // description 描述
    // url         点击后跳转的链接。
    // picurl      图文消息的图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80。如不填，在客户端不显示图片
    // 此处只能发一条图文消息，若要实现多条，需要修改
    public static function sendMessage_News($touser, $toparty, $title, $description, $url, $picur)
    {
        $obj = json_encode(array(

            'touser'    => $touser,
            'toparty'   => $toparty,
            'totag'     => "",
            'msgtype'   => "news",
            'agentid'   => 9,
            'news'      => array(               
                'articles'  => array(array(   //若要实现一次发多条，需要修改
                    'title'         => $title,
                    'description'   => $description,
                    'url'           => $url,
                    'picurl'        => $picur)
                                       ))

                ), JSON_UNESCAPED_UNICODE);
        return self::sendMessage($obj);
    }

//     发送消息（mpnews消息）a)发送时直接带上mpnews内容
//     touser  否   成员ID列表（消息接收者，多个接收者用‘|’分隔，最多支持1000个）。特殊情况：指定为@all，则向关注该企业应用的全部成员发送
//     toparty 否   部门ID列表，多个接收者用‘|’分隔，最多支持100个。当touser为@all时忽略本参数
//     totag   否   标签ID列表，多个接收者用‘|’分隔。当touser为@all时忽略本参数
//     msgtype 是   消息类型，此时固定为：mpnews
//     agentid 是   企业应用的id，整型。可在应用的设置页面查看
//     articles    是   图文消息，一个图文消息支持1到10个图文
//     title   是   图文消息的标题
//     thumb_media_id  是   图文消息缩略图的media_id, 可以在上传多媒体文件接口中获得。此处thumb_media_id即上传接口返回的media_id
//     author  否   图文消息的作者
//     content_source_url  否   图文消息点击“阅读原文”之后的页面链接
//     content 是   图文消息的内容，支持html标签
//     digest  否   图文消息的描述
//     show_cover_pic  否   是否显示封面，1为显示，0为不显示
//     safe    否   表示是否是保密消息，0表示否，1表示是，默认0
    public static function sendMessage_Mpnews(
        $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe)
    {
        $obj = json_encode(array(
            'touser'    => $touser,
            'toparty'   => $toparty,
            'totag'     => $totag,
            'msgtype'   => "mpnews",
            'agentid'   => $agentid,
            'mpnews'      => array(               
                'articles'  => array(array(   //若要实现一次发多条，需要修改
                    'title'         => $title,
                    'thumb_media_id'   => $thumb_media_id,
                    'author'           => $author,
                    'content_source_url'        => $content_source_url,
                    'content'       => $content,
                    'digest'        => $digest,
                    'show_cover_pic'=> $show_cover_pic,
                    '$safe'         => $safe)
                                       ))

                ), JSON_UNESCAPED_UNICODE);
        return self::sendMessage($obj);
    }


//     b)发送时使用永久图文素材ID：
// touser  否   成员ID列表（消息接收者，多个接收者用‘|’分隔，最多支持1000个）。特殊情况：指定为@all，则向关注该企业应用的全部成员发送
// toparty 否   部门ID列表，多个接收者用‘|’分隔，最多支持100个。当touser为@all时忽略本参数
// totag   否   标签ID列表，多个接收者用‘|’分隔。当touser为@all时忽略本参数
// msgtype 是   消息类型，此时固定为：mpnews
// agentid 是   企业应用的id，整型。可在应用的设置页面查看
// media_id    是   素材资源标识ID，通过上传永久图文素材接口获得。注：必须是在该agent下创建的。
// safe    否   表示是否是保密消息，0表示否，1表示是，默认0
// public static function sendMessage_MpnewsID(
//         $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe)
//     {
//         $obj = json_encode(array(

          

//             'touser'    => $touser,
//             'toparty'   => $toparty,
//             'totag'     => $totag,
//             'msgtype'   => "news",
//             'agentid'   => $agentid,
//             'mpnews'      => array(               
//                 'articles'  => array(array(   //若要实现一次发多条，需要修改
//                     'title'         => $title,
//                     'thumb_media_id'   => $thumb_media_id,
//                     'author'           => $author,
//                     'content_source_url'        => $content_source_url,
//                     'content'       => $content,
//                     'digest'        => $digest,
//                     'show_cover_pic'=> $show_cover_pic,
//                     '$safe'         => $safe)
//                                        ))

//                 ), JSON_UNESCAPED_UNICODE);
//         return self::sendMessage($obj);
//     }



       /*上传媒体文件*/
    public static function media($data, $type)
    {
        $access_token = self::getAccessToken();
        $sendSuccess = false;
        $url = "https://qyapi.weixin.qq.com/cgi-bin/media/upload?access_token=". $access_token."&type=".$type;
        $rtnobj = self::post($url, $data);
    
        try {
            if (json_decode($rtnobj)->errcode == 0)
                $sendSuccess = true;
        } catch (Excption $e) {
        }
        return $rtnobj;
    }


    public static function mediaupload($media,$type)
    {
        $me=array("media" => $media);
        //$obj = json_encode($me, JSON_UNESCAPED_UNICODE);
        return self::media($me,$type);
    }



       /*上传永久图文素材*/
    public static function material($data)
    {
        $access_token = self::getAccessToken();
        $sendSuccess = false;
        $url = "https://qyapi.weixin.qq.com/cgi-bin/material/add_mpnews?access_token=". $access_token;
        $rtnobj = self::post($url, $data);
	
        try {
            if (json_decode($rtnobj)->errcode == 0)
                $sendSuccess = true;
        } catch (Excption $e) {
        }
        return $rtnobj;
    }

    
    // access_token       必须   调用接口凭证
    // agentid            必须   企业应用的id，整型。可在应用的设置页面查看
    // articles           必须   图文消息，一个图文消息支持1到10个图文
    // title              必须   图文消息的标题
    // thumb_media_id     必须   图文消息缩略图的media_id, 可以在上传永久素材接口中获得
    // author              非    图文消息的作者
    // content_source_url  非    图文消息点击“阅读原文”之后的页面链接
    // content            必须   图文消息的内容，支持html标签
    // digest             必须   图文消息的描述
    // show_cover_pic      非    是否显示封面，1为显示，0为不显示。默认为0
    public static function addmpnews($title,$media_id,$author,$content_source_url,$content,$digest,$show_cover_pic)
    {
        $obj = json_encode(array(
                'agentid'   => 9,  //企业应用的id，整型。可在应用的设置页面查看，这里固定为9，为“B经营指标”
                'mpnews'    => array(
                    'articles'  => array(array(
                        'title'                 => $title,
                        'thumb_media_id'        => $media_id,
                        'author'                => $author,
                        'content_source_url'    => $content_source_url,
                        'content'               => $content,
                        'digest'                => $digest,
                        'show_cover_pic'        => $show_cover_pic
                                              )
                        //此处可修改为多篇文章
                                    )
                                   )
                               ), JSON_UNESCAPED_UNICODE);
        return self::material($obj);
    }



    // 修改永久图文素材
 
    public static function updateMpnews($data)
    {
        $access_token = self::getAccessToken();
        $sendSuccess = false;
        $url = "https://qyapi.weixin.qq.com/cgi-bin/material/update_mpnews?access_token=". $access_token;
        $rtnobj = self::post($url, $data);
    
        try {
            if (json_decode($rtnobj)->errcode == 0)
                $sendSuccess = true;
        } catch (Excption $e) {
        }
        return $rtnobj;
    }


    // access_token       必须  调用接口凭证
    // agentid            必须  企业应用的id，整型。可在应用的设置页面查看
    // articles           必须  图文消息，一个图文消息支持1到10个图文
    // title              必须  图文消息的标题
    // thumb_media_id     必须  图文消息缩略图的media_id, 可以在上传永久素材接口中获得
    // author              否   图文消息的作者
    // content_source_url  否   图文消息点击“阅读原文”之后的页面链接
    // content            必须  图文消息的内容，支持html标签
    // digest              否   图文消息的描述
    // show_cover_pic      否   是否显示封面，1为显示，0为不显示。默认为0
    public static function updateMpnews_Data($media_id,$title,$thumb_media_id,$author,$content_source_url,$content,$digest,$show_cover_pic)
    {
        $obj = json_encode(array(
                'agentid'   => 9,  //企业应用的id，整型。可在应用的设置页面查看，这里固定为9，为“B经营指标”
                'media_id'  => $media_id,
                'mpnews'    => array(
                    'articles'  => array(array(
                        'title'                 => $title,
                        'thumb_media_id'        => $thumb_media_id,
                        'author'                => $author,
                        'content_source_url'    => $content_source_url,
                        'content'               => $content,
                        'digest'                => $digest,
                        'show_cover_pic'        => $show_cover_pic
                                              )
                        //此处可修改为多篇文章
                                    )
                                   )
                               ), JSON_UNESCAPED_UNICODE);
        return self::updateMpnews($obj);
    }

}
