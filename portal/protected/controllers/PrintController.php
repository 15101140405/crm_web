<?php
class CMailFile
{ 

var $subject; 
var $addr_to; 
var $text_body; 
var $text_encoded; 
var $mime_headers; 
var $mime_boundary = "--==================_846811060==_"; 
var $smtp_headers; 

function CMailFile($subject,$to,$from,$msg,$filename,$downfilename,$mimetype = "application/octet-stream",$mime_filename = false) { 
$this->subject = $subject; 
$this->addr_to = $to; 
$this->smtp_headers = $this->write_smtpheaders($from); 
$this->text_body = $this->write_body($msg); 
$this->text_encoded = $this->attach_file($filename,$downfilename,$mimetype,$mime_filename); 
$this->mime_headers = $this->write_mimeheaders($filename, $mime_filename); 
} 

function attach_file($filename,$downfilename,$mimetype,$mime_filename) { 
$encoded = $this->encode_file($filename); 
if ($mime_filename) $filename = $mime_filename; 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-type: " . $mimetype . "; name=\"$filename\";\n"; 
$out = $out . "Content-Transfer-Encoding: base64\n"; 
$out = $out . "Content-disposition: attachment; filename=\"$downfilename\"\n\n"; 
$out = $out . $encoded . "\n"; 
$out = $out . "--" . $this->mime_boundary . "--" . "\n"; 
return $out; 
} 

function encode_file($sourcefile) { 
if (is_readable($sourcefile)) { 
$fd = fopen($sourcefile, "r"); 
$contents = fread($fd, filesize($sourcefile)); 
$encoded = chunk_split(base64_encode($contents)); 
fclose($fd); 
} 
return $encoded; 
} 

function sendfile() { 
$headers = $this->smtp_headers . $this->mime_headers; 
$message = $this->text_body . $this->text_encoded; 
mail($this->addr_to,$this->subject,$message,$headers); 
} 

function write_body($msgtext) { 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-Type: text/plain; charset=\"us-ascii\"\n\n"; 
$out = $out . $msgtext . "\n"; 
return $out; 
} 

function write_mimeheaders($filename, $mime_filename) { 
if ($mime_filename) $filename = $mime_filename; 
$out = "MIME-version: 1.0\n"; 
$out = $out . "Content-type: multipart/mixed; "; 
$out = $out . "boundary=\"$this->mime_boundary\"\n"; 
$out = $out . "Content-transfer-encoding: 7BIT\n"; 
$out = $out . "X-attachments: $filename;\n\n"; 
return $out; 
} 

function write_smtpheaders($addr_from) { 
$out = "From: $addr_from\n"; 
$out = $out . "Reply-To: $addr_from\n"; 
$out = $out . "X-Mailer: PHP3\n"; 
$out = $out . "X-Sender: $addr_from\n"; 
return $out; 
} 
} 

