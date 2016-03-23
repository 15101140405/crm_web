<?php

/**
 * Class LayoutForm
 * Protect info
 */
class LayoutForm extends InitForm
{
 
    public function getlayoutList($accountId,$company_id,$linkman_id)
    {
        $result = array();
        // $types = $this->getSupplierTypes($accountId);

        $Layout = OrderMeetingLayout::model()->findAll(array(
            "condition" => "account_id=:account_id",
            "params" => array(
                ":account_id" => $accountId,
            ),
        ));
        // var_dump($Layout['0']['id']);die;
        // $id = $Layout['0']['id'];
        var_dump($Layout);
        foreach ($Layout as $Layout) {
            $item = array();
            $item["account_id"] = $accountId;
            $item["company_id"] = $company_id;
            $item["linkman_name"] = $linkman_id;
            $item["layout_id"] = $Layout->id;
            // $item["layout_id"] = $id;
            $item["image"] = $Layout->image;
            $item["title"] = $Layout->title;
 
            $result[] = $item;
        }

        return $result;
    }

    public function LinkmanInsert($arr){
       
        $OrderMeetingCompanyLinkman = OrderMeetingCompanyLinkman::model()->find(
            array('condition'=>"account_id=:account_id",
                  'params'=>array(
                    'account_id'=>$arr['account_id']
                    ),
                )
            );
        $OrderMeetingCompany = OrderMeetingCompany::model()->find(
            array('condition'=>"account_id=:account_id",
                  'params'=>array(
                    'account_id'=>$arr['account_id']
                    ),
                )
            );
        $model = new OrderMeetingCompanyLinkman();  
        $model->account_id=$arr['account_id'];
        $model->name=$arr['linkman_name'];
        $model->telephone=$arr['linkman_phone'];
        $model->company_id=$OrderMeetingCompany['id'];
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
