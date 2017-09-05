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
$sqlString = "select * from his_clinicmaster where id='{$id}'"; 
$info = find($sqlString);  
if(!$info){ 
	echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 查询不到相关信息</div>';
}else{
	$sqlString = "select * from his_pat_index_master where patientId='".$info['patientId']."'"; 
	$patInfo = find($sqlString);  
	
	$info['parameter'] =  unserialize($info['parameter']); 
	$count = count($info['parameter']['name']); 
	$p = array();
	for($i = 0;$i < $count; $i++){
		$p[$i]['name']=$info['parameter']['name'][$i];
		$p[$i]['type']=$info['parameter']['type'][$i];
		$p[$i]['default']=$info['parameter']['default'][$i];
		$p[$i]['des']=$info['parameter']['des'][$i];
		
	}
	$info['parameter'] = $info['parameter'];
	
	if($op == 'edit'){
		if($type == 'do'){
			if(!is_supper()){die('只有超级管理员才可对接口进行操作');} 
			 
			$diagnosis = $_POST['diagnosis'];  //姓名
			//$parameter = serialize($_POST['p']); 
			 
			$parameter =  serialize($_POST['p']); 
			$parameter = unserialize($parameter); 
			$count = count($parameter['name']); 
			$p = array();
			for($i = 0;$i < $count; $i++){
				$rowNum =1;
				$codeRef=0;
				$codeRefStr ="已生成"; 
				
				$p['name'][$i] =$parameter['name'][$i];
				$p['type'][$i] ="Y";
				$p['default'][$i] =$parameter['default'][$i];
				$p['des'][$i] =$codeRefStr;
			}  
			$parameter = serialize($p);  
			
			$sqlString ="update  his_clinicmaster set parameter='$parameter'  where id='$id' ";
			$sqlString1 ="update  his_exammaster set status='1'  where patientId='".$info['patientId']."' and examDate='".$info['admitDate']."'";
			$sqlString2 ="update  his_labmaster set status='1'  where patientId='".$info['patientId']."' and inspectionDate='".$info['admitDate']."' ";
		 
			update($sqlString1,"boolean");
			update($sqlString2,"boolean");
			$sqlData2 =  update($sqlString,"boolean");
			if($sqlData2){
				go(U(array('act'=>'report','op'=>'edit','id'=>$id)));
			}else{
				echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 更新失败</div>';
		 	}  
		}
	} 
	 
	
	
}

?>
  <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;position:relative">
            <h4>就诊人及开单信息<span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
            <div style="margin-left:20px;">
                <form action="?act=report&type=do&op=edit&id=<?php echo $info['id']?>" method="post">
             
					
                      <h4>患者信息</h4>
					 <h5 style="color:#3F00FF">患者姓名:<?php echo $patInfo['trueName']?> &nbsp;&nbsp;&nbsp;&nbsp;患者ID:<?php echo $patInfo['patientId']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊卡号:<?php echo $patInfo['cardId']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊卡余额:<?php echo $patInfo['fee']?>元</h5>
                       <h4>就诊信息</h4>
					 <h5 style="color:#3F00FF">就诊科室:<?php echo $info['deptName']?> &nbsp;&nbsp;&nbsp;&nbsp;医生:<?php echo $info['doctorName']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊日期:<?php echo $info['admitDate']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊号:<?php echo $info['regId']?></h5>
                      <h4>诊断信息</h4>
					 <h5 style="color:#3F00FF">诊断:<?php echo $info['diagnosis']?></h5>
					 
					  <div class="form-group"> 
                        <h4>开处方、检查、检验结果</h4>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-3">类型</th>
                                <th class="col-md-2">是否出结果</th>
                                <th class="col-md-2">缺省值</th>
                                <th class="col-md-4">描述</th>
                                <th class="col-md-1"> </th>
                            </tr>
                            </thead>
                            <tbody id="parameter">

                            <?php $count = count($info['parameter']['name']);?>
                            <?php for($i=0;$i<$count;$i++){ ?>
								<tr>
								   <td class="form-group has-error">   
                                    
									<select class="form-control" name="p[name][]"> 
									<?php 
									if($info['parameter']['name'][$i] == '2'){
									?>
										<option value="2" selected>开检验</option>
									<?php }else{ ?>
											<option value="3"  selected>开检查</option>
									 <?php } ?> 
                                    </select>
									
									</td>
                                    <td> 
										<select class="form-control" name="p[type][]">
										<?php 
										if($info['parameter']['type'][$i] == 'Y'){
										?>
	<option value="Y" selected>已出结果</option>
										 <?php }else{ ?>
	<option value="N" selected >未出结果</option> 
	<option value="Y" >已出结果</option>
										 <?php } ?> 


</select>
</td>
											<td><input type="text" class="form-control" name="p[default][]" readonly placeholder="缺省值" value="<?php echo $info['parameter']['default'][$i]?>"></td>
											<td><input name="p[des][]"  readonly class="form-control" placeholder="是否已生成" value="<?php echo $info['parameter']['des'][$i]?>"></td>
                                           
                                        </tr>
                                        <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    
                     <button class="btn btn-success">保存修改</button> <button class="btn btn-success" onclick="javascript:history.go(-1)">返回列表</button>  
                </form>
            </div>
        </div>
    </div>
 