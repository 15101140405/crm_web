<?php

include_once('../library/WPRequest.php');

class StaffController extends InitController
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

    public function actionLogin()
    {

        $staff = Staff::model()->find(array(

            "condition" => "telephone = :telephone",
            "params"    => array(
                ":telephone" => $_POST['telephone']
                )
            ));
        if (empty($staff)) {

            echo "用户不存在";
        }
        if ($staff['password'] == $_POST['password']) {
            echo "登录成功";
        } else {
            echo "密码错误";
        }
        
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

    public function actionList()
    {
        /*if(isset($_GET['account_id'])){
            Yii::app()->session['account_id']=$_GET['account_id']; 
            $company = StaffCompany::model()->findByPk($_SESSION['account_id']);     
        };
        
        if(isset($_SESSION['userid']) && isset($_SESSION['code']) && isset($_SESSION['account_id']) && isset($_SESSION['staff_hotel_id'])){*///已登陆
            //echo '已登陆';
            $this->getlist();
        /*}else{ //未登录
            //echo '未登陆';
            $code = $_GET['code'];
            Yii::app()->session['code']=$code;
            if($code == ''){
                $url1 = 'http://www.cike360.com/school/crm_web/portal/index.php?r=staff/list&t=plan&code=&account_id='.$_GET['account_id'];
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$company['corpid']."&redirect_uri=".urlencode($url1)."&response_type=code&scope=snsapi_base&state=abc#wechat_redirect&from=&this_order=";
                echo "<script>window.location='".$url."';</script>";
            };

            $company = StaffCompany::model()->findByPk($_SESSION['account_id']);
            $t=new WPRequest;
            $userId = $t->getUserId($code,$company['corpid'],$company['corpsecret']);
            $adder=array("UserId"=>"222","DeviceId"=>"");
            $adder=json_decode($userId,true);
            if(!empty($adder['UserId'])) {
                Yii::app()->session['userid']=$adder['UserId'];
                $staff = Staff::model()->findByPk($adder['UserId']);
                Yii::app()->session['account_id']=$staff['account_id'];
                Yii::app()->session['staff_hotel_id']=$staff['hotel_list'];
          */      /*print_r($_SESSION['account_id']);die;*/
                /*$this->getlist();
            };*/
        };
    }

    public function getlist()
    {
        $staff = Staff::model()->findAll(array(
                'condition' => 'account_id = :account_id',
                'params' => array(
                        ':account_id' => $_SESSION['account_id']
                    )
            ));
        /*print_r($staff);die;*/
        $arr_staff = array();
        foreach ($staff as $key => $value) {
            $item = array();
            $newstr = rtrim($value['department_list'], "]");
            $newstr = ltrim($newstr, "[");
            $department_list = explode(",",$newstr);

            $i=0;
            foreach ($department_list as $key1 => $value1) {
                if($value1 == 1 || $value1 == 2 || $value1 == 3 || $value1 == 5){
                    $i++;
                }
            }
            if($i != 0){
                $item['id']=$value['id'];
                $item['avatar']=$value['avatar'];
                $item['name']=$value['name'];
                $item['department_list']=$department_list;
                $arr_staff[]=$item;
            };
        };
        /*print_r($_SESSION['account_id']);die;*/
        $this->render("list", array(
            "account_id" => $_SESSION['account_id'],
            "arr_staff" => $arr_staff,
        ));
    }

    public function actionAdd()
    {
        $arr_staff = array(
                'name' => "",
                'phone' => "",
                'department_list' => array(),
                'hotel_list' =>""
            );

        if($_GET['type'] == 'edit'){//若为编辑，从数据库取数据
            $staff = Staff::model()->findByPk($_GET['staff_id']);
            $arr_staff['id'] = $staff['id'];
            $arr_staff['name'] = $staff['name'];
            $arr_staff['phone'] = $staff['telephone'];
            $newstr = rtrim($staff['department_list'], "]");
            $newstr = ltrim($newstr, "[");
            $arr_staff['department_list'] = explode(",",$newstr);
            $arr_staff['hotel_list'] = $staff['hotel_list'];
        };

        $arr_hotel = StaffHotel::model()->findAll(array(
                'condition' => 'account_id = :account_id',
                'params' => array(
                        ':account_id' => $_GET['account_id']
                    )
            ));
        /*print_r($arr_hotel);die;*/
        $this->render("add", array(
            "arr_staff" => $arr_staff,
            "arr_hotel" => $arr_hotel,
        ));
        
    }

    public function actionInsert()
    {
        $admin = new Staff;
        $admin ->account_id=$_POST['account_id'];
        $admin ->name=$_POST['name'];
        $admin ->telephone=$_POST['telephone'];
        $admin ->department_list=$_POST['department_list'];
        $admin ->hotel_list=$_POST['hotel_list'];
        $admin ->save();

        $staff = Staff::model()->find(array(
                'condition' => 'name=:name && telephone=:telephone',
                'params' => array(
                        ':name' => $_POST['name'],
                        ':telephone' => $_POST['telephone'],
                    )
            ));

        $company = StaffCompany::model()->findByPk($_POST['account_id']);
        $t=explode(";",$_POST['department_list']);
        $department = "[";
        foreach ($t as $key => $value) {
            $t1 = StaffDepartment::model()->findByPk($value);
            $department .= $t1['weixin_id'];
            $department .= ",";
        }
        $department = substr($department,0,strlen($department)-1); 
        $department .= "]";

        $userid = $staff['id'];
        $name = $_POST['name'];
        $position ="";
        $mobile = $_POST['telephone'];
        $gender = $_POST['gender'];
        $email ="";
        $weixinid ="";
        $corpid = $company['corpid'];
        $corpsecret = $company['corpsecret'];
        /*print_r($corpid."&&||&&".$corpsecret);die;*/

        print_r(WPRequest::create_user($userid, $name, $department, $position, $mobile, $gender, $email, $email, $weixinid, $corpid, $corpsecret));
    }

    public function actionUpdate()
    {
        Staff::model()->updateByPk($_POST['staff_id'],array('name'=>$_POST['name'],'telephone'=>$_POST['telephone'],'department_list'=>$_POST['department_list'],'hotel_list'=>$_POST['hotel_list']));
    }

    public function actionDel()
    {
        Staff::model()->deleteByPk($_POST['staff_id']);
    }

    

}
