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

    public function actionIndex()
    {
        Yii::app()->session['userid']=100;
        Yii::app()->session['code']=123123123;
        Yii::app()->session['account_id']=1;
        Yii::app()->session['staff_hotel_id']=1;
        /*$_SESSION['userid']=100;
        $_SESSION['code']=123123123;
        $_SESSION['account_id']=1;
        $_SESSION['staff_hotel_id']=1;*/

        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){//已登陆
            //echo '已登陆';
            if($_GET['from'] == 'bill'){
                $order = Order::model()->findByPk($_GET['this_order']);
                
                $t1 = explode(" ",$order['order_date']);
                $t2 = explode("-",$t1[0]);
                
                $this->render("index",array(
                    'userId' => $_SESSION['userid'],
                    'first_show_year' => $t2[0],
                    'first_show_month' => $t2[1],
                    'first_show_day' => $t2[2],
                ));
            }else if($_GET['from'] == ''){
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                //print_r($y.$m.$d);
                $this->render("index",array(
                    //'userId' => $_SESSION['userid'],
                    'first_show_year' => $y,
                    'first_show_month' => $m,
                    'first_show_day' => $d,
                ));
            }
        }else{//未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=order/index';
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
                
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                $this->render("index",array(
                    'userId' => $adder['UserId'],
                    'first_show_year' => $y,
                    'first_show_month' => $m,
                    'first_show_day' => $d,
                ));
            }
        };
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
        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){//已登陆
            //echo '已登陆';
            
            $arr_order = Order::model()->findAll(array(
                "condition" => "adder_id=:adder_id || planner_id=:planner_id",
                "params" => array(
                    ":adder_id" => $_SESSION['userid'],
                    ":planner_id" => $_SESSION['userid']
                )
            ));
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
                
                $arr_order = Order::model()->findAll(array(
                    "condition" => "adder_id=:adder_id || planner_id=:planner_id",
                    "params" => array(
                        ":adder_id" => $_SESSION['userid'],
                        ":planner_id" => $_SESSION['userid']
                    )
                ));
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
        $department = array("[1,2,3]","[2,3]");
        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("department_list",$department);
        $criteria1->addCondition("account_id=:account_id");
        $criteria1->params[':account_id']=1; 
        $staff = Staff::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        foreach ($staff as $value) {
            $item['id'] = $value->id;
            $item['name'] = $value->name;
            $item['avatar'] = $value->avatar;
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

        /*if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){//已登陆
            //echo '已登陆';
            $this->render("selectType");
        }else{*///未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=order/selectType';
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
                $this->render("selectType");
            }
        /*};*/
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
        $order_hotel = Order::model()->findAll(array(
            'condition' => 'staff_hotel_id = :staff_hotel_id',
            'params' => array(
                ':staff_hotel_id' => $_POST['account_id']
            )
        ));
        $arr_order_all = array();
        foreach ($order_hotel as $key => $value) {
            $item =array();
            $item['order_id'] = $value['id'];
            $item['order_type'] = $value['order_type'];
            $item['order_name'] = $value['order_name'];
            $item['order_status'] = $value['order_status'];
            $item['planner_id'] = $value['planner_id'];
            $item['designer_id'] = $value['designer_id'];
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
        $this->render('order_info');
    }

    public function actionOrderinfofollow()
    {
        $this->render('order_info_follow');
    }

    public function actionOrderinfofollowdetail()
    {
        $this->render('order_info_follow_detail');
    }

}
