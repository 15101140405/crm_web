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

        $url = "http://localhost/school/crm_web/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";

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
            if ($_POST['yzm'] == $_SESSION['code']) {
                Staff::model()->updateByPk( $staff['id'] ,array('password'=>$_POST['password']));
                echo "success";
            } else {
                echo "errow";
            }
            
        } else {
            echo "发送请求";
            // $data = json_encode(array(
            //     'telephone' => $_POST['telephone'],
            // ), JSON_UNESCAPED_UNICODE);
            $data = array('telephone' => $telephone, );
            $result = WPRequest::post($url, $data);
            Yii::app()->session['code'] = $result;

        }

        // $data = array('telephone' => $telephone, );
        // $result = WPRequest::post($url, $data);
        // echo "result:".$result;
        // print_r($_SESSION);

       
    }

    public function actionRegister_host_pro()
    {
        $url = "http://localhost/school/crm_web/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";

        $person_data = array();

        if($_POST['department'] == 11){
            $person_data['CI_Type'] = 6;
            $person_data['service_type'] = 3;
        };
        if($_POST['department'] == 12){
            $person_data['CI_Type'] = 13;
            $person_data['service_type'] = 4;
        };
        if($_POST['department'] == 13){
            $person_data['CI_Type'] = 14;
            $person_data['service_type'] = 5;
        };
        if($_POST['department'] == 14){
            $person_data['CI_Type'] = 15;
            $person_data['service_type'] = 6;
        };

        if (isset($_POST['password'])) {
            if ($_POST['yzm'] == $_SESSION['code']) {
                $staff = Staff::model()->find(array(
                        'condition' => 'telephone=:telephone',
                        'params' => array(
                                ':telephone' => $_POST['telephone']
                            )
                    ));
                $staff_id = "";
                if(empty($staff)){ //手机号未注册，新建一个staff
                    $data = new Staff;
                    $data ->name = $_POST['name'];
                    $data ->telephone = $_POST['telephone'];
                    $data ->department_list = "[".$_POST['department']."]";
                    $data ->password = $_POST['password'];
                    $data ->save();
                    $staff_id = $data->attributes['id'];
                }else{  //手机号已经注册，但不是主持人，修改staff的department_list
                    $staff['department_list']=rtrim($staff['department_list'], "]");
                    $staff['department_list']=ltrim($staff['department_list'], "[");
                    $t=explode(',',$staff['department_list']);
                    $department_list = "[";
                    foreach ($t as $key => $value) {
                        $department_list .= $value . ",";
                    };
                    $department_list .= $_POST['department']."]";
                    $staff = Staff::model()->find(array(
                        'condition' => 'telephone=:telephone',
                        'params' => array(
                            ':telephone' => $_POST['telephone']
                        )
                    ));
                    Staff::model()->updateByPk($staff['id'],array('department_list'=>$department_list,'password'=>$_POST['password'],'name'=>$_POST['name']));
                };
                $data = new CaseInfo;
                $data ->CI_Name = $_POST['name'];
                $data ->CI_Pic = "";
                $data ->CI_Sort = 1;
                $data ->CI_Show = 1;
                $data ->CI_Type = $person_data['CI_Type'];
                $data ->CT_ID = $staff_id;
                $data ->save();
                $CI_ID = $data->attributes['CI_ID'];

                $data = new ServicePerson;
                $data ->team_id = 2;
                $data ->name = $_POST['name'];
                $data ->gender = 1;
                $data ->avatar = "";
                $data ->telephone = $_POST['telephone'];
                $data ->update_time = date('y-m-d h:i:s',time());
                $data ->staff_id = $staff_id;
                $data ->service_type = $person_data['service_type'];
                $data ->save();

                $data = new CaseBind;
                $data ->CB_Type = 4;
                $data ->TypeID = 0;
                $data ->CI_ID = $CI_ID;
                $data ->save();

                $staff_company = StaffCompany::model()->findAll();

                foreach ($staff_company as $key => $value) {
                    $data = new Supplier;
                    $data ->account_id = $value['id'];
                    $data ->type_id = $person_data['service_type'];
                    $data ->staff_id = $staff_id;
                    $data ->save();
                }
                echo "success"; 
            } else {
                echo "errow"  ;
            };
        } else {
            $staff = Staff::model()->find(array(
                        'condition' => 'telephone=:telephone',
                        'params' => array(
                                ':telephone' => $_POST['telephone']
                            )
                    ));
            if(!empty($staff)){ //如果手机号已经注册
                if($staff['department_list'] != ""){
                    $staff['department_list']=rtrim($staff['department_list'], "]");
                    $staff['department_list']=ltrim($staff['department_list'], "[");
                    $t=explode(',',$staff['department_list']);
                    $i = 0;
                    if(!empty($t)){
                        foreach ($t as $key => $value) {
                            if($value == 11){$i++;};
                        };
                    };
                    if($i != 0){  //如果注册者已经是主持人
                        echo "该手机号已经注册！" ; 
                    }else{  //注册者还不是主持人
                        echo "验证码已发送到您的手机！";
                        $data = array('telephone' => $_POST['telephone'], );
                        $result = WPRequest::post($url, $data);
                        Yii::app()->session['code'] = $result;
                        //echo $_SESSION['code'];
                    };
                };
            }else{ // 如果手机号还未注册
                echo "您的手机未注册，验证码已发送到您的手机！";
                $data = array('telephone' => $telephone, );
                $result = WPRequest::post($url, $data);
                Yii::app()->session['code'] = $result;
                //echo $_SESSION['code'];
            };
        };
    }


    public function actionLogin()
    {
        $this->render("login");
    }

    public function actionRegist()
    {
        $this->render("regist");
    }

    public function actionRegist_host()
    {
        $this->render("regist_host");   
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

                $cookie = new CHttpCookie('department_list',$staff['department_list']);
                Yii::app()->request->cookies['department_list']=$cookie;  

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
                    $t = explode(".", $val['CI_Pic']);
                    if(isset($t[0]) && $t[1]){
                        $list[$key]["CI_Pic"]=$url.$t[0]."_sm.".$t[1];
                    }else{
                        $list[$key]["CI_Pic"]="images/cover.jpg";
                    }
                    
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
                    'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id && product_show=:product_show',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id'],
                            ':standard_type' => 0,
                            ':supplier_type_id' => 20, 
                            ':product_show' => 1,
                        )
                ));
            foreach($product as  $key => $val){
                $t = explode(".", $val['ref_pic_url']);
                if(isset($t[0]) && $t[1]){
                        $product[$key]["ref_pic_url"]=$url.$t[0]."_sm.".$t[1];
                    }else{
                        $product[$key]["ref_pic_url"]="images/cover.jpg";
                    }
                
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
        }else if($_GET['CI_Type'] == 6){
            /*$tap = SupplierProductDecorationTap::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            ':account_id' => $_COOKIE['account_id']
                        )
                ));*/
            $case = CaseInfo::model()->find(array(
                    'condition' => 'CI_Type=:CI_Type && CT_ID=:CT_ID',
                    'params' => array(
                            ':CI_Type' => 6,
                            ':CT_ID' => $_COOKIE['userid']
                        )
                ));
            $service_person = ServicePerson::model()->find(array(
                    'condition' => 'staff_id=:staff_id',
                    'params' => array(
                            ':staff_id' => $case['CT_ID'],
                        ),
                ));
            // print_r($case);die;
            $this->render('index',array(
                    /*'tap'=>$tap,*/
                    'case' => $case,
                    'service_person' => $service_person,
                ));
        }else if($_GET['CI_Type'] == 8){
            $criteria = new CDbCriteria;
            $criteria->addInCondition('supplier_type_id', array(8,9,23));
            $criteria->addCondition("account_id = :account_id && product_show=:product_show");    
            $criteria->params[':account_id']=$_COOKIE['account_id'];
            $criteria->params[':product_show']=1;
            $supplier_product = SupplierProduct::model()->findAll($criteria); 

            foreach ($supplier_product as $key => $value) {
                $t = explode(".", $value['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $supplier_product[$key]['ref_pic_url'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                };
            };

            $this->render("index",array(
                    'supplier_product' => $supplier_product,
                ));
        }else if($_GET['CI_Type'] == 9){
            $criteria = new CDbCriteria;
            $criteria->addCondition("account_id = :account_id && product_show=:product_show && supplier_type_id=:supplier_type_id");    
            $criteria->params[':account_id']=$_COOKIE['account_id'];
            $criteria->params[':product_show']=1;
            $criteria->params[':supplier_type_id']=2;
            $criteria->order = 'update_time DESC';
            $supplier_product = SupplierProduct::model()->findAll($criteria); 

            foreach ($supplier_product as $key => $value) {
                $t = explode(".", $value['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $supplier_product[$key]['ref_pic_url'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                };
            };

            $result = yii::app()->db->createCommand("select wedding_set.id as CT_ID,case_info.CI_ID as CI_ID,case_info.CI_Pic,wedding_set.`name`,wedding_set.final_price from wedding_set left join staff_hotel on staff_hotel_id=staff_hotel.id left join case_info on wedding_set.id=case_info.CT_ID where case_info.CI_Type in (9,11) and account_id=".$_COOKIE['account_id']." and category in (3,4) and CI_Show=1");
            $menu = $result->queryAll();
            foreach ($menu as $key => $value) {
                $t = explode(".", $value['CI_Pic']);
                if(isset($t[0]) && isset($t[1])){
                    $menu[$key]['CI_Pic'] = "http://file.cike360.com".$t[0].'_sm.'.$t[1];
                };
            };
            /*print_r($supplier_product);die;*/
            $this->render("index",array(
                    'supplier_product' => $supplier_product,
                    'menu' => $menu,
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
            if(isset($t[0]) && isset($t[1])){
                $item['CR_Path'] = $url.$t[0].'_sm.'.$t[1];    
            }else{
                $item['CR_Path'] = "images/cover.jpg";
            };
            
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];    
        }else{
            $Pic = "images/cover.jpg";
        };
        

        //取场布产品信息
        $product = SupplierProduct::model()->findAll(array(
                'condition' => 'account_id=:account_id && standard_type=:standard_type && supplier_type_id=:supplier_type_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0,
                        ':supplier_type_id' => 20, 

                    )
            ));
        foreach ($product as $key => $value) {
            $t = explode(".", $value['ref_pic_url']);
            if(isset($t[0]) && isset($t[1])){
                $product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];
            }else{
                $product[$key]['ref_pic_url'] = "images/cover.jpg";
            };
        };
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
        foreach ($supplier_product as $key => $value) {
            $t=explode('.', $value['ref_pic_url']);
            if(isset($t[0]) && isset($t[1])){
                $supplier_product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];    
            };
        };
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

    public function actionEdit_set1()
    {
        $account_id = $_COOKIE['account_id'];
        $decoration_tap = array();
        if(!isset($_GET['type'])){
            $decoration_tap = SupplierProductDecorationTap::model()->findAll(array(
                "condition" => "account_id = :account_id",
                "params"    => array(
                    ":account_id" => $account_id,
                    )));
        }else if($_GET['type'] == 'menu'){
            $decoration_tap = DishType::model()->findAll();
        };
            
        $supplier_product = SupplierProduct::model()->findAll(array(
            'condition' => 'account_id=:account_id && standard_type=:standard_type',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0
                    )));
        foreach ($supplier_product as $key => $value) {
            $t=explode('.', $value['ref_pic_url']);
            if(isset($t[0]) && isset($t[1])){
                $supplier_product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];    
            };
        };
        $product_list = array();
        $Wedding_set = Wedding_set::model()->findByPk($_GET['ct_id']);
        if($Wedding_set['product_list']!=""){
            $t = explode(",", $Wedding_set['product_list']);
            foreach ($t as $key => $value) {
                $item = array();
                $t1 = explode("|", $value);
                $item['product_id'] = $t1[0];
                $item['price'] = $t1[1];
                $item['amount'] = $t1[2];
                $item['cost'] = $t1[3];
                $product_list[]=$item;
            };
        };
        $this->render("edit_set1",array(
            'wedding_set' => $Wedding_set,
            'decoration_tap' => $decoration_tap,
            'supplier_product' => $supplier_product,
            'product_list' => $product_list,
            ));
    }

    public function actionEdit_set2()
    {
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $Wedding_set = Wedding_set::model()->findByPk($_GET['ct_id']);
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
            if(isset($t[0]) && isset($t[1])){
                $item['CR_Path'] = $url.$t[0].'_sm.'.$t[1];
            }else{
                $item['CR_Path'] = "images/cover.jpg";
            };
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];
        }else{
            $Pic = "images/cover.jpg";
        };
        

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

        $this->render("edit_set2",array(
                'hotel' => $hotel,
                'Wedding_set' => $Wedding_set,
                'pic' => $Pic,
                'resources' => $resources,
                'case' => $case,
                'case_data' => $product,
                'tap' => $tap,
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

    public function actionUpload_product_lss()
    {
        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id in (8,9,23)");
        $supplier = $result->queryAll();
        $supplier_type = SupplierType::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        /*print_r($supplier);die;*/
        $this->render("upload_product_lss",array(
                'supplier' => $supplier,
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
        /*$result = yii::app()->db->createCommand("select * from supplier_product left join supplier on supplier_id=supplier.id left join staff on supplier.staff_id=staff.id left join service_person on staff.id=service_person.staff_id where service_person.id=".$_GET['service_person_id']);
        $product = $result->queryAll();*/
        $product = ServiceProduct::model()->findAll(array(
                'condition' => 'service_person_id=:service_person_id && product_show=:product_show',
                'params' => array(
                        ':service_person_id' => $_GET['service_person_id'],
                        ':product_show' => 1,
                    ),
            ));
        /*print_r($product);die;*/
        $this->render('edit_product',array(
                'product' => $product,
            ));
    }

    public function actionEdit_product_detail()
    {
        if(!isset($_GET['service_product_id'])){
            $this->render('edit_product_detail');
        }else{
            $service_product = ServiceProduct::model()->findByPk($_GET['service_product_id']);
            $this->render('edit_product_detail',array(
                    'product' => $service_product
                ));
        };
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
        $data ->CI_Type = 2;
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
        $data ->dish_type = $_POST['dish_type'];
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
        $staff = Staff::model()->find(array(
                'condition' => 'telephone=:telephone',
                'params' => array(
                        ':telephone' => $_POST['telephone']
                    )
            ));
        $id="";
        if(empty($staff)){
            $data = new Staff;
            $data ->account_id = $_COOKIE['account_id'];
            $data ->name = $_POST['name'];
            $data ->telephone = $_POST['telephone'];
            $data ->department_list = "[4]";
            $data ->update_time = $_POST['update_time'];
            $data ->save();
            //查找新增的员工ID
            $id = $data->attributes['id'];
        }else{
            $id = $staff['id'];
        };  

        //新增供应商
        $data = new Supplier;
        $data ->account_id = $_COOKIE['account_id'];
        $data ->type_id = $_POST['supplier_type'];
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
        $data ->category = $_POST['category'];
        $data ->final_price = $_POST['final_price'];
        $data ->feast_discount = $_POST['feast_discount'];
        $data ->other_discount = $_POST['other_discount'];
        $data ->product_list = $_POST['product_list'];
        $data->save();
        $id = $data->attributes['id'];

        $data = new CaseInfo;
        $data ->CI_Name = $_POST['CI_Name'];
        $data ->CI_Pic = $_POST['CI_Pic'];
        $data ->CI_Show = 1;
        $data ->CI_Type = $_POST['CI_Type'];
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

    public function actionSet_edit()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Name'=>$_POST['CI_Name'],'CI_Show'=>$_POST['CI_Show'],'CI_Pic'=>$_POST['CI_Pic']));
        Wedding_set::model()->updateByPk($_POST['CT_ID'],array('staff_hotel_id'=>$_POST['staff_hotel_id'],'name'=>$_POST['CI_Name'],'final_price'=>$_POST['final_price'],'feast_discount'=>$_POST['feast_discount'],'product_list'=>$_POST['product_list']));
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

    public function actionEdit_host_video()
    {
        $url = "http://file.cike360.com";

        //取资源信息
        $data = CaseResources::model()->findAll(array(
                'condition' => 'CI_ID=:CI_ID && CR_Type=:CR_Type',
                'params' => array(
                        ':CI_ID' => $_GET['ci_id'],
                        ':CR_Type' => 2,
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
            if(isset($t[0]) && isset($t[1])){
                $item['CR_Path'] = $url.$t[0].'_sm.'.$t[1];
            }else{
                $item['CR_Path'] = "images/cover.jpg";
            };
            
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];
        }else{
            $Pic = "images/cover.jpg";
        };
        
        $Pic = $url.$t[0].'_sm.'.$t[1];

        //取场布产品信息
        /*$product = SupplierProduct::model()->findAll(array(
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
            ));*/
        /*print_r($product);die;*/
        $this->render("edit_host_video",array(
                'pic' => $Pic,
                'resources' => $resources,
                'case' => $case,
                /*'case_data' => $product,
                'tap' => $tap,*/
            ));
    }

    public function actionEdit_host_img()
    {
        $url = "http://file.cike360.com";

        //取资源信息
        $data = CaseResources::model()->findAll(array(
                'condition' => 'CI_ID=:CI_ID && CR_Type=:CR_Type',
                'params' => array(
                        ':CI_ID' => $_GET['ci_id'],
                        ':CR_Type' => 1,
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
            if(isset($t[0]) && isset($t[1])){
                $item['CR_Path'] = $url.$t[0].'_sm.'.$t[1];
            }else{
                $item['CR_Path'] = "images/cover.jpg";
            };
            $item['CR_ID'] = $value['CR_ID'];
            $item['CR_Sort'] = $value['CR_Sort'];
            $resources[] = $item;
        };  

        /*print_r($resources);die;*/

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];
        }else{
            $Pic = "images/cover.jpg";
        };

        //取场布产品信息
        /*$product = SupplierProduct::model()->findAll(array(
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
            ));*/
        /*print_r($product);die;*/
        $this->render("edit_host_img",array(
                'pic' => $Pic,
                'resources' => $resources,
                'case' => $case,
                /*'case_data' => $product,
                'tap' => $tap,*/
            ));
    }

    public function actionEdit_host_self_info()
    {
        $url = "http://file.cike360.com";

        //取案例信息
        $case = CaseInfo::model()->findByPk($_GET['ci_id']);
        /*print_r($case['CI_Pic']);die;*/
        $t= explode('.', $case['CI_Pic']);$Pic="";
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];    
        }else{
            $Pic = "images/cover.jpg";
        };
        $t= explode('.', $case['CI_Pic']);
        $Pic="";
        if(isset($t[0]) && isset($t[1])){
            $Pic = $url.$t[0].'_sm.'.$t[1];
        };

        $staff = Staff::model()->findByPk($case['CT_ID']);
        /*print_r($product);die;*/
        $this->render("edit_host_self_info",array(
                'pic' => $Pic,
                'case' => $case,
                'staff' => $staff,
            ));
    }

    public function actionHost_video_edit()
    {
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

    public function actionHost_self_info_edit()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Name'=>$_POST['CI_Name'],'CI_Pic'=>$_POST['CI_Pic']));
        $case = CaseInfo::model()->findByPk($_POST['CI_ID']);
        Staff::model()->updateByPk($case['CT_ID'],array('name' => $_POST['CI_Name'],'telephone'=>$_POST['phone']));
    }

    public function actionHost_product_edit()
    {
        //新建service_product，并返回service_product_id
        $data = new ServiceProduct;
        $data ->service_person_id = $_POST['service_person_id'];
        $data ->service_type = $_POST['service_type'];
        $data ->product_name = $_POST['product_name'];
        $data ->price = $_POST['price'];
        $data ->unit = $_POST['unit'];
        $data ->update_time = date('y-m-d h:i:s',time());
        $data ->description = $_POST['description'];
        $data ->save();

        $service_product_id = $data->attributes['id'];

        //给所有公司新增一个supplier_product
        

        $case = CaseInfo::model()->findByPk($_POST['CI_ID']);

        $company = StaffCompany::model()->findAll();
        foreach ($company as $key => $value) {
            
            $supplier_id = yii::app()->db->createCommand("select supplier.id as supplier_id from supplier left join service_person on supplier.staff_id=service_person.staff_id where supplier.account_id=".$value['id']." and service_person.id=".$_POST['service_person_id']);
            $supplier_id = $supplier_id->queryAll();

            $data = new SupplierProduct;
            $data ->account_id = $value['id'];
            $data ->supplier_id = $supplier_id[0]['supplier_id'];
            $data ->service_product_id = $service_product_id;
            $data ->supplier_type_id = $_POST['service_type'];
            $data ->decoration_tap = 0;
            $data ->standard_type = 0;
            $data ->name = $_POST['product_name'];
            $data ->category = 2;
            $data ->unit_price = $_POST['price']*2;
            $data ->unit_cost = $_POST['price'];
            $data ->unit = $_POST['unit'];
            $data ->service_charge_ratio = 0;
            $data ->ref_pic_url = $case['CI_Pic'];
            $data ->description = $_POST['description'];
            $data ->update_time = date('y-m-d h:i:s',time());
            $data ->save();
        };

    }

    public function actionEdit_supplier_product()
    {
        $supplier = array();
        if(!isset($_GET['type'])){
            $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=20");
            $supplier = $result->queryAll();
        }else if($_GET['type'] == "lss"){
            $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id in (8,9,23)");
            $supplier = $result->queryAll();
        }else if($_GET['type'] == "dish"){
            $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=2");
            $supplier = $result->queryAll();
        }
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
        $product = SupplierProduct::model()->findByPk($_GET['product_id']);
        $t = explode(".", $product['ref_pic_url']);
        $picture="";
        if(isset($t[0]) && isset($t[1])){
            $picture = "http://file.cike360.com".$t[0]."_sm.".$t[1];
        }else{
            $picture = "images/cover.jpg";
        };  
        /*print_r($supplier);die;*/
        $this->render("edit_supplier_product",array(
                'picture' => $picture,
                'product' => $product,
                'supplier' => $supplier,
                'decoration_tap' => $decoration_tap,
                'supplier_type' => $supplier_type,
            ));
    }

    public function actionDel_case()
    {
        CaseInfo::model()->updateByPk($_POST['CI_ID'],array('CI_Show'=>0));
        if($_POST['CI_Type'] == 5 || $_POST['CI_Type'] == 9 || $_POST['CI_Type'] == 11 || $_POST['CI_Type'] == 12){
            $case = CaseInfo::model()->findByPk($_POST['CI_ID']);
            Wedding_set::model()->updateByPk($case['CT_ID'],array('set_show'=>0));
        };
    }

    public function actionDel_product()
    {
        SupplierProduct::model()->updateByPk($_POST['product_id'],array('product_show'=>0));
    }

    public function actionUpload_dish()
    {
        $dish_type = DishType::model()->findAll();
        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.`name`,supplier_type.`name` as supplier_type_name from supplier left join staff on staff_id=staff.id left join supplier_type on supplier.type_id=supplier_type.id where supplier.account_id=".$_COOKIE['account_id']." and supplier.type_id=2");
        $supplier = $result->queryAll();
        $this->render("upload_dish",array(
                'dish_type' => $dish_type,
                'supplier' => $supplier,
            ));
    }

    public function actionUpload_menu1()
    {
        $account_id = $_COOKIE['account_id'];

        $dish_type = DishType::model()->findAll();
        $supplier_product = SupplierProduct::model()->findAll(array(
            'condition' => 'account_id=:account_id && standard_type=:standard_type',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                        ':standard_type' => 0
                    )));
        $dish=array();
        foreach ($supplier_product as $key => $value) {
            $t=explode('.', $value['ref_pic_url']);
            if(isset($t[0]) && isset($t[1])){
                $supplier_product[$key]['ref_pic_url'] = $t[0]."_sm.".$t[1];    
            };
            if($value['supplier_type_id']==2){
                $dish[]=$value;
            }
        };
        //print_r($dish);die;
        $this -> render("upload_menu1",array(
            'dish_type' => $dish_type,
            'supplier_product' => $supplier_product,
            ));
    }

    public function actionUpload_menu2()
    {
        $hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id=:account_id',
                'params' => array(
                        ':account_id' => $_COOKIE['account_id'],
                    ),
            ));
        $this->render("upload_menu2",array(
                'hotel' => $hotel,
            ));
    } 

    public function actionEdit_host_product()
    {
        ServiceProduct::model()->updateByPk($_POST['id'],array(
                'product_name' => $_POST['product_name'],
                'price' => $_POST['price'],
                'unit' => $_POST['unit'],
                'description' => $_POST['description'],
            ));

        $company = StaffCompany::model()->findAll();
        foreach ($company as $key => $value) {
            SupplierProduct::model()->updateAll(array(
                    'name' => $_POST['product_name'],
                    'unit_price' => $_POST['price'],
                    'unit' => $_POST['unit'],
                    'description' => $_POST['description'],
                ),'account_id=:account_id && service_product_id=:service_product_id',array(':account_id' => $value['id'],':service_product_id' => $_POST['id']));
        };
    }

    public function actionDel_service_product()
    {
        ServiceProduct::model()->updateByPk($_POST['id'],array(
                'product_show' => 0,
            ));

        $company = StaffCompany::model()->findAll();
        foreach ($company as $key => $value) {
            SupplierProduct::model()->updateAll(array(
                    'product_show' => 0,
                ),'account_id=:account_id && service_product_id=:service_product_id',array(':account_id' => $value['id'],':service_product_id' => $_POST['id']));
        };
    }
}
