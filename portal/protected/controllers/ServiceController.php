<?php

include_once('../library/WPRequest.php');

class ServiceController extends InitController
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

    public function actionMy()
    {
        $service_team = array();
        if(isset($_GET['service_team_id'])){
            Yii::app()->session['service_team_id']=$_GET['service_team_id'];  
            $service_team = ServiceTeam::model()->findByPk($_GET['service_team_id']);  
        };

        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['service_team_id']) && isset($_SESSION['service_person_id'])){//已登陆
            //echo '已登陆';
            /*echo $_SESSION['userid'];die;*/
            $service_person = ServicePerson::model()->find(array(
                    'condition' => 'staff_id=:staff_id',
                    'params' => array(
                            ':staff_id' => $_SESSION['userid']
                        )
                ));
            Yii::app()->session['service_person_id']=$service_person['id'];

    
            $arr_order = array();
            $arr_order = ServiceOrder::model()->findAll(array(
                "condition" => "service_person_id=:service_person_id",
                "params" => array(
                    ":service_person_id" => $_SESSION['service_person_id']
                ),
                'order'=>'order_date DESC', 
            ));
            /*print_r($arr_order);die;*/
            /*echo $adder['UserId'];die;*/
            $this->render("my",array(
                'userId' => $_SESSION['userid'],
                "arr_order" => $arr_order,
            ));
        }else{ //未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=service/my&code=&service_team_id='.$_SESSION['service_team_id'];
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$service_team['corpid']."&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect&from=&this_order=";
                echo "<script>window.location='".$url."';</script>";
            };
            /*echo $code;*/
            $service_team = ServiceTeam::model()->findByPk($_SESSION['service_team_id']); 
            /*print_r($service_team);*/
            $t=new WPRequest;
            $userId = $t->getUserId($code,$service_team['corpid'],$service_team['corpsecret']);
            $adder=array("UserId"=>"222","DeviceId"=>"");
            $adder=json_decode($userId,true);

            if(!empty($adder['UserId'])) {
                Yii::app()->session['userid']=$adder['UserId'];
                /*echo $adder['UserId'];*/
                $service_person = ServicePerson::model()->find(array(
                        'condition' => 'staff_id=:staff_id',
                        'params' => array(
                                ':staff_id' => $_SESSION['userid']
                            )
                    ));
                Yii::app()->session['service_person_id']=$service_person['id'];
                Yii::app()->session['service_type']=$service_person['service_type'];

        
                $arr_order = array();
                $arr_order = ServiceOrder::model()->findAll(array(
                    "condition" => "service_person_id=:service_person_id",
                    "params" => array(
                        ":service_person_id" => $_SESSION['service_person_id']
                    ),
                    'order'=>'order_date DESC', 
                ));
                /*print_r($arr_order);die;*/
                /*echo $adder['UserId'];die;*/
                $this->render("my",array(
                    'userId' => $_SESSION['userid'],
                    "arr_order" => $arr_order,
                ));
            }
        };
    }

    public function actionProduct()
    {
        $service_team = array();
        if(isset($_GET['service_team_id'])){
            Yii::app()->session['service_team_id']=$_GET['service_team_id'];  
            $service_team = ServiceTeam::model()->findByPk($_GET['service_team_id']);  
        };

        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['service_team_id']) && isset($_SESSION['service_person_id'])){//已登陆
            //echo '已登陆';
            /*echo $_SESSION['userid'];die;*/
            $arr_product = ServiceProduct::model()->findAll(array(
                    "condition" => "service_person_id=:service_person_id",
                    "params" => array(
                        ":service_person_id" => $_SESSION['service_person_id']
                    )
                ));
                /*print_r($arr_order);die;*/
                /*echo $adder['UserId'];die;*/
                $this->render("product",array(
                    'userId' => $_SESSION['userid'],
                    "arr_product" => $arr_product,
                ));
        
        }else{ //未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=service/product&code=&service_team_id='.$_SESSION['service_team_id'];
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$service_team['corpid']."&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect&from=&this_order=";
                echo "<script>window.location='".$url."';</script>";
            };
            /*echo $code;*/
            $service_team = ServiceTeam::model()->findByPk($_SESSION['service_team_id']); 
            /*print_r($service_team);*/
            $t=new WPRequest;
            $userId = $t->getUserId($code,$service_team['corpid'],$service_team['corpsecret']);
            $adder=array("UserId"=>"222","DeviceId"=>"");
            $adder=json_decode($userId,true);

            if(!empty($adder['UserId'])) {
                Yii::app()->session['userid']=$adder['UserId'];
                /*echo $adder['UserId'];*/
                $service_person = ServicePerson::model()->find(array(
                        'condition' => 'staff_id=:staff_id',
                        'params' => array(
                                ':staff_id' => $_SESSION['userid']
                            )
                    ));
                Yii::app()->session['service_person_id']=$service_person['id'];
                Yii::app()->session['service_type']=$service_person['service_type'];

                $arr_product = ServiceProduct::model()->findAll(array(
                    "condition" => "service_person_id=:service_person_id",
                    "params" => array(
                        ":service_person_id" => $_SESSION['service_person_id']
                    )
                ));
                /*print_r($arr_order);die;*/
                /*echo $adder['UserId'];die;*/
                $this->render("product",array(
                    'userId' => $_SESSION['userid'],
                    "arr_product" => $arr_product,
                ));
            }
        };
    }

    public function actionProduct_add()
    {
        if($_GET['type'] == "edit"){
            $product = ServiceProduct::model()->findByPk($_GET['product_id']);
            $this->render("product_add",array(
                    'product' => $product,
                    'type' => "edit",
                ));
        }else{
            $product = array(
                    'id' => "",
                    'product_name' => "",
                    'price' => "",
                    'unit' => "",
                    'description' => "",
                );
            $this->render("product_add",array(
                    'product' => $product,
                    'type' => "new",
                ));
        }
    }

    public function actionInsert_product()
    {
        /*Yii::app()->session['service_person_id'] = 1;
        Yii::app()->session['service_type'] = 1;*/
        $admin=new ServiceProduct;
        $admin->service_person_id=$_SESSION['service_person_id'];
        $admin->service_type=$_SESSION['service_type'];
        $admin->product_name=$_POST['na'];
        $admin->price=$_POST['price'];
        $admin->unit=$_POST['unit'];
        $admin->update_time=$_POST['update_time'];
        $admin->description=$_POST['description'];
        $admin->save();
    }

    public function actionUpdate_product()
    {
        ServiceProduct::model()->updateByPk($_POST['product_id'],array('product_name'=>$_POST['na'],'price'=>$_POST['price'],'unit'=>$_POST['unit']));
    }

    public function actionDel_product()
    {
        ServiceProduct::model()->deleteByPk($_POST['product_id']);
    }
}
