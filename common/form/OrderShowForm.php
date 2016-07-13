<?php

/**
 * Class ProtectForm
 * Protect info
 */
class OrderShowForm extends InitForm
{
	public function new_product_insert($account_id,$supplier_id,$supplier_type_id,$decoration_tap,$name,$category,$price,$cost,$unit,$remark,$url,$order_id,$amount,$area,$sort)
	{
		// $product = new ProductForm;
		// $sp_id = $product->SpInsert($account_id,$supplier_id,$supplier_type_id,$decoration_tap,$name,$category,$price,$cost,$unit,$url);
		// $op_id = $product->OpInsert($order_id,$sp_id,$amount,$price,$cost,$remark);

        $sp = new SupplierProduct;
        $sp->account_id=$account_id;
        $sp->supplier_id=$supplier_id;
        $sp->service_product_id=0;
        $sp->supplier_type_id=$supplier_type_id;
        $sp->dish_type=0;
        $sp->decoration_tap=$decoration_tap;
        $sp->standard_type=0;
        $sp->name=$name;
        $sp->category=$category;
        $sp->unit_price=$price;
        $sp->unit_cost=$cost;
        $sp->unit=$unit;
        $sp->service_charge_ratio=0;
        $sp->ref_pic_url=$url;
        $sp->save();
        $sp_id = $sp->attributes['id'];

        $sp = SupplierProduct::model()->findByPk($sp_id);
        if($price == "#"){
            $price = $sp['unit_price'];
        };
        if($cost == "#"){
            $cost = $sp['unit_cost'];
        };
        if($remark == "#"){
            $remark = $sp['description'];
        };

        $model = new OrderProduct(); //产品
        $model->account_id = $sp['account_id'];
        $model->order_id = $order_id;
        $model->product_type = 0;
        $model->product_id = $sp_id;
        $model->order_set_id = 0;
        $model->sort = 1;
        $model->actual_price = $price;
        $model->unit = $amount;
        $model->actual_unit_cost = $cost;
        $model->actual_service_ratio = 0;
        $model->remark = $remark;
        $model->update_time = date('Y-m-d');
        $model->save();
        $op_id = $model->attributes['id'];



		$os = new OrderShow;
    	$os->type=2;
    	$os->img_id=0;
    	$os->order_product_id=$op_id;
    	$os->words=0;
    	$os->order_id=$order_id;
    	$os->show_area=$area;
    	$os->area_sort=$sort;
    	$os->update_time=date('y-m-d h:i:s',time());
    	$os->save();
        $os_id = $model->attributes['id'];


        return $amount;

	}

    public function product_insert($area,$sort,$order_id,$sp_id)
    {
    	$op = new ProductForm;
    	$op_id = $op->OpInsert($order_id,$sp_id,1,"#","#","#");

    	$os = new OrderShow;
    	$os->type=2;
    	$os->img_id=0;
    	$os->order_product_id=$op_id;
    	$os->words=0;
    	$os->order_id=$order_id;
    	$os->show_area=$area;
    	$os->area_sort=$sort;
    	$os->update_time=date('y-m-d h:i:s',time());
    	$os->save();
    } 

    public function img_insert($type,$url,$staff_id,$order_id,$area,$sort)
    {
    	$osi = new OrderShowImg;
    	$osi->type=$type;
    	$osi->img_url=$url;
    	$osi->staff_id=$staff_id;
    	$osi->update_time=date('y-m-d h:i:s',time());
    	$osi->save();
    	$id = $osi->attributes['id'];

    	$os = new OrderShow;
    	$os->type=1;
    	$os->img_id=$id;
    	$os->order_product_id=0;
    	$os->words=0;
    	$os->order_id=$order_id;
    	$os->show_area=$area;
    	$os->area_sort=$sort;
    	$os->update_time=date('y-m-d h:i:s',time());
    	$os->save();
    }

    public function words_insert($words,$order_id,$area,$sort)
    {
    	$os = new OrderShow;
    	$os->type=0;
    	$os->img_id=0;
    	$os->order_product_id=0;
    	$os->words=$words;
    	$os->order_id=$order_id;
    	$os->show_area=$area;
    	$os->area_sort=$sort;
    	$os->update_time=date('y-m-d h:i:s',time());
    	$os->save();
    }

    public function area_sort_batch_add($order_id,$area_id,$sort)
    {
        $result = yii::app()->db->createCommand("select * from order_show where order_id=".$order_id." and show_area=".$area_id." and area_sort>=".$sort);
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            OrderShow::model()->updateByPk($value['id'],array('area_sort' => $value['area_sort']+1));
        };

        // $sql = "";
        // foreach ($result as $key => $value) {
        //      $sql .= " when id=".$value['id']." then ".($value['area_sort']+1);
        // };
        // return $sql;


        // $result1 = yii::app()->db->createCommand("update order_show set area_sort = case ".$sql." else area_sort end");
        // $result1 = $result1->queryAll();
    }

































}
