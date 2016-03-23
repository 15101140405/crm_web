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
        $productInfo = SupplierProduct::model()->findByPk($productId);
        // $arr1['name']      = $arr['name'];
        // // $arr1['telephone'] = $arr['telephone'];

        // $count1 =  SupplierProduct::model()->updateAll('id =:id',array( ":id" => $productId));
        $arr2['account_id'] = $arr['account_id'];
        $arr2['supplier_id']    = $arr['supplier_id'];
        // $arr2['type_id']    = $arr['type_id'];
        $arr2['name']    = $arr['name'];
        // $arr2['category']    = $arr['category'];
        $arr2['unit_price']    = $arr['unit_price'];
        $arr2['unit']    = $arr['unit'];
        $arr2['unit_cost']    = $arr['unit_cost'];
        $arr2['cost']    = '555';
        // $arr2['service_charge_ratio']    = $arr['service_charge_ratio'];
        // $arr2['ref_pic_url']    = $arr['ref_pic_url'];
        // $arr2['description']    = $arr['description'];
        $arr2['update_time']= date('Y-m-d');
        $count2 =  SupplierProduct::model()->updateAll($arr2,'id =:id',array( ":id" => $productId));
     if($count2>0 ){
            /*return array('success'=>1);*/
            return array('success'=>3);
        }else{
            return array('success'=>0);
        }
    }
    public function productDelete($productId,$account_id){
        $count = SupplierProduct::model()->deleteByPk($productId,'account_id=:account_id',array(':account_id'=>$account_id));
        if($count>0){
            $arr['success']= 1;
            return  $arr;
        }else{
            $arr['success']= 0;
            return  $arr;
        }
    }

}
