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
        if(isset($_GET['account_id'])){
            Yii::app()->session['account_id']=$_GET['account_id'];  
            $company = StaffCompany::model()->findByPk($_SESSION['account_id']);   
        };
        /*if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){*///已登陆
            //echo '已登陆';
            /*$this->render('store');
        }else{*/ //未登录
            //echo '未登陆';
            $code = $_GET['code'];
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
            }
        /*};*/
    }

    public function actionSet_list()
    {
        $supplier_product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id = :account_id && supplier_type = :supplier_type && category = :category',
                'params' => array(
                        ':account_id' => $_SESSION['account_id'],
                        ':supplier_type' => 2,
                        ':category' => 2
                    )
            ));

        $product_data = array();

        foreach ($supplier_product as $key => $value) {
            $item = array();
            $criteria = new CDbCriteria; 
            $criteria->addCondition("img_type = :img_type && supplier_product_id = :supplier_product_id");    
            $criteria->params[':img_type']=1; 
            $criteria->params[':supplier_product_id']=$value['id'];  
            $ProductImg = ProductImg::model()->findAll($criteria);
            $item['name'] = $value['name'];
            $item['price'] = $value['unit_price'];
            $item['img_url'] = $ProductImg['img_url'];
            $item['unit'] = $value['unit'];
            $product_data[] = $item; 
        };

        $this->render('set_list',array(
            "product_data" => $product_data,
        ));

    }

    public function actionSet_detail()
    {
        $this->render('set_detail');
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
}
