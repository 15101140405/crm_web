<?php

/**
 * Class ReportForm
 * Report info
 */
class ReportForm  extends InitForm
{
    public function test(){
        $test = 'eee';
        return $test;
    }
    //周报
    public function  getOrderWeek($account_id,$hotel_id,$order_type,$date,$unix_time){
            $time_now = $unix_time;
            $now_date = date('Y-m-d 00:00:00',$time_now);
            $base_time = date('Y-m-d H:i:s',strtotime($now_date)-($date-1)*24*3600);
            $arr_num['base_time'] =  $base_time;
             $arr_num['date'] =  $date;
            for($i=1;$i<=$date;$i++){
                $base_time1 = date('Y-m-d H:i:s',(strtotime($base_time)+($i-1)*24*3600));
                $to_time = date('Y-m-d H:i:s',(strtotime($base_time)+$i*24*3600));
                $time_arr = array( //取数时间范围
                    '0'=>$base_time1,
                    '1'=>$to_time
                );
                $arr_every[] = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
            }
            $sum = 0;//本星期总数
             foreach($arr_every as $key=>$value){
                 $sum += $value;
            }
        //上星期总数
        $base_time2 = date('Y-m-d H:i:s',(strtotime($base_time)-7*24*3600));
        $to_time = $base_time;
        $time_arr = array( //取数时间范围
            '0'=>$base_time2,
            '1'=>$to_time
        );
        $last_week = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
        //上星期总数over
       // 比列计算
        if($last_week==0){
            $ratio = ($sum*100);
            $ratio .= '%';
            $rate_type = 0;
        }else{
            $ratio = ($sum-$last_week)*100/$last_week;
            if($ratio>0){
                $ratio =round($ratio).'%';
                $rate_type = 0;
            }else{
                $ratio = abs($ratio);
                $ratio = round($ratio).'%';
                $rate_type = 1;
            }
        }

        $arr_num['last_week'] =  $last_week;
        $arr_num['every_num'] =  $arr_every;
        $arr_num['total_num']=$sum;
        $arr_num['rate']=$ratio;
        $arr_num['rate_type']= $rate_type;
        return $arr_num;
    }

