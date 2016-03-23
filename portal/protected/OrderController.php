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
        $code = $_GET['code'];

        if($code == ''){
            $url1 = 'crm.cike360.com/portal/index.php?r=order/index';
            /*print_r($url1);die;*/
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxee0a719fd467c364&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect";
            echo "<script>window.location='".$url."';</script>";
        };

        $t=new WPRequest;
        $userId = $t->getUserId($code);
        $adder=array("UserId"=>"222","DeviceId"=>"");
        $adder=json_decode($userId,true);
    	if(!empty($adder['UserId'])) {
    		$this->render("index",array(
                'userId' => $adder['UserId'],
            ));
    	}
        
        
        
    }

    public function actionGetIndexData()
    {
        $year = $_POST['year'];
        $month = $_POST['month'];
        $account_id = $_POST['account_id'];
        /*print_r($month);die;*/
        $IndexData = $this->actionIndexData($year,$month,$account_id);

        echo $IndexData;
    }

    public function actionIndexData($year,$month,$accountId)
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
            "condition" => "account_id=:account_id",
            "params" => array(
                ":account_id" => $accountId,
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

        $alldate = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($orderData as $key => $value) {
            

            if ($value['order_year'] == $year && $value['order_month'] == $month && $value['order_status'] == 1 ) {
                $maybe_data .= $value['order_data']."," ;    
            };
            if ($value['order_year'] == $year && $value['order_month'] == $month && $value['order_status'] == 0 ) {
                $maybe_data .= $value['order_data']."," ;    
            };
            if ($value['order_year'] == $year && $value['order_month'] == $month && $value['order_status'] != 1 && $value['order_status'] != 0 ) {
                  $alldate[$value['order_data']] += ($value['order_time']+1);
            }
        }

        foreach ($alldate as $key => $value) {
            if($value == 6){
                $data .= (string)$key."," ; 
            }else if($value != 0){
                $half_data .= (string)$key."," ; 
            }
        };
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
        $staff_hotel_id = 1;
        $account_id = 1;

        $arr_order = Order::model()->findAll(array(
            "condition" => "staff_hotel_id=:staff_hotel_id",
            "params" => array(
                ":staff_hotel_id" => $staff_hotel_id
            )
        ));

        /*$my_order_wedding = Order::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                ":account_id" => $account_id
            )
        ));*/

        /*$arr_order = array(//+++++++++++++++++++++++++++所需数据
            '0' => array(
                'order_id' => '111',
                'order_time' => '15-12-11 12:11',
                'order_type' => '会议',
                'order_category' => '会议',
                'order_name' => '张斯恒',
                'company_name' => '北京浩瀚一方互联网科技1',
                'order_status' => '4',
                'order_new' => ''
            ),
            '1' => array(
                'order_id' => '1121',
                'order_time' => '15-12-11 12:11',
                'order_type' => '策划', //婚礼类型
                'order_category' => '婚礼',
                'order_name' => '',
                'company_name' => '北京浩瀚一方互联网科技2',
                'order_status' => '2',
                'order_new' => 'new'//order_name为空的婚礼
            ),
            '2' => array(
                'order_id' => '113',
                'order_time' => '15-12-11 12:11',
                'order_type' => '会议',
                'order_category' => '会议',
                'order_name' => '张斯恒',
                'company_name' => '北京浩瀚一方互联网科技3',
                'order_status' => '2',
                'order_new' => ''
            ),
            '3' => array(
                'order_id' => '113',
                'order_time' => '15-12-11 12:11',
                'order_type' => '策划',
                'order_category' => '婚礼',
                'order_name' => '',
                'company_name' => '北京浩瀚一方互联网科技4',
                'order_status' => '4',
                'order_new' => 'new'
            ),
            '4' => array(
                'order_id' => '1121',
                'order_time' => '15-12-11 12:11',
                'order_type' => '统筹',
                'order_category' => '婚礼',
                'order_name' => '张斯恒',
                'company_name' => '北京浩瀚一方互联网科技5',
                'order_status' => '1',
                'order_new' => ''
            ),
            '5' => array(
                'order_id' => '1121',
                'order_time' => '15-12-11 12:11',
                'order_type' => '策划',
                'order_category' => '婚礼',
                'order_name' => '',
                'company_name' => '北京浩瀚一方互联网科技6',
                'order_status' => '3',
                'order_new' => 'new',
            ),

        );*/

        

        $this->render("my", array(
            "arr_order" => $arr_order,
            /*"my_order_wedding" => $my_order_wedding*/
        ));
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
        $this->render("selectType");
    }

    public function actionFeast()
    {
        $this->render("feast");
    }

    public function actionSelectFeast()
    {
        $this->render("selectFeast");
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

    public static function post($url, $post_data = '', $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    public static function get($url, $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
    public static function getAccessToken()
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . WPRequest::$corpid . "&corpsecret=" . WPRequest::$corpsecret;
        //echo $url;
        $data = self::get($url);
        try {
            $obj = json_decode($data);
            return $obj->access_token;
        } catch (Excption $e) {
            return "";
        }

    }

}
