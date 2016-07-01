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
        $header = array(                                      //为适应“纷享销客”做的设置
            'Content-Type: application/json',                 ////////
        );                                                    ////////
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);        //为适应“纷享销客”做的设置
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
        // return $post_data;
    }

    public static function post_code($url, $post_data = '', $timeout = 5)
    {
        // $header = array(                                      //为适应“纷享销客”做的设置
        //     'Content-Type: application/json',                 ////////
        // );                                                    ////////
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);        //为适应“纷享销客”做的设置
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
        // return $post_data;
    }

    //微信接口
    public static function getAccessToken($corpid,$corpsecret)
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . $corpid . "&corpsecret=" . $corpsecret;
        //echo $url;
        $data = self::post($url);
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
        $corpid = "wxee0a719fd467c364";
        $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";

        $access_token = self::getAccessToken($corpid,$corpsecret);
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
    public static function getUserId($code,$corpid,$corpsecret)
    {
        $access_token = self::getAccessToken($corpid,$corpsecret);
        $url = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$access_token.'&code='.$code;
        $userId = self::get($url);
        return $userId;
    }

    /*发送消息（通用）*/
    public static function sendMessage($data,$corpid,$corpsecret)
    {
        $access_token = self::getAccessToken($corpid,$corpsecret);
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
    public static function sendMessage_Text($touser,$toparty,$content,$corpid,$corpsecret)
    {
        $obj = json_encode(array(
            'touser' => $touser,
            'toparty' => $toparty,
            'totag' => "",
            'msgtype' => "text",
            'agentid' => 0,
            'text' => array('content' => $content)
        ), JSON_UNESCAPED_UNICODE);
        return self::sendMessage($obj,$corpid,$corpsecret);
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
    public static function sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid,$corpid,$corpsecret)
    {
        $obj = json_encode(array(

            'touser'    => $touser,
            'toparty'   => $toparty,
            'totag'     => "",
            'msgtype'   => "news",
            'agentid'   => $agentid,
            'news'      => array(               
                'articles'  => array(array(   //若要实现一次发多条，需要修改
                    'title'         => $title,
                    'description'   => $description,
                    'url'           => $url,
                    'picurl'        => $picur)
                                       ))

                ), JSON_UNESCAPED_UNICODE);
        return self::sendMessage($obj,$corpid,$corpsecret);
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
        $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe,$corpid,$corpsecret)
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
        return self::sendMessage($obj,$corpid,$corpsecret);
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
        $corpid = "wxee0a719fd467c364";
        $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";

        $access_token = self::getAccessToken($corpid,$corpsecret);
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
        $corpid = "wxee0a719fd467c364";
        $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";

        $access_token = self::getAccessToken($corpid,$corpsecret);
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
        $corpid = "wxee0a719fd467c364";
        $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";

        $access_token = self::getAccessToken($corpid,$corpsecret);
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

    /*创建成员*/
    public static function create_post($data,$corpid,$corpsecret)
    {
        $access_token = self::getAccessToken($corpid,$corpsecret);
        $sendSuccess = false;
        $url = "https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token=" . $access_token;
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

    /*构造成员信息*/
    public static function create_user($userid,$name,$department,$position,$mobile,$gender,$email,$weixinid,$corpid,$corpsecret)
    {
        $access_token = self::getAccessToken($corpid,$corpsecret);
        $url = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token='.$access_token.'&code='.$code;
        $userId = self::get($url);
        return $userId;
        

        
        $obj = json_encode(array(
            "userid" => $userid,
            "name" => $name,
            "department" => $department,
            "position" => $position,
            "mobile" => $mobile,
            "gender" => $gender,
            "email" => $email,
            "weixinid" => $weixinid,
            /*"avatar_mediaid": "2-G6nrLmr5EC3MNb_-zL1dDdzkd0p7cNliYu9V5w7o8K0",
            "extattr": {"attrs":[{"name":"爱好","value":"旅游"},{"name":"卡号","value":"1234567234"}]}*/
        ), JSON_UNESCAPED_UNICODE);
        return self::create_post($obj,$corpid,$corpsecret);
    }

    //纷享销客接口

    //获取AppAccessToken
    public static function getAppAccessToken($appId,$appSecret)
    {
        $url = "https://open.fxiaoke.com/cgi/appAccessToken/get";
        $obj = json_encode(array(
            "appId" => $appId,
            "appSecret" => $appSecret,
        ));
        $rtnobj = self::post($url, $obj);
        return json_decode($rtnobj) -> appAccessToken;
    }
    //获取CorpAccessToken和corpId
    public static function getCorp($appId,$appSecret,$permanentCode)
    {
        $appAccessToken = self::getAppAccessToken($appId,$appSecret);
        $url = "https://open.fxiaoke.com/cgi/corpAccessToken/get";
        $obj = json_encode(array(
            "appAccessToken" => $appAccessToken,
            "permanentCode" => $permanentCode,
            ));
        $rtnobj = self::post($url, $obj);
        return array(
            'corpAccessToken'   => json_decode($rtnobj) -> corpAccessToken,
            'corpId'            => json_decode($rtnobj) -> corpId);
    }
    //获取部门列表
    public static function getdepartmentlist($appId,$appSecret,$permanentCode)
    {
        $corp = self::getCorp($appId,$appSecret,$permanentCode);
        $url = "https://open.fxiaoke.com/cgi/department/list";
        $obj = json_encode(array(
            "corpAccessToken" => $corp['corpAccessToken'],
            "corpId" => $corp['corpId'],
            ));
        $rtnobj = self::post($url, $obj);
        return json_decode($rtnobj) -> departments;
    }
    //获取部门下成员信息(简略)
    public static function getuserlist($appId,$appSecret,$permanentCode,$departmentId,$fetchChild)
    {
        $corp = self::getCorp($appId,$appSecret,$permanentCode,$fetchChild);
        $url = "https://open.fxiaoke.com/cgi/user/simpleList";
        $obj = json_encode(array(
            "corpAccessToken"   => $corp['corpAccessToken'],
            "corpId"            => $corp['corpId'],
            "departmentId"      => $departmentId,
            "fetchChild"        => $fetchChild,
            ));
        $rtnobj = self::post($url, $obj);
        return json_decode($rtnobj) -> userList;//网站上示例是userlist，实际为userList
    }
    //获取所有部门下成员信息(简略)
    public static function getalluserlist($appId,$appSecret,$permanentCode,$fetchChild)
    {
        $corp = self::getCorp($appId,$appSecret,$permanentCode,$fetchChild);
        $url = "https://open.fxiaoke.com/cgi/user/simpleList";
        $departmentlist = self::getdepartmentlist($appId,$appSecret,$permanentCode);
        $openUserId = array();
        foreach ($departmentlist as $key1 => $value1) {
            $departmentId = $value1 -> id;
            $userlist = self::getuserlist($appId,$appSecret,$permanentCode,$departmentId,$fetchChild);
            foreach ($userlist as $key2 => $value2) {

                $openUserId[] = $value2 -> openUserId;
            }
        }
        print_r($openUserId);

    }

    public static function idlist()
    {
        $yyylist = array(//花乡桥店信息指定接受者
            'FSUID_67A4868F7CFAE4AF788AC32E71FCE339',
            'FSUID_CB0A0E5AB1F711A228DBCCE3F989627F',
            'FSUID_6BA4A68106FF34311E82CFB4F89D2DF5',
            'FSUID_6AB95EFAF8216BB96A54AE431FC8A9A1',
            'FSUID_3975B7B1576E414E2041669FCC1CB273',
            'FSUID_DA7DCD271D388F8A8915E35F2BBB82AE',
            'FSUID_DC287F2734E8C8C184117FDDA29BDA7C',
            'FSUID_F0338535F23337012CB6074D180D7DE7',
            'FSUID_34FC4144BC25B1912C5AD7F22B6C6EA0',
            'FSUID_734647ECC6CAAEC4893438300C725B63',
            'FSUID_16F92F490425ED02F4C0C03DE59567EC',
            'FSUID_D23F7395A0A5E1B99EE842C70B3C5FAC',
            'FSUID_93BC033C128C50D0929118676670E40E',
            'FSUID_5808D6A98832F41F5CBE887508A50B4C',
            'FSUID_01472FEE07EB30448009B2FEBE40C089',
            'FSUID_459E85AA5C2C23316709285CBED22B91',
            // 'FSUID_E1CF441FB0630D803E3FD27C99E05922',//张斯恒，排除出去方便检测效果
            );
        $testlist = array(
            'FSUID_459E85AA5C2C23316709285CBED22B91',
            'FSUID_E1CF441FB0630D803E3FD27C99E05922',
            );
        return $yyylist;
    }
    
    //给全体发送消息
    //若要做单独接口，用末端的几行就行
    //为避免重复调用获取密钥接口，重新整合在这里，做好密钥本地存储更新后重做本部分
    public static function fxiaokesendMessage($appId,$appSecret,$permanentCode,$content)
    {
        $url = "https://open.fxiaoke.com/cgi/department/list";
        $corp = self::getCorp($appId,$appSecret,$permanentCode);
        $obj = json_encode(array(
            "corpAccessToken" => $corp['corpAccessToken'],
            "corpId" => $corp['corpId'],
            ));
        $rtnobj = self::post($url, $obj);
        $departmentlist = json_decode($rtnobj) -> departments;
        if (empty($openUserId)) {
            $openUserId = array();
            foreach ($departmentlist as $key1 => $value1) {
                $departmentId = $value1 -> id;
                $url = "https://open.fxiaoke.com/cgi/user/simpleList";
                $obj = json_encode(array(
                    "corpAccessToken"   => $corp['corpAccessToken'],
                    "corpId"            => $corp['corpId'],
                    "departmentId"      => $departmentId,
                    "fetchChild"        => true,
                    ));
                $rtnobj = self::post($url, $obj);
                $userlist = json_decode($rtnobj) -> userList;//网站上示例是userlist，实际为userList
                // print_r($userlist);die;
                foreach ($userlist as $key2 => $value2) {
                    $openUserId[] = $value2 -> openUserId;
                    // $openUserId[] = array(
                    //     'name'          => $value2 -> name,
                    //     'openUserId'    => $value2 -> openUserId
                    //     );
                }
            }
        }
        // print_r($openUserId);die;
        $url = "https://open.fxiaoke.com/cgi/message/send";
        $obj = json_encode(array(
            "corpAccessToken"   => $corp['corpAccessToken'],
            "corpId"            => $corp['corpId'],
            "toUser"            => $openUserId,
            "msgType"           => "text",
            "text"              => $content,
            ));
        // print_r($obj);die;
        $rtnobj = self::post($url, $obj);
        return $rtnobj;
    }

    //给指定人员发送消息
    //若要做单独接口，用末端的几行就行
    //为避免重复调用获取密钥接口，重新整合在这里，做好密钥本地存储更新后重做本部分
    public static function fxiaokedisendMessage($appId,$appSecret,$permanentCode,$content,$openUserId)
    {
        $corp = self::getCorp($appId,$appSecret,$permanentCode);
        $url = "https://open.fxiaoke.com/cgi/message/send";
        $obj = json_encode(array(
            "corpAccessToken"   => $corp['corpAccessToken'],
            "corpId"            => $corp['corpId'],
            "toUser"            => $openUserId,
            "msgType"           => "text",
            "text"              => $content,
            ));
        // print_r($obj);die;
        $rtnobj = self::post($url, $obj);
        return $rtnobj;
    }
}
