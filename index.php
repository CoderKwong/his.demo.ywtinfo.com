<?php
include './MinPHP/run/init.php';
$act = $_GET['act'];
$act = empty($act) ? 'index' : $_GET['act'];
$op = empty($_GET['op']) ? 'list' : $_GET['op'];
$menu = '';
switch($act){ 
	//登录退出
	case 'login':
		$menu = ' - 登录';
		$file = './MinPHP/run/login.php';
		break;
	//首页
	case 'index':
		$menu = ' - 欢迎';
		$file ='./MinPHP/run/hello.php';
		break;
	//患者管理
	case 'patient':
		$menu = ' - 患者管理';
		if($op=='add'){			
			$file ='./MinPHP/run/patientAdd.php';
		}else if($op=='edit'){
			$file ='./MinPHP/run/patientEdit.php';
		}else{
			$file ='./MinPHP/run/patientList.php';
		} 
		break; 
	//取号
	case 'takeReg':
		$menu = ' - 取号管理';
		$file ='./MinPHP/run/takeRegList.php';
		break;
	//退号
	case 'cancelReg':
		$menu = ' - 退号管理';
		$file ='./MinPHP/run/cancelRegList.php';
		break;
     //就诊管理
	case 'clinicReg':
		$menu = ' - 就诊管理';
		if($op=='edit'){
			$file ='./MinPHP/run/clinicRegEdit.php';
		}else{
			$file ='./MinPHP/run/clinicRegList.php';
		}
		break;
	//就诊管理
	case 'report':
		$menu = ' - 报告管理';
		if($op=='edit'){
			$file ='./MinPHP/run/reportEdit.php';
		}else{
			$file ='./MinPHP/run/reportList.php';
		}
		break;
	default :
		$menu = ' - 欢迎';
		$file = './MinPHP/run/hello.php';
		break;
}
include './MinPHP/run/main.php';