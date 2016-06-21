<?php

/**
 * Class CompanyForm
 * Protect info
 */
class CompanyForm extends InitForm
{
 
    public function getcompanyList($accountId,$order_id)
    {
        $result = array();
        // $types = $this->getSupplierTypes($accountId);

        /*$Company = OrderMeetingCompany::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                ":account_id" => $accountId,
            ),

            'order'=>'update_time', 
        ));*/

        $criteria3 = new CDbCriteria; 
        $criteria3->addCondition("account_id = :account_id && hotel_id = :hotel_id");    
        $criteria3->params[':account_id']=$_SESSION['account_id'];
        $criteria3->params[':hotel_id']=$_SESSION['staff_hotel_id'];
        $criteria3->order = 'update_time DESC'; 
        $Company = OrderMeetingCompany::model()->findAll($criteria3);  

        foreach ($Company as $Company) {
            $item = array();
            $item["account_id"] = $accountId;
            $item["order_id"] = $order_id;
            $item["company_id"] =  $Company->id;
            $item["company_name"] = $Company->company_name;
 
            $result[] = $item;
        }

        return $result;
    }

    public function companyInsert($arr){
       
        $OrderMeetingCompany = OrderMeetingCompany::model()->find(
            array('condition'=>"account_id=:account_id",
                  'params'=>array(
                    'account_id'=>$arr['account_id']
                    ),
                )
            );
        $model = new OrderMeetingCompany();  
        $model->account_id=$arr['account_id'];
        $model->company_name=$arr['company_name'];
        $model->hotel_id=$arr['hotel_id'];
        $model->update_time = date('Y-m-d');

        if($model->save()>0){
            $arr['success']='1';
            return $arr;
        }else{
            var_dump($model);
            $arr['success']='0';
            return $arr;
        }

    }
     

}