class PrintController extends InitController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main-not-exited';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions' => array(''),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionPad_print()
    {
        $post = json_decode(file_get_contents('php://input'));

        $html = $this->Pad_print_xuanran($post->order_id,$post->token);
        // $html = $this->Pad_print_xuanran(54,100);

        // echo $html;die;

        $fp = fopen("billtable.html","w");

        if(!$fp){
            echo "System Error";
            exit();
        }else {
            fwrite($fp,$html);
            fclose($fp);
            echo "Success";
        };

        
        

        //发送邮件 

        //主題 
        $subject = "报价单"; 

        //收件人 
        // $sendto = '80962715@qq.com'; 
        $sendto = $post->email; 
        echo $post->email;

        //發件人 
        //$replyto = '2837745713@qq.com'; 
        //$replyto = 'hunlicehuashi2016@126.com'; 
        $replyto = 'zhangsiheng0820@126.com'; 

        //內容 
        $message = "Hi,我的朋友，附件里是您做好报价单，下载后可直接打印，有问题随时找我哦。联系电话：15101140405 斯恒。祝愉快！"; 

        //附件 
        //$filename = "billtable".$_SESSION['userid'].".html"; 
        $filename = "billtable.html"; 
        //附件類別 
        //$mimetype = "billtable".$_SESSION['userid'].".html";  
        $mimetype = "billtable.html";  
        echo "1";

        $mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
        echo "2";
        $mailfile->sendfile(); 
        echo "3";

    }

    public function Pad_print_xuanran($order_id,$token)
    {
        // $post = json_decode(file_get_contents('php://input'));
        // $post=array(
        //         'order_id' => 54,
        //         'token' => 100,
        //     );

        // $order_detail = $this->Pad_print_data($post->order_id,$post->token);
        $order_detail = $this->Pad_print_data($order_id,$token);



/**********************************************************************************************************/
/**********************************************************************************************************/
/****************************************     CSS  部分     ************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

        $html = '<!DOCTYPE html>
<html>

<head>
    <title>报价单</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
</head>
<style>
*{margin:0;padding:0;border:0;outline:0;font:inherit;vertical-align:baseline;-webkit-tap-highlight-color:rgba(0,0,0,0)}
html,body,form,fieldset,p,div,h1,h2,h3,h4,h5,h6{-webkit-text-size-adjust:none}
html{font-size: 62.5%}
@media screen and (min-width:375px){
html{font-size:73.24%}
}
@media screen and (min-width:414px){
html{font-size:73.24%}
}
@media screen and (min-width:481px){
html{font-size:94%}
}
@media screen and (min-width:561px){
html{font-size:109%}
}
@media screen and (min-width:641px){
html{font-size:109%}
}
body{margin:0 auto;min-width:320px;font-family:"Heiti SC","Microsoft YaHei",Arial,"Helvetica";background:#f2f2f2;color:#333;font-size:1.2rem}
ol,ul,li{list-style:none}
table{border-collapse:collapse;border-spacing:0}
mark{background: none;}
h1,h2,h3,h4,h5,h6{font-weight:normal}
blockquote,q{quotes:none}
blockquote:before,blockquote:after,q:before,q:after{content:\'\';content:none}
strong,var,em,i{font-style:normal;font-weight:normal}
a{text-decoration:none;color:#333}
img{display:block;width:100%}
del{text-decoration:line-through}
.clear{height:0;overflow: hidden;clear: both;}
.float_l{float:left}
.float_r{float:right}
.mar_r10{margin-right:10px}
.mar_b10{margin-bottom:10px}
.arial{font-family:\'arial\'}
.ellipsis{overflow:hidden;white-space:nowrap;text-overflow:ellipsis}
.ellipsis_two{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.ellipsis_three{display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;}
.letter_middle{display:box;display:-webkit-box;box-orient:vertical;-webkit-box-orient:vertical;box-pack:center;-webkit-box-pack:center}
/******layout.css*********/
.box{
    display: -webkit-box;
    display: -moz-box;
    display: box;
}
.box.center {
    -webkit-box-pack: center;
    -moz-box-pack: center;
    box-pack: center;
    -webkit-box-align: center;
    -moz-box-align: center;
    box-align: center;
}
.box.h_center {
    -webkit-box-align: center;
    -moz-box-align: center;
    box-align: center;
}
.box.v_center:not(.vertical) {
    -webkit-box-pack: center;
    -moz-box-pack: center;
    box-pack: center;
}
.box.v_center.vertical {
    -webkit-box-orient: vertical;
    -moz-box-orient: vertical;
    box-orient: vertical;
    -webkit-box-pack: center;
    -moz-box-pack: center;
    box-pack: center;
}
.flexbox.h_center {
    display: -webkit-box;
    display: box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-pack: center;
    -moz-box-pack: center;
    box-pack: center;
    justify-content: center;
    -webkit-justify-content: center;
}
.flexbox.v_center:not(.vertical) {
    display: -webkit-box;
    display: box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -moz-box-align: center;
    box-align: center;
    align-items: center;
    -webkit-align-items: center;
}
.flexbox.v_center.vertical {
    display: -webkit-box;
    display: box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -moz-box-orient: vertical;
    box-orient: vertical;
    -webkit-box-pack: center;
    -moz-box-pack: center;
    box-pack: center;
    justify-content: center;
    -webkit-justify-content: center;
    flex-direction: column;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
}
.flexbox.center {
    display: -webkit-box;
    display: box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -moz-box-align: center;
    -webkit-box-pack: center;
    -moz-box-pack: center;
    box-align: center;
    box-pack: center;
    align-items: center;
    -webkit-align-items: center;
    justify-content: center;
    -webkit-justify-content: center;
}
/* start 弹性布局*/
.flexbox {
    display: -webkit-box;
    display: box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
}
.flexbox.reverse{
    flex-direction: row-reverse;
    -webkit-flex-direction: row-reverse;
    -ms-direction: row-reverse;
    -webkit-box-direction: reverse;
    -moz-box-direction: reverse;
    box-direction: reverse;
}
.flex1,
.flexitem {
    display: block;
    -webkit-box-flex: 1;
    -moz-box-flex: 1;
    -webkit-flex: 1;
    flex: 1;
}
.flex2{
    display: block;
    -webkit-box-flex: 2;
    -moz-box-flex: 2;
    -webkit-flex: 2;
    flex: 2;
}
.flex3{
    display: block;
    -webkit-box-flex: 3;
    -moz-box-flex: 3;
    -webkit-flex: 3;
    flex: 3;
}
.flex4{
    display: block;
    -webkit-box-flex: 4;
    -moz-box-flex: 4;
    -webkit-flex: 4;
    flex: 4;
}
.flex5{
    display: block;
    -webkit-box-flex: 5;
    -moz-box-flex: 5;
    -webkit-flex: 5;
    flex: 5;
}
.flex6{
    display: block;
    -webkit-box-flex: 6;
    -moz-box-flex: 6;
    -webkit-flex: 6;
    flex: 6;
}
.flex7{
    display: block;
    -webkit-box-flex: 7;
    -moz-box-flex: 7;
    -webkit-flex: 7;
    flex: 7;
}
.flex8{
    display: block;
    -webkit-box-flex: 8;
    -moz-box-flex: 8;
    -webkit-flex:8;
    flex: 8;
}
.flex9{
    display: block;
    -webkit-box-flex: 9;
    -moz-box-flex: 9;
    -webkit-flex:9;
    flex: 9;
}
/*end*/

/*栅格 start 2015/8/10 14:36:31*/
.grid {
    box-sizing: border-box;
    display: block;
    width: 100%;
}
.grid .row {
    box-sizing: border-box;
    display: block;
    width: 100%;
}

.grid .column_1,
.grid .column_2,
.grid .column_3,
.grid .column_4,
.grid .column_5,
.grid .column_6,
.grid .column_7,
.grid .column_8,
.grid .column_9,
.grid .column_10,
.grid .column_11,
.grid .column_12,
.grid .column1,
.grid .column2,
.grid .column3,
.grid .column4,
.grid .column5,
.grid .column6,
.grid .column7,
.grid .column8,
.grid .column9,
.grid .column10,
.grid .column11,
.grid .column12 {
    box-sizing: border-box;
    display: block;
    float: left;
}
.grid,
.grid .row,
.grid .column_1:before,
.grid .column_2:before,
.grid .column_3:before,
.grid .column_4:before,
.grid .column_5:before,
.grid .column_6:before,
.grid .column_7:before,
.grid .column_8:before,
.grid .column_9:before,
.grid .column_10:before,
.grid .column_11:before,
.grid .column_12:before,
.grid .column1:before,
.grid .column2:before,
.grid .column3:before,
.grid .column4:before,
.grid .column5:before,
.grid .column6:before,
.grid .column7:before,
.grid .column8:before,
.grid .column9:before,
.grid .column10:before,
.grid .column11:before,
.grid .column12:before {
    content: " ";
    display: table;
}
.grid,
.grid .column_1:after,
.grid .column_2:after,
.grid .column_3:after,
.grid .column_4:after,
.grid .column_5:after,
.grid .column_6:after,
.grid .column_7:after,
.grid .column_8:after,
.grid .column_9:after,
.grid .column_10:after,
.grid .column_11:after,
.grid .column_12:after,
.grid .column1:after,
.grid .column2:after,
.grid .column3:after,
.grid .column4:after,
.grid .column5:after,
.grid .column6:after,
.grid .column7:after,
.grid .column8:after,
.grid .column9:after,
.grid .column10:after,
.grid .column11:after,
.grid .column12:after {
    clear: both;
}

.grid .column_1,
.grid .column1{
    width: 8.33333333%;
}
.grid .column_2,
.grid .column2{
    width: 16.66666667%;
}
.grid .column_3,
.grid .column3{
    width: 25%;
}
.grid .column_4,
.grid .column4{
    width: 33.33333333%;
}
.grid .column_5,
.grid .column5{
    width: 41.66666667%;
}
.grid .column_6,
.grid .column6,{
    width: 50%;
}
.grid .column_7,
.grid .column7{
    width: 58.33333333%;
}
.grid .column_8,
.grid .column8{
    width: 66.66666667%;
}
.grid .column_9,
.grid .column9{
    width: 75%;
}
.grid .column_10,
.grid .column10{
    width: 83.33333333%;
}
.grid .column_11,
.grid .column11{
    width: 91.66666667%;
}
.grid .column_12,
.grid .column12{
    width: 100%;
}

.grid2:after,
.grid3:after,
.grid4:after,
.grid5:after,
.grid6:after,
.grid7:after,
.grid8:after,
.grid9:after,
.grid10:after,
.grid11:after,
.grid12:after {
    content: " ";
    display: table;
    clear: both;
}

.grid12>*,
.grid11>*,
.grid10>*,
.grid9>*,
.grid8>*,
.grid7>*,
.grid6>*,
.grid6>*,
.grid5>*,
.grid4>*,
.grid3>*,
.grid2>* {
    box-sizing: border-box;
    display: block;
    float: left;
}

.grid12>*:before,
.grid11>*:before,
.grid10>*:before,
.grid9>*:before,
.grid8>*:before,
.grid7>*:before,
.grid6>*:before,
.grid5>*:before,
.grid4>*:before,
.grid3>*:before,
.grid2>*:before{
    content: " ";
    display: table;
}

.grid12>*:after,
.grid11>*:after,
.grid10>*:after,
.grid9>*:after,
.grid8>*:after,
.grid7>*:after,
.grid6>*:after,
.grid5>*:after,
.grid4>*:after,
.grid3>*:after,
.grid2>*:after{
    clear:both;
}

.grid12>* {
    width: 8.333333333%;
}
.grid11>* {
    width: 9.0909090909%;
}
.grid10>* {
    width: 10%;
}
.grid9>* {
    width: 11.11111111%;
}
.grid8>* {
    width: 12.5%;
}
.grid7>* {
    width: 14.28571428%;
}
.grid6>* {
    width: 16.66666667%;
}
.grid5>* {
    width: 20%;
}
.grid4>* {
    width: 25%;
}
.grid3>* {
    width: 33.33333333%;
}
.grid2>* {
    width: 50%;
}
/*end*/




/*print.css*/
.print_module {
    padding: 4rem 1rem;
    background: #fff;
}
.print_top {
    margin-bottom: 3rem;
}
.print_title_box header {
    margin-bottom: 3rem;
}

.print_title_box header h1 {
    color: #666;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.print_title_box header p {
    color: #999;
}

.set_price_table_box {
    background: #f0efeb;
    padding: 1rem;
}

.set_price {
    border: none;
}

.set_price tr td {
    height: 2.3rem;
    width: 20%;
    font-size: 1rem;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #d4d4ca;
    border-bottom: 2px solid #d4d4ca;
    font-weight: bold;
}

.set_price tr:first-child td {
    border: none;
    border-bottom: 2px solid #d4d4ca;
    font-weight: normal;
}

.set_price tr td:first-child {
    border-bottom: 2px solid #000;
    border-left: none;
    border-right: none;
    color: #666;
    font-weight: normal;
}

.set_price tr td:last-child {
    border-right: none;
}

.set_price tr td:nth-child(2) {
    border-left: none;
}

.set_price tr td:nth-child(3),
.set_price tr td:last-child {
    background: #edeae5;
}

.set_price tr:first-child td,
.set_price tr:nth-child(4) td {
    background: none;
}

.set_price tr:nth-child(4) td {
    border-bottom: 2px solid #000;
}

.set_price tr:last-child td {
    padding-top: 1rem;
    border: none;
    background: none;
}

.set_price tr:last-child td .foot {
    height: 100%;
    text-align: left;
}
.set_price tr:last-child td .foot>div{
    overflow: hidden;
}
.set_price tr:last-child td .foot>div p{
    float: left;
    font-size: .9rem;
}
.option_table_box{
    margin-bottom: 2rem;
}
.option_table tr td {
    width: 12%;
    min-height: 1.8rem;
    line-height: 1.8rem;
    font-size: 1rem;
    text-align: right;
    padding-right: 1rem;
    border-bottom: 1px solid #c8cabd;
    border-right: 1px solid #c8cabd;
}

.option_table tr td:first-child {
    width: 28%;
}
.option_table tr td:first-child img{
    width: 82%;
}
.option_table tr td:last-child {
    width: 14%;
    border-right: none;
}

.option_table thead tr td {
    height: 2.5rem;
    line-height: 2.5rem;
    background: #a9a69d;
    text-align: center;
    padding: 0
}

.option_table tbody tr:nth-child(odd) td {
    background: #f0efeb;
}

.option_table tbody tr:nth-child(even) td {
    background: #edeae5;
}

.option_table thead tr td:first-child {
    background: #fff;
}

.option_table tbody tr td:first-child,
.option_table tbody tr td:nth-child(4) {
    background: #fff;
}

.option_table tbody tr:last-child td {
    border-bottom: 2px solid #a9a69d;
}

.option_table tfoot tr td {
    border-bottom: 2px solid #a9a69d;
}
.option_table tfoot tr td:first-child{
    border-right: none;
}
.option_table tfoot tr td:last-child {
    background: #a9a69d;
}
.total.bule{
    color: #3f95e0;
    font-weight: bold;
}
.total.green{
    color: #83ac2c;
    font-weight: bold;
}
.total.sl{
    color: #0c3861;
    font-weight: bold;
}


</style>';





/**********************************************************************************************************/
/**********************************************************************************************************/
/****************************************     Html 部分     ************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

$service = 10;
$decorat = 10;
$light = 10;
foreach ($order_detail['order_data']['discount']['list'] as $key => $value) {
    if($value['select'] == 1){
        if($value['id'] == 3 || $value['id'] == 4 || $value['id'] == 5 || $value['id'] == 6 || $value['id'] == 7){
            $service = $order_detail['order_data']['discount']['other_discount'];
        }else if($value['id'] == 20){
            $decorat = $order_detail['order_data']['discount']['other_discount'];
        }else if($value['id'] == 8 || $value['id'] == 9 || $value['id'] == 23){
            $light = $order_detail['order_data']['discount']['other_discount'];
        };
    };  
};


        $html .= '<body>
    <article class="print_module">
        <div class="print_top flexbox">
            <div class="print_title_box flex1">

                <header>
                    <h1>'.$order_detail['order_data']['order_name'].'</h1>
                    <p class="address">地址：<span>'.$order_detail['order_data']['hotel_name'].'</span>
                    </p>
                    <p class="date">日期：<span>'.$order_detail['order_data']['order_date'].'</span>
                    </p>
                </header>

                <div class="set_price_table_box">
                    <table class="set_price" width=100%>
                        <tr>
                            <td></td>
                            <td>婚宴</td>
                            <td>服务人员</td>
                            <td>场地布置</td>
                            <td>灯光音响视频</td>
                        </tr>
                        <tr>
                            <td><img src="http://file.cike360.com/image/print_hy.png" alt=""></td>
                            <td>'.$order_detail['order_data']['discount']['feast_discount'].'</td>
                            <td>'.$service.'</td>
                            <td>'.$decorat.'</td>
                            <td>'.$light.'</td>
                        </tr>
                        <tr>
                            <td><img src="http://file.cike360.com/image/print_ml.png" alt=""></td>
                            <td colspan="4">9</td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="foot flexbox v_center">
                                    <div class="flex1">
                                        <p>婚宴销售:<br/><img src="http://file.cike360.com/image/print_hlyh.png" alt="" style="width:4rem;"></p>
                                        <p>
                                            <span class="name">'.$order_detail['order_data']['planner_name'].' </span><span class="tel">'.$order_detail['order_data']['planner_phone'].'</span>
                                        </p>
                                    </div>
                                    <div class="flex1">
                                        <p>婚礼策划:<br/><img src="http://file.cike360.com/image/print_hlch.png" alt="" style="width:6.2rem;"></p>
                                        <p>
                                            <span class="name">'.$order_detail['order_data']['designer_name'].'</span><span class="tel">'.$order_detail['order_data']['designer_phone'].'</span>
                                        </p>
                                
                                    </div>
                                </div>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--echart-->
            <div class="chart_box flexbox center">
                <div id="main" style="width: 500px;height:450px;">
                    
                </div>
            </div>
        </div>';

        foreach ($order_detail['set_data']['feast'] as $key_t => $value_t) {

            $html .= 
            '<section class="option_table_box">
                <div class="option_table">
                    <table width=100%;>
                        <thead>
                            <tr>
                                <td><img src="http://file.cike360.com/image/print_feast.png" alt=""></td>
                                <td>Amount</td>
                                <td>Unit</td>
                                <td>Price</td>
                                <td>Remark</td>
                                <td>Total</td>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($value_t['product_list'] as $key => $value) {
                $html .= '<tr>
                            <td>'.$value['product_name'].'</td>
                            <td>'.$value['amount'].'</td>
                            <td>'.$value['unit'].'</td>
                            <td>'.$value['price'].'</td>
                            <td>'.$value['description'].'</td>
                            <td>'.$value['price']*$value['amount'].'</td>
                        </tr>';
            };
                            
            $html .=                
                        '</tbody>
                        <tfoot>
                            <tr>
                                <td class="total bule">总计</td>
                                <td colspan="4"></td>
                                <td>'.$value_t['total_price'].'</td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </section>';
        };

        foreach ($order_detail['area_product'] as $key_t => $value_t) {

            $html .= 
            '<section class="option_table_box">
                <div class="option_table">
                    <table width=100%;>
                        <thead>
                            <tr>
                                <td><img src="http://file.cike360.com/image/print_t0'.($key_t+1).'.png" alt=""></td>
                                <td>Amount</td>
                                <td>Unit</td>
                                <td>Price</td>
                                <td>Remark</td>
                                <td>Total</td>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($value_t['product_list'] as $key => $value) {
                $html .= '<tr>
                            <td>'.$value['product_name'].'</td>
                            <td>'.$value['amount'].'</td>
                            <td>'.$value['unit'].'</td>
                            <td>'.$value['price'].'</td>
                            <td>'.$value['description'].'</td>
                            <td>'.$value['price']*$value['amount'].'</td>
                        </tr>';
            };
                            
            $html .=                
                        '</tbody>
                        <tfoot>
                            <tr>
                                <td class="total bule">总计</td>
                                <td colspan="4"></td>
                                <td>'.$value_t['area_total'].'</td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </section>';
        };













/**********************************************************************************************************/
/**********************************************************************************************************/
/****************************************     Js   部分     ************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/


$total_price = 0;
$feast_total = 0;
foreach ($order_detail['area_product'] as $key => $value) {
    $total_price += $value['area_total'];
};
foreach ($order_detail['non_area_product'] as $key => $value) {
    $total_price += $value['price']*$value['amount'];
};
foreach ($order_detail['set_data']['feast'] as $key => $value) {
    $total_price += $value['total_price'];
    $feast_total += $value['total_price'];
};
$decorat = $order_detail['order_data']['type_price']['decorat'];
$service = $order_detail['order_data']['type_price']['service'];
$light = $order_detail['order_data']['type_price']['light'];

$wedding_room = $order_detail['area_product'][0]['discount_total'];
$outside = $order_detail['area_product'][1]['discount_total'];
$qiandao = $order_detail['area_product'][2]['discount_total'];
$yishi = $order_detail['area_product'][3]['discount_total'];


        $html.=<<<EOF
</article>
<script src="http://file.cike360.com/js/zepto.min.js"></script>
<script src="http://file.cike360.com/js/echarts.min.js"></script>
<script>
var myChart = echarts.init(document.getElementById('main'));
option = {
    tooltip: {
        trigger: 'item',
        formatter: "{a} <br/>{b}: {c} ({d}%)"
    }, 
    legend: {
        orient: 'horizontal',
        y: 'bottom',
        x: '20',
        itemWidth: 15,
        textStyle: {
            fontSize: 10,
            color: '#999',
        },
        data: ['新房布置' ,'酒店室外', '签到区', '仪式区', '服务人员', '灯光／音响／视频', '场地布置', '婚宴']
    }, 
    series: [
        {
            name: '分类',
            type: 'pie',
            selectedMode: 'single',
            radius: ['30%', '50%'],
            itemStyle: {
                normal: {
                    label: {
                        show: false,
                        normal: {
                            position: 'inner'
                        }
                    },
                    labelLine: {
                        show: false
                    }
                }
            },
            data: [
                {
                    value: $service,
                    name: '服务人员',
                    itemStyle: {
                        normal: {
                            color: '#bf21ec'
                        }
                    }
                },      
                {
                    value: $light,
                    name: '灯光／音响／视频',
                    itemStyle: {
                        normal: {
                            color: '#63d0ca',
                        }
                    }
                },
                {
                    value: $decorat,
                    name: '场地布置',
                    itemStyle: {
                        normal: {
                            color: '#f3e434'
                        }
                    }
                },
                {
                    value: $feast_total,
                    name: '婚宴',
                    itemStyle: {
                        normal: {
                            color: '#3d94cd'
                        }
                    }
                }
            ]
        },
        {
            name: '总价',
            type: 'pie',
            selectedMode: '',
            radius: ['0', '32%'],
            itemStyle: {
                normal: {
                    label: {
                        show: true,
                        position: 'center',
                        textStyle: {
                            fontSize: '20',
                            fontWeight: 'bold',
                            color: '#333',
                        },
                        formatter: function (data) {
                            return data.name + '\\n' + data.value;
                        },
                    },
                    labelLine: {
                        show: false
                    }
                }
            },
            labelLine: {
                normal: {
                    show: false
                }
            },
            data: [
                {
                    value: '￥$total_price',
                    name: '总价TOTAL',
                    itemStyle: {
                        normal: {
                            color: '#fff'
                        }
                    }
                },      
                ]
        },   
        {
            name: '区域',
            type: 'pie',
            radius: ['54%', '73%'],
            itemStyle: {
                normal: {
                    label: {
                        show: false
                    },
                    labelLine: {
                        show: false
                    }
                }
            },
            data: [
                {
                    value: $wedding_room,
                    name: '新房布置',
                    itemStyle: {
                        normal: {
                            color: '#3d94cd'
                        }
                    }
                }, 
                {
                    value: $outside,
                    name: '酒店室外',
                    itemStyle: {
                        normal: {
                            color: '#84ac25'
                        }
                    }
                },
                {
                    value: $qiandao,
                    name: '签到区',
                    itemStyle: {
                        normal: {
                            color: '#7eb495'
                        }
                    }
                },
                {
                    value: $yishi,
                    name: '仪式区',
                    itemStyle: {
                        normal: {
                            color: '#ff577b'
                        }
                    }
                }
            ]
        }
    ]
};
myChart.setOption(option);

$(function () {
    $(".print_btn").on('click', function () {
        $(".print_module_mask").addClass('show');
    })
    $(".print_module_mask").on('click', function (event) {
        if (event.target == this) {
            $(this).removeClass('show');
        }
    })
})
</script>
</body>

</html>
EOF;

        return $html;


        // echo json_encode($order_detail);
    }

    public function Pad_print_data($order_id,$token)
    {
        /************************************************************************/
        /************************************************************************/
        /******** CR_ID 构造规则：  ***********************************************/
        /******** 场地布置 10000000          ＋ sp_id     *************************/
        /******** 灯光／音响／视频  30000000   ＋  sp_id    *************************/
        /******** PPT 里的纯“图片” 60000000   ＋   show_id *************************/
        /******** 餐饮零点  90000000          +  sp_id     ************************/
        /******** 服务人员 120000000          ＋ CI_ID     ************************/
        /************************************************************************/
        /************************************************************************/



        // $post = json_decode(file_get_contents('php://input'));

        //取本订单 当前在order_show里的数据
        $result = yii::app()->db->createCommand("select s.id,s.type,i.img_url,s.order_product_id,sp.ref_pic_url,sp.supplier_type_id,sp.id as sp_id,words,show_area,area_sort ".
            "from order_show s ".
            "left join order_show_img i on s.img_id=i.id ".
            "left join order_product op on s.order_product_id=op.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "where s.order_id=".$order_id);
        $result = $result->queryAll();

        // echo json_encode($result);die;

        foreach ($result as $key => $value) {
            $t1 = explode('.', $value['ref_pic_url']);
            if(isset($t1[0]) && isset($t1[1])){
                $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$t1[0]."_sm.".$t1[1];
            }else{
                $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
            };
            $t2 = explode('.', $value['img_url']);
            if(isset($t2[0]) && isset($t2[1])){
                $result[$key]['img_url'] = 'http://file.cike360.com'.$t2[0]."_sm.".$t2[1];
            }else{
                $result[$key]['img_url'] = 'http://file.cike360.com'.$value['img_url'];
            };
        };

        //取本订单里的  order_product
        $result1 = yii::app()->db->createCommand("select op.id,op.order_set_id,o.other_discount,o.discount_range,o.feast_discount,ws.category as set_category,ws.name as set_name,st.name,op.actual_price,op.unit as amount,op.actual_unit_cost,op.actual_service_ratio,sp.name as product_name,sp.description,sp.ref_pic_url,sp.supplier_type_id,sp.unit,sp.id as sp_id,op.order_set_id,os.show_area ".
            "from order_product op ".
            "left join `order` o on op.order_id=o.id ".
            "left join order_show os on op.id=os.order_product_id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "left join supplier_type st on sp.supplier_type_id=st.id ".
            "left join order_set on op.order_set_id=order_set.id ".
            "left join wedding_set ws on order_set.wedding_set_id=ws.id ".
            "where op.order_id=".$order_id);
        $result1 = $result1->queryAll(); 

        foreach ($result1 as $key => $value) {
            $t1 = explode('.', $value['ref_pic_url']);
            if(isset($t1[0]) && isset($t1[1])){
                $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$t1[0]."_sm.".$t1[1];
            }else{
                $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
            };
        };

        //取本订单数据
        $order = Order::model()->findByPk($order_id);

        // *******************************************************
        // *****************   构造 订单基本信息    *****************
        // *******************************************************
        
        $result4 = yii::app()->db->createCommand("select o.id,o.order_name,feast_discount,other_discount,discount_range,cut_price,planner_id,s1.name as planner_name,s1.telephone as planner_phone,designer_id,s2.name as designer_name,s2.telephone as designer_phone,staff_hotel_id,sh.name as hotel_name,groom_name,groom_phone,groom_wechat,groom_qq,bride_name,bride_phone,bride_phone,bride_wechat,bride_qq,contact_name,contact_phone ".
            "from `order` o ".
            "left join staff_hotel sh on o.staff_hotel_id=sh.id ".
            "left join staff s1 on planner_id=s1.id ".
            "left join staff s2 on designer_id=s2.id ".
            "left join order_wedding ow on o.id=ow.order_id ".
            // "left join order_product op on o.id=op.order_id ".
            // "left join supplier_product sp on op.product_id=sp.id ".
            "where o.id=".$order_id/*." and sp.supplier_type_id=16"*/);
        $result4 = $result4->queryAll();
        // print_r($result4);die;


        //构造统筹师、策划师列表
        $staff = yii::app()->db->createCommand("select * from staff where account_id in (select account_id from staff where id=".$token.")");
        $staff = $staff->queryAll();

        $designer_list = array();
        $planner_list = array();

        foreach ($staff as $key => $value) {
            $t = rtrim($value['department_list'], "]");
            $t = ltrim($t, "[");
            $t = explode(',', $t);
            $d = 0;
            $p = 0;
            foreach ($t as $key_t => $value_t) {
                if($value_t == 2){$p++;};
                if($value_t == 3){$d++;};
            };
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            if($d != 0){
                $designer_list[]=$item;
            };
            if($p != 0){
                $planner_list[]=$item;
            };
        }

        //构造渠道列表
        $result5 = new ProductForm;
        $tuidan_list = $result5->tuidan_list($token);

        //构造折扣范围列表
        $discount = array();
        if(!empty($result4)){
            $discount['feast_discount'] = $result4[0]['feast_discount'];
            $discount['other_discount'] = $result4[0]['other_discount'];
            $discount['list'] = array();
            $descount_list = yii::app()->db->createCommand("select id,name from supplier_type where role=1");
            $descount_list = $descount_list->queryAll();
            if($discount['other_discount'] != 10 && isset($result4[0]['discount_range'])){
                $t=explode(',', $result4[0]['discount_range']);
                foreach ($descount_list as $key => $value) {
                    $item = array();
                    $item['id']=$value['id'];
                    $item['name']=$value['name'];
                    $item['select']=0;
                    $m=0;
                    foreach ($t as $key_t => $value_t) {
                        if($value['id'] == $value_t){
                            $m++;
                        };
                    };
                    if($m!=0){
                        $item['select']=1;
                    };
                    $discount['list'][]=$item;
                };
            }else{
                foreach ($descount_list as $key => $value) {
                    $item = array();
                    $item['id']=$value['id'];
                    $item['name']=$value['name'];
                    $item['select']=1;
                    $discount['list'][]=$item;
                };
            };
        };
        // echo json_encode($discount['list']);die;

        //产品按分类计算总价
        $type_price = array(
                'service' => 0,
                'decorat' => 0,
                'light' => 0,
            );

        foreach ($result1 as $key => $value) {
            if($value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7){
                $type_price['service'] += $value['actual_price']*$value['amount'];
            }else if($value['supplier_type_id'] == 20){
                $type_price['decorat'] += $value['actual_price']*$value['amount'];
            }else if($value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){
                $type_price['light'] += $value['actual_price']*$value['amount'];
            };
        };
            
        // print_r($result3);die;
        $order_data = array(
                "id"=> $result4[0]['id'] ,
                "order_name"=> $result4[0]['order_name'] ,
                "planner_id"=> $result4[0]['planner_id'] ,
                "planner_name"=> $result4[0]['planner_name'] ,
                "planner_phone"=> $result4[0]['planner_phone'] ,
                "designer_id"=> $result4[0]['designer_id'] ,
                "designer_name"=> $result4[0]['designer_name'] ,
                "designer_phone"=> $result4[0]['designer_phone'] ,
                "staff_hotel_id"=> $result4[0]['staff_hotel_id'] ,
                "hotel_name"=> $result4[0]['hotel_name'] ,
                "groom_name"=> $result4[0]['groom_name'] ,
                "groom_phone"=> $result4[0]['groom_phone'] ,
                "groom_wechat"=> $result4[0]['groom_wechat'] ,
                "groom_qq"=> $result4[0]['groom_qq'] ,
                "bride_name"=> $result4[0]['bride_name'] ,
                "bride_phone"=> $result4[0]['bride_phone'] ,
                "bride_wechat"=> $result4[0]['bride_wechat'] ,
                "bride_qq"=> $result4[0]['bride_qq'] ,
                "contact_name"=> $result4[0]['contact_name'] ,
                "contact_phone"=> $result4[0]['contact_phone'],
                'designer_list'=> $designer_list,
                'planner_list'=> $planner_list,
                'tuidan_list'=> $tuidan_list,
                'discount'=> $discount,
                'cut_price'=> $result4[0]['cut_price'],
                'type_price'=> $type_price
            );        
        $t=explode(' ', $order['order_date']);
        $order_data['order_date']=$t[0];
        $result3 = yii::app()->db->createCommand("select sp.id,sp.name from order_product op left join supplier_product sp on product_id=sp.id where op.order_id=".$order_id." and sp.supplier_type_id=16");
        $result3 = $result3->queryAll();
        if(!empty($result3)){
            $order_data['tuidan_id']=$result3[0]['id'];
            $order_data['tuidan_name']=$result3[0]['name'];
        }else{
            $order_data['tuidan_id']="";
            $order_data['tuidan_name']="";
        };
        // print_r($order_data);die;


        // *******************************************************
        // ********************   构造报价单    ********************
        // *******************************************************

        //有区域产品，按区域分组，并加总，计算出总价、折后总价；
        $area_product = array();
        $area = OrderShowArea::model()->findAll();
        $discount_range=explode(',', $order['discount_range']);
        foreach ($area as $key => $value) {
            if($value['id'] != 1){
                $tem=array();
                $tem['area_id']=$value['id'];
                $tem['area_name']=$value['name'];
                $tem['product_list'] = array();
                $tem['area_total'] = 0;
                $tem['discount_total'] = 0;
                foreach ($result1 as $key_p => $value_p) {
                    if($value['id'] == $value_p['show_area']){
                        $item=array();
                        $item['product_id']=$value_p['id'];
                        $item['product_name']=$value_p['product_name'];
                        $item['description']=$value_p['description'];
                        $item['ref_pic_url']=$value_p['ref_pic_url'];
                        $item['price']=$value_p['actual_price'];
                        $item['amount']=$value_p['amount'];
                        $item['unit']=$value_p['unit'];
                        $item['cost']=$value_p['actual_unit_cost'];
                        $item['set']="";

                        //构造 CR_ID  top
                        if($value_p['supplier_type_id'] == 20){
                            $item['CR_ID'] = 10000000 + $value_p['sp_id'];
                        }else if($value_p['supplier_type_id'] == 8 || $value_p['supplier_type_id'] == 9 || $value_p['supplier_type_id'] == 23){
                            $item['CR_ID'] = 30000000 + $value_p['sp_id'];
                        }else if($value_p['supplier_type_id'] == 3 || $value_p['supplier_type_id'] == 4 || $value_p['supplier_type_id'] == 5 || $value_p['supplier_type_id'] == 6 || $value_p['supplier_type_id'] == 7){
                            $CI_Type = 0;
                            if($value_p['supplier_type_id'] == 3){$CI_Type=6;};
                            if($value_p['supplier_type_id'] == 4){$CI_Type=13;};
                            if($value_p['supplier_type_id'] == 5){$CI_Type=14;};
                            if($value_p['supplier_type_id'] == 6){$CI_Type=15;};
                            if($value_p['supplier_type_id'] == 7){$CI_Type=21;};
                            $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value_p['sp_id']);
                            $result7 = $result7->queryAll();
                            // print_r($result7);die;
                            if(isset($result7[0])){
                                    $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                                };
                        }
                        //构造 CR_ID  end


                        $t = 0;
                        foreach ($discount_range as $key_r => $value_r) {
                            if($value_r == $value_p['supplier_type_id']){
                                $t++;
                            };
                        };
                        if($t!=0){
                            $item['discount'] = $order['other_discount']*0.1;
                            $tem['discount_total'] += $item['price']*$item['amount']*$order['other_discount'];
                        }else{
                            $item['discount'] = 1;
                            $tem['discount_total'] += $item['price']*$item['amount'];
                        }

                        if($value_p['order_set_id'] != 0){
                            $item['set']="套系产品";
                        };
                        $tem['product_list'][]=$item;
                        $tem['area_total'] += $item['price']*$item['amount'];
                    };
                };
                $area_product[] = $tem;
            };
        };

        //无区域产品，列出来
        $non_area_product = array();
        foreach ($result1 as $key => $value) {
            if($value['show_area'] == 0 && $value['supplier_type_id']!=2){
                $discount_range = rtrim($value['discount_range'], ",");
                $discount_range = explode(',', $discount_range);
                $r = 0;
                foreach ($discount_range as $key_dr => $value_dr) {
                    if($value_dr == $value['supplier_type_id']){
                        $r++;
                    };
                };
                $item=array();
                $item['product_id']=$value['id'];
                $item['product_name']=$value['product_name'];
                $item['description']=$value['description'];
                $item['ref_pic_url']=$value['ref_pic_url'];
                $item['price']=$value['actual_price'];
                $item['amount']=$value['amount'];
                $item['unit']=$value['unit'];
                $item['cost']=$value['actual_unit_cost'];
                $item['set']="";

                //构造 CR_ID  top
                if($value_p['supplier_type_id'] == 20){
                    $item['CR_ID'] = 10000000 + $value_p['sp_id'];
                }else if($value_p['supplier_type_id'] == 8 || $value_p['supplier_type_id'] == 9 || $value_p['supplier_type_id'] == 23){
                    $item['CR_ID'] = 30000000 + $value_p['sp_id'];
                }else if($value_p['supplier_type_id'] == 3 || $value_p['supplier_type_id'] == 4 || $value_p['supplier_type_id'] == 5 || $value_p['supplier_type_id'] == 6 || $value_p['supplier_type_id'] == 7){
                    $CI_Type = 0;
                    if($value_p['supplier_type_id'] == 3){$CI_Type=6;};
                    if($value_p['supplier_type_id'] == 4){$CI_Type=13;};
                    if($value_p['supplier_type_id'] == 5){$CI_Type=14;};
                    if($value_p['supplier_type_id'] == 6){$CI_Type=15;};
                    if($value_p['supplier_type_id'] == 7){$CI_Type=21;};
                    $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value_p['sp_id']);
                    $result7 = $result7->queryAll();
                    // print_r($result7);die;
                    if(isset($result7[0])){
                        $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                    };
                }
                //构造 CR_ID  end

                if($r == 0){
                    $item['discount'] = 1;
                }else{
                    $item['discount'] = $value['other_discount']*0.1;
                };
                if($value['order_set_id'] != 0){
                    $item['set']="套系产品";
                };
                $non_area_product[]=$item;
            };
        };

        //取套餐数据（婚宴、婚礼）
        $result2 = yii::app()->db->createCommand("select os.id,os.order_id,os.amount,os.actual_service_ratio,os.remark,ws.id as ws_id,ws.name,ws.category ".
            " from order_set os left join wedding_set ws on wedding_set_id=ws.id ".
            " where os.order_id=".$order_id);
        $result2 = $result2->queryAll();

        $set_data = array();
        $set_data['feast']=array();
        $set_data['other']=array();
        foreach ($result2 as $key_s => $value_s) {
            $tem=array();
            $tem['order_set_id']=$value_s['id'];
            $tem['set_name']=$value_s['name'];
            $tem['amount']=$value_s['amount'];
            $tem['actual_service_ratio']=$value_s['actual_service_ratio'];
            $tem['remark']=$value_s['remark'];
            $tem['total_price']=0;
            $tem['product_list']=array();
            foreach ($result1 as $key_p => $value_p) {
                if($value_p['order_set_id'] == $value_s['id']){
                    $item=array();
                    $item['product_id']=$value_p['id'];
                    $item['product_name']=$value_p['product_name'];
                    $item['description']=$value_p['description'];
                    $item['ref_pic_url']=$value_p['ref_pic_url'];
                    $item['price']=$value_p['actual_price'];
                    $item['amount']=$value_p['amount'];
                    $item['unit']=$value_p['unit'];
                    $item['cost']=$value_p['actual_unit_cost'];
                    $item['CR_ID']=90000000 + $value_p['sp_id'];
                    if($value_p['supplier_type_id'] == 2){
                        $item['discount'] = $value_p['feast_discount'];
                    }else{
                        $t=explode(',', $value_p['discount_range']);
                        $m=0;
                        foreach ($t as $key_tm => $value_tm) {
                            if($value_tm == $value_p['supplier_type_id']){
                                $m++;
                            };
                        };
                        if($m == 0){
                            $item['discount'] = 1;
                        }else{
                            $item['discount'] = $value_p['other_discount']*0.1;
                        };
                    };
                    $tem['product_list'][]=$item;
                    if($value_s['category'] == 3 || $value_s['category'] == 4){
                        $tem['total_price'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01);
                    }else{
                        $tem['total_price'] += $value_p['actual_price']*$value_p['amount'];
                    };
                };
            };
            foreach ($result1 as $key_p => $value_p) {
                if($value_p['supplier_type_id'] == 2 && $value_p['order_set_id'] == 0){
                    $item=array();
                    $item['product_id']=$value_p['id'];
                    $item['product_name']=$value_p['product_name'];
                    $item['description']=$value_p['description'];
                    $item['ref_pic_url']=$value_p['ref_pic_url'];
                    $item['price']=$value_p['actual_price'];
                    $item['amount']=$value_p['amount'];
                    $item['unit']=$value_p['unit'];
                    $item['CR_ID']=90000000 + $value_p['sp_id'];
                    $item['cost']=$value_p['actual_unit_cost'];
                    if($value_s['category'] == 3 || $value_s['category'] == 4){
                        $tem['product_list'][]=$item;
                        $tem['total_price'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01);
                    };
                };
            };
            if($value_s['category']==3 || $value_s['category']==4){
                $set_data['feast'][]=$tem;
            }else{
                $set_data['other'][]=$tem;
            };
        };

        $order_detail = array();
        $order_detail['order_data'] = $order_data;
        $order_detail['area_product'] = $area_product;
        $order_detail['non_area_product'] = $non_area_product;
        $order_detail['set_data'] = $set_data;

        return $order_detail; 
    }

    public function actionDesignbill()
    {
        $Order = Order::model()->findByPk($_POST['order_id']);
        $date = explode(" ",$Order['order_date']);
        $t = new StaffForm();
        $wed = OrderWedding::model()->find(array(
            'condition' => 'order_id=:order_id',
            'params' => array(
                ':order_id' => $_POST['order_id']
            )
        ));


        $order_data = array();
        $order_data['id']='W'.$_POST['order_id'].'-'.$date[0];
        $order_data['feast_discount']=$Order['feast_discount'];
        $order_data['other_discount']=$Order['other_discount'];
        $order_data['cut_price']=$Order['cut_price'];
        $order_data['designer_name']=$t->getName($Order['designer_id']);
        $order_data['groom_name']=$wed['groom_name'];
        $order_data['groom_phone']=$wed['groom_phone'];
        $order_data['bride_name']=$wed['bride_name'];
        $order_data['bride_phone']=$wed['bride_phone'];

        //print_r($order_data);die;

        $orderId = $_POST['order_id'];
        $supplier_product_id = array();
        $wed_feast = array();
        $arr_wed_feast = array();

        $order_discount = Order::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $orderId),
        ));

        /*print_r($order_discount['other_discount']);die;
*/
        /*********************************************************************************************************************/
        /*取餐饮数据*/
        /*********************************************************************************************************************/
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 2),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/
        if(!empty($supplier_id)){
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $criteria1->addCondition("category=:category");
            $criteria1->params[':category']=2; 
            $supplier_product = SupplierProduct::model()->findAll($criteria1);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = $value->id;
                $supplier_product_id[] = $item;
            };
            /*print_r($supplier_product_id);*/
        }
        
        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $wed_feast[] = $item;
            };
            /*print_r($wed_feast);*/
        }
        /*print_r($wed_feast);*/
        
        if(!empty($wed_feast)){
            $criteria3 = new CDbCriteria; 
            $criteria3->addCondition("id=:id");
            $criteria3->params[':id']=$wed_feast[0]['product_id']; 
            $supplier_product2 = SupplierProduct::model()->find($criteria3);
            /*print_r($supplier_product2);*/
            $arr_wed_feast = array(
                'name' => $supplier_product2['name'],
                'unit_price' => $wed_feast[0]['actual_price'],
                'unit' => $supplier_product2['unit'],
                'table_num' => $wed_feast[0]['unit'],
                'service_charge_ratio' => $wed_feast[0]['actual_service_ratio'],
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01)*$order_discount['feast_discount']*0.1,
                'total_cost' => $wed_feast[0]['actual_unit_cost']*$wed_feast[0]['unit'],
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01,
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01)/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01)),
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_wed_feast);*/

        /*********************************************************************************************************************/
        /*取灯光数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_light = array();
        $light = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 8),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $light[] = $item;
            };
        }
        if (!empty($light)) {
            $arr_light_total['total_price']=0;
            $arr_light_total['total_cost']=0;
            foreach ($light as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$light[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $light[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $light[$key]['unit'],
                );
                $arr_light[]=$item;
                $arr_light_total['total_price'] += $light[$key]['actual_price']*$light[$key]['unit'];
                $arr_light_total['total_cost'] +=$light[$key]['actual_unit_cost']*$light[$key]['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            if($arr_light_total['total_price'] != 0){
                $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];    
            }else if($arr_light_total['total_cost'] != 0){
                $arr_light_total['gross_profit_rate'] = 0;
            }     
        }else{
            $arr_light_total['gross_profit']=0;
            $arr_light_total['gross_profit_rate']=0;
            $arr_light_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }

        /*print_r($arr_light_total);*/

        /*********************************************************************************************************************/
        /*取视频数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_video = array();
        $arr_video_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 9),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            $video = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $video[] = $item;
            };
            /*print_r($video);*/
        }

        if (!empty($video)) {
            $arr_video_total['total_price']=0;
            $arr_video_total['total_cost']=0;
            foreach ($video as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$video[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $video[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $video[$key]['unit'],
                );
                $arr_video[]=$item;
                $arr_video_total['total_price'] += $video[$key]['actual_price']*$video[$key]['unit'];
                $arr_video_total['total_cost'] +=$video[$key]['actual_unit_cost']*$video[$key]['unit'];
            }
            
                $arr_video_total['gross_profit']=$arr_video_total['total_price']-$arr_video_total['total_cost'];
            if($arr_video_total['total_price'] != 0){
                $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];    
            }else if($arr_video_total['total_cost'] != 0){
                $arr_video_total['gross_profit_rate'] = 0;
            }           
            
        }

        /*print_r($arr_video_total);*/

        /*********************************************************************************************************************/
        /*取主持人数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_host = array();
        $arr_host_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 3),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            $host = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $host[] = $item;
            };
            /*print_r($host);*/
        }
        if (!empty($host)) {
            $arr_host_total['total_price']=0;
            $arr_host_total['total_cost']=0;
            foreach ($host as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$host[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $host[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $host[$key]['unit'],
                );
                $arr_host[]=$item;
                $arr_host_total['total_price'] += $host[$key]['actual_price']*$host[$key]['unit'];
                $arr_host_total['total_cost'] +=$host[$key]['actual_unit_cost']*$host[$key]['unit'];
            }        
            $arr_host_total['gross_profit']=$arr_host_total['total_price']-$arr_host_total['total_cost'];
            if($arr_host_total['total_price'] != 0){
                $arr_host_total['gross_profit_rate']=$arr_host_total['gross_profit']/$arr_host_total['total_price'];    
            }else if($arr_host_total['total_cost'] != 0){
                $arr_host_total['gross_profit_rate'] = 0;
            }   
        }

        /*print_r($arr_host);*/


        /*********************************************************************************************************************/
        /*取摄像数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_camera = array();
        $camera = array();
        $arr_camera_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 4),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $camera[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($camera)) {
            $arr_camera_total['total_price']=0;
            $arr_camera_total['total_cost']=0;
            foreach ($camera as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$camera[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $camera[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $camera[$key]['unit'],
                );
                $arr_camera[]=$item;
                $arr_camera_total['total_price'] += $camera[$key]['actual_price']*$camera[$key]['unit'];
                $arr_camera_total['total_cost'] +=$camera[$key]['actual_unit_cost']*$camera[$key]['unit'];
            }           
            $arr_camera_total['gross_profit']=$arr_camera_total['total_price']-$arr_camera_total['total_cost'];
            if($arr_camera_total['total_price'] != 0){
                $arr_camera_total['gross_profit_rate']=$arr_camera_total['gross_profit']/$arr_camera_total['total_price'];    
            }else if($arr_camera_total['total_cost'] != 0){
                $arr_camera_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取摄影数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_photo = array();
        $photo = array();
        $arr_photo_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 5),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $photo[] = $item;
            };
            /*print_r($photo);*/
        }
        if (!empty($photo)) {
            $arr_photo_total['total_price']=0;
            $arr_photo_total['total_cost']=0;
            foreach ($photo as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$photo[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $photo[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $photo[$key]['unit'],
                );
                $arr_photo[]=$item;
                $arr_photo_total['total_price'] += $photo[$key]['actual_price']*$photo[$key]['unit'];
                $arr_photo_total['total_cost'] +=$photo[$key]['actual_unit_cost']*$photo[$key]['unit'];
            }           
            $arr_photo_total['gross_profit']=$arr_photo_total['total_price']-$arr_photo_total['total_cost'];
            if($arr_photo_total['total_price'] != 0){
                $arr_photo_total['gross_profit_rate']=$arr_photo_total['gross_profit']/$arr_photo_total['total_price'];    
            }else if($arr_photo_total['total_cost'] != 0){
                $arr_photo_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_photo_total);*/

        /*********************************************************************************************************************/
        /*取化妆数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_makeup = array();
        $makeup = array();
        $arr_makeup_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 6),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $makeup[] = $item;
            };
            /*print_r($makeup);*/
        }
        if (!empty($makeup)) {
            $arr_makeup_total['total_price']=0;
            $arr_makeup_total['total_cost']=0;
            foreach ($makeup as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$makeup[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $makeup[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $makeup[$key]['unit'],
                );
                $arr_makeup[]=$item;
                $arr_makeup_total['total_price'] += $makeup[$key]['actual_price']*$makeup[$key]['unit'];
                $arr_makeup_total['total_cost'] +=$makeup[$key]['actual_unit_cost']*$makeup[$key]['unit'];
            }           
            $arr_makeup_total['gross_profit']=$arr_makeup_total['total_price']-$arr_makeup_total['total_cost'];
            if($arr_makeup_total['total_price'] != 0){
                $arr_makeup_total['gross_profit_rate']=$arr_makeup_total['gross_profit']/$arr_makeup_total['total_price'];    
            }else if($arr_makeup_total['total_cost'] != 0){
                $arr_makeup_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取其他人员数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_other = array();
        $other = array();
        $arr_other_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 7),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $other[] = $item;
            };
            /*print_r($other);*/
        }
        if (!empty($other)) {
            $arr_other_total['total_price']=0;
            $arr_other_total['total_cost']=0;
            foreach ($other as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$other[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $other[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $other[$key]['unit'],
                );
                $arr_other[]=$item;
                $arr_other_total['total_price'] += $other[$key]['actual_price']*$other[$key]['unit'];
                $arr_other_total['total_cost'] +=$other[$key]['actual_unit_cost']*$other[$key]['unit'];
            }           
            $arr_other_total['gross_profit']=$arr_other_total['total_price']-$arr_other_total['total_cost'];
            if($arr_other_total['total_price'] != 0){
                $arr_other_total['gross_profit_rate']=$arr_other_total['gross_profit']/$arr_other_total['total_price'];    
            }else if($arr_other_total['total_cost'] != 0){
                $arr_other_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/



        /*********************************************************************************************************************/
        /*计算人员部分总价*/
        /*********************************************************************************************************************/
        $arr_service_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        if(!empty($arr_host_total)){
            $arr_service_total['total_price'] += $arr_host_total['total_price'];
            $arr_service_total['total_cost'] += $arr_host_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_host_total['gross_profit'];
        }

        if(!empty($arr_camera_total)){
            $arr_service_total['total_price'] += $arr_camera_total['total_price'];
            $arr_service_total['total_cost'] += $arr_camera_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_camera_total['gross_profit'];
        }

        if(!empty($arr_photo_total)){
            $arr_service_total['total_price'] += $arr_photo_total['total_price'];
            $arr_service_total['total_cost'] += $arr_photo_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_photo_total['gross_profit'];
        }

        if(!empty($arr_makeup_total)){
            $arr_service_total['total_price'] += $arr_makeup_total['total_price'];
            $arr_service_total['total_cost'] += $arr_makeup_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_makeup_total['gross_profit'];
        }

        if(!empty($arr_other_total)){
            $arr_service_total['total_price'] += $arr_other_total['total_price'];
            $arr_service_total['total_cost'] += $arr_other_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_other_total['gross_profit'];
        }



        if($arr_service_total['total_price'] != 0){
            $arr_service_total['gross_profit_rate'] = $arr_service_total['gross_profit']/$arr_service_total['total_price'];
        }




        /*********************************************************************************************************************/
        /*取场地布置数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_decoration = array();
        $decoration = array();
        $arr_decoration_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 20),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $decoration[] = $item;
            };
            /*print_r($decoration);*/
        }
        if (!empty($decoration)) {
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
            foreach ($decoration as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$decoration[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);
                $ref_pic_url = "";
                $t = explode(".", $supplier_product2['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $ref_pic_url = "http://file.cike360.com".$t[0]."_sm.".$t[1];    
                };
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $decoration[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $decoration[$key]['unit'],
                    'ref_pic_url' => $ref_pic_url,
                );
                $arr_decoration[]=$item;
                $arr_decoration_total['total_price'] += $decoration[$key]['actual_price']*$decoration[$key]['unit'];
                $arr_decoration_total['total_cost'] +=$decoration[$key]['actual_unit_cost']*$decoration[$key]['unit'];
            }           
            $arr_decoration_total['gross_profit']=$arr_decoration_total['total_price']-$arr_decoration_total['total_cost'];
            if($arr_decoration_total['total_price'] != 0){
                $arr_decoration_total['gross_profit_rate']=$arr_decoration_total['gross_profit']/$arr_decoration_total['total_price'];    
            }else if($arr_decoration_total['total_cost'] != 0){
                $arr_decoration_total['gross_profit_rate'] = 0;
            }  
        }else{
            $arr_decoration_total['gross_profit']=0;
            $arr_decoration_total['gross_profit_rate']=0;
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }
        /*print_r($arr_decoration_total['total_cost']);die;*/

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取平面设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_graphic = array();
        $graphic = array();
        $arr_graphic_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 10),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $graphic[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($graphic)) {
            $arr_graphic_total['total_price']=0;
            $arr_graphic_total['total_cost']=0;
            foreach ($graphic as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$graphic[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $graphic[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $graphic[$key]['unit'],
                );
                $arr_graphic[]=$item;
                $arr_graphic_total['total_price'] += $graphic[$key]['actual_price']*$graphic[$key]['unit'];
                $arr_graphic_total['total_cost'] +=$graphic[$key]['actual_unit_cost']*$graphic[$key]['unit'];
            }           
            $arr_graphic_total['gross_profit']=$arr_graphic_total['total_price']-$arr_graphic_total['total_cost'];
            if($arr_graphic_total['total_price'] != 0){
                $arr_graphic_total['gross_profit_rate']=$arr_graphic_total['gross_profit']/$arr_graphic_total['total_price'];    
            }else {
                $arr_graphic_total['gross_profit_rate']=0;
            }
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视频设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_film = array();
        $film = array();
        $arr_film_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 11),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $film[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($film)) {
            $arr_film_total['total_price']=0;
            $arr_film_total['total_cost']=0;
            foreach ($film as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$film[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $film[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $film[$key]['unit'],
                );
                $arr_film[]=$item;
                $arr_film_total['total_price'] += $film[$key]['actual_price']*$film[$key]['unit'];
                $arr_film_total['total_cost'] +=$film[$key]['actual_unit_cost']*$film[$key]['unit'];
            }           
            $arr_film_total['gross_profit']=$arr_film_total['total_price']-$arr_film_total['total_cost'];
            if($arr_film_total['total_price'] != 0){
                $arr_film_total['gross_profit_rate']=$arr_film_total['gross_profit']/$arr_film_total['total_price'];    
            }else {
                $arr_film_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视策划师产品数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_designer = array();
        $designer = array();
        $arr_designer_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 17),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);die;*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $designer[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($designer)) {
            $arr_designer_total['total_price']=0;
            $arr_designer_total['total_cost']=0;
            foreach ($designer as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$designer[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $designer[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $designer[$key]['unit'],
                );
                $arr_designer[]=$item;
                $arr_designer_total['total_price'] += $designer[$key]['actual_price']*$designer[$key]['unit'];
                $arr_designer_total['total_cost'] +=$designer[$key]['actual_unit_cost']*$designer[$key]['unit'];
            }           
            $arr_designer_total['gross_profit']=$arr_designer_total['total_price']-$arr_designer_total['total_cost'];
            if($arr_designer_total['total_price'] != 0){
                $arr_designer_total['gross_profit_rate']=$arr_designer_total['gross_profit']/$arr_designer_total['total_price'];    
            }else {
                $arr_designer_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($designer);die;*/
        

        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_order_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        

        /*print_r($order_discount);die;*/

        if(!empty($arr_wed_feast)){
            $arr_order_total['total_price'] += $arr_wed_feast['total_price'] * $order_discount['feast_discount'] * 0.1;
            $arr_order_total['total_cost'] += $arr_wed_feast['total_cost'];
        }

        if(!empty($arr_video)){
            if($this->judge_discount(9,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_video_total['total_price'];
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_video_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }
            
        }

        if(!empty($arr_light)){
            if($this->judge_discount(8,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_light_total['total_price'];
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_light_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }
        }

        if(!empty($arr_service_total)){
            if($this->judge_discount(3,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_service_total['total_price'];
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_service_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }
        }

        if(!empty($arr_decoration_total)){
            if($this->judge_discount(20,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'];
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }
        }
        if(!empty($arr_graphic_total)){
            if($this->judge_discount(10,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'];
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }
        }
        if(!empty($arr_film_total)){
            if($this->judge_discount(11,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_film_total['total_price'];
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_film_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }
        }
        if(!empty($arr_designer_total)){
            if($this->judge_discount(17,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_designer_total['total_price'];
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_designer_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }
        }

        if($order_discount['cut_price'] != 0){
            $arr_order_total['total_price'] -= $order_discount['cut_price'];
        }

        /*print_r($arr_order_total['total_price']);die;*/
        $arr_order_total['gross_profit'] = $arr_order_total['total_price'] - $arr_order_total['total_cost'];

        if($arr_order_total['total_price'] != 0){
            $arr_order_total['gross_profit_rate']=$arr_order_total['gross_profit']/$arr_order_total['total_price'];    
        }else {
            $arr_order_total['gross_profit_rate']=0;
        }



        /*========================================================================================================
        ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊界面渲染＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
        ========================================================================================================*/




$html = '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
</head>
<body>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>

<table class="tftable" border="1">
<tr><th colspan="3">基本信息</th></tr>
<tr><td width="10%">订单编号</td><td colspan="2" width="90%">'.$order_data["id"].'</td></tr>
<tr><td width="10%">新郎信息</td><td width="50%">'.$order_data["groom_name"].'</td><td width="40%">'.$order_data["groom_phone"].'</td></tr>
<tr><td width="10%">新娘信息</td><td width="50%">'.$order_data["bride_name"].'</td><td width="40%">'.$order_data["bride_phone"].'</td></tr>
<tr><td width="10%">策划师</td><td colspan="2" width="90%">'.$order_data["designer_name"].'</td></tr>
<tr><td width="10%">婚宴折扣</td><td colspan="2" width="90%">'.$order_data["feast_discount"].'</td></tr>
<tr><td width="10%">婚礼折扣</td><td colspan="2" width="90%">'.$order_data["other_discount"].'</td></tr>
<tr><td width="10%">抹零</td><td colspan="2" width="90%">'.$order_data["cut_price"].'</td></tr>
<tr><td width="10%">订单总价</td><td colspan="2" width="90%">'.$arr_order_total['total_price'].'</td></tr>
</table>

';

/*<!-- 婚宴 -->*/
if (!empty($arr_wed_feast)) {

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<tr><td width="10%" rowspan = "5">婚宴</td><td width="4%">1</td><td width="12%">'.$arr_wed_feast['name'].'</td><td width="20%"></td><td width="4%">'.$arr_wed_feast['table_num'].'</td><td width="9%">'.$arr_wed_feast['unit'].'</td><td width="18%">'.$arr_wed_feast['unit_price'].'</td><td width="23%"> </td></tr>
</table>';


};


/*<!-- 灯光 -->*/

if (!empty($arr_light)) {
$i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

    foreach ($arr_light as $key => $value) {
        foreach ($value as $key1 => $value1) {
            $light[$key1] = $value1;
        }   


        if($i==1){
$html .= '<tr><td width="10%" rowspan = "'.count($arr_light).'">灯光</td><td width="4%">'.$i.'</td><td width="12%">'.$light['name'].'</td><td width="20%"></td><td width="4%">'.$light['amount'].'</td><td width="4%">'.$light['unit'].'</td><td width="23%">'.$light['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$light['name'].'</td><td width="20%"></td><td width="4%">'.$light['amount'].'</td><td width="4%">'.$light['unit'].'</td><td width="23%">'.$light['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .= '</table>';
};


/*<!-- 视频 -->*/

if (!empty($arr_video)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_video as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $video[$key1] = $value1;
            }
            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_video).'">视频</td><td width="4%">'.$i.'</td><td width="12%">'.$video['name'].'</td><td width="20%"></td><td width="4%">'.$video['amount'].'</td><td width="4%">'.$video['unit'].'</td><td width="23%">'.$video['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$video['name'].'</td><td width="20%"></td><td width="4%">'.$video['amount'].'</td><td width="4%">'.$video['unit'].'</td><td width="23%">'.$video['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';

  
    };

/*<!-- 主持人 -->*/

if (!empty($arr_host)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_host as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $host[$key1] = $value1;
            }
            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_host).'">主持人</td><td width="4%">'.$i.'</td><td width="12%">'.$host['name'].'</td><td width="20%"></td><td width="4%">'.$host['amount'].'</td><td width="4%">'.$host['unit'].'</td><td width="23%">'.$host['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$host['name'].'</td><td width="20%"></td><td width="4%">'.$host['amount'].'</td><td width="4%">'.$host['unit'].'</td><td width="23%">'.$host['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';
 
    };


/*<!-- 摄像 -->*/

if (!empty($arr_camera)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';
        foreach ($arr_camera as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $camera[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_camera).'">摄像</td><td width="4%">'.$i.'</td><td width="12%">'.$camera['name'].'</td><td width="20%"></td><td width="4%">'.$camera['amount'].'</td><td width="4%">'.$camera['unit'].'</td><td width="23%">'.$camera['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">.'.$camera['name'].'</td><td width="20%"></td><td width="4%">'.$camera['amount'].'</td><td width="4%">'.$camera['unit'].'</td><td width="23%">'.$camera['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 摄影 -->*/

    if (!empty($arr_photo)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_photo as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $photo[$key1] = $value1;
        }
        if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_photo).'">摄影</td><td width="4%">'.$i.'</td><td width="12%">'.$photo['name'].'</td><td width="20%"></td><td width="4%">'.$photo['amount'].'</td><td width="4%">'.$photo['unit'].'</td><td width="23%">'.$photo['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$photo['name'].'</td><td width="20%"></td><td width="4%">'.$photo['amount'].'</td><td width="4%">'.$photo['unit'].'</td><td width="23%">'.$photo['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 化妆 -->*/

    if (!empty($arr_makeup)) {
    $i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_makeup as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $makeup[$key1] = $value1;
            }

            if($i==1){

$html .= '<tr><td width="10%" rowspan = "'.count($arr_makeup).'">化妆</td><td width="4%">'.$i.'</td><td width="12%">'.$makeup['name'].'</td><td width="20%"></td><td width="4%">'.$makeup['amount'].'</td><td width="4%">'.$makeup['unit'].'</td><td width="23%">'.$makeup['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$makeup['name'].'</td><td width="20%"></td><td width="4%">'.$makeup['amount'].'</td><td width="4%">'.$makeup['unit'].'</td><td width="23%">'.$makeup['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .= '</table>';

 
    };


/*<!-- 其他 -->*/

    if (!empty($arr_other)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_other as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $other[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_other).'">其他</td><td width="4%">'.$i.'</td><td width="12%">'.$other['name'].'</td><td width="20%"></td><td width="4%">'.$other['amount'].'</td><td width="4%">'.$other['unit'].'</td><td width="23%">'.$other['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$other['name'].'</td><td width="20%"></td><td width="4%">'.$other['amount'].'</td><td width="4%">'.$other['unit'].'</td><td width="23%">'.$other['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';

    };


/*<!-- 场地布置 -->*/

    if (!empty($arr_decoration)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_decoration as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $decoration[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_decoration).'">场地布置</td><td width="4%">'.$i.'</td><td width="12%">'.$decoration['name'].'</td><td width="20%"></td><td width="4%">'.$decoration['amount'].'</td><td width="4%">'.$decoration['unit'].'</td><td width="23%">'.$decoration['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$decoration['name'].'</td><td width="20%"></td><td width="4%">'.$decoration['amount'].'</td><td width="4%">'.$decoration['unit'].'</td><td width="23%">'.$decoration['unit_price'].'</td><td width="23%"><img style="height:150px" src="'.$decoration['ref_pic_url'].'"></img></td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 平面设计 -->*/

    if (!empty($arr_graphic)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_graphic as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $graphic[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_graphic).'">平面设计</td><td width="4%">'.$i.'</td><td width="12%">'.$graphic['name'].'</td><td width="20%"></td><td width="4%">'.$graphic['amount'].'</td><td width="4%">'.$graphic['unit'].'</td><td width="23%">'.$graphic['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$graphic['name'].'</td><td width="20%"></td><td width="4%">'.$graphic['amount'].'</td><td width="4%">'.$graphic['unit'].'</td><td width="23%">'.$graphic['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 视频设计 -->*/

    if (!empty($arr_film)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_film as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $film[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_film).'">视频设计</td><td width="4%">'.$i.'</td><td width="12%">'.$film['name'].'</td><td width="20%"></td><td width="4%">'.$film['amount'].'</td><td width="4%">'.$film['unit'].'</td><td width="23%">'.$film['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$film['name'].'</td><td width="20%"></td><td width="4%">'.$film['amount'].'</td><td width="4%">'.$film['unit'].'</td><td width="23%">'.$film['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 策划费&杂费 -->*/
    if (!empty($arr_designer)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_designer as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $designer[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_designer).'">策划费&杂费</td><td width="4%">'.$i.'</td><td width="12%">'.$designer['name'].'</td><td width="20%"></td><td width="4%">'.$designer['amount'].'</td><td width="4%">'.$designer['unit'].'</td><td width="23%">'.$designer['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">.'.$i.'</td><td width="12%">'.$designer['name'].'</td><td width="20%"></td><td width="4%">'.$designer['amount'].'</td><td width="4%">'.$designer['unit'].'</td><td width="23%">'.$designer['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';

  
    };





$html .='</body>
</html>';

        //$fp = fopen("billtable".$_SESSION['userid'].".html","w");
        $fp = fopen("billtable.html","w");
        if(!$fp)
        {
        echo "System Error";
        exit();
        }
        else {
        fwrite($fp,$html);
        fclose($fp);
        echo "Success";
        }



        /*require_once "../library/email.class.php";
        //******************** 配置信息 ********************************
        $smtpserver = "smtp.qq.com";//SMTP服务器
        $smtpserverport =25;//SMTP服务器端口
        $smtpusermail = "2837745713@qq.com";//SMTP服务器的用户邮箱
        $smtpemailto = "zhangsiheng0820@126.com";//发送给谁
        $smtpuser = "2837745713";//SMTP服务器的用户帐号
        $smtppass = "xsxn1183";//SMTP服务器的用户密码
        $mailtitle = "报价单";//邮件主题
        $mailcontent = $html;//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = true;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

        echo   '<head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>报价单</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black">
                <meta name="format-detection" content="telephone=no">
                <link href="css/base.css" rel="stylesheet" type="text/css"/>
                <link href="css/style.css" rel="stylesheet" type="text/css"/>
                </head>
                <body>';

        echo "<div style='width:300px; margin:36px auto;'>";
        if($state==""){
            echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
            echo "<a href='index.html'>点此返回</a>";
            exit();
        }
        echo "恭喜！邮件发送成功！！";
        echo "<a href='index.html'>点此返回</a>";
        echo "</div></body>";*/

        
        

        //发送邮件 

        //主題 
        $subject = "test send email"; 

        //收件人 
        //$sendto = 'trhyyy@hpeprint.com'; 
        $sendto = $_POST['email']; 
        echo $_POST['email'];

        //發件人 
        //$replyto = '2837745713@qq.com'; 
        //$replyto = 'hunlicehuashi2016@126.com'; 
        $replyto = 'zhangsiheng0820@126.com'; 

        //內容 
        $message = ""; 

        //附件 
        //$filename = "billtable".$_SESSION['userid'].".html"; 
        $filename = "billtable.html"; 
        //附件類別 
        //$mimetype = "billtable".$_SESSION['userid'].".html";  
        $mimetype = "billtable.html";  
        echo "1";

        $mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
        echo "2";
        $mailfile->sendfile(); 
        echo "3";
    }

    public function judge_discount($type_id,$order_id){
        $order = Order::model()->findByPk($order_id); 
        $discount_range = explode(",",$order['discount_range']);
        $t=0;
        foreach ($discount_range as $key => $value) {
            if($value == $type_id){
                $t=1;
            }
        }
        return $t;
    }

    public function actionMeetingbill()
    {
        $_POST['order_id'] = 11;
        $_POST['email'] = 'zhangsiheng0820@126.com';
        $orderId = $_POST['order_id'];
        $supplier_product_id = array();
        $wed_feast = array();
        $arr_wed_feast = array();

        $order_discount = Order::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $orderId),
        ));

        /*********************************************************************************************************************/
        /*取餐饮数据*/
        /*********************************************************************************************************************/
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 2),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/
        if(!empty($supplier_id)){
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $criteria1->addCondition("category=:category");
            $criteria1->params[':category']=1; 
            $supplier_product = SupplierProduct::model()->findAll($criteria1);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = $value->id;
                $supplier_product_id[] = $item;
            };
            /*print_r($supplier_product_id);*/
        }
        
        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $wed_feast[] = $item;
            };
            /*print_r($wed_feast);*/
        }
        /*print_r($wed_feast);*/
        
        if(!empty($wed_feast)){
            $criteria3 = new CDbCriteria; 
            $criteria3->addCondition("id=:id");
            $criteria3->params[':id']=$wed_feast[0]['product_id']; 
            $supplier_product2 = SupplierProduct::model()->find($criteria3);
            /*print_r($supplier_product2);*/
            $arr_wed_feast = array(
                'name' => $supplier_product2['name'],
                'unit_price' => $wed_feast[0]['actual_price'],
                'unit' => $supplier_product2['unit'],
                'table_num' => $wed_feast[0]['unit'],
                'service_charge_ratio' => $wed_feast[0]['actual_service_ratio'],
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']/100),
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']/100,
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']/100)/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']/100)),
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_wed_feast);*/


        /*********************************************************************************************************************/
        /*取场地费数据*/
        /*********************************************************************************************************************/
        $changdi_fee = array();
        $arr_changdi_fee = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 19),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/
        if(!empty($supplier_id)){
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $criteria1->addCondition("category=:category");
            $criteria1->params[':category']=1; 
            $supplier_product = SupplierProduct::model()->findAll($criteria1);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = $value->id;
                $supplier_product_id[] = $item;
            };
            /*print_r($supplier_product_id);*/
        }
        
        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $changdi_fee[] = $item;
            };
            /*print_r($changdi_fee);*/
        }
        
        if(!empty($changdi_fee)){
            $criteria3 = new CDbCriteria; 
            $criteria3->addCondition("id=:id");
            $criteria3->params[':id']=$changdi_fee[0]['product_id']; 
            $supplier_product2 = SupplierProduct::model()->find($criteria3);
            /*print_r($supplier_product2);*/
            $arr_changdi_fee = array(
                'name' => $supplier_product2['name'],
                'unit_price' => $changdi_fee[0]['actual_price'],
                'unit' => $supplier_product2['unit'],
                'amount' => $changdi_fee[0]['unit'],
                'total_price' => $changdi_fee[0]['actual_price']*$changdi_fee[0]['unit'],
                'gross_profit' => ($changdi_fee[0]['actual_price']-$changdi_fee[0]['actual_unit_cost'])*$changdi_fee[0]['unit'],
                'gross_profit_rate' => (($changdi_fee[0]['actual_price']-$changdi_fee[0]['actual_unit_cost'])*$changdi_fee[0]['unit'])/($changdi_fee[0]['actual_price']*$changdi_fee[0]['unit']),
                /*'table_num' => $wed_feast[0]['unit'],
                'service_charge_ratio' => $wed_feast[0]['actual_service_ratio'],*/
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_changdi_fee);die;*/



        /*********************************************************************************************************************/
        /*取灯光数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_light = array();
        $light = array();
        $arr_light_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 8),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=1; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;

                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $light[] = $item;
            };
        }
        $arr_light_total['total_price']=0;
        $arr_light_total['total_cost']=0;
        if (!empty($light)) {
            foreach ($light as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$value['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $value['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $value['unit'],
                );
                $arr_light[]=$item;
                $arr_light_total['total_price'] += $value['actual_price']*$value['unit'];;
                $arr_light_total['total_cost'] +=$value['actual_unit_cost']*$value['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            if($arr_light_total['total_price'] != 0){
                $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];
            }else{
                $arr_light_total['gross_profit_rate']=0;
            };
            
        }

        /*print_r($arr_light);die;*/

        

        /*********************************************************************************************************************/
        /*取视频数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_video = array();
        $video = array();
        $arr_video_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 9),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=1; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*$video = array();*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $video[] = $item;
            };
            /*print_r($video);*/
        }
        $arr_video_total['total_price']=0;
        $arr_video_total['total_cost']=0;
        if (!empty($video)) {
            foreach ($video as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$value['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $value['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $value['unit'],
                );
                $arr_video[]=$item;
                $arr_video_total['total_price'] += $value['actual_price']*$value['unit'];;
                $arr_video_total['total_cost'] +=$value['actual_unit_cost']*$value['unit'];
            }           
            $arr_video_total['gross_profit']=$arr_video_total['total_price'];-$arr_video_total['total_cost'];
            $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];
        }

        /*print_r($arr_video_total);*/

        

        

        /*********************************************************************************************************************/
        /*取订单日期、统筹师数据*/
        /*********************************************************************************************************************/

        $criteria3 = new CDbCriteria; 
        $criteria3->addCondition("order_id=:order_id");
        $criteria3->params[':order_id']=$orderId; 
        $order_meeting = OrderMeeting::model()->find($criteria3);
        /*print_r($order_data);die;*/

        $company_linkman = OrderMeetingCompanyLinkman::model()->findByPk($order_meeting['company_linkman_id']);





        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_total = array(
            'total_price' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );
        if(!empty($arr_wed_feast)){
            $arr_total['total_price'] += $arr_wed_feast['total_price'] * $order_discount['feast_discount'] * 0.1;
            $arr_total['gross_profit'] += $arr_wed_feast['gross_profit'];
        }

        if(!empty($arr_changdi_fee)){
            if($this->judge_discount(19,$orderId) == 0){
                $arr_total['total_price'] += $arr_changdi_fee['total_price'];
                $arr_total['gross_profit'] += $arr_changdi_fee['gross_profit'];
            }else{
                $arr_total['total_price'] += $arr_changdi_fee['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_changdi_fee['gross_profit'];
            }
        }

        if(!empty($arr_video)){
            if($this->judge_discount(9,$orderId) == 0){
                $arr_total['total_price'] += $arr_video_total['total_price'];
                $arr_total['gross_profit'] += $arr_video_total['gross_profit'];
            }else{
                $arr_total['total_price'] += $arr_video_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_video_total['gross_profit'];
            }
        }

        if(!empty($arr_light)){
            if($this->judge_discount(8,$orderId) == 0){
                $arr_total['total_price'] += $arr_light_total['total_price'];
                $arr_total['gross_profit'] += $arr_light_total['gross_profit'];
            }else{
                $arr_total['total_price'] += $arr_light_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_light_total['gross_profit'];
            }
        }

        if($order_discount['cut_price'] != 0){
            $arr_total['total_price'] -= $order_discount['cut_price'];
        }

        if($arr_total['total_price'] != 0){
            $arr_total['gross_profit_rate'] = $arr_total['gross_profit']/$arr_total['total_price'];    
        }

        /*********************************************************************************************************************/
        /*查询订单信息*/
        /*********************************************************************************************************************/
        $order_data = Order::model()->findByPk($orderId);
        $planner = Staff::model()->findByPk($order_data['planner_id']);
        /*print_r($order_data);die;*/

        /*========================================================================================================
        ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊界面渲染＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
        ========================================================================================================*/




$html = '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
</head>
<body>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>

<table class="tftable" border="1">
<tr><th colspan="3">基本信息</th></tr>
<tr><td width="10%">订单编号</td><td colspan="2" width="90%">'.$order_data["id"].'</td></tr>
<tr><td width="10%">客户名称</td><td colspan="2" width="50%">'.$order_data['order_name'].'</td></tr>
<tr><td width="10%">联系人</td><td width="50%">'.$company_linkman['name'].'</td><td width="40%">'.$company_linkman['telephone'].'</td></tr>
<tr><td width="10%">统筹师</td><td colspan="2" width="90%">'.$planner['name'].'</td></tr>
<tr><td width="10%">餐饮折扣</td><td colspan="2" width="90%">'.$order_data["feast_discount"].'</td></tr>
<tr><td width="10%">其他折扣</td><td colspan="2" width="90%">'.$order_data["other_discount"].'</td></tr>
<tr><td width="10%">抹零</td><td colspan="2" width="90%">'.$order_data["cut_price"].'</td></tr>
<tr><td width="10%">订单总价</td><td colspan="2" width="90%">'.$arr_total['total_price'].'</td></tr>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';

/*<!-- 会议餐 -->*/
if (!empty($arr_wed_feast)) {

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<tr><td width="10%" rowspan = "5">婚宴</td><td width="4%">1</td><td width="12%">'.$arr_wed_feast['name'].'</td><td width="20%"></td><td width="4%">'.$arr_wed_feast['table_num'].'</td><td width="9%">'.$arr_wed_feast['unit'].'</td><td width="18%">'.$arr_wed_feast['unit_price'].'</td><td width="23%"> </td></tr>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';


};

/*<!-- 场地费 -->*/

if (!empty($arr_changdi_fee)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';
            

$html .='<tr><td width="10%" rowspan = "1">场地费</td><td width="4%">'.$i.'</td><td width="12%">'.$arr_changdi_fee['name'].'</td><td width="20%"></td><td width="4%">'.$arr_changdi_fee['amount'].'</td><td width="4%">'.$arr_changdi_fee['unit'].'</td><td width="23%">'.$arr_changdi_fee['unit_price'].'</td><td width="23%"> </td></tr>';


$html .='</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';
 
    };

/*<!-- 灯光 -->*/
/*print_r($arr_light);die;*/
if (!empty($arr_light)) {
$i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

    foreach ($arr_light as $key => $value) {

        if($i==1){
$html .= '<tr><td width="10%" rowspan = "'.count($arr_light).'">灯光</td><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .= '</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';
};


/*<!-- 视频 -->*/
/*print_r($arr_video);die;*/
if (!empty($arr_video)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_video as $key => $value) {

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_video).'">视频</td><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';

  
    };


$html .='</body>
</html>';

/*echo $html;die;*/

        //$fp = fopen("billtable".$_SESSION['userid'].".html","w");
        $fp = fopen("billtable.html","w");
        if(!$fp)
        {
        echo "System Error";
        exit();
        }
        else {
        fwrite($fp,$html);
        fclose($fp);
        echo "Success";
        }



        /*require_once "../library/email.class.php";
        //******************** 配置信息 ********************************
        $smtpserver = "smtp.qq.com";//SMTP服务器
        $smtpserverport =25;//SMTP服务器端口
        $smtpusermail = "2837745713@qq.com";//SMTP服务器的用户邮箱
        $smtpemailto = "zhangsiheng0820@126.com";//发送给谁
        $smtpuser = "2837745713";//SMTP服务器的用户帐号
        $smtppass = "xsxn1183";//SMTP服务器的用户密码
        $mailtitle = "报价单";//邮件主题
        $mailcontent = $html;//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = true;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

        echo   '<head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>报价单</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black">
                <meta name="format-detection" content="telephone=no">
                <link href="css/base.css" rel="stylesheet" type="text/css"/>
                <link href="css/style.css" rel="stylesheet" type="text/css"/>
                </head>
                <body>';

        echo "<div style='width:300px; margin:36px auto;'>";
        if($state==""){
            echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
            echo "<a href='index.html'>点此返回</a>";
            exit();
        }
        echo "恭喜！邮件发送成功！！";
        echo "<a href='index.html'>点此返回</a>";
        echo "</div></body>";*/

        
        

        //发送邮件 

        //主題 
        $subject = "test send email"; 

        //收件人 
        //$sendto = 'trhyyy@hpeprint.com'; 
        $sendto = $_POST['email']; 
        echo $_POST['email'];

        //發件人 
        //$replyto = '2837745713@qq.com'; 
        $replyto = 'zhangsiheng0820@126.com'; 

        //內容 
        $message = ""; 

        //附件 
        //$filename = "billtable".$_SESSION['userid'].".html"; 
        $filename = "billtable.html"; 
        //附件類別 
        //$mimetype = "billtable".$_SESSION['userid'].".html";  
        $mimetype = "billtable.html";  
        echo "1";

        $mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
        echo "2";
        $mailfile->sendfile(); 
        echo "3";   
    }    

}

