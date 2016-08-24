<?php

return array(
    'env' => 'development', // 测试环境development, 生产production
    'appId' => 'crm',
    'appName' => 'CRM | 婚庆酒店',

//    'db_development' => array(
//        'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=crm',
//        'emulatePrepare' => true,
//        'username' => 'root',
//        'password' => '123456',
//        'charset' => 'utf8',
//    ),

    'db_development' => array(
        'connectionString' => 'mysql:host=rm-2zej4p48m3ke4t1i1o.mysql.rds.aliyuncs.com;port=3306;dbname=crm',
        'emulatePrepare' => true,
        'username' => 'xiaosi001',
        'password' => 'Xsxn1183',
        'charset' => 'utf8',
    ),

    // 'db_production' => array(
    //     'connectionString' => 'mysql:host=123.57.207.153;port=3306;dbname=crm',
    //     'emulatePrepare' => true,
    //     'username' => 'crm',
    //     'password' => 'CR1D$2sk0mSoon',
    //     'charset' => 'utf8',
    // ),


);
