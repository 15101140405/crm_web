<?php
include_once('../library/WPRequest.php');
class PlanController extends InitController
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
    public function actionSave()
    {
        // $productId = $_GET['product_id'];
        $WeddingForm = new WeddingForm();
        //新添加订单
        // $post['order_date'] = $_POST['order_date'];
        // $post['order_type'] = $_POST['order_type'];
        // $post['planner_id'] = $_POST['planner_id'];
        // $post['staff_hotel_id'] = $_POST['staff_hotel_id'];
        // $accountId = $this->getAccountId();
        $post['account_id'] = $this->getAccountId();
        $post['expect_table'] = $_POST['expect_table'];
        // $post['account'] = $_POST['expect_table'];
        $post['Order_id'] = '3';
        $post['designer_id'] = '0';
        $post['Feast_Discount'] = '0.9';
        $post['Wedding_Discount'] = '0.2';
        $post['update_time']      = date('Y-m-d');
        // $post['order_time'] = $_POST['order_time'];
        // var_dump($post);
        $arr = $WeddingForm->weddingInsert($post);
        echo json_encode($arr);
    }
    public function actionList()
    {
        $this->render("/order/my");
    }

    public function actionCreate()
    {
        $this->render("create");
    }

    public function actionCustomerName()
    {
        $this->render("customerName");
    }

    public function actionUpdateCustomerName()
    {
        // var_dump($_POST);
        $order_id = $_GET['order_id'];
        $OrderWeddingForm = new OrderWeddingForm();
        $post['account_id']  =  $_SESSION['account_id'];
        $post['order_id'] = $order_id;
        $post['groom_name'] = $_POST['groom_name'];
        $post['groom_phone'] = $_POST['groom_phone'];
        $post['groom_wechat'] = $_POST['groom_wechat'];
        $post['groom_qq'] = $_POST['groom_qq'];
        $post['bride_name'] = $_POST['bride_name'];
        $post['bride_phone'] = $_POST['bride_phone'];
        $post['bride_wechat'] = $_POST['bride_wechat'];
        $post['bride_qq'] = $_POST['bride_qq'];
        $post['contact_name'] = '1';
        $post['contact_phone'] = '1';
        $post['planner_id'] = $_SESSION['userid'];
        $post['designer_id'] = $_SESSION['userid'];
        $post['update_time'] = time();
  
        $arr = $OrderWeddingForm->OrderWeddingInsert($post,$order_id);

        Order::model()->updateByPk($order_id,array('order_status'=>'1','order_name'=>$_POST['groom_name'].'&'.$_POST['bride_name'],'planner_id'=>$_SESSION['userid'],'designer_id'=>$_SESSION['userid']));

        echo json_encode($arr);
    }

    public function actionLinkmanInfo()
    {
        $this->render("linkmanInfo");
    }

    public function actionUpdatelinkman()
    {
        $order_id = $_GET['order_id'];
        $OrderWeddingForm = new OrderWeddingForm();
        $post['contact_name'] = $_POST['link_name'];
        $post['contact_phone'] = $_POST['link_phone'];
        $arr = $OrderWeddingForm->linkManUpdate($post,$order_id);
        echo json_encode($arr);
    }

    public function actionDetail()
    {
        $orderId = Yii::app()->getRequest()->getQuery('order_id');
        $orderForm = new OrderForm();
        $orderData = $orderForm -> getOrderDetail($orderId);
        $this->render("detail",array(
            "arr" => $orderData,
        ));
    }

    public function actionDetailInfo()
    {
        /*$this->render("detailInfo");*/
        $orderId   = $_GET['order_id'];

        $orderData = Order::model()->find(array(

            "condition" => "id=:id",
            "params"    => array( ":id" => $orderId),
                                               )
                                         );

        $orderweddingData = OrderWedding::model()->find(array(
            "condition"   => "order_id=:id",
            "params"      => array( ":id" => $orderId),
                                                             )
                                                       );
        $t1 = explode(" ",$orderData['order_date']);
        $date = $t1[0];
        $start_time = $t1[1];
        $t2 = explode(" ",$orderData['end_time']);
        $end_time = $t2[1];

        $detailInfoData=array(
            /*'expect_tables' => $orderweddingData['expect_table_count'],*/
            'date'          => $date,
            'start_time'    => $start_time,
            'end_time'      => $end_time,
            'boy_name'      => $orderweddingData['groom_name'],
            'boy_tele'      => $orderweddingData['groom_phone'],
            'boy_wxid'      => $orderweddingData['groom_wechat'], //微信号
            'boy_qq'        => $orderweddingData['groom_qq'],
            'girl_name'     => $orderweddingData['bride_name'],
            'girl_tele'     => $orderweddingData['bride_phone'],
            'girl_wxid'     => $orderweddingData['bride_wechat'], //微信号
            'girl_qq'       => $orderweddingData['bride_qq'],
            );

        $this->render("detailInfo",array(
            "arr"   =>   $detailInfoData,
                                        )
                     );
    }

    public function actionSelectDesigner()
    {
        $this->render("selectDesigner");
    }

    public function actionBill()
    {
        $orderId = $_GET['order_id'];
        $supplier_product_id = array();
        $wed_feast = array();
        $arr_wed_feast = array();
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
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']),
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
                $arr_light_total['total_price'] += $light[$key]['actual_price']*$light[$key]['unit'];;
                $arr_light_total['total_cost'] +=$light[$key]['actual_unit_cost']*$light[$key]['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];
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
                $arr_video_total['total_price'] += $video[$key]['actual_price']*$video[$key]['unit'];;
                $arr_video_total['total_cost'] +=$video[$key]['actual_unit_cost']*$video[$key]['unit'];
            }           
            $arr_video_total['gross_profit']=$arr_video_total['total_price']-$arr_video_total['total_cost'];
            $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];
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
                $arr_host_total['total_price'] += $host[$key]['actual_price']*$host[$key]['unit'];;
                $arr_host_total['total_cost'] +=$host[$key]['actual_unit_cost']*$host[$key]['unit'];
            }        
            $arr_host_total['gross_profit']=$arr_host_total['total_price']-$arr_host_total['total_cost'];
            $arr_host_total['gross_profit_rate']=$arr_host_total['gross_profit']/$arr_host_total['total_price'];
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
                $arr_camera_total['total_price'] += $camera[$key]['actual_price']*$camera[$key]['unit'];;
                $arr_camera_total['total_cost'] +=$camera[$key]['actual_unit_cost']*$camera[$key]['unit'];
            }           
            $arr_camera_total['gross_profit']=$arr_camera_total['total_price'];-$arr_camera_total['total_cost'];
            $arr_camera_total['gross_profit_rate']=$arr_camera_total['gross_profit']/$arr_camera_total['total_price'];
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
                $arr_photo_total['total_price'] += $photo[$key]['actual_price']*$photo[$key]['unit'];;
                $arr_photo_total['total_cost'] +=$photo[$key]['actual_unit_cost']*$photo[$key]['unit'];
            }           
            $arr_photo_total['gross_profit']=$arr_photo_total['total_price'];-$arr_photo_total['total_cost'];
            $arr_photo_total['gross_profit_rate']=$arr_photo_total['gross_profit']/$arr_photo_total['total_price'];
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
                $arr_makeup_total['total_price'] += $makeup[$key]['actual_price']*$makeup[$key]['unit'];;
                $arr_makeup_total['total_cost'] +=$makeup[$key]['actual_unit_cost']*$makeup[$key]['unit'];
            }           
            $arr_makeup_total['gross_profit']=$arr_makeup_total['total_price'];-$arr_makeup_total['total_cost'];
            $arr_makeup_total['gross_profit_rate']=$arr_makeup_total['gross_profit']/$arr_makeup_total['total_price'];
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
                $arr_other_total['total_price'] += $other[$key]['actual_price']*$other[$key]['unit'];;
                $arr_other_total['total_cost'] +=$other[$key]['actual_unit_cost']*$other[$key]['unit'];
            }           
            $arr_other_total['gross_profit']=$arr_other_total['total_price'];-$arr_other_total['total_cost'];
            $arr_other_total['gross_profit_rate']=$arr_other_total['gross_profit']/$arr_other_total['total_price'];
        }

        /*print_r($arr_makeup_total);*/



        /*********************************************************************************************************************/
        /*计算人员部分总价*/
        /*********************************************************************************************************************/
        $arr_service_total = array(
            'total_price' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        if(!empty($arr_host_total)){
            $arr_service_total['total_price'] += $arr_host_total['total_price'];
            $arr_service_total['gross_profit'] += $arr_host_total['gross_profit'];
        }

        if(!empty($arr_camera_total)){
            $arr_service_total['total_price'] += $arr_camera_total['total_price'];
            $arr_service_total['gross_profit'] += $arr_camera_total['gross_profit'];
        }

        if(!empty($arr_photo_total)){
            $arr_service_total['total_price'] += $arr_photo_total['total_price'];
            $arr_service_total['gross_profit'] += $arr_photo_total['gross_profit'];
        }

        if(!empty($arr_makeup_total)){
            $arr_service_total['total_price'] += $arr_makeup_total['total_price'];
            $arr_service_total['gross_profit'] += $arr_makeup_total['gross_profit'];
        }

        if(!empty($arr_other_total)){
            $arr_service_total['total_price'] += $arr_other_total['total_price'];
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
                $arr_decoration_total['total_price'] += $decoration[$key]['actual_price']*$decoration[$key]['unit'];;
                $arr_decoration_total['total_cost'] +=$decoration[$key]['actual_unit_cost']*$decoration[$key]['unit'];
            }           
            $arr_decoration_total['gross_profit']=$arr_decoration_total['total_price'];-$arr_decoration_total['total_cost'];
            $arr_decoration_total['gross_profit_rate']=$arr_decoration_total['gross_profit']/$arr_decoration_total['total_price'];
        }

        /*print_r($arr_makeup_total);*/
        

        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_order_total = array(
            'total_price' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        if(!empty($arr_wed_feast)){
            $arr_order_total['total_price'] += $arr_wed_feast['total_price'];
            $arr_order_total['gross_profit'] += $arr_wed_feast['gross_profit'];
        }

        if(!empty($arr_video)){
            $arr_order_total['total_price'] += $arr_video_total['total_price'];
            $arr_order_total['gross_profit'] += $arr_video_total['gross_profit'];
        }

        if(!empty($arr_light)){
            $arr_order_total['total_price'] += $arr_light_total['total_price'];
            $arr_order_total['gross_profit'] += $arr_light_total['gross_profit'];
        }

        if(!empty($arr_service_total)){
            $arr_order_total['total_price'] += $arr_service_total['total_price'];
            $arr_order_total['gross_profit'] += $arr_service_total['gross_profit'];
        }

        if(!empty($arr_decoration_total)){
            $arr_order_total['total_price'] += $arr_decoration_total['total_price'];
            $arr_order_total['gross_profit'] += $arr_decoration_total['gross_profit'];
        }

        $arr_order_total['gross_profit_rate'] = $arr_order_total['gross_profit']/$arr_order_total['total_price'];



        /*********************************************************************************************************************/
        /*取订单日期、名称……等数据*/
        /*********************************************************************************************************************/

        $criteria3 = new CDbCriteria; 
        $criteria3->addCondition("id=:id");
        $criteria3->params[':id']=$orderId; 
        $order_data = Order::model()->find($criteria3);
        /*print_r($order_data);*/

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
            "arr_order_total" => $arr_order_total,
            "arr_order_data" => $order_data,
            "designer" => $designer['name'],
            "planner" => $planer['name'],
            
        ));
    }

    public function actionChooseChannel()
    {

        $orderId   = $_GET['order_id'];
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
        

        
        $this->render("chooseChannel",  array(
            "arr"       =>  $list,
            "select"    =>  $select_reference));
        
    }

    public function actionSavechannel()//有变化才有post
    {
        if ( $_POST['select_id'] == 0){//有变化，选“无”时删除
        
        OrderProduct::model()->deleteAll(
            'order_id=:order_id && product_id=:product_id',array(
                'order_id'      =>  $_POST['order_id'],
                'product_id'    =>  $_POST['initial_id']));
        }

        if ($_POST['initial_id'] == 0 ){//有变化，原为空时新增
        
            $orderproduct= new OrderProduct;  
            /*print_r($data);die;*/
            $orderproduct->account_id = $_POST['account_id'];
            $orderproduct->order_id = $_POST['order_id'];
            $orderproduct->product_id = $_POST['select_id'];
            $orderproduct->actual_price = $_POST['actual_price'];
            $orderproduct->unit = $_POST['unit'];
            $orderproduct->actual_unit_cost = $_POST['actual_unit_cost'];
            $orderproduct->actual_service_ratio = $_POST['actual_service_ratio'];

            $orderproduct->save();
        }

        if ($_POST['initial_id'] != 0 && $_POST['select_id'] != 0){//有变化，初始和最终都不为“无”时修改

            $data = array(
                'product_id'    => $_POST['select_id'],
                'update_time'   => date('Y-m-d H:i:s'),
            );

            OrderProduct::model()->updateAll(
                $data,
                'order_id=:order_id && product_id=:product_id',
                array(
                    'order_id'      => $_POST['order_id'],
                    'product_id'    => $_POST['initial_id']));

        }

        
        
    }

    public function actionChooseDiscount()
    {
        $this->render("chooseDiscount");
    }
    public function actionFeast()
    {
        $this->render("feast");
    }

    public function actionWedinsert()
    {
        $payment= new Order;  

        $payment->account_id =$_SESSION['account_id'];
        $payment->designer_id =0;
        $payment->planner_id =0;
        $payment->adder_id =$_SESSION['userid'];
        $payment->staff_hotel_id =$_SESSION['staff_hotel_id'];
        $payment->order_name =$_POST['order_name'];
        $payment->order_type =$_POST['order_type'];
        $payment->order_date =$_POST['order_date'];
        $payment->order_time =$_POST['order_time'];
        $payment->end_time =$_POST['end_time'];
        $payment->order_status =0;
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

    public function actionWedDetailInsert()
    {
        $payment= new OrderWedding;  

        $payment->account_id =$_SESSION['account_id'];
        $payment->order_id =$_POST['order_id'];
        $payment->update_time =$_POST['update_time'];
        $payment->groom_name =$_POST['groom_name'];
        $payment->groom_phone =$_POST['groom_phone'];
        $payment->groom_wechat =$_POST['groom_wechat'];
        $payment->groom_qq =$_POST['groom_qq'];
        $payment->bride_name =$_POST['bride_name'];
        $payment->bride_phone =$_POST['bride_phone'];
        $payment->bride_wechat =$_POST['bride_wechat'];
        $payment->bride_qq =$_POST['bride_qq'];

        $payment->save();

        Order::model()->updateByPk($_POST['order_id'],array(
            'order_name'=>$_POST['groom_name'] .'&'. $_POST['bride_name'],
            'planner_id'=>$_SESSION['userid'],
            'designer_id'=>$_SESSION['userid'],
        ));
        $order = Order::model()->findByPk($_POST['order_id']);

        $staff = Staff::model()->findByPk($_SESSION['userid']);

        $html = '<div class="rich_media_content " id="js_content">    
                    <p>开单时间：'.$_POST['update_time'].'</p>
                    <p>订单类型：婚礼</p>
                    <p>新人姓名：'.$order['order_name'].'</p>
                    <p>开始时间：'.$order['order_date'].'</p>
                    <p>结束时间：'.$order['end_time'].'</p>
                    <p>统筹师：'.$staff["name"].'</p>
                    <p><br></p>
                </div>';
        
        $touser="@all";//你要发的人
        $toparty="";
        $totag="";
        $title="新客人进店了！";//标题
        $agentid=16;//应用
        $thumb_media_id="1VIziIEzGn_YvRxXK3OxPQpylPHLUnnA2gJ5_v8Cus2la7sjhAWYgzyFZhIVI9UoS6lkQ-ZLuMPZgP8BOVIS-XQ";
        $author="";
        $content_source_url="";
        $content = $html;
        $digest="描述";
        $show_cover_pic="";
        $safe="";
        $result=WPRequest::sendMessage_Mpnews(
                $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);
    }

    public function actionUpdatedetail()
    {
        OrderWedding::model()->updateAll(array(
            'groom_name'=>$_POST['groom_name'],
            'groom_phone'=>$_POST['groom_phone'],
            'groom_wechat'=>$_POST['groom_wechat'],
            'groom_qq'=>$_POST['groom_qq'],
            'bride_phone'=>$_POST['bride_phone'],
            'bride_wechat'=>$_POST['bride_wechat'],
            'bride_qq'=>$_POST['bride_qq']),'order_id=:order_id',array(':order_id'=>$_POST['order_id'])); 

        Order::model()->updateByPk($_POST['order_id'],array(
                'order_date'=>$_POST['order_date'],
                'end_time'=>$_POST['end_time']
            ));
    }


}
