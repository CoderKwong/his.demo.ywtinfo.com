<?php
defined('API') or exit();
if(!is_lgoin()){die('只有登录才可进行操作');} 

?> 
<?php   
$_VAL = I($_POST); 
$op = $_GET['op'];
$type = $_GET['type']; 
//编辑界面
if(empty($id)){$id = I($_GET['id']);} 
//得到数据的详情信息start
$sqlString = "select * from his_pat_index_master where id='{$id}'"; 
$info = find($sqlString);  
if(!$info){ 
	echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 查询不到相关信息</div>';
}


//添加接口
if($op == 'edit'){
	if($type == 'do'){
		if(!is_supper()){die('只有超级管理员才可对接口进行操作');}
		
		$trueName = $_POST['trueName'];  //姓名
		$phone = $_POST['phone']; //手机号码
		$idNo = $_POST['idNo'];    //身份证ID
		$address = $_POST['address'];  //地址
		$sex = $_POST['sex'];//性别 
		$lasttime = timenow(); //最后操作时间  
		$birth = getIDCardInfo($idNo);
		$card = $_POST['card'];//办就诊卡
		
		if($birth['error']==2){  
			$birthDay = $birth['birthday']; 
 
			if($card=="0"){
			   //已有卡
				$cid =$info['cardId'];
				$fee = $info['fee'];
			}else if($card=="1"){
			  //办卡 同时充值100元
				$sqlString ="SELECT MAX(cardId+1) cid FROM his_pat_index_master "; 
				$sqlData1 =find($sqlString);  
				$cid= str_pad($sqlData1['cid'],8,"0",STR_PAD_LEFT); 
				$fee= 100;
			}else if($card=="2"){
				//不办卡 卡余额为0
				$cid ="";
				$fee ="0";
			}else if($card=="3"){
			    //换卡 卡余额不变
				$sqlString ="SELECT MAX(cardId+1) cid FROM his_pat_index_master "; 
				$sqlData1 =find($sqlString);  
				$cid= str_pad($sqlData1['cid'],8,"0",STR_PAD_LEFT); 
				$fee= $info['fee'];
			}else if($card=="4"){
		    	//退卡 并退费卡余额为0
				$cid ="";
				$fee ="0";
			}else if($card=="5"){
				//卡充值  充值100元 
				$cid =$info['cardId'];
				$fee = floatval($info['fee'])+100;
			} 
			$sqlString ="update  his_pat_index_master set trueName='$trueName',phone='$phone',idNo='$idNo',birthDay='$birthDay',tel='$phone',address='$address',sex='$sex',cardId='$cid',fee='$fee' where id='$id' ";
			
			$sqlData2 =  update($sqlString,"boolean");
			if($sqlData2){
				go(U(array('act'=>'patient','op'=>'edit','id'=>$id)));
			}else{
				echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 更新失败</div>';
				
			}  
		}else{
			echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 身份证号格式有误，请重新录入 </div>';
		}
	}

	
}

?>
  <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;position:relative">
            <h4>修改病人主索引信息<span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
            <div style="margin-left:20px;">
                <form action="?act=patient&type=do&op=edit&id=<?php echo $info['id']?>" method="post">
             
					
					 <h5 style="color:#3F00FF">患者ID:<?php echo $info['patientId']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊卡号:<?php echo $info['cardId']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊卡余额:<?php echo $info['fee']?>元</h5>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">患者姓名</div>
                            <input type="text" class="form-control" name="trueName" placeholder="姓名"  value="<?php echo $info['trueName']?>" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">手机号码</div>
                            <input type="text" class="form-control" name="phone" placeholder="手机号码"  value="<?php echo $info['phone']?>"  required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">身份证ID</div>
                            <input type="text" class="form-control" name="idNo" placeholder="身份证ID"  value="<?php echo $info['idNo']?>"  required="required">
                        </div>
                    </div>
					
					 <div class="form-group"  >
                        <div class="input-group">
                            <div class="input-group-addon">办就诊卡</div>
                            <select class="form-control" name="card">
						  <?php   	 
						  if($info['cardId']!=""){
						  	//1,2隐藏		
						  ?>
<option value="0">已有卡</option>  
<option value="5">卡充值  充值100元</option> 
<option value="3">换卡 卡余额不变</option>
<option value="4">退卡 并退费卡余额为0</option>

						 <?php   }
						else if($info['cardId']==""){
							//	无改变	
						?>

<option value="2">不办卡 卡余额为0</option> 
<option value="1">办卡 同时充值100元</option> 

						   <?php }  ?>
	
							
							
                           </select> 
                        </div> 
                    </div>
					 
                    <div class="form-group">
                     <div class="input-group">
                            <div class="input-group-addon">家庭地址</div>
                            <input type="text" class="form-control" name="address" placeholder="地址"  value="<?php echo $info['address']?>" >
                        </div>
                    </div>
					
                    <div class="form-group"  >
                        <div class="input-group">
                            <div class="input-group-addon">&nbsp;&nbsp;&nbsp;&nbsp;性别&nbsp;&nbsp;&nbsp</div>
                            <select class="form-control" name="sex"> 
                            <option value="男"  <?php echo ($info['sex'] == '男' ? 'selected' : '')?> >男</option>
                            <option value="女" <?php echo  ($info['sex'] == '女' ? 'selected' : '')?> >女</option> 
                           </select> 
                        </div>
						
                    </div>
                       
                    
                     <button class="btn btn-success">保存修改</button> <button class="btn btn-success" onclick="javascript:history.go(-1)">返回列表</button>  
                </form>
            </div>
        </div>
    </div>
  