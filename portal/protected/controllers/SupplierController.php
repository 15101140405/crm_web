<?php

class SupplierController extends InitController
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
     * 供应商列表
     *
     */
    public function actionList()
    {
        $accountId = $this->getAccountId();

        $supplierForm = new SupplierForm();
        $suppliers = $supplierForm->getSupplierList($accountId);

        $this->render("list", array(
            "arr" => $suppliers,
        ));

    }

    public function actionAdd()
    {
        $this->render("add",array(

        ));
    }

    public function actionChooseType()
    {
        $accountId = $this->getAccountId();
        $supplierForm = new SupplierForm();
        $choosetype = $supplierForm->chooseType();
        $this->render("chooseType",array(
            "types" => $choosetype,
            "accountId" => $accountId
        ));
    }
    public function actionEdit()
    {
        $supplierId = $_GET['supplier_id'];
        $supplierForm = new SupplierForm();
        $supplierInfo = $supplierForm->supplierEdit($supplierId) ;
        echo json_encode($supplierInfo);
    }
    public function actionSave(){
        $supplierId = $_GET['supplier_id'];
        $supplierForm = new SupplierForm();
        if(is_numeric($supplierId)){ //编辑

            $post['account_id']     = $this->getAccountId();
            $post['staff_name']     = $_POST['na'];
            $post['telephone']      = $_POST['phone'];
            //$post['contract_url']= $_POST['supplier_contract'];
            $post['contract_url']= 1;
            $post['type_id']    =  $_POST['supplier_type_id'];
            $arr = $supplierForm->supplierUpdate($supplierId,$post);
            echo  json_encode($arr);
            //  echo  json_encode($_POST);
        }else{ //新增
            $post['account_id'] = $this->getAccountId();
            $post['staff_name'] = $_POST['na'];
            $post['telephone']  = $_POST['phone'];
            $post['type_id'] = $_POST['supplier_type_id'];
            $arr = $supplierForm->supplierInsert($post);
            echo  json_encode($arr);
        }
    }
    public function actionDelete()
    {
        $supplierId = $_GET['supplier_id'];
        if (is_numeric($supplierId)) { //删除
            $supplierForm = new SupplierForm();
            $post['account_id'] = $this->getAccountId();
           $result =  $supplierForm->supplierDelete($supplierId,$post['account_id']);
            echo json_encode($result);
        }
    }
}
