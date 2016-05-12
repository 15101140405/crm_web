<?php

include_once('../library/WPRequest.php');

class ProductController extends InitController
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

    /**
     * 产品列表
     *
     */
    public function actionList()
    {
        $product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id = :account_id && supplier_type_id = :supplier_type_id && category = :category && standard_type=:standard_type',
                'params' => array(
                        ':account_id' => $_SESSION['account_id'],
                        ':supplier_type_id' => $_GET['supplier_type'],
                        ':category' => $_GET['category'],
                        ':standard_type' => 0
                    )
            ));

        $supplier_product = array();
        foreach ($product as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['product_name'] = $value['name'];
            $t_supplier = Supplier::model()->findByPk($value['supplier_id']);
            $t_staff = Staff::model()->findByPk($t_supplier['staff_id']);
            $item['supplier_name'] = $t_staff['name'];
            $item['supplier_id'] = $value['supplier_id'];
            $supplier_product[] = $item;
        };
        /*print_r($supplier_product);die;*/
        $this->render("list",array(
                'supplier_product' => $supplier_product,
            ));
    }

    /**
     * 产品添加
     *
     */
    public function actionAdd()
    {
        if($_GET['product_id'] == ""){
            if($_GET['supplier_id'] == ""){
                $this->render("add");    
            }else{
                $supplier = Supplier::model()->findByPk($_GET['supplier_id']);
                $staff = Staff::model()->findByPk($supplier['staff_id']);
                $this->render("add",array(
                        'supplier_name' => $staff['name'],
                    )); 
            };
        }else{
            $supplier_product = SupplierProduct::model()->findByPk($_GET['product_id']);
            $supplier = Supplier::model()->findByPk($_GET['supplier_id']);
            $staff = Staff::model()->findByPk($supplier['staff_id']);
            $this->render("add",array(
                    'supplier_product' => $supplier_product,
                    'supplier_name' => $staff['name'],
                ));
        };
        
    }

    /**
     * 产品新增
     *
     */

    public function actionInsert(){
        $productForm = new ProductForm();
        $post['account_id']  =  $_SESSION['account_id'];
        $post['supplier_id'] = $_POST['supplier_id'];
        $post['supplier_type_id'] = $_POST['supplier_type_id'];
        $post['category'] = $_POST['category'];
        $post['name']      = $_POST['na'];
        $post['unit_price']      = $_POST['price'];
        $post['unit']      = $_POST['unit'];
        $post['unit_cost']      = $_POST['cost'];
        // $post['service_charge_ratio']      = $_POST['service_charge_ratio'];
        // $post['ref_pic_url']      = $_POST['ref_pic_url'];
        // $post['description']      = $_POST['description'];
        $post['update_time']      = $_POST['update_time'];
        $arr = $productForm->productInsert($post);
        echo json_encode($arr);
    }

    /**
     * 产品编辑
     *
     */

    public function actionEdit(){
        $productId = $_POST['product_id'];
        $productForm = new ProductForm();
        $post['supplier_id']     = $_POST['supplier_id'];
        $post['name']      = $_POST['na'];
        $post['unit_price']      = $_POST['price'];
        $post['unit']      = $_POST['unit'];
        $post['unit_cost']      = $_POST['cost'];
        // $post['service_charge_ratio']      = $_POST['service_charge_ratio'];
        // $post['ref_pic_url']               = $_POST['ref_pic_url'];
        // $post['description']               = $_POST['description'];
        $arr = $productForm->productUpdate($productId,$post);
        echo json_encode($arr);
    }

    /**
     * 产品删除
     *
     */
    public function actionDel()
    {
        SupplierProduct::model()->deleteByPk($_POST['product_id']);
    } 

    public function actionStore()
    {
        /*if(isset($_GET['account_id'])){
            Yii::app()->session['account_id']=$_GET['account_id'];  
            $company = StaffCompany::model()->findByPk($_SESSION['account_id']);   
        };*/
        /*if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){*///已登陆
            //echo '已登陆';
            /*$this->render('store');
        }else{*/ //未登录
            //echo '未登陆';
            /*$code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=product/store&code=';
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
                
                $this->render('store');
            }*/
        /*};*/
        Yii::app()->session['account_id']=$_GET['account_id'];
        Yii::app()->session['staff_hotel_id']=$_GET['staff_hotel_id'];
        
        if(isset($_COOKIE['userid'])){
            Yii::app()->session['userid']=$_COOKIE['userid'];
            $staff = Staff::model()->findByPk($_SESSION['userid']);
            $str =  rtrim($staff['department_list'], "]"); 
            $str =  ltrim($str, "[");
            $t = explode(",", $str);
            $user_type = 0; // 0-普通员工（能看订单、个人业绩）   1-管理层（能看订单统计、财务报告）
            foreach ($t as $key => $value){
                if($value == "6"){
                    $user_type++;
                };
            };
            $hotel = StaffHotel::model()->findByPk($_SESSION['staff_hotel_id']);

            $this->render('store',array(
                    'hotel_name' => $hotel['name'],
                    'user_type' => $user_type
                ));
        }else{
            $this->render('login');
        };
    }

    public function actionSetuserid()
    {
        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:telephone',
                'params' => array(
                        'telephone' => $_POST['phone']
                    )
            ));

        $cookie = new CHttpCookie('userid',$staff['id']);
        $cookie->expire = time()+60*60*24*30*12*100;  //有限期100年
        Yii::app()->request->cookies['userid']=$cookie;

        if(isset($_COOKIE['userid'])){
            echo 'success';
        }else{
            echo 'failed';
        }
    }

    public function actionSet_list()
    {
        $pricename = "unit_price";
        if ($_GET['from'] == "set") {//套系

            $pricename = "final_price";
            $table = "Wedding_set_img";
            $idname = "wedding_set_id";
            $list = Wedding_set::model()->findAll(array(
                    'condition' => 'staff_hotel_id = :staff_hotel_id && category = :category',
                    'params' => array(
                            ':staff_hotel_id' => $_SESSION['staff_hotel_id'],
                            ':category' => $_GET['category']
                        ),
                    'order' => $pricename
                ));

        } else {//婚宴，会议餐
        
            $table = "ProductImg";
            $idname = "supplier_product_id";
            $list = SupplierProduct::model()->findAll(array(
                    'condition' => 'account_id = :account_id && supplier_type_id = :supplier_type_id && category = :category',
                    'params' => array(
                            ':account_id' => $_SESSION['account_id'],
                            ':supplier_type_id' => $_GET['supplier_type_id'],
                            ':category' => $_GET['category']
                        ),
                    'order' => $pricename
                ));
        }

        $product_data = array();

        foreach ($list as $key => $value) {
            $item = array();
            $criteria = new CDbCriteria; 
            $criteria->addCondition("img_type = :img_type && ".$idname." = :id");    
            $criteria->params[':img_type']=1; 
            $criteria->params[':id']=$value['id'];  
            $ProductImg = $table::model()->find($criteria);
            $item['name'] = $value['name'];
            $item['price'] = $value[$pricename]."元/场";
            $item['unit'] = "";
            // print_r($ProductImg);die;
            if (!empty($ProductImg)) {
                $item['img_url'] = $ProductImg['img_url'];
            } else {
                $item['img_url'] = "../../crm_product_img/d32.jpg";
            }
            if ($_GET['from'] != "set") {
                $item['unit'] = $value['unit'];
                $item['price'] = $value[$pricename];
            }
            
            $item['id'] = $value['id'];
            $product_data[] = $item; 
        };

        $this->render('set_list',array(
            "product_data" => $product_data,
        ));

    }

    public function actionSet_detail()
    {
        $id=$_GET['product_id'];
        if ($_GET['from'] == "set") {//套系

            $pricename = "final_price";
            $table = "Wedding_set";
            $imgtable = "Wedding_set_img";
            $idname = "wedding_set_id";
            
        } else {//婚宴，会议餐
        
            $table = "SupplierProduct";
            $imgtable = "ProductImg";
            $idname = "supplier_product_id";
            
        }
        $supplier_product = $table::model()->find(array(
                'condition' => 'id = :id',
                'params' => array(
                        ':id' => $id
                    )
            ));

        $img = $imgtable::model()->findAll(array(
                'condition' => $idname.' = :id && img_type = :img_type',
                'params' => array(
                        ':id' => $id,
                        ':img_type' => 2
                    )
            ));


        $this->render('set_detail',array(
            "supplier_product"  => $supplier_product,
            "img"               => $img
        ));
    }

    public function actionSelectorder()
    {
        /*Yii::app()->session['userid']=100;*/
        $order = Order::model()->findAll(array(
                'condition' => 'planner_id = :planner_id || designer_id = :designer_id',
                'params' => array(
                        ':planner_id' => $_SESSION['userid'],
                        ':designer_id' => $_SESSION['userid'],
                    ),
                'order' => 'order_date DESC'
            ));
        /*print_r($order);die;*/
        $order_data = array();
        foreach ($order as $key => $value) {
            $item = array();
            if($_GET['category'] == $value['order_type']){
                $item['order_name'] = $value['order_name'];
                $item['order_type'] = $value['order_type'];
                $item['id'] = $value['id'];
                $t = explode(" ",$value['order_date']);
                $item['order_date'] = $t[0];
                $item['order_status'] = $value['order_status'];
                $staff = Staff::model()->findByPk($value['planner_id']);
                $item['planner_name'] = $staff['name'];
                $order_data[] = $item;
            };
        };

        $this->render("select_order",array(
                'order_data' => $order_data
            ));
    }

    public function actionCreateorder()
    {
        $this->render('create_order');
    }

    public function actionNeworder()
    {
        //存order表
        $payment= new Order;  

        $payment->account_id =$_SESSION['account_id'];
        $payment->designer_id =$_SESSION['userid'];
        $payment->planner_id =$_SESSION['userid'];
        $payment->adder_id =$_SESSION['userid'];
        $payment->staff_hotel_id =$_SESSION['staff_hotel_id'];
        $payment->order_name =$_POST['groom_name'];
        $payment->order_type =2;
        $payment->order_date =$_POST['order_date'];
        $payment->end_time =$_POST['end_time'];
        $payment->order_status =1;
        $payment->update_time =$_POST['update_time'];
        $payment->save();

        $order = Order::model()->find(array(
            'condition' => 'planner_id=:planner_id && update_time=:update_time',
            'params' => array(
                ':planner_id' =>$_SESSION['userid'],
                ':update_time' =>$_POST['update_time'],
            )
        ));

        //存order_wedding表
        $payment= new OrderWedding;  

        $payment->account_id =$_SESSION['account_id'];
        $payment->order_id =$order['id'];
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


        $hotel = StaffHotel::model()->findByPk($_SESSION['staff_hotel_id']);

        $staff = Staff::model()->findByPk($_SESSION['userid']);

        $date = explode(" ",$order['order_date']);
        $html = "";
        if($order['order_type'] == 2){
            $html = "新客人进店了[".$hotel['name']."] "."订单类型："."婚礼"."    "."日期：".$date[0]."       "."开单人（".$staff["name"].")";
        }else if($order['order_type'] == 1){
            $html = "新客人进店了[".$hotel['name']."] "."订单类型："."会议"."    "."日期：".$date[0]."       "."开单人（".$staff["name"].")";
        };
        

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
        //$result=WPRequest::sendMessage_Mpnews($touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);
        $result=WPRequest::sendMessage_Text($touser, $toparty, $content,$corpid,$corpsecret);
        //print_r($result);

        $wedding_set = Wedding_set::model()->findByPk($_POST['set_id']);
        $t1 = explode("/",$wedding_set['product_list']);
        $t2 = explode(",", $t1[0]);
        foreach ($t2 as $key => $value) {
            $product = explode("|", $value);
            $admin=new OrderProduct;         
            $admin->account_id=$_SESSION['account_id']; 
            $admin->order_id=$order['id'];
            $admin->product_id=$product[0]; 
            $admin->actual_price=$product[1]; 
            $admin->unit=$product[2]; 
            $admin->actual_unit_cost=$product[3]; 
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
        }

        Order::model()->updateByPk($order['id'],array('discount_range'=>$t1[2],'other_discount'=>$t1[1])); 

        echo $order['id'];
    }

    public function actionInsert_order_set()
    {
        $wedding_set = Wedding_set::model()->findByPk($_POST['set_id']);
        $t1 = explode("/",$wedding_set['product_list']);
        $t2 = explode(",", $t1[0]);
        foreach ($t2 as $key => $value) {
            $product = explode("|", $value);
            $admin=new OrderProduct;         
            $admin->account_id=$_SESSION['account_id']; 
            $admin->order_id=$_POST['order_id'];
            $admin->product_id=$product[0]; 
            $admin->actual_price=$product[1]; 
            $admin->unit=$product[2]; 
            $admin->actual_unit_cost=$product[3]; 
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
        }

        Order::model()->updateByPk($_POST['order_id'],array('discount_range'=>$t1[2],'other_discount'=>$t1[1])); 
    }
}
