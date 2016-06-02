<?php
class CMailFile
{ 

var $subject; 
var $addr_to; 
var $text_body; 
var $text_encoded; 
var $mime_headers; 
var $mime_boundary = "--==================_846811060==_"; 
var $smtp_headers; 

function CMailFile($subject,$to,$from,$msg,$filename,$downfilename,$mimetype = "application/octet-stream",$mime_filename = false) { 
$this->subject = $subject; 
$this->addr_to = $to; 
$this->smtp_headers = $this->write_smtpheaders($from); 
$this->text_body = $this->write_body($msg); 
$this->text_encoded = $this->attach_file($filename,$downfilename,$mimetype,$mime_filename); 
$this->mime_headers = $this->write_mimeheaders($filename, $mime_filename); 
} 

function attach_file($filename,$downfilename,$mimetype,$mime_filename) { 
$encoded = $this->encode_file($filename); 
if ($mime_filename) $filename = $mime_filename; 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-type: " . $mimetype . "; name=\"$filename\";\n"; 
$out = $out . "Content-Transfer-Encoding: base64\n"; 
$out = $out . "Content-disposition: attachment; filename=\"$downfilename\"\n\n"; 
$out = $out . $encoded . "\n"; 
$out = $out . "--" . $this->mime_boundary . "--" . "\n"; 
return $out; 
} 

function encode_file($sourcefile) { 
if (is_readable($sourcefile)) { 
$fd = fopen($sourcefile, "r"); 
$contents = fread($fd, filesize($sourcefile)); 
$encoded = chunk_split(base64_encode($contents)); 
fclose($fd); 
} 
return $encoded; 
} 

function sendfile() { 
$headers = $this->smtp_headers . $this->mime_headers; 
$message = $this->text_body . $this->text_encoded; 
mail($this->addr_to,$this->subject,$message,$headers); 
} 

function write_body($msgtext) { 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-Type: text/plain; charset=\"us-ascii\"\n\n"; 
$out = $out . $msgtext . "\n"; 
return $out; 
} 

function write_mimeheaders($filename, $mime_filename) { 
if ($mime_filename) $filename = $mime_filename; 
$out = "MIME-version: 1.0\n"; 
$out = $out . "Content-type: multipart/mixed; "; 
$out = $out . "boundary=\"$this->mime_boundary\"\n"; 
$out = $out . "Content-transfer-encoding: 7BIT\n"; 
$out = $out . "X-attachments: $filename;\n\n"; 
return $out; 
} 

function write_smtpheaders($addr_from) { 
$out = "From: $addr_from\n"; 
$out = $out . "Reply-To: $addr_from\n"; 
$out = $out . "X-Mailer: PHP3\n"; 
$out = $out . "X-Sender: $addr_from\n"; 
return $out; 
} 
} 

