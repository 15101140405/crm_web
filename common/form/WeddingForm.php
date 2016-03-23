<?php

/**
 * Class ProtectForm
 * Protect info
 */
class WeddingForm extends InitForm
{
 
    public function weddingInsert($arr){
 
        $OrderWedding = OrderWedding::model()->find(
            array('condition'=>"account_id=:account_id",
                    'params'=>array(
                        'account_id'=>$arr['account_id']
                        ),
                    )
        ); 
        $model = new OrderWedding();//婚礼订单
        // $model->order_date=$arr['order_date'];
        // $model->order_type=$arr['order_type'];
        // $model->planner_id = $arr['planner_id'];
        // $model->staff_hotel_id=$arr['staff_hotel_id'];
        // $accountId = $this->getAccountId();
        $model->account_id = $arr['account_id'];
        $model->expect_table_count=$arr['expect_table'];
        $model->designer_id=$arr['designer_id'];
        $model->order_id=$arr['Order_id'] ;
        $model->feast_discount=$arr['Feast_Discount'] ;
        $model->wedding_discount=$arr['Wedding_Discount'] ;
        $model->update_time=$arr['update_time'] ;
        // $model->order_time=$arr['order_time'];
        
        if($model->save()>0){
            $arr['success']='1';
            return $arr;
        }else{
            var_dump($model);
            $arr['success']='0';
            return $arr;
        }

    }
     
     
}
