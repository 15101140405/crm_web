<?php

/**
 * Class SupplierForm
 * Supplier info
 */
class SupplierForm extends InitForm
{
    public function getSupplierList($accountId,$supplier_type_id)
    {
        $result = array();

        $suppliers = Supplier::model()->findAll(array(
            "condition" => "account_id=:account_id && type_id=:type_id",
            "params" => array(
                ":account_id" => $accountId,
                ":type_id" => $supplier_type_id
            )
        ));

        foreach ($suppliers as $supplier) {
            $item = array();
            $item["supplier_id"] = $supplier->id;

            // 获取对应员工基础信息
            $staff = Staff::model()->findByPk($supplier->staff_id);
            if (!$staff) {
                continue;
            }
            $item["staff_name"] = $staff->name;
            $item["avatar"] = $staff->avatar;

            $type = SupplierType::model()->findByPk($supplier_type_id);
            $item['type_name'] = $type['name'];
            $result[] = $item;
        }

        return $result;
    }

    protected function getSupplierTypes($accountId)
    {
        $typeArr = array();

        $supplierTypes = SupplierType::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                ":account_id" => $accountId,
            ),
        ));

        foreach ($supplierTypes as $type) {
            $typeArr[$type->id] = $type;
        }

        return $typeArr;
    }
    public function chooseType(){
        $supplierTypes = SupplierType::model()->findAll(array(
            "condition" => "",
            "params" => array(
            ),
        ));

        foreach ($supplierTypes as $type) {
            $result[$type->id] = $type;
        }
        return  $result;
    }
    public function supplierEdit($supplierId){
        $supplierInfo = Supplier::model()->findByPk($supplierId);
        $supplierType = SupplierType::model()->findAll(array(
                    "condition" => "id=:type_id",
                    "params" => array(
                        ":type_id" => $supplierInfo->type_id,
                    ),
                ));
        foreach ($supplierType as $type) {
            $arr['supplier_type'] = $type->name ;
        }

        $supplierName = Staff::model()->findAll(array(
                           "condition" => "id=:staff_id",
                           "params" => array(
                                  ":staff_id" => $supplierInfo->staff_id,
                                ),
                            ));
        foreach ($supplierName as $name) {
            $arr['na'] = $name->name;
            $arr['phone'] =$name->telephone;
        }
        $arr['supplier_contract'] = $supplierInfo->contract_url;
        $arr['supplier_type_id'] = $supplierInfo->type_id;
        $arr['success'] = 1 ;
        return $arr;

    }
    public function supplierInsert($arr){
        $model = new Staff(); //员工
        $model->account_id = $arr['account_id'];
        $model->name       = $arr['staff_name'];
        $model->telephone  = $arr['telephone'];
        $model->save(false);
        $arr['staff_id'] = $model->attributes['id'];
        /* */
        $model = new Supplier(); //供应商
        $model->account_id=$arr['account_id'];
        $model->staff_id=$arr['staff_id'];
        $model->type_id=$arr['type_id'];
        $model->contract_url=3;  //合同待修改
        $model->update_time = time();
        if($model->save()>0){
            $arr['success']='1';
            return $arr;
        }else{
            $arr['success']='0';
            return $arr;
        }

    }
    public function supplierUpdate($supplierId,$arr){
        $supplierInfo = Supplier::model()->findByPk($supplierId); //查询员工id
        $arr1['name']      = $arr['staff_name'];
        $arr1['telephone'] = $arr['telephone'];

        $count1 =  Staff::model()->updateAll($arr1,'id =:id',array( ":id" => $supplierInfo->staff_id));
       $arr2['account_id'] = $arr['account_id'];
        $arr2['staff_id']   = $supplierInfo->staff_id;
        $arr2['contract_url']=$arr['contract_url'];
        $arr2['type_id']    = $arr['type_id'];
        $arr2['update_time']= time();
        $count2 =  Supplier::model()->updateAll($arr2,'id =:id',array( ":id" => $supplierId));
     if($count1>0 && $count2>0 ){
            return array('success'=>1);
        }else{
            return array('success'=>0);
        }
    }
    public function supplierDelete($supplierId,$account_id){
        $count = Supplier::model()->deleteByPk($supplierId,'account_id=:account_id',array(':account_id'=>$account_id));
        if($count>0){
            $arr['success']= 1;
            return  $arr;
        }else{
            $arr['success']= 0;
            return  $arr;
        }
    }

}
