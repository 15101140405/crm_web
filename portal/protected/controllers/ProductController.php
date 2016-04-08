<?php

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
                'condition' => 'account_id = :account_id && supplier_type_id = :supplier_type_id && category = :category',
                'params' => array(
                        ':account_id' => $_SESSION['account_id'],
                        ':supplier_type_id' => $_GET['supplier_type'],
                        ':category' => $_GET['category']
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
        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){//已登陆
            //echo '已登陆';
            $this->render('store');
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
                
                $this->render('store');
            }
        };
    }

}
