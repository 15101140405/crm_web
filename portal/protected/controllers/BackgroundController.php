<?php

include_once('../library/WPRequest.php');

class BackgroundController extends InitController
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

    public function actionLogin()
    {
        $this->render("login");
    }

    public function actionLogin_pro()
    {

        $staff = Staff::model()->find(array(

            "condition" => "telephone = :telephone",
            "params"    => array(
                ":telephone" => $_POST['telephone']
                )
            ));
        if (empty($staff)) {

            echo "用户不存在";
        }
        if ($staff['password'] == $_POST['password']) {
            echo "登录成功";
            Yii::app()->request->cookies['id'] = $staff['id'];
            Yii::app()->request->cookies['account_id'] = $_POST['account_id'];


        } else {
            echo "密码错误";
        }
        
    }

    public function actionCase_list()
    {

        $staff_id = $_COOKIE['id'];
        $result = yii::app()->db->createCommand("select * from case_info where ".

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".
            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".
            " and CI_Show=1 order by CI_Sort Desc");
        $list = $result->queryAll();
        echo json_encode($list);


    }


    public function actionIndex()
    {
        $this->render("index");
    }

}
