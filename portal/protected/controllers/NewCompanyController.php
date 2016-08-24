<?php

include_once('../library/WPRequest.php');

class NewCompanyController extends InitController
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

    public function actionAddNewCompany()
    {
        $tar_order_id = 1148;
        //post结构
        $post = array(
                'company_name' => '测试新增',
                'hotel_list' => array(
                        0 => array('name'=>'分店1'),
                    ),
                'staff_list' => array(
                        0 => array('name'=>'小明', 'telephone' => '13456789023', 'password'=>'88888888', 'department_list'=>'[2,3,6]'),
                        1 => array('name'=>'小丽', 'telephone' => '13456789024', 'password'=>'88888888', 'department_list'=>'[2,3]'),
                    ),
            )

        // 1、新增一家公司
        $admin=new StaffCompany;
        $admin->name=$post['name'];
        $admin->update_time=date('y-m-d h:i:s',time());
        $admin->save();
        $account_id = $admin->attributes['id'];

        // 2、新增门店
        $first_hotel_id="";
        foreach ($post['hotel_list'] as $key => $value) {
            $admin=new StaffHotel;
            $admin->name=$value['name'];
            $admin->account_id=$value['account_id'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $hotel_id = $admin->attributes['id'];

            if($key == 0){$first_hotel_id=$hotel_id;};

        };

        // 3、新增员工 (设定了管理层)
        $new_staff_id = array();
        foreach ($post['staff_list'] as $key => $value) {
            $admin=new Staff;
            $admin->name=$value['name'];
            $admin->account_id=$account_id;
            $admin->telephone=$value['telephone'];
            $admin->department_list=$value['department_list'];
            $admin->password=$post['password'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $staff_id = $admin->attributes['id'];

            $new_staff_id[] = $staff_id;
        };

        // 4、把所有公共service_person，设为supplier
        $service_person = ServicePerson::model()->findAll(array(
                'conditon' => 'status=:status',
                'params' => array(
                        ':status' => 0,
                    ),
            ));
        foreach ($service_person as $key => $value) {
            $admin=new Supplier;
            $admin->account_id=$account_id;
            $admin->type_id=$value['service_type'];
            $admin->staff_id=$value['staff_id'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $supplier_id = $admin->attributes['id'];


            // 5、复制service_product
            $service_person_product = ServiceProduct::model()->findAll(array(
                    'conditon' => 'service_person_id=:spi',
                    'params' => array(
                            ':spi' => $value['id']
                        )
                ));
            foreach ($service_person_product as $key1 => $value1) {
                $admin=new SupplierProduct;
                $admin->account_id=$account_id;
                $admin->supplier_id=$supplier_id;
                $admin->service_product_id=$value1['id'];
                $admin->supplier_type_id=$value1['service_type'];
                $admin->dish_type=0;
                $admin->decoration_tap=0;
                $admin->standard_type=0;
                $admin->name=$value1['product_name'];
                $admin->category=2;
                $admin->unit_price=$value1['price'];
                $admin->unit_cost=$value1['cost'];
                $admin->unit=$value1['unit'];
                $admin->service_charge_ratio=0;
                $admin->ref_pic_url=$value1['ref_pic_url'];
                $admin->description=$value1['description'];
                $admin->product_show=$value1['product_show'];
                $admin->update_time=date('y-m-d h:i:s',time());
                $admin->save();
            };
        };

        


        // 6、复制 公司0的 supplier_product
        $tar_product = SupplierProduct::model()->findAll(array(
                'conditon' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => 0
                    )
            ));

        $sp_shift = array(); //存储 原product_id 与 新product_id 的对应关系
        
        foreach ($tar_product as $key => $value) {
            $admin=new SupplierProduct;
            $admin->account_id=$account_id;
            $admin->supplier_id=$value['supplier_id'];
            $admin->service_product_id=$value['service_product_id'];
            $admin->supplier_type_id=$value['supplier_type_id'];
            $admin->dish_type=$value['dish_type'];
            $admin->decoration_tap=$value['decoration_tap'];
            $admin->standard_type=$value['standard_type'];
            $admin->name=$value['name'];
            $admin->category=$value['category'];
            $admin->unit_price=$value['unit_price'];
            $admin->unit_cost=$value['unit_cost'];
            $admin->unit=$value['unit'];
            $admin->service_charge_ratio=$value['service_charge_ratio'];
            $admin->ref_pic_url=$value['ref_pic_url'];
            $admin->description=$value['description'];
            $admin->product_show=$value['product_show'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $supplier_product_id = $admin->attributes['id'];

            $item = array();
            $item['tar_id'] = $value['id'];
            $item['id'] = $supplier_product_id;
            $sp_shift[] = $item;
        }

        // 7、为新增的第一个门店，复制 公司0的 wedding_set 
        $tar_wedding_set = Wedding_set::model()->findAll(array(
                'conditon' => 'staff_hotel_id=:staff_hotel_id',
                'params' => array(
                        ':staff_hotel_id' => 0
                    )
            ));

        $ws_shift = array(); //存储 原product_id 与 新product_id 的对应关系

        foreach ($tar_wedding_set as $key => $value) {
            $admin=new Wedding_set;
            $admin->staff_hotel_id=$first_hotel_id;
            $admin->name=$value['name'];
            $admin->category=$value['category'];
            $admin->final_price=$value['final_price'];
            $admin->feast_discount=$value['feast_discount'];
            $admin->other_discount=$value['other_discount'];
            $admin->theme_id=$value['theme_id'];
            $admin->product_list=$value['product_list'];
            $admin->set_show=$value['set_show'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $wedding_set_id = $admin->attributes['id'];


            $item = array();
            $item['tar_id'] = $value['id'];
            $item['id'] = $wedding_set_id;
            $ws_shift[] = $item;
        };
            



        // 8、为每个新增的员工，复制一个订单（$tar_order_id）
        $tar_order = Order::model()->findByPk($tar_order_id);
        $tar_order_product = OrderProduct::model()->findAll(array(
                'conditon' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $tar_order_id
                    )
            ));
        $tar_order_set = OrderSet::model()->findAll(array(
                'conditon' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $tar_order_id
                    )
            ));


        foreach ($new_staff_id as $key => $value) {
            //新增 Order
            $admin=new Order;
            $admin->account_id=$account_id;
            $admin->designer_id=$value;
            $admin->planner_id=$value;
            $admin->adder_id=$value;
            $admin->staff_hotel_id=$tar_order['staff_hotel_id'];
            $admin->order_name=$tar_order['order_name'];
            $admin->order_type=$tar_order['order_type'];
            $admin->order_date=date("Y-m-d",strtotime("+3 month"));
            $admin->end_time=$tar_order['end_time'];
            $admin->order_status=$tar_order['order_status'];
            $admin->other_discount=$tar_order['other_discount'];
            $admin->discount_range=$tar_order['discount_range'];
            $admin->feast_discount=$tar_order['feast_discount'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $order_id = $admin->attributes['id'];

            //新增 OrderSet
            $os_shift = array();
            foreach ($tar_order_set as $key_tos => $value_tos) {
                $tem_ws_id = "";
                foreach ($ws_shift as $key_wss => $value_wss) {
                    if($value_tos['wedding_set_id'] == $value_wss['tar_id']){
                        $tem_ws_id = $value_wss['id'];
                    };
                };

                $admin=new OrderSet;
                $admin->order_id=$account_id;
                $admin->wedding_set_id=$value;
                $admin->amount=$value;
                $admin->remark=$value;
                $admin->actual_service_ratio=$tar_order['staff_hotel_id'];
                $admin->order_product_list=$tar_order['order_name'];
                $admin->final_price=$tar_order['order_type'];
                $admin->update_time=date('y-m-d h:i:s',time());
                $admin->save();
                $order_set_id = $admin->attributes['id'];

                $item = array();
                $item['tar_id'] = $value_tos['id'];
                $item['id'] = $order_set_id;
                $os_shift[] = $item;
            };

                       

            //新增 OrderProduct
            foreach ($tar_order_product as $key1 => $value1) {
                //转换product_id
                $tem_product_id = "";
                foreach ($sp_shift as $key2 => $value2) {
                    if($value1['product_id'] == $value2['tar_id']){
                        $tem_product_id = $value2['id'];
                    };
                };
                //转换order_set_id
                $tem_order_set_id = 0;
                foreach ($os_shift as $key3 => $value3) {
                    if($value1['order_set_id'] != 0){
                        if($value1['order_set_id'] == $value3['tar_id']){
                            $tem_order_set_id = $value3['id'];
                        };
                    };   
                };

                $admin=new Order;
                $admin->account_id=$account_id;
                $admin->order_id=$order_id;
                $admin->product_type=$value1['product_type'];
                $admin->product_id=$tem_product_id;
                $admin->order_set_id=$tem_order_set_id;
                $admin->sort=$value1['sort'];
                $admin->actual_price=$value1['actual_price'];
                $admin->unit=$value1['unit'];
                $admin->actual_unit_cost=$value1['actual_unit_cost'];
                $admin->actual_service_ratio=$value1['actual_service_ratio'];
                $admin->remark=$value1['remark'];
                $admin->update_time=date('y-m-d h:i:s',time());
                $admin->save();
            };
        };
    }

}
