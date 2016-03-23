<?php

class SupplierselfController extends InitController
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

    public function actionOrder()
    {
        $user_id = 38 ;

        $arr_supplier = Supplier::model()->findAll(array(
            'condition' => 'staff_id=:staff_id',
            'params' => array(
                ':staff_id' => $user_id
            )
        ));

        $supplier_id = array();
        $supplier_id[0] = 0;
        $i=0;
        foreach ($arr_supplier as $key => $value) {
            if($supplier_id[$i] != $value['id']){
                $supplier_id[$i] = $value['id'];
                $t = $i++;
                $supplier_id[$i] = $supplier_id[$t];
            }
        }      


        $criteria = new CDbCriteria; 
        $criteria->addInCondition('supplier_id',$supplier_id);
        $arr_product = SupplierProduct::model()->findAll($criteria);

        $product_id = array();
        $product_id[0] = 0;
        $i=0;
        foreach ($arr_product as $key => $value) {

            $m=0;
            foreach ($product_id as $key => $value1) {
                if($value1 == $value['id']){
                    $m = $m + 1 ;
                }
            }

            if($m == 0){
                $product_id[$i] = $value['id'];
                $t = $i++;
                $product_id[$i] = $product_id[$t];
            }
        }
        /*print_r($product_id);die;*/
        $criteria = new CDbCriteria; 
        $criteria->addInCondition('product_id',$product_id);
        $arr_order_id = OrderProduct::model()->findAll($criteria);

        $order_id = array();
        $order_id[0] = 0;
        $i=0;
        foreach ($arr_order_id as $key => $value) {
            if($order_id[$i] != $value['order_id']){
                $order_id[$i] = $value['order_id'];
                $t = $i++;
                $order_id[$i] = $order_id[$t];
            }
        }

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition('id',$order_id);
        $arr_order = Order::model()->findAll($criteria1);


        $this->render("order", array(
            "arr_order" => $arr_order,
            /*"my_order_wedding" => $my_order_wedding*/
        ));
    }
    
    public function actionDetail()
    {
        $this->render('detail');
    }

    public function actionPrice()
    {
        $this->render('price_input');
    }
}
