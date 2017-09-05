<?php
defined('API') or exit();
if(!is_lgoin()){die('只有登录才可进行操作');} 

?> 
<?php   
$_VAL = I($_POST); 
$op = $_GET['op'];
$type = $_GET['type'];
//添加接口
if($op == 'add'){
	if($type == 'do'){
		if(!is_supper()){die('只有超级管理员才可对接口进行操作');}
		
		$trueName = $_POST['trueName'];  //姓名
		$phone = $_POST['phone']; //手机号码
		$idNo = $_POST['idNo'];    //身份证ID
		$address = $_POST['address'];  //地址
		$sex = $_POST['sex'];//性别 
		$card = $_POST['card'];//卡 
		$lasttime = timenow(); //最后操作时间  
		$birth = getIDCardInfo($idNo);
		
		if($birth['error']==2){  
			$birthDay = $birth['birthday']; 
			$sqlString ="SELECT * FROM his_pat_index_master WHERE idNo='".$idNo."'"; 
			$sqlData = find($sqlString);  
			if($sqlData){
				echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 身份证号存在，不能重复添加</div>';
			}else{
				$sqlString ="SELECT MAX(patientId+1) as pid,MAX(cardId+1) cid FROM his_pat_index_master "; 
				$sqlData1 =find($sqlString);  
				$pid = str_pad($sqlData1['pid'],8,"0",STR_PAD_LEFT);
				if($card=="0"){
					$cid = str_pad($sqlData1['cid'],8,"0",STR_PAD_LEFT);
					$fee =100;
				}else{
					$cid="";
					$fee =0;
				}
				
				$sqlString ="insert into his_pat_index_master (trueName,phone,email,idNoType,idNo,birthDay,tel,address,sex,nation,province,city,area,patientId,cardId,fee,createDate) VALUES ('$trueName','$phone','','0','$idNo','$birthDay','$phone','$address','$sex','','','','','$pid','$cid','$fee','$lasttime')";
				 
				$keyid = insert($sqlString,"autoid"); 
				if($keyid){
					go(U(array('act'=>'patient','op'=>'edit','id'=>$keyid['autoid'])));
				}else{
					echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 创建失败</div>';
					
				} 
			}
		}else{
			echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 身份证号格式有误，请重新录入 </div>';
		} 
	}
}

?>
  <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;position:relative">
            <h4>添加病人主索引信息<span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
            <div style="margin-left:20px;">
                <form action="?act=patient&type=do&op=add" method="post">
                   
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">患者姓名</div>
                            <input type="text" class="form-control" name="trueName" placeholder="姓名" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">手机号码</div>
                            <input type="text" class="form-control" name="phone" placeholder="手机号码" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">身份证ID</div>
                            <input type="text" class="form-control" name="idNo" placeholder="身份证ID" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                     <div class="input-group">
                            <div class="input-group-addon">家庭地址</div>
                            <input type="text" class="form-control" name="address" placeholder="地址">
                        </div>
                    </div>
					
					<div class="form-group"  >
                        <div class="input-group">
                            <div class="input-group-addon">办就诊卡</div>
                            <select class="form-control" name="card">
                              <option value="0">是 同时充值100元</option>
                              <option value="1">否 如要重新办卡请修改信息</option>
                           </select> 
                        </div> 
                    </div>
					
                      <div class="form-group"  >
                        <div class="input-group">
                            <div class="input-group-addon">&nbsp;&nbsp&nbsp;&nbsp性别&nbsp;&nbsp&nbsp</div>
                            <select class="form-control" name="sex">
                              <option value="男">男</option>
                              <option value="女">女</option>
                           </select> 
                        </div> 
                    </div>
                       
                    <button class="btn btn-success">保存添加</button>
                </form>
            </div>
        </div>
    </div>
  