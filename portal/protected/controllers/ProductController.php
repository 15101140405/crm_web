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
        $supplierId = $_GET['supplier_id'];
        $accountId = $this->getAccountId();

        $productForm = new ProductForm();
        $products = $productForm->getProductList($supplierId);

        $this->render("list",array(
            "arr" => $products,
            "supplier_id" => $supplierId,
            ));
    }

    /**
     * 产品添加
     *
     */
    public function actionAdd()
    {
        $this->render("add",array(

            ));
    }

    /**
     * 产品编辑
     *
     */
    public function actionEdit()
    {
        $productId = $_GET['product_id'];
        $productForm = new ProductForm();
        $productInfo = $productForm->productEdit($productId);
        echo json_encode($productInfo);
    }

    /**
     * 产品保存
     *
     */

    public function actionSave(){
        $productId = $_GET['product_id'];
        // echo $_POST['supplier_id'];die;
        $productForm = new ProductForm();
        if(is_numeric($productId)){//编辑
            $post['account_id']  =  $this->getAccountId();
            $post['supplier_id']     = $_POST['supplier_id'];
            // $post['type_id']     = $_POST['type_id'];
            $post['name']      = $_POST['na'];
            // $post['category']      = $_POST['category'];
            $post['unit_price']      = $_POST['price'];
            $post['unit']      = $_POST['unit'];
            $post['unit_cost']      = $_POST['cost'];
            // $post['service_charge_ratio']      = $_POST['service_charge_ratio'];
            // $post['ref_pic_url']      = $_POST['ref_pic_url'];
            // $post['description']      = $_POST['description'];
            $post['update_time']      = time();

            $arr = $productForm->productUpdate($productId,$post);
            echo json_encode($arr);

        }else{//新增添

            $post['account_id']  =  $this->getAccountId();
            $post['supplier_id'] = $_POST['supplier_id'];
            $supplier = Supplier::model()->findByPk($_POST['supplier_id']);
            $post['supplier_type_id'] = $supplier['type_id'];
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
    }
    /**
     * 产品删除
     *
     */
    public function actionDelete()
    {
        $productId = $_GET['product_id'];
        if(is_numeric($productId)){//删除
            $productForm = new ProductForm();
            $post['account_id'] = $this->getAccountId();
            $result = $productForm->productDelete($productId,$post['account_id']);
            echo json_encode($result);
        }
    } 

    public function actionStore()
    {
        $this->render('store');
    }

}
