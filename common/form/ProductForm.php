<?php

/**
 * Class ProtectForm
 * Protect info
 */
class ProductForm extends InitForm
{
    public function getProductList($supplier_id)
    {
        $result = array();
        // $types = $this->getSupplierTypes($accountId);

        $Product = SupplierProduct::model()->findAll(array(
            "condition" => "supplier_id=:supplier_id",
            "params" => array(
                ":supplier_id" => $supplier_id,
            ),
        ));

        foreach ($Product as $Product) {
            $item = array();
            $item["product_id"] = $Product->id;
            $item["name"] = $Product->name;
 
            $result[] = $item;
        }

        return $result;
    }

    
    public function productEdit($productId){
        $productInfo = SupplierProduct::model()->findByPk($productId);
      
        $arr['na'] = $productInfo->name;
        $arr['price'] = $productInfo->unit_price;
        $arr['unit'] = $productInfo->unit;
        $arr['cost'] = $productInfo->unit_cost;
        $arr['success'] = 1 ;
        return $arr;

    }
    public function productInsert($arr){
       
        $SupplierType = SupplierType::model()->find(
            array('condition'=>"account_id=:account_id",
                  'params'=>array(
                    'account_id'=>$arr['account_id']
                    ),
                )
            );
        // var_dump($SupplierType);die;
        $model = new SupplierProduct(); //产品
        $model->account_id = $arr['account_id'];
        $model->supplier_id = $arr['supplier_id'];
        $model->category = $arr['category'];
        $model->name = $arr['name'];
        $model->unit_price = $arr['unit_price'];
        $model->unit = $arr['unit'];
        $model->supplier_type_id = $arr['supplier_type_id'];
        $model->unit_cost = $arr['unit_cost'];
        $model->description = "111";
        $model->ref_pic_url = "000";
        $model->service_charge_ratio = "222";
        $model->update_time = date('Y-m-d');

        if($model->save()>0){
            $arr['success']='1';
            return $arr;
        }else{
            var_dump($model);
            $arr['success']='0';
            return $arr;
        }

    }

    public function productUpdate($productId,$arr){
        $arr2['supplier_id']    = $arr['supplier_id'];
        $arr2['name']    = $arr['name'];
        $arr2['unit_price']    = $arr['unit_price'];
        $arr2['unit']    = $arr['unit'];
        $arr2['unit_cost']    = $arr['unit_cost'];
        SupplierProduct::model()->updateAll($arr2,'id =:id',array( ":id" => $productId));
    }

    public function productDelete($productId,$account_id){
        $count = SupplierProduct::model()->deleteByPk($productId,'account_id=:account_id',array(':account_id'=>$account_id));
        if($count>0){
            $arr['success']= 1;
            return  $arr;
        }else{
            $arr['success']= 0;
            return  $arr;
        };

    }

    public function OpInsert($order_id,$sp_id,$amount,$price,$cost,$remark)
    {
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
        $id = $model->attributes['id'];

        return $id;
    }

    public function SpInsert($account_id,$supplier_id,$supplier_type_id,$decoration_tap,$name,$category,$unit_price,$unit_cost,$unit,$url)
    {
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
        $sp->unit_price=$unit_price;
        $sp->unit_cost=$unit_cost;
        $sp->unit=$unit;
        $sp->service_charge_ratio=0;
        $sp->ref_pic_url=$url;
        $sp->save();
        $id = $sp->attributes['id'];

        return $id;
    }

    public function Tuidan_list($token)
    {
        $result = yii::app()->db->createCommand("select sp.id,sp.name ".
            " from staff s left join staff_company sc on s.account_id=sc.id ".
            " left join supplier_product sp on sp.account_id=s.account_id ".
            " where sp.product_show=1 and sp.supplier_type_id=16 and s.id=".$token);
        $result = $result->queryAll();
        return $result;
    }



































}
