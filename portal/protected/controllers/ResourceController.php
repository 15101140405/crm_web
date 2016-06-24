<?php

include_once('../library/WPRequest.php');

class ResourceController extends InitController
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main-not-exited';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
//                'actions' => array(''),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionLogin()
    {
        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:telephone',
                'params' => array(
                        ':telephone' => $_POST['loginname']
                    )
            ));
        if($staff['password'] == $_POST['password']){
            echo json_encode(array('code'=>1,'token'=>$staff['id']));
        }else{
            echo json_encode(array('code'=>0,'token'=>''));
        }
    }

    function startwith($str,$pattern) {
        if(strpos($str,$pattern) === 0)
              return true;
        else
              return false;
    }

    function getUrlFileSize($url){
        try{
             return get_headers($url, true)['Content-Length'];
        }
        catch(Exception $e){
            return "";
        }
    }

    public function actionList()
    {

        //取案例
        $url ="http://file.cike360.com";
        $staff_id = $_GET['token'];
        //type 1 公司 2 分店 3 个人
        $result = yii::app()->db->createCommand("select * from case_info where "./*

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 )  and ".*/

            " CI_Show=1 and CI_Type in (1,2,3) order by CI_Sort Desc");
            
        $list = $result->queryAll();

        /*$list = findAllBySql("select * from case_info where ".

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=:id) ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=:id) ) ".
            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID= :id ))  ".
            " and CI_Show=1 order by CI_Sort Desc" ,array(':id'=>$staff_id)); */
        foreach($list as  $key => $val){
            if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                /*$t=explode(".", $val["CI_Pic"]);
                $CI_Pic = "";
                if(isset($t[0]) && isset($t[1])){
                    $CI_Pic = $t[0]."_sm.".$t[1];    
                }else{
                    $CI_Pic = $val['CI_Pic'];
                };*/
                $list[$key]["CI_Pic"]=$url.$val['CI_Pic'];
            };
            //$val["size"]=$this->getUrlFileSize($val["CI_Pic"]);
            /*$resources = CaseResources::model()->findAll(array(
                    'condition' => 'CI_ID=:CI_ID',
                    'params' => array(
                            ':CI_ID' => $val["CI_ID"]
                        )
                )); */
  
            $result1 = yii::app()->db->createCommand("select case_resources.CR_ID,case_resources.CR_Type,case_resources.CR_Sort,case_resources.CR_Name,case_resources.CR_Path,CR_Show,CR_Remarks,supplier_product.id,supplier_product.name,supplier_product.unit_price,supplier_product.unit,supplier_product.ref_pic_url,supplier_product.description from case_resources left join case_resources_product on case_resources_product.CR_ID=case_resources.CR_ID left join supplier_product on case_resources_product.supplier_product_id=supplier_product.id where CI_ID =".$val["CI_ID"]." order by case_resources.CR_Sort");
            
            $resources = $result1->queryAll();
            $jsonresources = array();
            $cur_resourceobj=null;
            $cur_crid = 0;
            //$cur_product = null;
            //$i = 0;
            $cur_product = array();
            foreach ($resources as $rkey => $rval) {
                $resourceobj =array(
                    "CR_ID"=>$rval["CR_ID"],
                    "CR_Name"=>$rval["CR_Name"],
                    "CR_Path"=>$rval["CR_Path"],
                    "CR_Sort"=>$rval["CR_Sort"],
                    "CR_Show"=>$rval["CR_Show"],
                    "CR_Remarks"=>$rval["CR_Remarks"],
                    "CR_Type"=>$rval["CR_Type"]
                    );
                if(!$this->startwith($rval["CR_Path"],"http://")&&!$this->startwith($rval["CR_Path"],"https://")){
                    $resourceobj["CR_Path"]=$url.$rval["CR_Path"];    
                }

                $cur_crid = $rval["CR_ID"];
                // $cur_resourceobj=$resourceobj;
                if($rval["id"]!=null){
                    $t=explode(".", $rval["ref_pic_url"]);
                    if(isset($t[0]) && isset($t[1])){
                        $ref_pic_url = $t[0]."_sm.".$t[1];    
                    }else{
                        $ref_pic_url = $rval['ref_pic_url'];
                    };
                    $productobj=array(
                        "id"=>$rval["id"],
                        "name"=>$rval["name"],
                        "unit_price"=>$rval["unit_price"],
                        "unit"=>$rval["unit"],
                        "description"=>$rval["description"],
                        "ref_pic_url"=>"http://file.cike360.com".$ref_pic_url
                        );
                    $cur_product[]=$productobj;
                    $resourceobj["product"]=$cur_product;
                }
                else{
                    $resourceobj["product"]=array();
                }
                if(/*$cur_crid!=$rval["CR_ID"]&&*/$cur_crid!=0){
                    $jsonresources[]=$resourceobj;
                    //$cur_resourceobj=null;
                    $cur_product=array();
                };
                if($cur_crid==0){
                    $jsonresources[] = $resourceobj;
                };
                $resourceobj = array();
            }
            $list[$key]["resources"]= $jsonresources;
            $list[$key]['product'] = array();
        };



        //取场地布置
        $staff = Staff::model()->findByPk($staff_id);
        $tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $staff['account_id'],
                    ),
            ));
        $i=10000;
        foreach ($tap as $key1 => $value1) {
            /*$t = explode(".", $value['pic']);
            if(isset($t[0]) && isset($t[1])){
                $CI_Pic = $t[0]."_sm.".$t[1];    
            }else{
                $CI_Pic = $value['ref_pic_url'];
            };*/
            $item = array(
                'CI_ID' => $i+$value1['id'],
                "CI_Name"=> $value1['name'],
                "CI_Place"=> "",
                "CI_Pic"=> "http://file.cike360.com".$value1['pic'],
                "CI_Time"=> null,
                "CI_CreateTime"=> null,
                "CI_Sort"=> "999",
                "CI_Show"=> "1",
                "CI_Remarks"=> "",
                "CI_Type"=> "7",
                "CT_ID"=> "0",
            );
            $supplier_product = SupplierProduct::model()->findAll(array(
                    'condition' => 'decoration_tap=:tap',
                    'params' => array(':tap' => $value1['id'])
                ));
            $resources = array();

            foreach ($supplier_product as $key2 => $value2) {
                $t = array(
                    "CR_ID"=> $i*2 + $value2['id'],
                    "CR_Name"=> $value2['name'],
                    "CR_Path"=> "http://file.cike360.com".$value2['ref_pic_url'],
                    "CR_Sort"=> "1",
                    "CR_Show"=> "1",
                    "CR_Remarks"=> null,
                    "CR_Type"=> "1",
                );
                $t['product'] = array();
                $ref_pic_url = "";
                $t_pic = explode(".", $value2['ref_pic_url']);
                if(isset($t_pic[0]) && isset($t_pic[1])){
                    $ref_pic_url = $t_pic[0]."_sm.".$t_pic[1];    
                }else{
                    $ref_pic_url = $value2['ref_pic_url'];
                };
                $t1 = array(
                        "id"=> $value2['id'],
                        "name"=> $value2['name'],
                        "unit_price"=> $value2['unit_price'],
                        "unit"=> $value2['unit'],
                        "description"=> $value2['description'],
                        "ref_pic_url"=> "http://file.cike360.com".$ref_pic_url,
                    );
                $t['product'][] = $t1;
                $resources[] = $t;
            };
            
            $item['resources'] = $resources;
            $item['product'] = array();
            
            $list[] = $item;
        };






        // 取灯光／音响／视频
        $lss = yii::app()->db->createCommand("select * from supplier_product where supplier_type_id in (8,9,23)");
        $lss = $lss->queryAll();
        $t = 30000;
        $type = yii::app()->db->createCommand("select * from supplier_type where id in (8,9,23)");
        $type = $type->queryAll();
        // print_r($type);die;
        $tem_case8 = array(
                "CI_ID" => $t+8,
                "CI_Name" => "灯光设备",
                "CI_Place" => "",
                "CI_Pic" => "http://file.cike360.com" . $type[0]['img'],
                "CI_Time" => "",
                "CI_CreateTime" => '',
                "CI_Sort" => "1",
                "CI_Show" => "1",
                "CI_Remarks" => "",
                "CI_Type" => "8",
                "CT_ID" => "0",
                'resources' => array(),
                'product' => array(),
            );
        $tem_case9 = array(
                "CI_ID" => $t+9,
                "CI_Name" => "视频设备",
                "CI_Place" => "",
                "CI_Pic" => "http://file.cike360.com" . $type[1]['img'],
                "CI_Time" => "",
                "CI_CreateTime" => '',
                "CI_Sort" => "1",
                "CI_Show" => "1",
                "CI_Remarks" => "",
                "CI_Type" => "8",
                "CT_ID" => "0",
                'resources' => array(),
                'product' => array(),
            );
        $tem_case23 = array(
                "CI_ID" => $t+23,
                "CI_Name" => "音响设备",
                "CI_Place" => "",
                "CI_Pic" => "http://file.cike360.com" . $type[2]['img'],
                "CI_Time" => "",
                "CI_CreateTime" => '',
                "CI_Sort" => "1",
                "CI_Show" => "1",
                "CI_Remarks" => "",
                "CI_Type" => "8",
                "CT_ID" => "0",
                'resources' => array(),
                'product' => array(),
            );
        // print_r(json_encode($lss));die;
        foreach ($lss as $key => $value) {
            $tem_resource = array(
                    "CR_ID" => $t*2+$value['id'],
                    "CR_Name" => $value['name'],
                    "CR_Path" => "http://file.cike360.com" . $value['ref_pic_url'],
                    "CR_Sort" => "1",
                    "CR_Show" => "1",
                    "CR_Remarks" => "",
                    "CR_Type" => "1",
                    "product" => array(),
                );
            /*$t1=explode('.', $value['ref_pic_url']);
            $Pic = "";
            if(isset($t1[0]) && isset($t1[1])){
                $Pic = "http://file.cike360.com/".$t1[0]."_sm.".$t1[1];
            };*/
            $tem_product = array(
                    "id" => $value['id'],
                    "name" => $value['name'],
                    "unit_price" => $value['unit_price'],
                    "unit" => $value['unit'],
                    "description" => $value['description'],
                    "ref_pic_url" => "http://file.cike360.com" . $value['ref_pic_url'],
                );
            $tem_resource['product'][] = $tem_product;
            if($value['supplier_type_id'] == "8"){
                $tem_case8['resources'][] = $tem_resource;    
            }else if($value['supplier_type_id'] == '9'){
                $tem_case9['resources'][] = $tem_resource;
            }else if($value['supplier_type_id'] == '23'){
                $tem_case23['resources'][] = $tem_resource;
            }
        };
        // print_r(json_encode($tem_case9));die;

        $list[] = $tem_case8;
        $list[] = $tem_case9;
        $list[] = $tem_case23;







        //取套系
        $set = yii::app()->db->createCommand("select * from case_info where "./*.

            "(( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 ))  and".*/

            " CI_Show=1 and CI_Type=5 order by CI_Sort Desc");
        $set = $set->queryAll();
        foreach($set as  $key3 => $val){
            if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                /*$t=explode(".", $val["CI_Pic"]);
                $CI_Pic = "";
                if(isset($t[0]) && isset($t[1])){
                    $CI_Pic = $t[0]."_sm.".$t[1];    
                }else{
                    $CI_Pic = $val['CI_Pic'];
                };*/
                $set[$key3]["CI_Pic"]=$url.$val['CI_Pic'];
            };
            //$val["size"]=$this->getUrlFileSize($val["CI_Pic"]);
            /*$resources = CaseResources::model()->findAll(array(
                    'condition' => 'CI_ID=:CI_ID',
                    'params' => array(
                            ':CI_ID' => $val["CI_ID"]
                        )
                )); */
  
            $result1 = yii::app()->db->createCommand("select case_resources.CR_ID,case_resources.CR_Type,case_resources.CR_Sort,case_resources.CR_Name,case_resources.CR_Path,CR_Show,CR_Remarks,supplier_product.id,supplier_product.name,supplier_product.unit_price,supplier_product.unit,supplier_product.ref_pic_url,supplier_product.description from case_resources left join case_resources_product on case_resources_product.CR_ID=case_resources.CR_ID left join supplier_product on case_resources_product.supplier_product_id=supplier_product.id where CI_ID =".$val["CI_ID"]." order by case_resources.CR_Sort");
            
            $resources = $result1->queryAll();
            $jsonresources = array();
            $cur_resourceobj=null;
            $cur_crid = 0;
            //$cur_product = null;
            //$i = 0;
            $cur_product = array();
            foreach ($resources as $rkey => $rval) {
                $resourceobj =array(
                    "CR_ID"=>$rval["CR_ID"],
                    "CR_Name"=>$rval["CR_Name"],
                    "CR_Path"=>$rval["CR_Path"],
                    "CR_Sort"=>$rval["CR_Sort"],
                    "CR_Show"=>$rval["CR_Show"],
                    "CR_Remarks"=>$rval["CR_Remarks"],
                    "CR_Type"=>$rval["CR_Type"]
                    );
                if(!$this->startwith($rval["CR_Path"],"http://")&&!$this->startwith($rval["CR_Path"],"https://")){
                    $resourceobj["CR_Path"]=$url.$rval["CR_Path"];    
                }
                
                
                $cur_crid = $rval["CR_ID"];
                // $resourceobj=$resourceobj;
                if($rval["id"]!=null){
                    $t=explode(".", $rval["ref_pic_url"]);
                    if(isset($t[0]) && isset($t[1])){
                        $ref_pic_url = $t[0]."_sm.".$t[1];    
                    }else{
                        $ref_pic_url = $rval['ref_pic_url'];
                    };
                    $productobj=array(
                        "id"=>$rval["id"],
                        "name"=>$rval["name"],
                        "unit_price"=>$rval["unit_price"],
                        "unit"=>$rval["unit"],
                        "description"=>$rval["description"],
                        "ref_pic_url"=>"http://file.cike360.com".$ref_pic_url
                        );
                    $cur_product[]=$productobj;
                    $resourceobj["product"]=$cur_product;
                }
                else{
                    $resourceobj["product"]=array();
                }

                $jsonresources[]=$resourceobj;
                //$cur_resourceobj=null;
                $cur_product=array();
            }
            $set[$key3]["resources"]= $jsonresources;

            $wedding_set = Wedding_set::model()->findByPk($val['CT_ID']);

            $temp = explode(',', $wedding_set['product_list']);

            $product = array();

            foreach ($temp as $key_tem => $temp_val) {
                $item = array();

                $t = explode('|', $temp_val);

                $supplier_product = SupplierProduct::model()->findByPk($t[0]);
                /*print_r($t);die;*/
                $t1=explode(".", $supplier_product["ref_pic_url"]);
                if(isset($t1[0]) && isset($t1[1])){
                    $ref_pic_url = $t1[0]."_sm.".$t1[1];    
                }else{
                    $ref_pic_url = $supplier_product['ref_pic_url'];
                };
                $item['id'] = $supplier_product['id'];
                $item['name'] = $supplier_product['name'];
                $item['unit_price'] = $supplier_product['unit_price'];
                $item['unit'] = $t[2];
                $item['description'] = $supplier_product['description'];
                $item['ref_pic_url'] = "http://file.cike360.com".$ref_pic_url;
                $product[] = $item;
            };
            $set[$key3]['product'] = $product;
        };
        /*echo json_encode($set);die;*/
        foreach ($set as $key4 => $value) {
            $list[]=$value;
        };



        




        //取婚宴套餐
        $menu = yii::app()->db->createCommand("select * from case_info where "./*

            "(( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 )) and".*/

            " CI_Show=1 and CI_Type=9 order by CI_Sort Desc");
        $menu = $menu->queryAll();
        /*print_r($set);die;*/
        foreach($menu as  $key3 => $val){
            if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                /*$t=explode(".", $val["CI_Pic"]);
                $CI_Pic = "";
                if(isset($t[0]) && isset($t[1])){
                    $CI_Pic = $t[0]."_sm.".$t[1];    
                }else{
                    $CI_Pic = $val['CI_Pic'];
                };*/
                $menu[$key3]["CI_Pic"]=$url.$val['CI_Pic'];
            };
            //$val["size"]=$this->getUrlFileSize($val["CI_Pic"]);
            /*$resources = CaseResources::model()->findAll(array(
                    'condition' => 'CI_ID=:CI_ID',
                    'params' => array(
                            ':CI_ID' => $val["CI_ID"]
                        )
                )); */
  
            $result1 = yii::app()->db->createCommand("select case_resources.CR_ID,case_resources.CR_Type,case_resources.CR_Sort,case_resources.CR_Name,case_resources.CR_Path,CR_Show,CR_Remarks,supplier_product.id,supplier_product.name,supplier_product.unit_price,supplier_product.unit,supplier_product.ref_pic_url,supplier_product.description from case_resources left join case_resources_product on case_resources_product.CR_ID=case_resources.CR_ID left join supplier_product on case_resources_product.supplier_product_id=supplier_product.id where CI_ID =".$val["CI_ID"]." order by case_resources.CR_Sort");
            
            $resources = $result1->queryAll();
            $jsonresources = array();
            $cur_resourceobj=null;
            $cur_crid = 0;
            //$cur_product = null;
            //$i = 0;
            $cur_product = array();
            foreach ($resources as $rkey => $rval) {
                $resourceobj =array(
                    "CR_ID"=>$rval["CR_ID"],
                    "CR_Name"=>$rval["CR_Name"],
                    "CR_Path"=>$rval["CR_Path"],
                    "CR_Sort"=>$rval["CR_Sort"],
                    "CR_Show"=>$rval["CR_Show"],
                    "CR_Remarks"=>$rval["CR_Remarks"],
                    "CR_Type"=>$rval["CR_Type"]
                    );
                if(!$this->startwith($rval["CR_Path"],"http://")&&!$this->startwith($rval["CR_Path"],"https://")){
                    $resourceobj["CR_Path"]=$url.$rval["CR_Path"];    
                };
                
                $cur_crid = $rval["CR_ID"];
                // $cur_resourceobj=$resourceobj;
                if($rval["id"]!=null){
                    $t=explode(".", $rval["ref_pic_url"]);
                    if(isset($t[0]) && isset($t[1])){
                        $ref_pic_url = $t[0].".".$t[1];    
                    }else{
                        $ref_pic_url = $rval['ref_pic_url'];
                    };
                    $productobj=array(
                        "id"=>$rval["id"],
                        "name"=>$rval["name"],
                        "unit_price"=>$rval["unit_price"],
                        "unit"=>$rval["unit"],
                        "description"=>$rval["description"],
                        "ref_pic_url"=>"http://file.cike360.com".$ref_pic_url
                        );
                    $cur_product[]=$productobj;
                    $resourceobj["product"]=$cur_product;
                }
                else{
                    $resourceobj["product"]=array();
                };
                $jsonresources[]=$resourceobj;
                //$cur_resourceobj=null;
                $cur_product=array();
            };
            $menu[$key3]["resources"]= $jsonresources;

            $wedding_set = Wedding_set::model()->findByPk($val['CT_ID']);

            $temp = explode(',', $wedding_set['product_list']);

            $product = array();

            foreach ($temp as $key_tem => $temp_val) {
                $item = array();

                $t = explode('|', $temp_val);

                $supplier_product = SupplierProduct::model()->findByPk($t[0]);
                /*print_r($t);die;*/
                $t1=explode(".", $supplier_product["ref_pic_url"]);
                if(isset($t1[0]) && isset($t1[1])){
                    $ref_pic_url = $t1[0].".".$t1[1];    
                }else{
                    $ref_pic_url = $supplier_product['ref_pic_url'];
                };
                $item['id'] = $supplier_product['id'];
                $item['name'] = $supplier_product['name'];
                $item['unit_price'] = $supplier_product['unit_price'];
                $item['unit'] = $t[2];
                $item['description'] = $supplier_product['description'];
                $item['ref_pic_url'] = "http://file.cike360.com".$ref_pic_url;
                $product[] = $item;
            };
            $menu[$key3]['product'] = $product;
        };
        /*echo json_encode($set);die;*/
        foreach ($menu as $key4 => $value) {
            $list[]=$value;
        };





        //取餐饮零点
        $i2 = 100000;
        $staff = Staff::model()->findByPk($_GET['token']);
        $dish_type = DishType::model()->findAll();
        $result = yii::app()->db->createCommand("select supplier_product.id as CR_ID,supplier_product.name as CR_Name,ref_pic_url as CR_Path,description as CR_Remarks,dish_type.id as CI_ID,unit_price,unit from supplier_product left join dish_type on dish_type=dish_type.id where product_show=1 and account_id=".$staff['account_id']);
        $supplier_product = $result->queryAll();
        foreach ($dish_type as $key_type => $value_type) {
            $item = array();
            $item['CI_ID'] = $i2+$value_type['id'];
            $item['CI_Name'] = $value_type['name'];
            $item['CI_Place'] = "";

            $t = explode(".", $value_type['pic']);
            if(isset($t[0]) && isset($t[1])){
                $pic = "http://file.cike360.com".$t[0].".".$t[1];
            }else{
                $pic = "";
            };
            $item['CI_Pic'] = $pic;
            $item['CI_Time'] = "";
            $item['CI_CreateTime'] = "";
            $item['CI_Sort'] = "1";
            $item['CI_Show'] = "1";
            $item['CI_Type'] = 10;
            $item['CT_ID'] = 0;
            $item['resources'] = array();
            foreach ($supplier_product as $key_pro => $value_pro) {
                $tem = array();
                if($value_pro['CI_ID'] == $value_type['id']){
                    $tem['CR_ID'] = $i2*2+$value_pro['CR_ID'];
                    $tem['CR_Name'] = $value_pro['CR_Name'];

                    $t = explode(".", $value_pro['CR_Path']);
                    if(isset($t[0]) && isset($t[1])){
                        $pic = "http://file.cike360.com".$t[0].".".$t[1];
                    }else{
                        $pic = "";
                    };
                    $tem['CR_Path'] = $pic;
                    $tem['CR_Sort'] = 1;
                    $tem['CR_Show'] = 1;
                    $tem['CR_Remarks'] = $value_pro['CR_Remarks'];
                    $tem['CR_Type'] = 1;
                    $tem['product'] = array();
                    $tem_p = array();
                    $tem_p['id'] = $value_pro['CR_ID'];
                    $tem_p['name'] = $value_pro['CR_Name'];
                    $tem_p['unit_price'] = $value_pro['unit_price'];
                    $tem_p['unit'] = $value_pro['unit'];
                    $tem_p['description'] = $value_pro['CR_Remarks'];
                    $tem_p['ref_pic_url'] = $pic;
                    $tem['product'][] = $tem_p;

                    $item['resources'][] = $tem;
                };
            };
            $item['product'] = array();
            $list[] = $item;
        }

        //取主持人
        $url ="http://file.cike360.com";
        $staff_id = $_GET['token'];
        //type 1 公司 2 分店 3 个人
        $result = yii::app()->db->createCommand("select * from case_info where "./*

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 )  and ".*/

            " CI_Show=1 and CI_Type=6 order by CI_Sort Desc");
            
        $host = $result->queryAll();

        /*$list = findAllBySql("select * from case_info where ".

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=:id) ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=:id) ) ".
            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID= :id ))  ".
            " and CI_Show=1 order by CI_Sort Desc" ,array(':id'=>$staff_id)); */
        foreach($host as  $key => $val){
            if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                /*$t=explode(".", $val["CI_Pic"]);
                $CI_Pic = "";
                if(isset($t[0]) && isset($t[1])){
                    $CI_Pic = $t[0]."_sm.".$t[1];    
                }else{
                    $CI_Pic = $val['CI_Pic'];
                };*/
                $host[$key]["CI_Pic"]=$url.$val['CI_Pic'];
            };
            //$val["size"]=$this->getUrlFileSize($val["CI_Pic"]);
            /*$resources = CaseResources::model()->findAll(array(
                    'condition' => 'CI_ID=:CI_ID',
                    'params' => array(
                            ':CI_ID' => $val["CI_ID"]
                        )
                )); */
  
            $result1 = yii::app()->db->createCommand("select case_resources.CR_ID,case_resources.CI_ID,case_resources.CR_Type,case_resources.CR_Sort,case_resources.CR_Name,case_resources.CR_Path,CR_Show,CR_Remarks from case_resources left join case_resources_product on case_resources_product.CR_ID=case_resources.CR_ID where CI_ID =".$val["CI_ID"]." order by case_resources.CR_Sort");
            
            $resources = $result1->queryAll();
            $jsonresources = array();
            $cur_resourceobj=null;
            $cur_crid = 0;
            //$cur_product = null;
            //$i = 0;
            $cur_product = array();
            foreach ($resources as $rkey => $rval) {
                $resourceobj =array(
                    "CR_ID"=>$rval["CR_ID"],
                    "CR_Name"=>$val["CI_Name"],
                    "CR_Path"=>$rval["CR_Path"],
                    "CR_Sort"=>$rval["CR_Sort"],
                    "CR_Show"=>$rval["CR_Show"],
                    "CR_Remarks"=>$rval["CR_Remarks"],
                    "CR_Type"=>$rval["CR_Type"]
                    );
                if(!$this->startwith($rval["CR_Path"],"http://")&&!$this->startwith($rval["CR_Path"],"https://")){
                    $resourceobj["CR_Path"]=$url.$rval["CR_Path"];    
                }
                
                $cur_crid = $rval["CR_ID"];
                // $cur_resourceobj=$resourceobj;
                $supplier_product = yii::app()->db->createCommand(
                    "select supplier_product.id as id,supplier_product.name as name,unit_price,unit,description,ref_pic_url from supplier_product ".
                    " left join supplier on supplier_id = supplier.id".
                    " left join case_info on case_info.CT_ID = supplier.staff_id ".
                    " where CI_ID=".$rval['CI_ID']." and CI_Type=6 and supplier_product.account_id in (select account_id from staff where id = ".$staff_id.") and supplier_product.supplier_type_id=3");
                
                $supplier_product = $supplier_product->queryAll();

                // print_r($supplier_product);
                foreach ($supplier_product as $key_prod => $value_prod) {
                    if(!empty($value_prod)){
                        $t=explode(".", $value_prod["ref_pic_url"]);
                        if(isset($t[0]) && isset($t[1])){
                            $ref_pic_url = $t[0]."_sm.".$t[1];    
                        }else{
                            $ref_pic_url = $rval['ref_pic_url'];
                        };
                        $productobj=array(
                            "id"=>$value_prod["id"],
                            "name"=>$value_prod["name"],
                            "unit_price"=>$value_prod["unit_price"],
                            "unit"=>$value_prod["unit"],
                            "description"=>$value_prod["description"],
                            "ref_pic_url"=>"http://file.cike360.com".$ref_pic_url
                            );
                        $cur_product[]=$productobj;
                    }
                    else{
                        $resourceobj["product"]=array();
                    }; 
                };
                $resourceobj["product"]=$cur_product;
                if(/*$cur_crid!=$rval["CR_ID"]&&*/$cur_crid!=0){
                    $jsonresources[]=$resourceobj;
                    //$cur_resourceobj=null;
                    $cur_product=array();
                };
                if($cur_crid==0){
                    $jsonresources[] = $resourceobj;
                };
            }
            $host[$key]["resources"]= $jsonresources;
            $host[$key]['product'] = array();
        };
        foreach ($host as $key => $value) {
            $list[] = $value;
        };



        //取门店介绍
        $result = yii::app()->db->createCommand("select i.CI_ID,CI_Name,CI_Place,CI_Pic,CI_Time,CI_CreateTime,CI_Sort,CI_Show,CI_Remarks,CI_Type,CT_ID from case_info i left join case_bind on i.CI_ID=case_bind.CI_ID where i.CI_Type=16 and case_bind.TypeID=".$staff['account_id']);
        $result = $result->queryAll();

        foreach($result as  $key => $val){
            $result[$key]["CI_Pic"]=$url.$val['CI_Pic'];
            
            $result1 = yii::app()->db->createCommand("select case_resources.CR_ID,case_resources.CR_Type,case_resources.CR_Sort,case_resources.CR_Name,case_resources.CR_Path,CR_Show,CR_Remarks,supplier_product.id,supplier_product.name,supplier_product.unit_price,supplier_product.unit,supplier_product.ref_pic_url,supplier_product.description from case_resources left join case_resources_product on case_resources_product.CR_ID=case_resources.CR_ID left join supplier_product on case_resources_product.supplier_product_id=supplier_product.id where CI_ID =".$val["CI_ID"]." order by case_resources.CR_Sort");
            
            $resources = $result1->queryAll();
            $jsonresources = array();
            $cur_resourceobj=null;
            $cur_crid = 0;
            //$cur_product = null;
            //$i = 0;
            $cur_product = array();
            foreach ($resources as $rkey => $rval) {
                $resourceobj =array(
                    "CR_ID"=>$rval["CR_ID"],
                    "CR_Name"=>$rval["CR_Name"],
                    "CR_Path"=>$rval["CR_Path"],
                    "CR_Sort"=>$rval["CR_Sort"],
                    "CR_Show"=>$rval["CR_Show"],
                    "CR_Remarks"=>$rval["CR_Remarks"],
                    "CR_Type"=>$rval["CR_Type"]
                    );
                if(!$this->startwith($rval["CR_Path"],"http://")&&!$this->startwith($rval["CR_Path"],"https://")){
                    $resourceobj["CR_Path"]=$url.$rval["CR_Path"];    
                }

                $cur_crid = $rval["CR_ID"];
                // $cur_resourceobj=$resourceobj;
                if($rval["id"]!=null){
                    $t=explode(".", $rval["ref_pic_url"]);
                    if(isset($t[0]) && isset($t[1])){
                        $ref_pic_url = $t[0]."_sm.".$t[1];    
                    }else{
                        $ref_pic_url = $rval['ref_pic_url'];
                    };
                    $productobj=array(
                        "id"=>$rval["id"],
                        "name"=>$rval["name"],
                        "unit_price"=>$rval["unit_price"],
                        "unit"=>$rval["unit"],
                        "description"=>$rval["description"],
                        "ref_pic_url"=>"http://file.cike360.com".$ref_pic_url
                        );
                    $cur_product[]=$productobj;
                    $resourceobj["product"]=$cur_product;
                }
                else{
                    $resourceobj["product"]=array();
                }
                if(/*$cur_crid!=$rval["CR_ID"]&&*/$cur_crid!=0){
                    $jsonresources[]=$resourceobj;
                    //$cur_resourceobj=null;
                    $cur_product=array();
                };
                if($cur_crid==0){
                    $jsonresources[] = $resourceobj;
                };
                $resourceobj = array();
            }
            $result[$key]["resources"]= $jsonresources;
            $result[$key]['product'] = array();
        };
        foreach ($result as $key => $value) {
            $list[]=$value;
        };



        echo json_encode($list);

    }

    public function actionOrderlist()
    {
        //$_POST['token']=100;
        $result = yii::app()->db->createCommand("select `order`.id as orderid,order_status as orderstatus,order_name as ordername,staff.name as designername,`order`.order_date as orderdate from `order` left join staff on `order`.designer_id=staff.id where designer_id=".$_GET['token']." order by orderdate DESC");
        $result = $result->queryAll();
        foreach ($result as $key => $value) {
            $product = yii::app()->db->createCommand("select order_product.id as productid,sort,supplier_product.name as productname,order_product.actual_price as unitprice,product_type as producttype,order_product.unit as amount,supplier_product.unit,supplier_product.ref_pic_url as img from order_product left join supplier_product on order_product.product_id=supplier_product.id where order_product.order_id=".$value['orderid']." order by sort");
            $product = $product->queryAll();
            foreach ($product as $key1 => $value1) {
                $t=explode(".", $value1['img']);
                if(isset($t[0]) && isset($t[1])){
                    $product[$key1]['img'] = "http://file.cike360.com".$t[0]."_sm.".$t[1];
                };
            };
            $result[$key]['product']=$product;
        };
        foreach ($result as $key => $value) {
            if($value['orderstatus'] == 1){
                $result[$key]['orderstatus'] = "未交订金";
            };
            if($value['orderstatus'] == 2){
                $result[$key]['orderstatus'] = "已交订金";
            };
            if($value['orderstatus'] == 3){
                $result[$key]['orderstatus'] = "付中期款";
            };
            if($value['orderstatus'] == 4){
                $result[$key]['orderstatus'] = "已付尾款";
            };
            if($value['orderstatus'] == 5){
                $result[$key]['orderstatus'] = "结算中";
            };
            if($value['orderstatus'] == 6){
                $result[$key]['orderstatus'] = "已完成";
            };
            $t = explode(" ", $value['orderdate']);
            $result[$key]['orderdate'] = $t[0];
        }

        echo json_encode($result);
        
    }   

    public function actionNeworderlist()
    {
        $result = yii::app()->db->createCommand("select o.id,s.name,order_date,order_type,order_name,order_status from `order` o left join staff_hotel s on staff_hotel_id=s.id where designer_id=".$_GET['token']." or planner_id=".$_GET['token']);
        $result = $result->queryAll();

        $order_list = "(";
        $arr_order = array();
        foreach ($result as $key => $value) {
            $order_list .= $value['id'].",";
            $t=explode(' ', $value['order_date']);
            $item=array();
            $item['hotel_name'] = $value['name'];
            $item['order_id'] = $value['id'];
            $item['order_date'] = $t[0];
            $item['order_name'] = $value['order_name'];
            $item['total_price'] = 0;
            $item['img_url'] = "";
            if($value['order_type'] == 1){
                $item['order_type'] = "会议";
            }else{
                $item['order_type'] = "婚礼";
            };
            if($value['order_status'] == 0){
                $item['order_status'] = '待定';
            }else if($value['order_status'] == 1){
                $item['order_status'] = '预定';
            }else if($value['order_status'] == 2){
                $item['order_status'] = '已交定金';
            }else if($value['order_status'] == 3){
                $item['order_status'] = '付中期款';
            }else if($value['order_status'] == 4){
                $item['order_status'] = '已付尾款';
            }else if($value['order_status'] == 5){
                $item['order_status'] = '结算中';
            }else if($value['order_status'] == 6){
                $item['order_status'] = '已完成';
            };
            $arr_order[] = $item;
        };
        if($order_list != "("){
            $order_list = substr($order_list,0,strlen($order_list)-1);
        };
        $order_list .= ")";
        // echo $order_list;die;
        $data = new OrderForm;
        $order_price = $data -> many_order_price($order_list);  //计算所有订单的总价
        
        $result = yii::app()->db->createCommand("select s.order_id,i.img_url from order_show s left join `order` o on s.order_id=o.id left join order_show_img i on img_id=i.id left join order_show_img_type t on i.type=t.id where o.id in ".$order_list." and s.type=1 and t.id=1");
        $result = $result->queryAll();

        foreach ($arr_order as $key => $value) {
            foreach ($order_price as $key_p => $value_p) {
                if($value['order_id'] == $value_p['order_id']){
                    $arr_order[$key]['total_price'] = $value_p['total_price'];
                };
            };
            foreach ($result as $key_r => $value_r) {
                if($value['order_id'] == $value_r['order_id']){
                    $arr_order[$key]['img_url'] = $value_r['img_url'];
                };
            };
        };
        echo json_encode($arr_order);

    }

    public function actionOrderdetail()
    {
        // $post = json_decode(file_get_contents('php://input'));

        //取本订单 当前在order_show里的数据
        $result = yii::app()->db->createCommand("select s.id,s.type,i.img_url,s.order_product_id,sp.ref_pic_url,words,show_area,area_sort from order_show s ".
            "left join order_show_img i on s.img_id=i.id ".
            "left join order_product op on s.order_product_id=op.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "where s.order_id=".$_GET['order_id']);
        $result = $result->queryAll();

        //取本订单里的  order_product
        $result1 = yii::app()->db->createCommand("select op.id,op.order_set_id,ws.category as set_category,ws.name as set_name,st.name,op.actual_price,op.unit as amount,op.actual_unit_cost,op.actual_service_ratio,sp.name as product_name,sp.description,sp.ref_pic_url,sp.supplier_type_id,sp.unit,op.order_set_id,os.show_area ".
            "from order_product op ".
            "left join order_show os on op.id=os.order_product_id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "left join supplier_type st on sp.supplier_type_id=st.id ".
            "left join order_set on op.order_set_id=order_set.id ".
            "left join wedding_set ws on order_set.wedding_set_id=ws.id ".
            "where op.order_id=".$_GET['order_id']);
        $result1 = $result1->queryAll(); 

        //取本订单数据
        $order = Order::model()->findByPk($_GET['order_id']);

        // *******************************************************
        // *****************   构造 订单基本信息    *****************
        // *******************************************************
        
        $result4 = yii::app()->db->createCommand("select o.id,o.order_name,feast_discount,other_discount,discount_range,cut_price,planner_id,s1.name as planner_name,designer_id,s2.name as designer_name,staff_hotel_id,sh.name as hotel_name,groom_name,groom_phone,groom_wechat,groom_qq,bride_name,bride_phone,bride_phone,bride_wechat,bride_qq,contact_name,contact_phone from `order` o ".
            "left join staff_hotel sh on o.staff_hotel_id=sh.id ".
            "left join staff s1 on planner_id=s1.id ".
            "left join staff s2 on designer_id=s2.id ".
            "left join order_wedding ow on o.id=ow.order_id ".
            // "left join order_product op on o.id=op.order_id ".
            // "left join supplier_product sp on op.product_id=sp.id ".
            "where o.id=".$_GET['order_id']/*." and sp.supplier_type_id=16"*/);
        $result4 = $result4->queryAll();
        // print_r($result4);die;


        //构造统筹师、策划师列表
        $staff = yii::app()->db->createCommand("select * from staff where account_id in (select account_id from staff where id=".$_GET['token'].")");
        $staff = $staff->queryAll();

        $designer_list = array();
        $planner_list = array();

        foreach ($staff as $key => $value) {
            $t = rtrim($value['department_list'], "]");
            $t = ltrim($t, "[");
            $t = explode(',', $t);
            $d = 0;
            $p = 0;
            foreach ($t as $key_t => $value_t) {
                if($value_t == 2){$p++;};
                if($value_t == 3){$d++;};
            };
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            if($d != 0){
                $designer_list[]=$item;
            };
            if($p != 0){
                $planner_list[]=$item;
            };
        }

        //构造渠道列表
        $result = new ProductForm;
        $tuidan_list = $result->tuidan_list($_GET['token']);

        //构造折扣范围列表
        $discount = array();
        $discount['feast_discount'] = $result4[0]['feast_discount'];
        $discount['other_discount'] = $result4[0]['other_discount'];
        $discount['list'] = array();
        $descount_list = yii::app()->db->createCommand("select id,name from supplier_type where role=1");
        $descount_list = $descount_list->queryAll();
        if($discount['other_discount'] != 10 && isset($result4[0]['discount_range'])){
            $t=explode(',', $result4[0]['discount_range']);
            foreach ($descount_list as $key => $value) {
                $item = array();
                $item['id']=$value['id'];
                $item['name']=$value['name'];
                $item['select']=0;
                $m=0;
                foreach ($t as $key_t => $value_t) {
                    if($value['id'] == $value_t){
                        $m++;
                    };
                };
                if($m!=0){
                    $item['select']=1;
                };
                $discount['list'][]=$item;
            };
        }else{
            $item['id']=$value['id'];
            $item['name']=$value['name'];
            $item['select']=1;
            $discount['list'][]=$item;
        };
        

        // print_r($result3);die;
        $order_data = array(
                "id"=> $result4[0]['id'] ,
                "order_name"=> $result4[0]['order_name'] ,
                "planner_id"=> $result4[0]['planner_id'] ,
                "planner_name"=> $result4[0]['planner_name'] ,
                "designer_id"=> $result4[0]['designer_id'] ,
                "designer_name"=> $result4[0]['designer_name'] ,
                "staff_hotel_id"=> $result4[0]['staff_hotel_id'] ,
                "hotel_name"=> $result4[0]['hotel_name'] ,
                "groom_name"=> $result4[0]['groom_name'] ,
                "groom_phone"=> $result4[0]['groom_phone'] ,
                "groom_wechat"=> $result4[0]['groom_wechat'] ,
                "groom_qq"=> $result4[0]['groom_qq'] ,
                "bride_name"=> $result4[0]['bride_name'] ,
                "bride_phone"=> $result4[0]['bride_phone'] ,
                "bride_wechat"=> $result4[0]['bride_wechat'] ,
                "bride_qq"=> $result4[0]['bride_qq'] ,
                "contact_name"=> $result4[0]['contact_name'] ,
                "contact_phone"=> $result4[0]['contact_phone'],
                'designer_list'=> $designer_list,
                'planner_list'=> $planner_list,
                'tuidan_list'=> $tuidan_list,
                'discount'=> $discount,
                'cut_price'=> $result4[0]['cut_price'],
            );        
        $t=explode(' ', $order['order_date']);
        $order_data['order_date']=$t[0];
        $result3 = yii::app()->db->createCommand("select sp.id,sp.name from order_product op left join supplier_product sp on product_id=sp.id where op.order_id=".$_GET['order_id']." and sp.supplier_type_id=16");
        $result3 = $result3->queryAll();
        if(!empty($result3)){
            $order_data['tuidan_id']=$result3['id'];
            $order_data['tuidan_name']=$result3['name'];
        }else{
            $order_data['tuidan_id']="";
            $order_data['tuidan_name']="";
        };
        // print_r($order_data);die;



        // *******************************************************
        // ********************   构造 PPT    ********************
        // *******************************************************

        //比较 s | p
        $order_show_list = array();
        foreach ($result as $key => $value) {
            $item=array();
            $item['show_id']=$value['id'];
            $item['show_type']=$value['type'];
            $item['show_area']=$value['show_area'];
            $item['area_sort']=$value['area_sort'];
            if($value['type'] == 0){
                $item['show_data']=$value['words'];
                $item['product_id']=0;
            }else if($value['type'] == 1){
                $item['show_data']=$value['img_url'];
                $item['product_id']=0;
            }else if($value['type'] == 2){
                $item['show_data']=$value['ref_pic_url'];
                $item['product_id']=$value['order_product_id'];
            };
            $order_show_list[]=$item;
        };
        foreach ($result1 as $key => $value) {
            $t=0;
            foreach ($order_show_list as $key_s => $value_s) {
                if($value['id'] == $value_s['product_id']){
                    $t++;
                };
            };
            if($t == 0 && $value['supplier_type_id'] !=2){
                $admin=new OrderShow;
                $admin->type=2;
                $admin->order_product_id=$value['id'];
                $admin->order_id=$_GET['order_id'];
                $admin->show_area=0;
                $admin->area_sort=1;
                $admin->update_time=date('y-m-d h:i:s',time());
                $admin->save();
                $show_id = $admin->attributes['id'];

                $item=array();
                $item['show_id']=$show_id;
                $item['show_type']=2;
                $item['show_area']=0;
                $item['area_sort']=1;
                $item['show_data']=$value['ref_pic_url'];
                $item['product_id']=$value['id'];
                $order_show_list[]=$item;
            };
        };
        $order_show = array();
        $area = OrderShowArea::model()->findAll();
        foreach ($area as $key => $value) {
            $tem=array();
            $tem['area_id'] = $value['id'];
            $tem['area_name'] = $value['name'];
            $tem['description'] = $value['description'];
            $tem['data']=array();
            foreach ($order_show_list as $key_l => $value_l) {
                if($value_l['show_area'] == $value['id']){
                    $item=array();
                    $item['data_type']=$value_l['show_type'];
                    $item['show_data']=$value_l['show_data'];
                    $item['product_id']=$value_l['product_id'];
                    $tem['data'][]=$item;
                };
            };
            $order_show[]=$tem;
        };
        $tem=array();
        $tem['area_id']=0;
        $tem['area_name']='待分配产品';
        $tem['description']="";
        $tem['data']=array();
        foreach ($order_show_list as $key_l => $value_l) {
            if($value_l['show_area'] == 0){
                $item=array();
                $item['data_type']=$value_l['show_type'];
                $item['show_data']=$value_l['show_data'];
                $item['product_id']=$value_l['product_id'];
                $tem['data'][]=$item;
            };
        };
        $order_show[]=$tem;


        // *******************************************************
        // ********************   构造报价单    ********************
        // *******************************************************

        //有区域产品，按区域分组，并加总，计算出总价、折后总价；
        $area_product = array();
        $area = OrderShowArea::model()->findAll();
        $discount_range=explode(',', $order['discount_range']);
        foreach ($area as $key => $value) {
            $tem=array();
            $tem['area_id']=$value['id'];
            $tem['area_name']=$value['name'];
            $tem['product_list'] = array();
            $tem['area_total'] = 0;
            $tem['discount_total'] = 0;
            foreach ($result1 as $key_p => $value_p) {
                if($value['id'] == $value_p['show_area']){
                    $item=array();
                    $item['product_id']=$value_p['id'];
                    $item['product_name']=$value_p['product_name'];
                    $item['description']=$value_p['description'];
                    $item['ref_pic_url']=$value_p['ref_pic_url'];
                    $item['price']=$value_p['actual_price'];
                    $item['amount']=$value_p['amount'];
                    $item['unit']=$value_p['unit'];
                    $item['cost']=$value_p['actual_unit_cost'];
                    $item['set']="";
                    if($value_p['order_set_id'] != 0){
                        $item['set']="套系产品";
                    };
                    $tem['product_list'][]=$item;
                    $tem['area_total'] += $item['price']*$item['amount'];
                    $t = 0;
                    foreach ($discount_range as $key_r => $value_r) {
                        if($value_r == $value_p['supplier_type_id']){
                            $t++;
                        };
                    };
                    if($t!=0){
                        $tem['discount_total'] += $item['price']*$item['amount']*$order['other_discount'];
                    }else{
                        $tem['discount_total'] += $item['price']*$item['amount'];
                    }
                };
            };
            $area_product[] = $tem;
        };

        //无区域产品，列出来
        $non_area_product = array();
        foreach ($result1 as $key => $value) {
            if($value['show_area'] == 0){
                $item=array();
                $item['product_id']=$value_p['id'];
                $item['product_name']=$value_p['product_name'];
                $item['description']=$value_p['description'];
                $item['ref_pic_url']=$value_p['ref_pic_url'];
                $item['price']=$value_p['actual_price'];
                $item['amount']=$value_p['amount'];
                $item['unit']=$value_p['unit'];
                $item['cost']=$value_p['actual_unit_cost'];
                $item['set']="";
                if($value_p['order_set_id'] != 0){
                    $item['set']="套系产品";
                };
                $non_area_product[]=$item;
            };
        };

        //取套餐数据（婚宴、婚礼）
        $result2 = yii::app()->db->createCommand("select os.id,os.order_id,os.amount,os.actual_service_ratio,os.remark,ws.id as ws_id,ws.name,ws.category ".
            " from order_set os left join wedding_set ws on wedding_set_id=ws.id ".
            " where os.order_id=".$_GET['order_id']);
        $result2 = $result2->queryAll();

        $set_data = array();
        $set_data['feast']=array();
        $set_data['other']=array();
        foreach ($result2 as $key_s => $value_s) {
            $tem=array();
            $tem['order_set_id']=$value_s['id'];
            $tem['set_name']=$value_s['name'];
            $tem['amount']=$value_s['amount'];
            $tem['actual_service_ratio']=$value_s['actual_service_ratio'];
            $tem['remark']=$value_s['remark'];
            $tem['total_price']=0;
            $tem['product_list']=array();
            foreach ($result1 as $key_p => $value_p) {
                if($value_p['order_set_id'] == $value_s['id']){
                    $item=array();
                    $item['product_id']=$value_p['id'];
                    $item['product_name']=$value_p['product_name'];
                    $item['description']=$value_p['description'];
                    $item['ref_pic_url']=$value_p['ref_pic_url'];
                    $item['price']=$value_p['actual_price'];
                    $item['amount']=$value_p['amount'];
                    $item['unit']=$value_p['unit'];
                    $item['cost']=$value_p['actual_unit_cost'];
                    $tem['product_list'][]=$item;
                    if($value_s['category'] == 3 || $value_s['category'] == 4){
                        $tem['total_price'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01);
                    }else{
                        $tem['total_price'] += $value_p['actual_price']*$value_p['amount'];
                    };
                };
            };
            foreach ($result1 as $key_p => $value_p) {
                if($value_p['supplier_type_id'] == 2 && $value_p['order_set_id'] == 0){
                    $item=array();
                    $item['product_id']=$value_p['id'];
                    $item['product_name']=$value_p['product_name'];
                    $item['description']=$value_p['description'];
                    $item['ref_pic_url']=$value_p['ref_pic_url'];
                    $item['price']=$value_p['actual_price'];
                    $item['amount']=$value_p['amount'];
                    $item['unit']=$value_p['unit'];
                    $item['cost']=$value_p['actual_unit_cost'];
                    if($value_s['category'] == 3 || $value_s['category'] == 4){
                        $tem['product_list'][]=$item;
                        $tem['total_price'] += $value_p['actual_price']*$value_p['amount']*(1+$value_s['actual_service_ratio']*0.01);
                    };
                };
            };
            if($value_s['category']==3 || $value_s['category']==4){
                $set_data['feast'][]=$tem;
            }else{
                $set_data['other'][]=$tem;
            };
        };

        $order_detail = array();
        $order_detail['order_data'] = $order_data;
        $order_detail['order_show'] = $order_show;
        $order_detail['area_product'] = $area_product;
        $order_detail['non_area_product'] = $non_area_product;
        $order_detail['set_data'] = $set_data;


        echo json_encode($order_detail);
    }

    public function actionProductstore()
    {
        $staff = Staff::model()->findByPk($_GET['token']);
        $result = yii::app()->db->createCommand("select * from supplier_product sp ".
            // "left join supplier_product_decoration_tap dt on sp.decoration_tap=dt.id ".
            " where account_id=".$staff['account_id']);
        $result = $result->queryAll();

        $product_store = array(
                '场地布置' => array(),
                '主持' => array(),
                '化妆' => array(),
                '摄影' => array(),
                '摄像' => array(),
                '其他' => array(),
                '灯光设备' => array(),
                '视频设备' => array(),
                '音响设备' => array(),
            );
        $tap = yii::app()->db->createCommand("select id,name from supplier_product_decoration_tap where account_id=".$staff['account_id']);
        $tap = $tap->queryAll();
        foreach ($tap as $key => $value) {
            $tap[$key]['product_list']=array();
            foreach ($result as $key_p => $value_p) {
                if($value_p['decoration_tap']==$value['id']){
                    $tap[$key]['product_list'][]=$value_p;
                };
                if($value_p['supplier_type_id']==3){
                    $product_store['主持'][]=$value_p;
                };
                if($value_p['supplier_type_id']==6){
                    $product_store['化妆'][]=$value_p;
                };
                if($value_p['supplier_type_id']==5){
                    $product_store['摄影'][]=$value_p;
                };
                if($value_p['supplier_type_id']==4){
                    $product_store['摄像'][]=$value_p;
                };
                if($value_p['supplier_type_id']==7){
                    $product_store['其他'][]=$value_p;
                };
                if($value_p['supplier_type_id']==8){
                    $product_store['灯光设备'][]=$value_p;
                };
                if($value_p['supplier_type_id']==9){
                    $product_store['视频设备'][]=$value_p;
                };
                if($value_p['supplier_type_id']==23){
                    $product_store['音响设备'][]=$value_p;
                };
            };
        };
        foreach ($tap as $key => $value) {
            $product_store['场地布置'][$value['name']]=$value['product_list'];
        };

            
        echo json_encode($product_store);
    }

    public function actionWords_insert()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
        //         'words' => "ceshiwords",
        //         'order_id' => 1033,
        //         'area' => 3,
        //         'sort' => 2,
        //     );

        $order_show=new OrderShowForm;
        $order_show->words_insert($post->words,$post->order_id,$post->area,$post->sort);

    }

    public function actionImg_insert()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
        //         'type' => 1,
        //         'url' => "ceshi",
        //         'staff_id' => 100,
        //         'order_id' => 1033,
        //         'area' => 3,
        //         'sort' => 2,
        //     );

        $order_show=new OrderShowForm;
        $order_show->img_insert($post->type,$post->url,$post->staff_id,$post->order_id,$post->area,$post->sort);
    }

    public function actionProduct_insert()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
        //         'area' => 3,
        //         'sort' => 1,
        //         'order_id' => 1033,
        //         'sp_id' => 109,
        //     );

        $order_show=new OrderShowForm;
        $order_show->product_insert($post->area,$post->sort,$post->order_id,$post->sp_id);
    }

    public function actionNew_product_insert()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
        //         'account_id' => 3,
        //         'supplier_id' => 2,
        //         'supplier_type_id' => 2,
        //         'decoration_tap' => 3,
        //         'name' => 'ceshi',
        //         'category' => 2,
        //         'price' => 3,
        //         'cost' => 3,
        //         'unit' => '个',
        //         'remark' => 'ceshi',
        //         'url' => 3,
        //         'amount' => 1,
        //         'order_id' => 1033,
        //         'area' => 1,
        //         'sort' => 1,
        //     );

        $order_show=new OrderShowForm;
        $order_show->new_product_insert($post->account_id,$post->supplier_id,$post->supplier_type_id,$post->decoration_tap,$post->name,$post->category,$post->price,$post->cost,$post->unit,$post->remark,$post->url,$post->order_id,$post->order_id,$post->area,$post->sort);
    }

    public function actionSave_ppt()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
        //         'order_id' => 1033,
        //         'list' => array(
        //                 0 => array('os_id'=> 1,'area_sort'=> 4,'show_area'=>1),
        //                 1 => array('os_id'=> 2,'area_sort'=> 5,'show_area'=>2),
        //                 2 => array('os_id'=> 3,'area_sort'=> 6,'show_area'=>3),
        //                 3 => array('os_id'=> 4,'area_sort'=> 7,'show_area'=>4),
        //             )
        //     );

        

        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $post->order_id,
                    )
            ));
        //删除  list中不存在的order_show
        foreach ($order_show as $key => $value) {
            $t = 0;
            foreach ($post->list as $key_l => $value_l) {
                if($value['id'] == $value_l['os_id']){
                    $t++;
                };        
            };
            if($t == 0){
                if($value['type'] == 0){
                    //删除order_show
                    OrderShow::model()->deleteByPk($value['id']);
                };
                if($value['type'] == 1){
                    //删除order_show  
                    OrderShow::model()->deleteByPk($value['id']);
                };
                if($value['type'] == 2){
                    //删除order_show   &&  order_product
                    OrderShow::model()->deleteByPk($value['id']);
                    OrderProduct::model()->deleteByPk($value['order_product_id']);
                };
            }
        };

        //更新order_show   area_sort
        $sql = "";
        foreach ($post->list as $key => $value) {
            $sql .= " when id=".$value['os_id']." then ".$value['area_sort'];
        };
        $result = yii::app()->db->createCommand("update order_show set area_sort = case ".$sql." else area_sort end");
        $result = $result->queryAll();

        //更新order_show   show_area
        $sql = "";
        foreach ($post->list as $key => $value) {
            $sql .= " when id=".$value['os_id']." then ".$value['show_area'];
        };
        $result = yii::app()->db->createCommand("update order_show set show_area = case ".$sql." else show_area end");
        $result = $result->queryAll();
    }

    public function actionUpdate_op()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
        //         'op_id' => 4037,
        //         'actual_price' => 109,
        //         'amount' => 109,
        //         'actual_unit_cost' => 109
        //     );

        OrderProduct::model()->updateByPk($post->op_id,array('actual_price'=>$post->actual_price,'unit'=>$post->amount,'actual_unit_cost'=>$post->actual_unit_cost));
    }

    public function actionUpdate_menu()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
        //     'order_id' => ,
        //     'wedding_set_id' => ,
        //     'table_amount' => ,
        //     'remark' => ,
        //     'actual_service_ratio' => ,
        //     'list' => array(
        //             0 => array(
        //                     'op_id' => ,
        //                     'actual_price' => ,
        //                 )
                    
        //         ),
        // );   

        //编辑order_set
        OrderSet::model()->updateAll(array('amount'=>$post->table_amount,'remark'=>$post->remark,'actual_service_ratio'=>$post->actual_service_ratio),'order_id=:order_id && wedding_set_id=:wedding_set_id',array(':order_id'=>$post->order_id,':wedding_set_id'=>$post->wedding_set_id));
    
        //删除菜品
        $result = yii::app()->db->createCommand("select op.id ".
            "from order_product op left join supplier_product sp on op.product_id=sp.id ".
            "where op.order_id=".$post->order_id." and sp.supplier_type_id=2");
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            $t = 0;
            foreach ($post->list as $key_l => $value_l) {
                if($value['id'] == $value_l['op_id']){
                    $t++;
                };
            };
            if($t == 0){
                OrderProduct::model()->deleteByPk($value['id']);
            };
        };



        //更新菜品   actual_price
        $sql = "";
        foreach ($post->list as $key => $value) {
            $sql .= " when id=".$value['op_id']." then ".$value['actual_price'];
        };
        $result = yii::app()->db->createCommand("update order_product set actual_price = case ".$sql." else actual_price end");
        $result = $result->queryAll();

        //更新菜品   unit
        $sql = "";
        foreach ($post->list as $key => $value) {
            $sql .= " when id=".$value['op_id']." then ".$value['unit'];
        };
        $result = yii::app()->db->createCommand("update order_product set unit = case ".$sql." else unit end");
        $result = $result->queryAll();

        //更新菜品   actual_unit_cost
        $sql = "";
        foreach ($post->list as $key => $value) {
            $sql .= " when id=".$value['op_id']." then ".$value['actual_unit_cost'];
        };
        $result = yii::app()->db->createCommand("update order_product set actual_unit_cost = case ".$sql." else actual_unit_cost end");
        $result = $result->queryAll();

        //更新菜品   actual_service_ratio
        $sql = "";
        foreach ($post->list as $key => $value) {
            $sql .= " when id=".$value['op_id']." then ".$value['actual_service_ratio'];
        };
        $result = yii::app()->db->createCommand("update order_product set actual_service_ratio = case ".$sql." else actual_service_ratio end");
        $result = $result->queryAll();   
    }

    public function actionUpdate_designer()  //  改 策划师
    {
        $post = json_decode(file_get_contents('php://input'));
        echo $post->order_id;

        Order::model()->updateByPk($post->order_id,array('designer_id'=>$post->designer_id));
    }

    public function actionUpdate_planner()   //  改 统筹师
    {
        $post = json_decode(file_get_contents('php://input'));

        Order::model()->updateByPk($post->order_id,array('planner_id'=>$post->planner_id));
    }

    public function actionUpdate_feast_discount()  //  改 餐饮折扣
    {
        $post = json_decode(file_get_contents('php://input'));

        Order::model()->updateByPk($post->order_id,array('feast_discount'=>$post->feast_discount));
    }

    public function actionUpdate_other_discount()  //  改 其他折扣
    {
        $post = json_decode(file_get_contents('php://input'));

        Order::model()->updateByPk($post->order_id,array('other_discount'=>$post->other_discount,'discount_range'=>$post->discount_range));
    }

    public function actionUpdate_cut_price()  //  改 免零
    {
        $post = json_decode(file_get_contents('php://input'));

        Order::model()->updateByPk($post->order_id,array('cut_price'=>$post->cut_price));
    }

    public function actionUpdate_tuidan()  //  改  推单渠道
    {
        $post = json_decode(file_get_contents('php://input'));

        $result = yii::app()->db->createCommand("select o.id from order_product o left join supplier_product s on o.product_id=s.id where o.order_id=".$post->order_id." and s.supplier_type_id=16");
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            OrderProduct::model()->deleteByPk($value['id']);
        };

        if($post->product_id != 0){
            $order = Order::model()->findByPk($post->order_id);

            $data = new OrderProduct;
            $data->account_id=$order['account_id'];
            $data->order_id=$post->order_id;
            $data->product_type=0;
            $data->product_id=$post->product_id;
            $data->order_set_id=$order_set_id;
            $data->sort=1;
            $data->actual_price=0;
            $data->unit=1;
            $data->actual_unit_cost=0;
            $data->update_time=date('y-m-d h:i:s',time());
            $data->actual_service_ratio=0;
            $data->save();
        };
            
    }

    public function actionOrder_show()
    {
        $post = json_decode(file_get_contents('php://input'));

        $result = yii::app()->db->createCommand("select os.id,os.type,words,osi.img_url,sp.ref_pic_url,show_area,area_sort from order_show os ".
            "left join order_show_img osi on img_id=osi.id ".
            "left join order_product op on order_product_id=op.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "where os.order_id=".$post->order_id.
            " order by show_area ASC,area_sort ASC");
        $result = $result->queryAll();

        $show_data = array();
        foreach ($result as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['type'] = $value['type'];
            $item['show_area'] = $value['show_area'];
            $item['area_sort'] =$value['area_sort'];
            if($value['type'] == 0){
                $item['data'] = $value['words'];
            };
            if($value['type'] == 1){
                $item['data'] = $value['img_url'];
            };
            if($value['type'] == 2){
                $item['data'] = $value['ref_pic_url'];
            };
            $show_data[] = $item;
        };
        
        echo json_encode($show_data);
        
    }
    
    public function array_remove(&$arr,$offset) 
    { 
        array_splice($arr, $offset, 1); 
    } 

    public function actionCeshi()
    {

    }




























}
