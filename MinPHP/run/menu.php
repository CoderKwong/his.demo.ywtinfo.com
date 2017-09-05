<?php 
defined('API') or exit(); 
?>  
     <div class="list">
        <ul class="list-unstyled"> 
            <li class="menu">
                <a href="index.php?act=patient">
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			       患者管理
				</a>
                  <br> 
                   &nbsp;&nbsp;&nbsp;&nbsp;建档、办卡、换卡、退卡、充值    
                <hr>
            </li> 
			<li class="menu">
                <a href="index.php?act=takeReg">
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			       预约取号管理
				</a>
                  <br> 
                   &nbsp;&nbsp;&nbsp;&nbsp;已在线上预约，可在线下取号 
                <hr>
            </li> 
			 <li class="menu">
                <a href="index.php?act=cancelReg">
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			       退号管理
				</a>
                  <br> 
                   &nbsp;&nbsp;&nbsp;&nbsp;已在线上预约缴费，可在线下退号  
                <hr>
            </li> 
			
			 <li class="menu">
                <a href="index.php?act=clinicReg">
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			       就诊管理
				</a>
                  <br> 
                   &nbsp;&nbsp;&nbsp;&nbsp;报到、出诊断、开处方、开检查、开检验    
                <hr>
            </li>  
			<li class="menu">
                <a href="index.php?act=report">
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			       报告管理
				</a>
                  <br> 
                   &nbsp;&nbsp;&nbsp;&nbsp;出检查结果、出检验结果      
                <hr>
            </li> 
			
			 <li class="menu">
                <a href="#">
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			      缴费管理
				</a>
                  <br> 
                   &nbsp;&nbsp;&nbsp;&nbsp;开单缴费（暂时不做）   
                <hr>
            </li> 
		
			 <li class="menu">
                <a href="#">
				<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
			       取药管理
				</a>
                  <br> 
                   &nbsp;&nbsp;&nbsp;&nbsp;报到、叫号（暂时不做）     
                <hr>
            </li> 
          </ul>
    </div>
   
<!--jquery模糊查询start-->
<script>
    var $COOKIE_KEY = "<?php echo C('cookie->navbar')?>"; //记录左侧菜单栏的开打与关闭状态的cookie的值
    function search(type,obj){
        var $find = $.trim($(obj).val());//得到搜索内容
        if(type == 'cate'){//对接口分类进行搜索操作
            if($find != ''){
                $(".menu").hide();
                //找到符合关键字的对象
                var $keywordobj = $(".keyword:contains('"+$find+"')")
                $keywordobj.each(function(i) {
                    var menu_id = $($keywordobj[i]).attr('id');
                    $("#info_"+menu_id).show();
                });
            }else{
                $(".menu").show();//在没有搜索内容的情况下,左侧导航菜单 全部 显示
            }
        }else if(type == 'api'){//对接口进行搜索操作
            if($find != ''){
                $(".menu").hide();//左侧导航菜单隐藏
                $(".info_api").hide();
                //找到符合关键字的对象
                var $keywordobj = $(".keyword:contains('"+$find+"')")
                $keywordobj.each(function(i) {
                    var menu_id = $($keywordobj[i]).attr('id');
                    $("#api_"+menu_id).show();//左侧导航菜单 部份 隐藏
                    $("#info_api_"+menu_id).show();//接口详情 部份 隐藏
                });
            }else{
                $(".menu").show();//在没有搜索内容的情况下,左侧导航菜单 全部 显示
                $(".info_api").show();//在没有搜索内容的情况下,接口详情 全部 显示
            }
        }
    }

    window.onload=function(){
        //添加关闭,打开左侧菜单的功能
        <?php if($_COOKIE[C('cookie->navbar')]==1){
            echo 'var status_flg="&gt";var cursor="e-resize";';
        }else{
            echo 'var status_flg="&lt";var cursor="w-resize"';
        }?>

        var navbarButton = '<div onclick="navbar(this)" ' +
            'style="text-align:center;line-height:120px;border-bottom-right-radius:5px;cursor:'+cursor+';border-top-right-radius:5px;width:14px;height:120px;background: rgba(91,192,222, 0.8);position:fixed;left:0;top:260px;color:#fff;box-shadow: 0px 0px 0px 1px #cccccc;">' +
            status_flg +
            '</div>'
        $('body').append(navbarButton);
    }
    // 全屏和normal
    function navbar(obj){
        if($('#mainwindow').hasClass('col-md-9')){
            $(obj).html('&gt;');
            $(obj).css("cursor","e-resize");
            $('#mainwindow').removeClass('col-md-9').addClass('col-md-12');
            $('#navbar').hide();
            $.cookie($COOKIE_KEY, '1');
        }else{
            $(obj).html('&lt;');
            $(obj).css("cursor","w-resize");
            $('#mainwindow').removeClass('col-md-12').addClass('col-md-9');
            $('#navbar').show();
            $.cookie($COOKIE_KEY, '0');
        }
    }
</script>
<!--jquery模糊查询end-->
<!--end-->