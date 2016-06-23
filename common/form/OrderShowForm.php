<?php

/**
 * Class ProtectForm
 * Protect info
 */
class OrderShowForm extends InitForm
{
	public function new_product_insert($account_id,$supplier_id,$supplier_type_id,$decoration_tap,$name,$category,$price,$cost,$unit,$remark,$url,$order_id,$amount,$area,$sort)
	{
		$product = new ProductForm;
		$sp_id = $product->SpInsert($account_id,$supplier_id,$supplier_type_id,$decoration_tap,$name,$category,$price,$cost,$unit,$url);
		$op_id = $product->OpInsert($order_id,$sp_id,$amount,$price,$cost,$remark);

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
}
