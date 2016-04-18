<?php

include_once('../library/WPRequest.php');

class TrialController extends InitController
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

    public function actionApply()
    {
        $this->render("apply");        
    }

    public function actionApplied()
    {
        $this->render("applied");        
    }

    public function actionSendmessage()
    {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $t = Staff::model()->findByPk($_POST['recomend']);
        $recomend = $t['name'];
        $touser="@all";//你要发的人
        $toparty="";
        $content = "新的试用申请：姓名｛".$name."}  电话｛".$phone."}   推荐人｛".$recomend."｝";
        $corpid="wx188103b36e2e2878";
        $corpsecret="wUVQBE10jcR-dU-_r0bJ9Vz446mq5cRdMmFIS0avKWrd1mw7UJHqqdZrTfjK8ZgC";

        //$result=WPRequest::sendMessage_Mpnews($touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);
        $result=WPRequest::sendMessage_Text($touser, $toparty, $content,$corpid,$corpsecret);
        print_r($result);

        $admin=new Trial;
        $admin->recomend_staff_id=$_POST['recomend'];
        $admin->customer_name=$name;
        $admin->customer_phone=$phone;
        $admin->update_time= date('y-m-d h:i:s',time());
        $admin->save();
    }

}
