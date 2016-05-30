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

    public function actionList()
    {
        $type=$_GET['type_id'];
        $sql = yii::app()->db->createCommand("select service_person.id,team_id,service_person.`name`,avatar,gender,supplier.id as supplier_id from service_person left join supplier on service_person.staff_id=supplier.staff_id where supplier.account_id=".$_SESSION['account_id']." and service_type=".$type);
        $service = $sql->queryAll();
        // $service = ServicePerson::model()->findAll(array(
        //         'condition' => 'service_type=:service_type',
        //         'params' => array(
        //                 ':service_type' => $type
        //             )
        //     ));

        $service_id=array();
        foreach ($service as $key => $value) {
            $service_id[]=$value['id'];
        };
        $criteria = new CDbCriteria;
        $criteria->addInCondition('service_person_id', $service_id);
        $service_order = ServiceOrder::model()->findAll($criteria);

        $service_data = array();
        foreach ($service as $key => $value) {
            $team=ServiceTeam::model()->findByPk($value['team_id']);
            $case=CaseInfo::model()->find(array(
                    'condition' => 'CI_Type=:CI_Type && CT_ID=:CT_ID',
                    'params' => array(
                            ':CI_Type' => 6,
                            ':CT_ID' => $value['staff_id']
                        )
                ));
            $Pic="";
            $t=explode('.', $case['CI_Pic']);
            if(isset($t[0]) && isset($t[1])){
                $Pic = "http://file.cike360.com".$t[0]."_sm.".$t[1];
            };
            $item=array(
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'team_name' => $team['name'],
                    'avatar' => $Pic,
                    'gender' => $value['gender'],
                    'order_num' => 0,
                    'starting_price' => 0,
                );
            foreach ($service_order as $key1 => $value1) {
                if($value1['service_person_id'] == $value['id']){
                    $item['order_num']++;
                }
            };
            $t = ServiceProduct::model()->findAll(array(
                    'condition' => 'service_person_id = :service_person_id',
                    'params' => array(
                            ':service_person_id' => $value['id'],
                        ),
                    'order' => 'price'
                ));
            if(!empty($t)){
                $item['starting_price'] = $t[0]['price'];
            }else{
                $item['starting_price'] =0;
            }
            $item['supplier_id'] = $value['supplier_id'];
            
            $service_data[] = $item; 
        };

        $service_team = ServiceTeam::model()->findAll();
        //print_r($service_data);

        $this->render('list',array(
                'service_data' => $service_data,
                'service_team' => $service_team,
            ));
    }

    public function actionDatefilter()
    {
        /*$_POST['team'] = "1";
        $_POST['gender'] ="";
        $_POST['date'] ="";*/

        $service_order = ServiceOrder::model()->findAll();
        $result_date = array();
        foreach ($service_order as $key => $value) {
            if($_POST['date'] != ""){
                $t1 = explode(" ",$value['order_date']);
                if($t1[0] == $_POST['date']){
                    $result_date[] = $value['service_person_id'];
                }
            };    
        }
        $result_gender_team = array();
        $service_person = ServicePerson::model()->findAll();
        foreach ($service_person as $key => $value) {
            if($_POST['gender'] != ""){
                if($value['gender'] != $_POST['gender']){
                    if($_POST['team'] != ""){
                        if($value['team_id'] != $_POST['team']){
                            $result_gender_team[] = $value['id'];
                        };
                    }else{
                        $result_gender_team[] = $value['id'];
                    };
                }else if($_POST['team'] != ""){
                    if($value['team_id'] != $_POST['team']){
                        $result_gender_team[] = $value['id'];
                    };
                };
            }else if($_POST['team'] != ""){
                if($value['team_id'] != $_POST['team']){
                    $result_gender_team[] = $value['id'];
                };
            };
        };

        foreach ($result_date as $key => $value) {
            $t = 0;
            foreach ($result_gender_team as $key1 => $value1) {
                if($value == $value1){$t++;};
            };
            if($t == 0){
                $result_gender_team[] = $value;
            }
        }

        $result = "";
        foreach ($result_gender_team as $key => $value) {
            $result .= $value;
            $result .= ",";
        }
        /*if($result != ""){
            $result = substr($result,0,strlen($result)-1); 
        }*/

        echo $result;
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
        if($_GET['from'] != 'design' && $_GET['from'] != 'team_list'){
            $service_team = array();
            if(isset($_GET['service_team_id'])){
                Yii::app()->session['service_team_id']=$_GET['service_team_id'];  
                $service_team = ServiceTeam::model()->findByPk($_GET['service_team_id']);  
            };
            Yii::app()->session['userid']=1;
            Yii::app()->session['code']=1;
            Yii::app()->session['service_person_id']=1;
            Yii::app()->session['service_team_id']=1;

            if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['service_team_id']) && isset($_SESSION['service_person_id'])){//已登陆
                //echo '已登陆';
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                $this->render("index",array(
                    'service_person_id' => $_SESSION['service_person_id'],
                    'name' => "",
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
                        'name' => "",
                        'first_show_year' => $y,
                        'first_show_month' => $m,
                        'first_show_day' => $d,
                    ));
                }
            };
        }else if($_GET['from'] == 'design'){
            //echo "非登录";
            $supplier_product = SupplierProduct::model()->findByPk($_GET['supplier_product_id']);
            $supplier = Supplier::model()->findByPk($supplier_product['supplier_id']);
            $staff = Staff::model()->findByPk($supplier['id']);
            $service_person = ServicePerson::model()->find(array(
                    'condition' => 'staff_id = :staff_id',
                    'params' => array(
                            ':staff_id' => $staff['id'], 
                        )
                ));
            /*print_r($staff);die;*/
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $this->render("index",array(
                'service_person_id' => $service_person['id'],
                'name' => $service_person['name'],
                'first_show_year' => $y,
                'first_show_month' => $m,
                'first_show_day' => $d,
            ));
        }else if($_GET['from'] == 'team_list'){
            //echo "非登录";

            $service_person = ServicePerson::model()->findByPk($_GET['service_person_id']);
            /*print_r($staff);die;*/
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $this->render("index",array(
                'service_person_id' => $service_person['id'],
                'name' => $service_person['name'],
                'first_show_year' => $y,
                'first_show_month' => $m,
                'first_show_day' => $d,
            ));
        }       
    }

    public function actionPersonnel_host()
    {
        /*if($_GET['from'] != 'design' && $_GET['from'] != 'team_list'){
            $service_team = array();
            if(isset($_GET['service_team_id'])){
                Yii::app()->session['service_team_id']=$_GET['service_team_id'];  
                $service_team = ServiceTeam::model()->findByPk($_GET['service_team_id']);  
            };
            Yii::app()->session['userid']=1;
            Yii::app()->session['code']=1;
            Yii::app()->session['service_person_id']=1;
            Yii::app()->session['service_team_id']=1;

            if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['service_team_id']) && isset($_SESSION['service_person_id'])){*///已登陆
                //echo '已登陆';
                $CI_Type=0;
                if($_GET['type_id']==3){$CI_Type = 6;};
                if($_GET['type_id']==4){$CI_Type = 13;};
                if($_GET['type_id']==5){$CI_Type = 14;};
                if($_GET['type_id']==6){$CI_Type = 15;};


                $result = yii::app()->db->createCommand("select service_person.staff_id,service_person.id,service_person.name as name,case_info.CI_Pic as avatar, case_info.CI_Pic as poster,case_resources.CR_Path as sample_video from service_person left join case_info on service_person.staff_id=case_info.CT_ID left join case_resources on case_info.CI_ID=case_resources.CI_ID where service_person.id=".$_GET['service_person_id']." and case_info.CI_Type=".$CI_Type." and case_resources.CR_Type=2");
                $temp = $result->queryAll();
                $service_person = array(
                        'id' => "",
                        'staff_id' => "",
                        'name' => "",
                        'avatar' => "",
                        'poster' => "",
                        'sample_video' => '',
                    );
                if(!empty($temp)){
                    $service_person = $temp[0];
                    $t=explode('.', $service_person['avatar']);
                    if(isset($t[0]) &&isset($t[1])){
                        $service_person['avatar']='http://file.cike360.com'.$t[0]."_sm.".$t[1];
                    };
                    $t=explode('.', $service_person['poster']);
                    if(isset($t[0]) &&isset($t[1])){
                        $service_person['poster']='http://file.cike360.com'.$t[0]."_sm.".$t[1];
                    };
                    $service_person['sample_video']='http://file.cike360.com'.$service_person['sample_video'];    
                };
                

                //$service_person = ServicePerson::model()->findByPk($_GET['service_person_id']);

                $y = date("Y");
                $m = date("m");
                $d = date("d");
                /*print_r($service_person);die;*/
                $this->render("personnel_host",array(
                    'service_person' => $service_person,
                    'first_show_year' => $y,
                    'first_show_month' => $m,
                    'first_show_day' => $d,
                ));

            /*}else{*///未登录
                //echo '未登陆';
                /*$code = $_GET['code'];
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
                    Yii::app()->session['userid']=$adder['UserId'];*/
                    /*echo $adder['UserId'];*/
                    /*$service_person = ServicePerson::model()->find(array(
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
                    $this->render("personnel_host",array(
                        'service_person_id' => $_SESSION['service_person_id'],
                        'name' => "",
                        'first_show_year' => $y,
                        'first_show_month' => $m,
                        'first_show_day' => $d,
                    ));
                }
            };
        }else if($_GET['from'] == 'design'){*/
            //echo "非登录";
            /*$supplier_product = SupplierProduct::model()->findByPk($_GET['supplier_product_id']);
            $supplier = Supplier::model()->findByPk($supplier_product['supplier_id']);
            $staff = Staff::model()->findByPk($supplier['id']);
            $service_person = ServicePerson::model()->find(array(
                    'condition' => 'staff_id = :staff_id',
                    'params' => array(
                            ':staff_id' => $staff['id'], 
                        )
                ));*/
            /*print_r($staff);die;*/
            /*$y = date("Y");
            $m = date("m");
            $d = date("d");
            $this->render("personnel_host",array(
                'service_person_id' => $service_person['id'],
                'name' => $service_person['name'],
                'first_show_year' => $y,
                'first_show_month' => $m,
                'first_show_day' => $d,
            ));
        }else if($_GET['from'] == 'team_list'){*/
            //echo "非登录";

            /*$service_person = ServicePerson::model()->findByPk($_GET['service_person_id']);*/
            /*print_r($staff);die;*/
            /*$y = date("Y");
            $m = date("m");
            $d = date("d");
            $this->render("personnel_host",array(
                'service_person_id' => $service_person['id'],
                'name' => $service_person['name'],
                'first_show_year' => $y,
                'first_show_month' => $m,
                'first_show_day' => $d,
            ));
        }  */
    }

    public function actionHotel_list_pro()
    {
        $post = json_decode(file_get_contents('php://input'));
        $staff = Staff::model()->findByPk($post->token);

        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $staff['account_id']
                    )
            ));

        $hotel_arr = array();

        foreach ($hotel as $key => $value) {
            $data = array(
                'hotelid' => $value['id'],
                'hotelname' => $value['name'],);
            $hotel_arr[] = $data;
        }
        echo json_encode($hotel_arr);
    }


    public function actionService_product_list()
    {
        if (!empty($_GET['order_id'])) {
            $selected = OrderProduct::model()->findAll(array(
                "condition" => "order_id = :order_id",
                "params" => array(":order_id" => $_GET['order_id'],),
                ));
        }
        // $result = yii::app()->db->createCommand("select order_product.id as order_product_id,supplier_product.id as supplier_product_id,actual_price,order_product.unit,supplier.id as supplier_id from order_product left join supplier_product on order_product.product_id=supplier_product.id left join supplier on supplier_id=supplier.id where supplier.type_id=3 and order_product.order_id=".$_GET['order_id']);
        // $select = $result->queryAll();
        // $supplier = Supplier::model()->find(array(
        //         'condition' => 'staff_id=:staff_id',
        //         'params' => array(
        //                 ':staff_id' => $_GET['staff_id']
        //             )
        //     ));
        $supplier = Supplier::model()->find(array(
                'condition' => 'staff_id=:staff_id',
                'params' => array(
                        ':staff_id' => $_GET['staff_id']
                    )
            ));
        $supplier_product = SupplierProduct::model()->findAll(array(
                'condition' => 'supplier_id=:supplier_id',
                'params' => array(
                        ':supplier_id' => $supplier['id']
                    )
            ));
        $this->render('service_product_list',array(
                'supplier_product'  => $supplier_product,
                'selected'          => $selected,
            ));
    }

    public function actionTeamList()
    {
        /*if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){*///已登陆
            $this->getTeamList($_GET['service_type']);
        /*}else{
            $company = array();
            if(isset($_GET['account_id'])){
                Yii::app()->session['account_id']=$_GET['account_id'];
                $company = StaffCompany::model()->findByPk($_SESSION['account_id']);    
            };
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=service/teamlist&t=plan&code=';
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$company['corpid']."&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect&from=&this_order=";
                echo "<script>window.location='".$url."';</script>";
            };
            $company = StaffCompany::model()->findByPk($_SESSION['account_id']); 
            $t=new WPRequest;
            $userId = $t->getUserId($code,$company['corpid'],$company['corpsecret']);
            $adder=array("UserId"=>"222","DeviceId"=>"");
            $adder=json_decode($userId,true);
            if(!empty($adder['UserId'])) {
                Yii::app()->session['userid']=$adder['UserId'];
                $staff = Staff::model()->findByPk($adder['UserId']);
                Yii::app()->session['account_id']=$staff['account_id'];
                Yii::app()->session['staff_hotel_id']=$staff['hotel_list'];

                $this->getTeamList($_GET['service_type']);
            };
        };*/
    }

    public function getTeamList($service_type)
    {
        $company = StaffCompany::model()->findByPk($_SESSION['account_id']);

        $service_person = ServicePerson::model()->findAll(array(
                'condition' => 'service_type = :service_type',
                'params' => array(
                        ':service_type' => $service_type,
                    )
            ));
        //不重复的输出所有包含以上service_type的团队id，构造team_list
        if(!empty($service_person)){
            $team_list=array();
            $i=0;
            $team_list[0]=$service_person[0]['team_id'];
            foreach ($service_person as $key => $value) {
                $j=0;
                foreach ($team_list as $key1 => $value1) {
                    if($value1 == $value['team_id']){$j++;};
                };
                if($j == 0){
                    $team_list[++$i] = $value['team_id'];
                }
            };
            //在team_list里删除当前公司的first_service_team
            foreach ($team_list as $key => $value) {
                if($value == $company['first_service_team']){
                    array_splice($team_list, $key, 1);
                };
            };
            //根据first_service_team，查找优先的服务人员团队
            $first_team_data=array();
            $service_person1 = ServicePerson::model()->findAll(array(
                    'condition' => 'team_id = :team_id',
                    'params' => array(
                            ':team_id' => $company['first_service_team'],
                        )
                ));
            $service_person_data = array();
            foreach ($service_person1 as $key1 => $value1) {
                $item['name'] = $value1['name'];
                $item['id'] = $value1['id'];
                $t = ServiceProduct::model()->findAll(array(
                        'condition' => 'service_person_id = :service_person_id',
                        'params' => array(
                                ':service_person_id' => $value1['id'],
                            ),
                        'order' => 'price'
                    ));
                $item['price'] = $t[0]['price'];
                $service_person_data[] = $item;
            };
            $first_team_data['team_member'] = $service_person_data;
            $service_team = ServiceTeam::model()->findByPk($company['first_service_team']);
            $first_team_data['team_name'] = $service_team['name'];

            //根据team_list查找每一个团队的成员信息，并构造数组
            /*print_r($team_list);die;*/
            $team_data=array();
            if(!empty($team_list)){
                foreach ($team_list as $key => $value) {
                    $item = array();
                    $service_person2 = ServicePerson::model()->findAll(array(
                            'condition' => 'team_id = :team_id',
                            'params' => array(
                                    ':team_id' => $value,
                                )
                        ));
                    $service_person_data = array();
                    foreach ($service_person2 as $key1 => $value1) {
                        $item = array();
                        $item['name'] = $value1['name'];
                        $item['id'] = $value1['id'];
                        $t = ServiceProduct::model()->findAll(array(
                                'condition' => 'service_person_id = :service_person_id',
                                'params' => array(
                                        ':service_person_id' => $value1['id'],
                                    ),
                                'order' => 'price'
                            ));
                        if(!empty($t)){
                            $item['price'] = $t[0]['price'];    
                        }else{
                            $item['price'] = 0;
                        };
                        $service_person_data[] = $item;
                    }
                    $item['team_member'] = $service_person_data;
                    $service_team = ServiceTeam::model()->findByPk($value);
                    $item['team_name'] = $service_team['name'];
                    $team_data[] = $item;
                };
            };   

            //查询当前公司的常用主持人
            $supplier = Supplier::model()->findAll(array(
                    'condition' => 'type_id = :type_id',
                    'params' => array(
                            ':type_id' => $service_type,
                        ),
                ));
            $service_person_id = array();
            foreach ($supplier as $key => $value) {
                $service_person = ServicePerson::model()->find(array(
                        'condition' => 'staff_id = :staff_id',
                        'params' => array(
                                ':staff_id' => $value['staff_id'],
                            ),
                    ));
                $service_person_id[] = $service_person['id'];
            }
            $this->render('team_list',array(
                    'team_data' => $team_data,
                    'first_team_data' => $first_team_data,
                    'service_person_id' => $service_person_id,
                    'type' => 'has',
                ));
        }else{
            $team_data = array(
                    'team_name' => "",
                    'team_member' => array(
                            0 => array(
                                    'name' => "",
                                    'id' => "",
                                    'price' => "",
                                )
                        )
                );
            $first_team_data = array(
                    'team_name' => "",
                    'team_member' => array(
                            0 => array(
                                    'name' => "",
                                    'id' => "",
                                    'price' => "",
                                )
                        )
                );
            $service_person_id = array();
            $this->render('team_list',array(
                    'team_data' => $team_data,
                    'first_team_data' => $first_team_data,
                    'service_person_id' => $service_person_id,
                    'type' => 'none',
                ));
        }
        
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

        $service_person = ServicePerson::model()->findByPk($_SESSION['service_person_id']);
        $supplier = Supplier::model()->findAll(array(
                'condition' => 'staff_id = :staff_id',
                'params' => array(
                        ':staff_id' => $service_person['staff_id'],
                    )
            ));

        foreach ($supplier as $key => $value) {
            $admin=new SupplierProduct;
            $admin->account_id=$value['account_id'];
            $admin->supplier_id=$value['id'];
            $admin->supplier_type_id=$value['type_id'];
            $admin->standard_type=0;
            $admin->name=$_POST['na'];
            $admin->category=2;
            $admin->unit_price=$_POST['price']*2;
            $admin->unit=$_POST['unit'];
            $admin->unit_cost=$_POST['price'];
            $admin->update_time=$_POST['update_time'];
            $admin->description=$_POST['description'];
            $admin->save();   
        };    
    }

    public function actionUpdate_product()
    {
        ServiceProduct::model()->updateByPk($_POST['product_id'],array('product_name'=>$_POST['na'],'price'=>$_POST['price'],'unit'=>$_POST['unit']));
        $service_product = ServiceProduct::model()->findByPk($_POST['product_id']);
        $service_person = ServicePerson::model()->findByPk($service_product['service_person_id']);
        $supplier = Supplier::model()->findAll(array(
                'condition' => 'staff_id = :staff_id',
                'params' => array(
                        ':staff_id' => $service_person['staff_id'],
                    )
            ));
        
        $supplier_id = array();
        foreach ($supplier as $key => $value) {
            $supplier_id[] = $value['id'];
        };

        $criteria = new CDbCriteria;  
        $criteria->addInCondition('supplier_id', $supplier_id);
        $criteria->addCondition("update_time = :update_time");    
        $criteria->params[':update_time']=$service_product['update_time'];
        $supplier_product = SupplierProduct::model()->findAll($criteria);   

        foreach ($supplier_product as $key => $value) {
             SupplierProduct::model()->updateByPk($value['id'],array('name'=>$_POST['na'],'unit_price'=>$_POST['price']*2,'unit_cost'=>$_POST['price'],'unit'=>$_POST['unit']));
        };
    }

    public function actionDel_product()
    {
        

        $service_product = ServiceProduct::model()->findByPk($_POST['product_id']);
        $service_person = ServicePerson::model()->findByPk($service_product['service_person_id']);
        $supplier = Supplier::model()->findAll(array(
                'condition' => 'staff_id = :staff_id',
                'params' => array(
                        ':staff_id' => $service_person['staff_id'],
                    )
            ));
        
        $supplier_id = array();
        foreach ($supplier as $key => $value) {
            $supplier_id[] = $value['id'];
        };

        $criteria = new CDbCriteria;  
        $criteria->addInCondition('supplier_id', $supplier_id);
        $criteria->addCondition("update_time = :update_time");    
        $criteria->params[':update_time']=$service_product['update_time'];
        $supplier_product = SupplierProduct::model()->findAll($criteria); 

        foreach ($supplier_product as $key => $value) {
             SupplierProduct::model()->deleteByPk($value['id']);
        };
        /*print_r($supplier_product);*/
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

    public function actionInsertSupplier()
    {   
        /*$_POST['service_person_id'] = 2;
        $_POST['account_id'] = 1;*/
        $service_person = ServicePerson::model()->findByPk($_POST['service_person_id']);

        $admin=new Supplier;
        $admin->account_id=$_POST['account_id'];
        $admin->staff_id=$service_person['staff_id'];
        $admin->type_id=$service_person['service_type'];
        $admin->save();

        $service_product = ServiceProduct::model()->findAll(array(
                'condition' => 'service_person_id = :service_person_id',
                'params' => array(
                        ':service_person_id' => $_POST['service_person_id'],
                    )
            ));
        /*print_r($service_product);die;*/
        $supplier = Supplier::model()->find(array(
                'condition' => 'account_id = :account_id && staff_id = :staff_id ',
                'params' => array(
                        ':account_id' => $_POST['account_id'],
                        ':staff_id' => $service_person['staff_id'],
                    )
            ));
        foreach ($service_product as $key => $value) {
            $admin1=new SupplierProduct;
            $admin1->account_id=$_POST['account_id'];
            $admin1->supplier_id=$supplier['id'];
            $admin1->supplier_type_id=$service_person['service_type'];
            $admin1->standard_type=0;
            $admin1->name=$value['product_name'];
            $admin1->category=2;
            $admin1->unit_price=$value['price']*2;
            $admin1->unit_cost=$value['price'];
            $admin1->unit=$value['unit'];
            $admin1->description=$value['description'];
            $admin1->save();
        }
            
    }

    public function actionDelSupplier()
    {
        $service_person = ServicePerson::model()->findByPk($_POST['service_person_id']);

        $supplier = Supplier::model()->find(array(
                'condition' => 'account_id = :account_id && staff_id = :staff_id ',
                'params' => array(
                        ':account_id' => $_POST['account_id'],
                        ':staff_id' => $service_person['staff_id'],
                    )
            ));


        $condition = 'account_id = :account_id && staff_id = :staff_id';
        $params = array(
                ':account_id' => $_POST['account_id'],
                ':staff_id' => $service_person['staff_id'],
            );
        Supplier::model()->deleteAll($condition,$params);

        $condition = 'account_id = :account_id && supplier_id = :supplier_id';
        $params = array(
                ':account_id' => $_POST['account_id'],
                ':supplier_id' => $supplier['id'],
            );
        SupplierProduct::model()->deleteAll($condition,$params);
    }
}
