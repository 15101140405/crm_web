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
        // $telephone = $_POST['telephone'];

        $url = "http://localhost:8080/crm_web_new/library/taobao-sdk-PHP-auto_1455552377940-20160505/send_code.php";

        // $staff = Staff::model()->find(array(
        //     "condition" => "telephone = :telephone",
        //     "params"    => array(
        //         ":telephone" => $_POST['telephone']
        //         )
        //     ));
        // if (empty($staff)) {
        //     echo "not exist";
        // } elseif (!empty($staff['password'])){
        //     echo "registered";
        // } else {
        //     $data = json_encode(array(
        //         'telephone' => $_POST['telephone'],
        //     ), JSON_UNESCAPED_UNICODE);
        //     WPRequest::post($url, $data);
        // }
        // $data = array(1);
        $result = WPRequest::post($url, $data);
        // print_r($data);
        // echo "result:".$result;

       
    }

    public function actionLogin()
    {
        $this->render("login");
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
        $url ="http://file.cike360.com";
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
    }
    public function actionUpload_product()
    {
        $result = yii::app()->db->createCommand("select supplier.id,supplier.type_id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$_COOKIE['account_id']);
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
        $data ->CI_Show = 1;
        $data ->CI_Remarks = $_POST['CI_Remarks']; 
        $data ->CI_Type = 1;
        $data->save();

        $CI_ID = $data->attributes['CI_ID'];
        
        $data = new CaseBind;
        $data ->CI_Type = 1; 
        $data ->TypeID = $_COOKIE['account_id'];
        $data ->CI_ID = $CI_ID;
        $data->save();

        $data = new CaseResources;
        $data ->CI_ID = $CI_ID;
        $data ->CR_Sort = 1;
        $data ->CR_Show = 1;
        foreach ($_POST['resources'] as $key => $value) {
            $data ->CR_Type = $vale['Cr_Type'];
            $data ->CR_Name = $vale['Cr_Name'];
            $data ->CR_Path = $vale['Cr_Path'];
            $data ->CR_Remarks = $vale['Remarks'];
            $data->save();
        }
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
        $data ->type_id = $_POST['type_id'];
        $data ->staff_id = $id;
        $data ->contract_url = "";
        $data ->update_time = $_POST['update_time'];
        $data ->save();
    }



}