    //月报
    public function getOrderMonth($account_id,$hotel_id,$order_type,$unix_time){
        //$today = date('Y-m-d H:i:s',strtotime('Today')); //今日时间
        //$month = date('n');  //月份
        //        //$now =   date('Y-m-d H:i:s',time());//现在
        $day =   date('d',$unix_time); //天数
        $base_time =date('Y-m-01 00:00:00',$unix_time);//月份第一天
        for($i=1;$i<=$day;$i++){
             $begin_time =   date('Y-m-d H:i:s',(strtotime($base_time)+($i-1)*24*3600));
            $to_time =  date('Y-m-d H:i:s',(strtotime($base_time)+$i*24*3600));
            $time_arr = array( //取数时间范围
                '0'=>$begin_time,
                '1'=>$to_time
            );
            $arr_every[] = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
        }
        $sum = 0;
        foreach($arr_every as $key=>$value){
            $sum += $value;
        }
        //上个月总数---------------------------------------
        $begin_time=date('Y-m-01 00:00:00', strtotime($base_time)-$day*24*3600);
        $to_time=$base_time;
        $time_arr = array( //取数时间范围
            '0'=>$begin_time,
            '1'=>$to_time
        );
        $last_month = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
       $arr_num['last_month'] =  $last_month;
        // 比列计算
        if($last_month==0){
            $ratio = ($sum*100);
            $ratio .= '%';
            $rate_type = 0;
        }else{
            $ratio = ($sum-$last_month)*100/$last_month;
            if($ratio>0){
                $ratio =round($ratio).'%';
                $rate_type = 0;
            }else{
                $ratio = abs($ratio);
                $ratio = round($ratio).'%';
                $rate_type = 1;
            }
        }
        $arr_num['every_num'] =  $arr_every;
        $arr_num['total_num']=$sum;
        $arr_num['rate']=$ratio;
        $arr_num['rate_type']= $rate_type;
        return $arr_num;

    }
    //季报
    public function getOrderQuarter($account_id,$hotel_id,$order_type,$unix_time){
        $month =   date('n',$unix_time); //天数
       // $base_time =date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));//月份第一天
        //所属季度
        $quarter_one   = array('1','2','3');
        $quarter_two   = array('4','5','6');
        $quarter_three = array('7','8','9');
        $quarter_four   = array('10','11','12');

        if(in_array($month,$quarter_one)){//一季度
            for($i=1;$i<=3;$i++){
                $begin_time = date('Y-0'.$i.'-01 00:00:00',$unix_time);
                $to_time   = date('Y-0'.($i+1).'-01 00:00:00',$unix_time);
                $time_arr = array( //取数时间范围
                    '0'=>$begin_time,
                    '1'=>$to_time
                );
                $arr_every[] = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
            }
            //上季度总数---------------------------------------
            $begin_time=date('Y-10-01 00:00:00', strtotime('-1 year'));
            $to_time=date('Y-01-01 00:00:00',$unix_time);
            $time_arr = array( //取数时间范围
                '0'=>$begin_time,
                '1'=>$to_time
            );
            $last_quarter = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
            $arr_num['last_quarter'] =  $last_quarter;
        }
         if(in_array($month,$quarter_two)){//二季度
                    for($i=4;$i<=6;$i++){
                        $begin_time = date('Y-0'.$i.'-01 00:00:00',$unix_time);
                        $to_time   = date('Y-0'.($i+1).'-01 00:00:00',$unix_time);
                        $time_arr = array( //取数时间范围
                            '0'=>$begin_time,
                            '1'=>$to_time
                        );
                        $arr_every[] = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
                    }
             //上季度总数---------------------------------------
             $begin_time=date('Y-01-01 00:00:00',$unix_time);
             $to_time=date('Y-04-01 00:00:00',$unix_time);
             $time_arr = array( //取数时间范围
                 '0'=>$begin_time,
                 '1'=>$to_time
             );
             $last_quarter = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
             $arr_num['last_quarter'] =  $last_quarter;
          }
        if(in_array($month,$quarter_three)){//三季度
            for($i=6;$i<=10;$i++){
                $begin_time = date('Y-'.$i.'-1 00:00:00',$unix_time);
                $to_time   = date('Y-'.($i+1).'-1 00:00:00',$unix_time);
                $time_arr = array( //取数时间范围
                    '0'=>$begin_time,
                    '1'=>$to_time
                );
                $arr_every[] = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
            }
            //上季度总数---------------------------------------
            $begin_time=date('Y-04-01 00:00:00',$unix_time);
            $to_time=date('Y-07-01 00:00:00',$unix_time);
            $time_arr = array( //取数时间范围
                '0'=>$begin_time,
                '1'=>$to_time
            );
            $last_quarter = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
            $arr_num['last_quarter'] =  $last_quarter;
        }
        if(in_array($month,$quarter_four)){//四季度
            for($i=10;$i<=12;$i++){
                $begin_time = date('Y-'.$i.'-1 00:00:00',$unix_time);
                $to_time   = date('Y-'.($i+1).'-1 00:00:00',$unix_time);
                $time_arr = array( //取数时间范围
                    '0'=>$begin_time,
                    '1'=>$to_time
                );
                $arr_every[] = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
            }
            //上季度总数---------------------------------------
            $begin_time=date('Y-08-01 00:00:00',$unix_time);
            $to_time=date('Y-11-01 00:00:00',$unix_time);
            $time_arr = array( //取数时间范围
                '0'=>$begin_time,
                '1'=>$to_time
            );
            $last_quarter = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
            $arr_num['last_quarter'] =  $last_quarter;
        }
        $sum = 0;
        foreach($arr_every as $key=>$value){
            $sum += $value;
        }
        // 比列计算
        if($last_quarter==0){
            $ratio = ($sum*100);
            $ratio .= '%';
            $rate_type = 0;
        }else{
            $ratio = ($sum-$last_quarter)*100/$last_quarter;
            if($ratio>0){
                $ratio =round($ratio).'%';
                $rate_type = 0;
            }else{
                $ratio = abs($ratio);
                $ratio = round($ratio).'%';
                $rate_type = 1;
            }
        }
        $arr_num['every_num'] = $arr_every;
        $arr_num['total_num']=$sum;
        //$arr_num['every_num'] = array(1,2,3);
        //$arr_num['total_num']=5;
        $arr_num['rate']=$ratio;
        $arr_num['rate_type']= $rate_type;
        return $arr_num;
    }
    //年报
    public function getOrderYear($account_id,$hotel_id,$order_type,$unix_time){
        $year = date('Y',$unix_time);
        $month_num = date('n',$unix_time);
        //$base_time = date('Y-01-01 00:00:00',$unix_time);
        for($i=1;$i<=$month_num;$i++){
            $begin_time =   date('Y-'.$i.'-01 00:00:00',$unix_time);
            $to_time =  date('Y-'.($i+1).'-01 00:00:00',$unix_time);
            $time_arr = array( //取数时间范围
                '0'=>$begin_time,
                '1'=>$to_time
            );
            $arr_every[] = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
        }
        $sum = 0;
        foreach($arr_every as $key=>$value){
            $sum += $value;
        }
        //上一年度总数---------------------------------------
        $begin_time=($year-1).'-01-01 00:00:00';
        $to_time=$year.'-01-01 00:00:00';
        $time_arr = array( //取数时间范围
            '0'=>$begin_time,
            '1'=>$to_time
        );
        $last_year = $this->orderNum($account_id,$hotel_id,$order_type,$time_arr);
        $arr_num['last_year'] =  $last_year;
        // 比列计算
        if($last_year==0){
            $ratio = ($sum*100);
            $ratio .= '%';
            $rate_type = 0;
        }else{
            $ratio = ($sum-$last_year)*100/$last_year;
            if($ratio>0){
                $ratio =round($ratio).'%';
                $rate_type = 0;
            }else{
                $ratio = abs($ratio);
                $ratio = round($ratio).'%';
                $rate_type = 1;
            }
        }
        $arr_num['month_num']= $month_num;
        $arr_num['year']= $year;
        $arr_num['every_num'] = $arr_every;
        $arr_num['total_num']=$sum;
        $arr_num['rate']=$ratio;
        $arr_num['rate_type']= $rate_type;
        return $arr_num;
    }
    //开单数量
    public function orderNum($account_id,$hotel_id,$order_type,$time_arr){
        $criteria = new CDbCriteria;
        $criteria->addCondition("account_id = :account_id");
        $criteria->params[':account_id']=$account_id;
        $criteria->addCondition("staff_hotel_id = :hotel_id");
        $criteria->params[':hotel_id']= $hotel_id;
        $criteria->addCondition("order_type = :order_type");
        $criteria->params[':order_type']= $order_type;
        $criteria->addCondition("update_time >= '".$time_arr[0]."'");
        $criteria->addCondition("update_time < '".$time_arr[1]."'");
        $res = Order::model()->findAll($criteria);
        $num = count($res);
        return $num;
    }
}
