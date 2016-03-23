<?php

/**
 * Class OrderMeetingForm
 * Protect info
 */
class OrderMeetingForm extends InitForm
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
 
        $OrderMeeting = OrderMeeting::model()->find(
            array('condition'=>"account_id=:account_id",
                    'params'=>array(
                        'account_id'=>$arr['account_id']
                        ),
                    )
        ); 
        $model = new OrderMeeting(); //订单
        $model->account_id=$arr['account_id'];
        $model->company_id=$arr['company_id'];
        $model->order_id=$arr['order_id'];
        $model->company_linkman_id = $arr['company_linkman_id'];
        $model->layout_id = $arr['layout_id'];
        $model->update_time = date('Y-m-d');
        if($model->save()>0){
            $arr['success']='1';
            // var_dump($model);
            // $arr['id'] = mysql_insert_id();
            return $arr;
        }else{
            var_dump($model);
            $arr['success']='0';
            return $arr;
        }

    }

    
    

}
