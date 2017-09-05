<?php
defined('API') or exit();
return array(
    //数据库连接配置
    'db'=>array(
			'host' => 'rm-wz941i5qk052i7g15o.mysql.rds.aliyuncs.com',   //数据库地址
            'dbname' => 'gdhistest_2017',   //数据库名
//            'user' => 'root',
//            'passwd' => 'root',    //密码
			 'user' => 'gdfangsi_2017',    //帐号
			 'passwd' => 'gdfangsi@2017',    //密码
    ),
    //session配置
    'session'=>array(
        'prefix' => 'his_',
    ),
    //cookie配置
    'cookie' => array(
        'navbar' => 'API_NAVBAR_STATUS',
    ),
    //版本信息
    'version'=>array(
        'no' => 'v1.0',  //版本号
        'time' => '2017-03-19 00:40',   //版本时间
    )

);
