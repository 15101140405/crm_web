<?php

include_once('../library/WPRequest.php');
include_once('../library/taobao-sdk-PHP-auto_1455552377940-20160505/TopSdk.php');
// include_once('../library/taobao-sdk-PHP-auto_1455552377940-20160505/top/TopClient.php');
// include_once('../library/taobao-sdk-PHP-auto_1455552377940-20160505/top/request/AlibabaAliqinFcSmsNumSendRequest.php');

class BackgroundController extends InitController
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

    public function actionRegister_pro()
    {
        $telephone = $_POST['telephone'];
        // $telephone = "18611323194";

        $url = "http://localhost:8080/crm_web_new/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";

        $staff = Staff::model()->find(array(
            "condition" => "telephone = :telephone",
            "params"    => array(
                ":telephone" => $_POST['telephone']
                )
            ));
        if (empty($staff)) {
            echo "not exist";
        } elseif (!empty($staff['password'])){
            echo "registered";
        } elseif (isset($_POST['password'])) {
            if ($_POST['password'] == $_SESSION['code']) {
                Staff::model()->updateByPk( $staff['id'] ,array('password'=>$_POST['password']));
                echo "success";
            } else {
                echo "errow";
            }
            
        } else {
            // $data = json_encode(array(
            //     'telephone' => $_POST['telephone'],
            // ), JSON_UNESCAPED_UNICODE);
            $data = array('telephone' => $telephone, );
            WPRequest::post($url, $data);
        }

        // $result = WPRequest::post($url, $data);
        // echo "result:".$result;

       
    }

    public function actionLogin()
    {
        $this->render("login");
    }

    public function actionRegist()
    {
        $this->render("regist");
    }

    public function actionLogin_pro()
    {

        $staff = Staff::model()->find(array(

            "condition" => "telephone = :telephone",
            "params"    => array(
                ":telephone" => $_POST['telephone']
                )
            ));
        if (empty($staff)) {
            echo "not exist";
        }else{
            if($staff['password'] == $_POST['password']){
                $cookie = new CHttpCookie('userid',$staff['id']);
                $cookie->expire = time()+60*60*24*30*12*100;  //有限期100年
                Yii::app()->request->cookies['userid']=$cookie;

                $cookie = new CHttpCookie('account_id',$staff['account_id']);
                Yii::app()->request->cookies['account_id']=$cookie;  

                echo "success";
            }else{
                echo "password error";
            }
        }
            
        
    }

    public function actionIndex()
    {
        $url = "http://file.cike360.com";
        if($_GET['CI_Type'] == 2 || $_GET['CI_Type'] == 5){
            $staff_id = $_COOKIE['userid'];
            $result = yii::app()->db->createCommand("select * from case_info where ".

                "( CI_ID in ( select CI_ID from case_bind where CB_type=1 and TypeID in ".
                    "(select account_id from staff where id=".$staff_id.") ) ".

                " or CI_ID in ( select CI_ID from case_bind where CB_type=2 and TypeID in ".
                "(select hotel_list from staff where id=".$staff_id.") ) ".
                " or CI_ID in ( select CI_ID from case_bind where CB_type=3 and TypeID=".$staff_id." ))  ".
                " and CI_Show=1 order by CI_Sort Desc");
            $list = $result->queryAll();
            foreach($list as  $key => $val){
                if(!$this->startwith($val["CI_Pic"],"http://")&&!$this->startwith($val["CI_Pic"],"https://")){
                    $list[$key]["CI_Pic"]=$url.$val["CI_Pic"];
                };
            };
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id']
                        )
                ));
            /*print_r($tap);die;*/
            $this->render("index",array(
                    'case_data' => $list,
                    'tap' => $tap,
                ));
        }else if($_GET['CI_Type'] == 7){
            $product = SupplierProduct::model()->findAll(array(
                    'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':standard_type' => 0,
                            ':supplier_type_id' => 20, 

                        )
                ));
            foreach($product as  $key => $val){
                $product[$key]["ref_pic_url"]=$url.$val["ref_pic_url"];
            };
            $tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id']
                        )
                ));
            /*print_r($product);die;*/
            $this->render("index",array(
                    'case_data' => $product,
                    'tap' => $tap,
                ));
        };   
    }

    public function actionUpload_case()
    {
        $this->render('upload_case');
    }

    public function actionEdit_case()
    {
        $url = "http://file.cike360.com";

        //取资源信息
        $data = CaseResources::model()->findAll(array(
                'condition' => 'CI_ID=:CI_ID',
                'params' => array(
                        ':CI_ID' => $_GET['ci_id'],
                    ),
                'order' => 'CR_Sort',
            ));
        $resources = array();
        foreach ($data as $key => $value) {
            $t = explode('.', $value['CR_Path']);
            $result = yii::app()->db->createCommand("select case_resources_product.id as bind_id,name,unit,unit_price from case_resources_product left join supplier_product on supplier_product_id=supplier_product.id where case_resources_product.CR_ID=".$value['CR_ID']);
            $result = $result->queryAll();
            $item = array();
            $item['product'] = $result;
            $item['CR_Path'] = $url.$t[0].'_sm.'.$t[1];
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic = $url.$t[0].'_sm.'.$t[1];

        //取场布产品信息
        $product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0,
                        ':supplier_type_id' => 20, 

                    )
            ));
        $tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id']
                    )
            ));
        /*print_r($product);die;*/
        $this->render("edit_case",array(
                'pic' => $Pic,
                'resources' => $resources,
                'case' => $case,
                'case_data' => $product,
                'tap' => $tap,
            ));
    }

    public function actionUpload_set1()
    {
        $account_id = $_COOKIE['account_id'];

        $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
            "condition" => "account_id = :account_id",
            "params"    => array(
                ":account_id" => $account_id,
                )));
        $supplier_product = SupplierProduct::model()->findAll(array(
            'condition' => 'account_id=:account_id && standard_type=:standard_type',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0
                    )));
        /*print_r($decoration_tap);die;*/
        $this -> render("upload_set1",array(
            'decoration_tap' => $decoration_tap,
            'supplier_product' => $supplier_product,
            ));
    }

    public function actionUpload_set2()
    {
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $this->render("upload_set2",array(
                'hotel' => $hotel,
            ));
    }

    public function actionUpload_product()
    {
        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=20");
        $supplier = $result->queryAll();
        $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        /*print_r($supplier);die;*/
        $this->render("upload_product",array(
                'supplier' => $supplier,
                'decoration_tap' => $decoration_tap,
                'supplier_type' => $supplier_type,
            ));
    }

    function startwith($str,$pattern) {
        if(strpos($str,$pattern) === 0)
              return true;
        else
              return false;
    }

    public function actionEdit_product()
    {
        //取产品数据
        $product = SupplierProduct::model()->findByPk($_GET['product_id']);

        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=20");
        $supplier = $result->queryAll();
        $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $this->render('edit_product',array(
                'product' => $product,
                'supplier' => $supplier,
                'decoration_tap' => $decoration_tap,
                'supplier_type' => $supplier_type,
            ));
    }

    public function actionCase_upload()
    {
        /*$_POST['CI_Name'] = 333;
        $_POST['CI_Pic'] = 333;
        $_POST['CI_Remarks'] = 333;*/

        $data = new CaseInfo;
        $data ->CI_Name = $_POST['CI_Name'];
        $data ->CI_Place = "";
        $data ->CI_Pic = $_POST['CI_Pic'];
        // $data ->CI_Time = $_POST['CI_Time'];
        $data ->CI_Sort = 1;
        $data ->CI_Show = $_POST['CI_Show'];
        $data ->CI_Remarks = "";
        $data ->CI_Type = 1;
        $data->save();

        $CI_ID = $data->attributes['CI_ID'];
        
        $data = new CaseBind;
        $data ->CB_Type = 1;
        $data ->TypeID = $_POST['account_id'];
        $data ->CI_ID = $CI_ID;
        $data->save();


        //resource 处理
        //$_POST['resource']= '/upload/wutai0120160515094855.jpg,/upload/wutai0220160515094857.png,/upload/wutai0320160515094859.png,/upload/wutai0420160515094900.jpg,/upload/wutai0520160515094901.jpg,/upload/wutai0620160515094902.jpg,/upload/wutai0720160515094903.jpg,/upload/wutai0820160515094905.jpg';
        $t = explode(",",$_POST['case_resource']);
        $resources = array();
        foreach ($t as $key => $value) {
            $t1 = explode(".", $value);
            $item = array();
            if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                $item['Cr_Type'] = 1 ;
            }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                $item['Cr_Type'] = 2 ;
            }
            $item['Cr_Path'] = $value;
            $resources[]=$item;
        };
        /*print_r($resources);die;*/


        $i = 1;
        foreach ($resources as $key => $value) {
            $data = new CaseResources;
            $data ->CI_ID = $CI_ID;
            $data ->CR_Show = 1;
            $data ->CR_Type = $value['Cr_Type'];
            $data ->CR_Name = "";
            $data ->CR_Path = $value['Cr_Path'];
            $data ->CR_Remarks = "";
            $data ->CR_Sort = $i++;
            $data->save();
        };
    }

    public function actionDecoration_tap()
    {
        $data = new SupplierProductDecorationTap;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->name = $_POST['name'];
        $data ->pic = $_POST['pic'];
        $data ->update_time = $_POST['update_time'];
        $data->save();
    }

    public function actionProduct_upload()
    {
        $data = new SupplierProduct;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->supplier_id = (int)$_POST['supplier_id']; 
        $data ->supplier_type_id = $_POST['supplier_type_id'];
        $data ->decoration_tap = $_POST['decoration_tap'];
        $data ->standard_type = $_POST['standard_type'];
        $data ->name = $_POST['name'];
        $data ->category = $_POST['category'];
        $data ->unit_price = $_POST['unit_price'];
        $data ->unit_cost = $_POST['unit_cost'];
        $data ->unit = $_POST['unit'];
        $data ->service_charge_ratio = $_POST['service_charge_ratio'];
        $data ->ref_pic_url = $_POST['ref_pic_url'];
        $data ->description = $_POST['description'];
        $data ->update_time = $_POST['update_time'];
        $data->save();
    }

    public function actionProduct_edit()
    {
        SupplierProduct::model()->updateByPk($_POST['product_id'],array(
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'supplier_id' => $_POST['supplier_id'],
                'supplier_type_id' => $_POST['supplier_type_id'],
                'decoration_tap' => $_POST['decoration_tap'],
                'unit' => $_POST['unit'],
                'unit_price' => $_POST['unit_price'],
                'unit_cost' => $_POST['unit_cost'],
                'ref_pic_url' => $_POST['ref_pic_url'],
            ));
    }

    public function actionSupplier_add()
    {
        $data = new Staff;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->name = $_POST['name'];
        $data ->telephone = $_POST['telephone'];
        $data ->department_list = "[4]";
        $data ->update_time = $_POST['update_time'];
        $data ->save();
        //查找新增的员工ID
        $id = $data->attributes['id'];

        //新增供应商
        $data = new Supplier;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->type_id = 20;
        $data ->staff_id = $id;
        $data ->contract_url = "";
        $data ->update_time = $_POST['update_time'];
        $data ->save();
    }

    public function actionTap_add()
    {
        $data = new SupplierProductDecorationTap;
        $data ->account_id = $_POST['account_id'];
        $data ->name = $_POST['name'];
        $data ->pic = $_POST['pic'];
        $data ->update_time = $_POST['update_time'];
        $data ->save();
    }

    public function actionDel_resource()
    {
        CaseResources::model()->deleteByPk($_POST['CR_ID']); 
    }

    public function actionBind_product()
    {
        $data = new CaseResourcesProduct;
        $data ->CR_ID = $_POST['CR_ID'];
        $data ->supplier_product_id = $_POST['supplier_product_id'];
        $data ->update_time = date('y-m-d h:i:s',time());
        $data ->save();
    }

    public function actionDel_bind()
    {
        CaseResourcesProduct::model()->deleteByPk($_POST['bind_id']); 
    }

    public function actionCase_edit()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Name'=>$_POST['CI_Name'],'CI_Show'=>$_POST['CI_Show'],'CI_Pic'=>$_POST['CI_Pic']));
        if($_POST['case_resource'] != ""){
            $t = explode(",",$_POST['case_resource']);
            $resources = array();
            foreach ($t as $key => $value) {
                $t1 = explode(".", $value);
                $item = array();
                if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                    $item['Cr_Type'] = 1 ;
                }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                    $item['Cr_Type'] = 2 ;
                }
                $item['Cr_Path'] = $value;
                $resources[]=$item;
            };
            /*print_r($resources);die;*/
            $i = $_POST['CR_Sort']+1;
            foreach ($resources as $key => $value) {
                $data = new CaseResources;
                $data ->CI_ID = $_POST['CI_ID'];
                $data ->CR_Show = 1;
                $data ->CR_Type = $value['Cr_Type'];
                $data ->CR_Name = "";
                $data ->CR_Path = $value['Cr_Path'];
                $data ->CR_Remarks = "";
                $data ->CR_Sort = $i++;
                $data->save();
            };
        };
    }

    public function actionSet_upload()
    {
        $data = new Wedding_set;
        $data ->staff_hotel_id = $_POST['staff_hotel_id'];
        $data ->name = $_POST['CI_Name'];
        $data ->category = 2;
        $data ->final_price = $_POST['final_price'];
        $data ->feast_discount = 1;
        $data ->other_discount = 1;
        $data ->product_list = $_POST['product_list'];
        $data->save();
        $id = $data->attributes['id'];

        $data = new CaseInfo;
        $data ->CI_Name = $_POST['CI_Name'];
        $data ->CI_Pic = $_POST['CI_Pic'];
        $data ->CI_Show = 1;
        $data ->CI_Type = 5;
        $data ->CT_ID = $id;
        $data->save();
        $CI_ID = $data->attributes['CI_ID'];

        $data = new CaseBind;
        $data ->CB_Type = 1;
        $data ->TypeID = $_COOKIE['account_id'];
        $data ->CI_ID = $CI_ID;
        $data->save();
        // $id = $data->attributes['id'];

        

        //复制于Case_edit()，改Cr_Type为CR_Type    ////////////////
        $t = explode(",",$_POST['case_resource']);
        $resources = array();
        foreach ($t as $key => $value) {
            $t1 = explode(".", $value);
            $item = array();
            if($t1[1] == "jpg" || $t1[1] == "png" || $t1[1] == "jpeg" || $t1[1] == "JPEG" || $t1[1] == "gif" || $t1[1] == "bmp" ){
                $item['CR_Type'] = 1 ;
            }else if($t1[1] == "mp4" || $t1[1] == "avi" || $t1[1] == "flv" || $t1[1] == "mpeg" || $t1[1] == "mov" || $t1[1] == "wmv" || $t1[1] == "rm" || $t1[1] == "3gp"){
                $item['CR_Type'] = 2 ;
            }
            $item['CR_Path'] = $value;
            $resources[]=$item;
        }
        foreach ($resources as $key => $value) {
            $data = new CaseResources;
            $data ->CI_ID = $CI_ID;
            $data ->CR_Show = 1;
            $data ->CR_Type = $value['CR_Type'];
            $data ->CR_Path = $value['CR_Path'];
            $data->save();
        };
        $data->save();
    }
}
