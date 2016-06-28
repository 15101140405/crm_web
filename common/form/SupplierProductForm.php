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
                "condition" => "supplier_type_id=:supplier_type_id && category=:category && product_show=:product_show",
                "params" => array(
                    ":supplier_type_id" => 2,
                    ":category" => 1,
                    ':product_show' => 1,
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
                "condition" => "supplier_type_id=:supplier_type_id && category=:category && account_id=:account_id && product_show=:product_show",
                "params" => array(
                    ":supplier_type_id" => 2,
                    ":category" => 2,
                    ":account_id" => $_SESSION['account_id'],
                    ':product_show' => 1,
                ),
                "order" => "unit_price"
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
                "condition" => "supplier_type_id=:supplier_type_id && category=:category && product_show=:product_show",
                "params" => array(
                    ":supplier_type_id" => 19,
                    ":category" => 1,
                    ':product_show' => 1,
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

    public function getServiceSupplierclassified()
    {
        $result = yii::app()->db->createCommand(
            "SELECT staff.`name`,supplier.id AS supplier_id,type_id,service_team.`name` AS team_name 
            FROM ((supplier LEFT JOIN staff ON supplier.staff_id=staff.id) LEFT JOIN service_person ON service_person.staff_id=supplier.staff_id) LEFT JOIN service_team ON service_person.team_id=service_team.id 
            WHERE supplier.account_id=".$_SESSION['account_id']." AND (type_id=3 OR type_id=4 OR type_id=5 OR type_id=6 OR type_id=7) 
            ORDER BY supplier.update_time DESC"
            );
        $servicesupplier = $result->queryAll();
        $servicelist['host']=array();
        $servicelist['video']=array();
        $servicelist['camera']=array();
        $servicelist['makeup']=array();
        $servicelist['other']=array();
        foreach ($servicesupplier as $key => $value) {
            if ($value['type_id'] == 3) {$servicelist['host'][] = $value;}
            if ($value['type_id'] == 4) {$servicelist['video'][] = $value;}
            if ($value['type_id'] == 5) {$servicelist['camera'][] = $value;}
            if ($value['type_id'] == 6) {$servicelist['makeup'][] = $value;}
            if ($value['type_id'] == 7) {$servicelist['other'][] = $value;}
        }
        return $servicelist;
    }
 
    public function getSupplierProductList($accountId)
    {
        $supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id=:account_id",
                "params" => array(
                    ":type_id" => 3,
                    ":account_id" => $accountId,
                ),
            ));
        $result = array();
        foreach ($supplier as $key => $value) {
            $item = array();
            $item['staff_id'] = $value['staff_id'];
            $item['supplier_id'] = $value['id'];
            $service_person = ServicePerson::model()->find(array(
                    'condition' => 'staff_id = :staff_id',
                    'params' => array(
                            ':staff_id' => $value['staff_id']
                        )
                ));
            $item['name'] = $service_person['name'];
            $service_team = ServiceTeam::model()->findByPk($service_person['team_id']);
            $item['team_name'] = $service_team['name'];
            $result[] = $item;
        }
        return $result;
            

           /*$supplier_id = array();
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
           
            return $result;*/
    }

    public function getSupplierProductList1($accountId)
        {
            $supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id=:account_id",
                "params" => array(
                    ":type_id" => 4,
                    ":account_id" => $accountId,
                ),
            ));
            $result = array();
            foreach ($supplier as $key => $value) {
                $item = array();
                $item['staff_id'] = $value['staff_id'];
                $service_person = ServicePerson::model()->find(array(
                        'condition' => 'staff_id = :staff_id',
                        'params' => array(
                                ':staff_id' => $value['staff_id']
                            )
                    ));
                $item['name'] = $service_person['name'];
                $service_team = ServiceTeam::model()->findByPk($service_person['team_id']);
                $item['team_name'] = $service_team['name'];
                $result[] = $item;
            }
            return $result;

            /*$Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 4,
                    ":account_id" => $accountId,
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
            $criteria1->addCondition("account_id = :account_id");    
            $criteria1->params[':account_id']=$accountId;  
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
           
            return $result;*/
        }

    public function getSupplierProductList2($accountId)
        {
            $supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id=:account_id",
                "params" => array(
                    ":type_id" => 5,
                    ":account_id" => $accountId,
                ),
            ));
            $result = array();
            foreach ($supplier as $key => $value) {
                $item = array();
                $item['staff_id'] = $value['staff_id'];
                $service_person = ServicePerson::model()->find(array(
                        'condition' => 'staff_id = :staff_id',
                        'params' => array(
                                ':staff_id' => $value['staff_id']
                            )
                    ));
                $item['name'] = $service_person['name'];
                $service_team = ServiceTeam::model()->findByPk($service_person['team_id']);
                $item['team_name'] = $service_team['name'];
                $result[] = $item;
            }
            return $result;

            /*$Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 5,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id");    
            $criteria1->params[':account_id']=$accountId;  
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
           
            return $result;*/
        }
        // =======化妆=====
        public function getSupplierProductList3($accountId)
        {
            $supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id=:account_id",
                "params" => array(
                    ":type_id" => 6,
                    ":account_id" => $accountId,
                ),
            ));
            $result = array();
            foreach ($supplier as $key => $value) {
                $item = array();
                $item['staff_id'] = $value['staff_id'];
                $service_person = ServicePerson::model()->find(array(
                        'condition' => 'staff_id = :staff_id',
                        'params' => array(
                                ':staff_id' => $value['staff_id']
                            )
                    ));
                $item['name'] = $service_person['name'];
                $service_team = ServiceTeam::model()->findByPk($service_person['team_id']);
                $item['team_name'] = $service_team['name'];
                $result[] = $item;
            }
            return $result;

            /*$Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 6,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id");    
            $criteria1->params[':account_id']=$accountId;  
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
           
            return $result;*/
        }


        // =========其他=========
        public function getSupplierProductList4($accountId)
        {
            $supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id=:account_id",
                "params" => array(
                    ":type_id" => 7,
                    ":account_id" => $accountId,
                ),
            ));
            $result = array();
            foreach ($supplier as $key => $value) {
                $item = array();
                $item['staff_id'] = $value['staff_id'];
                $service_person = ServicePerson::model()->find(array(
                        'condition' => 'staff_id = :staff_id',
                        'params' => array(
                                ':staff_id' => $value['staff_id']
                            )
                    ));
                $item['name'] = $service_person['name'];
                $service_team = ServiceTeam::model()->findByPk($service_person['team_id']);
                $item['team_name'] = $service_team['name'];
                $result[] = $item;
            }
            return $result;

            /*$Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 7,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id");    
            $criteria1->params[':account_id']=$accountId;  
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
           
            return $result;*/
        }


        //==============会议 灯光==============
        public function getSupplierProductLightM($accountId)
        {
            $Supplier = Supplier::model()->findAll(array(
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 8,
                    ":account_id" => $accountId,
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=1;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 9,
                    ":account_id" => $accountId,
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=1;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 8,
                    ":account_id" => $accountId,
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
            $criteria1->addCondition("account_id = :account_id  && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=1;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 9,
                    ":account_id" => $accountId,
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=1;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 10,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id  && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=1;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 11,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=1;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 12,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=$product_show;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 13,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=$product_show;  
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
                "condition" => "type_id=:type_id account_id = :account_id",
                "params" => array(
                    ":type_id" => 14,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=$product_show;  
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
                "condition" => "type_id=:type_id && account_id = :account_id",
                "params" => array(
                    ":type_id" => 15,
                    ":account_id" => $accountId
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
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=$product_show;  
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
                "condition" => "staff_id=:staff_id && account_id = :account_id",
                "params" => array(
                    ":staff_id" => $designer_id,
                    ":account_id" => $account_id,
                ),
            ));
     
            $staff_id = array();
            // var_dump($staff_id);die;
            $criteria1 = new CDbCriteria; 
            $criteria1->addCondition("supplier_id",$Supplier['id']);
            $criteria1->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria1->params[':account_id']=$accountId;  
            $criteria1->params[':product_show']=$product_show;  
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
