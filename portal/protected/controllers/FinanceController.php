<?php
include_once('../library/WPRequest.php');

class FinanceController extends InitController
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

    public function actionFinanceData()
    {
        $orderId = $_GET['order_id'];
        /*********************************************************************************************************************/
        /*找 当订单内 所有产品的 供应商名 ，  把供应商名 存入对应的每一个 order_product 表记录*/
        /*********************************************************************************************************************/
        $order_product_data_t = OrderProduct::model()->findAll(array(
            "condition" => "order_id = :order_id",
            "params" => array(":order_id" => $orderId),
        ));
        /*var_dump($order_product_data);die;*/

        $supplier_id = array();
        $OrderProductData = array();
        $order_product_data = array();
        foreach ($order_product_data_t as $key => $value) {

            $supplier_product = SupplierProduct::model()->find(array(
                "condition" => "id = :id",
                "params" => array(":id" => $value['product_id']),
            ));

            $supplier = Supplier::model()->find(array(
                "condition" => "id = :id",
                "params" => array(":id" => $supplier_product['supplier_id']),
            ));

            $staff = Staff::model()->find(array(
                "condition" => "id = :id",
                "params" => array(":id" => $supplier['staff_id']),
            ));

            $item = array();
            $item['id'] = $value['id'];
            $item['order_id'] = $value['order_id'];   
            $item['product_id'] = $value['product_id']; 
            $item['actual_price'] = $value['actual_price']; 
            $item['amount'] = $value['unit']; 
            $item['actual_unit_cost'] = $value['actual_unit_cost']; 
            $item['actual_service_ratio'] = $value['actual_service_ratio']; 
            $item['supplier_id'] = $supplier_product['supplier_id'];
            $item['supplier_name'] = $staff['name'];
            $item['supplier_type_id'] = $supplier['type_id'];
            $item['product_name'] = $supplier_product['name'];
            $item['unit'] = $supplier_product['unit'];
            $order_product_data[]=$item;
        };
        /*********************************************************************************************************************/
        /*找到所有supplier_id,存入一个supplier_id不重复的数组*/
        /*********************************************************************************************************************/
 
        $supplierId =array();
        $supplierId[0]['supplier_id'] = $order_product_data[0]['supplier_id'];
        $supplierId[0]['supplier_name'] = $order_product_data[0]['supplier_name'];
        $supplierId[0]['supplier_type_id'] = $order_product_data[0]['supplier_type_id'];

        $i = 0 ;
        foreach ($order_product_data as $key => $value) {

            $m=0;
            foreach ($supplierId as $key => $value1) {
                if($value1['supplier_id'] == $value['supplier_id']){
                    $m = $m + 1 ;
                }
            }
            
            if ( $m == 0 ) {
                $t = $i + 1 ;
                /*print_r($t);die;*/
                $item = array();
                $supplierId[$t] = array();
                $item['supplier_id'] = $value['supplier_id'];
                $item['supplier_name'] = $value['supplier_name'];
                $item['supplier_type_id'] = $value['supplier_type_id'];
                $supplierId[$t] = $item;
                $i++;
            };
        };
        /*var_dump($order_product_data);die;*/

        /*********************************************************************************************************************/
        /*把所有 order_product 按 supplier_id 分组*/
        /*********************************************************************************************************************/
 
        $supplier_finance = array();
        $i = 0 ;
        foreach ($supplierId as $key => $value) {
            $supplier_finance[$key] = array();
            $supplier_finance[$key]['supplier_id'] = $value['supplier_id'];
            $supplier_finance[$key]['supplier_name'] = $value['supplier_name'];
            $supplier_finance[$key]['supplier_type_id'] = $value['supplier_type_id'];
            $supplier_finance[$key]['supplier_total_cost'] = 0;
            $supplier_finance[$key]['supplier_total_paid'] = 0;
            foreach ($order_product_data as $key1 => $value1) {
                if($value1['supplier_id'] == $value['supplier_id'] ){
                    /*print_r($value1['supplier_id']);print_r($value['supplier_id']);die;*/
                    /*$supplier_finance[$key]['product'] = array();
                    $supplier_finance[$key]['product'][$i] = array();*/
                    $supplier_finance[$key]['product'][$i]['product_id'] = $value1['product_id'];
                    $supplier_finance[$key]['product'][$i]['actual_price'] = $value1['actual_price'];
                    $supplier_finance[$key]['product'][$i]['amount'] = $value1['amount'];
                    $supplier_finance[$key]['product'][$i]['unit'] = $value1['unit'];
                    $supplier_finance[$key]['product'][$i]['actual_unit_cost'] = $value1['actual_unit_cost'];
                    $supplier_finance[$key]['product'][$i]['actual_service_ratio'] = $value1['actual_service_ratio'];
                    $supplier_finance[$key]['product'][$i]['product_name'] = $value1['product_name'];
                    $supplier_finance[$key]['supplier_total_cost'] += $value1['actual_unit_cost']*$value1['amount'];
                    $i++;
                }
            }
            $total_paid = SupplierPayment::model()->findAll(array(
                "condition" => "order_id = :order_id && supplier_id = :supplier_id",
                "params" => array(":order_id" => $orderId , ":supplier_id" => $value['supplier_id']),
            ));
            foreach ($total_paid as $key1 => $value1) {
                $supplier_finance[$key]['supplier_total_paid'] += $value1['money'];
            }
        }
        /*print_r($supplier_finance[3]);die;*/


        /*********************************************************************************************************************/
        /*计算订单总成本（应付账款）、已付账款、未付账款*/
        /*********************************************************************************************************************/

        //订单总成本（应付账款）
        $order_total = 0;
        foreach ($supplier_finance as $key => $value) {
            $order_total += $value['supplier_total_cost'];
        }

        //已付账款
        $order_paid = 0;
        $order_paid_all = SupplierPayment::model()->findAll(array(
            "condition" => "order_id = :order_id",
            "params" => array(":order_id" => $orderId)
        ));
        /*print_r($order_paid_all);*/
        foreach ($order_paid_all as $key => $value) {
            $order_paid += $value['money'];
        }


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


        /*********************************************************************************************************************/
        /*取收款记录数据*/
        /*********************************************************************************************************************/
        $order_cashier = Order::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $orderId)
        ));

        /*print_r($order_cashier);die;*/

        $financeData = array(
            'supplier_finance' => $supplier_finance,
            "order_data" => $order_data,
            "designer" => $designer['name'],
            "planner" => $planer['name'],   
            "order_total"  => $order_total ,  
            "order_paid" => $order_paid, 
            "order_cashier" => $order_cashier 
        );

        return $financeData;
    }

    public function actionWedCostCalculate()
    {
        
        $financeData = $this->actionFinanceData();

        $this->render("wedCostCalculate",array(
            'arr_supplier_finance' => $financeData['supplier_finance'], 
            "arr_order_data" => $financeData['order_data'],
            "designer" => $financeData['designer'],
            "planner" => $financeData['planner'],   
            "order_total"  => $financeData['order_total'] ,  
            "order_paid" => $financeData['order_paid'], 
            "order_cashier" => $financeData['order_cashier']  
        ));
    }

    public function actionCashier()
    {
        $orderId = $_GET['order_id'];
        if($_GET['type'] == "edit"){
            $data = OrderPayment::model()->findByPk($_GET['paymentId']);  
            /*echo "edit";die;  */  
            /*print_r($data);die;*/
            $this->render('cashier',array(
                'data' => $data,
                'type' => $_GET['type']
            ));
        }else{
            $data = array(
                'type' => '',
                'remarks' => 'ceshi',
                'time' => '',
                'money' => '',
                'way' => '',
                );
            /*echo "new";die;*/
            $this->render('cashier',array(
                'data' => $data,
                'type' => $_GET['type']
            )); 
        }
    }

    public function actionSave()
    {
        $payment_data = array();
        $payment_data['feast_deposit'] = $_POST['feast_deposit'];
        $payment_data['medium_term'] = $_POST['medium_term'];
        $payment_data['final_payments'] = $_POST['final_payments'];
        $order = Order::model()->updateByPk($_GET['order_id'],array('feast_deposit'=>$payment_data['feast_deposit'],'medium_term'=>$payment_data['medium_term'],'final_payments'=>$payment_data['final_payments']));        
    }

    public function actionPayment()
    {
        $payment = SupplierPayment::model()->findAll(array(
            "condition" => "order_id = :order_id && supplier_id = :supplier_id",
            "params" => array(":order_id" => $_GET['order_id'] , ":supplier_id" => $_GET['supplier_id'])
        ));

        $result = $this -> actionFinanceData();

        /*print_r($result);*/

        /*print_r($payment);die;*/
        $this -> render('payment',array(
            'payment' => $payment,
            'result' => $result,
        ));
    }

    public function actionPayinsert()
    {
        
        $payment_data['supplier_id']=$_POST['supplier_id'];
        $payment_data['order_id']=$_POST['order_id'];
        $payment_data['money']=$_POST['money'];
        $payment_data['update_time']=$_POST['update_time'];
        $payment_data['remarks']=$_POST['remarks'];  
        /*print_r($payment_data);die;*/

        $payment= new SupplierPayment;  

        $payment->supplier_id =$payment_data['supplier_id'];
        $payment->order_id =$payment_data['order_id'];
        $payment->money =$payment_data['money'];
        $payment->update_time =$payment_data['update_time'];
        $payment->remarks =$payment_data['remarks'];
        $payment->save();
        /*print_r("nihao");die;*/

        /*if($payment->save() > 0){echo"添加成功"; }else{echo"添加失败"; }*/
    }

    public function actionOrderpaymentupdate()
    {
        OrderPayment::model()->updateByPk($_POST['paymentId'],array('money'=>$_POST['payment'],'time'=>$_POST['payment_time'],'remarks'=>$_POST['remarks'],'way'=>$_POST['payment_way'],'type'=>$_POST['payment_type']));
        if($_POST['payment_type'] == "0"){Order::model()->updateByPk($_POST['order_id'],array('order_status'=>2));};
        if($_POST['payment_type'] == "1"){Order::model()->updateByPk($_POST['order_id'],array('order_status'=>3));};
        if($_POST['payment_type'] == "2"){Order::model()->updateByPk($_POST['order_id'],array('order_status'=>4));};
    }

    public function actionOrderpaymentdel()
    {
        OrderPayment::model()->deleteByPk($_POST['paymentId']);
    }


    public function actionOrder()
    {
        $staff_hotel_id = 1;
        $account_id = 1;

        $arr_order = Order::model()->findAll(array(
            "condition" => "staff_hotel_id=:staff_hotel_id",
            "params" => array(
                ":staff_hotel_id" => $staff_hotel_id
            )
        ));

        $this->render("order", array(
            "arr_order" => $arr_order,
            /*"my_order_wedding" => $my_order_wedding*/
        ));
    }

    public function actionIndexdata()
    {
        /*if($_POST['order_status'] == 0 || $_POST['order_status'] == 1){
            Order::model()->updateByPk( $_POST['order_id'] ,array('order_status'=>2,'feast_deposit'=>$_POST['payment'],'feast_deposit_way'=>$_POST['payment_way'],'feast_deposit_time'=>$_POST['payment_time'])); 

        }else if($_POST['order_status'] == 2){
            Order::model()->updateByPk($_POST['order_id'],array('order_status'=>3,'medium_term'=>$_POST['payment'],'medium_term_way'=>$_POST['payment_way'],'medium_term_time'=>$_POST['payment_time'])); 
        }else if($_POST['order_status'] == 3){
            Order::model()->updateByPk($_POST['order_id'],array('order_status'=>4,'final_payments'=>$_POST['payment'],'final_payments_way'=>$_POST['payment_way'],'final_payments_time'=>$_POST['payment_time'])); 
        }*/
        $payment = OrderPayment::model()->find(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                    ':order_id' => $_POST['order_id']
                    )));
        $order = Order::model()->find(array(
                'condition' => 'id=:id',
                'params' => array(
                    ':id' => $_POST['order_id']
                    )));
        $date = explode(' ', $order['order_date']);
        $hotel = StaffHotel::model()->find(array(
                'condition' => 'id=:id',
                'params' => array(
                    ':id' => $order['staff_hotel_id']
                    )));   
        $staff1 = Staff::model()->findByPK($order['designer_id']);
        $staff2 = Staff::model()->findByPK($order['planner_id']);
        if ($order['order_type'] == 1) {
            $order_type = "会议";
        } else {
            $order_type = "婚礼";
        }
        

        if (empty($payment)) {
            $touser = "@all";
            $toparty = "";
            $content = "成单了！[".$hotel['name']."]
订单类型：".$order_type."
日期：".$date[0]."
策划师：".$staff1['name']."
统筹师：".$staff2['name'];
            $corpid = "wxee0a719fd467c364";
            $corpsecret = "DQZtiEV2EqTf3_iLnxIzvi3aHie8Q8UWyNJSuDJfqymupa7_tQuTV-gmFNWN84Gb";
            WPRequest::sendMessage_Text($touser,$toparty,$content,$corpid,$corpsecret);

        }

        $payment= new OrderPayment;
        $payment->order_id=$_POST['order_id'];
        $payment->money=$_POST['payment'];
        $payment->time=$_POST['payment_time'];
        $payment->remarks=$_POST['remarks'];
        $payment->way=$_POST['payment_way'];
        $payment->type=$_POST['payment_type'];
        $payment->update_time=date('Y-m-d h:i:s',time());
        $payment->save();

        if($_POST['payment_type'] == 0){
            Order::model()->updateByPk($_POST['order_id'],array('order_status' => 2));
        }else if($_POST['payment_type'] == 1){
            Order::model()->updateByPk($_POST['order_id'],array('order_status' => 3));
        }else if($_POST['payment_type'] == 2){
            Order::model()->updateByPk($_POST['order_id'],array('order_status' => 4));
        }
        
    }

    public function actionUpdatedata()
    {
        OrderMerchandiser::model()->updateByPk($_POST['paymentId'],array('remarks'=>$_POST['remarks'],'payment'=>$_POST['payment'],'payment_time'=>$_POST['payment_time'],'payment_type'=>$_POST['payment_type'],'payment_way'=>$_POST['payment_way']));  
    }

    public function actionDeldata()
    {
        OrderMerchandiser::model()->deleteByPk($_POST['paymentId']);  
    }

    public function actionCashierList()
    {
        $payment = OrderPayment::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    )
            ));
        /*print_r($payment);die;*/

        $this->render('cashier_list',array(
                'payment' => $payment
            ));
    }
}