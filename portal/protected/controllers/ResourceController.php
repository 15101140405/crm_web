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
public $url ="http://xxxx.com/";
    public function actionList()
    {
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
                $list[$key]["CI_Pic"]=$url+$val["CI_Pic"];
            }
            //$val["size"]=$this->getUrlFileSize($val["CI_Pic"]);
            /*$resources = CaseResources::model()->findAll(array(
                    'condition' => 'CI_ID=:CI_ID',
                    'params' => array(
                            ':CI_ID' => $val["CI_ID"]
                        )
                )); */
  
            $result1 = yii::app()->db->createCommand("select * from case_resources where CI_ID =".$val["CI_ID"]);
            
            $resources = $result1->queryAll();

            foreach ($resources as $rkey => $rval) {
                if(!$this->startwith($rval["CR_Path"],"http://")&&!$this->startwith($rval["CR_Path"],"https://")){
                /*$resources[$rkey]["CR_Path"]=$this->$url+$rval["CR_Path"];*/
            }
            //$rval["size"]=$this->getUrlFileSize($rval["CR_Path"]);
            }
            $list[$key]["resources"]= $resources;
        }
        echo json_encode($list);

    }

    
}
