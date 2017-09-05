<?php
defined('API') or exit();
if(!is_lgoin()){die('只有登录才可进行操作');} 

?> 
<?php  
 
$_VAL = I($_POST);
$op = $_GET['op'];
$type = $_GET['type']; 
$info = $_GET['info']; 
if(empty($id)){$id = I($_GET['id']);} 
$page = empty($_GET['page']) ? '1' : $_GET['page'];
$keyword = $_POST['keyword'];  //关键字
if($type == 'do'){
	$sqlString = "update his_appoints_master set cancelFlag='1',payFlag='0' where id='{$id}'"; 
	$sqlData = update($sqlString);  
	if($sqlData){ 
		$sqlString = "select * from his_appoints_master where id='{$id}'"; 
		$sqlData = find($sqlString);  
		$sqlString ="INSERT INTO his_clinicmaster(regId, regDate,orderIdHis, STATUS, patName, patientId, admitDate, hospitalId, deptId, deptName, doctorId, doctorName, doctorTitle, regFee, seqCode, admitAddress, sessionName, admitRange, serviceName, insuRegInfo, returnFlag, startTime, endTime, transactionId)	 VALUES('".$sqlData['id']."', '".$sqlData['orderTime']."','".$sqlData['orderIdHIS']."||".$sqlData['seqCode']."', 'N', '".$sqlData['patientName']."', '".$sqlData['patientId']."', '".$sqlData['regDate']."', '1000', '".$sqlData['deptId']."', '".$sqlData['deptName']."', '".$sqlData['doctorId']."', '".$sqlData['doctorName']."', '".$sqlData['doctorTitle']."', '".$sqlData['fee']."','".$sqlData['seqCode']."', '".$sqlData['address']."', '".$sqlData['timeName']."', '', '', '', 'Y', '".$sqlData['startTime']."', '".$sqlData['endTime']."', '')";
		insert($sqlString,"return");				
		
		echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> '.$info.' 取号成功</div>';
	}
}

if($keyword!=""){
	$sqlWhere=" and (patientName like '%" .$keyword. "%' or patientId like  '%" .$keyword. "%' or doctorName like  '%" .$keyword. "%'  )"	; 
} 
//admitDate='".datenow()."' 
$sql = "select count(*) as row from his_appoints_master where cancelFlag='1' and payFlag='1' and regDate>='".datenow()."'  $sqlWhere"; 
$rowCount = find($sql); 
$pageSize =20; 
$pageCountNum = round($rowCount['row'] / $pageSize);
$pageNum = $pageSize*($page-1); 
$sql = "select * from his_appoints_master  where cancelFlag='1' and payFlag='1' and regDate>='".datenow()."'  $sqlWhere limit $pageNum,$pageSize"; 
$list = select($sql); 
$multipage = multipage($pageCountNum,$page,"&act=takeReg&op=list");

?> 
 <form action="?act=takeReg&op=list" method="post">
  <div class="form-group">  
	 <table class="table">
        <thead>
            <tr>
                <td><input type="text" class="form-control" name="keyword" value="<?php echo $keyword?>" placeholder="关键字：姓名、病人ID、医生姓名"></td> 
                <td>
                    <button class="btn btn-success" >查询</button>  
                </td>
            </tr>
          </thead>  
        </table>
    </div> 
</form>
    <?php if(count($list)){ ?>
        <div class="info_api" style="border:1px solid #ddd;margin-bottom:20px;" >
        	
        	<table class="table" style="background:#fff;padding:20px ;position:relative">
        	<thead>
        		<tr  style="background:#f5f5f5;">
	
					<th class="col-md-1">病人ID</th>
        			<th class="col-md-1">姓名</th>
					<th class="col-md-2">科室</th>
					<th class="col-md-2">医生</th>
					<th class="col-md-2">就诊日期</th>
					<th class="col-md-2">午别</th>
        			<th class="col-md-1">费用</th>
        			<th class="col-md-1">操作</th>
			 	</tr>
        	</thead>
        	<tbody  > 
				<?php foreach($list as $v) {?>
	<tr>
                        <td><?php echo $v['patientId']?></td>
                        <td><?php echo $v['patientName']?></td>
                        <td><?php echo $v['deptName']?></td>
                        <td><?php echo $v['doctorName']?></td>  
                        <td><?php echo $v['regDate']?></td> 
                        <td><?php echo $v['timeName']?></td> 
                        <td><?php echo $v['fee']?></td> 
                        <td><a href="<?php echo U(array('act'=>'takeReg','type'=>'do','id'=>$v['id'],'info'=>"'患者：".$v['patName']." 医生：".$v['doctorName']." 就诊日期：".$v['regDate']."'"))?>">取号操作</a></td>
						
                    </tr>
                <?php } ?>

		   </tbody>
		</table> 

          <div style="background:#ffffff;padding:5px 10px;"> 
                 <?php echo $multipage;?>  
            </div> 
        </div> 
        <div id="gotop" onclick="goTop()" style="z-index:999999;font-size:18px;display:none;color:#e6e6e6;cursor:pointer;width:42px;height:42px;border:#ddd 1px solid;line-height:42px;text-align:center;background:rgba(91,192,222, 0.8);position:fixed;right:20px;bottom:200px;border-radius:50%;box-shadow: 0px 0px 0px 1px #cccccc;">T</div>  
    <?php } else{ ?>
        <div style="font-size:16px;">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 没有信息
        </div>
    <?php }?>
    <script>
      
        //返回顶部
        function goTop(){
            $('#mainwindow').animate(
                { scrollTop: '0px' }, 200
            );
        }

        //检测滚动条,显示返回顶部按钮
        document.getElementById('mainwindow').onscroll = function () {
            if(document.getElementById('mainwindow').scrollTop > 100){
                document.getElementById('gotop').style.display='block';
            }else{
                document.getElementById('gotop').style.display='none';
            }
        };
    </script>  
	