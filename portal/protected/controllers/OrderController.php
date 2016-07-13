<?php

include_once('../library/WPRequest.php');

class OrderController extends InitController
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

    public function actionOrder()
    {
        /*Yii::app()->session['userid']=100;*/
        // $order = Order::model()->findAll(array(
        //         'condition' => 'planner_id = :planner_id || designer_id = :designer_id',
        //         'params' => array(
        //                 ':planner_id' => $_SESSION['userid'],
        //                 ':designer_id' => $_SESSION['userid']
        //             ),
        //         'order' => 'update_time DESC'
        //     ));

        // $supplier_type_id = 16 ;//supplier_type_id为16的即“推单渠道”

        // $list = SupplierProduct::model()->findAll(array(
        //     "condition" => "supplier_type_id=:id",
        //     "params"    => array( ":id" => $supplier_type_id), 
        //                                                )
        //                                          );
        // $product_id = array();
        // foreach ($list as $key => $value) {
        //     $item = array();
        //     $item['id'] = $value['id'];
        //     $item['name'] = $value['name'];
        //     $product_id[$key] = $value['id'];
        // }
        // $order_data = array();
        // foreach ($order as $key => $value) {
        //     if($value['account_id'] == $_SESSION['account_id'] && $value['staff_hotel_id'] == $_SESSION['staff_hotel_id']){
        //         $item = array();
        //         $item['order_name'] = $value['order_name'];
        //         $item['order_type'] = $value['order_type'];
        //         $item['id'] = $value['id'];
        //         $t = explode(" ",$value['order_date']);
        //         $item['order_date'] = $t[0];
        //         $item['order_status'] = $value['order_status'];
        //         $staff = Staff::model()->findByPk($value['planner_id']);
        //         $item['planner_name'] = $staff['name'];

        //         $criteria3 = new CDbCriteria; 
        //         $criteria3 -> addInCondition("product_id",$product_id);
        //         $criteria3 -> addCondition("order_id=:id");
        //         $criteria3 ->params[':id']= $value['id'];; 
        //         $select = OrderProduct::model()->find($criteria3);

        //         $select_reference = SupplierProduct::model()->find(array(
        //             "condition" => "id=:id",
        //             "params" => array( ":id" => $select['product_id'])
        //             )
        //         );


        //         $order_data[] = $item;
        //     };
        // };
        $result = yii::app()->db->createCommand("SELECT `order`.order_name,order_type,`order`.id,`order`.order_date,order_status,staff.`name` AS planner_name,supplier_product.`name` AS reference_name 
            FROM staff RIGHT JOIN `order` ON staff.id=planner_id LEFT JOIN (order_product RIGHT JOIN supplier_product ON supplier_product.id=product_id AND supplier_type_id=16) ON order_id=`order`.id 
            where (planner_id=".$_SESSION['userid']." OR designer_id=".$_SESSION['userid'].") AND staff_hotel_id=".$_SESSION['staff_hotel_id']." 
            ORDER BY `order`.update_time DESC");
        $order_data = $result->queryAll();

        $hotel = StaffHotel::model()->findByPk($_SESSION['staff_hotel_id']);

        $this->render("order",array(
                'order_data' => $order_data,
                'hotel_name' => $hotel['name']
            ));
    }


    public function actionIndex()
    {
        /*Yii::app()->session['userid']=100;
        Yii::app()->session['code']=123123123;
        Yii::app()->session['account_id']=1;
        Yii::app()->session['staff_hotel_id']=1;*/
        /*$_SESSION['userid']=100;
        $_SESSION['code']=123123123;
        $_SESSION['account_id']=1;
        $_SESSION['staff_hotel_id']=1;*/
        /*$company = array();
        if(isset($_GET['account_id'])){
            Yii::app()->session['account_id']=$_GET['account_id'];
            $company = StaffCompany::model()->findByPk($_SESSION['account_id']);    
        };
        
        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){*///已登陆
            //echo '已登陆';
            $staff = Staff::model()->findByPk($_SESSION['userid']);
            $str =  rtrim($staff['department_list'], "]"); 
            $str =  ltrim($str, "[");
            $t = explode(",", $str);
            $user_type = 0; // 0-普通员工（能看订单、个人业绩）   1-管理层（能看订单统计、财务报告）
            foreach ($t as $key => $value) {
                if($value == 6){$user_type++;};
            };
            $hotel = StaffHotel::model()->findByPk($_SESSION['staff_hotel_id']);
            if($_GET['from'] == 'bill'){
                $order = Order::model()->findByPk($_GET['this_order']);
                
                $t1 = explode(" ",$order['order_date']);
                $t2 = explode("-",$t1[0]);
                
                $this->render("index",array(
                    'userId' => $_SESSION['userid'],
                    'first_show_year' => $t2[0],
                    'first_show_month' => $t2[1],
                    'first_show_day' => $t2[2],
                    'hotel_name' => $hotel['name'],
                    'user_type' => $user_type
                ));
            }else if($_GET['from'] == ''){
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                //print_r($y.$m.$d);
                $this->render("index",array(
                    'userId' => $_SESSION['userid'],
                    'first_show_year' => $y,
                    'first_show_month' => $m,
                    'first_show_day' => $d,
                    'hotel_name' => $hotel['name'],
                    'user_type' => $user_type
                ));
            }
        /*}else{//未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=order/index&from=&code=';
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
                
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                $hotel = StaffHotel::model()->findByPk($_SESSION['staff_hotel_id']);
                $this->render("index",array(
                    'userId' => $adder['UserId'],
                    'first_show_year' => $y,
                    'first_show_month' => $m,
                    'first_show_day' => $d,
                    'hotel_name' => $hotel['name'],
                ));
            }
        };*/
    }

    public function actionGetIndexData()
    {
        $year = $_POST['year'];
        $month = $_POST['month'];
        $account_id = $_POST['account_id'];
        $staff_hotel_id = $_POST['staff_hotel_id'];
        /*print_r($month);die;*/
        $IndexData = $this->actionIndexData($year,$month,$account_id,$staff_hotel_id);

        echo $IndexData;
    }

    public function actionIndexData($year,$month,$accountId,$staff_hotel_id)
    {

        
        /*$data = date('y-m-d',time());
        $data = explode("-",$data);
        print_r($data);die;

        $year = "20".$data[0];
        $year = (int)$year;
        $month = (int)$data[1];*/

        /*$year = $_POST['year'];
        $month = $_POST['month'];
        $accountId = $_POST['account_id'];*/

        $maybe_data = "";

        $order = Order::model()->findAll(array(
            "condition" => "account_id=:account_id && staff_hotel_id=:staff_hotel_id",
            "params" => array(
                ":account_id" => $accountId,
                ":staff_hotel_id" =>$staff_hotel_id
            ),
        ));

        
        $orderData = array();

        
        foreach ($order as $key => $value) {

            $arr = explode(" ",$value['order_date']);
            $data = explode("-",$arr[0]); 

            $item = array(
                'order_id' => $value['id'] ,
                'account_id' => $value['account_id'],
                'planner_id' => $value['planner_id'],
                'designer_id' => $value['designer_id'],
                'staff_hotel_id' => $value['staff_hotel_id'],
                'order_name' => $value['order_name'],
                'order_type' => $value['order_type'],
                'order_status' => $value['order_status'],
                'update_time' => $value['update_time'],
                'order_time' => $value['order_time'],
                'order_year' => (int)$data[0],
                'order_month' => (int)$data[1],
                'order_data' => (int)$data[2] 
            );
            
            $orderData[] = $item;

           /* var_dump($orderData[0]);die;*/
        }

        $maybe_data = "";
        $half_data = "";
        $data = "";

        /*$alldate = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);*/

        foreach ($orderData as $key => $value) {
            
            if ($value['order_year'] == $year && $value['order_month'] == $month && $value['order_status'] == 1 ) {
                $maybe_data .= $value['order_data']."," ;    
            };
            /*if ($value['order_year'] == $year && $value['order_month'] == $month && $value['order_status'] == 0 ) {
                $maybe_data .= $value['order_data']."," ;    
            };*/
            if ($value['order_year'] == $year && $value['order_month'] == $month && $value['order_status'] != 1 && $value['order_status'] != 0 ) {
                  $maybe_data .= $value['order_data']."," ;
            }
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
        return $arr_order;
        /*var_dump($maybe_data);die; */
    }

    public function actionMy()
    {
        /*Yii::app()->session['userid']=100;
        Yii::app()->session['code']='asjfdlk123';
        Yii::app()->session['account_id']=1;
        Yii::app()->session['staff_hotel_id']=1;*/
        $company = array();
        if(isset($_GET['account_id'])){
            Yii::app()->session['account_id']=$_GET['account_id'];
            $company = StaffCompany::model()->findByPk($_SESSION['account_id']);    
        };

        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){//已登陆
            //echo '已登陆';
            /*echo $_SESSION['userid'];die;*/
            $arr_order = array();
            $staff = Staff::model()->findByPk($_SESSION['userid']);
            $newstr = rtrim($staff['department_list'], "]");
            $newstr = ltrim($newstr, "[");
            $arr_type = explode(",",$newstr);
            $t = 0;
            foreach ($arr_type as $key => $value) {
                if($value == 6){
                    $t++;
                }
            };
            if($t != 0){//访问者为管理层
                $criteria = new CDbCriteria; 
                $criteria->addInCondition('order_status', array(1,2,3,4,5,6)); 
                $criteria->addCondition("account_id = :account_id");    
                $criteria->params[':account_id']=$_SESSION['account_id'];
                $criteria->order = 'order_date DESC' ;
                $arr_order = Order::model()->findAll($criteria);
            }else{
                $arr_order = Order::model()->findAll(array(
                    "condition" => "adder_id=:adder_id || planner_id=:planner_id && account_id = :account_id",
                    "params" => array(
                        ":adder_id" => $_SESSION['userid'],
                        ":planner_id" => $_SESSION['userid'],
                        ":account_id" => $_SESSION['account_id'],
                    ),
                    'order'=>'order_date  DESC', 
                ));
            };
            
            //print_r($arr_order);die;
            if(!empty($arr_order)){
                $this->render("my",array(
                    //'userId' => $_SESSION['userid'],
                    "arr_order" => $arr_order,
                ));
            }else{
                $this->render("my_empty");
            }
        }else{ //未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=order/my&t=plan&code=';
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

                
                $arr_order = array();
                $staff = Staff::model()->findByPk($_SESSION['userid']);
                $newstr = rtrim($staff['department_list'], "]");
                $newstr = ltrim($newstr, "[");
                $arr_type = explode(",",$newstr);
                $t = 0;
                foreach ($arr_type as $key => $value) {
                    if($value == 6){
                        $t++;
                    }
                };
                if($t != 0){//访问者为管理层
                    $criteria = new CDbCriteria; 
                    $criteria->addInCondition('order_status', array(1,2,3,4,5,6)); 
                    $criteria->addCondition("account_id = :account_id");    
                    $criteria->params[':account_id']=$_SESSION['account_id'];
                    $criteria->order = 'order_date DESC' ;
                    $arr_order = Order::model()->findAll($criteria);
                }else{
                    $arr_order = Order::model()->findAll(array(
                        "condition" => "adder_id=:adder_id || planner_id=:planner_id && account_id = :account_id",
                        "params" => array(
                            ":adder_id" => $_SESSION['userid'],
                            ":planner_id" => $_SESSION['userid'],
                            ":account_id" => $_SESSION['account_id'],
                        ),
                        'order'=>'order_date DESC', 
                    ));
                };
                /*echo $adder['UserId'];die;*/
                if(!empty($arr_order)){
                    $this->render("my",array(
                        'userId' => $_SESSION['userid'],
                        "arr_order" => $arr_order,
                    ));
                }else{
                    $this->render("my_empty");
                }
            }
        };
    }

    public function actionSave()
    {
        $OrderForm = new OrderForm();
        $post['account_id'] = $this->getAccountId();
        $post['order_date'] = $_POST['order_date'];
        $post['order_time'] = $_POST['order_time'];
        $post['order_type'] = $_POST['order_type'];
        $post['planner_id'] = $_POST['planner_id'];
        $post['expect_table'] = $_POST['expect_table'];
        $post['staff_hotel_id'] = $_POST['staff_hotel_id'];
        $post['designer_id'] = '0';
        if($post['order_type'] == 2){
            $post['order_name'] = '婚礼';
        }else{
            $post['order_name'] = '会议';
        }
        // $post['order_name'] = $_GET['order_name'];
        // $post['order_status'] = $_POST['order_status'];
        $post['order_status'] = '0';
        // $post['expect_table'] = $_POST['expect_table'];
        $post['update_time']      = date('Y-m-d');
        // $post['order_time'] = $_POST['order_time'];
        // var_dump($post);
        $arr = $OrderForm->orderInsert($post);

        echo json_encode($arr);
    }

    public function actionSaveMeeting()
    {
        // var_dump($_GET);die;
        $OrderMeetingForm = new OrderMeetingForm();
        $post['account_id'] = $this->getAccountId();
        $post['company_id'] = $_GET['company_id'];
        $post['order_id'] = $_GET['order_id'];
        $post['company_linkman_id'] = $_GET['linkman_id'];
        $post['layout_id'] = '0';
        $post['update_time']      = date('Y-m-d');
 
        $arr = $OrderMeetingForm->orderInsert($post);

        echo json_encode($arr);
    }

    public function actionTransition()
    {
        $arr_staff=array();
        $result = yii::app()->db->createCommand("SELECT * FROM staff WHERE account_id = 1 && (department_list LIKE '[2,%' || department_list LIKE '%,2,%' || department_list LIKE '%,2]')");
        $staff = $result->queryAll();
        // print_r($staff);
        $item = array();
        foreach ($staff as $value) {
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['avatar'] = $value['avatar'];
            $arr_staff[] = $item;
        };
        $this->render("transition",array(
            "arr_staff" => $arr_staff ,
        ));
    }

    public function actionSelectType()
    {
        /*Yii::app()->session['userid']=100;
        Yii::app()->session['code']='asjfdlk123';
        Yii::app()->session['account_id']=1;
        Yii::app()->session['staff_hotel_id']=1;*/

        /*if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){*///已登陆
            //echo '已登陆';
            $this->render("selectType");
        /*}else{//未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=order/selectType';
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxee0a719fd467c364&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect&from=&this_order=";
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
                $this->render("selectType");
            }
        };*/
    }

    public function actionChoosediscount()
    {
        $order = Order:: model()->findByPk($_GET['order_id']);
        $this->render("choosediscount",array(
            'feast_discount' => $order['feast_discount'],
            'other_discount' => $order['other_discount'],
            'discount_range' => $order['discount_range'],
        ));
    }

    public function actionDiscount()
    {
        $discount_range = $_POST['discount_range'];
        if($discount_range == ""){
            Order::model()->updateByPk( $_POST['order_id'] ,array('feast_discount'=>$_POST['discount']));
        }else{
            Order::model()->updateByPk( $_POST['order_id'] ,array('other_discount'=>$_POST['discount'],'discount_range'=>$_POST['discount_range']));
        }
    }

    public function actionChangeFree()
    {
        $orderdata = Order::model()->findByPk( $_GET['order_id'] );
        $this->render('changefree',array(
            'orderdata' => $orderdata,
        ));
    }

    public function actionUpdateChangeFree()
    {
        Order::model()->updateByPk( $_POST['order_id'] ,array('cut_price'=>$_POST['changefree']));
    }

    public function actionFeast()
    {
        $this->render("feast");
    }

    public function actionSelectFeast()
    {
        $this->render("selectFeast");
    }

    public function actionChangeOrderStatus()
    {
        Order::model()->updateByPk( $_POST['order_id'] ,array('order_status'=>$_POST['order_status'])); 
    }

    public function actionDelOrder()
    {
        $order = Order::model()->deleteByPk($_POST['order_id']);
        $t= OrderProduct::model()->find(array(
            'condition' => 'order_id=:order_id',
            'params' => array(
                ':order_id' => $_POST['order_id'],
            ),
        ));
        $product = 0;
        if(!empty($t)){
            $product = OrderProduct::model()->deleteAll('order_id=:order_id',array(':order_id'=>$_POST['order_id']));
        }else {
            $product = 1;
        }
          

        if($order>0 && $product>0){ 
            echo "success"; 
        }else{ 
            echo "fail"; 
        } 
    }

    public function actionFindMonthOrder()
    {
        /*die;*/
        $criteria = new CDbCriteria;        
        $criteria->addCondition("account_id = :account_id && staff_hotel_id = :staff_hotel_id");    
        $criteria->params[':account_id']=$_POST['account_id']; 
        $criteria->params[':staff_hotel_id']=$_POST['staff_hotel_id'];   
        $criteria->addInCondition('order_status', array(1,2,3,4,5,6)); 
        $order_hotel = Order::model()->findAll($criteria);  

        $arr_order_all = array();
        foreach ($order_hotel as $key => $value) {
            $item =array();
            $item['order_id'] = $value['id'];
            $item['order_type'] = $value['order_type'];
            $item['order_name'] = $value['order_name'];
            $item['order_status'] = $value['order_status'];
            $t1=Staff::model()->findByPk($value['planner_id']);
            $item['planner_name'] = $t1['name'];
            $t2=Staff::model()->findByPk($value['designer_id']);
            $item['designer_name'] = $t2['name'];

            /*if($value['planner_id'] != 0){
                $planner = Staff::model()->find(array(
                    "condition" => "id=:id",
                    "params" => array(
                        ":id" => $value['planner_id']
                    )
                ));
                $item['planner_name'] = $planner['name'];
            }else{$item['planner_name'] = "无统筹师";};
            if($value['designer_id'] != 0){
                $designer = Staff::model()->find(array(
                    "condition" => "id=:id",
                    "params" => array(
                        ":id" => $value['designer_id']
                    )
                ));
                $item['designer_id'] = $designer['name'];
            }else{$item['designer_id'] = "无策划师";};*/

            $temp = explode(" ",$value['order_date']);
            $temp1 = explode("-",$temp[0]);
            
            $item['order_day'] = (int)$temp1[2];
            $item['order_month'] = (int)$temp1[1];
            $item['order_year'] = $temp1[0];

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

/*    public function actionPrintbill()
    {
        

        function gen_static_file($program, $filename)
        {
            $program1 = $program;
            $filename1 = "/alidata/www/crm_web/" . $filename;
            $cmd_str = $program1 . " } " . $filename1 . " ";
            system($cmd_str);
            echo $filename . " generated.〈br〉";
        }

        $filename = " temp_billtable_design.html";
        gen_static_file('<?php echo $this->createUrl("design/billtable", array("order_id" => $_GET["order_id"]));?>', $filename);
    }

    public function actionPrintbill2()
    {
        $title = "拓迈国际测试模板"; 
        $file = "TwoMax Inter test templet, author：Matrix@Two_Max"; 
        $url = "/alidata/www/crm_web/portal/protected/views/design/bill.php";
        $fp = fopen ($url,"r"); 
        $content = fread ($fp,filesize ("/alidata/www/crm_web/portal/protected/views/design/bill.php")); 
        $content .= str_replace ("{ file }",$file,$content); 
        $content .= str_replace ("{ title }",$title,$content); 
        // echo $content; 
        $filename = "/alidata/www/test.html"; 
        $handle = fopen ($filename,"w"); //打开文件指针，创建文件 
        /* 
        　检查文件是否被创建且可写 
        */ 
        /*if (!is_writable ($filename)){ 
        die ("文件：".$filename."不可写，请检查其属性后重试！"); 
        } 
        if (!fwrite ($handle,$content)){ //将信息写入文件 
        die ("生成文件".$filename."失败！"); 
        } 
        fclose ($handle); //关闭指针 
        die ("创建文件".$filename."成功！"); 
    }*/

    public function actionPrintbill3()
    {
        $out1 = "<html><head><title>PHP网站静态化教程</title></head>
        <body>欢迎访问PHP网站开发教程网www.leapsoul.cn，本文主要介绍PHP网站页面静态化的方法
        </body></html>";
        $fp = fopen("leapsoulcn.html","w");
        if(!$fp)
        {
        echo "System Error";
        exit();
        }
        else {
        fwrite($fp,$out1);
        fclose($fp);
        echo "Success";
        }
    }

    public function actionOrderinfo()
    {
        /*Yii::app()->session['userid']=100;
        Yii::app()->session['code']='asjfdlk123';
        Yii::app()->session['account_id']=1;
        Yii::app()->session['staff_hotel_id']=1;*/
        /*if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){*///已登陆
            //echo '已登陆';
            $order = Order::model()->findByPk($_GET['order_id']);
            $staff = Staff::model()->findByPk($order['designer_id']);
            $staff_user = Staff::model()->findByPk($_SESSION['userid']);
            $t = new OrderProductForm();
            $order_total = $t -> total_price($_GET['order_id']);
            /*print_r($order_total);die;*/
            $order_data = array();
            $order_data['order_name'] = $order['order_name'];
            $order_data['order_date'] = $order['order_date'];
            $order_data['order_status'] = $order['order_status'];
            $order_data['designer_name'] = $staff['name'];
            $order_data['user_department_list'] = $staff_user['department_list'];
            $follow = OrderMerchandiser::model()->findAll(array(
                    'condition' => 'order_id=:order_id',
                    'params' => array(
                            ':order_id' => $_GET['order_id']
                        )
                ));
            /*print_r($follow);die;*/
            $in_door = 0;
            $out_door = 0;
            foreach ($follow as $key => $value) {
                if($value['type'] == '0'){$in_door++;}else{$out_door++;};
            }
            $payment = OrderPayment::model()->findAll(array(
                    'condition' => 'order_id=:order_id',
                    'params' => array(
                            ':order_id' => $_GET['order_id']
                        )
                ));
            $total_payment = 0;
            foreach ($payment as $key => $value) {
                $total_payment += $value['money'];
            };
            $payment_rate = 0;
            if($order_total['total_price'] != 0){
                $payment_rate = $total_payment/$order_total['total_price'];    
            }
            
            $this->render('order_info',array(
                    'order_data' => $order_data,
                    'order_total' => $order_total,
                    'in_door' => $in_door,
                    'out_door' => $out_door,
                    'total_payment' => $total_payment,
                    'payment_rate' => $payment_rate,
                ));
        /*}else{ //未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = "http://www.cike360.com/school/crm_web/portal/index.php?r=order/orderinfo&code=&order_id=".$_GET['order_id'];
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxee0a719fd467c364&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect&from=&this_order=";
                echo "<script>window.location='".$url."';</script>";
            };

            $t=new WPRequest;
            $userId = $t->getUserId($code);
            $adder=array("UserId"=>"222","DeviceId"=>"");
            $adder=json_decode($userId,true);
            if(!empty($adder['UserId'])) {
                Yii::app()->session['userid']=$adder['UserId'];
                $staff = Staff::model()->findByPk($adder['UserId']);
                Yii::app()->session['account_id']=$staff['account_id'];
                Yii::app()->session['staff_hotel_id']=$staff['hotel_list'];
                
                $order = Order::model()->findByPk($_GET['order_id']);
                $staff = Staff::model()->findByPk($order['designer_id']);
                $staff_user = Staff::model()->findByPk($_SESSION['userid']);
                $t = new OrderProductForm();
                $order_total = $t -> total_price($_GET['order_id']);*/
                /*print_r($order_total);die;*/
                /*$order_data = array();
                $order_data['order_name'] = $order['order_name'];
                $order_data['order_date'] = $order['order_date'];
                $order_data['order_status'] = $order['order_status'];
                $order_data['designer_name'] = $staff['name'];
                $order_data['user_department_list'] = $staff_user['department_list'];
                $follow = OrderMerchandiser::model()->findAll(array(
                        'condition' => 'order_id=:order_id',
                        'params' => array(
                                ':order_id' => $_GET['order_id']
                            )
                    ));*/
                /*print_r($follow);die;*/
                /*$in_door = 0;
                $out_door = 0;
                foreach ($follow as $key => $value) {
                    if($value['type'] == '0'){$in_door++;}else{$out_door++;};
                }*/
                /*$payment = OrderPayment::model()->findAll(array(
                        'condition' => 'order_id=:order_id',
                        'params' => array(
                                ':order_id' => $_GET['order_id']
                            )
                    ));
                $total_payment = 0;
                foreach ($payment as $key => $value) {
                    $total_payment += $value['money'];
                };
                $payment_rate = $total_payment/$order_total['total_price'];*/

                /*$this->render('order_info',array(
                        'order_data' => $order_data,
                        'order_total' => $order_total,
                        'in_door' => $in_door,
                        'out_door' => $out_door,*/
                        /*'total_payment' => $total_payment,
                        'payment_rate' => $payment_rate,*/
                    /*));
            }
        };*/
        
    }

    public function actionOrderinfofollow()
    {
        $follow = OrderMerchandiser::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    ),
                'order'=>'time'
            ));
        $order = Order::model()->findByPk($_GET['order_id']);
        /*print_r($follow);die;*/

        $this->render('order_info_follow',array(
                'follow' => $follow,
                'order' => $order
            ));
    }

    public function actionOrderinfofollowdetail()
    {
        
        if($_GET['type'] == 'edit'){
             $data = OrderMerchandiser::model()->findByPk($_GET['followId']);
             $this->render('order_info_follow_detail',array(
                    'data' => $data,
                )); 
        }else{
            $this->render('order_info_follow_detail');    
        }
    }

    public function actionFollowinsert()
    {
        $staff = Staff::model()->findByPk($_POST['staff_id']);
        $order = Order::model()->findByPk($_POST['order_id']);

        $follow=new OrderMerchandiser;         
        $follow->order_id=$_POST['order_id']; 
        $follow->staff_id=$_SESSION['userid'];
        $follow->time=$_POST['time'];
        $follow->remarks=$_POST['remarks'];
        $follow->type=$_POST['type'];
        $follow->staff_name=$staff['name'];
        $follow->order_name=$order['order_name'];
        $follow->order_date=$order['order_date'];
        $follow->save();

    }

    public function actionFollowupdate()
    {

        OrderMerchandiser::model()->updateByPk($_POST['followId'],array('remarks'=>$_POST['remarks'],'time'=>$_POST['time'],'type'=>$_POST['type'])); 

    }

    public function actionFollowdel()
    {

        OrderMerchandiser::model()->deleteByPk($_POST['followId']); 

    }

    public function actionCheckoutpost(){
        /*$_POST['order_id'] = 11;
        $_POST['type'] = "meeting";*/
        Order::model()->updateByPk($_POST['order_id'],array('order_status'=>5));
        $order = Order::model()->findByPk($_POST['order_id']);
        $order['order_date'] = explode(" ",$order['order_date']);
        $staff = Staff::model()->findAll(array(
                'condition' => 'account_id = :account_id ',
                'params' => array(
                        ':account_id' => $order['account_id']
                    )
                ));
        $touser_list = "";
        foreach ($staff as $key => $value) {
            $newstr = rtrim($value['department_list'], "]");
            $newstr = ltrim($value['department_list'], "[");
            $arr_type = explode(",",$newstr);
            foreach ($arr_type as $key1 => $value1) {
                if($value1 == 5){
                    $touser_list .= $value['id']."|";
                }
            }
        }
        $touser_list = substr($touser_list,0,strlen($touser_list)-1); 
        
        

        $touser= $touser_list;
        print_r($touser);die;
        $content= "中文怎么解决";
        $title= '结算申请:'.$order['order_name'].'|'.$order['order_date'][0];
        $agentid=0;
        if($_POST['type'] == "meeting"){
            $url="http://www.cike360.com/school/crm_web/portal/index.php?r=meeting/bill&code=&order_id=".$_POST['order_id'];    
        }else if($_POST['type'] == "design"){
            $url="http://www.cike360.com/school/crm_web/portal/index.php?r=design/bill&code=&order_id=".$_POST['order_id'];
        }
        
        $thumb_media_id="1VIziIEzGn_YvRxXK3OxPQpylPHLUnnA2gJ5_v8Cus2la7sjhAWYgzyFZhIVI9UoS6lkQ-ZLuMPZgP8BOVIS-XQ";
        $media_id="2n8jAkMtWj42qcBGih5M_hq0teff_17YKATQXYyLlLyAEN6Z_5mOgSyBUcKz7ebu9";
        $description="结算申请";
        $picur="http://www.cike360.com/school/crm_web/image/thumb.jpg";
        // $t=new ReportController;

        // $content = $t->actionDayreport();
        // print_r($content);die;
        //$content=$html;
        // $content=ReportController::actionDayreport();
        $digest="描述";
        //$media="C:\Users\Light\Desktop\life\65298b36.jpg";
        // $media="@/var/www/html/school/crm_web/image/thumb.jpg";
        // $type="image";

        echo "</br>";
        $author="";
        $content_source_url="http://www.cike360.com/school/crm_web/portal/index.php?r=report/dayreporthtml";
        $show_cover_pic="";
        $safe="";
        $toparty="";
        $totag="";
        //$result1=WPRequest::updateMpnews_Data($media_id,$title,$thumb_media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
        /*$result2=WPrequest::sendMessage_Mpnews(
                $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);*/
        // $result=WPRequest::addmpnews( $title,$media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
        print_r(WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid));
        //$result2=WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid);
        //$result=WPRequest::mediaupload($media,$type);
        /*echo "result1:";
        print_r($result1);*/
        /*echo "result2:";
        print_r($result2);*/
    }

    public function actionCheckoutagree()
    {
        $order = Order::model()->findByPk($_POST['order_id']);
        $final= new OrderFinal;
        $final->order_id = $_POST['order_id'];
        $final->account_id = $order['account_id'];
        $final->staff_hotel_id = $order['staff_hotel_id'];
        $final->final_price = $_POST['final_price'];
        $final->final_payment = $_POST['final_payment'];
        $final->final_cost = $_POST['final_cost'];
        $final->final_profit = $_POST['final_profit'];
        $final->final_profit_rate = $_POST['final_profit_rate'];
        $final->feast_profit = $_POST['feast_profit'];
        $final->other_profit = $_POST['other_profit'];
        $final->save(); 

        Order::model()->updateByPk($_POST['order_id'],array('order_status'=>6));

        $touser_list = $order['planner_id']."|".$order['designer_id'];
        $touser= $touser_list;
        print_r($touser);die;
        $content= "中文怎么解决";
        $title= '结算已通过:'.$order['order_name'].'|'.$order['order_date'][0];
        $agentid=0;
        if($_POST['type'] == "meeting"){
            $url="http://www.cike360.com/school/crm_web/portal/index.php?r=meeting/bill&code=&order_id=".$_POST['order_id'];    
        }else if($_POST['type'] == "design"){
            $url="http://www.cike360.com/school/crm_web/portal/index.php?r=design/bill&code=&order_id=".$_POST['order_id'];
        }
        
        $thumb_media_id="1VIziIEzGn_YvRxXK3OxPQpylPHLUnnA2gJ5_v8Cus2la7sjhAWYgzyFZhIVI9UoS6lkQ-ZLuMPZgP8BOVIS-XQ";
        $media_id="2n8jAkMtWj42qcBGih5M_hq0teff_17YKATQXYyLlLyAEN6Z_5mOgSyBUcKz7ebu9";
        $description="结算申请";
        $picur="http://www.cike360.com/school/crm_web/image/thumb.jpg";
        // $t=new ReportController;

        // $content = $t->actionDayreport();
        // print_r($content);die;
        //$content=$html;
        // $content=ReportController::actionDayreport();
        $digest="描述";
        //$media="C:\Users\Light\Desktop\life\65298b36.jpg";
        // $media="@/var/www/html/school/crm_web/image/thumb.jpg";
        // $type="image";

        $author="";
        $content_source_url="http://www.cike360.com/school/crm_web/portal/index.php?r=report/dayreporthtml";
        $show_cover_pic="";
        $safe="";
        $toparty="";
        $totag="";
        //$result1=WPRequest::updateMpnews_Data($media_id,$title,$thumb_media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
        /*$result2=WPrequest::sendMessage_Mpnews(
                $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);*/
        // $result=WPRequest::addmpnews( $title,$media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
        print_r(WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid));
        //$result2=WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid);
        //$result=WPRequest::mediaupload($media,$type);
        /*echo "result1:";
        print_r($result1);*/
        /*echo "result2:";
        print_r($result2);*/
    }

    public function actionCheckoutrefuse()
    {
        Order::model()->updateByPk($_POST['order_id'],array('order_status'=>4));
        $order = Order::model()->findByPk($_POST['order_id']);

        $touser_list = $order['planner_id']."|".$order['designer_id'];
        $touser= $touser_list;
        print_r($touser);die;
        $content= "中文怎么解决";
        $title= '结算被拒绝:'.$order['order_name'].'|'.$order['order_date'][0];
        $agentid=0;
        if($_POST['type'] == "meeting"){
            $url="http://www.cike360.com/school/crm_web/portal/index.php?r=meeting/bill&code=&order_id=".$_POST['order_id'];    
        }else if($_POST['type'] == "design"){
            $url="http://www.cike360.com/school/crm_web/portal/index.php?r=design/bill&code=&order_id=".$_POST['order_id'];
        }
        
        $thumb_media_id="1VIziIEzGn_YvRxXK3OxPQpylPHLUnnA2gJ5_v8Cus2la7sjhAWYgzyFZhIVI9UoS6lkQ-ZLuMPZgP8BOVIS-XQ";
        $media_id="2n8jAkMtWj42qcBGih5M_hq0teff_17YKATQXYyLlLyAEN6Z_5mOgSyBUcKz7ebu9";
        $description="结算申请";
        $picur="http://www.cike360.com/school/crm_web/image/thumb.jpg";
        // $t=new ReportController;

        // $content = $t->actionDayreport();
        // print_r($content);die;
        //$content=$html;
        // $content=ReportController::actionDayreport();
        $digest="描述";
        //$media="C:\Users\Light\Desktop\life\65298b36.jpg";
        // $media="@/var/www/html/school/crm_web/image/thumb.jpg";
        // $type="image";

        echo "</br>";
        $author="";
        $content_source_url="http://www.cike360.com/school/crm_web/portal/index.php?r=report/dayreporthtml";
        $show_cover_pic="";
        $safe="";
        $toparty="";
        $totag="";
        //$result1=WPRequest::updateMpnews_Data($media_id,$title,$thumb_media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
        /*$result2=WPrequest::sendMessage_Mpnews(
                $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);*/
        // $result=WPRequest::addmpnews( $title,$media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
        print_r(WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid));
        //$result2=WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid);
        //$result=WPRequest::mediaupload($media,$type);
        /*echo "result1:";
        print_r($result1);*/
        /*echo "result2:";
        print_r($result2);*/
    }

    public function actionOrderprint()
    {
        $this->render('orderprint');
    }

    public function actionOrdertransition()
    {
        $user = Staff::model()->findByPk($_SESSION['userid']);
        $target = Staff::model()->findByPk($_POST['staff_id']);
        $order = Order::model()->findByPk($_POST['order_id']);
        $date = explode(" ",$order['order_date']);
        $content = $user['name']."将订单".$order['order_name']."[".$date[0]."]转移给".$target['name'];
        if($_POST['type'] == 'designer'){
            Order::model()->updateByPk($_POST['order_id'],array('designer_id' => $_POST['staff_id']));           
        }else if($_POST['type'] == 'planner'){
            Order::model()->updateByPk($_POST['order_id'],array('planner_id' => $_POST['staff_id']));
        };
        $touser="@all";//你要发的人
        $toparty="";
        $company = StaffCompany::model()->findByPk($_SESSION['account_id']);  
        $corpid=$company['corpid'];
        $corpsecret=$company['corpsecret'];
        $result=WPRequest::sendMessage_Text($touser, $toparty, $content,$corpid,$corpsecret);
        //纷享接口
        $hotel = StaffHotel::model()->findByPk($order['staff_hotel_id']);
        $appId = $hotel['fxiaoke_AppID'];
        $appSecret = $hotel['fxiaoke_APPSecret'];
        $permanentCode = $hotel['permanentCode'];
        $content2 = array(
            "content"   => $content,
            );
        if ($order['staff_hotel_id'] == 1 || $order['staff_hotel_id'] == 2) {
            $result = WPRequest::fxiaokesendMessage($appId,$appSecret,$permanentCode,$content2);
        } else if ($order['staff_hotel_id'] == 4) {
            $openUserId = WPRequest::idlist();
            $result = WPRequest::fxiaokedisendMessage($appId,$appSecret,$permanentCode,$content2,$openUserId);
        } 
    }

    public function actionOrdercost()
    {
        $this->render('ordercost');
    }

    public function actionSavecost()
    {
        // $_POST['order_id']=542;
        // $_POST['type']='meeting_feast';
        // $_POST['money']=9900.00;
        $result = array();
        if($_POST['type'] == 'wedding_feast' || $_POST['type'] == 'meeting_feast'){
            $result = yii::app()->db->createCommand("select o.id,o.actual_price,o.unit,o.actual_unit_cost,s.supplier_type_id from order_product o left join supplier_product s on o.product_id=s.id where o.order_id=".$_POST['order_id']." and s.supplier_type_id=2 ");
            $result = $result->queryAll();
        }else if($_POST['type'] == 'wedding' || $_POST['type'] == 'meeting'){
            $result = yii::app()->db->createCommand("select o.id,o.actual_price,o.unit,o.actual_unit_cost,s.supplier_type_id from order_product o left join supplier_product s on o.product_id=s.id where o.order_id=".$_POST['order_id']." and s.supplier_type_id<>2 ");
            $result = $result->queryAll();
        };
        // print_r($result);die;

        $total_cost = 0;
        foreach ($result as $key => $value) {
            $total_cost += $value['actual_unit_cost']*$value['unit'];
        };

        // $order = yii::app()->db->createCommand("select s1.name as designer_name,s2.name as planner_name from `order` left join staff s1 on designer_id=s1.id left join staff s2 on planner_id=s2.id where `order`.id=".$_POST['order_id']);
        // $order = $order->queryAll();//取策划师／婚宴销售姓名

        if($total_cost == 0 && empty($result)){
            echo 000;
            // echo "策划师还没录入商品，请您联系本单策划师/婚宴销售：".$order[0]['designer_name']."/".$order[0]['planner_name'];
        }else if(!empty($result) && $total_cost == 0){
            echo 1;
            foreach ($result as $key => $value) {
                if($_POST['type'] == 'wedding_feast' || $_POST['type'] == 'meeting_feast'){
                    if($value['supplier_type_id'] == 2){
                        echo $value['id'];
                        print_r(OrderProduct::model()->updateByPk($value['id'],array('actual_unit_cost'=>$_POST['money']/$value['unit'])));
                    };
                    break;
                }else if($_POST['type'] == 'wedding' || $_POST['type'] == 'meeting'){
                    if($value['supplier_type_id'] != 2){
                        //print_r($value['actual_unit_cost']);
                        OrderProduct::model()->updateByPk($value['id'],array('actual_unit_cost'=>$_POST['money']/$value['unit']));
                    };
                    break;
                };
            };
        }else if($total_cost != 0 && !empty($result)){
            $temp = $_POST['money']/$total_cost;
            // echo $value['id']."|".$temp."/";
            print_r($result);
            foreach ($result as $key => $value) {
                // echo $value['actual_unit_cost'].",".$value['actual_unit_cost']*$temp."|";
                OrderProduct::model()->updateByPk($value['id'],array('actual_unit_cost'=>$value['actual_unit_cost']*$temp));
            };
        }
    }

    public function actionBooking(){
        $post = json_decode(file_get_contents('php://input'));
        // $post=array('order_date' => '2016-06-19 19:30');
        // print_r($post['order_date']);die;
        $date = explode(' ', $post->order_date);
        // $date = explode(' ', $post['order_date']);
        $order = yii::app()->db->createCommand("select order_status,order_type,name ".
            " from `order` o left join staff on o.planner_id=staff.id ".
            " where order_date like '".$date[0]."' and staff_hotel_id=".$post->staff_hotel_id);
        $order = $order->queryAll();
        if(!empty($order)){
            $t = 0;
            foreach ($order as $key => $value) {
                if($value['order_status'] == 0){
                    $t =1;
                }else if($value['order_status'] == 1){
                    $t = 2;
                }else if($value['order_status'] != 0 && $value['order_status'] != 1){
                    $t =3;
                };
            };
            if($t == 1){
                if($order['order_type'] == 1){
                    $json = array(
                        'data' => '有咨询，会议，销售：'.$order['name'],
                    );
                    echo json_encode($json);
                }else{
                    $json = array(
                        'data' => '有咨询，婚礼，销售：'.$order['name'],
                    );
                    echo json_encode($json);
                };
            }else if($t == 2){
                if($order['order_type'] == 1){
                    $json = array(
                        'data' => '有预定，会议，销售：'.$order['name'],
                    );
                    echo json_encode($json);
                }else{
                    echo '有预定，婚礼，销售：'.$order['name'];
                };
            }else if($t == 3){
                if($order['order_type'] == 1){
                    $json = array(
                        'data' => '已定，会议，销售：'.$order['name'],
                    );
                    echo json_encode($json);
                }else{
                    $json = array(
                        'data' => '已定，婚礼，销售：'.$order['name'],
                    );
                    echo json_encode($json);
                };
            };
        }else{
            $json = array(
                'data' => "空档期，可以预定！",
            );
            echo json_encode($json);
        };
    }

    public function sendMessage($html,$corpid,$corpsecret)
    {
        $touser="@all";//你要发的人
        $toparty="";
        $content = $html;
        $result=WPRequest::sendMessage_Text($touser, $toparty, $content,$corpid,$corpsecret);
        print_r($result);
    }

}
