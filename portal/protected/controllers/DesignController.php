<?php

class DesignController extends InitController
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

    public function actionList()
    {
        $this->render("/order/my");
    }

    public function actionDetail()
    {
        $orderId = $_GET['order_id'];
        $orderForm = new OrderForm();
        $orderData = $orderForm -> getOrderDetail($orderId);
        /*print_r($orderData);*/
        $this->render("detail",array(
            "arr" => $orderData,
        ));
    }

    public function actionBill()
    {
        /*Yii::app()->session['userid']=100;
        Yii::app()->session['code']='asjfdlk123';
        Yii::app()->session['account_id']=1;
        Yii::app()->session['staff_hotel_id']=1;*/
        /*if(isset($_SESSION['userid']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){//已登陆
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

        /*print_r($order_discount['other_discount']);die;
*/
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
            $criteria1->params[':category']=2; 
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
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01),
                'total_cost' => $wed_feast[0]['actual_unit_cost']*$wed_feast[0]['unit'],
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01,
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01)/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01)),
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_wed_feast);*/

        /*********************************************************************************************************************/
        /*取灯光数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_light = array();
        $light = array();
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
        if (!empty($light)) {
            $arr_light_total['total_price']=0;
            $arr_light_total['total_cost']=0;
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
                $arr_light_total['total_price'] += $light[$key]['actual_price']*$light[$key]['unit'];
                $arr_light_total['total_cost'] +=$light[$key]['actual_unit_cost']*$light[$key]['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            if($arr_light_total['total_price'] != 0){
                $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];    
            }else if($arr_light_total['total_cost'] != 0){
                $arr_light_total['gross_profit_rate'] = 0;
            }     
        }else{
            $arr_light_total['gross_profit']=0;
            $arr_light_total['gross_profit_rate']=0;
            $arr_light_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }

        /*print_r($arr_light_total);*/

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

        if (!empty($video)) {
            $arr_video_total['total_price']=0;
            $arr_video_total['total_cost']=0;
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
                $arr_video_total['total_price'] += $video[$key]['actual_price']*$video[$key]['unit'];
                $arr_video_total['total_cost'] +=$video[$key]['actual_unit_cost']*$video[$key]['unit'];
            }
            
                $arr_video_total['gross_profit']=$arr_video_total['total_price']-$arr_video_total['total_cost'];
            if($arr_video_total['total_price'] != 0){
                $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];    
            }else if($arr_video_total['total_cost'] != 0){
                $arr_video_total['gross_profit_rate'] = 0;
            }           
            
        }

        /*print_r($arr_video_total);*/

        /*********************************************************************************************************************/
        /*取主持人数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_host = array();
        $arr_host_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 3),
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
            $host = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $host[] = $item;
            };
            /*print_r($host);*/
        }
        if (!empty($host)) {
            $arr_host_total['total_price']=0;
            $arr_host_total['total_cost']=0;
            foreach ($host as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$host[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $host[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $host[$key]['unit'],
                );
                $arr_host[]=$item;
                $arr_host_total['total_price'] += $host[$key]['actual_price']*$host[$key]['unit'];
                $arr_host_total['total_cost'] +=$host[$key]['actual_unit_cost']*$host[$key]['unit'];
            }        
            $arr_host_total['gross_profit']=$arr_host_total['total_price']-$arr_host_total['total_cost'];
            if($arr_host_total['total_price'] != 0){
                $arr_host_total['gross_profit_rate']=$arr_host_total['gross_profit']/$arr_host_total['total_price'];    
            }else if($arr_host_total['total_cost'] != 0){
                $arr_host_total['gross_profit_rate'] = 0;
            }   
        }

        /*print_r($arr_host);*/


        /*********************************************************************************************************************/
        /*取摄像数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_camera = array();
        $camera = array();
        $arr_camera_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 4),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $camera[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($camera)) {
            $arr_camera_total['total_price']=0;
            $arr_camera_total['total_cost']=0;
            foreach ($camera as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$camera[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $camera[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $camera[$key]['unit'],
                );
                $arr_camera[]=$item;
                $arr_camera_total['total_price'] += $camera[$key]['actual_price']*$camera[$key]['unit'];
                $arr_camera_total['total_cost'] +=$camera[$key]['actual_unit_cost']*$camera[$key]['unit'];
            }           
            $arr_camera_total['gross_profit']=$arr_camera_total['total_price']-$arr_camera_total['total_cost'];
            if($arr_camera_total['total_price'] != 0){
                $arr_camera_total['gross_profit_rate']=$arr_camera_total['gross_profit']/$arr_camera_total['total_price'];    
            }else if($arr_camera_total['total_cost'] != 0){
                $arr_camera_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取摄影数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_photo = array();
        $photo = array();
        $arr_photo_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 5),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $photo[] = $item;
            };
            /*print_r($photo);*/
        }
        if (!empty($photo)) {
            $arr_photo_total['total_price']=0;
            $arr_photo_total['total_cost']=0;
            foreach ($photo as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$photo[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $photo[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $photo[$key]['unit'],
                );
                $arr_photo[]=$item;
                $arr_photo_total['total_price'] += $photo[$key]['actual_price']*$photo[$key]['unit'];
                $arr_photo_total['total_cost'] +=$photo[$key]['actual_unit_cost']*$photo[$key]['unit'];
            }           
            $arr_photo_total['gross_profit']=$arr_photo_total['total_price']-$arr_photo_total['total_cost'];
            if($arr_photo_total['total_price'] != 0){
                $arr_photo_total['gross_profit_rate']=$arr_photo_total['gross_profit']/$arr_photo_total['total_price'];    
            }else if($arr_photo_total['total_cost'] != 0){
                $arr_photo_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_photo_total);*/

        /*********************************************************************************************************************/
        /*取化妆数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_makeup = array();
        $makeup = array();
        $arr_makeup_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 6),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $makeup[] = $item;
            };
            /*print_r($makeup);*/
        }
        if (!empty($makeup)) {
            $arr_makeup_total['total_price']=0;
            $arr_makeup_total['total_cost']=0;
            foreach ($makeup as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$makeup[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $makeup[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $makeup[$key]['unit'],
                );
                $arr_makeup[]=$item;
                $arr_makeup_total['total_price'] += $makeup[$key]['actual_price']*$makeup[$key]['unit'];
                $arr_makeup_total['total_cost'] +=$makeup[$key]['actual_unit_cost']*$makeup[$key]['unit'];
            }           
            $arr_makeup_total['gross_profit']=$arr_makeup_total['total_price']-$arr_makeup_total['total_cost'];
            if($arr_makeup_total['total_price'] != 0){
                $arr_makeup_total['gross_profit_rate']=$arr_makeup_total['gross_profit']/$arr_makeup_total['total_price'];    
            }else if($arr_makeup_total['total_cost'] != 0){
                $arr_makeup_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取其他人员数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_other = array();
        $other = array();
        $arr_other_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 7),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $other[] = $item;
            };
            /*print_r($other);*/
        }
        if (!empty($other)) {
            $arr_other_total['total_price']=0;
            $arr_other_total['total_cost']=0;
            foreach ($other as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$other[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $other[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $other[$key]['unit'],
                );
                $arr_other[]=$item;
                $arr_other_total['total_price'] += $other[$key]['actual_price']*$other[$key]['unit'];
                $arr_other_total['total_cost'] +=$other[$key]['actual_unit_cost']*$other[$key]['unit'];
            }           
            $arr_other_total['gross_profit']=$arr_other_total['total_price']-$arr_other_total['total_cost'];
            if($arr_other_total['total_price'] != 0){
                $arr_other_total['gross_profit_rate']=$arr_other_total['gross_profit']/$arr_other_total['total_price'];    
            }else if($arr_other_total['total_cost'] != 0){
                $arr_other_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/



        /*********************************************************************************************************************/
        /*计算人员部分总价*/
        /*********************************************************************************************************************/
        $arr_service_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );
        /*print_r($arr_photo_total);die;*/
        if(!empty($arr_host_total)){
            $arr_service_total['total_price'] += $arr_host_total['total_price'];
            $arr_service_total['total_cost'] += $arr_host_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_host_total['gross_profit'];
        }

        if(!empty($arr_camera_total)){
            $arr_service_total['total_price'] += $arr_camera_total['total_price'];
            $arr_service_total['total_cost'] += $arr_camera_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_camera_total['gross_profit'];
        }

        if(!empty($arr_photo_total)){
            $arr_service_total['total_price'] += $arr_photo_total['total_price'];
            $arr_service_total['total_cost'] += $arr_photo_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_photo_total['gross_profit'];
        }

        if(!empty($arr_makeup_total)){
            $arr_service_total['total_price'] += $arr_makeup_total['total_price'];
            $arr_service_total['total_cost'] += $arr_makeup_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_makeup_total['gross_profit'];
        }

        if(!empty($arr_other_total)){
            $arr_service_total['total_price'] += $arr_other_total['total_price'];
            $arr_service_total['total_cost'] += $arr_other_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_other_total['gross_profit'];
        }



        if($arr_service_total['total_price'] != 0){
            $arr_service_total['gross_profit_rate'] = $arr_service_total['gross_profit']/$arr_service_total['total_price'];
        }




        /*********************************************************************************************************************/
        /*取场地布置数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_decoration = array();
        $decoration = array();
        $arr_decoration_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 20),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $decoration[] = $item;
            };
            /*print_r($decoration);*/
        }
        if (!empty($decoration)) {
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
            foreach ($decoration as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$decoration[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $decoration[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $decoration[$key]['unit'],
                );
                $arr_decoration[]=$item;
                $arr_decoration_total['total_price'] += $decoration[$key]['actual_price']*$decoration[$key]['unit'];
                $arr_decoration_total['total_cost'] +=$decoration[$key]['actual_unit_cost']*$decoration[$key]['unit'];
            }           
            $arr_decoration_total['gross_profit']=$arr_decoration_total['total_price']-$arr_decoration_total['total_cost'];
            if($arr_decoration_total['total_price'] != 0){
                $arr_decoration_total['gross_profit_rate']=$arr_decoration_total['gross_profit']/$arr_decoration_total['total_price'];    
            }else if($arr_decoration_total['total_cost'] != 0){
                $arr_decoration_total['gross_profit_rate'] = 0;
            }  
        }else{
            $arr_decoration_total['gross_profit']=0;
            $arr_decoration_total['gross_profit_rate']=0;
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }
        /*print_r($arr_decoration_total['total_cost']);die;*/

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取平面设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_graphic = array();
        $graphic = array();
        $arr_graphic_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 10),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $graphic[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($graphic)) {
            $arr_graphic_total['total_price']=0;
            $arr_graphic_total['total_cost']=0;
            foreach ($graphic as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$graphic[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $graphic[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $graphic[$key]['unit'],
                );
                $arr_graphic[]=$item;
                $arr_graphic_total['total_price'] += $graphic[$key]['actual_price']*$graphic[$key]['unit'];
                $arr_graphic_total['total_cost'] +=$graphic[$key]['actual_unit_cost']*$graphic[$key]['unit'];
            }           
            $arr_graphic_total['gross_profit']=$arr_graphic_total['total_price']-$arr_graphic_total['total_cost'];
            if($arr_graphic_total['total_price'] != 0){
                $arr_graphic_total['gross_profit_rate']=$arr_graphic_total['gross_profit']/$arr_graphic_total['total_price'];    
            }else {
                $arr_graphic_total['gross_profit_rate']=0;
            }
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视频设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_film = array();
        $film = array();
        $arr_film_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 11),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $film[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($film)) {
            $arr_film_total['total_price']=0;
            $arr_film_total['total_cost']=0;
            foreach ($film as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$film[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $film[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $film[$key]['unit'],
                );
                $arr_film[]=$item;
                $arr_film_total['total_price'] += $film[$key]['actual_price']*$film[$key]['unit'];
                $arr_film_total['total_cost'] +=$film[$key]['actual_unit_cost']*$film[$key]['unit'];
            }           
            $arr_film_total['gross_profit']=$arr_film_total['total_price']-$arr_film_total['total_cost'];
            if($arr_film_total['total_price'] != 0){
                $arr_film_total['gross_profit_rate']=$arr_film_total['gross_profit']/$arr_film_total['total_price'];    
            }else {
                $arr_film_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视策划师产品数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_designer = array();
        $designer = array();
        $arr_designer_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 17),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);die;*/

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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $designer[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($designer)) {
            $arr_designer_total['total_price']=0;
            $arr_designer_total['total_cost']=0;
            foreach ($designer as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$designer[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $designer[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $designer[$key]['unit'],
                );
                $arr_designer[]=$item;
                $arr_designer_total['total_price'] += $designer[$key]['actual_price']*$designer[$key]['unit'];
                $arr_designer_total['total_cost'] +=$designer[$key]['actual_unit_cost']*$designer[$key]['unit'];
            }           
            $arr_designer_total['gross_profit']=$arr_designer_total['total_price']-$arr_designer_total['total_cost'];
            if($arr_designer_total['total_price'] != 0){
                $arr_designer_total['gross_profit_rate']=$arr_designer_total['gross_profit']/$arr_designer_total['total_price'];    
            }else {
                $arr_designer_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($designer);die;*/
        

        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_order_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        

        /*print_r($order_discount);die;*/

        if(!empty($arr_wed_feast)){
            $arr_order_total['total_price'] += $arr_wed_feast['total_price'] * $order_discount['feast_discount'] * 0.1;
            $arr_order_total['total_cost'] += $arr_wed_feast['total_cost'];
        }

        if(!empty($arr_video)){
            if($this->judge_discount(9,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_video_total['total_price'];
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_video_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }
            
        }

        if(!empty($arr_light)){
            if($this->judge_discount(8,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_light_total['total_price'];
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_light_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }
        }

        if(!empty($arr_service_total)){
            if($this->judge_discount(3,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_service_total['total_price'];
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_service_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }
        }

        if(!empty($arr_decoration_total)){
            if($this->judge_discount(20,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'];
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }
        }
        if(!empty($arr_graphic_total)){
            if($this->judge_discount(10,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'];
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }
        }
        if(!empty($arr_film_total)){
            if($this->judge_discount(11,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_film_total['total_price'];
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_film_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }
        }
        if(!empty($arr_designer_total)){
            if($this->judge_discount(17,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_designer_total['total_price'];
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_designer_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }
        }

        if($order_discount['cut_price'] != 0){
            $arr_order_total['total_price'] -= $order_discount['cut_price'];
        }

        /*print_r($arr_order_total['total_price']);die;*/
        $arr_order_total['gross_profit'] = $arr_order_total['total_price'] - $arr_order_total['total_cost'];

        if($arr_order_total['total_price'] != 0){
            $arr_order_total['gross_profit_rate']=$arr_order_total['gross_profit']/$arr_order_total['total_price'];    
        }else {
            $arr_order_total['gross_profit_rate']=0;
        }

        
        



        /*********************************************************************************************************************/
        /*取订单日期、名称……等数据*/
        /*********************************************************************************************************************/

        $criteria3 = new CDbCriteria; 
        $criteria3->addCondition("id=:id");
        $criteria3->params[':id']=$orderId; 
        $order_data = Order::model()->find($criteria3);
        /*print_r($order_data);die;*/

        //查找策划师姓名
        $criteria3 = new CDbCriteria; 
        $criteria3->addCondition("id=:id");
        $criteria3->params[':id']=$order_data['designer_id']; 
        $designer= Staff::model()->find($criteria3);
        /*print_r($designer);*/


        //查找策划师姓名
        $criteria3 = new CDbCriteria; 
        $criteria3->addCondition("id=:id");
        $criteria3->params[':id']=$order_data['planner_id']; 
        $planer= Staff::model()->find($criteria3);
        /*print_r($designer);*/

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
        foreach ($follow as $key => $value) {
            if($value['type'] == '0'){$in_door++;}else{$out_door++;};
        };

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



        // *********************************************************************************************************************
        // 查访问者，所在部门
        // *********************************************************************************************************************
        $staff_user = Staff::model()->findByPk($_SESSION['userid']);
        $user_department_list= $staff_user['department_list'];
        /*print_r($in_door);die;*/
        $this->render("bill",array(
            "arr_wed_feast" => $arr_wed_feast,
            "arr_video" => $arr_video,
            "arr_video_total" => $arr_video_total,
            "arr_light" => $arr_light,
            "arr_light_total" => $arr_light_total,
            "arr_host" => $arr_host,
            "arr_camera" => $arr_camera,
            "arr_photo" => $arr_photo,
            "arr_makeup" => $arr_makeup,
            "arr_other" => $arr_other,
            'arr_service_total' => $arr_service_total,
            "arr_decoration" => $arr_decoration,
            "arr_decoration_total" => $arr_decoration_total,
            "arr_graphic" => $arr_graphic,
            "arr_graphic_total" => $arr_graphic_total,
            "arr_film" => $arr_film,
            "arr_film_total" => $arr_film_total,
            "arr_designer" => $arr_designer,
            "arr_designer_total" => $arr_designer_total,
            "arr_order_total" => $arr_order_total,
            "arr_order_data" => $order_data,
            "designer" => $designer['name'],
            "planner" => $planer['name'],
            "select_reference"  => $select_reference,
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

    public function actionBilltable()
    {
        $Order = Order::model()->findByPk($_GET['order_id']);
        $date = explode(" ",$Order['order_date']);
        $t = new StaffForm();
        $wed = OrderWedding::model()->find(array(
            'condition' => 'order_id=:order_id',
            'params' => array(
                ':order_id' => $_GET['order_id']
            )
        ));


        $order_data = array();
        $order_data['id']='W'.$_GET['order_id'].'-'.$date[0];
        $order_data['feast_discount']=$Order['feast_discount'];
        $order_data['other_discount']=$Order['other_discount'];
        $order_data['cut_price']=$Order['cut_price'];
        $order_data['designer_name']=$t->getName($Order['designer_id']);
        $order_data['groom_name']=$wed['groom_name'];
        $order_data['groom_phone']=$wed['groom_phone'];
        $order_data['bride_name']=$wed['bride_name'];
        $order_data['bride_phone']=$wed['bride_phone'];

        //print_r($order_data);die;

        $orderId = $_GET['order_id'];
        $supplier_product_id = array();
        $wed_feast = array();
        $arr_wed_feast = array();

        $order_discount = Order::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $orderId),
        ));

        /*print_r($order_discount['other_discount']);die;
*/
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
            $criteria1->params[':category']=2; 
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
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio'])*$order_discount['feast_discount'],
                'total_cost' => $wed_feast[0]['actual_unit_cost']*$wed_feast[0]['unit'],
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio'],
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio'])/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio'])),
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_wed_feast);*/

        /*********************************************************************************************************************/
        /*取灯光数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_light = array();
        $light = array();
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
        if (!empty($light)) {
            $arr_light_total['total_price']=0;
            $arr_light_total['total_cost']=0;
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
                $arr_light_total['total_price'] += $light[$key]['actual_price']*$light[$key]['unit'];
                $arr_light_total['total_cost'] +=$light[$key]['actual_unit_cost']*$light[$key]['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            if($arr_light_total['total_price'] != 0){
                $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];    
            }else if($arr_light_total['total_cost'] != 0){
                $arr_light_total['gross_profit_rate'] = 0;
            }     
        }else{
            $arr_light_total['gross_profit']=0;
            $arr_light_total['gross_profit_rate']=0;
            $arr_light_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }

        /*print_r($arr_light_total);*/

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

        if (!empty($video)) {
            $arr_video_total['total_price']=0;
            $arr_video_total['total_cost']=0;
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
                $arr_video_total['total_price'] += $video[$key]['actual_price']*$video[$key]['unit'];
                $arr_video_total['total_cost'] +=$video[$key]['actual_unit_cost']*$video[$key]['unit'];
            }
            
                $arr_video_total['gross_profit']=$arr_video_total['total_price']-$arr_video_total['total_cost'];
            if($arr_video_total['total_price'] != 0){
                $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];    
            }else if($arr_video_total['total_cost'] != 0){
                $arr_video_total['gross_profit_rate'] = 0;
            }           
            
        }

        /*print_r($arr_video_total);*/

        /*********************************************************************************************************************/
        /*取主持人数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_host = array();
        $arr_host_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 3),
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
            $host = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $host[] = $item;
            };
            /*print_r($host);*/
        }
        if (!empty($host)) {
            $arr_host_total['total_price']=0;
            $arr_host_total['total_cost']=0;
            foreach ($host as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$host[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $host[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $host[$key]['unit'],
                );
                $arr_host[]=$item;
                $arr_host_total['total_price'] += $host[$key]['actual_price']*$host[$key]['unit'];
                $arr_host_total['total_cost'] +=$host[$key]['actual_unit_cost']*$host[$key]['unit'];
            }        
            $arr_host_total['gross_profit']=$arr_host_total['total_price']-$arr_host_total['total_cost'];
            if($arr_host_total['total_price'] != 0){
                $arr_host_total['gross_profit_rate']=$arr_host_total['gross_profit']/$arr_host_total['total_price'];    
            }else if($arr_host_total['total_cost'] != 0){
                $arr_host_total['gross_profit_rate'] = 0;
            }   
        }

        /*print_r($arr_host);*/


        /*********************************************************************************************************************/
        /*取摄像数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_camera = array();
        $camera = array();
        $arr_camera_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 4),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $camera[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($camera)) {
            $arr_camera_total['total_price']=0;
            $arr_camera_total['total_cost']=0;
            foreach ($camera as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$camera[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $camera[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $camera[$key]['unit'],
                );
                $arr_camera[]=$item;
                $arr_camera_total['total_price'] += $camera[$key]['actual_price']*$camera[$key]['unit'];
                $arr_camera_total['total_cost'] +=$camera[$key]['actual_unit_cost']*$camera[$key]['unit'];
            }           
            $arr_camera_total['gross_profit']=$arr_camera_total['total_price']-$arr_camera_total['total_cost'];
            if($arr_camera_total['total_price'] != 0){
                $arr_camera_total['gross_profit_rate']=$arr_camera_total['gross_profit']/$arr_camera_total['total_price'];    
            }else if($arr_camera_total['total_cost'] != 0){
                $arr_camera_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取摄影数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_photo = array();
        $photo = array();
        $arr_photo_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 5),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $photo[] = $item;
            };
            /*print_r($photo);*/
        }
        if (!empty($photo)) {
            $arr_photo_total['total_price']=0;
            $arr_photo_total['total_cost']=0;
            foreach ($photo as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$photo[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $photo[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $photo[$key]['unit'],
                );
                $arr_photo[]=$item;
                $arr_photo_total['total_price'] += $photo[$key]['actual_price']*$photo[$key]['unit'];
                $arr_photo_total['total_cost'] +=$photo[$key]['actual_unit_cost']*$photo[$key]['unit'];
            }           
            $arr_photo_total['gross_profit']=$arr_photo_total['total_price']-$arr_photo_total['total_cost'];
            if($arr_photo_total['total_price'] != 0){
                $arr_photo_total['gross_profit_rate']=$arr_photo_total['gross_profit']/$arr_photo_total['total_price'];    
            }else if($arr_photo_total['total_cost'] != 0){
                $arr_photo_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_photo_total);*/

        /*********************************************************************************************************************/
        /*取化妆数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_makeup = array();
        $makeup = array();
        $arr_makeup_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 6),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $makeup[] = $item;
            };
            /*print_r($makeup);*/
        }
        if (!empty($makeup)) {
            $arr_makeup_total['total_price']=0;
            $arr_makeup_total['total_cost']=0;
            foreach ($makeup as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$makeup[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $makeup[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $makeup[$key]['unit'],
                );
                $arr_makeup[]=$item;
                $arr_makeup_total['total_price'] += $makeup[$key]['actual_price']*$makeup[$key]['unit'];
                $arr_makeup_total['total_cost'] +=$makeup[$key]['actual_unit_cost']*$makeup[$key]['unit'];
            }           
            $arr_makeup_total['gross_profit']=$arr_makeup_total['total_price']-$arr_makeup_total['total_cost'];
            if($arr_makeup_total['total_price'] != 0){
                $arr_makeup_total['gross_profit_rate']=$arr_makeup_total['gross_profit']/$arr_makeup_total['total_price'];    
            }else if($arr_makeup_total['total_cost'] != 0){
                $arr_makeup_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取其他人员数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_other = array();
        $other = array();
        $arr_other_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 7),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $other[] = $item;
            };
            /*print_r($other);*/
        }
        if (!empty($other)) {
            $arr_other_total['total_price']=0;
            $arr_other_total['total_cost']=0;
            foreach ($other as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$other[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $other[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $other[$key]['unit'],
                );
                $arr_other[]=$item;
                $arr_other_total['total_price'] += $other[$key]['actual_price']*$other[$key]['unit'];
                $arr_other_total['total_cost'] +=$other[$key]['actual_unit_cost']*$other[$key]['unit'];
            }           
            $arr_other_total['gross_profit']=$arr_other_total['total_price']-$arr_other_total['total_cost'];
            if($arr_other_total['total_price'] != 0){
                $arr_other_total['gross_profit_rate']=$arr_other_total['gross_profit']/$arr_other_total['total_price'];    
            }else if($arr_other_total['total_cost'] != 0){
                $arr_other_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/



        /*********************************************************************************************************************/
        /*计算人员部分总价*/
        /*********************************************************************************************************************/
        $arr_service_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        if(!empty($arr_host_total)){
            $arr_service_total['total_price'] += $arr_host_total['total_price'];
            $arr_service_total['total_cost'] += $arr_host_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_host_total['gross_profit'];
        }

        if(!empty($arr_camera_total)){
            $arr_service_total['total_price'] += $arr_camera_total['total_price'];
            $arr_service_total['total_cost'] += $arr_camera_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_camera_total['gross_profit'];
        }

        if(!empty($arr_photo_total)){
            $arr_service_total['total_price'] += $arr_photo_total['total_price'];
            $arr_service_total['total_cost'] += $arr_photo_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_photo_total['gross_profit'];
        }

        if(!empty($arr_makeup_total)){
            $arr_service_total['total_price'] += $arr_makeup_total['total_price'];
            $arr_service_total['total_cost'] += $arr_makeup_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_makeup_total['gross_profit'];
        }

        if(!empty($arr_other_total)){
            $arr_service_total['total_price'] += $arr_other_total['total_price'];
            $arr_service_total['total_cost'] += $arr_other_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_other_total['gross_profit'];
        }



        if($arr_service_total['total_price'] != 0){
            $arr_service_total['gross_profit_rate'] = $arr_service_total['gross_profit']/$arr_service_total['total_price'];
        }




        /*********************************************************************************************************************/
        /*取场地布置数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_decoration = array();
        $decoration = array();
        $arr_decoration_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 20),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $decoration[] = $item;
            };
            /*print_r($decoration);*/
        }
        if (!empty($decoration)) {
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
            foreach ($decoration as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$decoration[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $decoration[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $decoration[$key]['unit'],
                );
                $arr_decoration[]=$item;
                $arr_decoration_total['total_price'] += $decoration[$key]['actual_price']*$decoration[$key]['unit'];
                $arr_decoration_total['total_cost'] +=$decoration[$key]['actual_unit_cost']*$decoration[$key]['unit'];
            }           
            $arr_decoration_total['gross_profit']=$arr_decoration_total['total_price']-$arr_decoration_total['total_cost'];
            if($arr_decoration_total['total_price'] != 0){
                $arr_decoration_total['gross_profit_rate']=$arr_decoration_total['gross_profit']/$arr_decoration_total['total_price'];    
            }else if($arr_decoration_total['total_cost'] != 0){
                $arr_decoration_total['gross_profit_rate'] = 0;
            }  
        }else{
            $arr_decoration_total['gross_profit']=0;
            $arr_decoration_total['gross_profit_rate']=0;
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }
        /*print_r($arr_decoration_total['total_cost']);die;*/

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取平面设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_graphic = array();
        $graphic = array();
        $arr_graphic_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 10),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $graphic[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($graphic)) {
            $arr_graphic_total['total_price']=0;
            $arr_graphic_total['total_cost']=0;
            foreach ($graphic as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$graphic[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $graphic[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $graphic[$key]['unit'],
                );
                $arr_graphic[]=$item;
                $arr_graphic_total['total_price'] += $graphic[$key]['actual_price']*$graphic[$key]['unit'];
                $arr_graphic_total['total_cost'] +=$graphic[$key]['actual_unit_cost']*$graphic[$key]['unit'];
            }           
            $arr_graphic_total['gross_profit']=$arr_graphic_total['total_price']-$arr_graphic_total['total_cost'];
            if($arr_graphic_total['total_price'] != 0){
                $arr_graphic_total['gross_profit_rate']=$arr_graphic_total['gross_profit']/$arr_graphic_total['total_price'];    
            }else {
                $arr_graphic_total['gross_profit_rate']=0;
            }
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视频设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_film = array();
        $film = array();
        $arr_film_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 11),
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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $film[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($film)) {
            $arr_film_total['total_price']=0;
            $arr_film_total['total_cost']=0;
            foreach ($film as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$film[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $film[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $film[$key]['unit'],
                );
                $arr_film[]=$item;
                $arr_film_total['total_price'] += $film[$key]['actual_price']*$film[$key]['unit'];
                $arr_film_total['total_cost'] +=$film[$key]['actual_unit_cost']*$film[$key]['unit'];
            }           
            $arr_film_total['gross_profit']=$arr_film_total['total_price']-$arr_film_total['total_cost'];
            if($arr_film_total['total_price'] != 0){
                $arr_film_total['gross_profit_rate']=$arr_film_total['gross_profit']/$arr_film_total['total_price'];    
            }else {
                $arr_film_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视策划师产品数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_designer = array();
        $designer = array();
        $arr_designer_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 17),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);die;*/

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
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $designer[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($designer)) {
            $arr_designer_total['total_price']=0;
            $arr_designer_total['total_cost']=0;
            foreach ($designer as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$designer[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $designer[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $designer[$key]['unit'],
                );
                $arr_designer[]=$item;
                $arr_designer_total['total_price'] += $designer[$key]['actual_price']*$designer[$key]['unit'];
                $arr_designer_total['total_cost'] +=$designer[$key]['actual_unit_cost']*$designer[$key]['unit'];
            }           
            $arr_designer_total['gross_profit']=$arr_designer_total['total_price']-$arr_designer_total['total_cost'];
            if($arr_designer_total['total_price'] != 0){
                $arr_designer_total['gross_profit_rate']=$arr_designer_total['gross_profit']/$arr_designer_total['total_price'];    
            }else {
                $arr_designer_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($designer);die;*/
        

        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_order_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        

        /*print_r($order_discount);die;*/

        if(!empty($arr_wed_feast)){
            $arr_order_total['total_price'] += $arr_wed_feast['total_price'];
            $arr_order_total['total_cost'] += $arr_wed_feast['total_cost'];
        }

        if(!empty($arr_video)){
            $arr_order_total['total_price'] += $arr_video_total['total_price'];
            $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
        }

        if(!empty($arr_light)){
            $arr_order_total['total_price'] += $arr_light_total['total_price'];
            $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
        }

        if(!empty($arr_service_total)){
            $arr_order_total['total_price'] += $arr_service_total['total_price'];
            $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
        }

        if(!empty($arr_decoration_total)){
            $arr_order_total['total_price'] += $arr_decoration_total['total_price'];
            $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
        }
        if(!empty($arr_graphic_total)){
            $arr_order_total['total_price'] += $arr_graphic_total['total_price'];
            $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
        }
        if(!empty($arr_film_total)){
            $arr_order_total['total_price'] += $arr_film_total['total_price'];
            $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
        }
        if(!empty($arr_designer_total)){
            $arr_order_total['total_price'] += $arr_designer_total['total_price'];
            $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
        }

        /*print_r($arr_order_total['total_price']);die;*/
        $arr_order_total['gross_profit'] = $arr_order_total['total_price'] - $arr_order_total['total_cost'];

        if($arr_order_total['total_price'] != 0){
            $arr_order_total['gross_profit_rate']=$arr_order_total['gross_profit']/$arr_order_total['total_price'];    
        }else {
            $arr_order_total['gross_profit_rate']=0;
        }






        $out1="1";/*=$this->render("billtable",array(
            "order_data" => $order_data,
            "arr_wed_feast" => $arr_wed_feast,
            "arr_video" => $arr_video,
            "arr_video_total" => $arr_video_total,
            "arr_light" => $arr_light,
            "arr_light_total" => $arr_light_total,
            "arr_host" => $arr_host,
            "arr_camera" => $arr_camera,
            "arr_photo" => $arr_photo,
            "arr_makeup" => $arr_makeup,
            "arr_other" => $arr_other,
            'arr_service_total' => $arr_service_total,
            "arr_decoration" => $arr_decoration,
            "arr_decoration_total" => $arr_decoration_total,
            "arr_graphic" => $arr_graphic,
            "arr_graphic_total" => $arr_graphic_total,
            "arr_film" => $arr_film,
            "arr_film_total" => $arr_film_total,
            "arr_designer" => $arr_designer,
            "arr_designer_total" => $arr_designer_total,
            "arr_order_total" => $arr_order_total,
            
        ));
*/
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

    public function actionDecoration()
    {
//         $Supplier = Supplier::model()->findAll(array(
//             "condition" => "type_id=:type_id",
//             "params" => array(
//                 ":type_id" => 20,
//             ),
//         ));
//         $supplier_id = array();
//         foreach($Supplier as $key => $value){
//             $supplier_id[$key] = $value['id'];

//         }
//         $criteria = new CDbCriteria; 
//         $criteria->addInCondition("supplier_id",$supplier_id);
//         $SupplierProducts = SupplierProduct::model()->findAll($criteria);
//         $product_id = array();
//         foreach($SupplierProducts as $key => $value){
//             $product_id[$key] = $value['id'];
//         }
//         $criteria1 = new CDbCriteria; 
//         $criteria1->addInCondition("product_id",$product_id);
//         $criteria1->addCondition("order_id=:order_id");
//         $criteria1->params[':order_id']=$_GET['order_id']; 
//         $OrderProducts = OrderProduct::model()->findAll($criteria1);
//         $product_list = array();

        $data = $this -> actionGetOrderProduct(20);

        $supplier_product = yii::app()->db->createCommand("select standard_type,supplier_product.id,supplier_product.name as product_name,staff.name as staff_name from supplier_product left join supplier on supplier_id=supplier.id left join staff on staff_id=staff.id where supplier_product.account_id=".$_SESSION['account_id']." and supplier_type_id=20");
        $product_list = $supplier_product->queryAll();

        $product_total = 0 ;
        foreach($data as $key => $value){
            $product_list[$key]['product_id'] = $value['product_id'];
            $product_list[$key]['unit_price'] = $value['actual_price'];
            $product_list[$key]['amount'] = $value['unit'];
            $product_list[$key]['total'] = 0;
            $product_list[$key]['total'] += $value['actual_price'] * $value['unit'];
            $product_total += $value['actual_price'] * $value['unit'];

            // $Product = SupplierProduct::model()->find(array(
            //     "condition" => "id=:id",
            //     "params" => array(
            //         ":id" => $value['product_id'],
            //     ),
            // ));
            // $product_list[$key]['img'] = $Product['ref_pic_url'];
            // $product_list[$key]['name'] = $Product['name'];
            // $product_list[$key]['unit'] = $Product['unit'];
        }
        //print_r($data);die;
        $this->render("decoration",
            array(
                'product_list' => $product_list,
                'product_total' => $product_total,
                'data' => $data,
                // 'arr_category_appliance' => $supplierProducts1,
            ));
        // $this->render("decoration");
    }

    public function actionDecorationDetail()
    {
        if($_GET['type'] == "edit"){
            $product_data = SupplierProduct::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $_GET['product_id'],
                ),
            ));
            $amount = OrderProduct::model()->find(array(
                "condition" => "product_id=:product_id && order_id=:order_id",
                "params" => array(
                    ":product_id" => $_GET['product_id'],
                    ":order_id" => $_GET['order_id']
                ),
            ));
            $this->render("decorationDetail",array(
                'product_data' => $product_data,
                'amount' => $amount['unit']
            ));  
        }else{
            $this->render("decorationDetail");
        }
        
    }

    public function actionDressAppliance()
    {
        $accountId = $_SESSION['account_id'];
        $SupplierProductForm = new SupplierProductForm();
        $supplierProducts = $SupplierProductForm->getSupplierProductdressAppliance($accountId);
        $supplierProducts1 = $SupplierProductForm->getSupplierProductdressAppliance1($accountId);

        /*********************************************************************************************************************/
        /*取order_product  婚纱*/
        /*********************************************************************************************************************/

        $dress_data = $this -> actionGetOrderProduct(12);
        $dress_total = 0 ;
        if(!empty($dress_data)){
            foreach ($dress_data as $key => $value) {
                $dress_total += $value['actual_price']*$value['unit'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  婚品*/
        /*********************************************************************************************************************/

        $appliance_data = $this -> actionGetOrderProduct(13);
        $appliance_total = 0 ;
        if(!empty($appliance_data)){
            foreach ($appliance_data as $key => $value) {
                $appliance_total += $value['actual_price']*$value['unit'];
            }
        }

        $dressappliance_total = $dress_total + $appliance_total ;
        
        $lightingscreen_bill = array(  //background data
            'total' => $dressappliance_total,
            'desc' => array(
                '酒水' => $dress_total,
                '车辆' => $appliance_total,
            )

        );

         
        $this->render("dressAppliance",
            array(
                'arr_category_dress' => $supplierProducts,
                'arr_category_appliance' => $supplierProducts1,
                'dress_data' => $dress_data,
                'appliance_data' => $appliance_data,
                'dress_bill' => $lightingscreen_bill,
            ));
        // $this->render("dressAppliance");
    }

    public function actionDrinksCar()
    {
        $accountId = $_SESSION['account_id'];
        $SupplierProductForm = new SupplierProductForm();
        $supplierProducts = $SupplierProductForm->getSupplierProductdrinksCar($accountId);
        $supplierProducts1 = $SupplierProductForm->getSupplierProductdrinksCar1($accountId);


        /*********************************************************************************************************************/
        /*取order_product  酒水*/
        /*********************************************************************************************************************/

        $drinks_data = $this -> actionGetOrderProduct(14);
        $drinks_total = 0 ;
        if(!empty($drinks_data)){
            foreach ($drinks_data as $key => $value) {
                $drinks_total += $value['actual_price']*$value['unit'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  车辆*/
        /*********************************************************************************************************************/

        $car_data = $this -> actionGetOrderProduct(15);
        $car_total = 0 ;
        if(!empty($car_data)){
            foreach ($car_data as $key => $value) {
                $car_total += $value['actual_price']*$value['unit'];
            }
        }

        $drinkscar_total = $drinks_total + $car_total ;
        
        $lightingscreen_bill = array(  //background data
            'total' => $drinkscar_total,
            'desc' => array(
                '酒水' => $drinks_total,
                '车辆' => $car_total,
            )

        );
        
        $this->render("drinksCar",
            array(
                'arr_category_drinks' => $supplierProducts,
                'arr_category_car' => $supplierProducts1,
                'drinks_data' => $drinks_data,
                'car_data' => $drinks_data,
                'drinks_bill' => $lightingscreen_bill,
            ));
        // $this->render("drinksCar");
    }

    public function actionLightingScreen()
    {
        $accountId = $_SESSION['account_id'];
        $SupplierProductForm = new SupplierProductForm();
        $supplierProducts = $SupplierProductForm->getSupplierProductLight($accountId);
        $supplierProducts1 = $SupplierProductForm->getSupplierProductLight1($accountId);

        /*********************************************************************************************************************/
        /*取order_product  灯光*/
        /*********************************************************************************************************************/

        $lighting_data = $this -> actionGetOrderProduct(8);
        $lighting_total = 0 ;
        if(!empty($lighting_data)){
            foreach ($lighting_data as $key => $value) {
                $lighting_total += $value['actual_price']*$value['unit'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  视频*/
        /*********************************************************************************************************************/

        $screen_data = $this -> actionGetOrderProduct(9);
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

    public function actionLightingMadeDetail()
    {
        $this->render("lightingMadeDetail");
    }

    public function actionGraphicFilm()
    {
        $accountId = $_SESSION['account_id'];
        $SupplierProductForm = new SupplierProductForm();
        $supplierProducts = $SupplierProductForm->getSupplierProductgraphicFilm($accountId);
        $supplierProducts1 = $SupplierProductForm->getSupplierProductgraphicFilm1($accountId);

        /*********************************************************************************************************************/
        /*取order_product  平面设计*/
        /*********************************************************************************************************************/

        $graphic_data = $this -> actionGetOrderProduct(10);
        $graphic_total = 0 ;
        if(!empty($graphic_data)){
            foreach ($graphic_data as $key => $value) {
                $graphic_total += $value['actual_price']*$value['unit'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  视频设计*/
        /*********************************************************************************************************************/

        $film_data = $this -> actionGetOrderProduct(11);
        $film_total = 0 ;
        if(!empty($film_data)){
            foreach ($film_data as $key => $value) {
                $film_total += $value['actual_price']*$value['unit'];
            }
        }
        $filmgraphic_total = $graphic_total + $film_total ;

        $film_bill = array(  //background data
            'total' => $filmgraphic_total,
            'desc' => array(
                '平面设计' => $graphic_total,
                '视频设计' => $film_total,
            )

        );

         
        $this->render("graphicFilm",
            array(
                'arr_category_graphic' => $supplierProducts,
                'arr_category_film' => $supplierProducts1,
                'graphic_data' => $graphic_data,
                'film_data' => $film_data,
                'film_bill' => $film_bill,
            ));
        // $this->render("graphicFilm");
    }

    public function actionPlanother()
    {
        $accountId = $_SESSION['account_id'];

        $SupplierProduct = SupplierProduct::model()->findAll(array(
            "condition" => "supplier_type_id=:supplier_type_id && account_id=:account_id",
            "params" => array( 
                ":supplier_type_id" => 17,
                ":account_id"       => $accountId),
        ));

        $arr_category_planother = array();
        foreach ($SupplierProduct as $key => $value) {
            $supplier = Supplier::model()->findByPk($value['supplier_id']);
            $staff = Staff::model()->findByPk($supplier['staff_id']);
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['supplier_name'] = $staff['name'];
            $arr_category_planother[] = $item;
        }
        /*print_r($arr_category_planother);die;*/
        
        /*********************************************************************************************************************/
        /*取order_product  策划师产品*/
        /*********************************************************************************************************************/

        $planother_data = $this -> actionGetOrderProduct(17);
        $planother_total = 0 ;
        if(!empty($planother_data)){
            foreach ($planother_data as $key => $value) {
                $planother_total += $value['actual_price']*$value['unit'];
            }
        }



        $planother_bill = array(  //background data
            'total' => $planother_total,
        );

        /*print_r($planother_bill['total']);die;*/

        $this->render("planother",
            array(
                'arr_category_planother' => $arr_category_planother,
                'planother_data' => $planother_data,
                'planother_bill' => $planother_bill,
            ));
        // $this->render("graphicFilm");
    }

    public function actionTpDetail()
    {
        $productData = SupplierProduct::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $_GET['product_id']),
        ));

        $orderproduct = OrderProduct::model()->find(array(
            "condition" => "order_id = :order_id && product_id = :product_id",
            "params" => array(":order_id" => $_GET['order_id'],':product_id' => $_GET['product_id']),
        ));

        // print_r($orderproduct);die;

        $this->render("tpDetail",array(
            'productData' => $productData ,
            'orderproduct' => $orderproduct 
        ));
    }

    public function actionHostDetail()
    {
        $supplier_product = SupplierProduct::model()->findAll(array(
            "condition" => "supplier_id = :supplier_id && account_id = :account_id",
            "params" => array(":supplier_id" => $_GET['supplier_id'],':account_id' => $_SESSION['account_id']),
        ));
        $supplier = Supplier::model()->findByPk($_GET['supplier_id']);
        $service_person = ServicePerson::model()->find(array(
                'condition' => 'staff_id = :staff_id',
                'params' => array(
                        ':staff_id' => $supplier['staff_id'],
                    )
            ));
        $orderproduct = array();
        if($_GET['type'] == "edit"){
            $supplierproduct = SupplierProduct::model()->findAll(array(
                    'condition' => 'supplier_id = :supplier_id',
                    'params' => array(
                            ':supplier_id' => $_GET['supplier_id']
                        )
                ));
            $product_id = array();
            foreach ($supplierproduct as $key => $value) {
                $product_id[] = $value['id']; 
            };

            $criteria = new CDbCriteria; 
            $criteria->addInCondition('product_id', $product_id);
            $criteria->addCondition("order_id = :order_id");    
            $criteria->params[':order_id']=$_GET['order_id'];  
            $orderproduct = OrderProduct::model()->find($criteria); 

        };
        /*print_r($supplier_product);die;*/
        /*print_r($supplier);die;*/
        $this->render('hostDetail',array(
                'supplier_product' => $supplier_product,
                'order_product' => $orderproduct,
                'service_person' => $service_person,
            ));
    }

    public function actionServicePersonnel()
    {
        /*********************************************************************************************************************/
        /*取supplier_product*/
        /*********************************************************************************************************************/

        $accountId = $_SESSION['account_id'];
        $SupplierProductForm = new SupplierProductForm();
        $supplierProducts = $SupplierProductForm->getSupplierProductList($accountId);
        /*print_r($supplierProducts);die;*/
        $supplierProducts1 = $SupplierProductForm->getSupplierProductList1($accountId);
        $supplierProducts2 = $SupplierProductForm->getSupplierProductList2($accountId);
        $supplierProducts3 = $SupplierProductForm->getSupplierProductList3($accountId);
        $supplierProducts4 = $SupplierProductForm->getSupplierProductList4($accountId);


        /*********************************************************************************************************************/
        /*取order_product  主持人*/
        /*********************************************************************************************************************/

        $result = yii::app()->db->createCommand("select order_product.id as order_product_id,supplier_product.id as supplier_product_id,actual_price,order_product.unit,supplier.staff_id from order_product left join supplier_product on product_id=supplier_product.id left join supplier on supplier_id=supplier.id where supplier.type_id=3 and order_product.order_id=".$_GET['order_id']);
        $host_data = $result->queryAll();
        /*print_r($host_data);die;*/
        $host_selected_staff_id = array();
        $host_id = array();
        $host_total = 0 ;
        foreach ($host_data as $key => $value) {
            $host_selected_staff_id[] = $value['staff_id'];
            $host_total += $value['actual_price']*$value['unit'];
        };
        
        
        /*if(!empty($host_data)){
            foreach ($host_data as $key => $value) {
                
                $supplier_product = SupplierProduct::model()->findByPk($value['product_id']);
                $supplier = Supplier::model()->findByPk($supplier_product['supplier_id']);
                $host_selected_staff_id[] = $supplier['staff_id'];
            }
        }*/

        /*********************************************************************************************************************/
        /*取order_product  摄像师*/
        /*********************************************************************************************************************/

        $video_data = $this -> actionGetOrderProduct(4);
        $video_total = 0 ;
        $video_selected_staff_id = array();
        if(!empty($video_data)){
            foreach ($video_data as $key => $value) {
                $video_total += $value['actual_price']*$value['unit'];
                $supplier_product = SupplierProduct::model()->findByPk($value['product_id']);
                $supplier = Supplier::model()->findByPk($supplier_product['supplier_id']);
                $video_selected_staff_id[] = $supplier['staff_id'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  摄影师*/
        /*********************************************************************************************************************/

        $camera_data = $this -> actionGetOrderProduct(5);
        $camera_total = 0 ;
        $camera_selected_staff_id = array();
        if(!empty($camera_data)){
            foreach ($camera_data as $key => $value) {
                $camera_total += $value['actual_price']*$value['unit'];
                $supplier_product = SupplierProduct::model()->findByPk($value['product_id']);
                $supplier = Supplier::model()->findByPk($supplier_product['supplier_id']);
                $camera_selected_staff_id[] = $supplier['staff_id'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  化妆师*/
        /*********************************************************************************************************************/

        $makeup_data = $this -> actionGetOrderProduct(6);
        $makeup_total = 0 ;
        $makeup_selected_staff_id = array();
        if(!empty($makeup_data)){
            foreach ($makeup_data as $key => $value) {
                $makeup_total += $value['actual_price']*$value['unit'];
                $supplier_product = SupplierProduct::model()->findByPk($value['product_id']);
                $supplier = Supplier::model()->findByPk($supplier_product['supplier_id']);
                $makeup_selected_staff_id[] = $supplier['staff_id'];
            }
        }

        /*********************************************************************************************************************/
        /*取order_product  其他*/
        /*********************************************************************************************************************/

        $other_data = $this -> actionGetOrderProduct(7);
        $other_total = 0 ;
        $other_selected_staff_id = array();
        if(!empty($other_data)){
            foreach ($other_data as $key => $value) {
                $other_total += $value['actual_price']*$value['unit'];
                $supplier_product = SupplierProduct::model()->findByPk($value['product_id']);
                $supplier = Supplier::model()->findByPk($supplier_product['supplier_id']);
                $other_selected_staff_id[] = $supplier['staff_id'];
            }
        }
        
        $service_total = $host_total + $video_total + $camera_total + $makeup_total + $other_total ;
        
        $serve_bill = array(  //background data
            'total' => $service_total,
            'desc' => array(
                '主持人' => $host_total,
                '摄像师' => $video_total,
                '摄影师' => $camera_total,
                '化妆师' => $makeup_total,
                '其他' => $other_total,
            )

        );


        

        /*print_r($supplierProducts);die;*/


        $this->render("servicePersonnel",
            array(
                'arr_category_host' => $supplierProducts,
                'arr_category_video' => $supplierProducts1,
                'arr_category_camera' => $supplierProducts2,
                'arr_category_makeup' => $supplierProducts3,
                'arr_category_other' => $supplierProducts4,
                'host_id' => $host_id,
                'video_data' => $video_data,
                'camera_data' => $camera_data,
                'makeup_data' => $makeup_data,
                'other_data' => $other_data,
                'serve_bill' => $serve_bill,
                'other_selected_staff_id' =>$other_selected_staff_id,
                'makeup_selected_staff_id' => $makeup_selected_staff_id,
                'camera_selected_staff_id' => $camera_selected_staff_id,
                'video_selected_staff_id' => $video_selected_staff_id,
                'host_selected_staff_id' => $host_selected_staff_id,
            ));
    }

    public function actionGetOrderProduct($supplier_type_id)//获取本订单的相应种类的已选产品
    {
        $OrderHost = SupplierProduct::model()->findAll(array(
            "select" => "id",
            "condition" => "supplier_type_id=:supplier_type_id && category=:category && account_id=:account_id",
            "params" => array( ":supplier_type_id" => $supplier_type_id , ":category" => 2 , ":account_id" => $_SESSION['account_id']),
            "order" => "unit_price"

        ));

        $supplier_id = array();
        foreach ($OrderHost as $key => $value) {
            $supplier_id[] = $value['id'];
        }
        /*print_r($host_id);die;*/
        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("product_id",$supplier_id);
        $criteria1->addCondition("order_id=:order_id");
        $criteria1->params[':order_id']=$_GET['order_id']; 
        $supplier_data = OrderProduct::model()->findAll($criteria1);
        /*var_dump($supplier_data);die;*/
        return $supplier_data ;


    }

    public function actionVideoDetail()
    {
        $this->render("videoDetail");
    }

    public function actionfeastDetail(){
        $this->render("feastDetail");
    }
    public function actionSavetp()
    {
        // $data = array(
        //     'account_id'            => $_POST['account_id'],
        //     'order_id'              => $_POST['order_id'],
        //     'product_id'            => $_POST['product_id'],
        //     'actual_price'          => $_POST['actual_price'],
        //     'unit'                  => $_POST['amount'],
        //     'actual_unit_cost'      => $_POST['actual_unit_cost'],
        //     'update_time'           => $_POST['update_time'],
        //     'actual_service_ratio'  => $_POST['actual_service_ratio'],
        //     'remark'                => $_POST['remark']
        // );


        $orderproduct= new OrderProduct;  
        /*print_r($data);die;*/
        $orderproduct->account_id = $_POST['account_id'];
        $orderproduct->order_id = $_POST['order_id'];
        $orderproduct->product_id = $_POST['product_id'];
        $orderproduct->actual_price = $_POST['actual_price'];
        $orderproduct->unit = $_POST['amount'];
        $orderproduct->actual_unit_cost = $_POST['actual_unit_cost'];
        $orderproduct->update_time = $_POST['update_time'];
        $orderproduct->actual_service_ratio = $_POST['actual_service_ratio'];
        $orderproduct->remark = $_POST['remark'];

        $orderproduct->save();

    }

    public function actionSavehost()
    {
        /*if($_POST['product_id'] == 0){
            $supplier = Supplier::model()->findByPk($_POST['supplier_id']);
            $supplierproduct= new SupplierProduct;  
            $supplierproduct->account_id = $_POST['account_id'];
            $supplierproduct->supplier_id = $_POST['supplier_id'];
            $supplierproduct->supplier_type_id = $supplier['type_id'];
            $supplierproduct->standard_type = 0;
            $supplierproduct->name = "婚礼主持";
            $supplierproduct->category = 2;
            $supplierproduct->unit_price = $_POST['actual_price'];
            $supplierproduct->unit_cost = $_POST['actual_unit_cost'];
            $supplierproduct->unit = "元／场";
            $supplierproduct->update_time = $_POST['update_time'];
            $supplierproduct->service_charge_ratio = 0;

            $supplierproduct->save();

            $supplierproduct = SupplierProduct::model()->find(array(
                    'condition' => 'account_id=:account_id && supplier_id=:supplier_id && update_time=:update_time',
                    'params' => array(
                            ':account_id' => $_POST['account_id'],
                            ':supplier_id' => $_POST['supplier_id'],
                            ':update_time' => $_POST['update_time'],
                        )
                ));

            $orderproduct= new OrderProduct;  
            $orderproduct->account_id = $_POST['account_id'];
            $orderproduct->order_id = $_POST['order_id'];
            $orderproduct->product_id = $supplierproduct['id'];
            $orderproduct->actual_price = $supplierproduct['unit_price'];
            $orderproduct->unit = $supplierproduct['unit'];
            $orderproduct->actual_unit_cost = $supplierproduct['unit_cost'];
            $orderproduct->update_time = $_POST['update_time'];
            $orderproduct->actual_service_ratio = $supplierproduct['service_charge_ratio'];

            $orderproduct->save();
        }else{*/
            $orderproduct= new OrderProduct;  
            $orderproduct->account_id = $_POST['account_id'];
            $orderproduct->order_id = $_POST['order_id'];
            $orderproduct->product_id = $_POST['product_id'];
            $orderproduct->actual_price = $_POST['actual_price'];
            $orderproduct->unit = "元／场";
            $orderproduct->actual_unit_cost = $_POST['actual_unit_cost'];
            $orderproduct->update_time = $_POST['update_time'];

            $orderproduct->save();
        /*}*/
    }

    public function actionUpdatetp()
    {
        /*$_POST['order_id'] = 68;
        $_POST['product_id'] = 36;*/
        
        $orderproduct = OrderProduct::model()->find(array(
                'condition' => 'order_id=:order_id && product_id=:product_id',
                'params' => array(
                        ':order_id' => $_POST['order_id'],
                        'product_id' => $_POST['product_id']
                    )
            ));
        /*print_r($orderproduct);die;*/
        OrderProduct::model()->updateByPk(
            $orderproduct['id'],array(
                'actual_price'          => $_POST['actual_price'],
                'unit'                  => $_POST['amount'],
                'actual_unit_cost'      => $_POST['actual_unit_cost'],
                'actual_service_ratio'  => $_POST['actual_service_ratio'],
                'remark'                => $_POST['remark'] ));
    }

    public function actionUpdatehost()
    {
        /*$_POST['order_id'] = 68;
        $_POST['product_id'] = 36;*/
        
        OrderProduct::model()->updateByPk($_POST['order_product_id'],array('product_id'=>$_POST['product_id'],'actual_price'=>$_POST['actual_price'],'actual_unit_cost'=>$_POST['actual_unit_cost']));
    }

    public function actionSavedec()
    {

        $data = array(
            'account_id' => $_POST['account_id'],
            'order_id' => $_POST['order_id'],
            'product_id' => $_POST['product_id'],
            'name' => $_POST['name'],
            'actual_price' => $_POST['actual_price'],
            'unit' => $_POST['unit'],
            'amount' => $_POST['amount'],
            'actual_unit_cost' => $_POST['actual_unit_cost'],
            'update_time' => $_POST['update_time'],
            'actual_service_ratio' => $_POST['actual_service_ratio'],
            'remark' => $_POST['remark']
        );

        $supplier = Supplier::model()->find(array(
            "condition" => "account_id = :account_id && type_id = :type_id",
            "params" => array(":account_id" => $data['account_id'] , ':type_id' => 20),
        ));


        $supplierproduct= new SupplierProduct;  
        /*print_r($data);die;*/

        $supplierproduct->account_id = $data['account_id'];
        $supplierproduct->supplier_id = $supplier['id'];
        $supplierproduct->supplier_type_id = 20;
        $supplierproduct->standard_type = 1;
        $supplierproduct->name = $data['name'];
        $supplierproduct->category = 2;
        $supplierproduct->unit_price = $data['actual_price'];
        $supplierproduct->unit = $data['unit'];
        $supplierproduct->unit_cost = $data['actual_unit_cost'];
        $supplierproduct->service_charge_ratio = $data['actual_service_ratio'];
        $supplierproduct->ref_pic_url = 000;
        $supplierproduct->description = $data['remark'];
        $supplierproduct->update_time = $data['update_time'];       

        $supplierproduct->save();
        /*var_dump($data);die;*/

        $supplier_product = SupplierProduct::model()->find(array(
            "condition" => "update_time = :update_time && supplier_id = :supplier_id",
            "params" => array(":update_time" => $data['update_time'] , ':supplier_id' => $supplier['id']),
        ));
        
        $orderproduct= new OrderProduct;  
        /*print_r($data);die;*/
        $orderproduct->account_id = $data['account_id'];
        $orderproduct->order_id = $data['order_id'];
        $orderproduct->product_id = $supplier_product['id'];
        $orderproduct->actual_price = $data['actual_price'];
        $orderproduct->unit = $data['amount'];
        $orderproduct->actual_unit_cost = $data['actual_unit_cost'];
        $orderproduct->update_time = $data['update_time'];
        $orderproduct->actual_service_ratio = $data['actual_service_ratio'];

        $orderproduct->save();
    }

    public function actionUpdatedec()
    {
        $data1 = array(
            'account_id' => $_POST['account_id'],
            'order_id' => $_POST['order_id'],
            'product_id' => $_POST['product_id'],
            'actual_price' => $_POST['actual_price'],
            'unit' => $_POST['amount'],
            'actual_unit_cost' => $_POST['actual_unit_cost'],
            'update_time' => $_POST['update_time'],
        );

        OrderProduct::model()->updateAll($data1,'order_id=:order_id && product_id=:product_id',array('order_id'=>$_POST['order_id'],'product_id'=>$_POST['product_id']));


        $data2 = array(
            'name' => $_POST['name'],
            'unit_price' => $_POST['actual_price'],
            'unit' => $_POST['unit'],
            'unit_cost' => $_POST['actual_unit_cost'],
            'update_time' => $_POST['update_time'],
            'remark' => $_POST['remark']
        );

        SupplierProduct::model()->updateAll($data2,'id=:id',array('id'=>$_POST['product_id']));

    }

    public function actionDeltp()
    {
        OrderProduct::model()->deleteAll('order_id=:order_id && product_id=:product_id',array('order_id'=>$_POST['order_id'],'product_id'=>$_POST['product_id']));
    }    

    public function actionDelhost()
    {
        OrderProduct::model()->deleteByPk($_POST['order_product_id']);
    }    

    public function actionChooseChannel()
    {
        $channel = SupplierProduct::model()->findAll(array(
            'condition' => ''
        ));
    }

    public function actionFeast()
    {
        
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
        $accountId = $_SESSION['account_id'];
        $supplierproductForm = new SupplierProductForm();
        $supplierproducts = $supplierproductForm->SupplierProductIndex3($accountId);
        //var_dump($supplierproducts);die;
        $this->render("feast",array(
            "arr_feast" => $supplierproducts,
            'feast_data' => $feast_data,
            'feast_bill' => $feast_bill,
        ));
    }

    public function actionUpdate_order_product()
    {
        $post = json_decode(file_get_contents('php://input'));

        OrderProduct::model()->deleteAll('order_id=:order_id',array(':order_id'=>$post->orderid));
        $staff = Staff::model()->findByPk($post->token);

        $account_id = $staff['account_id'];

        $supplier_product_id = array();

        foreach ($post->product as $key => $value) {
            $supplier_product_id[] = $value ->productid;
        }

        $criteria = new CDbCriteria; 
        $criteria ->addInCondition("id",$supplier_product_id);

        $supplier_product = SupplierProduct::model()->findAll($criteria);

        foreach ($post->product as $key1 => $value1) {
            $data = new OrderProduct;
            $data ->account_id = $account_id;
            $data ->order_id = $post->orderid;
            foreach ($supplier_product as $key2 => $value2) {
                if ($value1->productid == $value2['id']) {
                    $data ->actual_unit_cost = $value2['unit_cost'];
                }
            }
            $data ->product_type = $value1->producttype;
            $data ->product_id = $value1->productid;
            $data ->unit = $value1->amount;
            $data ->actual_price = $value1->unitprice;
            $data ->sort = $value1->sort;

            $data->save();
        }; 
    }

    public function actionAdd_to_order()
    {   
        $code = 0;
        $result['msg'] = "";
        $post = json_decode(file_get_contents('php://input'));
        // print_r($post->token);die;
        try {
            $staff = Staff::model()->find(array(
                "condition" => "id = :id",
                "params"    => array(
                    ":id" => $post->token,
            )));

            $account_id = $staff['account_id'];

            $supplier_product_id = array();

            foreach ($post->product as $key => $value) {
                $supplier_product_id[] = $value ->productid;
            }

            $criteria = new CDbCriteria; 
            $criteria ->addInCondition("id",$supplier_product_id);
            
            $supplier_product = SupplierProduct::model()->findAll($criteria);

            foreach ($post->product as $key1 => $value1) {
                $data = new OrderProduct;
                foreach ($supplier_product as $key2 => $value2) {
                    if ($value1->productid == $value2['id']) {
                        $data ->actual_unit_cost = $value2['unit_cost'];
                    }
                }
                $data = new OrderProduct;
                $data ->account_id = $account_id;
                $data ->order_id = $post->orderid;
                $data ->product_type = $value1->producttype;
                $data ->product_id = $value1->productid;
                $data ->unit = $value1->amount;
                $data ->actual_price = $value1->unitprice;
                $data ->sort = 1;

                $data->save();
            }
            $code = 1;
        } catch (Exception $e) {
            $result['msg'] = $e;
        }
        $result['code'] = $code;
        echo json_encode($result);
    }

    public function actionAdd_temporary()
    {   
        $code = 0;
        $result['msg'] = "";
        // print_r($post->token);die;
        try {
            $post = json_decode(file_get_contents('php://input'));
            $staff = Staff::model()->find(array(
                "condition" => "id = :id",
                "params"    => array(
                    ":id" => $post->token,
            )));
            $account_id = $staff['account_id'];

            $data = new SupplierProduct;
            $data ->account_id = $account_id;
            $data ->supplier_id = $post->supplierid;
            $data ->supplier_type_id = $post->suppliertypeid;
            $data ->decoration_tap = $post->decorationtap;
            $data ->standard_type = 1;
            $data ->name = $post->name;
            $data ->cotegory = 2;
            $data ->unit_price = $post->unitprice;
            $data ->unit_cost = $post->unit_cost;
            $data ->unit = $post->unit;
            $data ->service_charge_ratio = 0;
            $data ->ref_pic_url = $post->refpicurl;
            $data ->description = $post->description;

            $data->save();            
            $product_id = $data->attributes['id'];

            $data = new OrderProduct;     
            $data ->account_id = $account_id;
            $data ->order_id = $post->orderid;
            $data ->product_type = 0;
            $data ->product_id = $product_id;
            $data ->unit = $post->amount;
            $data ->actual_price = $post->unitprice;
            $data ->sort = 1;

            $data->save();

            
            
            $code = 1;
        } catch (Exception $e) {
            $result['msg'] = $e;
        }
        $result['code'] = $code;
        echo json_encode($result);
    }

    public function actionAdd_order()
    {
        $post = json_decode(file_get_contents('php://input'));
        $code = 0;
        $result['msg'] = "";
        // try {
            $staff = Staff::model()->find(array(
                "condition" => "id = :id",
                "params"    => array(
                    ":id" => $post->token,
            )));

            $account_id = $staff['account_id'];

            $data = new Order;
            $data ->account_id = $account_id;
            $data ->designer_id = $staff['id'];
            $data ->planner_id = $staff['id'];
            $data ->adder_id = $staff['id'];
            $data ->staff_hotel_id = $post->hotelid;
            $data ->order_name = $post->groomname."&".$post->bridename;
            $data ->order_type = 2;
            $data ->order_date = $post->orderdate;
            $data ->order_status = 1;
            $data ->other_discount = 10;
            $data ->feast_discount = 10;
            $data ->cut_price = 0;

            $data->save();
            $order_id = $data->attributes['id'];

            $data = new OrderWedding;
            $data ->account_id = $account_id;
            $data ->order_id = $order_id;
            $data ->groom_name = $post->groomname;
            $data ->groom_phone = $post->groomtelephone;
            $data ->bride_name = $post->bridename;
            $data ->bride_phone = $post->bridetelephone;
            $data ->contact_name = $post->linkmanname;
            $data ->contact_phone = $post->linkmantelephone;
            
            $data->save();

            //复制自self actioinAdd_to_order()    有改动，注释       ////////////////
            

            $supplier_product_id = array();

            foreach ($post->product as $key => $value) {
                $supplier_product_id[] = $value ->productid;
            }

            $criteria = new CDbCriteria; 
            $criteria ->addInCondition("id",$supplier_product_id);
            
            $supplier_product = SupplierProduct::model()->findAll($criteria);

            foreach ($post->product as $key1 => $value1) {
                $data = new OrderProduct;
                foreach ($supplier_product as $key2 => $value2) {
                    if ($value1->productid == $value2['id']) {
                        $data ->actual_unit_cost = $value2['unit_cost'];
                    }
                }
                $data ->account_id = $account_id;
                $data ->order_id = $order_id;//新建的订单
                $data ->product_type = $value1->producttype;
                $data ->product_id = $value1->productid;
                $data ->unit = $value1->amount;
                $data ->actual_price = $value1->unitprice;
                $data ->sort = $value1->sort;//原是固定1，这里的情况有值

                $data->save();
            }
            $code = 1;
        // } catch (Exception $e) {
        //     $result['msg'] = $e;
        // }
        $result['code'] = $code;
        echo json_encode($result);
    }

}
