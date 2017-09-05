<?php
defined('API') or exit(); 
 

//生成检验
function labList($patientId,$patientName,$dataTime,$deptName,$doctorName,$diagnosis,$randomNum=5,$isStatus=1){ 
	$sqlString ="SELECT DISTINCT  m.*  FROM his_labmaster m,his_labreport r WHERE m.id=r.labMasterId ORDER BY RAND() LIMIT ".$randomNum;   
	$sqlDataList = select($sqlString);
	
	foreach($sqlDataList as $key=>$v){
		$checkId = orderId(); 
		$sqlString = "insert into his_labmaster (hospitalId,patientId,inspectionId, inspectionName, inspectionDate, status, patientName, patientAge, gender, deptName, clinicalDiagnosis, reportDoctorName, clinicSeq, inpatientId)";
		$sqlString .=" VALUES ('1000','$patientId','$checkId','".$v['inspectionName']."','$dataTime','$isStatus','$patientName','".$v['patientAge']."','".$v['gender']."','".$deptName."','".$diagnosis."','".$doctorName."','".$v['clinicSeq']."','".$v['inpatientId']."')";
		$keyid = insert($sqlString,"autoid"); 
		if($keyid)
		{ 
			labInfo("1000",$patientId,$keyid['autoid'],$checkId,$dataTime,$v['inspectionId']);
		}   
	}
}


//检验结果
function labInfo($hospitalId,$patientId,$labKeyid,$checkId,$dataTime,$itemId){  
	$sqlString =" SELECT * FROM his_labreport where itemId='".$itemId."'"; 
	$sqlDataList = select($sqlString); 
	foreach($sqlDataList as $key=>$v){
		
		$sqlString = "insert into his_labreport (labMasterId, hospitalId, patientId, itemId, itemName, orderNo, result, units, lowerLimit, upperLimit, abnormal, reportTime, testEngName, specimType)";
		$sqlString .=" VALUES ('$labKeyid','$hospitalId','$patientId','$checkId','".$v['itemName']."','0','".$v['result']."','".$v['units']."','".$v['lowerLimit']."','".$v['upperLimit']."','".$v['abnormal']."','".$dataTime."','".$v['testEngName']."','".$v['specimType']."')";
		 
		insert($sqlString);
	}   
	
}  


//生成检查
function examList($patientId,$patientName,$dataTime,$deptName,$doctorName,$diagnosis,$randomNum=5,$isStatus=1){ 
	$sqlString =" SELECT * FROM his_exammaster ORDER BY RAND() LIMIT ".$randomNum;   
	$sqlDataList = select($sqlString); 
	foreach($sqlDataList as $key=>$v){
		$checkId = orderId(); 
		$sqlString = "insert into his_exammaster (hospitalId, patientId, patientName,examId, examName, examDate, status,deptName, reportDoctorName, clinicSeq, inpatientId)";
		$sqlString .=" VALUES ('1000','$patientId','$patientName','".$checkId."','".$v['examName']."','".$dataTime."','".$isStatus."','".$deptName."','".$doctorName."','".$v['clinicSeq']."','".$v['inpatientId']."')";
		
		$keyid = insert($sqlString,"autoid"); 
		if($keyid)
		{ 
			examInfo("1000",$patientId,$keyid['autoid'],$checkId,$dataTime,$deptName,$doctorName,$diagnosis,$v['examId']);
		}   
	}
}


//检查结果
function examInfo($hospitalId,$patientId,$examKeyid,$checkId,$dataTime,$deptName,$doctorName,$diagnosis,$examId){  
	$sqlString =" SELECT * FROM his_examreport where examId='".$examId."'";   
	$sqlDataList = select($sqlString);
	
	foreach($sqlDataList as $key=>$v){
		$sqlString = "insert into his_examreport (examMasterId, hospitalId, patientId, examId, exmaName, deptName, doctorName, reportTime, checkPart, checkMethod, checkSituation, diagnosis, verifyDate, verifyDocName, appDocName)";
		$sqlString .=" VALUES ('$examKeyid','$hospitalId','$patientId','$checkId','".$v['exmaName']."','".$deptName."','".$doctorName."','".$dataTime."','".$v['checkPart']."','".$v['checkMethod']."','".$v['checkSituation']."','".$diagnosis."','".$dataTime."','".$v['verifyDocName']."','".$doctorName."')";
		insert($sqlString);
	}   
	
}  
