<?php

include_once('../library/WPRequest.php');

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
        $result = yii::app()->db->createCommand("select supplier.id,staff.name from supplier left join staff on staff_id=staff.id where supplier.account_id=".$_COOKIE['account_id']);
        $supplier = $result->queryAll();
        /*print_r($supplier);die;*/
        $this->render("upload_product",array(
                'supplier' => $supplier
            ));
    }

    function startwith($str,$pattern) {
        if(strpos($str,$pattern) === 0)
              return true;
        else
              return false;
    }

}
