<?php

/**
 * Class SupplierProduct
 * Supplier info
 */
class SupplierProductForm extends InitForm
{

    public function SupplierProductIndex($accountId){
        $SupplierProduct = SupplierProduct::model()->findAll(array(
                "condition" => "supplier_type_id=:supplier_type_id && category=:category",
                "params" => array(
                    ":supplier_type_id" => 2,
                    ":category" => 2,
                ),
            ));
 
        $result = array();
        foreach($SupplierProduct as $value){
            $item = array();
            $item['product_id'] = $value->id;
            $item['name'] = $value->name;
            //$SupplierProduct->supplier_id;
            $Suppliers = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            // var_dump($Suppliers);die;
            $Staffs = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $Suppliers['staff_id'],
                ),
            ));
            $item['supplier_name'] = $Staffs->name;
            $result[] = $item;   
        }
        return $result;
        
    }
    //==========餐饮=========
    public function SupplierProductIndex1($accountId){
        $SupplierProduct = SupplierProduct::model()->findAll(array(
                "condition" => "supplier_type_id=:supplier_type_id && category=:category",
                "params" => array(
                    ":supplier_type_id" => 2,
                    ":category" => 1,
                ),
            ));
 
        $result = array();
        foreach($SupplierProduct as $value){
            $item = array();
            $item['product_id'] = $value->id;
            $item['name'] = $value->name;
            //$SupplierProduct->supplier_id;
            $Suppliers = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            // var_dump($Suppliers);die;
            $Staffs = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $Suppliers['staff_id'],
                ),
            ));
            $item['supplier_name'] = $Staffs->name;
            $result[] = $item;   
        }
        return $result;
        
    }

    public function SupplierProductIndex3($accountId){
        $SupplierProduct = SupplierProduct::model()->findAll(array(
                "condition" => "supplier_type_id=:supplier_type_id && category=:category",
                "params" => array(
                    ":supplier_type_id" => 2,
                    ":category" => 2,
                ),
            ));
 
        $result = array();
        foreach($SupplierProduct as $value){
            $item = array();
            $item['product_id'] = $value->id;
            $item['name'] = $value->name;
            //$SupplierProduct->supplier_id;
            $Suppliers = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            // var_dump($Suppliers);die;
            $Staffs = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $Suppliers['staff_id'],
                ),
            ));
            $item['supplier_name'] = $Staffs->name;
            $result[] = $item;   
        }
        return $result;
        
    }

     //==========场地费=========
    public function SupplierProductIndex2($accountId){
        $SupplierProduct = SupplierProduct::model()->findAll(array(
                "condition" => "supplier_type_id=:supplier_type_id && category=:category",
                "params" => array(
                    ":supplier_type_id" => 19,
                    ":category" => 1,
                ),
            ));
 
        $result = array();
        foreach($SupplierProduct as $value){
            $item = array();
            $item['product_id'] = $value->id;
            $item['name'] = $value->name;
            //$SupplierProduct->supplier_id;
            $Suppliers = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            // var_dump($Suppliers);die;
            $Staffs = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $Suppliers['staff_id'],
                ),
            ));
            $item['supplier_name'] = $Staffs->name;
            $result[] = $item;   
        }
        return $result;
        
    }
 
    public function getSupplierProductList($accountId)
    {
        $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 3,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
            $staff_id = array();
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
    }

    public function getSupplierProductList1($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 4,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }

    public function getSupplierProductList2($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 5,
                ),
            ));
            $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }
        // =======化妆=====
        public function getSupplierProductList3($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 6,
                ),
            ));
            $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }


        // =========其他=========
        public function getSupplierProductList4($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 7,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }


        //==============会议 灯光==============
        public function getSupplierProductLightM($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 8,
                ),
            ));
            // $SupplierProduct = SupplierProduct::model()->findAll(array(
            //     "condition" => "supplier_type_id=:supplier_type_id && category=:category",
            //     "params" => array(
            //         ":supplier_type_id" => 8,
            //         ":category" => 1,
            //     ),
            // ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addCondition("category=1");
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }

        ///=================会议     视频===============

        public function getSupplierProductLightM1($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 9,
                ),
            ));

            //   $SupplierProduct = SupplierProduct::model()->findAll(array(
            //     "condition" => "supplier_type_id=:supplier_type_id && category=:category",
            //     "params" => array(
            //         ":supplier_type_id" => 9,
            //         ":category" => 1,
            //     ),
            // ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addCondition("category=1");
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }

        // ========灯光==========
        public function getSupplierProductLight($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 8,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $criteria1->addCondition("category=2");
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }



        // ====视频=====

        public function getSupplierProductLight1($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 9,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }


        // 平面设计
         public function getSupplierProductgraphicFilm($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 10,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }


        // ============视频设计=========
          public function getSupplierProductgraphicFilm1($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 11,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }



        // =======婚礼=========
         
          public function getSupplierProductdressAppliance($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 12,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }


        // ==========婚品===========


        
          public function getSupplierProductdressAppliance1($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 13,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }
   


   // ================酒水婚车====================
          public function getSupplierProductdrinksCar($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 14,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }




          public function getSupplierProductdrinksCar1($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id",
                "params" => array(
                    ":type_id" => 15,
                ),
            ));
           $supplier_id = array();
            foreach($Supplier as $value){
                $item = $value->id;
                $supplier_id[] = $item;
            }
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $result = array();
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $result;
        }

        public function getSupplierProductplanother($designer_id)
        {
            $Supplier = Supplier::model()->find(array(
                "condition" => "staff_id=:staff_id",
                "params" => array(
                    ":staff_id" => $designer_id,
                ),
            ));
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addCondition("supplier_id",$Supplier['id']);
            $SupplierProducts = SupplierProduct::model()->findAll($criteria1);
            $result = array();
            // var_dump($SupplierProducts);
            foreach ($SupplierProducts as $key=>$SupplierProducts) {
                $item = array();
                $item["product_id"] = $SupplierProducts->id;
                $item["name"] = $SupplierProducts->name;
                $item["supplier_id"] = $SupplierProducts->supplier_id;
     
                $result[] = $item;
                // $staff_id[] = $item["supplier_id"];
            }
            foreach($result as $key=>$value){
                 
            $supplier = Supplier::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $value['supplier_id'],
                ),
            ));
            $staff = Staff::model()->find(array(
                "condition" => "id=:id",
                "params" => array(
                    ":id" => $supplier['staff_id'],
                ),
            ));
            $result[$key]['supplier_name'] = $staff['name'];
            }
           
            return $Supplier;
        }






}
