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
        $accountId = $_SESSION['account_id'];
        $supplier_type_id = $_GET['supplier_type'];
        $supplierForm = new SupplierForm();
        $suppliers = $supplierForm->getSupplierList($accountId,$supplier_type_id);

        $this->render("list", array(
            "arr" => $suppliers,
        ));

    }

    public function actionAdd()
    {
        $staff = array();
        if($_GET['edit_supplier_id'] == ""){
            $this->render("add");
        }else{
            $supplier = Supplier::model()->findByPk($_GET['edit_supplier_id']);
            $staff = Staff::model()->findByPk($supplier['staff_id']);
            $this->render("add",array(
                    'staff' => $staff
                ));
        }
        
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

    public function actionInsert(){
        //新增供应商对应的员工
        $admin=new Staff;
        $admin->account_id=$_SESSION['account_id'];
        $admin->name=$_POST['na'];
        $admin->telephone=$_POST['phone'];
        $admin->department_list="[4]";
        $admin->update_time=$_POST['update_time'];
        $admin->save();
        //查找新增的员工ID
        $staff=Staff::model()->find(array(
                'condition' => 'name=:name && telephone=:telephone && update_time=:update_time',
                'params' => array(
                        ':name' => $_POST['na'],
                        ':telephone' => $_POST['phone'],
                        ':update_time' => $_POST['update_time']
                    )
            ));

        //新增供应商
        $admin1=new Supplier;
        $admin1->account_id=$_SESSION['account_id'];
        $admin1->type_id=$_POST['type_id'];
        $admin1->staff_id=$staff['id'];
        $admin1->update_time=$_POST['update_time'];
        $admin1->save();
    }

    public function actionEdit()
    {
        $supplier = Supplier::model()->findByPk($_POST['supplier_id']);
        Staff::model()->updateByPk($supplier['staff_id'],array('name'=>$_POST['na'],'telephone'=>$_POST['phone']));
    }

    public function actionDel()
    {
        $supplier = Supplier::model()->findByPk($_POST['supplier_id']);
        Staff::model()->deleteByPk($supplier['staff_id']);
        Supplier::model()->deleteByPk($_POST['supplier_id']);
    }
}
