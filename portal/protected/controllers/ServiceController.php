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

    public function actionCreate_order()
    {
        if($_GET['type'] == "edit"){
            $order = ServiceOrder::model()->findByPk($_GET['service_order_id']);
        }else{
            $order = array(
                    'order_date' => "",
                    'order_name' => "",
                    'order_place' => "",
                    'linkman_name' => "",
                    'linkman_phone' => "",
                    'price' => "",
                    'remarks' => "",
                ); 
        };
        $this->render("create_order",array(
                'order' => $order,
            ));
        
    }

    public function actionIndex()
    {
        $service_team = array();
        if(isset($_GET['service_team_id'])){
            Yii::app()->session['service_team_id']=$_GET['service_team_id'];  
            $service_team = ServiceTeam::model()->findByPk($_GET['service_team_id']);  
        };
        /*Yii::app()->session['userid']=1;
        Yii::app()->session['code']=1;
        Yii::app()->session['service_person_id']=1;*/


        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['service_team_id']) && isset($_SESSION['service_person_id'])){//已登陆
            //echo '已登陆';
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $this->render("index",array(
                'service_person_id' => $_SESSION['service_person_id'],
                'first_show_year' => $y,
                'first_show_month' => $m,
                'first_show_day' => $d,
            ));

        }else{//未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=service/index&from=&code=';
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$service_team['corpid']."&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect&from=&this_order=";
                echo "<script>window.location='".$url."';</script>";
            };
            $service_team = ServiceTeam::model()->findByPk($_SESSION['service_team_id']); 
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
                
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                $this->render("index",array(
                    'service_person_id' => $_SESSION['service_person_id'],
                    'first_show_year' => $y,
                    'first_show_month' => $m,
                    'first_show_day' => $d,
                ));
            }
        };
    }

    public function actionIndexData()
    {
        /*$_POST['service_person_id'] = 1;
        $year = 2016;
        $month = 4;*/


        $maybe_data = "";

        $order = ServiceOrder::model()->findAll(array(
            "condition" => "service_person_id=:service_person_id",
            "params" => array(
                ":service_person_id" => $_POST['service_person_id'],
            ),
        ));

        $orderData = array();
        
        foreach ($order as $key => $value) {

            $arr = explode(" ",$value['order_date']);
            $data = explode("-",$arr[0]); 

            $item = array(
                'service_order_id' => $value['id'] ,
                'service_team_id' => $value['service_team_id'],
                'service_person_id' => $value['service_person_id'],
                'order_id' => $value['order_id'],
                'order_name' => $value['order_name'],
                'linkman_name' => $value['linkman_name'],
                'linkman_phone' => $value['linkman_phone'],
                'service_type' => $value['service_type'],
                'order_year' => (int)$data[0],
                'order_month' => (int)$data[1],
                'order_data' => (int)$data[2],
                'order_place' => $value['order_place'], 
                'order_status' => $value['order_status'], 
            );
            
            $orderData[] = $item;

        }

        $maybe_data = "";
        $half_data = "";
        $data = "";

        /*$alldate = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);*/

        foreach ($orderData as $key => $value) {
            
            if ($value['order_year'] == $_POST['year'] && $value['order_month'] == $_POST['month'] ) {
                $maybe_data .= $value['order_data']."," ;    
            };
    
        }

        /*foreach ($alldate as $key => $value) {
            if($value == 6){
                $data .= (string)$key."," ; 
            }else if($value != 0){
                $half_data .= (string)$key."," ; 
            }
        };*/
        /*var_dump($maybe_data);die;*/
        $arr_order = array();
        if($data == ""){$arr_order['data'] = 0;}else {$arr_order['data'] = substr($data,0,strlen($data)-1);};
        if($half_data == ""){$arr_order['half_data'] = 0;}else {$arr_order['half_data'] = substr($half_data,0,strlen($half_data)-1);};
        if($maybe_data == ""){$arr_order['maybe_data'] = 0;}else {$arr_order['maybe_data'] = substr($maybe_data,0,strlen($maybe_data)-1);};
        
        $arr_order=implode('|',$arr_order);
        echo $arr_order;
        /*var_dump($maybe_data);die; */
    }

    public function actionFindMonthOrder()
    {
        /*die;*/
        /*$_POST['service_person_id'] = 1;
        $_POST['year']=2016;
        $_POST['month']=4;*/

        $criteria = new CDbCriteria;        
        $criteria->addCondition("service_person_id = :service_person_id");    
        $criteria->params[':service_person_id']=$_POST['service_person_id'];   
        $order_hotel = ServiceOrder::model()->findAll($criteria);  

        $arr_order_all = array();
        foreach ($order_hotel as $key => $value) {

            $arr = explode(" ",$value['order_date']);
            $data = explode("-",$arr[0]); 

            $item = array(
                'service_order_id' => $value['id'] ,
                'service_team_id' => $value['service_team_id'],
                'service_person_id' => $value['service_person_id'],
                'order_id' => $value['order_id'],
                'order_name' => $value['order_name'],
                'linkman_name' => $value['linkman_name'],
                'linkman_phone' => $value['linkman_phone'],
                'service_type' => $value['service_type'],
                'order_year' => (int)$data[0],
                'order_month' => (int)$data[1],
                'order_data' => (int)$data[2],
                'order_place' => $value['order_place'],
                'order_status' => $value['order_status'],  
            );

            $arr_order_all[] = $item;
        }

        $arr_order = array();
        $i=0;

        foreach ($arr_order_all as $key => $value) {
            if($value['order_year'] == $_POST['year'] && $value['order_month'] == $_POST['month']){
                /*$temp = json_encode($value, JSON_UNESCAPED_UNICODE);*/
                $arr_order[$i] = $value;
                $i++;
            }
        };
        /*print_r($arr_order);die;*/
        function json_encode_ex($value)
        {
            if (version_compare(PHP_VERSION,'5.4.0','<'))
            {
                $str = json_encode($value);
                $str = preg_replace_callback(
                                            "#\\\u([0-9a-f]{4})#i",
                                            function($matchs)
                                            {
                                                 return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
                                            },
                                             $str
                                            );
                return $str;
            }
            else
            {
                return json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }

        json_encode_ex($arr_order);

        echo json_encode($arr_order);
        
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

    public function actionInsert_order()
    {
        /*Yii::app()->session['service_team_id'] = 1;
        Yii::app()->session['service_person_id'] = 1;
        Yii::app()->session['service_type'] = 1;*/
        $admin=new ServiceOrder;
        $admin->service_team_id=$_SESSION['service_team_id'];
        $admin->service_person_id=$_SESSION['service_person_id'];
        $admin->service_type=$_SESSION['service_type'];
        $admin->order_name=$_POST['order_name'];
        $admin->linkman_name=$_POST['linkman'];
        $admin->linkman_phone=$_POST['linkman_phone'];
        $admin->order_date=$_POST['order_date'];
        $admin->update_time=$_POST['update_time'];
        $admin->order_place=$_POST['place'];
        $admin->remarks=$_POST['remarks'];
        $admin->price=$_POST['price'];
        $admin->save();
    }

    public function actionUpdate_order()
    {
        ServiceOrder::model()->updateByPk($_POST['service_order_id'],array('order_name'=>$_POST['order_name'],'linkman_name'=>$_POST['linkman'],'linkman_phone'=>$_POST['linkman_phone'],'order_date'=>$_POST['order_date'],'order_place'=>$_POST['place'],'remarks'=>$_POST['remarks'],'price'=>$_POST['price']));
    }

    public function actionDel_order()
    {
        ServiceOrder::model()->deleteByPk($_POST['service_order_id']);
    }
}
