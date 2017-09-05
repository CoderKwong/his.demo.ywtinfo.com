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
				$codeRefStr ="未生成";
				if($parameter['default'][$i]!=""){ 
					$rowNum = findNum($parameter['default'][$i]);
				} 
		
							
				if($parameter['type'][$i]=="Y"){
					$codeRef =1;
					$codeRefStr ="已生成"; 
				}
				
				 
				if($parameter['des'][$i]!="已生成"){   
					$codeRefStr ="已生成";   
					if($parameter['name'][$i]=="2"){
						labList($patInfo['patientId'],$patInfo['patientName'],$info['admitDate'],$info['deptName'],$info['doctorName'],$diagnosis,$rowNum,$codeRef);
					}else if($parameter['name'][$i]=="3"){
						examList($patInfo['patientId'],$patInfo['patientName'],$info['admitDate'],$info['deptName'],$info['doctorName'],$diagnosis,$rowNum,$codeRef);
					}   
				} 
				
				$p['name'][$i] =$parameter['name'][$i];
				$p['type'][$i] =$parameter['type'][$i];
				$p['default'][$i] =$rowNum;
				$p['des'][$i] =$codeRefStr;
			}  
			$parameter = serialize($p);  
			
			 $sqlString ="update  his_clinicmaster set diagnosis='$diagnosis',parameter='$parameter',clinicFlag='0' where id='$id' ";			 
			$sqlData2 =  update($sqlString,"boolean");
			if($sqlData2){
				go(U(array('act'=>'clinicReg','op'=>'edit','id'=>$id)));
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
                <form action="?act=clinicReg&type=do&op=edit&id=<?php echo $info['id']?>" method="post">
             
					
                      <h4>患者信息</h4>
					 <h5 style="color:#3F00FF">患者姓名:<?php echo $patInfo['trueName']?> &nbsp;&nbsp;&nbsp;&nbsp;患者ID:<?php echo $patInfo['patientId']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊卡号:<?php echo $patInfo['cardId']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊卡余额:<?php echo $patInfo['fee']?>元</h5>
                       <h4>就诊信息</h4>
					 <h5 style="color:#3F00FF">就诊科室:<?php echo $info['deptName']?> &nbsp;&nbsp;&nbsp;&nbsp;医生:<?php echo $info['doctorName']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊日期:<?php echo $info['admitDate']?> &nbsp;&nbsp;&nbsp;&nbsp;就诊号:<?php echo $info['regId']?></h5>
                    
					<div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">诊断</div>
                            <input type="text" class="form-control" name="diagnosis" placeholder="诊断"  value="<?php echo $info['diagnosis']?>" required="required">
                        </div>
                    </div>
					  <div class="form-group">
                        
                        <h4>开处方、检查、检验</h4>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-3">类型</th>
                                <th class="col-md-2">是否出结果</th>
                                <th class="col-md-2">缺省值</th>
                                <th class="col-md-4">描述</th>
                                <th class="col-md-1">
                                    <button type="button" class="btn btn-success" onclick="add()">新增</button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="parameter">

                            <?php $count = count($info['parameter']['name']);?>
                            <?php for($i=0;$i<$count;$i++){ ?>
								<tr>
								   <td class="form-group has-error">   
                                    <?php 
                                    $selected[1] = ($info['parameter']['name'][$i] == '2') ? 'selected' : '';
                                    $selected[2] = ($info['parameter']['name'][$i] == '3') ? 'selected' : '';
                                    ?>
									<select class="form-control" name="p[name][]"> 
                                    <option value="2" <?php echo $selected[1]?>>开检验</option>
                                    <option value="3" <?php echo $selected[2]?>>开检查</option>
                                    </select>
									
									</td>
                                    <td>
                                    <?php
                                    $selected[3] = ($info['parameter']['type'][$i] == 'Y') ? 'selected' : '';
                                    $selected[4] = ($info['parameter']['type'][$i] == 'N') ? 'selected' : '';
                                    ?>
										<select class="form-control" name="p[type][]">
											<option value="N" <?php echo $selected[4]?>>暂不出结果</option>
											<option value="Y" <?php echo $selected[3]?>>同时出结果</option>
                                        </select>
                                        </td>
											<td><input type="text" class="form-control" name="p[default][]" placeholder="缺省值" value="<?php echo $info['parameter']['default'][$i]?>"></td>
											<td><input name="p[des][]"  readonly class="form-control" placeholder="是否已生成" value="<?php echo $info['parameter']['des'][$i]?>"></td>
                                           <td><button type="button" class="btn btn-danger" onclick="del(this)">删除</button></td>
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
    <script>
        function add(){
            var $html ='<tr>' +
                '<td class="form-group has-error" >' +
                    '<select class="form-control" name="p[name][]">' +
                        '  <option value="2">开检验</option> <option value="3">开检查</option>' +
                    '</select >' +
				'<td>' +
                    '<select class="form-control" name="p[type][]">' +
                        '<option value="N">暂不出结果</option> <option value="Y">同时出结果</option> ' +
                    '</select >' +
                '</td>' +
                '<td>' +
                    '<input type="text" class="form-control" name="p[default][]" placeholder="缺省值" value="1">' +
                '</td>' +
                '<td>' +
                    '<input name="p[des][]" disabled class="form-control" placeholder="是否已生成" >' +
                '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-danger" onclick="del(this)">删除</button>' +
                '</td>' +
                '</tr >';
            $('#parameter').append($html);
        }
        function del(obj){
            $(obj).parents('tr').remove();
        }
    </script>