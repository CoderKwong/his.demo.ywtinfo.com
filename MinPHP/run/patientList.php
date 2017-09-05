<?php
defined('API') or exit();
if(!is_lgoin()){die('只有登录才可进行操作');} 

?> 
<?php  

//clinicList('ZA3854423','徐雯雯','2017-02-13'); 

$_VAL = I($_POST);
$page = empty($_GET['page']) ? '1' : $_GET['page'];
$keyword = $_POST['keyword'];  //关键字
if($keyword!=""){
	$sqlWhere=" and (trueName like '%" .$keyword. "%' or patientId like  '%" .$keyword. "%' or cardId like  '%" .$keyword. "%' or idNo like  '%" .$keyword. "%'  )"	; 
} 
 
$sql = "select count(*) as row from his_pat_index_master where  1=1 $sqlWhere"; 
$rowCount = find($sql); 
$pageSize =20; 
$pageCountNum = round($rowCount['row'] / $pageSize);
$pageNum = $pageSize*($page-1); 
$sql = "select * from his_pat_index_master  where 1=1 $sqlWhere ORDER BY id DESC limit $pageNum,$pageSize "; 
$list = select($sql); 
$multipage = multipage($pageCountNum,$page,"&act=patient&op=list");

?> 
 <form action="?act=patient&type=do&op=list" method="post">
  <div class="form-group">  
	 <table class="table">
        <thead>
            <tr>
                <td><input type="text" class="form-control" name="keyword" value="<?php echo $keyword?>" placeholder="关键字：姓名、病人ID、卡号、身份证号码"></td> 
                <td>
                    <button class="btn btn-success" >查询</button> 
                    <button type="button" class="btn btn-success"style="margin-left: 20px;" onclick="add()">建档</button>
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
        			<th class="col-md-1">姓名</th>
        			<th class="col-md-2">手机号码</th>
        			<th class="col-md-2">身份证</th>
        			<th class="col-md-2">病人ID</th>
        			<th class="col-md-2">卡号</th>
        			<th class="col-md-1">费用</th>
        			<th class="col-md-1">操作</th>
			 	</tr>
        	</thead>
        	<tbody  > 
				<?php foreach($list as $v) {?>
					<tr>
                        <td><?php echo $v['trueName']?></td>
                        <td><?php echo $v['phone']?></td>
                        <td><?php echo $v['idNo']?></td>
                        <td><?php echo $v['patientId']?></td> 
                        <td><?php echo $v['cardId']?></td> 
                        <td><?php echo $v['fee']?></td> 
                        <td><a href="<?php echo U(array('act'=>'patient','op'=>'edit','id'=>$v['id']))?>">详细操作</a></td>
						
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
        
		 var addurl = '<?php echo U(array('act'=>'patient','op'=>'add'))?>';
        //添加接口
        function add(){
            window.location.href=addurl;
        }
		
		   //编辑某个接口
        function editApi(gourl){
            window.location.href=gourl;
        }

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
	