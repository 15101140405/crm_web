<?php

include_once('../library/WPRequest.php');

class DailyReportController extends InitController
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
        $post = json_decode(file_get_contents('php://input'));

        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:telephone',
                'params' => array(
                        ':telephone' => $post->phone,
                    )
            ));
        if($staff['password'] == $post->password){
            $data = array(
                    'account_id' => $staff['account_id'],
                    'staff_hotel_id' => ""
                );
            $hotel = StaffHotel::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $staff['account_id']
                        )
                ));
            if(!empty($hotel)){
                $data['staff_hotel_id'] = $hotel[0]['id'];
            };

            echo json_encode($data);
        }else{
            echo 'faild';
        };
    }

    public function actionTodayBrief()
    {
        //取当日进店数据
        $time = time();
        $date = date("y-m-d");

        $criteria = new CDbCriteria; 
        $criteria->addSearchCondition('update_time', $date);
        $criteria->addCondition('account_id=:account_id');
        $criteria->addCondition('staff_hotel_id=:staff_hotel_id');
        $criteria->params[':account_id']=$_GET['account_id'];  
        $criteria->params[':staff_hotel_id']=$_GET['staff_hotel_id'];  
        $order1 = Order::model()->findAll($criteria);

        $indoor = count($order1); 


        //取当日开单数据
        $result = yii::app()->db->createCommand("select `order`.id,order_payment.update_time from `order` right join order_payment on `order`.id = order_payment.order_id where type=0 and account_id=".$_GET['account_id']." and staff_hotel_id=".$_GET['staff_hotel_id']." order by id");
        $result = $result->queryAll();
        $open_order = array();

        // print_r($result);die;
        foreach ($result as $key => $value) {
            $t=0;
            foreach ($open_order as $key1 => $value1) {
                if($value['id'] == $value1){
                    $t++;
                };
            };
            $time = explode(' ', $value['update_time']);
            // print_r(date('Y-m-d',time()));die;
            if($t==0 && $time[0] == date('Y-m-d',time())){$open_order[] = $value['id'];};
        };



        $result = yii::app()->db->createCommand("select o.id as order_id,o.order_date,o.update_time,s1.name as planner_name,s2.name as designer_name,o.order_type ".
            " from `order` o left join staff s1 on planner_id=s1.id ".
            " left join staff s2 on designer_id=s2.id ".
            " where order_status in (0,1) and o.account_id=".$_GET['account_id']." and o.staff_hotel_id=".$_GET['staff_hotel_id']." order by o.order_date");
        $result = $result->queryAll();

        $order_pool = array();
        $fly_order = array();
        foreach ($result as $key => $value) {
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($value['update_time']);  //订单创建时间
            $zero3=strtotime ($value['order_date']);  //活动日期

            $open_time=ceil(($zero1-$zero2)/86400); //60s*60min*24h

            $t = explode(' ', $value['order_date']);

            $item = array(
                    'order_id' => $value['order_id'],
                    'order_date' => $t[0],
                    'planner_name' => $value['planner_name'],
                    'designer_name' => $value['designer_name'],
                    'open_time' => $open_time,
                    'order_type' => $value['order_type']
                );
            if($zero3 >= $zero1){$order_pool[] = $item;};
            if($zero3 < $zero1){$fly_order[] = $item;};
            
        };

        //取酒店列表
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_GET['account_id'],
                    ),
            ));

        $hotel_data = array();

        foreach ($hotel as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['name']
                );
            $hotel_data[]=$item;
        };

        $data = array(
                'indoor' => $indoor,
                'open_order' => count($open_order),
                'order_pool' => $order_pool,
                'fly_order' => $fly_order,
                'hotel' => $hotel_data
            );

        echo json_encode($data);
    }

    public function actionOrderFollow(){
        $follow = OrderMerchandiser::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    ),
                'order'=>'time'
            ));

        $follow_data = array();
        foreach ($follow as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'type' => $value['type'],
                    'staff_name' => $value['staff_name'],
                    'time' => $value['time'],
                    'order_name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'remarks' => $value['remarks'],
                );
            $follow_data[] = $item;
        };

        $order = Order::model()->findByPk($_GET['order_id']);

        $data = array(
                'follow' => $follow_data,
                'order' => $order
            );

        echo json_encode($data);
    }

    public function actionOrder()
    {
        //取累计订单
        $order_total = Order::model()->findAll(array(
                'condition' => 'staff_hotel_id=:staff_hotel_id',
                'params' => array(
                        ':staff_hotel_id' => $_GET['staff_hotel_id']
                    ),
                // 'order' => 'order_date'
            ));
        $wedding_all = 0;
        $meeting_all = 0;
        $wedding_doing = 0;
        $meeting_doing = 0;
        $sure_order_id = "(";

        // $ttt = "";

        foreach ($order_total as $key => $value) {
            // if($value['id'] == 481){echo 1;};
            // echo $value['id'].",";
            $t = explode(' ', $value['order_date']);
            $t1 = explode('-', $t[0]);
            // print_r(date('M'));die;
            if($value['order_status'] == 2 || $value['order_status'] == 3 || $value['order_status'] == 4 || $value['order_status'] == 5 || $value['order_status'] == 6){
                if($t1[0] == date('Y')){$sure_order_id .= $value['id'].",";};
                if($t1[0] >= date('Y')){
                    if($value['order_type'] == 1){
                        $meeting_all++;
                    }else if($value['order_type'] == 2){
                        // echo $value['id'].",".$t[0]."||";

                        $wedding_all++;
                        // if($value['id'] == 48s1){echo 2;};
                    };

                    if(($t1[1] == date('m') && $t1[2] >= date('d')) || ($t1[1] > date('m')) || $t1[0] > date('Y')){
                        if($value['order_type'] == 1){
                            $meeting_doing++;
                        }else if($value['order_type'] == 2){
                            $wedding_doing++;
                        };
                    };  
                };
            };
        };

        //取订单数据
        $result = yii::app()->db->createCommand("select o1.id as order_id,order_type,order_date,s1.name as planner_name,s2.name as designer_name from `order` o1 left join staff s1 on o1.planner_id = s1.id left join staff s2 on o1.designer_id = s2.id where order_status in (2,3,4,5,6) and o1.account_id=".$_GET['account_id']." and o1.staff_hotel_id=".$_GET['staff_hotel_id']." order by order_date");
        $result = $result->queryAll();
        // print_r(json_encode($result));die;

        $order_list = array();

        foreach ($result as $key => $value) {//取推单渠道
            $item = array();
            $t = explode(' ', $value['order_date']);
            $t1 = explode('-', $t[0]);
            if(($t1[0] >= date("Y") && $t1[1] > date("m")) || ($t1[0] >= date("Y") && $t1[1] == date("m") && $t1[2] > date("d"))){
                $item['order_date'] = $t[0];
                $item['order_type'] = $value['order_type'];
                $item['pd'] = $value['planner_name']."/".$value['designer_name'];
                $result = yii::app()->db->createCommand("select s.name from order_product o left join supplier_product s on o.product_id = s.id where o.order_id=".$value['order_id']." and s.supplier_type_id=16");
                $result = $result->queryAll();
                // print_r($result);
                if(empty($result)){
                    $item['tuidan'] = '无';
                }else{
                    $item['tuidan'] = $result[0]['name'];
                };
                $order_list[] = $item;
            };
        };

        //取酒店列表
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_GET['account_id'],
                    ),
            ));

        $hotel_data = array();

        foreach ($hotel as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['name']
                );
            $hotel_data[]=$item;
        };


        $data = array(
                'wedding_all' => $wedding_all,
                'wedding_doing' => $wedding_doing,
                'meeting_all' => $meeting_all,
                'meeting_doing' => $meeting_doing,
                'order_list' => $order_list,
                'hotel' => $hotel_data
            );

        echo json_encode($data);
    }

    public function actionFinance()
    {
        //取累计订单
        $order_total = Order::model()->findAll(array(
                'condition' => 'staff_hotel_id=:staff_hotel_id',
                'params' => array(
                        ':staff_hotel_id' => $_GET['staff_hotel_id']
                    ),
                // 'order' => 'order_date'
            ));
        $wedding_all = 0;
        $meeting_all = 0;
        $wedding_doing = 0;
        $meeting_doing = 0;
        $sure_order_id = "(";

        // $ttt = "";

        foreach ($order_total as $key => $value) {
            // if($value['id'] == 481){echo 1;};
            // echo $value['id'].",";
            $t = explode(' ', $value['order_date']);
            $t1 = explode('-', $t[0]);
            // print_r(date('M'));die;
            if($value['order_status'] == 2 || $value['order_status'] == 3 || $value['order_status'] == 4 || $value['order_status'] == 5 || $value['order_status'] == 6){
                if($t1[0] == date('Y')){$sure_order_id .= $value['id'].",";};
                if($t1[0] >= date('Y')){
                    if($value['order_type'] == 1){
                        $meeting_all++;
                    }else if($value['order_type'] == 2){
                        // echo $value['id'].",".$t[0]."||";

                        $wedding_all++;
                        // if($value['id'] == 48s1){echo 2;};
                    };

                    if(($t1[1] == date('m') && $t1[2] >= date('d')) || ($t1[1] > date('m')) || $t1[0] > date('Y')){
                        if($value['order_type'] == 1){
                            $meeting_doing++;
                        }else if($value['order_type'] == 2){
                            $wedding_doing++;
                        };
                    };  
                };
            };
        };
        // print_r($ttt);die;
        $sure_order_id = substr($sure_order_id,0,strlen($sure_order_id)-1);
        $sure_order_id .= ")";
        
        // print_r($sure_order_id);die;

        

        //取门店销售目标
        $hotel = StaffHotel::model()->findByPk($_GET['staff_hotel_id']);


        //取销售额  ()
        $order_product_designOrder = yii::app()->db->createCommand("".
            "select actual_price,order_product.unit,actual_unit_cost,actual_service_ratio,designer_id,planner_id,other_discount,feast_discount,discount_range,supplier_type_id,s1.`name` as designer_name,s2.`name` as planner_name ".
            "from (((order_product left join `order` on order_id = `order`.id) ".
            "left join supplier_product on product_id = supplier_product.id) ".
            "left join staff s1 on designer_id = s1.id) ".
            "left join staff s2 on planner_id = s2.id".
            " where order_id in " .$sure_order_id. "order by designer_id");
        $order_product_designOrder = $order_product_designOrder->queryAll(); 

        // print_r(json_encode($order_product_designOrder));die;

        $hotel_total_sales = 0;
        $hotel_total_cost = 0;

        $design_person_sales = array();
        $tem_id = $order_product_designOrder[0]['designer_id'];
        $t_total_sales = 0;//存储个人策划总价
        $tem_person_data = array();//存储个人信息

        foreach ($order_product_designOrder as $key => $value){
            $hotel_total_cost += $value['actual_unit_cost'];
            if($value['designer_id'] != $tem_id){
                $t_total_sales = 0;
            };
            if($value['supplier_type_id'] == 2){
                $hotel_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1)*(1+$value['actual_service_ratio']*0.01);
            }else{
                $t=explode(',', $value['discount_range']);
                $tem = 0;
                foreach ($t as $key1 => $value1) {
                    if($value1 == $value['supplier_type_id']){$tem++;};
                };
                if($tem == 0){//不在折扣范围内
                    $hotel_total_sales += $value['actual_price']*$value['unit'];
                    $t_total_sales += $value['actual_price']*$value['unit'];
                }else{//在折扣范围内
                    $hotel_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1);
                    $t_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1);
                };
            };

            if($value['designer_id'] == $tem_id){
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    ); 
            }else{
                $design_person_sales[] = $tem_person_data;
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    );
            };
            // echo $tem_id."|";
            $tem_id = $value['designer_id'];
        };
        // print_r($design_person_sales);die;

        $arr_order = yii::app()->db->createCommand("select turnover from `order` where id in ".$sure_order_id);
        $arr_order = $arr_order->queryAll(); 
        foreach ($arr_order as $key => $value) {
            if($value['turnover'] != 0 && $value['turnover'] != "" && $value['turnover'] != null){
                $hotel_total_sales -= $value['turnover'];
            };
        };



        //取汇款总额
        $order_payment = yii::app()->db->createCommand("select * from order_payment where order_id in " .$sure_order_id);
        $order_payment = $order_payment->queryAll(); 
        $order_total_payment = 0;
        foreach ($order_payment as $key => $value) {
            $order_total_payment += $value['money'];
        };



        //计算个人业绩
        $order_total = Order::model()->findAll(array( //取门店累计订单
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_GET['account_id']
                    )
            ));

        $sure_order_id = "(";

        foreach ($order_total as $key => $value) {
            $t = explode(' ', $value['order_date']);
            $t1 = explode('-', $t[0]);
            // print_r(date('M'));die;
            if($value['order_status'] == 2 || $value['order_status'] == 3 || $value['order_status'] == 4 || $value['order_status'] == 5 || $value['order_status'] == 6){
                if($t1[0] == date('Y')){$sure_order_id .= $value['id'].",";};
            };
        };
        $sure_order_id = substr($sure_order_id,0,strlen($sure_order_id)-1);
        $sure_order_id .= ")";
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        $order_product_planGroup = yii::app()->db->createCommand("". //个人餐饮业绩
            "select planner_id,s2.name,sum(actual_price*unit*feast_discount*0.1*(1+actual_service_ratio*0.01)) as total ".
            "from (order_product left join `order` on order_id = `order`.id) ".
            "left join staff s2 on planner_id = s2.id".
            " where order_id in " .$sure_order_id. "group by planner_id");
        $order_product_planGroup = $order_product_planGroup->queryAll(); 
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋

        $order_product_designOrder = yii::app()->db->createCommand("".  //个人策划业绩
            "select actual_price,order_product.unit,actual_service_ratio,designer_id,planner_id,other_discount,feast_discount,discount_range,supplier_type_id,s1.`name` as designer_name,s2.`name` as planner_name ".
            "from (((order_product left join `order` on order_id = `order`.id) ".
            "left join supplier_product on product_id = supplier_product.id) ".
            "left join staff s1 on designer_id = s1.id) ".
            "left join staff s2 on planner_id = s2.id".
            " where order_id in " .$sure_order_id. "order by designer_id");
        $order_product_designOrder = $order_product_designOrder->queryAll(); 

        // print_r(json_encode($order_product_designOrder));die;



        $design_person_sales = array();
        $tem_id = $order_product_designOrder[0]['designer_id'];
        $t_total_sales = 0;//存储个人策划总价
        $tem_person_data = array();//存储个人信息

        foreach ($order_product_designOrder as $key => $value){
            if($value['designer_id'] != $tem_id){
                $t_total_sales = 0;
            };
            if($value['supplier_type_id'] != 2){
                $t=explode(',', $value['discount_range']);
                $tem = 0;
                foreach ($t as $key1 => $value1) {
                    if($value1 == $value['supplier_type_id']){$tem++;};
                };
                if($tem == 0){//不在折扣范围内
                    $t_total_sales += $value['actual_price']*$value['unit'];
                }else{//在折扣范围内
                    $t_total_sales += $value['actual_price']*$value['unit']*($value['feast_discount']*0.1);
                };
            };

            if($value['designer_id'] == $tem_id){
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    ); 
            }else{    
                $tem_person_data = array(
                        'designer_id' => $value['designer_id'],
                        'name' => $value['designer_name'],
                        'total' => $t_total_sales
                    );
                $design_person_sales[] = $tem_person_data;
            };
            // echo $tem_id."|";
            $tem_id = $value['designer_id'];
        };
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        $arr_staff_sales = array();//存全部员工销售额;
        $staff_sales = array();//存个人销售额；
        // print_r($design_person_sales);die;
        foreach ($order_product_planGroup as $key_p => $value_p) {
            $staff_sales['id'] = $value_p['planner_id'];
            $staff_sales['name'] = $value_p['name'];
            $staff_sales['sales'] = $value_p['total'];
            foreach ($design_person_sales as $key_d => $value_d) {
                if($value_p['planner_id'] == $value_d['designer_id']){
                    $staff_sales['sales'] += $value_d['total'];
                };/*else{
                    $t=array(
                            'id' => $value_d['designer_id'],
                            'name' => $value_d['name'],
                            'sales' => $value_d['total'],
                        );
                    foreach ($order_product_planGroup as $kp => $vp) {
                        
                    }
                };*/
            };
            $arr_staff_sales[] = $staff_sales;
        };
        // print_r($arr_staff_sales);die;
        foreach ($design_person_sales as $key => $value) {
            $staff_sales['id'] = $value['designer_id'];
            $staff_sales['name'] = $value['name'];
            $staff_sales['sales'] = $value['total'];
            $t=0;
            foreach ($arr_staff_sales as $k_arr => $val_arr) {
                if($val_arr['id'] == $value['designer_id']){
                    $t++;
                };
            };
            if($t == 0){
                $arr_staff_sales[] = $staff_sales;
            };
        };
        
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋

        foreach ($arr_staff_sales as $key => $value) {
            $arr_staff_sales[$key]['sales'] = round($value['sales']);        
        };


        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        // ＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋＋
        $sales = array();
        foreach ($arr_staff_sales as $user) {
            $sales[] = $user['sales'];
        }
         
        array_multisort($sales, SORT_DESC, $arr_staff_sales);

        //取酒店列表
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_GET['account_id'],
                    ),
            ));

        $hotel_data = array();

        foreach ($hotel as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['name']
                );
            $hotel_data[]=$item;
        };

        $data = array(
                'hotel_total_sales' => number_format($hotel_total_sales/10000,1),
                'hotel_total_cost' => number_format($hotel_total_cost/10000,1),
                'order_total_payment' => number_format($order_total_payment/10000,1),
                'profit' => number_format(($order_total_payment-$hotel_total_cost)/10000,1),
                'arr_staff_sales' => $arr_staff_sales,
                'hotel' => $hotel_data
            );

        echo json_encode($data);
    }

    public function actionChannel()
    {
        $channel = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && supplier_type_id=:supplier_type_id',
                'params' => array(
                        ':account_id' => $_GET['account_id'],
                        ':supplier_type_id' => 16
                    )
            ));

        $channel_data = array();

        foreach ($channel as $key => $value) {
            $result = yii::app()->db->createCommand("select o.id ".
                " from `order` o left join order_product op on o.id=op.order_id ".
                " where op.product_id=".$value['id']." and o.staff_hotel_id=".$_GET['staff_hotel_id']);
            $result = $result->queryAll();

            $order_num = 0;
            $order_id_list = "";
            $cur_id = "";
            $total_sales = array();

            if(!empty($result)){
                $order_num = 1;
                $order_id_list = "(".$result[0]['id'];
                $cur_id = $result[0]['id'];

                foreach ($result as $key1 => $value1) {
                    if($value1['id'] != $cur_id){
                        $cur_id = $value1['id'];
                        $order_num++;
                        $order_id_list .= ",".$value1['id'];
                    };
                };

                $order_id_list .= ")";

                // print_r($order_id_list);die;

                // $order_id_list = "(44,45)";

                $orderForm = new OrderForm;
                $total_sales = $orderForm->many_order_price($order_id_list);
                // print_r($total_sales);die;
            };


            $item = array();
            $item['name'] = $value['name'];
            $item['num'] = $order_num;
            $item['total_sales'] = 0;
            foreach ($total_sales as $key => $value) {
                $item['total_sales'] += $value['total_price'];
            };

            if($item['num'] != 0){
                $channel_data[] = $item;
            };

        };

        $num = array();
        foreach ($channel_data as $user) {
            $num[] = $user['num'];
        }
         
        array_multisort($num, SORT_DESC, $channel_data);

        //取酒店列表
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_GET['account_id'],
                    ),
            ));

        $hotel_data = array();

        foreach ($hotel as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['name']
                );
            $hotel_data[]=$item;
        };

        $data = array(
                'channel_data' => $channel_data,
                'hotel' => $hotel_data
            );

        echo json_encode($data);
    }

}
