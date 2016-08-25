<?php

include_once('../library/WPRequest.php');
include_once('../library/taobao-sdk-PHP-auto_1455552377940-20160505/TopSdk.php');

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
        // $post = json_decode(file_get_contents('php://input'));
        /*$post = array(
                'loginname' => '15101140405',
                'password' => '88888888'
            );*/

        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:telephone',
                'params' => array(
                        ':telephone' => $_POST['loginname']/*$post->loginname*//*$post['loginname']*/
                    )
            ));

        if($staff['password'] == $_POST['password']/*$post->password*//*$post['password']*/){
            echo json_encode(array('code'=>1,'token'=>$staff['id']));
        }else{
            echo json_encode(array('code'=>0,'token'=>''));
        };
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

    public function actionMyUpload(){
        
        /**************************************/
        /***************取案例******************/
        /**************************************/
        $url ="http://file.cike360.com";
        $staff_id = $_GET['token'];
        $staff = Staff::model()->findByPk($staff_id);
        //type 1 公司 2 分店 3 个人
        $result = yii::app()->db->createCommand("select * from case_info where ".

            "CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID=0) ".
            
            " or ( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
            "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 )  and ".

            " CI_Show=1 and CI_Type in (2) order by CT_ID DESC,CI_Sort ASC");
            
        $case = $result->queryAll();

        $case_data = array();
        foreach ($case as $key => $value) {
            if($value['CI_Type'] == 2 && $value['CI_Show'] == 1){
                $item = array();
                $item['CI_ID'] = $value['CI_ID'];
                $item['CI_Name'] = $value['CI_Name'];
                $item['CI_Pic'] = "http://file.cike360.com".$value['CI_Pic'];
                
                $video = CaseResources::model()->findAll(array(
                        'condition' => 'CI_ID=:CI_ID && CR_Type=:CR_Type',
                        'params' => array(
                                ':CI_ID' => $value['CI_ID'],
                                ':CR_Type' => 2
                            )
                    ));
                $item['video'] = array();
                foreach ($video as $key1 => $value1) {
                    $item_video = array();
                    $item_video['CR_ID'] = $value1['CR_ID'];
                    $item_video['CR_Path'] = "http://file.cike360.com".$value1['CR_Path'];
                    $item['video'][] = $item_video;
                }; 
                $case_data[] = $item;
            }
                
        };


        /**************************************/
        /***************取套系******************/
        /**************************************/
        $set0 = yii::app()->db->createCommand("select * from case_info where ".
            " CI_Show=1 and CI_Type=5 and CT_ID in (".
            " select id from wedding_set where staff_hotel_id in (select id from staff_hotel where account_id=0)) order by CI_Sort Desc");
        $set0 = $set0->queryAll();
        $set_cur = yii::app()->db->createCommand("select * from case_info where ".
            " CI_Show=1 and CI_Type=5 and CT_ID in ( ".
            " select id from wedding_set where staff_hotel_id in (select id from staff_hotel where account_id=".$staff['account_id'].")) order by CI_Sort Desc");
        $set_cur = $set_cur->queryAll();

        $set_data = array();
        foreach ($set0 as $key => $value) {
            $item = array();
            $item['CI_ID'] = $value['CI_ID'];
            $item['CI_Name'] = $value['CI_Name'];
            $item['CI_Pic'] = "http://file.cike360.com".$value['CI_Pic'];
            $set_data[] = $item;
        };
        foreach ($set_cur as $key => $value) {
            $item = array();
            $item['CI_ID'] = $value['CI_ID'];
            $item['CI_Name'] = $value['CI_Name'];
            $item['CI_Pic'] = "http://file.cike360.com".$value['CI_Pic'];
            $set_data[] = $item;
        };

        /**************************************/
        /***************取场地布置***************/
        /**************************************/
        $i=10000000;
        if($staff['account_id'] != 0){
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id || account_id=:ai',
                    'params' => array(
                            ':account_id' => $staff['account_id'],
                            ':ai' => 0
                        ),
                    'order' => 'account_id ASC,sort ASC'
                ));
        }else{
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $staff['account_id'],
                        ),
                    'order' => 'sort ASC'
                ));
        };
            
        $decoration_data = array();
        foreach ($tap as $key1 => $value1) {
            $item = array(
                'CI_ID' => $i+$value1['id'],
                "CI_Name"=> $value1['name'],
                "CI_Pic"=> "http://file.cike360.com".$value1['pic'],
            );
            $decoration_data[] = $item;
        };

        /**************************************/
        /***************取婚宴套餐***************/
        /**************************************/
        $menu = yii::app()->db->createCommand("select * from case_info where ".
            " CI_Show=1 and CI_Type=9 and CT_ID in ( ".
            " select id from wedding_set where staff_hotel_id in (select id from staff_hotel where account_id=".$staff['account_id'].")) order by CI_Sort Desc");
        $menu = $menu->queryAll();

        $menu_data = array();
        foreach ($menu as $key => $value) {
            $item = array(
                'CI_ID' => $i+$value['CI_ID'],
                "CI_Name"=> $value['CI_Name'],
                "CI_Pic"=> "http://file.cike360.com".$value['CI_Pic'],
            );
            $menu_data[] = $item;
        };

        /**************************************/
        /***************取婚宴零点***************/
        /**************************************/
        $dish_type = DishType::model()->findAll();

        $dish_data = array();
        foreach ($dish_type as $key => $value) {
            $item = array(
                'CI_ID' => $i+$value['id'],
                "CI_Name"=> $value['name'],
                "CI_Pic"=> "http://file.cike360.com".$value['pic'],
            );
            $dish_data[] = $item;
        };

        /********************************************/
        /***************取灯光、音响、视频***************/
        /********************************************/
        $t = 30000000;
        $type = yii::app()->db->createCommand("select * from supplier_type where id in (8,9,23)");
        $type = $type->queryAll();
        
        $lss_data = array();
        $lss_data[0] = array(
                "CI_ID" => $t+8,
                "CI_Name" => "灯光设备",
                "CI_Pic" => "http://file.cike360.com" . $type[0]['img'],
            );
        $lss_data[1] = array(
                "CI_ID" => $t+9,
                "CI_Name" => "视频设备",
                "CI_Pic" => "http://file.cike360.com" . $type[1]['img'],
            );
        $lss_data[2] = array(
                "CI_ID" => $t+23,
                "CI_Name" => "音响设备",
                "CI_Pic" => "http://file.cike360.com" . $type[2]['img'],
            );

        /**************************************/
        /***************取服务人员***************/
        /**************************************/
        $result8 = yii::app()->db->createCommand("select CI_ID, CI_Name, CI_Pic, CI_Type ".
            "from case_info ci ".
            "left join staff s on ci.CT_ID=s.id ".
            "left join supplier sup on s.id=sup.staff_id ".
            "where sup.account_id=".$staff['account_id']." and ci.CI_Type in (6,13,14,15,21) order by ci.CI_Type");
        $service = $result8->queryAll();

        $service_data = array();
        foreach ($service as $key => $value) {
            $item = array(
                'CI_ID' => $i+$value['CI_ID'],
                "CI_Name"=> $value['CI_Name'],
                "CI_Pic"=> "http://file.cike360.com".$value['CI_Pic'],
                "CI_Type" => ""
            );
            if($value['CI_Type'] == '6'){$item['CI_Type'] = "主持人";};
            if($value['CI_Type'] == '13'){$item['CI_Type'] = "摄像师";};
            if($value['CI_Type'] == '14'){$item['CI_Type'] = "摄影师";};
            if($value['CI_Type'] == '15'){$item['CI_Type'] = "化妆师";};
            if($value['CI_Type'] == '21'){$item['CI_Type'] = "其它";};
            $service_data[] = $item;
        };




        /**************************************/
        /***************输出结果*****************/
        /**************************************/
        $result = array(
                '案例' => $case_data,
                '套系' => $set_data,
                '服务人员' => $service_data,
                '场地布置' => $decoration_data,
                '灯光／音响／视频' => $lss_data,
                '婚宴套餐' => $menu_data,
                '菜品零点' => $dish_data,
            );

        echo json_encode($result);
    }

    public function actionList()
    {
        /************************************************************************/
        /************************************************************************/
        /******** CR_ID 构造规则：  ***********************************************/
        /******** 场地布置 10000000          ＋ sp_id     *************************/
        /******** 灯光／音响／视频  30000000   ＋  sp_id    *************************/
        /******** PPT 里的纯“图片” 60000000   ＋   show_id *************************/
        /******** 餐饮零点  90000000          +  sp_id     ************************/
        /******** 服务人员 120000000          ＋ CI_ID     ************************/
        /************************************************************************/
        /************************************************************************/




        //取案例
        $url ="http://file.cike360.com";
        $staff_id = $_GET['token'];
        //type 1 公司 2 分店 3 个人
        $result = yii::app()->db->createCommand("select * from case_info where ".

            "CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID=0) ".
            
            " or ( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
            "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 )  and ".

            " CI_Show=1 and CI_Type in (1,2,3) order by CT_ID DESC,CI_Sort ASC");
            
        $case = $result->queryAll();

        /*$list = findAllBySql("select * from case_info where ".

            "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=:id) ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=:id) ) ".
            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID= :id ))  ".
            " and CI_Show=1 order by CI_Sort Desc" ,array(':id'=>$staff_id)); */
        $list = array();
        foreach($case as  $key => $val){
            if($val['CI_Show'] == 1){
                if($val['CI_Type'] == 1 || $val['CI_Type'] == 2 || $val['CI_Type'] == 3){
                    $item = array();
                    $item['CI_ID'] = $val['CI_ID'];
                    $item['CI_Name'] = $val['CI_Name'];
                    $item['CI_Place'] = $val['CI_Place'];
                    $item['CI_Pic'] = $val['CI_Pic'];
                    $item['CI_Time'] = $val['CI_Time'];
                    $item['CI_CreateTime'] = $val['CI_CreateTime'];
                    $item['CI_Sort'] = $val['CI_Sort'];
                    $item['CI_Show'] = $val['CI_Show'];
                    $item['CI_Remarks'] = $val['CI_Remarks'];
                    $item['CI_Type'] = $val['CI_Type'];
                    $item['CT_ID'] = $val['CT_ID'];
                    if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                        /*$t=explode(".", $val["CI_Pic"]);
                        $CI_Pic = "";
                        if(isset($t[0]) && isset($t[1])){
                            $CI_Pic = $t[0]."_sm.".$t[1];    
                        }else{
                            $CI_Pic = $val['CI_Pic'];
                        };*/
                        $item["CI_Pic"]=$url.$val['CI_Pic'];
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
                    $item["resources"]= $jsonresources;
                    $item['product'] = array();
                    $list[] = $item;
                };
            };
            // $list[$key]['set_category'] = 0;
        };



        //取场地布置
        $staff = Staff::model()->findByPk($staff_id);

        if($staff['account_id'] != 0){
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id || account_id=:ai',
                    'params' => array(
                            ':account_id' => $staff['account_id'],
                            ':ai' => 0
                        ),
                    'order' => 'account_id ASC,sort ASC'
                ));
        }else{
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $staff['account_id'],
                        ),
                    'order' => 'sort ASC'
                ));
        };
            
        $i=10000000;
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

            $result = yii::app()->db->createCommand("select * from supplier_product where account_id in (0,".$staff['account_id'].") and decoration_tap=".$value1['id']);
            $supplier_product = $result->queryAll();

            // $supplier_product = SupplierProduct::model()->findAll(array(
            //         'condition' => 'decoration_tap=:tap',
            //         'params' => array(':tap' => $value1['id'])
            //     ));
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
            // $item['set_category'] = 0;
            
            $list[] = $item;
        };






        // 取灯光／音响／视频
        $lss = yii::app()->db->createCommand("select * from supplier_product where supplier_type_id in (8,9,23) and account_id in (".$staff['account_id'].",0)");
        $lss = $lss->queryAll();
        $t = 30000000;
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
            // $tem_resource['set_category'] = 0;
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
        $set0 = yii::app()->db->createCommand("select * from case_info where "./*.

            "(( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 ))  and".*/

            " CI_Show=1 and CI_Type=5 and CT_ID in (select id from wedding_set where staff_hotel_id in (select id from staff_hotel where account_id=2)) order by CI_Sort Desc");
        $set0 = $set0->queryAll();

        $set_cur = yii::app()->db->createCommand("select * from case_info where "./*.

            "(( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                "(select account_id from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
            "(select hotel_list from staff where id=".$staff_id.") ) ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

            " or CI_ID in ( select CI_ID from case_bind where CB_type=4 ))  and".*/

            " CI_Show=1 and CI_Type=5 and CT_ID in (select id from wedding_set where staff_hotel_id in (select id from staff_hotel where account_id=".$staff['account_id'].")) order by CI_Sort Desc");
        $set_cur = $set_cur->queryAll();

        $set=array();
        foreach ($set0 as $key => $value) {
            $set[]=$value;
        };
        foreach ($set_cur as $key => $value) {
            $set[]=$value;
        };

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
        // echo json_encode($set);die;
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

            " CI_Show=1 and CI_Type=9 and CT_ID in (select id from wedding_set where staff_hotel_id in (select id from staff_hotel where account_id=".$staff['account_id'].")) order by CI_Sort Desc");
        $menu = $menu->queryAll();
        /*print_r($set);die;*/
        foreach($menu as  $key3 => $val){
            if($val['CI_Show'] == 1){
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
        };
        // echo json_encode($menu);die;
        foreach ($menu as $key4 => $value4) {
            if($value4['CI_Show'] == '1'){
                $list[]=$value4;
            };
        };





        //取餐饮零点
        $i2 = 90000000;
        $staff = Staff::model()->findByPk($_GET['token']);
        $dish_type = DishType::model()->findAll();
        $result = yii::app()->db->createCommand("select supplier_product.id as CR_ID,supplier_product.name as CR_Name,ref_pic_url as CR_Path,description as CR_Remarks,dish_type.id as CI_ID,unit_price,unit ".
            "from supplier_product left join dish_type on dish_type=dish_type.id ".
            "where product_show=1 and account_id=".$staff['account_id']);
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








        //取服务人员
        $url ="http://file.cike360.com";
        $staff_id = $_GET['token'];

        // $result8 = yii::app()->db->createCommand("select s.id,sp.type_id from staff s ".
        //     "left join supplier sp on s.id=sp.staff_id ".
        //     "where s.account_id=".$staff['account_id']." and sp.type_id in (3,4,5,6,7)");
        // $non_r_host = $result8->queryAll();

        $result8 = yii::app()->db->createCommand("select ci.CI_ID,CI_Name,CI_Place,CI_Pic,CI_Time,CI_CreateTime,CI_Sort,CI_Show,CI_Remarks,CI_Type,CT_ID,CR_ID,CR_Name,CR_Path,CR_Sort,CR_Remarks,CR_Type,CR_Show,CR_Remarks ".
            "from case_resources cr ".
            "left join case_info ci on cr.CI_ID=ci.CI_ID ".
            "left join staff s on ci.CT_ID=s.id ".
            "left join supplier sup on s.id=sup.staff_id ".
            "where sup.account_id=".$staff['account_id']." and ci.CI_Type in (6,13,14,15,21) order by cr.CI_ID DESC");
        $non_r_host = $result8->queryAll();

        // echo json_encode($non_r_host);die;

        $result9 = yii::app()->db->createCommand("select sp.id,sp.name,unit_price,unit_cost,unit,description,ref_pic_url,sup.staff_id,sp.supplier_type_id from supplier_product sp".
            " left join supplier sup on sup.id=sp.supplier_id".
            " where sp.account_id=".$staff['account_id']." and sp.product_show=1");
        $non_r_host_product = $result9->queryAll();

        // $service_person = array();

        $tem_CI_ID = 0;
        if(isset($non_r_host[0]['CI_ID'])){
            $tem_CI_ID = $non_r_host[0]['CI_ID'];
        }; 

        $end = array(
                "CI_ID"=> "0",
                "CI_Name"=> "\u4e54\u51cc\u5f69\u5986",
                "CI_Place"=> null,
                "CI_Pic"=> "\/upload\/\u4e54\u51cc\u5f69\u59861366162691020160706120003.jpg",
                "CI_Time"=> null,
                "CI_CreateTime"=> "2016-07-06 12:00:59",
                "CI_Sort"=> "1",
                "CI_Show"=> "1",
                "CI_Remarks"=> null,
                "CI_Type"=> "15",
                "CT_ID"=> "2222443",
                "CR_ID"=> "1494",
                "CR_Name"=> "",
                "CR_Path"=> "\/upload\/\u554a\u554a\u5b8c1820160706120056.jpg",
                "CR_Sort"=> "18",
                "CR_Remarks"=> "",
                "CR_Type"=> "1",
                "CR_Show"=> "1"
            );
        array_push($non_r_host,$end);

        // echo $tem_CI_ID;die;
        $tem_resource=array();
        $tem_ci=array();
        foreach ($non_r_host as $key => $value) {
            if($value['CI_Show'] == 1){
                // echo $value['CI_ID'];

                if($value['CI_ID'] == $tem_CI_ID){

                    $tem_ci['CI_ID']=120000000+$value['CI_ID'];
                    $tem_ci['CI_Name']=$value['CI_Name'];
                    $tem_ci['CI_Place']=$value['CI_Place'];
                    $tem_ci['CI_Pic']="http://file.cike360.com".$value['CI_Pic'];
                    $tem_ci['CI_Time']=$value['CI_Time'];
                    $tem_ci['CI_CreateTime']=$value['CI_CreateTime'];
                    $tem_ci['CI_Sort']=$value['CI_Sort'];
                    $tem_ci['CI_Show']=$value['CI_Show'];
                    $tem_ci['CI_Remarks']=$value['CI_Remarks'];
                    $tem_ci['CI_Type']=$value['CI_Type'];
                    $tem_ci['CT_ID']=$value['CT_ID'];
                    $tem_ci['CI_Type']=$value['CI_Type'];
                    $tem_ci['resources']=array();
                    $tem_ci['product']=array();

                    $item=array();
                    $item['CR_ID'] = 120000000+$value['CR_ID'];
                    $item['CR_Name'] = $value['CR_Name'];
                    $item['CR_Path'] = "http://file.cike360.com".$value['CR_Path'];
                    $item['CR_Sort'] = $value['CR_Sort'];
                    $item['CR_Show'] = $value['CR_Show'];
                    $item['CR_Remarks'] = $value['CR_Remarks'];
                    $item['CR_Type'] = $value['CR_Type'];
                    $item['product'] = array();

                    foreach ($non_r_host_product as $key_p => $value_p) {
                        if($value_p['staff_id'] == $value['CT_ID']){
                            $item_p = array();
                            $item_p['id'] = $value_p['id'];
                            $item_p['name'] = $value_p['name'];
                            $item_p['unit_price'] = $value_p['unit_price'];
                            $item_p['unit'] = $value_p['unit'];
                            $item_p['description'] = $value_p['description'];
                            $item_p['ref_pic_url'] = "http://file.cike360.com".$value_p['ref_pic_url'];
                            $item['product'][] = $item_p;
                        };
                    };

                    $tem_resource[] = $item;

                }else{
                    // echo $value['CI_ID'];die;
                    
                    $tem_ci['resources']=$tem_resource;
                    // echo json_encode($tem_ci);die;

                    $list[] = $tem_ci;

                    $tem_resource = array();
                    $tem_CI_ID = $value['CI_ID'];

                    $item=array();
                    $item['CR_ID'] = 120000000+$value['CR_ID'];
                    $item['CR_Name'] = $value['CR_Name'];
                    $item['CR_Path'] = "http://file.cike360.com".$value['CR_Path'];
                    $item['CR_Sort'] = $value['CR_Sort'];
                    $item['CR_Show'] = $value['CR_Show'];
                    $item['CR_Remarks'] = $value['CR_Remarks'];
                    $item['CR_Type'] = $value['CR_Type'];
                    $item['product'] = array();

                    foreach ($non_r_host_product as $key_p => $value_p) {
                        if($value_p['staff_id'] == $value['CT_ID']){
                            $item_p = array();
                            $item_p['id'] = $value_p['id'];
                            $item_p['name'] = $value_p['name'];
                            $item_p['unit_price'] = $value_p['unit_price'];
                            $item_p['unit'] = $value_p['unit'];
                            $item_p['description'] = $value_p['description'];
                            $item_p['ref_pic_url'] = "http://file.cike360.com".$value_p['ref_pic_url'];
                            $item['product'][] = $item_p;
                        };
                    };

                    $tem_resource[] = $item;
                
                }; 
            };
                 
        };


        // echo json_encode($service_person);die;

        //type 1 公司 2 分店 3 个人
        // $result = yii::app()->db->createCommand("select * from case_info where "./*

        //     "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
        //         "(select account_id from staff where id=".$staff_id.") ) ".

        //     " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
        //     "(select hotel_list from staff where id=".$staff_id.") ) ".

        //     " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".

        //     " or CI_ID in ( select CI_ID from case_bind where CB_type=4 )  and ".*/

        //     " CI_Show=1 and CI_Type=6 order by CI_Sort Desc");
            
        // $host = $result->queryAll();

        // /*$list = findAllBySql("select * from case_info where ".

        //     "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
        //         "(select account_id from staff where id=:id) ) ".

        //     " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
        //     "(select hotel_list from staff where id=:id) ) ".
        //     " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID= :id ))  ".
        //     " and CI_Show=1 order by CI_Sort Desc" ,array(':id'=>$staff_id)); */
        // foreach($host as  $key => $val){
        //     if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
        //         /*$t=explode(".", $val["CI_Pic"]);
        //         $CI_Pic = "";
        //         if(isset($t[0]) && isset($t[1])){
        //             $CI_Pic = $t[0]."_sm.".$t[1];    
        //         }else{
        //             $CI_Pic = $val['CI_Pic'];
        //         };*/
        //         $host[$key]["CI_Pic"]=$url.$val['CI_Pic'];
        //     };
        //     //$val["size"]=$this->getUrlFileSize($val["CI_Pic"]);
        //     /*$resources = CaseResources::model()->findAll(array(
        //             'condition' => 'CI_ID=:CI_ID',
        //             'params' => array(
        //                     ':CI_ID' => $val["CI_ID"]
        //                 )
        //         )); */
  
        //     $result1 = yii::app()->db->createCommand("select case_resources.CR_ID,case_resources.CI_ID,case_resources.CR_Type,case_resources.CR_Sort,case_resources.CR_Name,case_resources.CR_Path,CR_Show,CR_Remarks from case_resources left join case_resources_product on case_resources_product.CR_ID=case_resources.CR_ID where CI_ID =".$val["CI_ID"]." order by case_resources.CR_Sort");
            
        //     $resources = $result1->queryAll();
        //     $jsonresources = array();
        //     $cur_resourceobj=null;
        //     $cur_crid = 0;
        //     //$cur_product = null;
        //     //$i = 0;
        //     $cur_product = array();
        //     foreach ($resources as $rkey => $rval) {
        //         $resourceobj =array(
        //             "CR_ID"=>$rval["CR_ID"],
        //             "CR_Name"=>$val["CI_Name"],
        //             "CR_Path"=>$rval["CR_Path"],
        //             "CR_Sort"=>$rval["CR_Sort"],
        //             "CR_Show"=>$rval["CR_Show"],
        //             "CR_Remarks"=>$rval["CR_Remarks"],
        //             "CR_Type"=>$rval["CR_Type"]
        //             );
        //         if(!$this->startwith($rval["CR_Path"],"http://")&&!$this->startwith($rval["CR_Path"],"https://")){
        //             $resourceobj["CR_Path"]=$url.$rval["CR_Path"];    
        //         }
                
        //         $cur_crid = $rval["CR_ID"];
        //         // $cur_resourceobj=$resourceobj;
        //         $supplier_product = yii::app()->db->createCommand(
        //             "select supplier_product.id as id,supplier_product.name as name,unit_price,unit,description,ref_pic_url from supplier_product ".
        //             " left join supplier on supplier_id = supplier.id".
        //             " left join case_info on case_info.CT_ID = supplier.staff_id ".
        //             " where CI_ID=".$rval['CI_ID']." and CI_Type=6 and supplier_product.account_id in (select account_id from staff where id = ".$staff_id.") and supplier_product.supplier_type_id=3");
                
        //         $supplier_product = $supplier_product->queryAll();

        //         // print_r($supplier_product);
        //         foreach ($supplier_product as $key_prod => $value_prod) {
        //             if(!empty($value_prod)){
        //                 $t=explode(".", $value_prod["ref_pic_url"]);
        //                 if(isset($t[0]) && isset($t[1])){
        //                     $ref_pic_url = $t[0]."_sm.".$t[1];    
        //                 }else{
        //                     $ref_pic_url = $rval['ref_pic_url'];
        //                 };
        //                 $productobj=array(
        //                     "id"=>$value_prod["id"],
        //                     "name"=>$value_prod["name"],
        //                     "unit_price"=>$value_prod["unit_price"],
        //                     "unit"=>$value_prod["unit"],
        //                     "description"=>$value_prod["description"],
        //                     "ref_pic_url"=>"http://file.cike360.com".$ref_pic_url
        //                     );
        //                 $cur_product[]=$productobj;
        //             }
        //             else{
        //                 $resourceobj["product"]=array();
        //             }; 
        //         };
        //         $resourceobj["product"]=$cur_product;
        //         if(/*$cur_crid!=$rval["CR_ID"]&&*/$cur_crid!=0){
        //             $jsonresources[]=$resourceobj;
        //             //$cur_resourceobj=null;
        //             $cur_product=array();
        //         };
        //         if($cur_crid==0){
        //             $jsonresources[] = $resourceobj;
        //         };
        //     }
        //     $host[$key]["resources"]= $jsonresources;
        //     $host[$key]['product'] = array();
        //     // $host[$key]['set_category'] = 0;
        // };
        // foreach ($host as $key => $value) {
        //     $list[] = $value;
        // };



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
            // $result[$key]['set_category'] = 0;
        };
        foreach ($result as $key => $value) {
            $list[]=$value;
        };


        /***********************************************************************************/
        /*＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊  订单列表  ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊*/
        /***********************************************************************************/

        // $staff = Staff::model()->findByPk($_GET['token']);

        $result1 = yii::app()->db->createCommand("select * from `order` where designer_id=".$_GET['token']." or planner_id=".$_GET['token']);
        $result1 = $result1->queryAll();

        $order_doing = array();

        foreach ($result1 as $key => $value) {
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($value['order_date']);  //订单创建时间
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'to_date' => 0,
                );
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
            };
        };



        /***********************************************************************************/
        /*＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊  区域列表  ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊*/
        /***********************************************************************************/

        $order_show_list = OrderShowArea::model()->findAll(array(
                'condition' => 'id != :id',
                'params' => array(
                        ':id' => 1
                    )
            ));

        $area = array();

        foreach ($order_show_list as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $area[] = $item;
        };

        $list_result = array(
                'list' => $list,
                'arr_order' => $order_doing,
                'area' => $area
            );

        echo json_encode($list_result);

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
        // $_GET['token'] = 100;
        

    }

    public function actionOrderdetail()
    {

        /************************************************************************/
        /************************************************************************/
        /******** CR_ID 构造规则：  ***********************************************/
        /******** 场地布置 10000000          ＋ sp_id     *************************/
        /******** 灯光／音响／视频  30000000   ＋  sp_id    *************************/
        /******** PPT 里的纯“图片” 60000000   ＋   show_id *************************/
        /******** 餐饮零点  90000000          +  sp_id     ************************/
        /******** 服务人员 120000000          ＋ CI_ID     ************************/
        /************************************************************************/
        /************************************************************************/



        // $post = json_decode(file_get_contents('php://input'));

        //取本订单 当前在order_show里的数据
        $result = yii::app()->db->createCommand("select s.id,s.type,i.img_url,s.order_product_id,sp.ref_pic_url,sp.supplier_type_id,sp.id as sp_id,words,show_area,area_sort ".
            "from order_show s ".
            "left join order_show_img i on s.img_id=i.id ".
            "left join order_product op on s.order_product_id=op.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "where s.order_id=".$_GET['order_id']);
        $result = $result->queryAll();

        // echo json_encode($result);die;

        foreach ($result as $key => $value) {
            $t0 = explode('/', $value['ref_pic_url']);
            if(isset($t0[1])){
                if($t0[1] == 'upload'){
                    $t1 = explode('.', $value['ref_pic_url']);
                    if(isset($t1[0]) && isset($t1[1])){
                        $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$t1[0]."_sm.".$t1[1];
                    }else{
                        $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                    };
                }else if($t0[1] == 'imgs'){
                    $result[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                };
            };
                
            $t2 = explode('.', $value['img_url']);
            if(isset($t2[0]) && isset($t2[1])){
                $result[$key]['img_url'] = 'http://file.cike360.com'.$t2[0]."_sm.".$t2[1];
            }else{
                $result[$key]['img_url'] = 'http://file.cike360.com'.$value['img_url'];
            };
        };

        //取本订单里的  order_product
        $result1 = yii::app()->db->createCommand("select op.id,op.order_set_id,o.other_discount,o.discount_range,o.feast_discount,ws.category as set_category,ws.name as set_name,st.name,op.actual_price,op.unit as amount,op.actual_unit_cost,op.actual_service_ratio,sp.name as product_name,sp.description,sp.ref_pic_url,sp.supplier_type_id,sp.unit,sp.id as sp_id,op.order_set_id,os.show_area ".
            "from order_product op ".
            "left join `order` o on op.order_id=o.id ".
            "left join order_show os on op.id=os.order_product_id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            "left join supplier_type st on sp.supplier_type_id=st.id ".
            "left join order_set on op.order_set_id=order_set.id ".
            "left join wedding_set ws on order_set.wedding_set_id=ws.id ".
            "where op.order_id=".$_GET['order_id']);
        $result1 = $result1->queryAll(); 

        foreach ($result1 as $key => $value) {
            $t0 = explode('/', $value['ref_pic_url']);
            if(isset($t0[1])){
                if($t0[1] == 'upload'){
                    $t1 = explode('.', $value['ref_pic_url']);
                    if(isset($t1[0]) && isset($t1[1])){
                        $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$t1[0]."_sm.".$t1[1];
                    }else{
                        $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                    };
                }else if($t0[1] == 'imgs'){
                    $result1[$key]['ref_pic_url'] = 'http://file.cike360.com'.$value['ref_pic_url'];
                    
                };
            };
                
        };

        // echo json_encode($result1);die;

        //取本订单数据
        $order = Order::model()->findByPk($_GET['order_id']);

        // *******************************************************
        // *****************   构造 订单基本信息    *****************
        // *******************************************************
        
        $result4 = yii::app()->db->createCommand("select o.id,o.order_name,feast_discount,other_discount,discount_range,cut_price,planner_id,s1.name as planner_name,s1.telephone as planner_phone,designer_id,s2.name as designer_name,s2.telephone as designer_phone,staff_hotel_id,sh.name as hotel_name,groom_name,groom_phone,groom_wechat,groom_qq,bride_name,bride_phone,bride_phone,bride_wechat,bride_qq,contact_name,contact_phone ".
            "from `order` o ".
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
        $result5 = new ProductForm;
        $tuidan_list = $result5->tuidan_list($_GET['token']);

        //构造折扣范围列表
        $discount = array();
        if(!empty($result4)){
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
                foreach ($descount_list as $key => $value) {
                    $item = array();
                    $item['id']=$value['id'];
                    $item['name']=$value['name'];
                    $item['select']=1;
                    $discount['list'][]=$item;
                };
            };
        };
        // echo json_encode($discount['list']);die;

        //产品按分类计算总价
        $type_price = array(
                'service' => 0,
                'decorat' => 0,
                'light' => 0,
            );

        foreach ($result1 as $key => $value) {
            if($value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7){
                $type_price['service'] += $value['actual_price']*$value['amount'];
            }else if($value['supplier_type_id'] == 20){
                $type_price['decorat'] += $value['actual_price']*$value['amount'];
            }else if($value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){
                $type_price['light'] += $value['actual_price']*$value['amount'];
            };
        };

        //构造跟单
        $follow = OrderMerchandiser::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    ),
                'order'=>'time'
            ));

        $follow_data = array();
        foreach ($follow as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'type' => $value['type'],
                    'staff_name' => $value['staff_name'],
                    'time' => $value['time'],
                    'order_name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'remarks' => $value['remarks'],
                );
            $follow_data[] = $item;
        };

        //构造收款记录
        $payment = OrderPayment::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $_GET['order_id']
                    ),
                'order' => 'time'
            ));
        $payment_data = array(
                '0' => array(
                        'total_price' => 0,
                        'list' => array(),
                    ),
                '1' => array(
                        'total_price' => 0,
                        'list' => array(),
                    ),
                '2' => array(
                        'total_price' => 0,
                        'list' => array(),
                    ),
            );
        foreach ($payment as $key => $value) {
            if($value['type'] == 0){
                $payment_data['0']['total_price'] += $value['money'];
                $item=array();
                $item['id'] = $value['id'];
                $item['money'] = $value['money'];
                $item['time'] = $value['time'];
                $payment_data['0']['list'][] = $item;
            }else if($value['type'] == 1){
                $payment_data['1']['total_price'] += $value['money'];
                $item=array();
                $item['id'] = $value['id'];
                $item['money'] = $value['money'];
                $item['time'] = $value['time'];
                $payment_data['1']['list'][] = $item;
            }else if($value['type'] == 2){
                $payment_data['2']['total_price'] += $value['money'];
                $item=array();
                $item['id'] = $value['id'];
                $item['money'] = $value['money'];
                $item['time'] = $value['time'];
                $payment_data['2']['list'][] = $item;
            };
        };  
        // print_r($result3);die;
        $order_data = array(
                "id"=> $result4[0]['id'] ,
                "order_name"=> $result4[0]['order_name'] ,
                "planner_id"=> $result4[0]['planner_id'] ,
                "planner_name"=> $result4[0]['planner_name'] ,
                "planner_phone"=> $result4[0]['planner_phone'] ,
                "designer_id"=> $result4[0]['designer_id'] ,
                "designer_name"=> $result4[0]['designer_name'] ,
                "designer_phone"=> $result4[0]['designer_phone'] ,
                "staff_hotel_id"=> $result4[0]['staff_hotel_id'] ,
                "hotel_name"=> $result4[0]['hotel_name'] ,
                "order_place"=> $order['order_place'] ,
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
                'type_price'=> $type_price,
                'follow_data'=> $follow_data,
                'payment_data'=> $payment_data
            );        
        $t=explode(' ', $order['order_date']);
        $order_data['order_date']=$t[0];
        $result3 = yii::app()->db->createCommand("select sp.id,sp.name from order_product op left join supplier_product sp on product_id=sp.id where op.order_id=".$_GET['order_id']." and sp.supplier_type_id=16");
        $result3 = $result3->queryAll();
        if(!empty($result3)){
            $order_data['tuidan_id']=$result3[0]['id'];
            $order_data['tuidan_name']=$result3[0]['name'];
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
        // $order_show_data = OrderShow::model()->findAll(array(
        //         'condition' => 'order_id=:order_id',
        //         'params' => array(
        //                 ':order_id' => $_GET['order_id']
        //             )
        //     ));
        foreach ($result as $key => $value) {
            if($value['supplier_type_id'] != 2){
                $item=array();
                $item['show_id']=$value['id'];
                $item['show_type']=$value['type'];
                $item['show_area']=$value['show_area'];
                $item['area_sort']=$value['area_sort'];
                $item['CR_ID'] = 0;
                if($value['type'] == 0){
                    $item['show_data']=$value['words'];
                    $item['product_id']=0;
                }else if($value['type'] == 1){
                    $item['show_data']=$value['img_url'];
                    $item['product_id']=0;
                    $item['CR_ID'] = 60000000 + $value['id'];
                }else if($value['type'] == 2){
                    $item['show_data']=$value['ref_pic_url'];
                    $item['product_id']=$value['order_product_id'];
                    if($value['supplier_type_id'] == 20){
                        $item['CR_ID'] = 10000000 + $value['sp_id'];
                    }else if($value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){
                        $item['CR_ID'] = 30000000 + $value['sp_id'];
                    }else if($value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7){
                        $CI_Type = 0;
                        if($value['supplier_type_id'] == 3){$CI_Type=6;};
                        if($value['supplier_type_id'] == 4){$CI_Type=13;};
                        if($value['supplier_type_id'] == 5){$CI_Type=14;};
                        if($value['supplier_type_id'] == 6){$CI_Type=15;};
                        if($value['supplier_type_id'] == 7){$CI_Type=21;};
                        $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value['sp_id']);
                        $result7 = $result7->queryAll();
                        // print_r($result7);die;
                        if(isset($result7[0])){
                                    $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                                };
                    };
                };
                $order_show_list[]=$item;
            };
        };

        // echo json_encode($order_show_list);die;
        $result6 = yii::app()->db->createCommand("select * ".
            "from order_show where order_id=".$_GET['order_id']." and show_area=0 ".
            "order by area_sort DESC");
        $non_area_show = $result6->queryAll();
        $i=1;
        if(!empty($non_area_show)){
            $i=$non_area_show[0]['area_sort']+1;
        };
        foreach ($result1 as $key => $value) {
            if($value['supplier_type_id'] != 2){
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
                    $admin->area_sort= $i;
                    $admin->update_time=date('y-m-d h:i:s',time());
                    $admin->save();
                    $show_id = $admin->attributes['id'];

                    $item=array();
                    $item['show_id']=$show_id;
                    $item['show_type']=2;
                    $item['show_area']=0;
                    $item['area_sort']=$i++;
                    $item['show_data']=$value['ref_pic_url'];
                    $item['product_id']=$value['id'];
                    $item['CR_ID']=0;                             //  没有区域的产品，在PPT部分，加了一个CR_ID＝0
                    $order_show_list[]=$item;
                };
            };
        };
        $order_show = array();
        $area = OrderShowArea::model()->findAll();
        // echo json_encode($order_show_list);die;
        foreach ($area as $key => $value) {
            $tem=array();
            $tem['area_id'] = $value['id'];
            $tem['area_name'] = $value['name'];
            $tem['description'] = $value['description'];
            $tem['data']=array();
            foreach ($order_show_list as $key_l => $value_l) {
                if($value_l['show_area'] == $value['id']){
                    $item=array();
                    $item['show_id']=$value_l['show_id'];
                    $item['data_type']=$value_l['show_type'];
                    $item['show_data']=$value_l['show_data'];
                    $item['product_id']=$value_l['product_id'];
                    $item['sort']=$value_l['area_sort'];
                    $item['CR_ID']=$value_l['CR_ID'];
                    $tem['data'][]=$item;
                };
            };
            if(!empty($tem['data'])){
                $num1 = array();
                foreach ( $tem['data'] as $key => $value ){
                    $num1[$key] = $value ['sort'];
                };
                // print_r($num1);echo ',';print_r($tem['data']);echo '|';
                array_multisort($num1, SORT_ASC, $tem['data']);
            };
                
            $order_show[]=$tem;
        };

        if(!empty($order_show)){
            $num1 = array();
            foreach ( $order_show as $key => $value ){
                $num1[$key] = $value ['area_id'];
            };
            // print_r($num1);echo ',';print_r($tem['data']);echo '|';
            array_multisort($num1, SORT_ASC, $order_show);
        };

        $tem=array();
        $tem['area_id']=0;
        $tem['area_name']='待分配产品';
        $tem['description']="";
        $tem['data']=array();
        foreach ($order_show_list as $key_l => $value_l) {
            if($value_l['show_area'] == 0){
                $item=array();
                $item['show_id']=$value_l['show_id'];
                $item['data_type']=$value_l['show_type'];
                $item['show_data']=$value_l['show_data'];
                $item['product_id']=$value_l['product_id'];
                $item['sort']=$value_l['area_sort'];
                $item['CR_ID']=$value_l['CR_ID'];
                $tem['data'][]=$item;
            };
        };
        if(!empty($tem['data'])){
            $num1 = array();
            foreach ( $tem['data'] as $key => $value ){
                $num1[$key] = $value ['sort'];
            };
            // print_r($num1);die;
            // print_r($tem['data']);die;

            array_multisort($num1, SORT_ASC, $tem['data']);    
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
            if($value['id'] != 1){
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

                        //构造 CR_ID  top
                        if($value_p['supplier_type_id'] == 20){
                            $item['CR_ID'] = 10000000 + $value_p['sp_id'];
                        }else if($value_p['supplier_type_id'] == 8 || $value_p['supplier_type_id'] == 9 || $value_p['supplier_type_id'] == 23){
                            $item['CR_ID'] = 30000000 + $value_p['sp_id'];
                        }else if($value_p['supplier_type_id'] == 3 || $value_p['supplier_type_id'] == 4 || $value_p['supplier_type_id'] == 5 || $value_p['supplier_type_id'] == 6 || $value_p['supplier_type_id'] == 7){
                            $CI_Type = 0;
                            if($value_p['supplier_type_id'] == 3){$CI_Type=6;};
                            if($value_p['supplier_type_id'] == 4){$CI_Type=13;};
                            if($value_p['supplier_type_id'] == 5){$CI_Type=14;};
                            if($value_p['supplier_type_id'] == 6){$CI_Type=15;};
                            if($value_p['supplier_type_id'] == 7){$CI_Type=21;};
                            $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value_p['sp_id']);
                            $result7 = $result7->queryAll();
                            // print_r($result7);die;
                            if(isset($result7[0])){
                                    $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                                };
                        }
                        //构造 CR_ID  end


                        $t = 0;
                        foreach ($discount_range as $key_r => $value_r) {
                            if($value_r == $value_p['supplier_type_id']){
                                $t++;
                            };
                        };
                        if($t!=0){
                            $item['discount'] = $order['other_discount']*0.1;
                            $tem['discount_total'] += $item['price']*$item['amount']*$order['other_discount'];
                        }else{
                            $item['discount'] = 1;
                            $tem['discount_total'] += $item['price']*$item['amount'];
                        }

                        if($value_p['order_set_id'] != 0){
                            $item['set']="套系产品";
                        };
                        $tem['product_list'][]=$item;
                        $tem['area_total'] += $item['price']*$item['amount'];
                    };
                };
                $area_product[] = $tem;
            };
        };

        //无区域产品，列出来
        $non_area_product = array();
        foreach ($result1 as $key => $value) {
            if($value['show_area'] == 0 && $value['supplier_type_id']!=2){
                $discount_range = rtrim($value['discount_range'], ",");
                $discount_range = explode(',', $discount_range);
                $r = 0;
                foreach ($discount_range as $key_dr => $value_dr) {
                    if($value_dr == $value['supplier_type_id']){
                        $r++;
                    };
                };
                $item=array();
                $item['product_id']=$value['id'];
                $item['product_name']=$value['product_name'];
                $item['description']=$value['description'];
                $item['ref_pic_url']=$value['ref_pic_url'];
                $item['price']=$value['actual_price'];
                $item['amount']=$value['amount'];
                $item['unit']=$value['unit'];
                $item['cost']=$value['actual_unit_cost'];
                $item['set']="";

                //构造 CR_ID  top
                if($value_p['supplier_type_id'] == 20){
                    $item['CR_ID'] = 10000000 + $value_p['sp_id'];
                }else if($value_p['supplier_type_id'] == 8 || $value_p['supplier_type_id'] == 9 || $value_p['supplier_type_id'] == 23){
                    $item['CR_ID'] = 30000000 + $value_p['sp_id'];
                }else if($value_p['supplier_type_id'] == 3 || $value_p['supplier_type_id'] == 4 || $value_p['supplier_type_id'] == 5 || $value_p['supplier_type_id'] == 6 || $value_p['supplier_type_id'] == 7){
                    $CI_Type = 0;
                    if($value_p['supplier_type_id'] == 3){$CI_Type=6;};
                    if($value_p['supplier_type_id'] == 4){$CI_Type=13;};
                    if($value_p['supplier_type_id'] == 5){$CI_Type=14;};
                    if($value_p['supplier_type_id'] == 6){$CI_Type=15;};
                    if($value_p['supplier_type_id'] == 7){$CI_Type=21;};
                    $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value_p['sp_id']);
                    $result7 = $result7->queryAll();
                    // print_r($result7);die;
                    if(isset($result7[0])){
                        $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                    };
                }
                //构造 CR_ID  end

                if($r == 0){
                    $item['discount'] = 1;
                }else{
                    $item['discount'] = $value['other_discount']*0.1;
                };
                if($value['order_set_id'] != 0){
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
                    $item['CR_ID']=90000000 + $value_p['sp_id'];
                    if($value_p['supplier_type_id'] == 2){
                        $item['discount'] = $value_p['feast_discount'];
                    }else{
                        $t=explode(',', $value_p['discount_range']);
                        $m=0;
                        foreach ($t as $key_tm => $value_tm) {
                            if($value_tm == $value_p['supplier_type_id']){
                                $m++;
                            };
                        };
                        if($m == 0){
                            $item['discount'] = 1;
                        }else{
                            $item['discount'] = $value_p['other_discount']*0.1;
                        };
                    };
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
                    $item['CR_ID']=90000000 + $value_p['sp_id'];
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

        // *******************************************************
        // ***************   构造 email 列表    *******************
        // *******************************************************

        $email = StaffEmail::model()->findAll(array(
                'condition' => 'staff_id=:staff_id',
                'params' => array(
                        ':staff_id' => $_GET['token'],
                    )
            ));

        $email_list = array();
        foreach ($email as $key => $value) {
            $item=array();
            $item['email'] = $value['email'];
            $email_list[] = $item;
        };
        // print_r($email_list);die;


        // // *******************************************************
        // // ***************   构造 我的订单 列表    *******************
        // // *******************************************************

        // $result = yii::app()->db->createCommand("select * from `order` where designer_id=".$_GET['token']." or planner_id=".$_GET['token']);
        // $result = $result->queryAll();

        // // print_r($result);die;

        // $order_doing = array();
        // $order_done = array();
        // $order_nearest = array(
        //         'name' => '无订单',
        //         'to_date' => 0,
        //         'order_date' => ""
        //     );

        // foreach ($result as $key => $value) {
        //     $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
        //     $zero2=strtotime ($value['order_date']);  //订单创建时间
        //     $item = array(
        //             'id' => $value['id'],
        //             'name' => $value['order_name'],
        //             'order_date' => $value['order_date'],
        //             'to_date' => 0,
        //         );
        //     if($zero2 >= $zero1){
        //         $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
        //         $order_doing[]=$item;
        //     }else{
        //         $item['to_date'] = ceil(($zero1-$zero2)/86400); //60s*60min*24h
        //         $order_done[]=$item;
        //     }
        // }

        // // print_r($order_done);die;

        // $to_date = array();
        // foreach ( $order_doing as $key => $row ){
        //     $to_date[] = $row ['to_date'];
        // };

        // array_multisort($to_date, SORT_ASC, $order_doing);


        // $to_date1 = array();
        // foreach ( $order_done as $key => $row ){
        //     $to_date1[] = $row['to_date'];
        // };

        // array_multisort($to_date1, SORT_ASC, $order_done);

        // if(!empty($order_doing)){
        //     $t = explode(' ', $order_doing[0]['order_date']);
        //     $name = "";
        //     $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
        //     $zero2=strtotime ($order_doing[0]['order_date']);  //订单创建时间

        //     foreach ($order_doing as $key => $value) {
        //         $order_date = explode(' ', $value['order_date']);
        //         if($order_date[0] == $t[0]){
        //             $name .= $value['name'].",";
        //         };
        //     };
        //     $order_nearest['name'] = rtrim($name, ",");
        //     $order_nearest['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
        //     $order_nearest['order_date'] = $t[0];
        // };  

        // $order_data2 = array(
        //         'doing' => $order_doing,
        //         'done' => $order_done,
        //         'nearest' => $order_nearest,
        //     );


        $order_detail = array();
        $order_detail['order_data'] = $order_data;
        $order_detail['order_show'] = $order_show;
        $order_detail['area_product'] = $area_product;
        $order_detail['non_area_product'] = $non_area_product;
        $order_detail['set_data'] = $set_data;
        $order_detail['email_list'] = $email_list;
        // $order_detail['order_doing'] = $order_data2;
        echo json_encode($order_detail);
    }

    public function actionProductstore()
    {
        /************************************************************************/
        /************************************************************************/
        /******** CR_ID 构造规则：  ***********************************************/
        /******** 场地布置 10000000          ＋ sp_id     *************************/
        /******** 灯光／音响／视频  30000000   ＋  sp_id    *************************/
        /******** PPT 里的纯“图片” 60000000   ＋   show_id *************************/
        /******** 餐饮零点  90000000          +  sp_id     ************************/
        /******** 服务人员 120000000          ＋ CI_ID     ************************/
        /************************************************************************/
        /************************************************************************/

        $staff = Staff::model()->findByPk($_GET['token']);
        $result = yii::app()->db->createCommand("select * from supplier_product sp ".
            " where product_show=1 and account_id in (0,".$staff['account_id'].")");
        $result = $result->queryAll();

        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'role=:role',
                'params' => array(
                        ':role' => 1
                    ),
                'order' => 'sort ASC'
            ));
        // print_r($supplier_type);die;
        // $product_store = array(
        //         '场地布置' => array(),
        //         '主持' => array(),
        //         '化妆' => array(),
        //         '摄影' => array(),
        //         '摄像' => array(),
        //         '其他' => array(),
        //         '灯光设备' => array(),
        //         '视频设备' => array(),
        //         '音响设备' => array(),
        //     );
        $decoration_tap = array();

        $decoration_tap0 = yii::app()->db->createCommand("select id,name ".
            " from supplier_product_decoration_tap where account_id=0 order by sort ASC");
        $decoration_tap0 = $decoration_tap0->queryAll();


        $decoration_tap_cur = yii::app()->db->createCommand("select id,name ".
            " from supplier_product_decoration_tap where account_id=".$staff['account_id']." order by sort ASC");
        $decoration_tap_cur = $decoration_tap_cur->queryAll();

        foreach ($decoration_tap0 as $key => $value) {
            $decoration_tap[]=$value;
        };

        foreach ($decoration_tap_cur as $key => $value) {
            $decoration_tap[]=$value;
        };

        // print_r($decoration_tap);die;

        $product_store = array(
                0 => array(
                        'supplier_type_name' => '场地布置',
                        'type' => 1,
                        'tap' => array(),
                        'list' => array()
                    ),
                1 => array(
                        'supplier_type_name' => '服务人员',
                        'type' => 1,
                        'tap' => array(
                                0 => array(
                                        'id' => 3,
                                        'name' => '主持人',
                                        'list' => array()
                                    ),
                                1 => array(
                                        'id' => 4,
                                        'name' => '摄像师',
                                        'list' => array()
                                    ),
                                2 => array(
                                        'id' => 5,
                                        'name' => '摄影师',
                                        'list' => array()
                                    ),
                                3 => array(
                                        'id' => 6,
                                        'name' => '化妆师',
                                        'list' => array()
                                    ),
                                4 => array(
                                        'id' => 7,
                                        'name' => '其他',
                                        'list' => array()
                                    ),

                            ),
                        'list' => array()
                    ),
                2 => array(
                        'supplier_type_name' => '灯光／音响／视频',
                        'type' => 1,
                        'tap' => array(
                                0 => array(
                                        'id' => 8,
                                        'name' => '灯光',
                                        'list' => array()
                                    ),
                                1 => array(
                                        'id' => 23,
                                        'name' => '音响',
                                        'list' => array()
                                    ),
                                2 => array(
                                        'id' => 9,
                                        'name' => '视频',
                                        'list' => array()
                                    ),
                            ),
                        'list' => array()
                    ),
            );

        foreach ($decoration_tap as $key_tap => $value_tap) {
            $item = array();
            $item['id'] = $value_tap['id'];
            $item['name'] = $value_tap['name'];
            $item['list'] = array();
            foreach ($result as $key_r => $value_r) {
                if($value_r['decoration_tap'] == $value_tap['id']){
                    $t = array();
                    $t['id'] = $value_r['id'];
                    $t['account_id'] = $value_r['account_id'];
                    $t['supplier_id'] = $value_r['supplier_id'];
                    $t['service_product_id'] = $value_r['service_product_id'];
                    $t['supplier_type_id'] = $value_r['supplier_type_id'];
                    $t['dish_type'] = $value_r['dish_type'];
                    $t['decoration_tap'] = $value_r['decoration_tap'];
                    $t['standard_type'] = $value_r['standard_type'];
                    $t['name'] = $value_r['name'];
                    $t['category'] = $value_r['category'];
                    $t['unit_price'] = $value_r['unit_price'];
                    $t['unit_cost'] = $value_r['unit_cost'];
                    $t['unit'] = $value_r['unit'];
                    $t['service_charge_ratio'] = $value_r['service_charge_ratio'];
                    $t['ref_pic_url'] = $value_r['ref_pic_url'];
                    $t['description'] = $value_r['description'];
                    $t['product_show'] = $value_r['product_show'];
                    $t['update_time'] = $value_r['update_time'];
                    if($value_r['supplier_type_id'] == 20){
                        $t['CR_ID'] = 10000000 + $value_r['id'];
                    }else if($value_r['supplier_type_id'] == 8 || $value_r['supplier_type_id'] == 9 || $value_r['supplier_type_id'] == 23){
                        $t['CR_ID'] = 30000000 + $value_r['id'];
                    }else if($value_r['supplier_type_id'] == 3 || $value_r['supplier_type_id'] == 4 || $value_r['supplier_type_id'] == 5 || $value_r['supplier_type_id'] == 6 || $value_r['supplier_type_id'] == 7){
                        $CI_Type = 0;
                        if($value_r['supplier_type_id'] == 3){$CI_Type=6;};
                        if($value_r['supplier_type_id'] == 4){$CI_Type=13;};
                        if($value_r['supplier_type_id'] == 5){$CI_Type=14;};
                        if($value_r['supplier_type_id'] == 6){$CI_Type=15;};
                        if($value_r['supplier_type_id'] == 7){$CI_Type=21;};
                        $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value_r['id']);
                        $result7 = $result7->queryAll();
                        // print_r($result7);die;
                        if(isset($result7[0])){
                            $t['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                        };
                    }
                    $item['list'][]=$t;
                };
            };
            $product_store[0]['tap'][]=$item;
        };

        foreach ($result as $key => $value) {
            $t = array();
            $t['id'] = $value['id'];
            $t['account_id'] = $value['account_id'];
            $t['supplier_id'] = $value['supplier_id'];
            $t['service_product_id'] = $value['service_product_id'];
            $t['supplier_type_id'] = $value['supplier_type_id'];
            $t['dish_type'] = $value['dish_type'];
            $t['decoration_tap'] = $value['decoration_tap'];
            $t['standard_type'] = $value['standard_type'];
            $t['name'] = $value['name'];
            $t['category'] = $value['category'];
            $t['unit_price'] = $value['unit_price'];
            $t['unit_cost'] = $value['unit_cost'];
            $t['unit'] = $value['unit'];
            $t['service_charge_ratio'] = $value['service_charge_ratio'];
            $t['ref_pic_url'] = $value['ref_pic_url'];
            $t['description'] = $value['description'];
            $t['product_show'] = $value['product_show'];
            $t['update_time'] = $value['update_time'];
            if($value['supplier_type_id'] == 20){
                $t['CR_ID'] = 10000000 + $value['id'];
            }else if($value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){
                $t['CR_ID'] = 30000000 + $value['id'];
            }else if($value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7){
                $CI_Type = 0;
                if($value['supplier_type_id'] == 3){$CI_Type=6;};
                if($value['supplier_type_id'] == 4){$CI_Type=13;};
                if($value['supplier_type_id'] == 5){$CI_Type=14;};
                if($value['supplier_type_id'] == 6){$CI_Type=15;};
                if($value['supplier_type_id'] == 7){$CI_Type=21;};
                $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value['id']);
                $result7 = $result7->queryAll();
                // print_r($result7);die;
                if(isset($result7[0])){
                    if(isset($result7[0])){
                    $t['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                };
                };
            }
            if($value['supplier_type_id'] == 3){
                // echo $value['supplier_type_id'].",".json_encode($t)."||";
                $product_store[1]['tap'][0]['list'][]=$t;
            };
            if($value['supplier_type_id'] == 4){
                $product_store[1]['tap'][1]['list'][]=$t;
            };
            if($value['supplier_type_id'] == 5){
                $product_store[1]['tap'][2]['list'][]=$t;
            };
            if($value['supplier_type_id'] == 6){
                $product_store[1]['tap'][3]['list'][]=$t;
            };
            if($value['supplier_type_id'] == 7){
                $product_store[1]['tap'][4]['list'][]=$t;
            };
            if($value['supplier_type_id'] == 8){
                $product_store[2]['tap'][0]['list'][]=$t;
            };
            if($value['supplier_type_id'] == 9){
                $product_store[2]['tap'][1]['list'][]=$t;
            };
            if($value['supplier_type_id'] == 23){
                $product_store[2]['tap'][2]['list'][]=$t;
            };
        };
        

        foreach ($product_store as $key => $value) {
            if($value['type'] == 1){
                foreach ($value['tap'] as $key1 => $value1) {
                    foreach ($value1['list'] as $key2 => $value2) {
                        $t = explode('.', $value2['ref_pic_url']);
                        if(isset($t[0]) && isset($t[1])){
                            $product_store[$key]['tap'][$key1]['list'][$key2]['ref_pic_url'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                        }else{
                            $product_store[$key]['tap'][$key1]['list'][$key2]['ref_pic_url'] = 'style/images/none_pic.png';
                        }
                    }
                }
            }else{
                foreach ($value['list'] as $key1 => $value1) {
                    $t = explode('.', $value1['ref_pic_url']);
                    if(isset($t[0]) && isset($t[1])){
                        $product_store[$key]['list'][$key1]['ref_pic_url'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                    }else{
                        $product_store[$key]['list'][$key1]['ref_pic_url'] = 'style/images/none_pic.png';
                    }
                };
            };
        };

        // foreach ($tap as $key => $value) {
        //     $tap[$key]['product_list']=array();
        //     foreach ($result as $key_p => $value_p) {
        //         if($value_p['decoration_tap']==$value['id']){
        //             $tap[$key]['product_list'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==3){
        //             $product_store['主持'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==6){
        //             $product_store['化妆'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==5){
        //             $product_store['摄影'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==4){
        //             $product_store['摄像'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==7){
        //             $product_store['其他'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==8){
        //             $product_store['灯光设备'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==9){
        //             $product_store['视频设备'][]=$value_p;
        //         };
        //         if($value_p['supplier_type_id']==23){
        //             $product_store['音响设备'][]=$value_p;
        //         };
        //     };
        // };
        // foreach ($tap as $key => $value) {
        //     $product_store['场地布置'][$value['name']]=$value['product_list'];
        // };

            
        echo json_encode($product_store);
    }

    public function actionDishstore()
    {
        // $_GET['token'] == 100;
        $staff = Staff::model()->findByPk($_GET['token']);

        $supplier_product = SupplierProduct::model()->findAll(array(
                'condition' => 'supplier_type_id=:st_id && account_id=:account_id',
                'params' => array(
                        ':st_id' => 2,
                        ':account_id' => $staff['account_id']
                    )
            ));


        $dish = DishType::model()->findAll();

        $dish_store = array();
        foreach ($dish as $key => $value) {
            $temp = array();
            $temp['dish_id'] = $value['id'];
            $temp['dish_name'] = $value['name'];
            $temp['dish_pic'] = $value['pic'];
            $temp['list'] = array();
            foreach ($supplier_product as $key1 => $value1) {
                if($value1['dish_type'] == $value['id']){
                    $t = explode('.', $value1['ref_pic_url']);
                    $item = array();
                    $item['sp_id'] = $value1['id'];
                    $item['name'] = $value1['name'];
                    $item['price'] = $value1['unit_price'];
                    $item['cost'] = $value1['unit_cost'];
                    $item['unit'] = $value1['unit'];
                    $item['CR_ID'] = 90000000 + $value1['id'];
                    if(isset($t[0]) && isset($t[1])){
                        $item['pic'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                    }else{
                        $item['pic'] = 'style/images/none_pic.png';
                    }
                    $temp['list'][] = $item;
                };
            };
            $dish_store[] = $temp;
        };

        echo json_encode($dish_store);

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

        echo $post->sort;

        $order_show=new OrderShowForm;
        $order_show->area_sort_batch_add(/*1033,3,2*/$post->order_id,$post->area,$post->sort);
        $order_show->words_insert(/*"ceshiwords",1033,3,2*/$post->words,$post->order_id,$post->area,$post->sort);
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
        $order_show->area_sort_batch_add($post->order_id,$post->area,$post->sort);
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
        $order_show->area_sort_batch_add($post->order_id,$post->area,$post->sort);
        $order_show->product_insert($post->area,$post->sort,$post->order_id,$post->sp_id);
    }

    public function actionNew_product_insert()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $post = array(
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
        //         'order_id' => 44,
        //         'staff_id' => 100,
        //         'area' => 1,
        //         'sort' => 1,
        //     );
        // $staff = Staff::model()->findByPk($post['staff_id']);
        $staff = Staff::model()->findByPk($post->staff_id);

        $order_show=new OrderShowForm;
        $order_show->area_sort_batch_add($post->order_id,$post->area,$post->sort);
        // $order_show->area_sort_batch_add($post['order_id'],$post['area'],$post['sort']);
        $result = $order_show->new_product_insert($staff['account_id'],$post->supplier_id,$post->supplier_type_id,$post->decoration_tap,$post->name,$post->category,$post->price,$post->cost,$post->unit,$post->remark,$post->url,$post->order_id,$post->amount,$post->area,$post->sort);
        // echo $order_show->new_product_insert($staff['account_id'],$post['supplier_type_id'],$post['supplier_type_id'],$post['decoration_tap'],$post['name'],$post['category'],$post['price'],$post['cost'],$post['unit'],$post['remark'],$post['url'],$post['order_id'],$post['amount'],$post['area'],$post['sort']);
        echo $result;
    }

    public function actionGet_supplier_tab(){
        $staff = Staff::model()->findByPk($_GET['token']);

        $result = yii::app()->db->createCommand("select sp.id,sp.type_id,sf.name from supplier sp left join staff sf on sp.staff_id=sf.id where sp.account_id=".$staff['account_id']." and sp.type_id=20");
        $supplier = $result->queryAll();

        $result = yii::app()->db->createCommand("select * from supplier_product_decoration_tap where account_id=".$staff['account_id'].' order by sort');
        $decoration_tap = $result->queryAll();

        $data = array(
                'supplier' => $supplier,
                'decoration_tap' => $decoration_tap
            );

        echo json_encode($data);
    }

    public function actionSave_ppt()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $json_st = '{"order_id":"1033","order_show":[{"area_id":"1","area_name":"新娘布置","description":null,"data":[]},{"area_id":"2","area_name":"酒店室外","description":null,"data":[{"show_id":"9581","data_type":"2","show_data":"000","product_id":"4461","sort":"2"}]},{"area_id":"3","area_name":"签到区","description":null,"data":[{"show_id":"9565","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9566","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9570","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9571","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9572","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9573","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9574","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9575","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"5"},{"show_id":"9576","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"4"},{"show_id":"9577","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"4"},{"show_id":"9578","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"4"},{"show_id":"9579","data_type":"0","show_data":"ceshiwords","product_id":0,"sort":"3"}]},{"area_id":"4","area_name":"仪式区","description":null,"data":[]},{"area_id":0,"area_name":"待分配产品","description":"","data":[{"show_id":"9522","data_type":"2","show_data":"/upload/伊缘圆 (2)20160523114630.jpg","product_id":"3990","sort":"2"},{"show_id":"9523","data_type":"2","show_data":"/upload/伊缘圆 (37)20160519155117.jpg","product_id":"3991","sort":"3"},{"show_id":"9524","data_type":"2","show_data":"/upload/伊缘圆 (33)20160519154522.jpg","product_id":"3992","sort":"4"},{"show_id":"9525","data_type":"2","show_data":"/upload/伊缘圆 (2)20160519151813.jpg","product_id":"3993","sort":"5"},{"show_id":"9526","data_type":"2","show_data":"/upload/7d80a1bfa1b5ccb1b08ca88dc8987a5bf6adcb7172ac-cmz1yd20160524134641.jpg","product_id":"3994","sort":"6"},{"show_id":"9527","data_type":"2","show_data":"/upload/15C5ACD0-54F3-4326-8FF1-9FEFF1DB965820160524141027.jpg","product_id":"3995","sort":"7"},{"show_id":"9528","data_type":"2","show_data":"000","product_id":"3998","sort":"8"},{"show_id":"9529","data_type":"2","show_data":"000","product_id":"3999","sort":"9"},{"show_id":"9530","data_type":"2","show_data":"/upload/3420160524135134.jpg","product_id":"4000","sort":"10"},{"show_id":"9531","data_type":"2","show_data":"/upload/b9604d7f7b3034c1d95c02885b71db0961ab21374031-RNmHwU20160524141319.jpg","product_id":"4001","sort":"11"},{"show_id":"9532","data_type":"2","show_data":"/upload/d702f70c13c7e428de35211d0c790ac761138522bcee-422K2T20160523113906.jpg","product_id":"4002","sort":"12"},{"show_id":"9533","data_type":"2","show_data":"/upload/66520fa57fceefe5cde71d76796c2e05c74b1bba1973d-3XaEsz20160523113621.jpg","product_id":"4003","sort":"13"},{"show_id":"9534","data_type":"2","show_data":"1","product_id":"4004","sort":"14"},{"show_id":"9535","data_type":"2","show_data":"000","product_id":"4005","sort":"15"},{"show_id":"9536","data_type":"2","show_data":"000","product_id":"4006","sort":"16"},{"show_id":"9537","data_type":"2","show_data":"000","product_id":"4007","sort":"17"},{"show_id":"9538","data_type":"2","show_data":"/upload/52c524dc79a2220160522171642.jpg","product_id":"4008","sort":"18"},{"show_id":"9539","data_type":"2","show_data":"/upload/52c5250b3639020160522171808.jpg","product_id":"4009","sort":"19"},{"show_id":"9540","data_type":"2","show_data":"/upload/伊缘圆 (1)120160519133854.jpg","product_id":"4010","sort":"20"},{"show_id":"9541","data_type":"2","show_data":"/upload/伊缘圆 (18)20160519165718.jpg","product_id":"4011","sort":"21"},{"show_id":"9542","data_type":"2","show_data":"/upload/jmb72SJAyB7JkB4akXs6fQWc3nXQjYnhh70020160523111458.jpg","product_id":"4012","sort":"22"},{"show_id":"9543","data_type":"2","show_data":"000","product_id":"4013","sort":"23"},{"show_id":"9544","data_type":"2","show_data":"000","product_id":"4014","sort":"24"},{"show_id":"9545","data_type":"2","show_data":"1","product_id":"4015","sort":"25"},{"show_id":"9546","data_type":"2","show_data":"000","product_id":"4016","sort":"26"},{"show_id":"9547","data_type":"2","show_data":"000","product_id":"4017","sort":"27"},{"show_id":"9548","data_type":"2","show_data":"000","product_id":"4018","sort":"28"},{"show_id":"9549","data_type":"2","show_data":"/upload/66520fa57fceefe5cde71d76796c2e05c74b1bba1973d-3XaEsz20160523113621.jpg","product_id":"4019","sort":"29"},{"show_id":"9550","data_type":"2","show_data":"/upload/d702f70c13c7e428de35211d0c790ac761138522bcee-422K2T20160523113906.jpg","product_id":"4020","sort":"30"},{"show_id":"9551","data_type":"2","show_data":"/upload/QQ截图2016061415482120160614154838.png","product_id":"4021","sort":"31"},{"show_id":"9552","data_type":"2","show_data":"/upload/QQ截图2016061415421520160614154459.png","product_id":"4022","sort":"32"},{"show_id":"9553","data_type":"2","show_data":"/upload/QQ截图2016061415470520160614154724.png","product_id":"4023","sort":"33"},{"show_id":"9554","data_type":"2","show_data":"/upload/c新娘花房 (3)20160614154037.jpg","product_id":"4024","sort":"34"},{"show_id":"9555","data_type":"2","show_data":"/upload/QQ截图2016061415331720160614153334.png","product_id":"4025","sort":"35"},{"show_id":"9556","data_type":"2","show_data":"/upload/图片320160614152427.png","product_id":"4026","sort":"36"},{"show_id":"9557","data_type":"2","show_data":"/upload/伊缘圆 (2)20160519151813.jpg","product_id":"4027","sort":"37"},{"show_id":"9558","data_type":"2","show_data":"/upload/0220160524135057.jpg","product_id":"4028","sort":"38"},{"show_id":"9559","data_type":"2","show_data":"/upload/4320160524135243.jpg","product_id":"4029","sort":"39"},{"show_id":"9560","data_type":"2","show_data":"/upload/图片220160614150453.png","product_id":"4030","sort":"40"},{"show_id":"9561","data_type":"2","show_data":"/upload/图片120160614150153.png","product_id":"4031","sort":"41"},{"show_id":"9562","data_type":"2","show_data":"/upload/QQ截图2016061414570820160614145740.png","product_id":"4032","sort":"42"},{"show_id":"9563","data_type":"2","show_data":"000","product_id":"4036","sort":"43"},{"show_id":"9564","data_type":"2","show_data":"000","product_id":"4037","sort":"44"},{"show_id":"9567","data_type":"0","show_data":"上传文字123123123","product_id":0,"sort":"21"},{"show_id":"9568","data_type":"0","show_data":"上传文字123123","product_id":0,"sort":"4"},{"show_id":"9569","data_type":"0","show_data":"上传文字34234234234","product_id":0,"sort":"4"},{"show_id":"1001","data_type":"2","show_data":"/upload/b27f929fb5975de4d70df883d6964a887ff5935b2d1df-5hHHnX20160524134720.jpg","product_id":"3997","sort":"1"},{"show_id":"7255","data_type":"2","show_data":"/upload/BF472384-354E-4033-B79C-91E4A4D2D6AE20160524140642.jpg","product_id":"3996","sort":"1"}]}]}';
        // $post = json_decode($json_st);
        // $json = json_decode( json_encode($post),true);

        // $post = array(
        //         'order_id' => 1033,
        //         'list' => array(
        //                 0 => array('os_id'=> 1,'area_sort'=> 4,'show_area'=>1),
        //                 1 => array('os_id'=> 2,'area_sort'=> 5,'show_area'=>2),
        //                 2 => array('os_id'=> 3,'area_sort'=> 6,'show_area'=>3),
        //                 3 => array('os_id'=> 4,'area_sort'=> 7,'show_area'=>4),
        //             )
        //     );

        $list=array();
        foreach ($post->order_show as $key => $value) {
            if(!empty($value->data)){
                foreach ($value->data as $key1 => $value1) {
                    $item = array();
                    $item['os_id'] = $value1->show_id;
                    $item['area_sort'] = $value1->sort;
                    $item['show_area'] = $value->area_id;
                    $list[]=$item;
                };    
            }
            
        };
        // print_r($list);
        
        // print_r(json_encode($list));die;

        

        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => $post->order_id,
                    )
            ));
        //删除  list中不存在的order_show
        foreach ($order_show as $key => $value) {
            $t = 0;
            foreach ($list as $key_l => $value_l) {
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

        // //更新order_show   area_sort
        // $sql = "";
        // foreach ($list as $key => $value) {
        //     $sql .= " when id=".$value['os_id']." then ".$value['area_sort'];
        // };
        // $result = yii::app()->db->createCommand("update order_show set area_sort = case ".$sql." else area_sort end");
        // $result = $result->queryAll();

        // //更新order_show   show_area
        // $sql = "";
        // foreach ($post->list as $key => $value) {
        //     $sql .= " when id=".$value['os_id']." then ".$value['show_area'];
        // };
        // $result = yii::app()->db->createCommand("update order_show set show_area = case ".$sql." else show_area end");
        // $result = $result->queryAll();

        //更新  order_show :  area_sort、show_area
        foreach ($list as $key => $value) {
            OrderShow::model()->updateByPk($value['os_id'],array('show_area'=>$value['show_area'],'area_sort'=>$value['area_sort']));
        };
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

    public function actionDel_op()
    {
        $post = json_decode(file_get_contents('php://input'));

        OrderProduct::model()->deleteByPk($post->op_id);
        OrderShow::model()->deleteAll('order_product_id=:op_id',array(':op_id'=>$post->op_id));
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
        OrderSet::model()->updateByPk($post->feast->order_set_id,array('amount'=>$post->feast->amount,'actual_service_ratio'=>$post->feast->actual_service_ratio));
    
        $order_set = OrderSet::model()->findByPk($post->feast->order_set_id);

        //删除菜品
        $result = yii::app()->db->createCommand("select op.id ".
            "from order_product op left join supplier_product sp on op.product_id=sp.id ".
            "where op.order_id=".$order_set['order_id']." and sp.supplier_type_id=2");
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            $t = 0;
            foreach ($post->feast->product_list as $key_l => $value_l) {
                if($value['id'] == $value_l->product_id){
                    $t++;
                };
            };
            if($t == 0){
                OrderProduct::model()->deleteByPk($value['id']);
            };
        };

        foreach ($post->feast->product_list as $key => $value) {
            OrderProduct::model()->updateByPk($value->product_id,array('actual_price'=>$value->price,'unit'=>$value->amount,'actual_unit_cost'=>$value->cost,'actual_service_ratio'=>$post->feast->actual_service_ratio));
        }; 
    }

    public function actionUpdate_other()
    {
        $post = json_decode(file_get_contents('php://input'));

        //编辑order_set
        OrderSet::model()->updateByPk($post->other->order_set_id,array('amount'=>$post->other->amount,'actual_service_ratio'=>$post->other->actual_service_ratio));
    
        $order_set = OrderSet::model()->findByPk($post->other->order_set_id);

        //删除单品
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'role=:role',
                'params' => array(
                        ':role' => 1
                    )
            ));

        $supplier_type_list = "(";
        foreach ($supplier_type as $key => $value) {
            $supplier_type_list .= $value['id'].",";
        };
        $supplier_type_list = rtrim($supplier_type_list, ",");
        $supplier_type_list .= ")";


        $result = yii::app()->db->createCommand("select op.id ".
            "from order_product op left join supplier_product sp on op.product_id=sp.id ".
            "where op.order_id=".$order_set['order_id']." and sp.supplier_type_id in ".$supplier_type_list);
        $result = $result->queryAll();

        foreach ($result as $key => $value) {
            $t = 0;
            foreach ($post->other->product_list as $key_l => $value_l) {
                if($value['id'] == $value_l->product_id){
                    $t++;
                };
            };
            if($t == 0){
                OrderProduct::model()->deleteByPk($value['id']);
            };
        };

        foreach ($post->other->product_list as $key => $value) {
            OrderProduct::model()->updateByPk($value->product_id,array('actual_price'=>$value->price,'unit'=>$value->amount,'actual_unit_cost'=>$value->cost));
        };
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

        if($post->tuidan_id != 0){
            $order = Order::model()->findByPk($post->order_id);

            $data = new OrderProduct;
            $data->account_id=$order['account_id'];
            $data->order_id=$post->order_id;
            $data->product_type=0;
            $data->product_id=$post->tuidan_id;
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

        /************************************************************************/
        /************************************************************************/
        /******** CR_ID 构造规则：  ***********************************************/
        /******** 场地布置 20000000          ＋ sp_id     *************************/
        /******** 灯光／音响／视频  30000000   ＋  sp_id    *************************/
        /******** PPT 里的纯“图片” 60000000   ＋   show_id *************************/
        /******** 餐饮零点  90000000          +  sp_id     ************************/
        /******** 服务人员 120000000          ＋ CI_ID     ************************/
        /************************************************************************/
        /************************************************************************/

        $post = json_decode(file_get_contents('php://input'));

        $result = yii::app()->db->createCommand("select os.id,os.type,words,osi.img_url,sp.ref_pic_url,show_area,area_sort,sp.id as sp_id,sp.supplier_type_id from order_show os ".
            "left join order_show_img osi on img_id=osi.id ".
            "left join order_product op on order_product_id=op.id ".
            "left join supplier_product sp on op.product_id=sp.id ".
            // "where os.order_id=1203 and supplier_type_id!=2".
            "where os.order_id=".$post->order_id." and supplier_type_id!=2".
            " order by show_area ASC,area_sort ASC");
        $result = $result->queryAll();

        $show_data = array();
        $non_area = array();
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
                $item['data'] = "http://file.cike360.com".$value['img_url'];
                $item['CR_ID'] = 60000000 + $value['id'];
            };
            if($value['type'] == 2){
                $item['data'] = "http://file.cike360.com".$value['ref_pic_url'];
                if($value['supplier_type_id'] == 20){
                    $item['CR_ID'] = 20000000 + $value['sp_id'];
                }else if($value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){
                    $item['CR_ID'] = 30000000 + $value['sp_id'];
                }else if($value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7){
                    $CI_Type = 0;
                    if($value['supplier_type_id'] == 3){$CI_Type=6;};
                    if($value['supplier_type_id'] == 4){$CI_Type=13;};
                    if($value['supplier_type_id'] == 5){$CI_Type=14;};
                    if($value['supplier_type_id'] == 6){$CI_Type=15;};
                    if($value['supplier_type_id'] == 7){$CI_Type=21;};
                    $result7 = yii::app()->db->createCommand("SELECT case_info.CI_ID from case_info left join supplier on case_info.CT_ID=supplier.staff_id  left join supplier_product on supplier.id=supplier_product.supplier_id where supplier_product.id=".$value['sp_id']);
                    $result7 = $result7->queryAll();
                    // print_r($result7);die;
                    if(isset($result7[0])){
                                $item['CR_ID'] = 120000000 + $result7[0]['CI_ID'];
                            };
                }
            };

            if($value['show_area'] != 0){
                $show_data[] = $item;
            }else{
                $non_area[] = $item;
            };    
        };

        foreach ($non_area as $key => $value) {
            $show_data[] = $value;
        };

        echo json_encode($show_data);

    }

    public function actionAdd_set_to_order()
    {
        $post = json_decode(file_get_contents('php://input'));

        $staff = Staff::model()->findByPk($post->token);

        $result = yii::app()->db->createCommand("select * from case_info ci left join wedding_set ws on CT_ID=ws.id where ci.CI_ID=".$post->ci_id);
        $result = $result->queryAll();

        $product = explode(',', $result[0]['product_list']);

        $data = new OrderSet;
        $data->order_id=$post->order_id;
        $data->wedding_set_id=$result[0]['id'];
        $data->amount=$post->amount;
        $data->remark="";
        $data->actual_service_ratio=$post->actual_service_ratio;
        $data->order_product_list=$result[0]['product_list'];
        $data->final_price=$result[0]['final_price'];
        $data->update_time=date('y-m-d h:i:s',time());
        $data->save();
        $order_set_id = $data->attributes['id'];
        $i=1;
        foreach ($product as $key => $value) {
            $t=explode('|', $value);
            $data = new OrderProduct;
            $data->account_id=$staff['account_id'];
            $data->order_id=$post->order_id;
            $data->product_type=0;
            $data->product_id=$t[0];
            $data->order_set_id=$order_set_id;
            $data->sort=1;
            $data->actual_price=$t[1];
            $data->unit=$t[2];
            $data->actual_unit_cost=$t[3];
            $data->update_time=date('y-m-d h:i:s',time());
            $data->actual_service_ratio=$post->actual_service_ratio;
            $data->remark="";
            $data->save();
            $order_product_id = $data->attributes['id'];


            $data = new OrderShow;
            $data->type=2;
            $data->img_id=0;
            $data->order_product_id=$order_product_id;
            $data->words=0;
            $data->order_id=$post->order_id;
            $data->show_area=0;
            $data->area_sort=$i++;
            $data->update_time=date('y-m-d h:i:s',time());
            $data->save();
        }
        // echo json_encode($result);

    }

    public function actionNew_email_print()
    {
        $post = json_decode(file_get_contents('php://input'));

        $data = new StaffEmail;
        $data->staff_id = $post->staff_id;
        $data->email = $post->email;
        $data->update_time = date('y-m-d h:i:s',time());
        $data->save();

        $print = new PrintForm;
        $print->pad_print($post->order_id,$post->email);
    }

    public function actionOld_email_print()
    {
        $post = json_decode(file_get_contents('php://input'));

        $print = new PrintForm;
        $print->pad_print($post->order_id,$post->email);
        // $print->pad_print(1033,'80962715@qq.com');
    }

    public function actionUpdate_feast_remark()
    {
        $post = json_decode(file_get_contents('php://input'));

        OrderSet::model()->updateByPk($post->order_set_id,array('remark'=>$post->remark));
    }

    public function actionUpdate_order_detail_info()
    {
        $post = json_decode(file_get_contents('php://input'));

        $result = OrderWedding::model()->updateAll(array(
            'groom_name' => $post->groom_name,
            'groom_phone' => $post->groom_phone,
            'groom_qq' => $post->groom_qq,
            'groom_wechat' => $post->groom_wechat,
            'bride_name' => $post->bride_name,
            'bride_phone' => $post->bride_phone,
            'bride_qq' => $post->bride_qq,
            'bride_wechat' => $post->bride_wechat,
            'contact_name' => $post->contact_name,
            'contact_phone' => $post->contact_phone,
        ),'order_id=:order_id',array('order_id'=>$post->order_id));
        Order::model()->updateByPk($post->order_id,array('order_name'=>$post->groom_name."&".$post->bride_name,'order_date'=>$post->order_date,'order_place'=>$post->order_place,'staff_hotel_id'=>$post->hotel_id));

        print_r($result);
    }

    public function actionDel_feast_set()
    {
        $post = json_decode(file_get_contents('php://input'));

        //删除：order_set
        // OrderSet::model()->deleteAll('order_id=:order_id && wedding_set_id=:wedding_set_id',array(':order_id' => $post->order_id , ':wedding_set_id' => $post->wedding_set_id));
        OrderSet::model()->deleteByPk($post->order_set_id);
        OrderProduct::model()->deleteAll('order_id=:order_id && order_set_id=:order_set_id' , array(':order_id' => $post->order_id , ':order_set_id' => $post->order_set_id));
    }

    public function actionDel_other_set()
    {
        $post = json_decode(file_get_contents('php://input'));

        // $post = array(
        //         'order_set_id' => 55,
        //         'order_id' => 54,
        //     );

        //删除：order_set、order_product、order_show
        OrderSet::model()->deleteByPk($post->order_set_id);
        // OrderSet::model()->deleteByPk($post['order_set_id']);
        $order_product = OrderProduct::model()->findAll(array(
            'condition' => 'order_id=:order_id && order_set_id=:order_set_id',
            'params' => array(
                ':order_id' => $post->order_id , 
                // ':order_id' => $post['order_id'] , 
                ':order_set_id' => $post->order_set_id
                // ':order_set_id' => $post['order_set_id']
            )
        ));
        foreach ($order_product as $key => $value) {
            OrderShow::model()->deleteAll('order_id=:order_id && order_product_id=:product_id' , array(':order_id' => $post->order_id , ':product_id' => $value['id']));
            // print_r(OrderShow::model()->deleteAll('order_id=:order_id && order_product_id=:product_id' , array(':order_id' => $post['order_id'] , ':product_id' => $value['id'])));
            OrderProduct::model()->deleteByPk($value['id']);
        };
    }

    public function actionAdd_set_dish()
    {
        $post = json_decode(file_get_contents('php://input'));

        $staff = Staff::model()->findByPk($post->token);
        $sp = SupplierProduct::model()->findByPk($post->sp_id);
        $order_set = OrderSet::model()->findByPk($post->order_set_id);

        $data = new OrderProduct;
        $data->account_id = $staff['account_id'];
        $data->order_id = $post->order_id;
        $data->product_type = 0;
        $data->product_id = $post->sp_id;
        $data->order_set_id = $post->order_set_id;
        $data->actual_price = $post->price;
        $data->unit = $post->amount;
        $data->actual_unit_cost = $sp['unit_cost'];
        $data->actual_service_ratio = $order_set['actual_service_ratio'];
        $data->remark = "";
        $data->update_time = date('y-m-d h:i:s',time());
        $data->save();
    }


    public function actionSendcode()
    {
        // $post = json_decode(file_get_contents('php://input'));


        $url = "http://localhost/school/crm_web/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";   //服务器发消息
        // $url = "http://localhost/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";               //本地发消息
        
        $data = array('telephone' => $_GET['telephone']);
        $result = WPRequest::post_code($url, $data);
        // print_r($result);

        $t = MessageCode::model()->findAll(array(
                'condition' => 'phone=:phone',
                'params' => array(
                        ':phone' => $_GET['telephone']
                    )
            ));

        if(empty($t)){
            $message_code = new MessageCode;
            $message_code->phone = $_GET['telephone'];
            $message_code->code = $result;
            $message_code->update_time = date('y-m-d h:i:s',time());
            $message_code->save();
        }else{
            MessageCode::model()->updateAll(array('code' => $result),'phone=:phone',array(':phone' => $_GET['telephone']));
        };
    }

    public function actionTestcode()
    {
        // $post = json_decode(file_get_contents('php://input'));

        $code = MessageCode::model()->find(array(
                'condition' => 'phone=:phone',
                'params' => array(
                        ':phone' => $_GET['telephone']
                    )
            ));
        if(empty($code)){
            echo 'failed';
        }else{
            if(isset($code['code'])){
                if($code['code'] == $_GET['code']){
                    $staff = Staff::model()->find(array(
                            'condition' => 'telephone=:telephone',
                            'params' => array(
                                    ':telephone' => $_GET['telephone']
                                )
                        ));
                    if(!empty($staff)){
                        if($staff['account_id'] != 0){
                            echo 'existed';
                        }else{
                            echo 'success_existed';
                        };
                    }else{
                        $data = new Staff;
                        $data->account_id = 0;
                        $data->name = '体验用户';
                        $data->telephone = $_GET['telephone'];
                        $data->department_list='[2,3]';
                        $data->password = '88888888';
                        $data->save();
                        $staff_id=$data->attributes['id'];
                        $this->create_example_order($staff_id);
                        echo 'success_new';
                    }
                }else{
                    echo 'failed';
                };
            }else{
                echo 'failed';
            };
        };
    }

    public function actionAddProductToExistOrder(){
        $post = json_decode(file_get_contents('php://input'));

        // print_r($post);

        // $post = array(
        //         'token' => 100,
        //         'product_id' =>703,
        //         'order_id' =>53,
        //         'area_id' =>1
        //     );

        $staff = Staff::model()->findByPk($post->token);
        // $staff = Staff::model()->findByPk($post['token']);

        $product = SupplierProduct::model()->findByPk($post->product_id);
        // $product = SupplierProduct::model()->findByPk($post['product_id']);

        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id && show_area=:show_area',
                'params' => array(
                        ':order_id' => $post->order_id,
                        // ':order_id' => $post['order_id'],
                        ':show_area' => $post->area_id,
                        // ':show_area' => $post['area_id'],
                    ),
                'order' => 'area_sort DESC'
            ));
        $sort = 1;
        if(isset($order_show[0]['area_sort'])){
            $sort=$order_show[0]['area_sort']+1;
        };

        $data = new OrderProduct;
        $data->account_id=$staff['account_id'];
        $data->order_id=$post->order_id;
        // $data->order_id=$post['order_id'];
        $data->product_type=0;
        $data->product_id=$post->product_id;
        // $data->product_id=$post['product_id'];
        $data->order_set_id=0;
        $data->actual_price=$product['unit_price'];
        $data->unit=1;
        $data->actual_unit_cost=$product['unit_cost'];
        $data->update_time=date('y-m-d h:i:s',time());
        print_r($data->save());
        $order_product_id=$data->attributes['id'];

        $data = new OrderShow;
        $data->type=2;
        $data->img_id=0;
        $data->order_product_id=$order_product_id;
        $data->words=0;
        $data->order_id=$post->order_id;
        // $data->order_id=$post['order_id'];
        $data->show_area=$post->area_id;
        // $data->show_area=$post['area_id'];
        $data->area_sort=$sort;
        $data->update_time=date('y-m-d h:i:s',time());
        print_r($data->save());

    }

    public function actionArealist(){
        $order_show_list = OrderShowArea::model()->findAll(array(
                'condition' => 'id != :id',
                'params' => array(
                        ':id' => 1
                    )
            ));

        $area = array();

        foreach ($order_show_list as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $area[] = $item;
        };

        // print($order_show_list);die;

        echo json_encode($area);
    }

    public function actionChannelinsert()
    {
        $post = json_decode(file_get_contents('php://input'));

        $t = Staff::model()->findAll(array(
                'condition' => 'telephone=:telephone',
                'params' => array(
                        ':telephone' => $post->phone
                    )
            ));

        if(empty($t)){
            $designer = Staff::model()->findByPk($post->token);

            $admin=new Staff;         
            $admin->account_id=$designer['account_id']; 
            $admin->name=$post->name; 
            $admin->telephone=$post->phone; 
            $admin->save();
            $staff_id=$admin->attributes['id'];
            
            $admin=new Supplier;
            $admin->account_id=$designer['account_id']; 
            $admin->type_id=16; 
            $admin->staff_id=$staff_id; 
            $admin->save();
            $supplier_id=$admin->attributes['id'];

            $admin=new SupplierProduct;
            $admin->account_id=$designer['account_id']; 
            $admin->supplier_id=$supplier_id; 
            $admin->supplier_type_id=16; 
            $admin->standard_type=0; 
            $admin->name=$post->name; 
            $admin->category=2; 
            $admin->save();
            $product_id=$admin->attributes['id'];

            $result = yii::app()->db->createCommand("select o.id ".
                " from order_product o left join supplier_product s on o.product_id=s.id ".
                " where o.order_id=".$post->order_id." and s.supplier_type_id=16");
            $result = $result->queryAll();

            foreach ($result as $key => $value) {
                OrderProduct::model()->deleteByPk($value['id']);
            };

            if($product_id != 0){
                $order = Order::model()->findByPk($post->order_id);
                $data = new OrderProduct;
                $data->account_id=$order['account_id'];
                $data->order_id=$post->order_id;
                $data->product_type=0;
                $data->product_id=$product_id;
                $data->sort=1;
                $data->actual_price=0;
                $data->unit=1;
                $data->actual_unit_cost=0;
                $data->update_time=date('y-m-d h:i:s',time());
                $data->actual_service_ratio=0;
                $data->save();
            };

        }else{
            $result = yii::app()->db->createCommand("select o.id from order_product o left join supplier_product s on o.product_id=s.id where o.order_id=".$post->order_id." and s.supplier_type_id=16");
            $result = $result->queryAll();

            foreach ($result as $key => $value) {
                OrderProduct::model()->deleteByPk($value['id']);
            };

            $result = yii::app()->db->createCommand("select sp.id ".
                " from supplier_product sp left join supplier s on sp.supplier_id=s.id ".
                " left join staff on s.staff_id=staff.id ".
                " where staff.id=".$t[0]['id']." and sp.supplier_type_id=16");
            $product = $result->queryAll();

            $product_id = 0;
            $order = Order::model()->findByPk($post->order_id);


            if(empty($product)){
                $admin=new Supplier;
                $admin->account_id=$order['account_id']; 
                $admin->type_id=16; 
                $admin->staff_id=$t[0]['id']; 
                $admin->save();
                $supplier_id=$admin->attributes['id'];

                $admin=new SupplierProduct;
                $admin->account_id=$order['account_id']; 
                $admin->supplier_id=$supplier_id; 
                $admin->supplier_type_id=16; 
                $admin->standard_type=0; 
                $admin->name=$post->name; 
                $admin->category=2; 
                $admin->save();
                $product_id=$admin->attributes['id'];
            }else{
                $product_id = $product[0]['id'];
            }

            if($product_id != 0){
                $data = new OrderProduct;
                $data->account_id=$order['account_id'];
                $data->order_id=$post->order_id;
                $data->product_type=0;
                $data->product_id=$product_id;
                $data->sort=1;
                $data->actual_price=0;
                $data->unit=1;
                $data->actual_unit_cost=0;
                $data->update_time=date('y-m-d h:i:s',time());
                $data->actual_service_ratio=0;
                $data->save();
            };
        };
    }



    public function create_example_order($staff_id){
        $order = new Order;
        $order->account_id=0;
        $order->designer_id=$staff_id;
        $order->planner_id=$staff_id;
        $order->adder_id=$staff_id;
        $order->staff_hotel_id=0;
        $order->order_name="李雷&韩梅梅";
        $order->order_type=2;
        $order->order_date=date("y-m-d",strtotime("+20 day"));
        $order->order_status=3;
        $order->update_time=date("y-m-d",strtotime("-120 day"));
        $order->other_discount=8.5;
        $order->discount_range='17,20,3,8,9,10,11,12,13,14,15';
        $order->feast_discount=9;
        $order->cut_price=0;
        $order->save();
        $order_id = $order->attributes['id'];

        $order_product = OrderProduct::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => 1155,
                    ),
            ));

                

        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => 1155,
                    ),
            ));



        foreach ($order_show as $key => $value) {
            $order_product_id = 0;
            if($value['type'] == 2){
                foreach ($order_product as $key1 => $value1) {
                    if($value1['id'] == $value['order_product_id']){
                        $op = new OrderProduct;
                        $op->account_id=0;
                        $op->order_id=$order_id;
                        $op->product_type=0;
                        $op->product_id=$value1['product_id'];
                        $op->order_set_id=$value1['order_set_id'];
                        $op->sort=$value1['sort'];
                        $op->actual_price=$value1['actual_price'];
                        $op->unit=$value1['unit'];
                        $op->actual_unit_cost=$value1['actual_unit_cost'];
                        $op->update_time=date('y-m-d h:i:s',time());
                        $op->actual_service_ratio=$value1['actual_service_ratio'];
                        $op->remark=$value1['remark'];
                        $op->save();
                        $order_product_id = $op->attributes['id'];
                    };
                };
            };
            $os = new OrderShow;
            $os->type=$value['type'];
            $os->img_id=$value['img_id'];
            $os->order_product_id=$order_product_id;
            $os->words=$value['words'];
            $os->order_id=$order_id;
            $os->show_area=$value['show_area'];
            $os->area_sort=$value['area_sort'];
            $os->update_time=date('y-m-d h:i:s',time());
            $os->save();
        };

        $order_wedding = OrderWedding::model()->findAll(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                        ':order_id' => 1155,
                    )
            ));

        foreach ($order_wedding as $key => $value) {
            $ow = new OrderWedding;
            $ow->account_id=0;
            $ow->order_id=$order_id;
            $ow->update_time=date('y-m-d h:i:s',time());
            $ow->groom_name=$value['groom_name'];
            $ow->groom_phone=$value['groom_phone'];
            $ow->groom_qq=$value['groom_qq'];
            $ow->groom_wechat=$value['groom_wechat'];
            $ow->bride_name=$value['bride_name'];
            $ow->bride_phone=$value['bride_phone'];
            $ow->bride_qq=$value['bride_qq'];
            $ow->bride_wechat=$value['bride_wechat'];
            $ow->contact_name=$value['contact_name'];
            $ow->contact_phone=$value['contact_phone'];
            $ow->save();
        };
    }

    public function actionGoods_fenpei(){
        $post = json_decode(file_get_contents('php://input'));

        $order_show = OrderShow::model()->findAll(array(
                'condition' => 'order_id=:order_id && show_area=:show_area',
                'params' => array(
                        ':order_id' => $post->order_id,
                        ':show_area' => $post->area_id
                    ),
                'order' => 'area_sort DESC'
            ));
        $sort = 1;
        if(isset($order_show[0]['area_sort'])){
            $sort = $order_show[0]['area_sort']+1;
        };

        OrderShow::model()->updateAll(array('show_area'=>$post->area_id,'area_sort'=>$sort),'order_product_id=:opi && order_id=:oi',array(':opi'=>$post->product_id,':oi'=>$post->order_id));
    }

    public function actionDelOrder()
    {
        $post = json_decode(file_get_contents('php://input'));

        $order = Order::model()->deleteByPk($post->order_id);
        $t= OrderProduct::model()->find(array(
            'condition' => 'order_id=:order_id',
            'params' => array(
                ':order_id' => $post->order_id,
            ),
        ));
        $product = 0;
        if(!empty($t)){
            $product = OrderProduct::model()->deleteAll('order_id=:order_id',array(':order_id'=>$post->order_id));
        }else {
            $product = 1;
        }
          

        if($order>0 && $product>0){ 
            echo "success"; 
        }else{ 
            echo "fail"; 
        } 
    }

    public function actionOrderDoing()
    {
        $result = yii::app()->db->createCommand("select * from `order` where designer_id=".$_GET['token']." or planner_id=".$_GET['token']);
        $result = $result->queryAll();

        // print_r($result);die;

        $order_doing = array();
        $order_done = array();
        $order_nearest = array(
                'name' => '无订单',
                'to_date' => 0,
                'order_date' => ""
            );

        foreach ($result as $key => $value) {
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($value['order_date']);  //订单创建时间
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'to_date' => 0,
                );
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
            }else{
                $item['to_date'] = ceil(($zero1-$zero2)/86400); //60s*60min*24h
                $order_done[]=$item;
            }
        }

        // print_r($order_done);die;

        $to_date = array();
        foreach ( $order_doing as $key => $row ){
            $to_date[] = $row ['to_date'];
        };

        array_multisort($to_date, SORT_ASC, $order_doing);


        $to_date1 = array();
        foreach ( $order_done as $key => $row ){
            $to_date1[] = $row['to_date'];
        };

        array_multisort($to_date1, SORT_ASC, $order_done);

        if(!empty($order_doing)){
            $t = explode(' ', $order_doing[0]['order_date']);
            $name = "";
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($order_doing[0]['order_date']);  //订单创建时间

            foreach ($order_doing as $key => $value) {
                $order_date = explode(' ', $value['order_date']);
                if($order_date[0] == $t[0]){
                    $name .= $value['name'].",";
                };
            };
            $order_nearest['name'] = rtrim($name, ",");
            $order_nearest['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
            $order_nearest['order_date'] = $t[0];
        };  

        $order_data = array(
                'doing' => $order_doing,
                'done' => $order_done,
                'nearest' => $order_nearest,
            );

        echo json_encode($order_data);
    }

    public function actionOrderCalendar()
    {
        $staff=Staff::model()->findByPk($_GET['token']);
        $hotel=StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $staff['account_id'],
                    )
            ));
        $order_data = array();
        $hotel_list = array();
        $date_list = array(
                '2015' => array(
                        '01' => array(),
                        '02' => array(),
                        '03' => array(),
                        '04' => array(),
                        '05' => array(),
                        '06' => array(),
                        '07' => array(),
                        '08' => array(),
                        '09' => array(),
                        '10' => array(),
                        '11' => array(),
                        '12' => array(),
                    ),
                '2016' => array(
                        '01' => array(),
                        '02' => array(),
                        '03' => array(),
                        '04' => array(),
                        '05' => array(),
                        '06' => array(),
                        '07' => array(),
                        '08' => array(),
                        '09' => array(),
                        '10' => array(),
                        '11' => array(),
                        '12' => array(),
                    ),
                '2017' => array(
                        '01' => array(),
                        '02' => array(),
                        '03' => array(),
                        '04' => array(),
                        '05' => array(),
                        '06' => array(),
                        '07' => array(),
                        '08' => array(),
                        '09' => array(),
                        '10' => array(),
                        '11' => array(),
                        '12' => array(),
                    ),
                '2018' => array(
                        '01' => array(),
                        '02' => array(),
                        '03' => array(),
                        '04' => array(),
                        '05' => array(),
                        '06' => array(),
                        '07' => array(),
                        '08' => array(),
                        '09' => array(),
                        '10' => array(),
                        '11' => array(),
                        '12' => array(),
                    ),
            );

        foreach ($hotel as $key_h => $value_h) {
            $hotel_item = array(
                    'id' => $value_h['id'],
                    'name' => $value_h['name']
                );
            $hotel_list[] = $hotel_item;

            $hotel_order_item = $date_list;

            $order = Order::model()->findAll(array(
                    'condition' => 'account_id=:account_id && staff_hotel_id=:staff_hotel_id',
                    'params' => array(
                            ':account_id' => $staff['account_id'],
                            ':staff_hotel_id' => $value_h['id']
                        ),
                    'order' => 'order_date ASC'
                ));
            $tem1 = explode(' ', $order[0]['order_date']);
            $tem2 = explode('-', $tem1[0]);

            // print_r($order[0]['order_date']);die;
            $cur_year = $tem2[0];
            $cur_month = $tem2[1];
            $cur_day = $tem2[2];
            $cur_list = array();



            foreach ($order as $key => $value) {
                $t1 = explode(' ', $value['order_date']);
                $t2 = explode('-', $t1[0]);
                if($t2[0] == $cur_year && $t2[1] == $cur_month && $t2[2] == $cur_day){
                    $item = array(
                            'order_name' => $value['order_name'],
                            'order_date' => $value['order_date'],
                            'order_status' => $value['order_status'],
                            'to_date' => 0
                        );
                    $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
                    $zero2=strtotime ($value['order_date']);  //订单创建时间
                    if($zero1 > $zero2){
                        $item['to_date']=ceil(($zero1-$zero2)/86400); //60s*60min*24h
                    }else{
                        $item['to_date']=ceil(($zero2-$zero1)/86400); //60s*60min*24h
                    };
                    $cur_list[] = $item;
                }else{
                    $item = array(
                            $cur_day => $cur_list
                        );
                    $hotel_order_item[$cur_year][$cur_month][] = $item;

                    $cur_list = array();
                    $cur_year = $t2[0];
                    $cur_month = $t2[1];
                    $cur_day = $t2[2];

                    $item = array(
                            'order_name' => $value['order_name'],
                            'order_date' => $value['order_date'],
                            'to_date' => 0
                        );
                    $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
                    $zero2=strtotime ($value['order_date']);  //订单创建时间
                    if($zero1 > $zero2){
                        $item['to_date']=ceil(($zero1-$zero2)/86400); //60s*60min*24h
                    }else{
                        $item['to_date']=ceil(($zero2-$zero1)/86400); //60s*60min*24h
                    };
                    $cur_list[] = $item;
                }
            };

            $temp = array(
                    'id' => $value_h['id'],
                    'order_list' => $hotel_order_item
                );

            $order_data[] = $temp;
        };  

        $data = array(
                'order_data' => $order_data,
                'hotel_list' => $hotel_list
            );      
            
        echo json_encode($data);
    }

    public function actionFollowInsert()
    {
        $post = json_decode(file_get_contents('php://input'));

        $staff = Staff::model()->findByPk($post->token);
        $order = Order::model()->findByPk($post->order_id);

        $follow=new OrderMerchandiser;         
        $follow->order_id=$post->order_id; 
        $follow->staff_id=$post->token;
        $follow->time=$post->time;
        $follow->remarks=$post->remark;
        $follow->type=$post->type;
        $follow->staff_name=$staff['name'];
        $follow->order_name=$order['order_name'];
        $follow->order_date=$order['order_date'];
        $follow->save();
    }

    public function actionCashInsert()
    {
        $post = json_decode(file_get_contents('php://input'));

        $payment = OrderPayment::model()->find(array(
                'condition' => 'order_id=:order_id',
                'params' => array(
                    ':order_id' => $post->order_id
                    )));
        $order = Order::model()->find(array(
                'condition' => 'id=:id',
                'params' => array(
                    ':id' => $post->order_id
                    )));
        $date = explode(' ', $order['order_date']);
        $hotel = StaffHotel::model()->find(array(
                'condition' => 'id=:id',
                'params' => array(
                    ':id' => $order['staff_hotel_id']
                    )));   
        $staff1 = Staff::model()->findByPK($order['designer_id']);
        $staff2 = Staff::model()->findByPK($order['planner_id']);
        if ($order['order_type'] == 1) {
            $order_type = "会议";
        } else {
            $order_type = "婚礼";
        };
        

        if (empty($payment)) {
            $touser = "@all";
            $toparty = "";
            $content = "成单了！[".$hotel['name']."]
订单类型：".$order_type."
日期：".$date[0]."
策划师：".$staff1['name']."
统筹师：".$staff2['name'];
            $staff = Staff::model()->findByPk($post->token);
            $company = StaffCompany::model()->findByPk($staff['account_id']);  
            $corpid=$company['corpid'];
            $corpsecret=$company['corpsecret'];
            // WPRequest::sendMessage_Text($touser,$toparty,$content,$corpid,$corpsecret);
            //分享销客接口
            $appId = $hotel['fxiaoke_AppID'];
            $appSecret = $hotel['fxiaoke_APPSecret'];
            $permanentCode = $hotel['permanentCode'];
            $content2 = array(
                "content"   => $content,
                );
            if ($order['staff_hotel_id'] == 1 || $order['staff_hotel_id'] == 2) {
                $result = WPRequest::fxiaokesendMessage($appId,$appSecret,$permanentCode,$content2);
            } else if ($order['staff_hotel_id'] == 4) {
                $openUserId = WPRequest::idlist();
                $result = WPRequest::fxiaokedisendMessage($appId,$appSecret,$permanentCode,$content2,$openUserId);
            };
        };

        $payment= new OrderPayment;
        $payment->order_id=$post->order_id;
        $payment->money=$post->payment;
        $payment->time=$post->payment_time;
        $payment->remarks=$post->remarks;
        $payment->way=$post->payment_way;
        $payment->type=$post->payment_type;
        $payment->update_time=date('Y-m-d h:i:s',time());
        $payment->save();

        if($post->payment_type == 0){
            Order::model()->updateByPk($post->order_id,array('order_status' => 2));
        }else if($post->payment_type == 1){
            Order::model()->updateByPk($post->order_id,array('order_status' => 3));
        }else if($post->payment_type == 2){
            Order::model()->updateByPk($post->order_id,array('order_status' => 4));
        };
    }

    public function actionLibraryCasePaging()
    {
        $web = array();
        $case = array();
        $tab = array();

        if($_GET['page'] == 1){
            //构造网站列表
            $result = yii::app()->db->createCommand("select * from library_web");
            $web_data = $result->queryAll();
            foreach ($web_data as $key => $value) {
                $item = array();
                $item['id'] = $value['Web_ID'];
                $item['name'] = $value['Web_Name'];
                $web[]=$item;
            };

            //构造  case_list
            $result = yii::app()->db->createCommand("select * from library_web_case where Web_ID=".$_GET['web_id']." and Case_ID limit 0,17");
            $case_data = $result->queryAll();
            foreach ($case_data as $key1 => $value1) {
                $item = array();
                $item['id'] = $value1['Case_ID'];
                $item['name'] = $value1['Case_Name'];
                $item['company'] = $value1['Case_Company'];
                $item['cover_img'] = $value1['cover_img'];
                $item['page'] = 1;
                $case[] = $item;
            };

            //构造  tab
            $tab_data = LibraryWebTab::model()->findAll(array(
                    'condition' => 'Web_ID=:Web_ID',
                    'params' => array(
                            ':Web_ID' => $_GET['web_id']
                        )
                ));
            $t = array(
                    'ev' => array(),
                    'style' => array(),
                    'color' => array()
                );
            foreach ($tab_data as $key2 => $value2) {
                if($value2['Type_ID'] == 2){
                    $item = array();
                    $item['id'] = $value2['Tab_ID'];
                    $item['name'] = $value2['Tab_Name'];
                    $t['style'][] = $item;
                }else if($value2['Type_ID'] == 3){
                    $item = array();
                    $item['id'] = $value2['Tab_ID'];
                    $item['name'] = $value2['Tab_Name'];
                    $t['color'][] = $item;
                }else if($value2['Type_ID'] == 1){
                    $item = array();
                    $item['id'] = $value2['Tab_ID'];
                    $item['name'] = $value2['Tab_Name'];
                    $t['ev'][] = $item;
                };
            };
            $tab = $t;

            //查询case表总页数
            $result = yii::app()->db->createCommand("select count(*) from  library_web_case where Web_ID=".$_GET['web_id']);
            $row = $result->queryAll();
            $page = ceil($row[0]['count(*)']/16);

            $data = array(
                    'web' => $web,
                    'case' => $case,
                    'tab' => $tab,
                    'page' => $page
                );
            echo json_encode($data);
        }else{
            //构造  case_list
            $case = array();
            $start = ($_GET['page']-1)*16+403;
            $result = yii::app()->db->createCommand("select * from library_web_case where Web_ID=".$_GET['web_id']." and Case_ID limit ".$start.",16");
            $case_data = $result->queryAll();
            // echo  count($case_data);die;
            foreach ($case_data as $key1 => $value1) {
                $item = array();
                $item['id'] = $value1['Case_ID'];
                $item['name'] = $value1['Case_Name'];
                $item['company'] = $value1['Case_Company'];
                $item['cover_img'] = $value1['cover_img'];
                $item['page'] = $_GET['page'];
                $case[] = $item;
            };
            // echo $start."|".$end;
            echo json_encode($case);
        }
    }

    public function actionLibraryTabCase()
    {
        $post = json_decode(file_get_contents('php://input'));
        // $web_id = 1;
        // $tab = array(309,344);
        // $page = 2;

        $tab = array();
        $web = array();

        //构造 web
        $result = yii::app()->db->createCommand("select * from library_web");
        $web_data = $result->queryAll();
        foreach ($web_data as $key => $value) {
            $item = array();
            $item['id'] = $value['Web_ID'];
            $item['name'] = $value['Web_Name'];
            $web[]=$item;
        };

        //构造  tab
        $tab_data = LibraryWebTab::model()->findAll(array(
                'condition' => 'Web_ID=:Web_ID',
                'params' => array(
                        ':Web_ID' => $post->web_id
                    )
            ));
        $t = array(
                'ev' => array(),
                'style' => array(),
                'color' => array()
            );
        foreach ($tab_data as $key2 => $value2) {
            if($value2['Type_ID'] == 2){
                $item = array();
                $item['id'] = $value2['Tab_ID'];
                $item['name'] = $value2['Tab_Name'];
                $t['style'][] = $item;
            }else if($value2['Type_ID'] == 3){
                $item = array();
                $item['id'] = $value2['Tab_ID'];
                $item['name'] = $value2['Tab_Name'];
                $t['color'][] = $item;
            }else if($value2['Type_ID'] == 1){
                $item = array();
                $item['id'] = $value2['Tab_ID'];
                $item['name'] = $value2['Tab_Name'];
                $t['ev'][] = $item;
            };
        };
        $tab = $t;

        //构造 case
        $arr_case_list = array();
        $result = array();
        // foreach ($post->tab as $key => $value) {
        foreach ($post->tab as $key => $value) {
            $arr_case_list[$key]=array();
            $case = LibraryTabBind::model()->findAll(array(
                'condition' => 'Tab_ID=:Tab_ID && Resources_Type=:Resources_Type',
                'params' => array(
                        ':Tab_ID' => $value,
                        ':Resources_Type' => 1
                    )
            ));  
            foreach ($case as $key1 => $value1) {
                $arr_case_list[$key][] = $value1['Resources_ID'];
            };  
        };

        
        if(count($arr_case_list) == 1){
            $arr_case_list = $arr_case_list[0];
        }else{
            $result = $arr_case_list[0];
            for ($i=1; $i < count($arr_case_list); $i++) { 
                $result = array_intersect($result, $arr_case_list[$i]);
            };
            $arr_case_list = $result;
        };


        // echo json_encode($arr_case_list);
        $case = array();
        $start = 0 ;
        $end = 16;
        if($post->page != 1){
            $start =  ($post->page-1)*16+1;
            $end = $post->page*16;
        };
        // $start = ($post->page-1)*16;
        $id_list = "(";
        $i = 0;
        foreach ($arr_case_list as $key => $value) {
            if($i >= $start && $i <= $end){
                $id_list .= $value.",";    
            };
            $i++;
        };
        // echo $id_list;die;

        $id_list = rtrim($id_list, ",");
        $id_list .= ")";
        // print_r($arr_case_list);

        // echo $id_list;die;
        $result = yii::app()->db->createCommand("select * from library_web_case where Web_ID=".$post->web_id." and Case_ID in ".$id_list);
        $case_data = $result->queryAll();
        // echo  count($case_data);die;
        foreach ($case_data as $key1 => $value1) {
            $item = array();
            $item['id'] = $value1['Case_ID'];
            $item['name'] = $value1['Case_Name'];
            $item['company'] = $value1['Case_Company'];
            $item['cover_img'] = $value1['cover_img'];
            $item['page'] = $post->page;
            $case[] = $item;
        };
        // echo $start."|".$end;


        

        $data = array(
                'web' => $web,
                'case' => $case,
                'tab' => $tab
            );
        echo json_encode($data);
    }

    public function actionLibraryCaseList()
    {
        $result = yii::app()->db->createCommand("select * from library_web");
        $web = $result->queryAll();

        $web_data = array();
        foreach ($web as $key => $value) {
            $tem_web = array();
            $tem_web['id'] = $value['Web_ID'];
            $tem_web['name'] = $value['Web_Name'];
            $tem_web['case_list'] = array();

            //构造  case_list
            $result = yii::app()->db->createCommand("select * from library_web_case where Web_ID=".$value['Web_ID']);
            $case = $result->queryAll();
            foreach ($case as $key1 => $value1) {
                $item = array();
                $item['id'] = $value1['Case_ID'];
                $item['name'] = $value1['Case_Name'];
                $item['company'] = $value1['Case_Company'];
                $item['cover_img'] = $value1['cover_img'];
                $item['tab'] = array();

                // $result = yii::app()->db->createCommand("select * from library_tab_bind where Resources_ID=".$value1['Case_ID']." and Resources_Type=1");
                // $tab = $result->queryAll();

                // foreach ($tab as $key2 => $value2) {
                //     $tab[] = $value2['Tab_ID'];
                // };

                $tem_web['case_list'][] = $item;

            };

            //构造  tab
            $tab = LibraryWebTab::model()->findAll(array(
                    'condition' => 'Web_ID=:Web_ID',
                    'params' => array(
                            ':Web_ID' => $value['Web_ID']
                        )
                ));
            $t = array(
                    '1' => array(),
                    '2' => array(),
                    '3' => array()
                );
            foreach ($tab as $key2 => $value2) {
                if($value2['Type_ID'] == 2){
                    $item = array();
                    $item['id'] = $value2['Tab_ID'];
                    $item['name'] = $value2['Tab_Name'];
                    $t['2'][] = $item;
                }else if($value2['Type_ID'] == 3){
                    $item = array();
                    $item['id'] = $value2['Tab_ID'];
                    $item['name'] = $value2['Tab_Name'];
                    $t['3'][] = $item;
                }else if($value2['Type_ID'] == 1){
                    $item = array();
                    $item['id'] = $value2['Tab_ID'];
                    $item['name'] = $value2['Tab_Name'];
                    $t['1'][] = $item;
                };
            };
            $tem_web['tab'] = $t;

            $web_data[] = $tem_web;
        };

        echo json_encode($web_data);
    }


    

    public function actionLibraryCaseDetail()
    {
        $img = LibraryWebCaseImg::model()->findAll(array(
                'condition' => 'Case_ID=:Case_ID',
                'params' => array(
                        ':Case_ID' => $_GET['case_id']
                    )
            ));

        $case = LibraryWebCase::model()->findByPk($_GET['case_id']);

        $result = array(
                'case_name' => $case['Case_Name'],
                'img_list' => array()
            );

        
        $start = ($_GET['page'] - 1)*12;
        $end = $_GET['page']*12 - 1;

        foreach ($img as $key => $value) {
            if($key >= $start && $key <= $end){
                $item = array();
                $item['id'] = $value['Img_ID'];            
                $item['local_URL'] = "http://file.cike360.com".ltrim($value['local_URL'], ".");
                $item['local_URL_lg'] = "http://file.cike360.com".ltrim($value['local_URL_lg'], ".");
                $item['be_liked'] = $value['be_liked'];
                $result['img_list'][] = $item;
            };
        };

        $page = ceil(count($img)/12);

        //取    decoration tab
        $staff = Staff::model()->findByPk($_GET['token']);
        $tab = yii::app()->db->createCommand("select * from supplier_product_decoration_tap spdt where account_id in (0,".$staff['account_id'].")");
        $tab = $tab->queryAll();
        $tab_list = array();
        foreach ($tab as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['name']
                );
            $tab_list[] = $item;
        };

        //取正在执行的订单
        $result1 = yii::app()->db->createCommand("select * from `order` where designer_id=".$_GET['token']." or planner_id=".$_GET['token']);
        $result1 = $result1->queryAll();

        $order_doing = array();

        foreach ($result1 as $key => $value) {
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($value['order_date']);  //订单创建时间
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'to_date' => 0,
                );
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
            }
        }

        //当单排序
        $to_date = array();
        foreach ( $order_doing as $key => $row ){
            $to_date[] = $row ['to_date'];
        };
        array_multisort($to_date, SORT_ASC, $order_doing);


        //取  show_area
        $area = OrderShowArea::model()->findAll(array(
                'condition' => 'type=:type',
                'params' => array(
                        ':type' => 1
                    )
            ));
        $area_data = array();
        foreach ($area as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] =$value['name'];
            $area_data[] = $item;
        };

        //取   supplier
        $supplier = yii::app()->db->createCommand("select supplier.id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$staff['account_id']." and supplier.type_id=20");
        $supplier = $supplier->queryAll();
        $supplier_data = array();
        foreach ($supplier as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $supplier_data[] = $item;
        };

        //取 StaffFolder
        $Folder = LibraryStaffFolder::model()->findAll(array(
                'condition' => 'Staff_ID=:Staff_ID',
                'params' => array(
                        ':Staff_ID' => $_GET['token']
                    )
            ));
        $staff_folder = array();
        foreach ($Folder as $key => $value) {
            $item = array();
            $item['id'] = $value['Folder_ID'];
            $item['name'] = $value['Folder_Name'];

            $staff_folder[] = $item;
        };

        //构造最终数据
        $data = array(
                'case_name' => $case['Case_Name'],
                'img' => $result,
                'order' => $order_doing,
                'tab' => $tab_list,
                'page' => $page,
                'area_list' => $area_data,
                'supplier' => $supplier_data,
                'folder' => $staff_folder
            );

        echo json_encode($data);
    }

    public function actionLibraryNewProduct()
    {
        $post = json_decode(file_get_contents('php://input'));

        $staff = Staff::model()->findByPk($post->staff_id);

        $order_show=new OrderShowForm;
        $order_show->area_sort_batch_add($post->order_id,$post->area,$post->sort);
        $result = $order_show->new_product_insert($staff['account_id'],$post->supplier_id,$post->supplier_type_id,$post->decoration_tap,$post->name,$post->category,$post->price,$post->cost,$post->unit,$post->remark,$post->url,$post->order_id,$post->amount,$post->area,$post->sort);
        echo $result;
    }

    

    public function actionFolderBind()
    {
        $post = json_decode(file_get_contents('php://input'));

        // $data = array('Folder_ID'=> "1", 'Img_ID' => "99428"); 

        $test = LibraryFolderBind::model()->findAll(array(
                'condition' => 'Folder_ID=:Folder_ID && Img_ID=:Img_ID',
                'params' => array(
                        ':Folder_ID' => $post->Folder_ID,
                        ':Img_ID' => $post->Img_ID
                    )
            ));

        if(empty($test)){
            $admin = new LibraryFolderBind;
            $admin->Folder_ID=$post->Folder_ID;
            $admin->Img_ID=$post->Img_ID;
            $admin->update_time=date('Y-m-d h:i:s',time());
            $admin->save();

            $result = yii::app()->db->createCommand("update library_web_case_img set be_liked = be_liked+1 where Img_ID=".$post->Img_ID);
            $result = $result->execute();

            echo 1;
        }else{
            echo 0;
        };
    }

    public function actionNewFolder()
    {
        $post = json_decode(file_get_contents('php://input'));

        $admin = new LibraryStaffFolder;
        $admin->Folder_Name=$post->Folder_Name;
        $admin->Staff_ID=$post->token;
        $admin->update_time=date('Y-m-d h:i:s',time());
        $admin->save();
        $id = $data->attributes['Folder_ID'];

        echo $id;
    }

    public function actionFolderList()
    {
        $folder = LibraryStaffFolder::model()->findAll(array(
                'condition' => 'Staff_ID=:Staff_ID',
                'params' => array(
                        ':Staff_ID' => $_GET['token']
                    )
            ));
        // print_r($folder);die;

        $folderList = array();
        foreach ($folder as $key => $value) {
            // $img = LibraryFolderBind::model()->findAll(array(
            //         'condition' => 'Folder_ID=:Folder_ID',
            //         'params' => array(
            //                 ':Folder_ID' => $value['Folder_ID']
            //             )
            //     ));

            $result = yii::app()->db->createCommand("select fb.Img_ID,wci.local_URL from library_folder_bind fb ".
                " left join library_web_case_img wci on fb.Img_ID=wci.Img_ID ".
                " where fb.Folder_ID=".$value['Folder_ID']);
            $img = $result->queryAll();

            $item = array();
            $item['id'] = $value['Folder_ID'];
            $item['name'] = $value['Folder_Name'];
            $item['img_amount'] = count($img);

            if(!empty($img)){
                $t = explode('/', $img[0]['local_URL']);
                $item['poster'] = "http://file.cike360.com/imgs/".$t[count($t)-1];
            }else{
                $item['poster'] = "style/images/cbd.jpg";
            };

            $folderList[] = $item;
        };

        echo json_encode($folderList);
    }

    public function actionFolderDetail()
    {
        $result = yii::app()->db->createCommand("select fb.Img_ID,wci.local_URL from library_folder_bind fb ".
            " left join library_web_case_img wci on fb.Img_ID=wci.Img_ID ".
            " where fb.Folder_ID=".$_GET['folder_id']);
        $img = $result->queryAll();

        $img_list = array();
        foreach ($img as $key => $value) {
            $t = explode('/', $value['local_URL']);

            $item = array();
            $item['url'] = "http://file.cike360.com/imgs/".$t[count($t)-1];
            $item['id'] = $value['Img_ID'];

            $img_list[] = $item;
        };

        //取    decoration tab
        $staff = Staff::model()->findByPk($_GET['token']);
        $tab = yii::app()->db->createCommand("select * from supplier_product_decoration_tap spdt where account_id in (0,".$staff['account_id'].")");
        $tab = $tab->queryAll();
        $tab_list = array();
        foreach ($tab as $key => $value) {
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['name']
                );
            $tab_list[] = $item;
        };

        //取正在执行的订单
        $result1 = yii::app()->db->createCommand("select * from `order` where designer_id=".$_GET['token']." or planner_id=".$_GET['token']);
        $result1 = $result1->queryAll();

        $order_doing = array();

        foreach ($result1 as $key => $value) {
            $zero1=strtotime (date('y-m-d h:i:s')); //当前时间
            $zero2=strtotime ($value['order_date']);  //订单创建时间
            $item = array(
                    'id' => $value['id'],
                    'name' => $value['order_name'],
                    'order_date' => $value['order_date'],
                    'to_date' => 0,
                );
            if($zero2 >= $zero1){
                $item['to_date'] = ceil(($zero2-$zero1)/86400); //60s*60min*24h
                $order_doing[]=$item;
            }
        }

        //当单排序
        $to_date = array();
        foreach ( $order_doing as $key => $row ){
            $to_date[] = $row ['to_date'];
        };
        array_multisort($to_date, SORT_ASC, $order_doing);


        //取  show_area
        $area = OrderShowArea::model()->findAll(array(
                'condition' => 'type=:type',
                'params' => array(
                        ':type' => 1
                    )
            ));
        $area_data = array();
        foreach ($area as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] =$value['name'];
            $area_data[] = $item;
        };

        //取   supplier
        $supplier = yii::app()->db->createCommand("select supplier.id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$staff['account_id']." and supplier.type_id=20");
        $supplier = $supplier->queryAll();
        $supplier_data = array();
        foreach ($supplier as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $supplier_data[] = $item;
        };

        //取  folder_name
        $folder = LibraryStaffFolder::model()->findByPk($_GET['folder_id']);

        //构造最终数据
        $data = array(
                'img' => $img_list,
                'order' => $order_doing,
                'tab' => $tab_list,
                'area_list' => $area_data,
                'supplier' => $supplier_data,
                'folder_name' => $folder['Folder_Name']
            );

        echo json_encode($data);
    }

    public function actionGetFolderName()
    {
        $folder = LibraryStaffFolder::model()->findByPk($_GET['folder_id']);
        echo $folder['Folder_Name'];
    }

    public function actionEditFolder()
    {
        $post = json_decode(file_get_contents('php://input'));
        LibraryStaffFolder::model()->updateByPk($post->folder_id,array('Folder_Name'=>$post->folder_name));
    }

    public function actionDelFolder()
    {
        $post = json_decode(file_get_contents('php://input'));

        $folder = LibraryStaffFolder::model()->findByPk($post->folder_id);

        $admin = new LibraryStaffFolderDel;
        $admin->Folder_ID = $folder['Folder_ID'];
        $admin->Folder_Name = $folder['Folder_Name'];
        $admin->Staff_ID = $folder['Staff_ID'];
        $admin->update_time = date('Y-m-d h:i:s',time());
        $admin->save();

        LibraryStaffFolder::model()->deleteByPk($post->folder_id);
    }

    public function actionNew_company()
    {
        // $post = json_decode(file_get_contents('php://input'));
        // echo $this->regist_company($post->name,$post->place);

        $name = "ceshi";
        $place = "ceshi222";
        echo $this->regist_company($name,$place);
    }

    public function actionNew_staff()
    {
        $post = json_decode(file_get_contents('php://input'));
        echo $this->regist_staff($post->name,$post->company_id,$post->phone,$post->code,$post->password);

        // $name="ceshi3";
        // $company_id=31;
        // $phone=13333333304;
        // $code = 123456;
        // $password = 88888888;
        // echo $this->regist_staff($name,$company_id,$phone,$code,$password);

    }

    public function actionNew_independent_designer()
    {
        $post = json_decode(file_get_contents('php://input'));
        $company_id = $this->regist_company($post->name,$post->place);
        echo $this->regist_staff($post->name,$company_id,$post->phone,$post->code,$post->password);
    }

    public function regist_company($name,$place)
    {
        // 1、新增公司
        $admin=new StaffCompany;
        // $admin->name=$post->name;
        // $admin->place=$post->place;
        $admin->name=$name;
        $admin->place=$place;
        $admin->update_time=date('Y-m-d h:i:s',time());
        $admin->save();
        $account_id = $admin->attributes['id'];

        // 2、新增门店
        $first_hotel_id="";
        $admin=new StaffHotel;
        // $admin->name=$post->name;
        $admin->name=$name;
        $admin->account_id=$account_id;
        $admin->update_time=date('y-m-d h:i:s',time());
        $admin->save();
        $hotel_id = $admin->attributes['id'];
        $first_hotel_id=$hotel_id;



        // 4、把所有公共service_person，设为supplier
        $service_person = ServicePerson::model()->findAll(array(
                'condition' => 'status=:status',
                'params' => array(
                        ':status' => 1
                    )
            ));
        foreach ($service_person as $key => $value) {
            $admin=new Supplier;
            $admin->account_id=$account_id;
            $admin->type_id=$value['service_type'];
            $admin->staff_id=$value['staff_id'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $supplier_id = $admin->attributes['id'];


            // 5、复制service_product
            $service_person_product = ServiceProduct::model()->findAll(array(
                    'condition' => 'service_person_id=:spi',
                    'params' => array(
                            ':spi' => $value['id']
                        )
                ));
            foreach ($service_person_product as $key1 => $value1) {
                $admin=new SupplierProduct;
                $admin->account_id=$account_id;
                $admin->supplier_id=$supplier_id;
                $admin->service_product_id=$value1['id'];
                $admin->supplier_type_id=$value1['service_type'];
                $admin->dish_type=0;
                $admin->decoration_tap=0;
                $admin->standard_type=0;
                $admin->name=$value1['product_name'];
                $admin->category=2;
                $admin->unit_price=$value1['price'];
                $admin->unit_cost=$value1['cost'];
                $admin->unit=$value1['unit'];
                $admin->service_charge_ratio=0;
                $admin->ref_pic_url=$value1['ref_pic_url'];
                $admin->description=$value1['description'];
                $admin->product_show=$value1['product_show'];
                $admin->update_time=date('y-m-d h:i:s',time());
                $admin->save();
            };
        };


        // 6、复制 公司2的 supplier_product
        $tar_product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => 2
                    )
            ));

        
        foreach ($tar_product as $key => $value) {
            $admin=new SupplierProduct;
            $admin->ori_id=$value['id'];
            $admin->account_id=$account_id;
            $admin->supplier_id=$value['supplier_id'];
            $admin->service_product_id=$value['service_product_id'];
            $admin->supplier_type_id=$value['supplier_type_id'];
            $admin->dish_type=$value['dish_type'];
            $admin->decoration_tap=$value['decoration_tap'];
            $admin->standard_type=$value['standard_type'];
            $admin->name=$value['name'];
            $admin->category=$value['category'];
            $admin->unit_price=$value['unit_price'];
            $admin->unit_cost=$value['unit_cost'];
            $admin->unit=$value['unit'];
            $admin->service_charge_ratio=$value['service_charge_ratio'];
            $admin->ref_pic_url=$value['ref_pic_url'];
            $admin->description=$value['description'];
            $admin->product_show=$value['product_show'];
            $admin->update_time=date('y-m-d h:i:s',time());
            $admin->save();
            $supplier_product_id = $admin->attributes['id'];
        }

        // 7、为新增的第一个门店，复制 公司2（门店8的） wedding_set 
        // $tar_wedding_set = Wedding_set::model()->findAll(array(
        //         'condition' => 'staff_hotel_id=:staff_hotel_id',
        //         'params' => array(
        //                 ':staff_hotel_id' => 8
        //             )
        //     ));

        // $ws_shift = array(); //存储 原product_id 与 新product_id 的对应关系

        // foreach ($tar_wedding_set as $key => $value) {
        //     if($value['set_show'] == 1){
        //         // (1)复制一个  Wedding_set
        //         $admin=new Wedding_set;
        //         $admin->ori_id=$value['id'];
        //         $admin->staff_hotel_id=$first_hotel_id;
        //         $admin->name=$value['name'];
        //         $admin->category=$value['category'];
        //         $admin->final_price=$value['final_price'];
        //         $admin->feast_discount=$value['feast_discount'];
        //         $admin->other_discount=$value['other_discount'];
        //         $admin->theme_id=$value['theme_id'];
        //         $admin->product_list=$value['product_list'];
        //         $admin->set_show=$value['set_show'];
        //         $admin->update_time=date('y-m-d h:i:s',time());
        //         $admin->save();
        //         $wedding_set_id = $admin->attributes['id'];


        //         // (2)复制  Wedding_set  对应的  case
        //         $case = CaseInfo::model()->find(array(
        //                 'condition' => 'CI_Type=:CI_Type && CT_ID=:CT_ID',
        //                 'params' => array(
        //                         ':CI_Type' => 5,
        //                         ':CT_ID' => $value['id']
        //                     )
        //             ));
        //         $admin=new CaseInfo;
        //         $admin->CI_Name=$case['CI_Name'];
        //         $admin->CI_Pic=$case['CI_Pic'];
        //         $admin->CI_CreateTime=date('y-m-d h:i:s',time());
        //         $admin->CI_Show=$case['CI_Show'];
        //         $admin->CI_Type=$case['CI_Type'];
        //         $admin->CT_ID=$wedding_set_id;
        //         $admin->save();
        //         $case_id = $admin->attributes['CI_ID'];


        //         // (3)插入  case_bind
        //         $admin=new CaseBind;
        //         $admin->CB_Type=1;
        //         $admin->TypeID=$account_id;
        //         $admin->CI_ID=$case_id;
        //         $admin->save();


        //         // (4)复制  case_resource
        //         $case_resources = CaseResources::model()->findAll(array(
        //                 'condition' => 'CI_ID=:CI_ID',
        //                 'params' => array(
        //                         ':CI_ID' => $case['CI_ID']
        //                     )
        //             ));
        //         foreach ($case_resources as $key1 => $value1) {
        //             if($value1['CR_Show'] == 1){
        //                 $admin=new CaseResources;
        //                 $admin->CI_ID=$case_id;
        //                 $admin->CR_Type=$value1['CR_Type'];
        //                 $admin->CR_CreateTime=date('y-m-d h:i:s',time());
        //                 $admin->CR_Show=$value1['CR_Show'];
        //                 $admin->save();
        //             };
        //         };
        //     };
        // };

        return $account_id;
    }

    public function regist_staff($name,$company_id,$phone,$code,$password)
    {

        $tar_order_id = 1148;

        $result_data = array(
                'status' => 0,
                'token' => 0
            );
        /********验证code*********/
        $message_code = MessageCode::model()->findAll(array(
                'condition' => 'phone=:phone',
                'params' => array(
                        // ':phone' => $post->phone
                        ':phone' => $phone
                    )
            ));

        $t = 0;
        foreach ($message_code as $key => $value) {
            // if($post->code == $value['code']){
            if($code == $value['code']){
                $t=1;
            };
        };

        /********判断code是否正确*********/
        if($t == 0){
            $result['status'] = "failed";
        }else{
            //1、如果手机号已注册，则更改所在公司
            $staff=Staff::model()->find(array(
                    'condition' => 'telephone=:telephone',
                    'params' => array(
                            // ':telephone' => $post->phone
                            ':telephone' => $phone
                        )
                ));
            $t = 0;
            $staff_id = 0;
            if(!empty($staff)){
                $t=1;
                $staff_id = $staff['id'];
                // Staff::model()->updateByPk($staff['id'],array('account_id'=>$post->company_id));
                Staff::model()->updateByPk($staff['id'],array('account_id'=>$company_id));
                $result_data['status'] = "success";
                $result_data['token'] = $staff_id;
            }else {
                //2、手机号未注册，新增一个员工
                $admin=new Staff;
                // $admin->name=$post->name;
                $admin->name=$name;
                // $admin->account_id=$post->company_id;
                $admin->account_id=$company_id;
                // $admin->telephone=$post->phone;
                $admin->telephone=$phone;
                $admin->department_list="2,3";
                // $admin->password=$post->password;
                $admin->password=$password;
                $admin->update_time=date('y-m-d h:i:s',time());
                $admin->save();
                $staff_id = $admin->attributes['id'];

                //3、为新员工复制  一个订单
                $tar_order = Order::model()->findByPk($tar_order_id);
                $tar_order_product = OrderProduct::model()->findAll(array(
                        'condition' => 'order_id=:order_id',
                        'params' => array(
                                ':order_id' => $tar_order_id
                            )
                    ));
                $tar_order_set = OrderSet::model()->findAll(array(
                        'condition' => 'order_id=:order_id',
                        'params' => array(
                                ':order_id' => $tar_order_id
                            )
                    ));

                //新增 Order
                $admin=new Order;
                // $admin->account_id=$post->company_id;
                $admin->account_id=$company_id;
                $admin->designer_id=$staff_id;
                $admin->planner_id=$staff_id;
                $admin->adder_id=$staff_id;
                $admin->staff_hotel_id=$tar_order['staff_hotel_id'];
                $admin->order_name=$tar_order['order_name'];
                $admin->order_type=$tar_order['order_type'];
                $admin->order_date=date("Y-m-d",strtotime("+3 month"));
                $admin->end_time=$tar_order['end_time'];
                $admin->order_status=$tar_order['order_status'];
                $admin->other_discount=$tar_order['other_discount'];
                $admin->discount_range=$tar_order['discount_range'];
                $admin->feast_discount=$tar_order['feast_discount'];
                $admin->update_time=date('y-m-d h:i:s',time());
                $admin->save();
                $order_id = $admin->attributes['id'];

                //新增 OrderSet
                $os_shift = array();
                foreach ($tar_order_set as $key_tos => $value_tos) {
                    // $tem_ws_id = "";
                    // foreach ($ws_shift as $key_wss => $value_wss) {
                    //     if($value_tos['wedding_set_id'] == $value_wss['tar_id']){
                    //         $tem_ws_id = $value_wss['id'];
                    //     };
                    // };
                    $staff_hotel = StaffHotel::model()->find(array(
                            'condition' => 'account_id=:account_id',
                            'params' => array(
                                    // ':account_id' => $post->company_id
                                    ':account_id' => $company_id
                                )
                        ));
                    $wedding_set = Wedding_set::model()->find(array(
                            'condition' => 'ori_id=:ori_id && staff_hotel_id=:staff_hotel_id',
                            'params' => array(
                                    ':ori_id' => $value_tos['wedding_set_id'],
                                    ':staff_hotel_id' => $staff_hotel['id']
                                )
                        ));
                    if(!empty($wedding_set)){
                        $admin=new OrderSet;
                        // $admin->order_id=$post->company_id;
                        $admin->order_id=$company_id;
                        $admin->wedding_set_id=$wedding_set['id'];
                        $admin->amount=$value_tos['amount'];
                        $admin->remark=$value_tos['remark'];
                        $admin->actual_service_ratio=$value_tos['actual_service_ratio'];
                        $admin->order_product_list=$value_tos['order_product_list'];
                        $admin->final_price=$value_tos['final_price'];
                        $admin->update_time=date('y-m-d h:i:s',time());
                        $admin->save();
                        $order_set_id = $admin->attributes['id'];

                        $item = array();
                        $item['tar_id'] = $value_tos['id'];
                        $item['id'] = $order_set_id;
                        $os_shift[] = $item;
                    }

                        
                };
           

                //新增 OrderProduct
                foreach ($tar_order_product as $key1 => $value1) {
                    //转换product_id
                    // $tem_product_id = "";
                    // foreach ($sp_shift as $key2 => $value2) {
                    //     if($value1['product_id'] == $value2['tar_id']){
                    //         $tem_product_id = $value2['id'];
                    //     };
                    // };
                    $supplier_product = SupplierProduct::model()->find(array(
                            'condition' => 'ori_id=:ori_id && account_id=:account_id',
                            'params' => array(
                                    ':ori_id' => $value1['product_id'],
                                    // ':account_id' => $post->company_id
                                    ':account_id' => $company_id
                                )
                        ));
                    //转换order_set_id
                    $tem_order_set_id = 0;
                    foreach ($os_shift as $key3 => $value3) {
                        if($value1['order_set_id'] != 0){
                            if($value1['order_set_id'] == $value3['tar_id']){
                                $tem_order_set_id = $value3['id'];
                            };
                        };   
                    };

                    $admin=new OrderProduct;
                    // $admin->account_id=$post->company_id;
                    $admin->account_id=$company_id;
                    $admin->order_id=$order_id;
                    $admin->product_type=$value1['product_type'];
                    $admin->product_id=$supplier_product['id'];
                    $admin->order_set_id=$tem_order_set_id;
                    $admin->sort=$value1['sort'];
                    $admin->actual_price=$value1['actual_price'];
                    $admin->unit=$value1['unit'];
                    $admin->actual_unit_cost=$value1['actual_unit_cost'];
                    $admin->actual_service_ratio=$value1['actual_service_ratio'];
                    $admin->remark=$value1['remark'];
                    $admin->update_time=date('y-m-d h:i:s',time());
                    $admin->save();
                };

                $result_data['status'] = "success";
                $result_data['token'] = $staff_id;
            };
        };
        return json_encode($result_data);
    }

    public function actionDelChannel()
    {
        $post = json_decode(file_get_contents('php://input'));
        SupplierProduct::model()->updateByPk($post->tuidan_id,array('product_show'=>0));
    }

    public function actionDelGd()
    {
        $post = json_decode(file_get_contents('php://input'));
        OrderMerchandiser::model()->deleteByPk($post->gd_id);
    }

    public function actionDelCashier()
    {
        $post = json_decode(file_get_contents('php://input'));
        OrderPayment::model()->deleteByPk($post->payment_id);
    }

    public function actionNew_supplier()
    {
        $post = json_decode(file_get_contents('php://input'));

        $staff = Staff::model()->findByPk($post->token);

        $supplier_staff = Staff::model()->find(array(
                'condition' => 'phone=:phone',
                'params' => array(
                        ':phone' => $post->supplier_phone
                    )
            ));

        $supplier_id = 0;

        if(!empty($supplier_staff)){
            $arr = array(
                    'account_id' => $supplier_staff['account_id'],
                    'type_id' => $post->type_id,
                    'staff_id' => $supplier_staff['id']
                );
            $supplier_id = $this->add_supplier($arr);
        }else{
            $arr_staff = array(
                    'account_id' => $staff['account_id'],
                    'staff_name' => $post->supplier_name,
                    'telephone' => $post->supplier_phone,
                    'department_list' => "[4]",
                    'password' => '88888888'
                );

            $staff_id = $this->add_staff($arr_staff);

            $arr_supplier = array(
                    'account_id' => $staff['account_id'],
                    'type_id' => $post->type_id,
                    'staff_id' => $staff_id
                );
            $supplier_id = $this->add_supplier($arr);
        };
        echo $supplier_id;
    }

    public function actionNew_tap()
    {
        $post = json_decode(file_get_contents('php://input'));

        $staff = Staff::model()->findByPk($post->token);

        $tap = SupplierProductDecorationTap::model()->findAll(array(
                'order' => 'sort DESC',
            ));

        $model = new SupplierProductDecorationTap;
        $model->account_id = $staff['account_id'];
        $model->name = $post->tap_name;
        $model->sort = $tap[0]['sort']+1;
        $model->pic = "/upload/daojushoutu220160519141822.jpg";
        $model->update_time = date('y-m-d h:i:s',time());
        $model->save();
        $id = $model->attributes['id'];

        echo $id;
    }

    public function add_staff($arr)
    {
        $model = new Staff; 
        $model->account_id      = $arr['account_id'];
        $model->name            = $arr['staff_name'];
        $model->telephone       = $arr['telephone'];
        $model->department_list = $arr['department_list'];
        $model->update_time     = date('y-m-d h:i:s',time());
        $model->password        = $arr['password'];
        $model->save();
        $id = $model->attributes['id'];
        return $id;
    }

    public function add_supplier($arr)
    {
        $model=new Supplier;
        $model->account_id = $arr['account_id'];
        $model->type_id = $arr['type_id'];
        $model->staff_id = $arr['staff_id'];
        $model->update_time = date('y-m-d h:i:s',time());
        $model->save();
        $id = $model->attributes['id'];
        return $id;
    }

    public function actionSearch_company()
    {
        $result = yii::app()->db->createCommand("select * from staff_company where name like '%" .$_GET['name']. "%'");
        $result = $result->queryAll();

        $company = array();
        foreach ($result as $key => $value) {
            $item = array();
            $item['id'] = $value['id'];
            $item['name'] = $value['name'];
            $item['place'] = $value['place'];
            $company[] = $item;
        };

        echo json_encode($company);

        // $sql = "select * from topics where subject like '%" .$subject. "%'";
        // $result = mysql_query($sql);
    }

    public function actionSearch_company_number()
    {
        $company = StaffCompany::model()->findAll();

        echo count($company);
    }
    
    public function array_remove(&$arr,$offset) 
    { 
        array_splice($arr, $offset, 1); 
    } 

    public function actionCeshi()
    {   
        
        print_r($result);
        
    }




























}
