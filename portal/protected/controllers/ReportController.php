<?php
include_once('../library/WPRequest.php');

class ReportController extends InitController
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

    /**
     * 供应商列表
     *
     */
    public function actionBilling()
    {
        $accountId = $this->getAccountId();
        //$a =  $accountId;
        //$a=  date('Y-m-d H:i:s',strtotime('Today'));
        $a = date('Y-m-d H:i:s',strtotime('this monday'));
        ///$a = date("w");
        $this->render("billing",array(
            "test" => $a
        ));

    }

    public function actionInfo()//开单信息
    {
        //print_r($_GET);
        $hotel_id  =  addslashes($_GET['hotel_id']);
        $chart1_type =addslashes($_GET['chart1_type']);
       // $unix_time = time();
        $unix_time = addslashes($_GET['show_date']);
        $arr_sum['t0'] = time();
        $arr_sum['ti'] = $unix_time;
        $date      = addslashes($_GET['show_day']);
      /*  $date = date("w"); //星期几1234560
        if($date==0){
            $date=7;
        }
      */
        $report = new ReportForm();
        $account_id = $this->getAccountId();
        if($chart1_type==0) {//周报
            $wedding = $report->getOrderWeek($account_id, $hotel_id, 2,$date,$unix_time);
            $meeting = $report->getOrderWeek($account_id, $hotel_id, 1,$date,$unix_time);
        }
        if($chart1_type==1) {//月报
            $wedding = $report->getOrderMonth($account_id, $hotel_id, 2,$unix_time);
            $meeting = $report->getOrderMonth($account_id, $hotel_id, 1,$unix_time);
        }
        /**/
        if($chart1_type==2) {//季报
            $wedding = $report->getOrderQuarter($account_id, $hotel_id, 2,$unix_time);
            $meeting = $report->getOrderQuarter($account_id, $hotel_id, 1,$unix_time);
        }
        if($chart1_type==3) {//年报
            $wedding = $report->getOrderYear($account_id, $hotel_id, 2,$unix_time);
            $meeting = $report->getOrderYear($account_id, $hotel_id, 1,$unix_time);
        }
        $arr_sum['wedding'] = $wedding;
        $arr_sum['meeting'] = $meeting;
        $arr_sum['success'] = 1;
        echo json_encode($arr_sum);

    }

    public function actionIndividual_performance_targets()
    {
        $this->render("individual_performance_targets");

    }

    public function actionProfit()
    {
        $this->render("profit");

    }

    public function actionDayreport()
    {
        //取当日开单数据
        /*$time = time();
        $date = date("Y-m-d");

        $criteria = new CDbCriteria; 
        $criteria->addSearchCondition('update_time', $date);
        $order1 = Order::model()->findAll($criteria);
        /*print_r($order1);die;*/

        /*$yesterday_open_order = count($order1); 

        $order_open = array();
        foreach ($order1 as $key => $value) {
            $item = array();
            $t1 = explode(" ",$value['order_date']);
            $t2 = explode("-",$t1[0]);
            $t3 = $t2[1].'/'.$t2[2];
            $item['date'] = $t3;
            $item['type'] = '无';
            if($value['order_type'] == '1'){
                $item['type'] = '婚礼';
            }else if($value['order_type'] == '2'){
                $item['type'] = '会议';
            }

            $staff = Staff::model()->findByPk($value['adder_id']);
            $item['name'] = $staff['name'];
            $order_open[] = $item ;
        }

        

        //取全部已定订单数据
        $criteria3 = new CDbCriteria; 
        $criteria3->addInCondition('order_status', array(2,3));
        $criteria3->order = 'order_date DESC'; 
        $order3 = Order::model()->findAll($criteria3);  




        $tuidan = SupplierProduct::model()->findAll(array(
            'condition' => 'supplier_type_id = :supplier_type_id ',
            'params' => array(':supplier_type_id'=>16),
            ));*/
        
        /*$post=Post::model()->find(array(  
            'select'=>'title',  
            'condition'=>'postID=:postID',  
            'params'=>array(':postID'=>10),  
            ));  */
        /*$meeting_num = 0;
        $wedding_num = 0;

        $tuidan_id = array();
        foreach ($tuidan as $key => $value) {
            $tuidan_id[] = $value['id'];
        };

        $order_all = array();
        foreach ($order3 as $key => $value) {
            $item = array();
            $t1 = explode(" ",$value['order_date']);
            $t2 = explode("-",$t1[0]);
            $t3 = $t2[1].'/'.$t2[2];
            $item['date'] = $t3;
            $item['type'] = "";
            if($value['order_type'] == "1"){
                $item['type'] = '会议';
            }else if($value['order_type'] == "2"){
                $item['type'] = '婚礼';
            };

            $staff = Staff::model()->findByPk($value['planner_id']);
            $item['planner_name'] = $staff['name'];
            $staff = Staff::model()->findByPk($value['designer_id']);
            $item['designer_name'] = $staff['name'];
            /*print_r($tuidan_id);die;*/
            /*$criteria3 = new CDbCriteria; 
            $criteria3->addCondition('order_id', $value['id']);
            $criteria3->addInCondition('product_id', $tuidan_id);
            $order_product = OrderProduct::model()->findAll($criteria3);*/ 
            /*print_r($order_product);die;*/
            /*$SupplierProduct = array();
            if(!empty($arr)){
                $SupplierProduct = SupplierProduct::model()->findByPk($order_product['product_id']);
            }else{
                $SupplierProduct['name'] = "无";
            };
            

            $item['tuidan_name'] = $SupplierProduct['name'];

            $order_all[] = $item;
            if($value['order_type'] == 1){
                $meeting_num++;
            }else if($value['order_type'] == 2){
                $wedding_num++;
            };
        };*/
        /*print_r($order_all);die;*/

        /*print_r($today_order);print_r($meeting_num);print_r($wedding_num);  */


        /*$html = '<div class="rich_media_content " id="js_content">';   
        $html .='                <section style="box-sizing: border-box; background-color: rgb(255, 255, 255);">'; 
        $html .='                    <section class="Powered-by-XIUMI V5" style="position: static; box-sizing: border-box;">'; 
        $html .='                        <section class="" style="margin: 0.5em 0px; position: static; box-sizing: border-box;">'; 
        $html .='                            <section class="" style="border-top-width: 2px; border-top-style: solid; border-color: rgb(95, 156, 239); padding-top: 3px; box-sizing: border-box;">'; 
        $html .='                                <section class="" style="display: inline-block; vertical-align: top; height: 2em; line-height: 2em; padding: 0px 0.5em; color: rgb(255, 255, 255); box-sizing: border-box; background-color: rgb(95, 156, 239);">'; 
        $html .='                                    <section style="box-sizing: border-box;">今日进店［ '.$yesterday_open_order.' ］单</section>'; 
        $html .='                                    <section style="box-sizing: border-box;">'; 
        $html .='                                        <br style="box-sizing: border-box;"  />'; 
        $html .='                                    </section>'; 
        $html .='                                    <section style="box-sizing: border-box;">'; 
        $html .='                                        <br style="box-sizing: border-box;"  />'; 
        $html .='                                    </section>'; 
        $html .='                                </section>'; 
        $html .='                                <section style="width: 0px; display: inline-block; vertical-align: top; border-left-width: 0.8em; border-left-style: solid; border-left-color: rgb(95, 156, 239); border-top-width: 1em; border-top-style: solid; border-top-color: rgb(95, 156, 239); border-right-width: 0.8em !important; border-right-style: solid !important; border-right-color: transparent !important; border-bottom-width: 1em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important; box-sizing: border-box;">'; 
        $html .='                                </section>'; 
        $html .='                            </section>'; 
        $html .='                        </section>'; 
        $html .='                    </section>';  


        foreach ($order_open as $key => $value) {
            $html .= '<section class="" style="position: static; box-sizing: border-box;">'; */
        /*    $html .='                        <section class="" style="display: inline-block; vertical-align: top; width: 20%; box-sizing: border-box;">'; 
            $html .='                            <section class="Powered-by-XIUMI V5" style="position: static; box-sizing: border-box;">'; 
            $html .='                                <section class="" style=" transform: translate3d(10px, 0px, 0px); -webkit-transform: translate3d(10px, 0px, 0px); -moz-transform: translate3d(10px, 0px, 0px); -o-transform: translate3d(10px, 0px, 0px); margin: 12px 0% 0px; position: static; box-sizing: border-box;">'; 
            $html .='                                    <img style="max-width: 100%; width: 50%; box-sizing: border-box; vertical-align: middle;" class="" src="http://img.xiumi.us/xmi/ua/yTtV/i/7f3379102852667b03d0b4d539e2819e-sz_14151.png@1l_640w.png"  />'; 
            $html .='                                </section>'; 
            $html .='                            </section>'; 
            $html .='                        </section>'; */
            /*$html .='                        <section class="" style="display: inline-block; vertical-align: top; width: 80%; box-sizing: border-box;">'; 
            $html .='                            <section class="Powered-by-XIUMI V5" style="position: static; box-sizing: border-box;">'; 
            $html .='                                <section class="" style="margin: 0px; position: static; box-sizing: border-box;">'; 
            $html .='                                    <section class="" style="display: inline-block; float: left; width: 1em; height: 1em; margin: 1.5em 0px -2em -0.5em; border: 1px solid rgb(95, 156, 239); border-radius: 100%; box-sizing: border-box; background-color: rgb(95, 156, 239);">'; 
            $html .='                                    </section>'; 
            $html .='                                    <section class="" style="border-left-width: 1px; border-left-style: solid; height: 1.2em; border-color: rgb(95, 156, 239); box-sizing: border-box;">'; 
            $html .='                                    </section>'; 
            $html .='                                    <section class="" style="border-left-width: 1px; border-left-style: solid; border-color: rgb(95, 156, 239); box-sizing: border-box;">'; 
            $html .='                                        <section class="" style="padding: 0px 0px 10px 15px; box-sizing: border-box;">'; 
            $html .='                                            <section class="Powered-by-XIUMI V5" style="box-sizing: border-box;">'; 
            $html .='                                                <section class="" style="position: static; box-sizing: border-box;">'; 
            $html .='                                                    <section class="" style="box-sizing: border-box;">'; 
            $html .='                                                        <section style="box-sizing: border-box;">' .$value['date']. ' (' .$value['type']. ') ｜ 开单人：' .$value['name']. '</section>'; 
            $html .='                                                    </section>'; 
            $html .='                                                </section>'; 
            $html .='                                            </section>'; 
            $html .='                                        </section>'; 
            $html .='                                    </section>'; 
            $html .='                                </section>'; 
            $html .='                            </section>'; 
            $html .='                        </section>'; 
            $html .='                    </section>';
        };  

        $html .= '<section class="Powered-by-XIUMI V5" style="box-sizing: border-box;">'; 
        $html .='                        <section class="" style="position: static; box-sizing: border-box;">'; 
        $html .='                            <section class="" style="box-sizing: border-box;">'; 
        $html .='                                <section style="box-sizing: border-box;">'; 
        $html .='                                    <br style="box-sizing: border-box;"  />'; 
        $html .='                                </section>'; 
        $html .='                            </section>'; 
        $html .='                        </section>'; 
        $html .='                    </section>'; 
        $html .='                    <section class="Powered-by-XIUMI V5" style="position: static; box-sizing: border-box;">'; 
        $html .='                        <section class="" style="margin: 0.5em 0px; position: static; box-sizing: border-box;">'; 
        $html .='                            <section class="" style="border-top-width: 2px; border-top-style: solid; border-color: rgb(95, 156, 239); padding-top: 3px; box-sizing: border-box;">'; 
        $html .='                                <section class="" style="display: inline-block; vertical-align: top; height: 2em; line-height: 2em; padding: 0px 0.5em; color: rgb(255, 255, 255); box-sizing: border-box; background-color: rgb(95, 156, 239);">'; 
        $html .='                                    <section style="box-sizing: border-box;">已签订单｜婚礼［' .$wedding_num. '］场／会议［ ' .$meeting_num. '］场</section>'; 
        $html .='                                </section>'; 
        $html .='                                <section style="width: 0px; display: inline-block; vertical-align: top; border-left-width: 0.8em; border-left-style: solid; border-left-color: rgb(95, 156, 239); border-top-width: 1em; border-top-style: solid; border-top-color: rgb(95, 156, 239); border-right-width: 0.8em !important; border-right-style: solid !important; border-right-color: transparent !important; border-bottom-width: 1em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important; box-sizing: border-box;">'; 
        $html .='                                </section>'; 
        $html .='                            </section>'; 
        $html .='                        </section>'; 
        $html .='                    </section>'; 

        foreach ($order_all as $key => $value) {
            $html .= '<section class="Powered-by-XIUMI V5" style="position: static; box-sizing: border-box;">'; 
            $html .='                        <section class="" style="margin: 0px; position: static; box-sizing: border-box;">'; 
            $html .='                            <section class="" style="display: inline-block; vertical-align: top; width: 80%; box-sizing: border-box;">'; 
            $html .='                                <section style="border-right-width: 1px; border-right-style: solid; border-color: rgb(95, 156, 239); height: 1.2em; box-sizing: border-box;">'; 
            $html .='                                </section>'; 
            $html .='                                <section style="display: inline-block; float: right; width: 1em; height: 1em; margin: 0px -0.5em -2em 0px; border: 1px solid rgb(95, 156, 239); border-radius: 100%; box-sizing: border-box; background-color: rgb(95, 156, 239);">'; 
            $html .='                                </section>'; 
            $html .='                                <section style="border-right-width: 1px; border-right-style: solid; border-color: rgb(95, 156, 239); box-sizing: border-box;">'; 
            $html .='                                    <section class="" style="padding: 0px 20px 10px 0px; box-sizing: border-box;">'; 
            $html .='                                        <section class="Powered-by-XIUMI V5" style="box-sizing: border-box;">'; 
            $html .='                                            <section class="" style="position: static; box-sizing: border-box;">'; 
            $html .='                                                <section class="" style="text-align: right; font-size: 12px; box-sizing: border-box;">'; 
            if($value['type'] == "会议"){
                $html .='                                                    <section style="box-sizing: border-box;">统筹师：' .$value["planner_name"]. '|（推单：'.$value["tuidan_name"].') '.$value["type"].' </section>'; 
            }else {
                $html .='                                                    <section style="box-sizing: border-box;">策划师：' .$value["designer_name"]. '|（推单：'.$value["tuidan_name"].') '.$value["type"].' </section>';         
            }
            $html .='                                                </section>'; 
            $html .='                                            </section>'; 
            $html .='                                        </section>'; 
            $html .='                                    </section>'; 
            $html .='                                </section>'; 
            $html .='                            </section>'; 
            $html .='                            <section class="" style="display: inline-block; vertical-align: top; width: 18%; box-sizing: border-box;">'; 
            $html .='                                <span style="display: inline-block; margin-top: 1.2em; margin-left: 1em; font-size: 14px; box-sizing: border-box;" class="">'; 
            $html .='                                    <section style="box-sizing: border-box;">' .$value["date"]. '</section>'; 
            $html .='                                </span> '; 
            $html .='                            </section>'; 
            $html .='                        </section>'; 
            $html .='                    </section>';
        };*/

        /*$html .= '<section class="Powered-by-XIUMI V5" style="position: static; box-sizing: border-box;">'; 
        $html .='                        <section class="" style="text-align: center; margin-top: 10px; margin-bottom: 10px; font-size: 32px; position: static; box-sizing: border-box;">'; 
        $html .='                            <section class="" style="width: 5em; height: 5em; margin: auto; display: inline-block; vertical-align: bottom; border-radius: 100%; box-sizing: border-box; background-color: rgb(95, 156, 239);">'; 
        $html .='                                <section style="display: table; width: 100%; height: 5em; box-sizing: border-box;">'; 
        $html .='                                    <section class="" style="display: table-cell; vertical-align: middle; width: 100%; line-height: 1.2; padding: 15px; border-radius: 100%; box-sizing: border-box;">'; 
        $html .='                                        <section class="Powered-by-XIUMI V5" style="box-sizing: border-box;">'; 
        $html .='                                            <section class="" style="position: static; box-sizing: border-box;">'; 
        $html .='                                                <section class="" style="color: rgb(255, 255, 255); font-size: 20.16px; box-sizing: border-box;">'; 
        $html .='                                                    <section style="box-sizing: border-box;">查看</section>'; 
        $html .='                                                    <section style="box-sizing: border-box;">订单详情</section>'; 
        $html .='                                                </section>'; 
        $html .='                                            </section>'; 
        $html .='                                        </section>'; 
        $html .='                                    </section>'; 
        $html .='                                </section>'; 
        $html .='                            </section>'; 
        $html .='                        </section>'; 
        $html .='                    </section>'; 
        $html .='                    <section class="Powered-by-XIUMI V5" style="box-sizing: border-box;">'; 
        $html .='                        <section class="" style="position: static; box-sizing: border-box;">'; 
        $html .='                            <section class="" style="box-sizing: border-box;">'; 
        $html .='                                <section style="box-sizing: border-box;"><br style="box-sizing: border-box;"  /></section>'; 
        $html .='                            </section>'; 
        $html .='                        </section>'; 
        $html .='                    </section>'; 
        $html .='                </section>'; 
        $html .='                <p><br  /></p>'; 
        $html .='            </div>';*/

        $company = StaffCompany::model()->findAll();
        /*print_r($company);die;*/
        foreach ($company as $key => $value) {
            $hotel = StaffHotel::model()->findAll(array(
                    'condition' => 'account_id=:account_id',
                    'params' => array(
                            'account_id' => $value['id']
                        )
                ));
            foreach ($hotel as $key1 => $value1) {
                $touser="100|101";
                $content="中文怎么解决";
                $title="今日经营日报（".$value1['name'].")";
                $agentid=0;
                $url="http://www.cike360.com/school/crm_web/portal/index.php?r=report/dayreporthtml&code=&account_id=".$value['id']."&staff_hotel_id=".$value1['id'];
                $thumb_media_id="1VIziIEzGn_YvRxXK3OxPQpylPHLUnnA2gJ5_v8Cus2la7sjhAWYgzyFZhIVI9UoS6lkQ-ZLuMPZgP8BOVIS-XQ";
                $media_id="2n8jAkMtWj42qcBGih5M_hq0teff_17YKATQXYyLlLyAEN6Z_5mOgSyBUcKz7ebu9";
                $description="经营日报";
                $picur="http://www.cike360.com/school/crm_web/image/thumb.jpg";
                // $t=new ReportController;

                // $content = $t->actionDayreport();
                // print_r($content);die;
                //$content=$html;
                // $content=ReportController::actionDayreport();
                $digest="描述";
                //$media="C:\Users\Light\Desktop\life\65298b36.jpg";
                // $media="@/var/www/html/school/crm_web/image/thumb.jpg";
                // $type="image";

                echo "</br>";
                $author="";
                $content_source_url="http://www.cike360.com/school/crm_web/portal/index.php?r=report/dayreporthtml";
                $show_cover_pic="";
                $safe="";
                $toparty="";
                $totag="";
                $result1=WPRequest::updateMpnews_Data($media_id,$title,$thumb_media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
                /*$result2=WPrequest::sendMessage_Mpnews(
                        $touser, $toparty, $totag, $agentid, $title, $thumb_media_id, $author, $content_source_url, $content, $digest, $show_cover_pic, $safe);*/
                // $result=WPRequest::addmpnews( $title,$media_id,$author,$content_source_url,$content,$digest,$show_cover_pic);
                //print_r(WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur));
                $result2=WPRequest::sendMessage_News($touser, $toparty, $title, $description, $url, $picur, $agentid,$value['corpid'],$value['corpsecret']);
                //$result=WPRequest::mediaupload($media,$type);
                echo "result1:";
                print_r($result1);
                echo "result2:";
                print_r($result2);
            }
        }
    }

    public function actionDayreporthtml()
    {
        
        Yii::app()->session['account_id']=$_GET['account_id'];  
        Yii::app()->session['staff_hotel_id']=$_GET['staff_hotel_id'];  
        $company = StaffCompany::model()->findByPk($_SESSION['account_id']);     

        //********************************************************************************************
        //取当年已签订单数据
        //********************************************************************************************
        $data = $this->order_num();

        /*print_r($data);die;*/


        //********************************************************************************************
        //取销售额
        //********************************************************************************************
        $year = date("Y");
        $order_status_forecast = array(0,1,2,3,4,5,6);
        $order_status_deal = array(2,3,4,5,6);
        $sales=array();
        //取门店信息
        $hotel=StaffHotel::model()->findByPk($_SESSION['staff_hotel_id']);
        $sales['target']=$hotel['target'];
        $sales['forecast']=number_format(($this->hotel_total_sales($_SESSION['staff_hotel_id'],$year,$order_status_forecast))/10000, 1);;
        $sales['deal']=number_format(($this->hotel_total_sales($_SESSION['staff_hotel_id'],$year,$order_status_deal))/10000,1);
        $sales['payment']=number_format(($this->hotel_total_payment($_SESSION['staff_hotel_id'],$year,$order_status_deal))/10000,1);

        //取当日开单数据
        $time = time();
        $date = date("y-m-d");

        $criteria = new CDbCriteria; 
        $criteria->addSearchCondition('update_time', $date);
        $criteria->addCondition('account_id=:account_id');
        $criteria->addCondition('staff_hotel_id=:staff_hotel_id');
        $criteria->params[':account_id']=$_SESSION['account_id'];  
        $criteria->params[':staff_hotel_id']=$_SESSION['staff_hotel_id'];  
        $order1 = Order::model()->findAll($criteria);

        

        $yesterday_open_order = count($order1); 

        $order_open = array();
        /*print_r($order1);die;*/

        foreach ($order1 as $key => $value) {
            $item = array();
            $t1 = explode(" ",$value['order_date']);
            $t2 = explode("-",$t1[0]);
            $t3 = $t2[1].'/'.$t2[2];
            $item['date'] = $t3;
            $item['type'] = '无';
            if($value['order_type'] == '2'){
                $item['type'] = '婚礼';
            }else if($value['order_type'] == '1'){
                $item['type'] = '会议';
            }

            $staff = Staff::model()->findByPk($value['adder_id']);
            $item['name'] = $staff['name'];
            $order_open[] = $item ;
        }
        
        

        //取全部已定订单数据
        $criteria3 = new CDbCriteria; 
        $criteria3->addInCondition('order_status', array(1,2,3));
        $criteria3->addCondition('account_id=:account_id');
        $criteria3->addCondition('staff_hotel_id=:staff_hotel_id');
        $criteria3->addCondition('staff_hotel_id=:staff_hotel_id');
        $criteria3->params[':account_id']=$_SESSION['account_id'];  
        $criteria3->params[':staff_hotel_id']=$_SESSION['staff_hotel_id'];  
        $criteria3->order = 'order_date DESC'; 
        $order3 = Order::model()->findAll($criteria3);  




        $tuidan = SupplierProduct::model()->findAll(array(
            'condition' => 'supplier_type_id = :supplier_type_id ',
            'params' => array(':supplier_type_id'=>16),
            ));
        
        /*$post=Post::model()->find(array(  
            'select'=>'title',  
            'condition'=>'postID=:postID',  
            'params'=>array(':postID'=>10),  
            ));  */
        $meeting_num = 0;
        $wedding_num = 0;

        $tuidan_id = array();
        foreach ($tuidan as $key => $value) {
            $tuidan_id[] = $value['id'];
        };

        $order_all = array();
        foreach ($order3 as $key => $value) {
            $item = array();
            $t1 = explode(" ",$value['order_date']);
            $t2 = explode("-",$t1[0]);
            $t3 = $t2[1].'/'.$t2[2];
            $item['date'] = $t3;
            $item['type'] = "";
            if($value['order_type'] == "1"){
                $item['type'] = '会议';
            }else if($value['order_type'] == "2"){
                $item['type'] = '婚礼';
            };

            $staff = Staff::model()->findByPk($value['planner_id']);
            $item['planner_name'] = $staff['name'];
            $staff = Staff::model()->findByPk($value['designer_id']);
            $item['designer_name'] = $staff['name'];
            /*print_r($tuidan_id);die;*/
            $criteria3 = new CDbCriteria; 
            $criteria3->addCondition('order_id', $value['id']);
            $criteria3->addInCondition('product_id', $tuidan_id);
            $order_product = OrderProduct::model()->findAll($criteria3); 
            /*print_r($order_product);die;*/
            $SupplierProduct = array();
            if(!empty($arr)){
                $SupplierProduct = SupplierProduct::model()->findByPk($order_product['product_id']);
            }else{
                $SupplierProduct['name'] = "无";
            };
            

            $item['tuidan_name'] = $SupplierProduct['name'];

            $order_all[] = $item;
            if($value['order_type'] == 1){
                $meeting_num++;
            }else if($value['order_type'] == 2){
                $wedding_num++;
            };
        };

        


        /*print_r($order_open);die;*/
        $this->render('dayreporthtml',array(
                'order_sure' => $data,
                'sales' => $sales,
                'yesterday_open_order' => $yesterday_open_order,
                'order_open' => $order_open,
                'wedding_num' => $wedding_num,
                'meeting_num' => $meeting_num,
                'order_all' => $order_all,
                'hotel_name' => $hotel['name'],
                'hotel_id' => $hotel['id']
            ));

        //生成静态页面
        /*$controller = new controller('report');
        $content  = $controller->render('dayreport',array(
                'order_sure' => $data,
                'sales' => $sales,
            ));
    
        $filePath = YiiBase::getPathOfAlias('webroot') . '/'. Generator::myHtmlLink($model) . '/itranslation.html';
        return Generator::save($filePath, $content);*/
    }

    public function hotel_total_sales($hotel_id,$year,$order_status)
    {
        
        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("order_status",$order_status);
        $criteria1->addCondition("staff_hotel_id=:staff_hotel_id");
        $criteria1->params[':staff_hotel_id']=$hotel_id; 
        $order = Order::model()->findAll($criteria1);
        $order_id = array();
        
        foreach ($order as $key => $value) {
            $t1 = explode(' ',$value['order_date']);
            $t2 = explode('-',$t1[0]);
            /*print_r($value['id'].$year."|".$t2[0]."</br>");*/
            if($year == $t2[0]){
                $order_id[$key] = (int)$value['id'];
            };
        }
        /*var_dump($order_id);die;*/
        $hotel_total_sales = 0;
        foreach ($order_id as $key => $value) {
            $t = $this->order_wedding_total_price($value);
            $hotel_total_sales += $t['total_price'];
            /*print_r($t);die;*/
        }

        return $hotel_total_sales;
    }

    public function order_wedding_total_price($order_id)
    {
        $orderId = $order_id;
        $supplier_product_id = array();
        $wed_feast = array();
        $arr_wed_feast = array();

        $order_discount = Order::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $orderId),
        ));

        /*print_r($order_discount['other_discount']);die;
*/
        /*********************************************************************************************************************/
        /*取餐饮数据*/
        /*********************************************************************************************************************/
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 2),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/
        if(!empty($supplier_id)){
            $criteria1 = new CDbCriteria; 
            $criteria1->addInCondition("supplier_id",$supplier_id);
            $criteria1->addCondition("category=:category");
            $criteria1->params[':category']=2; 
            $supplier_product = SupplierProduct::model()->findAll($criteria1);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = $value->id;
                $supplier_product_id[] = $item;
            };
            /*print_r($supplier_product_id);*/
        }
        
        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $wed_feast[] = $item;
            };
            /*print_r($wed_feast);*/
        }
        /*print_r($wed_feast);*/
        
        if(!empty($wed_feast)){
            $criteria3 = new CDbCriteria; 
            $criteria3->addCondition("id=:id");
            $criteria3->params[':id']=$wed_feast[0]['product_id']; 
            $supplier_product2 = SupplierProduct::model()->find($criteria3);
            /*print_r($supplier_product2);*/
            $arr_wed_feast = array(
                'name' => $supplier_product2['name'],
                'unit_price' => $wed_feast[0]['actual_price'],
                'unit' => $supplier_product2['unit'],
                'table_num' => $wed_feast[0]['unit'],
                'service_charge_ratio' => $wed_feast[0]['actual_service_ratio'],
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']),
                'total_cost' => $wed_feast[0]['actual_unit_cost']*$wed_feast[0]['unit'],
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio'],
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio'])/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio'])),
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_wed_feast);*/

        /*********************************************************************************************************************/
        /*取灯光数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_light = array();
        $light = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 8),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $light[] = $item;
            };
        }
        if (!empty($light)) {
            $arr_light_total['total_price']=0;
            $arr_light_total['total_cost']=0;
            foreach ($light as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$light[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $light[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $light[$key]['unit'],
                );
                $arr_light[]=$item;
                $arr_light_total['total_price'] += $light[$key]['actual_price']*$light[$key]['unit'];
                $arr_light_total['total_cost'] +=$light[$key]['actual_unit_cost']*$light[$key]['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            if($arr_light_total['total_price'] != 0){
                $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];    
            }else if($arr_light_total['total_cost'] != 0){
                $arr_light_total['gross_profit_rate'] = 0;
            }     
        }else{
            $arr_light_total['gross_profit']=0;
            $arr_light_total['gross_profit_rate']=0;
            $arr_light_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }

        /*print_r($arr_light_total);*/

        /*********************************************************************************************************************/
        /*取视频数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_video = array();
        $arr_video_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 9),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            $video = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $video[] = $item;
            };
            /*print_r($video);*/
        }

        if (!empty($video)) {
            $arr_video_total['total_price']=0;
            $arr_video_total['total_cost']=0;
            foreach ($video as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$video[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $video[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $video[$key]['unit'],
                );
                $arr_video[]=$item;
                $arr_video_total['total_price'] += $video[$key]['actual_price']*$video[$key]['unit'];
                $arr_video_total['total_cost'] +=$video[$key]['actual_unit_cost']*$video[$key]['unit'];
            }
            
                $arr_video_total['gross_profit']=$arr_video_total['total_price']-$arr_video_total['total_cost'];
            if($arr_video_total['total_price'] != 0){
                $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];    
            }else if($arr_video_total['total_cost'] != 0){
                $arr_video_total['gross_profit_rate'] = 0;
            }           
            
        }

        /*print_r($arr_video_total);*/

        /*********************************************************************************************************************/
        /*取主持人数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_host = array();
        $arr_host_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 3),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            $host = array();
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $host[] = $item;
            };
            /*print_r($host);*/
        }
        if (!empty($host)) {
            $arr_host_total['total_price']=0;
            $arr_host_total['total_cost']=0;
            foreach ($host as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$host[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $host[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $host[$key]['unit'],
                );
                $arr_host[]=$item;
                $arr_host_total['total_price'] += $host[$key]['actual_price']*$host[$key]['unit'];
                $arr_host_total['total_cost'] +=$host[$key]['actual_unit_cost']*$host[$key]['unit'];
            }        
            $arr_host_total['gross_profit']=$arr_host_total['total_price']-$arr_host_total['total_cost'];
            if($arr_host_total['total_price'] != 0){
                $arr_host_total['gross_profit_rate']=$arr_host_total['gross_profit']/$arr_host_total['total_price'];    
            }else if($arr_host_total['total_cost'] != 0){
                $arr_host_total['gross_profit_rate'] = 0;
            }   
        }

        /*print_r($arr_host);*/


        /*********************************************************************************************************************/
        /*取摄像数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_camera = array();
        $camera = array();
        $arr_camera_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 4),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $camera[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($camera)) {
            $arr_camera_total['total_price']=0;
            $arr_camera_total['total_cost']=0;
            foreach ($camera as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$camera[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $camera[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $camera[$key]['unit'],
                );
                $arr_camera[]=$item;
                $arr_camera_total['total_price'] += $camera[$key]['actual_price']*$camera[$key]['unit'];
                $arr_camera_total['total_cost'] +=$camera[$key]['actual_unit_cost']*$camera[$key]['unit'];
            }           
            $arr_camera_total['gross_profit']=$arr_camera_total['total_price']-$arr_camera_total['total_cost'];
            if($arr_camera_total['total_price'] != 0){
                $arr_camera_total['gross_profit_rate']=$arr_camera_total['gross_profit']/$arr_camera_total['total_price'];    
            }else if($arr_camera_total['total_cost'] != 0){
                $arr_camera_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取摄影数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_photo = array();
        $photo = array();
        $arr_photo_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 5),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $photo[] = $item;
            };
            /*print_r($photo);*/
        }
        if (!empty($photo)) {
            $arr_photo_total['total_price']=0;
            $arr_photo_total['total_cost']=0;
            foreach ($photo as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$photo[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $photo[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $photo[$key]['unit'],
                );
                $arr_photo[]=$item;
                $arr_photo_total['total_price'] += $photo[$key]['actual_price']*$photo[$key]['unit'];
                $arr_photo_total['total_cost'] +=$photo[$key]['actual_unit_cost']*$photo[$key]['unit'];
            }           
            $arr_photo_total['gross_profit']=$arr_photo_total['total_price']-$arr_photo_total['total_cost'];
            if($arr_photo_total['total_price'] != 0){
                $arr_photo_total['gross_profit_rate']=$arr_photo_total['gross_profit']/$arr_photo_total['total_price'];    
            }else if($arr_photo_total['total_cost'] != 0){
                $arr_photo_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_photo_total);*/

        /*********************************************************************************************************************/
        /*取化妆数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_makeup = array();
        $makeup = array();
        $arr_makeup_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 6),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $makeup[] = $item;
            };
            /*print_r($makeup);*/
        }
        if (!empty($makeup)) {
            $arr_makeup_total['total_price']=0;
            $arr_makeup_total['total_cost']=0;
            foreach ($makeup as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$makeup[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $makeup[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $makeup[$key]['unit'],
                );
                $arr_makeup[]=$item;
                $arr_makeup_total['total_price'] += $makeup[$key]['actual_price']*$makeup[$key]['unit'];
                $arr_makeup_total['total_cost'] +=$makeup[$key]['actual_unit_cost']*$makeup[$key]['unit'];
            }           
            $arr_makeup_total['gross_profit']=$arr_makeup_total['total_price']-$arr_makeup_total['total_cost'];
            if($arr_makeup_total['total_price'] != 0){
                $arr_makeup_total['gross_profit_rate']=$arr_makeup_total['gross_profit']/$arr_makeup_total['total_price'];    
            }else if($arr_makeup_total['total_cost'] != 0){
                $arr_makeup_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取其他人员数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_other = array();
        $other = array();
        $arr_other_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 7),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $other[] = $item;
            };
            /*print_r($other);*/
        }
        if (!empty($other)) {
            $arr_other_total['total_price']=0;
            $arr_other_total['total_cost']=0;
            foreach ($other as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$other[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $other[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $other[$key]['unit'],
                );
                $arr_other[]=$item;
                $arr_other_total['total_price'] += $other[$key]['actual_price']*$other[$key]['unit'];
                $arr_other_total['total_cost'] +=$other[$key]['actual_unit_cost']*$other[$key]['unit'];
            }           
            $arr_other_total['gross_profit']=$arr_other_total['total_price']-$arr_other_total['total_cost'];
            if($arr_other_total['total_price'] != 0){
                $arr_other_total['gross_profit_rate']=$arr_other_total['gross_profit']/$arr_other_total['total_price'];    
            }else if($arr_other_total['total_cost'] != 0){
                $arr_other_total['gross_profit_rate'] = 0;
            }  
        }

        /*print_r($arr_makeup_total);*/



        /*********************************************************************************************************************/
        /*计算人员部分总价*/
        /*********************************************************************************************************************/
        $arr_service_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        if(!empty($arr_host_total)){
            $arr_service_total['total_price'] += $arr_host_total['total_price'];
            $arr_service_total['total_cost'] += $arr_host_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_host_total['gross_profit'];
        }

        if(!empty($arr_camera_total)){
            $arr_service_total['total_price'] += $arr_camera_total['total_price'];
            $arr_service_total['total_cost'] += $arr_camera_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_camera_total['gross_profit'];
        }

        if(!empty($arr_photo_total)){
            $arr_service_total['total_price'] += $arr_photo_total['total_price'];
            $arr_service_total['total_cost'] += $arr_photo_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_photo_total['gross_profit'];
        }

        if(!empty($arr_makeup_total)){
            $arr_service_total['total_price'] += $arr_makeup_total['total_price'];
            $arr_service_total['total_cost'] += $arr_makeup_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_makeup_total['gross_profit'];
        }

        if(!empty($arr_other_total)){
            $arr_service_total['total_price'] += $arr_other_total['total_price'];
            $arr_service_total['total_cost'] += $arr_other_total['total_cost'];
            $arr_service_total['gross_profit'] += $arr_other_total['gross_profit'];
        }



        if($arr_service_total['total_price'] != 0){
            $arr_service_total['gross_profit_rate'] = $arr_service_total['gross_profit']/$arr_service_total['total_price'];
        }




        /*********************************************************************************************************************/
        /*取场地布置数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_decoration = array();
        $decoration = array();
        $arr_decoration_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 20),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $decoration[] = $item;
            };
            /*print_r($decoration);*/
        }
        if (!empty($decoration)) {
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
            foreach ($decoration as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$decoration[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $decoration[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $decoration[$key]['unit'],
                );
                $arr_decoration[]=$item;
                $arr_decoration_total['total_price'] += $decoration[$key]['actual_price']*$decoration[$key]['unit'];
                $arr_decoration_total['total_cost'] +=$decoration[$key]['actual_unit_cost']*$decoration[$key]['unit'];
            }           
            $arr_decoration_total['gross_profit']=$arr_decoration_total['total_price']-$arr_decoration_total['total_cost'];
            if($arr_decoration_total['total_price'] != 0){
                $arr_decoration_total['gross_profit_rate']=$arr_decoration_total['gross_profit']/$arr_decoration_total['total_price'];    
            }else if($arr_decoration_total['total_cost'] != 0){
                $arr_decoration_total['gross_profit_rate'] = 0;
            }  
        }else{
            $arr_decoration_total['gross_profit']=0;
            $arr_decoration_total['gross_profit_rate']=0;
            $arr_decoration_total['total_price']=0;
            $arr_decoration_total['total_cost']=0;
        }
        /*print_r($arr_decoration_total['total_cost']);die;*/

        /*print_r($arr_makeup_total);*/


        /*********************************************************************************************************************/
        /*取平面设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_graphic = array();
        $graphic = array();
        $arr_graphic_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 10),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $graphic[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($graphic)) {
            $arr_graphic_total['total_price']=0;
            $arr_graphic_total['total_cost']=0;
            foreach ($graphic as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$graphic[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $graphic[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $graphic[$key]['unit'],
                );
                $arr_graphic[]=$item;
                $arr_graphic_total['total_price'] += $graphic[$key]['actual_price']*$graphic[$key]['unit'];
                $arr_graphic_total['total_cost'] +=$graphic[$key]['actual_unit_cost']*$graphic[$key]['unit'];
            }           
            $arr_graphic_total['gross_profit']=$arr_graphic_total['total_price']-$arr_graphic_total['total_cost'];
            if($arr_graphic_total['total_price'] != 0){
                $arr_graphic_total['gross_profit_rate']=$arr_graphic_total['gross_profit']/$arr_graphic_total['total_price'];    
            }else {
                $arr_graphic_total['gross_profit_rate']=0;
            }
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视频设计数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_film = array();
        $film = array();
        $arr_film_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 11),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $film[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($film)) {
            $arr_film_total['total_price']=0;
            $arr_film_total['total_cost']=0;
            foreach ($film as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$film[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $film[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $film[$key]['unit'],
                );
                $arr_film[]=$item;
                $arr_film_total['total_price'] += $film[$key]['actual_price']*$film[$key]['unit'];
                $arr_film_total['total_cost'] +=$film[$key]['actual_unit_cost']*$film[$key]['unit'];
            }           
            $arr_film_total['gross_profit']=$arr_film_total['total_price']-$arr_film_total['total_cost'];
            if($arr_film_total['total_price'] != 0){
                $arr_film_total['gross_profit_rate']=$arr_film_total['gross_profit']/$arr_film_total['total_price'];    
            }else {
                $arr_film_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($arr_camera_total);*/


        /*********************************************************************************************************************/
        /*取视策划师产品数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_designer = array();
        $designer = array();
        $arr_designer_total = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 17),
        ));
        $supplier_id = array();
        foreach ($supplier_id_result as $value) {
            $item = $value->id;
            $supplier_id[] = $item;
        };
        /*print_r($supplier_id);die;*/

        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("supplier_id",$supplier_id);
        $criteria1->addCondition("category=:category");
        $criteria1->params[':category']=2; 
        $supplier_product = SupplierProduct::model()->findAll($criteria1);
        /*print_r($supplier_product);*/
        $supplier_product_id = array();
        foreach ($supplier_product as $value) {
            $item = $value->id;
            $supplier_product_id[] = $item;
        };
        /*print_r($supplier_product_id);*/

        if(!empty($supplier_product_id)){
            $criteria2 = new CDbCriteria; 
            $criteria2->addInCondition("product_id",$supplier_product_id);
            $criteria2->addCondition("order_id=:order_id");
            $criteria2->params[':order_id']=$orderId; 
            $supplier_product = OrderProduct::model()->findAll($criteria2);
            /*print_r($supplier_product);*/
            foreach ($supplier_product as $value) {
                $item = array();
                $item['id'] = $value->id;
                $item['product_id'] = $value->product_id;
                $item['actual_price'] = $value->actual_price;
                $item['unit'] = $value->unit;
                $item['actual_unit_cost'] = $value->actual_unit_cost;
                $item['actual_service_ratio'] = $value->actual_service_ratio;
                $designer[] = $item;
            };
            /*print_r($camera);*/
        }
        if (!empty($designer)) {
            $arr_designer_total['total_price']=0;
            $arr_designer_total['total_cost']=0;
            foreach ($designer as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$designer[$key]['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $designer[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $designer[$key]['unit'],
                );
                $arr_designer[]=$item;
                $arr_designer_total['total_price'] += $designer[$key]['actual_price']*$designer[$key]['unit'];
                $arr_designer_total['total_cost'] +=$designer[$key]['actual_unit_cost']*$designer[$key]['unit'];
            }           
            $arr_designer_total['gross_profit']=$arr_designer_total['total_price']-$arr_designer_total['total_cost'];
            if($arr_designer_total['total_price'] != 0){
                $arr_designer_total['gross_profit_rate']=$arr_designer_total['gross_profit']/$arr_designer_total['total_price'];    
            }else {
                $arr_designer_total['gross_profit_rate']=0;
            }
            
        }

        /*print_r($designer);die;*/
        

        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_order_total = array(
            'total_price' => 0 ,
            'total_cost' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );

        

        /*print_r($order_discount);die;*/

        if(!empty($arr_wed_feast)){
            $arr_order_total['total_price'] += $arr_wed_feast['total_price'] * $order_discount['feast_discount'] * 0.1;
            $arr_order_total['total_cost'] += $arr_wed_feast['total_cost'];
        }

        if(!empty($arr_video)){
            if($this->judge_discount(9,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_video_total['total_price'];
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_video_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_video_total['total_cost'];
            }
            
        }

        if(!empty($arr_light)){
            if($this->judge_discount(8,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_light_total['total_price'];
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_light_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_light_total['total_cost'];
            }
        }

        if(!empty($arr_service_total)){
            if($this->judge_discount(3,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_service_total['total_price'];
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_service_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_service_total['total_cost'];
            }
        }

        if(!empty($arr_decoration_total)){
            if($this->judge_discount(20,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'];
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_decoration_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_decoration_total['total_cost'];
            }
        }
        if(!empty($arr_graphic_total)){
            if($this->judge_discount(10,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'];
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_graphic_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_graphic_total['total_cost'];
            }
        }
        if(!empty($arr_film_total)){
            if($this->judge_discount(11,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_film_total['total_price'];
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_film_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_film_total['total_cost'];
            }
        }
        if(!empty($arr_designer_total)){
            if($this->judge_discount(17,$orderId) == 0){
                $arr_order_total['total_price'] += $arr_designer_total['total_price'];
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }else{
                $arr_order_total['total_price'] += $arr_designer_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_order_total['total_cost'] += $arr_designer_total['total_cost'];
            }
        }

        if($order_discount['cut_price'] != 0){
            $arr_order_total['total_price'] -= $order_discount['cut_price'];
        }

        /*print_r($arr_order_total['total_price']);die;*/
        $arr_order_total['gross_profit'] = $arr_order_total['total_price'] - $arr_order_total['total_cost'];

        if($arr_order_total['total_price'] != 0){
            $arr_order_total['gross_profit_rate']=$arr_order_total['gross_profit']/$arr_order_total['total_price'];    
        }else {
            $arr_order_total['gross_profit_rate']=0;
        }

        return $arr_order_total;
    }

    public function judge_discount($type_id,$order_id){
        $order = Order::model()->findByPk($order_id); 
        $discount_range = explode(",",$order['discount_range']);
        $t=0;
        foreach ($discount_range as $key => $value) {
            if($value == $type_id){
                $t=1;
            }
        }
        return $t;
    }

    public function hotel_total_payment($hotel_id,$year,$order_status)
    {
        $criteria1 = new CDbCriteria; 
        $criteria1->addInCondition("order_status",$order_status);
        $criteria1->addCondition("staff_hotel_id=:staff_hotel_id");
        $criteria1->params[':staff_hotel_id']=$hotel_id; 
        $order = Order::model()->findAll($criteria1);
        $order_id = array();
        
        foreach ($order as $key => $value) {
            $t1 = explode(' ',$value['order_date']);
            $t2 = explode('-',$t1[0]);
            /*print_r($value['id'].$year."|".$t2[0]."</br>");*/
            if($year == $t2[0]){
                $order_id[$key] = (int)$value['id'];
            };
        }

        $criteria2 = new CDbCriteria; 
        $criteria2->addInCondition("order_id",$order_id);
        $payment = OrderPayment::model()->findAll($criteria2);
        $hotel_total_payment = 0;

        foreach ($payment as $key => $value) {
            $hotel_total_payment += $value['money'];
        };               

        return $hotel_total_payment;
    }

    public function actionFinancereport()
    {
        Yii::app()->session['account_id']=$_GET['account_id'];  
        Yii::app()->session['staff_hotel_id']=$_GET['staff_hotel_id'];  
        $company = StaffCompany::model()->findByPk($_SESSION['account_id']);     

        //********************************************************************************************
        //取当年累计销售额、销售目标
        //********************************************************************************************
        $year = date("Y");
        $order_status_deal = array(2,3,4,5,6);
        $sales=array();

        //取门店信息
        $hotel=StaffHotel::model()->findByPk($_SESSION['staff_hotel_id']);
        $sales['target']=$hotel['target'];
        $sales['deal']=number_format(($this->hotel_total_sales($_SESSION['staff_hotel_id'],$year,$order_status_deal))/10000,1);


        //********************************************************************************************
        //取当年已结算毛利
        //********************************************************************************************
        $final_profit = $this->hotel_final_profit();

        //********************************************************************************************
        //取当年已签婚礼、会议数
        //********************************************************************************************
        $data = $this->order_num_total();



        $this->render("finance_report",array(
                'sales' => $sales,
                'final_profit' => $final_profit,
                'data' => $data,
            ));
    }

    public function hotel_final_profit()
    {
        $order_basic = Order::model()->findAll(array(
                'condition' => 'account_id=:account_id && staff_hotel_id=:staff_hotel_id',
                'params' => array(
                        ':account_id' => $_SESSION['account_id'],
                        ':staff_hotel_id' => $_SESSION['staff_hotel_id'],
                    )
            ));
        $order_id =array();
        foreach ($order_basic as $key => $value) {
            $order_id[] = $value['id'];
        };
        $criteria= new CDbCriteria; 
        $criteria->addInCondition("order_id",$order_id);
        $order_final = OrderFinal::model()->findAll($criteria);
        $final_profit = 0;
        foreach ($order_final as $key => $value) {
            $final_profit += $value['final_profit'];
        }

        return sprintf("%.1f", $final_profit/10000);
    }

    public function order_num_total(){
        $order_basic = Order::model()->findAll(array(
                'condition' => 'account_id=:account_id && staff_hotel_id=:staff_hotel_id',
                'params' => array(
                        ':account_id' => $_SESSION['account_id'],
                        ':staff_hotel_id' => $_SESSION['staff_hotel_id'],
                    )
            ));
        $data = array('meeting' =>0,'wedding' =>0);
        $year = date("Y");
        foreach ($order_basic as $key => $value) {
            $t1 = explode(" ", $value['order_date']);
            $t2 = explode("-", $t1[0]);
            if( $t2[0] == $year && $value['order_status'] != 0  && $value['order_type'] == 1 ){
                $data['meeting']++; 
            }else if($t2[0] == $year && $value['order_status'] != 0  && $value['order_type'] == 2){
                $data['wedding']++; 
            }
        }
        return $data;
    }

    public function order_num()
    {
        $order_basic = Order::model()->findAll(array(
                'condition' => 'account_id=:account_id && staff_hotel_id=:staff_hotel_id',
                'params' => array(
                        ':account_id' => $_SESSION['account_id'],
                        ':staff_hotel_id' => $_SESSION['staff_hotel_id'],
                    )
            ));


        $year = date("Y");
        /*print_r($year);die;*/
        $order_month = array();
        foreach ($order_basic as $key => $value) {
            $t1 = explode(" ", $value['order_date']);
            $t2 = explode("-", $t1[0]);
            if( $t2[0] == $year && $value['order_status'] != 0 && $value['order_status'] != 1 ){
                $item = array();
                $item['month']=$t2[1];
                $item['type']=$value['order_type'];
                $order_month[] = $item;    
            };
        }
        /*print_r($order_month);die;*/
        $data=array(
                'wedding' => array(0,0,0,0,0,0,0,0,0,0,0,0),
                'meeting' => array(0,0,0,0,0,0,0,0,0,0,0,0)
            );
        
        foreach ($order_month as $key => $value) {
            if($value['month'] == '01'){
                if($value['type'] == "2"){
                    $data['wedding'][0] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][0] += 1;
                };
            }else if($value['month'] == '02'){
                if($value['type'] == "2"){
                    $data['wedding'][1] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][1] += 1;
                };
            }else if($value['month'] == '03'){
                if($value['type'] == "2"){
                    $data['wedding'][2] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][2] += 1;
                };
            }else if($value['month'] == '04'){
                if($value['type'] == "2"){
                    $data['wedding'][3] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][3] += 1;
                };
            }else if($value['month'] == '05'){
                if($value['type'] == "2"){
                    $data['wedding'][4] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][4] += 1;
                };
            }else if($value['month'] == '06'){
                if($value['type'] == "2"){
                    $data['wedding'][5] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][5] += 1;
                };
            }else if($value['month'] == '07'){
                if($value['type'] == "2"){
                    $data['wedding'][6] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][6] += 1;
                };
            }else if($value['month'] == '08'){
                if($value['type'] == "2"){
                    $data['wedding'][7] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][7] += 1;
                };
            }else if($value['month'] == '09'){
                if($value['type'] == "2"){
                    $data['wedding'][8] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][8] += 1;
                };
            }else if($value['month'] == '10'){
                if($value['type'] == "2"){
                    $data['wedding'][9] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][9] += 1;
                };
            }else if($value['month'] == '11'){
                if($value['type'] == "2"){
                    $data['wedding'][10] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][10] += 1;
                };
            }else if($value['month'] == '12'){
                if($value['type'] == "2"){
                    $data['wedding'][11] += 1;
                }else if($value['type'] == "1"){
                    $data['meeting'][11] += 1;
                };
            };     
        }

        return $data;
    }

}  

