<?php
include_once('../library/WPRequest.php');
class MeetingController extends InitController
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

    public function actionSelectCustomer()
    {
        /*$this->render("selectCustomer");*/
        $accountId = $_SESSION['account_id'];
        $order_id = $_GET['order_id'];
        // var_dump($order_id);
        $companyForm = new CompanyForm();
        $companys = $companyForm->getcompanyList($accountId,$order_id);
        // var_dump($companys);
        $ordermeeting = OrderMeeting::model()->find(array(
                'condition' => 'order_id=:order_id && account_id=:account_id',
                'params' => array(
                        ':order_id' => $_GET['order_id'],
                        ':account_id' => $_SESSION['account_id']
                    )
            ));
        $this->render("selectCustomer",array(
                "arr_old_customer" => $companys,
                "selected_company" => $ordermeeting['company_id']
            ));
    }
    public function actionInsert()
    {
        // var_dump($_POST);die;
        $companyForm = new CompanyForm();
        $post['account_id']  =  $_SESSION['account_id'];
        $post['company_name']    = $_POST['new_customer'];
        $post['update_time']      = time();
        $arr = $companyForm->companyInsert($post);
        echo json_encode($arr);
 
    }

    public function actionSelectCustomerInfo()
    {

        $this->render("selectCustomerInfo");
    }

    public function actionAddCustomer()
    {
        $this->render("addCustomer");
    }

    public function actionSelectLinkman()
    {
        // var_dump($_POST);
        // var_dump($_GET);
        /*$this->render("selectLinkman");*/
        $accountId = $this->getAccountId();

        // $order_id = $_GET['order_id'];
        // $company_id = $_GET['company_id'];
        $linkmanForm = new LinkmanForm();
        $linkmans = $linkmanForm->getlinkmanList($accountId);
        // $linkmans = $linkmanForm->getlinkmanList($accountId,$order_id,$company_id);
        // var_dump($linkmans);
        //print_r($linkmans);die;
        $this->render("selectLinkman",array(
            "arr_old_linkman" => $linkmans,
           
            ));
        // $this->render("selectLinkman");
    }

 

    public function actionInsertLinkman()
    {
        // var_dump($_POST);die;
        $LinkmanForm = new LinkmanForm();
        $post['account_id']  =  $_SESSION['account_id'];
        $post['linkman_name']    = $_POST['linkman_name'];
        $post['linkman_phone']    = $_POST['linkman_phone'];
        $post['company_id'] = $_POST['company_id'];
        $post['order_id'] = $_POST['order_id'];
        $post['update_time']      = time();
        $arr = $LinkmanForm->LinkmanInsert($post);
        echo json_encode($arr);
    }

    public function actionSelectLinkmanInfo()
    {
        $this->render("selectLinkmanInfo");
    }

    public function actionAddLinkman()
    {
        $this->render("addLinkman");
    }

    public function actionSelectLayout()
    {
        // var_dump($_GET);die;
        $accountId = $this->getAccountId();
        $company_id = $_GET['company_id'];
        $linkman_id = $_GET['linkman_id'];
        $layoutForm = new LayoutForm();
        // if(!empty($company_id && $linkman_id)){
            $layout = $layoutForm->getlayoutList($accountId,$company_id,$linkman_id);  
        // }
        
        // var_dump($linkmans);
        $this->render("selectLayout",array(
            "arr_select_layout" => $layout,
            ));
        // $this->render("selectLayout");
    }

    public function actionSelectLayoutInfo()
    {
        $this->render("selectLayoutInfo");
    }

    public function actionSelectTime()
    {
        $hotel = StaffHotel::model()->findAll(array(
            'condition' => 'account_id=:account_id',
            'params' => array(
                    ':account_id' => $_SESSION['account_id']
                )
        ));
        if($_GET['from']=='detailinfo'){
            $order = Order::model()->findByPk($_GET['order_id']);
            $t = explode(" ",$order['order_date']);
            $t1 = explode(":",$t[1]);
            $t2 = $t1[0].":".$t1[1];
            $t3 = explode(" ",$order['end_time']);
            $t4 = explode(":",$t3[1]);
            $t5 = $t4[0].":".$t4[1];
            $this->render("selectTime",array(
                    'order_date' => $t[0],
                    'order_time' => $t2,
                    'end_time' => $t5,
                    'hotel' => $hotel,
                ));    
        }else{
            $this->render("selectTime",array(
                    'order_date' => "",
                    'order_time' => "",
                    'end_time' => "",
                    'hotel' => $hotel,
                )); 
        }
        //$this->render("selectTime");
    }

    public function actionSelectTimeInfo()
    {
        $this->render("selectTimeInfo");
    }

    public function actionDetail()
    {
        $orderId = $_GET['order_id'];
        /*$orderForm = new OrderForm();*/
        $orderData = Order::model()->findAll(array(
            /*"select" => "order_name,order_data",*/
            "condition" => "id=:id",
            "params" => array( ":id" => $orderId),
       ));
        $this->render("detail",array(
            "arr" => $orderData,
        ));
    }

    public function actionDetailInfo()
    {

        $ordermeetingData = OrderMeeting::model()->find(array(
                "condition" => "order_id=:id",
                "params" => array( ":id" => $_GET['order_id']),
            ));

        
        $linkmanData = OrderMeetingCompanyLinkman::model()->find(array(
                "condition" => "id=:id",
                "params" => array( ":id" => $ordermeetingData['company_linkman_id']),
            ));
        
        $orderDate = Order::model()->find(array(
                "condition" => "id=:id",
                "params" => array( ":id" => $_GET['order_id']),
            ));


        $this->render("detailInfo",array(
                "order_name"    =>  $orderDate['order_name'],
                "company_id"    =>  $ordermeetingData['company_id'],
                "linkman_id"    =>  $ordermeetingData['company_linkman_id'],
                "linkmanname"   =>  $linkmanData['name'],
                "linkmanphone"  =>  $linkmanData['telephone'],
                "time"          =>  $orderDate['order_date']     
            ));
    }

    public function actionBill()
    {
        /*Yii::app()->session['userid']=100;
        Yii::app()->session['code']='asjfdlk123';
        Yii::app()->session['account_id']=1;
        Yii::app()->session['staff_hotel_id']=1;*/
        /*if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){//已登陆
            echo '已登陆';*/
            $this->Bill($_GET['order_id']);
        /*}else{ //未登录
            echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=meeting/bill&order_id='.$_GET['order_id'].'&code=';
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
                
                $this->Bill($_GET['order_id']);
            };
        };*/
    }

    public function Bill($orderId)
    {
        
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
                'total_cost' => $wed_feast[0]['actual_unit_cost']*$wed_feast[0]['unit'],
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
        /*print_r($supplier_id_result);die;*/
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);die;*/
        $supplier_product_id = array();
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
            /*print_r($supplier_product_id);die;*/
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
            /*print_r($changdi_fee);die;*/
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
                'total_cost' => $changdi_fee[0]['actual_unit_cost']*$changdi_fee[0]['unit'],
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
        $arr_light_total['total_price']=0;
        $arr_light_total['total_cost']=0;
        if (!empty($light)) {
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
                $arr_light_total['total_price'] += $light[$key]['actual_price']*$light[$key]['unit'];;
                $arr_light_total['total_cost'] +=$light[$key]['actual_unit_cost']*$light[$key]['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];
        }

        /*print_r($light);die;*/

        

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
        $arr_video_total['total_price']=0;
        $arr_video_total['total_cost']=0;
        if (!empty($video)) {
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
                $arr_video_total['total_price'] += $video[$key]['actual_price']*$video[$key]['unit'];;
                $arr_video_total['total_cost'] +=$video[$key]['actual_unit_cost']*$video[$key]['unit'];
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
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );
        if(!empty($arr_wed_feast)){
            $arr_total['total_price'] += $arr_wed_feast['total_price'] * $order_discount['feast_discount'] * 0.1;
            $arr_total['gross_profit'] += $arr_wed_feast['gross_profit'];
            $arr_total['total_cost'] += $arr_wed_feast['total_cost'];
        }

        if(!empty($arr_changdi_fee)){
            if($this->judge_discount(19,$orderId) == 0){
                $arr_total['total_price'] += $arr_changdi_fee['total_price'];
                $arr_total['gross_profit'] += $arr_changdi_fee['gross_profit'];
                $arr_total['total_cost'] += $arr_changdi_fee['total_cost'];
            }else{
                $arr_total['total_price'] += $arr_changdi_fee['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_changdi_fee['gross_profit'];
                $arr_total['total_cost'] += $arr_changdi_fee['total_cost'];
            }
        }

        if(!empty($arr_video)){
            if($this->judge_discount(9,$orderId) == 0){
                $arr_total['total_price'] += $arr_video_total['total_price'];
                $arr_total['gross_profit'] += $arr_video_total['gross_profit'];
                $arr_total['total_cost'] += $arr_video_total['total_cost'];
            }else{
                $arr_total['total_price'] += $arr_video_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_video_total['gross_profit'];
                $arr_total['total_cost'] += $arr_video_total['total_cost'];
            }
        }

        if(!empty($arr_light)){
            if($this->judge_discount(8,$orderId) == 0){
                $arr_total['total_price'] += $arr_light_total['total_price'];
                $arr_total['gross_profit'] += $arr_light_total['gross_profit'];
                $arr_total['total_cost'] += $arr_light_total['total_cost'];
            }else{
                $arr_total['total_price'] += $arr_light_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_light_total['gross_profit'];
                $arr_total['total_cost'] += $arr_light_total['total_cost'];
            }
        }

        if($order_discount['cut_price'] != 0){
            $arr_total['total_price'] -= $order_discount['cut_price'];
        }

        if($arr_total['total_price'] != 0){
            $arr_total['gross_profit_rate'] = $arr_total['gross_profit']/$arr_total['total_price'];    
        }
        


        // *********************************************************************************************************************
        // 查已选的推单渠道
        // *********************************************************************************************************************
        $supplier_type_id = 16 ;//supplier_type_id为16的即“推单渠道”

        $list = SupplierProduct::model()->findAll(array(
            "condition" => "supplier_type_id=:id",
            "params"    => array( ":id" => $supplier_type_id), 
                                                       )
                                                 );
        $product_id = array();
        foreach ($list as $key => $value) {
            $product_id[$key] = $value['id'];
        }

        $criteria3 = new CDbCriteria; 
        $criteria3 -> addInCondition("product_id",$product_id);
        $criteria3 -> addCondition("order_id=:id");
        $criteria3 ->params[':id']=$orderId; 
        $select = OrderProduct::model()->find($criteria3);


        $select_reference = SupplierProduct::model()->find(array(
            "condition" => "id=:id",
            "params" => array( ":id" => $select['product_id'])
                                                       )
                                                 );

        $follow = OrderMerchandiser::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    )
            ));
        /*print_r($follow);die;*/
        $in_door = 0;
        $out_door = 0;

        // *********************************************************************************************************************
        // 查回款数据
        // *********************************************************************************************************************
        $order_data = Order::model()->findByPk($orderId);
        $planner = Staff::model()->findByPk($order_data['planner_id']);
        $ordre_payment = OrderPayment::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    )
            ));
        foreach ($ordre_payment as $key => $value) {
            if($value['type'] == 0){
                $order_data['feast_deposit'] += $value['money'];
            };
            if($value['type'] == 1){
                $order_data['medium_term'] += $value['money'];
            };
            if($value['type'] == 2){
                $order_data['final_payments'] += $value['money'];
            };
        }

        $payment_total = $order_data['feast_deposit']+$order_data['medium_term']+$order_data['final_payments'];

        // *********************************************************************************************************************
        // 查访问者，所在部门
        // *********************************************************************************************************************
        $staff_user = Staff::model()->findByPk($_SESSION['userid']);
        $user_department_list= $staff_user['department_list'];




        //取回款记录
        $payment_data = array(
                'feast_deposit' => 0,
                'medium_term' => 0,
                'final_payments' => 0
            );
        $order_payment = OrderPayment::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    )
            ));
        /*print_r($order_payment);die;*/
        foreach ($order_payment as $key => $value) {
            if($value['type'] == 0){
                $payment_data['feast_deposit'] += $value['money'];
            };
            if($value['type'] == 1){
                $payment_data['medium_term'] += $value['money'];
            };
            if($value['type'] == 2){
                $payment_data['final_payments'] += $value['money'];
            };
        }

        
        /*********************************************************************************************************************/
        /*向 VIEW 传数据*/
        /*********************************************************************************************************************/
        $this->render("bill",array(
            "arr_wed_feast"     => $arr_wed_feast,
            "arr_changdi_fee"   => $arr_changdi_fee,
            "arr_video"         => $arr_video,
            "arr_video_total"   => $arr_video_total,
            "arr_light"         => $arr_light,
            "arr_light_total"   => $arr_light_total,
            "arr_total"         => $arr_total,
            "arr_order_data"    => $order_data,
            "payment_total"     => $payment_total,
            "linkman"           => $company_linkman['name'],
            "select_reference"  => $select_reference,
            "planner"           => $planner['name'],
            'in_door' => $in_door,
            'out_door' => $out_door,
            'user_department_list' => $user_department_list,
            'payment_data' => $payment_data
        ));
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

    public function actionFeast()
    {
        $accountId = $this->getAccountId();
        $supplierproductForm = new SupplierProductForm();
        $order_id = $_GET['order_id'];
        /*print_r($order_id);die;*/
        $feast_data = $this -> actionGetOrderProduct(2,$order_id);
        /*print_r($feast_data);die;*/
        $feast_total = 0 ;
        if(!empty($feast_data)){
            foreach ($feast_data as $key => $value) {
                $feast_total += $value['actual_price']*$value['unit']*(1+$value['actual_service_ratio']*0.01);
            }
        }


        $feast_bill = array(  //background data
            'total' => $feast_total,
        );      
        /*print_r($feast_data);die;*/
        $supplierproducts = $supplierproductForm->SupplierProductIndex1($accountId);
        //var_dump($supplierproducts);die;
        $this->render("feast",array(
            "arr_feast" => $supplierproducts,
            'feast_data' => $feast_data,
            'feast_bill' => $feast_bill,
        ));
    }


    public function actionchangdifei()
    {
        $accountId = $this->getAccountId();
        $supplierproductForm = new SupplierProductForm();
        $order_id = $_GET['order_id'];
        /*print_r($order_id);die;*/
        $changdifei_data = $this -> actionGetOrderProduct(19,$order_id);
        /*print_r($feast_data);die;*/
        $changdifei_total = 0 ;
        if(!empty($changdifei_data)){
            foreach ($changdifei_data as $key => $value) {
                $changdifei_total += $value['actual_price']*$value['unit'];
            }
        }


        $changdifei_bill = array(  //background data
            'total' => $changdifei_total,
        );      
        /*print_r($feast_data);die;*/
        $supplierproducts = $supplierproductForm->SupplierProductIndex2($accountId);
        //var_dump($supplierproducts);die;
        $this->render("changdifei",array(
            "arr_changdifei" => $supplierproducts,
            'changdifei_data' => $changdifei_data,
            'changdifei_bill' => $changdifei_bill,
        ));
    }


    public function actionlightingScreen()
    {
        $accountId = $this->getAccountId();
        $SupplierProductForm = new SupplierProductForm();
        $supplierProducts = $SupplierProductForm->getSupplierProductLightM($accountId);
        $supplierProducts1 = $SupplierProductForm->getSupplierProductLightM1($accountId);

        /*********************************************************************************************************************/
        /*取order_product  灯光*/
        /*********************************************************************************************************************/

        $order_id = $_GET['order_id'];
        $lighting_data = $this -> actionGetOrderProduct(8,$order_id);
        $lighting_total = 0 ;
        if(!empty($lighting_data)){
            foreach ($lighting_data as $key => $value) {
                $lighting_total += $value['actual_price']*$value['unit'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  视频*/
        /*********************************************************************************************************************/

        $screen_data = $this -> actionGetOrderProduct(9,$order_id);
        $screen_total = 0 ;
        if(!empty($screen_data)){
            foreach ($screen_data as $key => $value) {
                $screen_total += $value['actual_price']*$value['unit'];
            }
        }

        $lightingscreen_total = $lighting_total + $screen_total ;
        
        $lightingscreen_bill = array(  //background data
            'total' => $lightingscreen_total,
            'desc' => array(
                '灯光' => $lighting_total,
                '视频' => $screen_total,
            )

        );
         
        $this->render("lightingScreen",
            array(
                'arr_category_light' => $supplierProducts,
                'arr_category_screen' => $supplierProducts1,
                'lighting_data' => $lighting_data,
                'screen_data' => $screen_data,
                'light_bill' => $lightingscreen_bill,
            ));
        // $this->render("lightingScreen");
    }

    public function actionTpDetail(){
        $productData = SupplierProduct::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $_GET['product_id']),
        ));

        $orderproduct = OrderProduct::model()->find(array(
            "condition" => "order_id = :order_id && product_id = :product_id",
            "params" => array(":order_id" => $_GET['order_id'],':product_id' => $_GET['product_id']),
        ));

        /*print_r($_SESSION);die;*/

        $this->render("tpDetail",array(
            'productData' => $productData ,
            'orderproduct' => $orderproduct 
        ));
    }


     public function actionGetOrderProduct($supplier_type_id,$order_id)
    {
        $OrderHost = SupplierProduct::model()->findAll(array(
            "select" => "id",
            "condition" => "supplier_type_id=:supplier_type_id && category=:category",
            "params" => array( ":supplier_type_id" => $supplier_type_id ,":category" => 1),

        ));

        $supplier_id = array();
        foreach ($OrderHost as $key => $value) {
            $supplier_id[] = $value['id'];
        }
        /*print_r($host_id);die;*/
        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("product_id",$supplier_id);
        $criteria1->addCondition("order_id=:order_id");
        $criteria1->params[':order_id']=$order_id; 
        $supplier_data = OrderProduct::model()->findAll($criteria1);
        // var_dump($supplier_data);die;
        return $supplier_data ;


    }

    public function actionMeetinginsert()
    {
        $payment= new Order;  

        $payment->account_id =$_SESSION['account_id'];
        $payment->designer_id =$_SESSION['userid'];
        $payment->planner_id =$_SESSION['userid'];
        $payment->adder_id =$_SESSION['userid'];
        $payment->staff_hotel_id =$_POST['hotel_id'];
        $payment->order_name =$_POST['order_name'];
        $payment->order_type =$_POST['order_type'];
        $payment->order_date =$_POST['order_date'];
        $payment->order_time =$_POST['order_time'];
        $payment->end_time =$_POST['end_time'];
        $payment->order_status =1;
        $payment->update_time =$_POST['update_time'];
        $payment->save();

        $order = Order::model()->find(array(
            'condition' => 'order_date=:order_date && order_time=:order_time && update_time=:update_time',
            'params' => array(
                ':order_date' =>$_POST['order_date'],
                ':order_time' =>$_POST['order_time'],
                ':update_time' =>$_POST['update_time'],
            )
        ));

        echo $order['id'];
    }

    public function actionMeetingDetailInsert()
    {
        $payment= new OrderMeeting;  

        $payment->account_id =$_POST['account_id'];
        $payment->order_id =$_POST['order_id'];
        $payment->company_id =$_POST['company_id'];
        $payment->company_linkman_id =$_POST['company_linkman_id'];
        $payment->layout_id =$_POST['layout_id'];
        $payment->update_time =$_POST['update_time'];
        $payment->save();

        $company = OrderMeetingCompany::model()->findByPk($_POST['company_id']);

        Order::model()->updateByPk($_POST['order_id'],array(
            'order_name'=>$company['company_name'],
            'planner_id'=>$_SESSION['userid'],
        ));
        $order = Order::model()->findByPk($_POST['order_id']);
        //$order = Order::model()->findByPk($_GET['order_id']);
        $hotel = StaffHotel::model()->findByPk($order['staff_hotel_id']);

        $staff = Staff::model()->findByPk($_SESSION['userid']);

        /*$html = '<div class="rich_media_content " id="js_content">    
                    
                    <p>订单类型：婚礼</p>
                    <p>新人姓名：'.$order['order_name'].'</p>
                    <p>开始时间：'.$order['order_date'].'</p>
                    <p>结束时间：'.$order['end_time'].'</p>
                    <p>统筹师：'.$staff["name"].'</p>
                    <p><br></p>
                </div>';*/
        /*print_r($order);die;*/
        $date = explode(" ",$order['order_date']);
        $html = "";
        if($order['order_type'] == 2){
            $html = "新客人进店了[".$hotel['name']."] "."
订单类型：婚礼
日期：".$date[0]."
开单人：".$staff["name"];
        }else if($order['order_type'] == 1){
            $html = "新客人进店了[".$hotel['name']."] "."
订单类型：会议
日期：".$date[0]."
开单人：".$staff["name"];
        };
        
        /*print_r($html);die;*/
        $touser="@all";//你要发的人
        $toparty="";
        $totag="";
        $title="新客人进店了！";//标题
        $agentid=0;//应用
        $thumb_media_id="1VIziIEzGn_YvRxXK3OxPQpylPHLUnnA2gJ5_v8Cus2la7sjhAWYgzyFZhIVI9UoS6lkQ-ZLuMPZgP8BOVIS-XQ";
        $author="";
        $content_source_url="";
        $content = $html;
        $digest="描述";
        $show_cover_pic="";
        $safe="";

        $company = StaffCompany::model()->findByPk($_SESSION['account_id']);  
        $corpid=$company['corpid'];
        $corpsecret=$company['corpsecret'];
        echo 1;
        //$result=WPRequest::sendMessage_Mpnews($touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);
        $result=WPRequest::sendMessage_Text($touser, $toparty, $content,$corpid,$corpsecret);
        print_r($result);
        echo 2;
    }

    public function actionSavetp()
    {
        $data = array(
            'account_id' => $_POST['account_id'],
            'order_id' => $_POST['order_id'],
            'product_id' => $_POST['product_id'],
            'actual_price' => $_POST['actual_price'],
            'unit' => $_POST['amount'],
            'actual_unit_cost' => $_POST['actual_unit_cost'],
            'update_time' => $_POST['update_time'],
            'actual_service_ratio' => $_POST['actual_service_ratio']
        );


        $orderproduct= new OrderProduct;  
        /*print_r($data);die;*/
        $orderproduct->account_id = $data['account_id'];
        $orderproduct->order_id = $data['order_id'];
        $orderproduct->product_id = $data['product_id'];
        $orderproduct->actual_price = $data['actual_price'];
        $orderproduct->unit = $data['unit'];
        $orderproduct->actual_unit_cost = $data['actual_unit_cost'];
        $orderproduct->update_time = $data['update_time'];
        $orderproduct->actual_service_ratio = $data['actual_service_ratio'];

        $orderproduct->save();
    }

    public function actionUpdatetp()
    {
        $data = array(
            'account_id' => $_POST['account_id'],
            'order_id' => $_POST['order_id'],
            'product_id' => $_POST['product_id'],
            'actual_price' => $_POST['actual_price'],
            'unit' => $_POST['amount'],
            'actual_unit_cost' => $_POST['actual_unit_cost'],
            'update_time' => $_POST['update_time'],
            'actual_service_ratio' => $_POST['actual_service_ratio']
        );

        OrderProduct::model()->updateAll($data,'order_id=:order_id && product_id=:product_id',array('order_id'=>$_POST['order_id'],'product_id'=>$_POST['product_id']));
    }

    public function actionDeltp()
    {
        OrderProduct::model()->deleteAll('order_id=:order_id && product_id=:product_id',array('order_id'=>$_POST['order_id'],'product_id'=>$_POST['product_id']));
    } 

    public function actionUpdatecompanyid()
    {
        OrderMeeting::model()->updateAll(array('company_id' => $_POST['company_id']),'order_id=:order_id && account_id=:account_id',array(':order_id' => $_POST['order_id'] , ':account_id' => $_SESSION['account_id']));
        $company = OrderMeetingCompany::model()->findByPk($_POST['company_id']);
        Order::model()->updateByPk($_POST['order_id'],array('order_name'=>$company['company_name']));
    }

    public function actionUpdatelinkmanid()
    {
        OrderMeeting::model()->updateAll(array('company_linkman_id' => $_POST['linkman_id']),'order_id=:order_id && account_id=:account_id',array(':order_id' => $_POST['order_id'] , ':account_id' => $_SESSION['account_id']));
    }

    public function actionUpdatetime()
    {
        Order::model()->updateByPk($_POST['order_id'],array('order_date' => $_POST['order_date'],'end_time' => $_POST['end_time']));
    }

   // public function action

}
