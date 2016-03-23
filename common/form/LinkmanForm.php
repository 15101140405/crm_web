<?php

/**
 * Class LinkmanForm
 * Protect info
 */
class LinkmanForm extends InitForm
{
 
    public function getlinkmanList($accountId)
    {
        $result = array();
        // $types = $this->getSupplierTypes($accountId);

        $Linkman = OrderMeetingCompanyLinkman::model()->findAll(array(
            "condition" => "company_id=:company_id",
            "params" => array(
                ":company_id" => $_GET['company_id'],
            ),
        ));

        foreach ($Linkman as $Linkman) {
            $item = array();
            $item["account_id"] = $accountId;
            $item["company_id"] = $Linkman->company_id;
            // $item["order_id"] = $order_id;
            $item["linkman_name"] = $Linkman->name;
            $item["linkman_phone"] = $Linkman->telephone;
            $item["linkman_id"] = $Linkman->id;
 
            $result[] = $item;
        }

        return $result;
    }

 

    public function LinkmanInsert($arr){
    
        $model = new OrderMeetingCompanyLinkman();  
        $model->account_id=$arr['account_id'];
        $model->name=$arr['linkman_name'];
        $model->telephone=$arr['linkman_phone'
        ];
        $model->company_id=$arr['company_id'];
        $model->update_time = date('Y-m-d');

        if($model->save()>0){
            $arr['success']='1';
            return $arr;
        }else{
            /*var_dump($model);*/
            $arr['success']='0';
            return $arr;
        }

    }
     

}