class PrintController extends InitController
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

    public function actionDesignbill()
    {
        $Order = Order::model()->findByPk($_POST['order_id']);
        $date = explode(" ",$Order['order_date']);
        $t = new StaffForm();
        $wed = OrderWedding::model()->find(array(
            'condition' => 'order_id=:order_id',
            'params' => array(
                ':order_id' => $_POST['order_id']
            )
        ));


        $order_data = array();
        $order_data['id']='W'.$_POST['order_id'].'-'.$date[0];
        $order_data['feast_discount']=$Order['feast_discount'];
        $order_data['other_discount']=$Order['other_discount'];
        $order_data['cut_price']=$Order['cut_price'];
        $order_data['designer_name']=$t->getName($Order['designer_id']);
        $order_data['groom_name']=$wed['groom_name'];
        $order_data['groom_phone']=$wed['groom_phone'];
        $order_data['bride_name']=$wed['bride_name'];
        $order_data['bride_phone']=$wed['bride_phone'];

        //print_r($order_data);die;

        $orderId = $_POST['order_id'];
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
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01)*$order_discount['feast_discount']*0.1,
                'total_cost' => $wed_feast[0]['actual_unit_cost']*$wed_feast[0]['unit'],
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01,
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']*0.01)/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']*0.01)),
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
                $ref_pic_url = "";
                $t = explode(".", $supplier_product2['ref_pic_url']);
                if(isset($t[0]) && isset($t[1])){
                    $ref_pic_url = "http://file.cike360.com".$t[0]."_sm.".$t[1];    
                };
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $decoration[$key]['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $decoration[$key]['unit'],
                    'ref_pic_url' => $ref_pic_url,
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



        /*========================================================================================================
        ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊界面渲染＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
        ========================================================================================================*/




$html = '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
</head>
<body>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>

<table class="tftable" border="1">
<tr><th colspan="3">基本信息</th></tr>
<tr><td width="10%">订单编号</td><td colspan="2" width="90%">'.$order_data["id"].'</td></tr>
<tr><td width="10%">新郎信息</td><td width="50%">'.$order_data["groom_name"].'</td><td width="40%">'.$order_data["groom_phone"].'</td></tr>
<tr><td width="10%">新娘信息</td><td width="50%">'.$order_data["bride_name"].'</td><td width="40%">'.$order_data["bride_phone"].'</td></tr>
<tr><td width="10%">策划师</td><td colspan="2" width="90%">'.$order_data["designer_name"].'</td></tr>
<tr><td width="10%">婚宴折扣</td><td colspan="2" width="90%">'.$order_data["feast_discount"].'</td></tr>
<tr><td width="10%">婚礼折扣</td><td colspan="2" width="90%">'.$order_data["other_discount"].'</td></tr>
<tr><td width="10%">抹零</td><td colspan="2" width="90%">'.$order_data["cut_price"].'</td></tr>
<tr><td width="10%">订单总价</td><td colspan="2" width="90%">'.$arr_order_total['total_price'].'</td></tr>
</table>

';

/*<!-- 婚宴 -->*/
if (!empty($arr_wed_feast)) {

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<tr><td width="10%" rowspan = "5">婚宴</td><td width="4%">1</td><td width="12%">'.$arr_wed_feast['name'].'</td><td width="20%"></td><td width="4%">'.$arr_wed_feast['table_num'].'</td><td width="9%">'.$arr_wed_feast['unit'].'</td><td width="18%">'.$arr_wed_feast['unit_price'].'</td><td width="23%"> </td></tr>
</table>';


};


/*<!-- 灯光 -->*/

if (!empty($arr_light)) {
$i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

    foreach ($arr_light as $key => $value) {
        foreach ($value as $key1 => $value1) {
            $light[$key1] = $value1;
        }   


        if($i==1){
$html .= '<tr><td width="10%" rowspan = "'.count($arr_light).'">灯光</td><td width="4%">'.$i.'</td><td width="12%">'.$light['name'].'</td><td width="20%"></td><td width="4%">'.$light['amount'].'</td><td width="4%">'.$light['unit'].'</td><td width="23%">'.$light['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$light['name'].'</td><td width="20%"></td><td width="4%">'.$light['amount'].'</td><td width="4%">'.$light['unit'].'</td><td width="23%">'.$light['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .= '</table>';
};


/*<!-- 视频 -->*/

if (!empty($arr_video)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_video as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $video[$key1] = $value1;
            }
            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_video).'">视频</td><td width="4%">'.$i.'</td><td width="12%">'.$video['name'].'</td><td width="20%"></td><td width="4%">'.$video['amount'].'</td><td width="4%">'.$video['unit'].'</td><td width="23%">'.$video['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$video['name'].'</td><td width="20%"></td><td width="4%">'.$video['amount'].'</td><td width="4%">'.$video['unit'].'</td><td width="23%">'.$video['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';

  
    };

/*<!-- 主持人 -->*/

if (!empty($arr_host)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_host as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $host[$key1] = $value1;
            }
            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_host).'">主持人</td><td width="4%">'.$i.'</td><td width="12%">'.$host['name'].'</td><td width="20%"></td><td width="4%">'.$host['amount'].'</td><td width="4%">'.$host['unit'].'</td><td width="23%">'.$host['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$host['name'].'</td><td width="20%"></td><td width="4%">'.$host['amount'].'</td><td width="4%">'.$host['unit'].'</td><td width="23%">'.$host['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';
 
    };


/*<!-- 摄像 -->*/

if (!empty($arr_camera)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';
        foreach ($arr_camera as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $camera[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_camera).'">摄像</td><td width="4%">'.$i.'</td><td width="12%">'.$camera['name'].'</td><td width="20%"></td><td width="4%">'.$camera['amount'].'</td><td width="4%">'.$camera['unit'].'</td><td width="23%">'.$camera['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">.'.$camera['name'].'</td><td width="20%"></td><td width="4%">'.$camera['amount'].'</td><td width="4%">'.$camera['unit'].'</td><td width="23%">'.$camera['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 摄影 -->*/

    if (!empty($arr_photo)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_photo as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $photo[$key1] = $value1;
        }
        if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_photo).'">摄影</td><td width="4%">'.$i.'</td><td width="12%">'.$photo['name'].'</td><td width="20%"></td><td width="4%">'.$photo['amount'].'</td><td width="4%">'.$photo['unit'].'</td><td width="23%">'.$photo['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$photo['name'].'</td><td width="20%"></td><td width="4%">'.$photo['amount'].'</td><td width="4%">'.$photo['unit'].'</td><td width="23%">'.$photo['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 化妆 -->*/

    if (!empty($arr_makeup)) {
    $i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_makeup as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $makeup[$key1] = $value1;
            }

            if($i==1){

$html .= '<tr><td width="10%" rowspan = "'.count($arr_makeup).'">化妆</td><td width="4%">'.$i.'</td><td width="12%">'.$makeup['name'].'</td><td width="20%"></td><td width="4%">'.$makeup['amount'].'</td><td width="4%">'.$makeup['unit'].'</td><td width="23%">'.$makeup['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$makeup['name'].'</td><td width="20%"></td><td width="4%">'.$makeup['amount'].'</td><td width="4%">'.$makeup['unit'].'</td><td width="23%">'.$makeup['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .= '</table>';

 
    };


/*<!-- 其他 -->*/

    if (!empty($arr_other)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_other as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $other[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_other).'">其他</td><td width="4%">'.$i.'</td><td width="12%">'.$other['name'].'</td><td width="20%"></td><td width="4%">'.$other['amount'].'</td><td width="4%">'.$other['unit'].'</td><td width="23%">'.$other['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$other['name'].'</td><td width="20%"></td><td width="4%">'.$other['amount'].'</td><td width="4%">'.$other['unit'].'</td><td width="23%">'.$other['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';

    };


/*<!-- 场地布置 -->*/

    if (!empty($arr_decoration)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_decoration as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $decoration[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_decoration).'">场地布置</td><td width="4%">'.$i.'</td><td width="12%">'.$decoration['name'].'</td><td width="20%"></td><td width="4%">'.$decoration['amount'].'</td><td width="4%">'.$decoration['unit'].'</td><td width="23%">'.$decoration['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$decoration['name'].'</td><td width="20%"></td><td width="4%">'.$decoration['amount'].'</td><td width="4%">'.$decoration['unit'].'</td><td width="23%">'.$decoration['unit_price'].'</td><td width="23%"><img style="height:150px" src="'.$decoration['ref_pic_url'].'"></img></td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 平面设计 -->*/

    if (!empty($arr_graphic)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_graphic as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $graphic[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_graphic).'">平面设计</td><td width="4%">'.$i.'</td><td width="12%">'.$graphic['name'].'</td><td width="20%"></td><td width="4%">'.$graphic['amount'].'</td><td width="4%">'.$graphic['unit'].'</td><td width="23%">'.$graphic['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$graphic['name'].'</td><td width="20%"></td><td width="4%">'.$graphic['amount'].'</td><td width="4%">'.$graphic['unit'].'</td><td width="23%">'.$graphic['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 视频设计 -->*/

    if (!empty($arr_film)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_film as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $film[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_film).'">视频设计</td><td width="4%">'.$i.'</td><td width="12%">'.$film['name'].'</td><td width="20%"></td><td width="4%">'.$film['amount'].'</td><td width="4%">'.$film['unit'].'</td><td width="23%">'.$film['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$film['name'].'</td><td width="20%"></td><td width="4%">'.$film['amount'].'</td><td width="4%">'.$film['unit'].'</td><td width="23%">'.$film['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>';


    };


/*<!-- 策划费&杂费 -->*/
    if (!empty($arr_designer)) {
    $i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_designer as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $designer[$key1] = $value1;
            }

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_designer).'">策划费&杂费</td><td width="4%">'.$i.'</td><td width="12%">'.$designer['name'].'</td><td width="20%"></td><td width="4%">'.$designer['amount'].'</td><td width="4%">'.$designer['unit'].'</td><td width="23%">'.$designer['unit_price'].'</td><td width="23%"> </td></tr>';

            $i++;
            }else{

$html .='<tr><td width="4%">.'.$i.'</td><td width="12%">'.$designer['name'].'</td><td width="20%"></td><td width="4%">'.$designer['amount'].'</td><td width="4%">'.$designer['unit'].'</td><td width="23%">'.$designer['unit_price'].'</td><td width="23%"> </td></tr>';
            $i++;
            }
        };
$html .='</table>';

  
    };





$html .='</body>
</html>';

        //$fp = fopen("billtable".$_SESSION['userid'].".html","w");
        $fp = fopen("billtable.html","w");
        if(!$fp)
        {
        echo "System Error";
        exit();
        }
        else {
        fwrite($fp,$html);
        fclose($fp);
        echo "Success";
        }



        /*require_once "../library/email.class.php";
        //******************** 配置信息 ********************************
        $smtpserver = "smtp.qq.com";//SMTP服务器
        $smtpserverport =25;//SMTP服务器端口
        $smtpusermail = "2837745713@qq.com";//SMTP服务器的用户邮箱
        $smtpemailto = "zhangsiheng0820@126.com";//发送给谁
        $smtpuser = "2837745713";//SMTP服务器的用户帐号
        $smtppass = "xsxn1183";//SMTP服务器的用户密码
        $mailtitle = "报价单";//邮件主题
        $mailcontent = $html;//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = true;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

        echo   '<head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>报价单</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black">
                <meta name="format-detection" content="telephone=no">
                <link href="css/base.css" rel="stylesheet" type="text/css"/>
                <link href="css/style.css" rel="stylesheet" type="text/css"/>
                </head>
                <body>';

        echo "<div style='width:300px; margin:36px auto;'>";
        if($state==""){
            echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
            echo "<a href='index.html'>点此返回</a>";
            exit();
        }
        echo "恭喜！邮件发送成功！！";
        echo "<a href='index.html'>点此返回</a>";
        echo "</div></body>";*/

        
        

        //发送邮件 

        //主題 
        $subject = "test send email"; 

        //收件人 
        //$sendto = 'trhyyy@hpeprint.com'; 
        $sendto = $_POST['email']; 
        echo $_POST['email'];

        //發件人 
        //$replyto = '2837745713@qq.com'; 
        //$replyto = 'hunlicehuashi2016@126.com'; 
        $replyto = 'zhangsiheng0820@126.com'; 

        //內容 
        $message = ""; 

        //附件 
        //$filename = "billtable".$_SESSION['userid'].".html"; 
        $filename = "billtable.html"; 
        //附件類別 
        //$mimetype = "billtable".$_SESSION['userid'].".html";  
        $mimetype = "billtable.html";  
        echo "1";

        $mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
        echo "2";
        $mailfile->sendfile(); 
        echo "3";
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

    public function actionMeetingbill()
    {
        $_POST['order_id'] = 11;
        $_POST['email'] = 'zhangsiheng0820@126.com';
        $orderId = $_POST['order_id'];
        $supplier_product_id = array();
        $wed_feast = array();
        $arr_wed_feast = array();

        $order_discount = Order::model()->find(array(
            "condition" => "id = :id",
            "params" => array(":id" => $orderId),
        ));

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
            $criteria1->params[':category']=1; 
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
                'total_price' => $wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']/100),
                'gross_profit' => ($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']/100,
                'gross_profit_rate' => (($wed_feast[0]['actual_price']-$wed_feast[0]['actual_unit_cost'])*$wed_feast[0]['unit']+$wed_feast[0]['actual_price']*$wed_feast[0]['unit']*$wed_feast[0]['actual_service_ratio']/100)/($wed_feast[0]['actual_price']*$wed_feast[0]['unit']*(1+$wed_feast[0]['actual_service_ratio']/100)),
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_wed_feast);*/


        /*********************************************************************************************************************/
        /*取场地费数据*/
        /*********************************************************************************************************************/
        $changdi_fee = array();
        $arr_changdi_fee = array();
        $supplier_id_result = Supplier::model()->findAll(array(
            "condition" => "type_id = :type_id",
            "params" => array(":type_id" => 19),
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
            $criteria1->params[':category']=1; 
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
                $changdi_fee[] = $item;
            };
            /*print_r($changdi_fee);*/
        }
        
        if(!empty($changdi_fee)){
            $criteria3 = new CDbCriteria; 
            $criteria3->addCondition("id=:id");
            $criteria3->params[':id']=$changdi_fee[0]['product_id']; 
            $supplier_product2 = SupplierProduct::model()->find($criteria3);
            /*print_r($supplier_product2);*/
            $arr_changdi_fee = array(
                'name' => $supplier_product2['name'],
                'unit_price' => $changdi_fee[0]['actual_price'],
                'unit' => $supplier_product2['unit'],
                'amount' => $changdi_fee[0]['unit'],
                'total_price' => $changdi_fee[0]['actual_price']*$changdi_fee[0]['unit'],
                'gross_profit' => ($changdi_fee[0]['actual_price']-$changdi_fee[0]['actual_unit_cost'])*$changdi_fee[0]['unit'],
                'gross_profit_rate' => (($changdi_fee[0]['actual_price']-$changdi_fee[0]['actual_unit_cost'])*$changdi_fee[0]['unit'])/($changdi_fee[0]['actual_price']*$changdi_fee[0]['unit']),
                /*'table_num' => $wed_feast[0]['unit'],
                'service_charge_ratio' => $wed_feast[0]['actual_service_ratio'],*/
                /*'remark' => $wed_feast['']*/
            );
        }
        /*print_r($arr_changdi_fee);die;*/



        /*********************************************************************************************************************/
        /*取灯光数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_light = array();
        $light = array();
        $arr_light_total = array();
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
        $criteria1->params[':category']=1; 
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
        $arr_light_total['total_price']=0;
        $arr_light_total['total_cost']=0;
        if (!empty($light)) {
            foreach ($light as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$value['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $value['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $value['unit'],
                );
                $arr_light[]=$item;
                $arr_light_total['total_price'] += $value['actual_price']*$value['unit'];;
                $arr_light_total['total_cost'] +=$value['actual_unit_cost']*$value['unit'];
            }           
            $arr_light_total['gross_profit']=$arr_light_total['total_price']-$arr_light_total['total_cost'];
            if($arr_light_total['total_price'] != 0){
                $arr_light_total['gross_profit_rate']=$arr_light_total['gross_profit']/$arr_light_total['total_price'];
            }else{
                $arr_light_total['gross_profit_rate']=0;
            };
            
        }

        /*print_r($arr_light);die;*/

        

        /*********************************************************************************************************************/
        /*取视频数据*/
        /*********************************************************************************************************************/
        $supplier_product_id = array();
        $arr_video = array();
        $video = array();
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
        $criteria1->params[':category']=1; 
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
            /*$video = array();*/
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
        $arr_video_total['total_price']=0;
        $arr_video_total['total_cost']=0;
        if (!empty($video)) {
            foreach ($video as $key => $value) {
                $criteria3 = new CDbCriteria; 
                $criteria3->addCondition("id=:id");
                $criteria3->params[':id']=$value['product_id']; 
                $supplier_product2 = SupplierProduct::model()->find($criteria3);

                
                
                $item= array(
                    'name' => $supplier_product2['name'],
                    'unit_price' => $value['actual_price'],
                    'unit' => $supplier_product2['unit'],
                    'amount' => $value['unit'],
                );
                $arr_video[]=$item;
                $arr_video_total['total_price'] += $value['actual_price']*$value['unit'];;
                $arr_video_total['total_cost'] +=$value['actual_unit_cost']*$value['unit'];
            }           
            $arr_video_total['gross_profit']=$arr_video_total['total_price'];-$arr_video_total['total_cost'];
            $arr_video_total['gross_profit_rate']=$arr_video_total['gross_profit']/$arr_video_total['total_price'];
        }

        /*print_r($arr_video_total);*/

        

        

        /*********************************************************************************************************************/
        /*取订单日期、统筹师数据*/
        /*********************************************************************************************************************/

        $criteria3 = new CDbCriteria; 
        $criteria3->addCondition("order_id=:order_id");
        $criteria3->params[':order_id']=$orderId; 
        $order_meeting = OrderMeeting::model()->find($criteria3);
        /*print_r($order_data);die;*/

        $company_linkman = OrderMeetingCompanyLinkman::model()->findByPk($order_meeting['company_linkman_id']);





        /*********************************************************************************************************************/
        /*计算订单总价*/
        /*********************************************************************************************************************/
        $arr_total = array(
            'total_price' => 0 ,
            'gross_profit' => 0 ,
            'gross_profit_rate' => 0 ,
        );
        if(!empty($arr_wed_feast)){
            $arr_total['total_price'] += $arr_wed_feast['total_price'] * $order_discount['feast_discount'] * 0.1;
            $arr_total['gross_profit'] += $arr_wed_feast['gross_profit'];
        }

        if(!empty($arr_changdi_fee)){
            if($this->judge_discount(19,$orderId) == 0){
                $arr_total['total_price'] += $arr_changdi_fee['total_price'];
                $arr_total['gross_profit'] += $arr_changdi_fee['gross_profit'];
            }else{
                $arr_total['total_price'] += $arr_changdi_fee['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_changdi_fee['gross_profit'];
            }
        }

        if(!empty($arr_video)){
            if($this->judge_discount(9,$orderId) == 0){
                $arr_total['total_price'] += $arr_video_total['total_price'];
                $arr_total['gross_profit'] += $arr_video_total['gross_profit'];
            }else{
                $arr_total['total_price'] += $arr_video_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_video_total['gross_profit'];
            }
        }

        if(!empty($arr_light)){
            if($this->judge_discount(8,$orderId) == 0){
                $arr_total['total_price'] += $arr_light_total['total_price'];
                $arr_total['gross_profit'] += $arr_light_total['gross_profit'];
            }else{
                $arr_total['total_price'] += $arr_light_total['total_price'] * $order_discount['other_discount'] * 0.1;
                $arr_total['gross_profit'] += $arr_light_total['gross_profit'];
            }
        }

        if($order_discount['cut_price'] != 0){
            $arr_total['total_price'] -= $order_discount['cut_price'];
        }

        if($arr_total['total_price'] != 0){
            $arr_total['gross_profit_rate'] = $arr_total['gross_profit']/$arr_total['total_price'];    
        }

        /*********************************************************************************************************************/
        /*查询订单信息*/
        /*********************************************************************************************************************/
        $order_data = Order::model()->findByPk($orderId);
        $planner = Staff::model()->findByPk($order_data['planner_id']);
        /*print_r($order_data);die;*/

        /*========================================================================================================
        ＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊界面渲染＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊＊
        ========================================================================================================*/




$html = '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
</head>
<body>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>

<table class="tftable" border="1">
<tr><th colspan="3">基本信息</th></tr>
<tr><td width="10%">订单编号</td><td colspan="2" width="90%">'.$order_data["id"].'</td></tr>
<tr><td width="10%">客户名称</td><td colspan="2" width="50%">'.$order_data['order_name'].'</td></tr>
<tr><td width="10%">联系人</td><td width="50%">'.$company_linkman['name'].'</td><td width="40%">'.$company_linkman['telephone'].'</td></tr>
<tr><td width="10%">统筹师</td><td colspan="2" width="90%">'.$planner['name'].'</td></tr>
<tr><td width="10%">餐饮折扣</td><td colspan="2" width="90%">'.$order_data["feast_discount"].'</td></tr>
<tr><td width="10%">其他折扣</td><td colspan="2" width="90%">'.$order_data["other_discount"].'</td></tr>
<tr><td width="10%">抹零</td><td colspan="2" width="90%">'.$order_data["cut_price"].'</td></tr>
<tr><td width="10%">订单总价</td><td colspan="2" width="90%">'.$arr_total['total_price'].'</td></tr>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';

/*<!-- 会议餐 -->*/
if (!empty($arr_wed_feast)) {

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<tr><td width="10%" rowspan = "5">婚宴</td><td width="4%">1</td><td width="12%">'.$arr_wed_feast['name'].'</td><td width="20%"></td><td width="4%">'.$arr_wed_feast['table_num'].'</td><td width="9%">'.$arr_wed_feast['unit'].'</td><td width="18%">'.$arr_wed_feast['unit_price'].'</td><td width="23%"> </td></tr>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';


};

/*<!-- 场地费 -->*/

if (!empty($arr_changdi_fee)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';
            

$html .='<tr><td width="10%" rowspan = "1">场地费</td><td width="4%">'.$i.'</td><td width="12%">'.$arr_changdi_fee['name'].'</td><td width="20%"></td><td width="4%">'.$arr_changdi_fee['amount'].'</td><td width="4%">'.$arr_changdi_fee['unit'].'</td><td width="23%">'.$arr_changdi_fee['unit_price'].'</td><td width="23%"> </td></tr>';


$html .='</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';
 
    };

/*<!-- 灯光 -->*/
/*print_r($arr_light);die;*/
if (!empty($arr_light)) {
$i=1;

$html .= '<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

    foreach ($arr_light as $key => $value) {

        if($i==1){
$html .= '<tr><td width="10%" rowspan = "'.count($arr_light).'">灯光</td><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';

        $i++;
        }else{

$html .= '<tr><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .= '</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';
};


/*<!-- 视频 -->*/
/*print_r($arr_video);die;*/
if (!empty($arr_video)) {
$i=1;

$html .='<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>';

        foreach ($arr_video as $key => $value) {

            if($i==1){

$html .='<tr><td width="10%" rowspan = "'.count($arr_video).'">视频</td><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }else{

$html .='<tr><td width="4%">'.$i.'</td><td width="12%">'.$value['name'].'</td><td width="20%"></td><td width="4%">'.$value['amount'].'</td><td width="4%">'.$value['unit'].'</td><td width="23%">'.$value['unit_price'].'</td><td width="23%"> </td></tr>';
        $i++;
        }
    };
$html .='</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>';

  
    };


$html .='</body>
</html>';

/*echo $html;die;*/

        //$fp = fopen("billtable".$_SESSION['userid'].".html","w");
        $fp = fopen("billtable.html","w");
        if(!$fp)
        {
        echo "System Error";
        exit();
        }
        else {
        fwrite($fp,$html);
        fclose($fp);
        echo "Success";
        }



        /*require_once "../library/email.class.php";
        //******************** 配置信息 ********************************
        $smtpserver = "smtp.qq.com";//SMTP服务器
        $smtpserverport =25;//SMTP服务器端口
        $smtpusermail = "2837745713@qq.com";//SMTP服务器的用户邮箱
        $smtpemailto = "zhangsiheng0820@126.com";//发送给谁
        $smtpuser = "2837745713";//SMTP服务器的用户帐号
        $smtppass = "xsxn1183";//SMTP服务器的用户密码
        $mailtitle = "报价单";//邮件主题
        $mailcontent = $html;//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = true;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

        echo   '<head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>报价单</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black">
                <meta name="format-detection" content="telephone=no">
                <link href="css/base.css" rel="stylesheet" type="text/css"/>
                <link href="css/style.css" rel="stylesheet" type="text/css"/>
                </head>
                <body>';

        echo "<div style='width:300px; margin:36px auto;'>";
        if($state==""){
            echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
            echo "<a href='index.html'>点此返回</a>";
            exit();
        }
        echo "恭喜！邮件发送成功！！";
        echo "<a href='index.html'>点此返回</a>";
        echo "</div></body>";*/

        
        

        //发送邮件 

        //主題 
        $subject = "test send email"; 

        //收件人 
        //$sendto = 'trhyyy@hpeprint.com'; 
        $sendto = $_POST['email']; 
        echo $_POST['email'];

        //發件人 
        //$replyto = '2837745713@qq.com'; 
        $replyto = 'zhangsiheng0820@126.com'; 

        //內容 
        $message = ""; 

        //附件 
        //$filename = "billtable".$_SESSION['userid'].".html"; 
        $filename = "billtable.html"; 
        //附件類別 
        //$mimetype = "billtable".$_SESSION['userid'].".html";  
        $mimetype = "billtable.html";  
        echo "1";

        $mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
        echo "2";
        $mailfile->sendfile(); 
        echo "3";   
    }    

}

