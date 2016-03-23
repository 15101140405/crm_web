<?php

/**
 * Class OrderWeddingForm
 * Protect info
 */
class OrderWeddingForm extends InitForm
{
    public function getOrderDetail($orderId)
    {
       $DetailArr = Order::model()->find(array(
            "condition" => "id=:id",
            "params" => array( ":id" => $orderId ),

       ));
       return $DetailArr;
    }


    public function OrderWeddingInsert($arr,$order_id){
 
        $OrderWedding = OrderWedding::model()->find(
            array('condition'=>"account_id=:account_id",
                    'params'=>array(
                        'account_id'=>$arr['account_id']
                        ),
                    )
        ); 
        $model = new OrderWedding(); //订单
        
        $model->account_id = $arr['account_id'];
        $model->order_id = $arr['order_id'];
        $model->account_id = $arr['account_id'];
        $model->groom_name = $arr['groom_name'];
        $model->groom_phone = $arr['groom_phone'];
        $model->groom_wechat = $arr['groom_wechat'];
        $model->groom_qq = $arr['groom_qq'];
        $model->bride_name = $arr['bride_name'];
        $model->bride_phone = $arr['bride_phone'];
        $model->bride_wechat = $arr['bride_wechat'];
        $model->bride_qq = $arr['bride_qq'];
        $model->contact_name = $arr['contact_name'];
        $model->contact_phone = $arr['contact_phone'];
        $model->update_time = date('Y-m-d');
         
        if($model->save()>0){
            $arr['success']='1';
            // var_dump($model);
            return $arr;
        }else{
            var_dump($model);
            $arr['success']='0';
            return $arr;
        }

    }

        public function linkManUpdate($arr,$order_id){
 
        $OrderWedding = OrderWedding::model()->find($order_id); 
        // $model = new OrderWedding(); //订单
        
        // $model->account_id = $arr['account_id'];
        // $model->order_id = $arr['order_id'];
        // $model->account_id = $arr['account_id'];
        // $model->groom_name = $arr['groom_name'];
        // $model->groom_phone = $arr['groom_phone'];
        // $model->groom_wechat = $arr['groom_wechat'];
        // $model->groom_qq = $arr['groom_qq'];
        // $model->bride_name = $arr['bride_name'];
        // $model->bride_phone = $arr['bride_phone'];
        // $model->bride_wechat = $arr['bride_wechat'];
        // $model->bride_qq = $arr['bride_qq'];
        $arr2['contact_name'] = $arr['contact_name'];
        $arr2['contact_phone'] = $arr['contact_phone'];
        $arr2['update_time']= date('Y-m-d');
        $count2 =  OrderWedding::model()->updateAll($arr2,'order_id =:order_id',array( ":order_id" => $order_id));
        if($count2>0 ){
            var_dump($count2);
            /*return array('success'=>1);*/
            return array('success'=>3);
        }else{
            return array('success'=>0);
        }

    }
    
    

}
