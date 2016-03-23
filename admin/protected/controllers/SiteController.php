<?php

class SiteController extends InitController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/classic/main';

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
                //'actions' => array('index', 'login', 'logout', 'error', 'message'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                //'actions'=>array('admin','delete'),
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
     */
    public function actionIndex()
    {
        $this->redirect($this->createUrl("log/admin"));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $this->layout = '//layouts/classic/main_no_header';

        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
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

    public function actionLogin()
    {
        $this->layout = '//layouts/classic/main-not-exited';
        $model = new LoginForm;

        // collect user input data
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if (!isset($_POST['rememberMe'])) {
                $_POST['rememberMe'] = true;
            }

            $model->setAttributes(array(
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'rememberMe' => $_POST['rememberMe'],
            ));

            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $ext = array(
                    'url' => $this->createUrl('site/index')
                );
                return $this->sucResponse("登陆成功~", null, $ext);

            } else {
                return $this->errResponse("用户名或密码错误");
            }
        }

        $this->render('login');
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
