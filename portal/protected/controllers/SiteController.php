<?php

class SiteController extends InitController
{
    public $layout = '//layouts/main';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),

            'upload' => array(
                'class' => 'xupload.actions.XUploadAction',
                'path' => Yii::app()->getBasePath() . "/../../uploads",
                'publicPath' => Yii::app()->getBaseUrl() . "/../uploads",
            ),
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
                //'actions'=>array('login', 'logout', 'error', 'message'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                //'actions'=>array(''),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions' => array(''),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     *
     */
    public function actionIndex()
    {
        if (isset($_GET["accountId"]) && $_GET["accountId"]) {
            $accountId = $_GET["accountId"];

        } else {
            $accountId = 1;
        }

        $redirectUrl = $this->createUrl("order/index", array(
            "accountId" => $accountId
        ));

        $this->redirect($redirectUrl);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $message = Yii::app()->errorHandler->error;
        $this->errResponse($message);
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     *
     * param  无
     * return 返回登录结果json
     */
    public function actionLogin()
    {
        Yii::app()->user->name = "user";
        Yii::app()->user->id = 1;
        $this->redirect(Yii::app()->user->returnUrl);
//        $this->layout = '//layouts/classic/main-not-exited';
//
//        if (empty($_SESSION['user'])) {
//            $appId = "wxf29be229f32800a9";
//            $redirectUri = Yii::app()->request->hostInfo . $this->createUrl("site/callback");
//            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$redirectUri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
//
//            $this->redirect($url);
//
//        } else {
//            var_dump($_SESSION['user']);
//        }

    }

    public function actionCallback()
    {
        $corpId = "wxf29be229f32800a9";
        $secret = "RN76xV99TOvqwIH4v8MfZf6k9w6_ZofopXjjuAJuiU9hVwBWoKIKYsw82jUGqTWG";
        $code = $_GET["code"];

        $getTokenUrl = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corpId}&corpsecret={$secret}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $getTokenUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        $jsonObj = json_decode($res, true);

        $accessToken = $jsonObj['access_token'];
        $agentId = 1;
        $getUserInfoUrl = "https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token={$accessToken}&code={$code}&agentid={$agentId}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $getUserInfoUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);

        $userObj = json_decode($res, true);
        $_SESSION['user'] = $userObj;
        print("\r\n" . "Welcome~" . "\r\n\r\n");
        print_r($userObj);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * 通用上传表单渲染action
     * param
     * return
     * update
     */
    public function actionUploadForm()
    {
        $this->layout = "//layouts/upload/uploadForm";

        Yii::import("xupload.models.XUploadForm");
        $uploadForm = new XUploadForm;

        $this->render('uploadForm', array(
            'uploadForm' => $uploadForm,
        ));
    }

    public function actionMultiUploadForm()
    {
        $this->layout = "//layouts/upload/uploadForm";

        Yii::import("xupload.models.XUploadForm");
        $uploadForm = new XUploadForm;

        $this->render('multiUploadForm', array(
            'uploadForm' => $uploadForm,
        ));
    }


}