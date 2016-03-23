<?php
/**
 * Created by PhpStorm.
 * User: keqiaow
 * Date: 2016/1/7
 * Time: 2:12
 */


if(empty($_SESSION['user'])){
    header("Location:http://crm.batorange.com/weixin/test.php");
}else{
    print_r($_SESSION['user']);
}