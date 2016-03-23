<?php

/**
 * Class ProtectForm
 * Protect info
 */
class OrderForm extends InitForm
{
    public function getOrderDetail($orderId)
    {
       $DetailArr = Order::model()->find(array(
            "condition" => "id=:id",
            "params" => array( ":id" => $orderId ),

       ));
       return $DetailArr;
    }

    public function getOrderIndex($accountId)
    {
        $result = array();

        $Order = order::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                ":account_id" => $accountId,
            ),
        ));
        //$Order = "SELECT order_date,count(order_time) from order GROUP BY order_date";
            $aa = '';
            $bb = '';
            $cc = '';
            $dd = '';
    // var_dump($Order);die;
        foreach ($Order as $Order) {
            $item = array();
            $time = $Order->order_time;
            // var_dump($time);
            $status = $Order->order_status;
            $date = $Order->order_date;
            if($time == '0' && $status != '1'){
                $aa.= $date.',';
            }
            if($time == '1' && $status != '1'){
                $bb.= $date.',';
            }
            if($time == '2' && $status != '1'){
                $cc.= $date.',';
            }
            if($status == '1'){
                $dd.=$date.',';
            }
        }
        $aa = rtrim($aa,',');
        $array1 = explode(',',$aa);
        // var_dump($array1);die;
        $bb = rtrim($bb,',');
        $array2 = explode(',',$bb);
        // var_dump($array2);die;
        $cc = rtrim($cc,',');
        $array3 = explode(',',$cc);
        // var_dump($array3);die;
        $dd = rtrim($dd,',');
        // var_dump($dd);die;
        $inter1 = array_intersect($array1,$array2,$array3);//取三者交际 一天有三个订单的
        $item['data'] = implode(',',$inter1);
        // var_dump($inter1);
        //把对应的当天（有三个订单的天数）的订单传到前台
        // $date_order = order::model()->findAll(array(
        //     "condition" => "order_date=:order_date",
        //     "params" => array(
        //         ":order_date" => $item['data'] ,
        //     ),
        // ));
        foreach ($inter1 as $key=>$val){
            $date_order = order::model()
            ->findAll(array(
            "condition" => "order_date=:order_date",
            "params" => array(
                ":order_date" => $inter1[$key],
            ),
        ));
         // var_dump($date_order); 
        }
        
         // var_dump($date_order);die;
        $item['data'] = date("d",strtotime($item['data']));
        // var_dump($item['data']);die;  
        $marge1 = array_merge($array1,$array2);//先取0 1并集    a
        $inter2 = array_intersect($marge1,$array3);//取0 1的并际结果(a)和 2 的交际   b 
        $inter3 = array_intersect($array1,$array2);//取0 1的交际    c
        $marge2 = array_unique((array_merge($inter2,$inter3)));//  b c 并集   一天有两个订单的
        $item['half_data'] = implode(',',$marge2);
        $item['half_data'] = date("d",strtotime($item['half_data']));
        
        $item['maybe_data'] = $dd;
        $item['maybe_data'] = date("d",strtotime($item['maybe_data']));
        // var_dump($item['half_date']);die;
 

        $result[] = $item;
        // var_dump($item);die;
        return $result;

    }

    public function orderInsert($arr){
 
        $Order = Order::model()->find(
            array('condition'=>"account_id=:account_id",
                    'params'=>array(
                        'account_id'=>$arr['account_id']
                        ),
                    )
        ); 
        $model = new Order(); //订单
        $model->order_date = $arr['order_date'];
        $model->order_type = $arr['order_type'];
        $model->order_time = $arr['order_time'];
        $model->planner_id = $arr['planner_id'];
        $model->designer_id = $arr['designer_id'];
        $model->staff_hotel_id=$arr['staff_hotel_id'];
        $model->account_id = $arr['account_id'];
        $model->order_name = $arr['order_name'];
        $model->order_status = $arr['order_status'];
        $model->expect_table = $arr['expect_table'];
        $model->update_time = date('Y-m-d');
        $date = explode(' ',$arr['order_date']);
        $dd = $date['0'];
        $time = $arr['order_time'];
        //把日期和上下午拼接起来
        $limit = $dd.$time;
        // var_dump($limit);
        $Order2 = Order::model()->findAll(
            array('condition'=>"account_id=:account_id",
                    'params'=>array(
                        'account_id'=>$arr['account_id']
                        ),
                    )
        ); 
        $result = array();
        foreach($Order2 as $value){
                // var_dump($value);
            $ss = explode(' ',$value['order_date']);
            $aa = $value['order_time'];
            $result[] = $ss['0'].$aa;
        }
        if(in_array($limit, $result)){
            echo '订单时间有冲突，请选择其他时间';
        }else{
            if($model->save()>0){
                $arr['success']='1';
                return $arr;
            }else{
                $arr['success']='0';
                return $arr;
            }
        }
    }

    
    

}
