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
                        ':telephone' => $_POST['username']
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
        $result = yii::app()->db->createCommand("select * from case_info where ".

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 )  ".

            " and CI_Show=1 and CI_Type in (1,2,3) order by CI_Sort Desc");
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
                
                if($cur_crid!=$rval["CR_ID"]&&$cur_crid!=0){
                    $jsonresources[]=$cur_resourceobj;
                    //$cur_resourceobj=null;
                    $cur_product=array();
                }
                $cur_crid = $rval["CR_ID"];
                $cur_resourceobj=$resourceobj;
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
                    $cur_resourceobj["product"]=$cur_product;
                }
                else{
                    $cur_resourceobj["product"]=array();
                }
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







        //取套系
        $set = yii::app()->db->createCommand("select * from case_info where ".

            "(( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 ))  ".

            " and CI_Show=1 and CI_Type=5 order by CI_Sort Desc");
        $set = $set->queryAll();
        /*print_r($set);die;*/
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
                
                if($cur_crid!=$rval["CR_ID"]&&$cur_crid!=0){
                    $jsonresources[]=$cur_resourceobj;
                    //$cur_resourceobj=null;
                    $cur_product=array();
                }
                $cur_crid = $rval["CR_ID"];
                $cur_resourceobj=$resourceobj;
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
                    $cur_resourceobj["product"]=$cur_product;
                }
                else{
                    $cur_resourceobj["product"]=array();
                }
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


        echo json_encode($list);

    }

    public function actionOrderlist()
    {
        //$_POST['token']=100;
        $result = yii::app()->db->createCommand("select `order`.id as orderid,order_status as orderstatus,order_name as ordername,staff.name as designername,`order`.order_date as orderdate from `order` left join staff on `order`.designer_id=staff.id where designer_id=".$_GET['token']." order by orderdate DESC");
        $result = $result->queryAll();
        foreach ($result as $key => $value) {
            $product = yii::app()->db->createCommand("select order_product.id as productid,sort,supplier_product.name as productname,order_product.actual_price as unitprice,product_type as producttype,order_product.unit as amount,supplier_product.unit from order_product left join supplier_product on order_product.product_id=supplier_product.id where order_product.order_id=".$value['orderid']." order by sort");
            $product = $product->queryAll();
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
    
    public function array_remove(&$arr,$offset) 
    { 
        array_splice($arr, $offset, 1); 
    } 
}
