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
        $url ="http://file.cike360.com";
        $staff_id = $_GET['token'];
        //type 1 公司 2 分店 3 个人
        $result = yii::app()->db->createCommand("select * from case_info where ".

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".
            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".
            " and CI_Show=1 order by CI_Sort Desc");
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
                $list[$key]["CI_Pic"]=$url.$val["CI_Pic"];
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
                    $productobj=array(
                        "id"=>$rval["id"],
                        "name"=>$rval["name"],
                        "unit_price"=>$rval["unit_price"],
                        "unit"=>$rval["unit"],
                        "description"=>$rval["description"],
                        "ref_pic_url"=>$rval["ref_pic_url"]
                        );
                    $cur_product[]=$productobj;
                    $cur_resourceobj["product"]=$cur_product;
                }
                else{
                    $cur_resourceobj["product"]=array();
                }


                // if($rkey == 0){
                //     print_r($rval['CR_ID']);die;
                //     $cur_resource = $rval['CR_ID'];
                //     if($rval['id'] != null){

                //         $product[] = array_splice($resources[$rkey], 7);
                //     }else{
                //         /*echo "1";die;*/
                //         array_splice($resources[$rkey], 7);
                //         /*print_r($resources[$rkey]);die;*/
                //     };
                //     /*print_r($product);die;*/
                // }else{
                //     if($rval['CR_ID'] == $cur_resource){
                //         $product[] = array_splice($resources[$rval], 7);
                //         /*print_r($product);die;*/
                //         $this->array_remove($resources, $rval-$i);
                //         $i++;
                //     }else{
                //         if($rval['id'] != null){
                //             $resources[$rkey-$i-1]['product']=$product;
                //             $cur_resource = $rval['CR_ID'];
                //             $product = array();
                //             $product[] = array_splice($resources[$rkey], 7);
                //         }else{
                //             $cur_resource = $rval['CR_ID'];
                //             /*print_r($list[$key_case]['resources'][$key_resource]);die;*/
                //             $product = array();
                //             $product[] = array_splice($resources[$rkey], 7);
                //         };
                        
                //     };
                // };
            
            /*echo json_encode($resources);die;*/
            //$rval["size"]=$this->getUrlFileSize($rval["CR_Path"]);
            }

            /*print_r($resources);die;*/


            $list[$key]["resources"]= $jsonresources;
        };
        /*print_r($list);die;*/
        /*$cur_resource = 0;
        $cur_product =0;
        $result = array();

        foreach ($list as $key_case => $case) {
            $product = array();
            $i = 0;
            foreach ( $case['resources'] as $key_resource => $resource ) {
                
            };
        };
*/




        /*$CR_ID_list = array();
        $CR_ID_list[0] = $list[0]['resources'][0]['CR_ID'];
        foreach ($list as $key => $value) {
            foreach ($value['resources'] as $key1 => $value1) {
                $t = 0;
                if($value1['id'] != null){
                    foreach ($CR_ID_list as $key2 => $value2) {
                        if($value1['CR_ID'] == $value2){
                            $t++;
                        };
                    };
                    if($t == 0){
                        $CR_ID_list[]=$value1['CR_ID'];
                    };
                };
            };
        };*/
        /*print_r($CR_ID_list);die;*/
        /*echo json_encode($list);die;*/
        /*print_r($CR_ID_list);die;*/
        /*foreach ($CR_ID_list as $key => $value) {
            $item = array();

            foreach ($list as $key1 => $value1) {
                $CR_in_CI = 0;
                $chongfu = 0;
                $i=0;
                foreach ($value1['resources'] as $key2 => $value2) {
                    if($value2['CR_ID'] == $value){
                        $CR_in_CI++;
                        if($chongfu == 0){
                            $item1 = array_splice($list[$key1]['resources'][$key2-$i], 7);
                            $item[] = $item1;*/
                            /*print_r($value2);die; */
                            /*$chongfu++;
                        }else{
                            $item1 = array_splice($list[$key1]['resources'][$key2-$i], 7);
                            $item[] = $item1;
                            $this->array_remove($list[$key1]['resources'], $key2-$i);
                            $i = $i + 1;
                        };
                    }else{
                        print_r($key2);
                        $this->array_remove($list[$key1]['resources'], $key2);
                        $list[$key1]['resources'][$key2-$i]['product']=array();
                    }
                };
                foreach ($value1['resources'] as $key3 => $value3) {
                    if($value3['CR_ID'] == $value){
                        $list[$key1]['resources'][$key3]['product']=$item;
                    };
                };
            };
            echo json_encode($item);
            unset($item);*/
            /*foreach ($list as $key1 => $value1) {
                foreach ($value1['resources'] as $key2 => $value2) {
                    if($value != $value2['CR_ID']){
                        $this->array_remove($list[$key1]['resources'], $key2);
                        $list[$key1]['resources'][$key2]['product']=array();
                    };
                };
            };*/
        /*};*/
        echo json_encode($list);

    }

    public function actionOrderlist()
    {
        /*$order_id = yii::app()->db->createCommand("select id from order where designer_id=".$_GET['token']);
        $order_id = $order_id->queryAll();*/
        $order_id=Order::model()->findAll(array(  
            'select'=>'id',  
            'condition'=>'designer_id=:designer_id',  
            'params'=>array(':designer_id'=>$_GET['token']),  
        ));  
        $id_list = "(";
        foreach ($order_id as $key => $value) {
            $id_list .= $value['id'];
            $id_list .= ",";
        };
        $id_list = substr($id_list,0,strlen($id_list)-1); 
        $id_list .= ")";
        /*print_r($id_list);die;*/

        $result = yii::app()->db->createCommand("select * from order_product left join supplier_product on order_product.product_id=supplier_product.id where order_product.order_id in".$id_list);
        $result = $result->queryAll();
        echo json_encode($result);
        /*print_r($order_id);*/

    }   
    
    public function array_remove(&$arr,$offset) 
    { 
        array_splice($arr, $offset, 1); 
    } 
}
