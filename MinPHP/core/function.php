<?php defined('API') or exit();?>
<?php
    /**
     * @dec 得到配置文件的配置项
     * @param null $name
     * @return mixed
     * 使用方法,例子
     * C('db') 或 C('version->no')
     */
    function C($name = null){
        static $_config = array();
        if(empty($_config)){
            $_config = include_once './MinPHP/core/config.php';
        }
        if(is_null($name)){
            return $_config;
        }else{
            if(strpos($name,'->')){
                $arr = explode('->',$name);
                $tmp = $_config;
                foreach($arr as $v){
                    $tmp = $tmp[$v];
                }
                return $tmp;
            }
            return $_config[$name];
        }
    }

    //得到数据库连接资源
    function M(){
        static $_model = null;
        if(is_null($_model)){
            $db=C('db');
            try {
                $_model = new PDO("mysql:host={$db['host']};dbname={$db['dbname']}","{$db['user']}","{$db['passwd']}");
            } catch ( PDOException $e ) {
                die ( "Connect Error Infomation:" . $e->getMessage () );
            }
            //设置数据库编码
            $_model->exec('SET NAMES utf8');
        }
        return $_model;
    }

    //返回一条记录集
    function find($sql){
        $rs = M()->query($sql);
        $row = $rs->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    //返回多条记录
    function select($sql){
        $rs = M()->query($sql);
        $rows = array();
        while($row = $rs->fetch(PDO::FETCH_ASSOC)){
            $rows[] = $row;
        }
        return $rows;
    }

    //insert
    function insert($sql,$resultType){ 
		if($resultType=="boolean"){
			$data = M()->exec($sql);
			if($data){
				return array("return"=>"true");
			}else{
				return false;
			}
		}else if($resultType=="autoid"){			
			$data = M()->exec($sql);    
			$data = M()->lastInsertId();
			if($data){
				return array("autoid"=>$data);
			}else{
				return "";
			}
		} else{
			$data = M()->exec($sql);
			if($data){ 
				return array("return"=>"true");
			}else{ 
				return false;
			}
		}
    }
	
	 

    //update
    function update($sql){
        return M()->exec($sql);
    }

    //设置和获取session值
    function session($key = null,$value = null){
        $pre = C('session->prefix');  //session前缀
        if(is_null($key)){
            return $_SESSION[$pre];
        }else{
            if(is_null($value)){
                return $_SESSION[$pre][$key];
            }else{
                $_SESSION[$pre][$key] = $value;
            }
        }
    }

    //判断是否登录
    function is_lgoin(){
        $login_name = session('login_name');
        return empty($login_name) ? false : true;
    }

    //判断是否为超级管理员
    function is_supper(){
        return session('issupper') == 1 ? true : false;
    }

    //跳转
    function go($url){
        $gourl = '<script language="javascript" type="text/javascript">window.location.href="'.$url.'"</script>';
        die($gourl) ;
    }

    //生成url
    function U($array = null){
        if(is_null($array)){
            $url = '';
        }else{
            $url = '?'.http_build_query($array);
            $url = str_replace('%23','#',$url);
        }
        return 'index.php'.$url;
    }

    //安全过滤
    function I($val){
        if(is_array($val)){
            foreach($val as $k => $v){
                $val[$k] = I($v);
            }
            return $val;
        }else{
            if(is_numeric($val)){
                return intval($val);
            }else if(is_string($val)){
                return htmlspecialchars(trim($val),ENT_QUOTES);
            }else{
                return $val;
            }
        }
    }

    //网站基础路径baseUrl
    function baseUrl(){
        $currentPath = $_SERVER['SCRIPT_NAME'];
        $pathInfo = pathinfo($currentPath);
        $hostName = $_SERVER['HTTP_HOST'];
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://' ? 'https://' : 'http://';
        return $protocol.$hostName.$pathInfo['dirname']."/";
    }

    /**
     * @dec 下载文件 指定了content参数，下载该参数的内容
     * @access public
     * @param string $showname 下载显示的文件名
     * @param string $content  下载的内容
     * @param integer $expire  下载内容浏览器缓存时间
     * @return void
     */
    function download($showname='',$content='',$expire=180) {
        $type	=	"application/octet-stream";
        //发送Http Header信息 开始下载
        header("Pragma: public");
        header("Cache-control: max-age=".$expire);
        //header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Expires: " . gmdate("D, d M Y H:i:s",time()+$expire) . "GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
        header("Content-Disposition: attachment; filename=".$showname);
        header("Content-type: ".$type);
        header('Content-Encoding: none');
        header("Content-Transfer-Encoding: binary" );
        die($content);
    }


	
	/**
	 *生成时间戳+随机码 唯一订单ID号
	 * @return string
	 */
	function orderId() {
		list($s1, $s2) = explode(' ', microtime());		
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2))).randomnum();
	}
	
	
	
	/**
	 * 生成随机数字串
	 * @param string $lenth 长度
	 * @return string 字符串
	 */
	function randomnum() {
		
		$str = null;
		$strPol = "0123456789";
		$max = strlen($strPol)-1;

		for($i=0;$i<6;$i++){
			$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
		}

		return $str;
	} 
	
	
	/**
	 * 生成时间
	 * @return string
	 */
	function timenow() { 
		return date("Y-m-d H:i:s", time());
	}
	
	
	/**
	* 生成时间
	* @return string
	*/
	function datenow() { 
		return date("Y-m-d", time());
	}
	
	//PHP提取字符串中的数字
	function findNum($str=''){
        $str=trim($str);
        if(empty($str)){return '';}
        $result='';
        for($i=0;$i<strlen($str);$i++){
            if(is_numeric($str[$i])){
                $result.=$str[$i];
            }
        }
        return $result;
    }
	
	/**
 * @param $maxpage  总页数
 * @param $page    当前页
 * @param string $para  翻页参数(不需要写$page),如http://www.example.com/article.php?page=3&id=1，$para参数就应该设为'&id=1'
 * @return string  返回的输出分页html内容
 */
function multipage($maxpage, $page, $para = '') {
    $multipage = '';  //输出的分页内容
    $listnum = 15;     //同时显示的最多可点击页面

    if ($maxpage < 2) {
        return '';
    }else{
        $offset = 2;
        if ($maxpage <= $listnum) {
            $from = 1;
            $to = $maxpage;
        } else {
            $from = $page - $offset; //起始页
            $to = $from + $listnum - 1;  //终止页
            if($from < 1) {
                $to = $page + 1 - $from;
                $from = 1;
                if($to - $from < $listnum) {
                    $to = $listnum;
                }
            } elseif($to > $maxpage) {
                $from = $maxpage - $listnum + 1;
                $to = $maxpage;
            }
        }

        $multipage .= ($page - $offset > 1 && $maxpage >= $page ? '<li><a href="?page=1'.$para.'" >1...</a></li>' : '').
            ($page > 1 ? '<li><a href="?page='.($page - 1).$para.'" >&laquo;</a></li>' : '');

        for($i = $from; $i <= $to; $i++) {
            $multipage .= $i == $page ? '<li class="active"><a href="?page='.$i.$para.'" >'.$i.'</a></li>' : '<li><a href="?page='.$i.$para.'" >'.$i.'</a></li>';
        }

        $multipage .= ($page < $maxpage ? '<li><a href="?page='.($page + 1).$para.'" >&raquo;</a></li>' : '').
            ($to < $maxpage ? '<li><a href="?page='.$maxpage.$para.'" class="last" >...'.$maxpage.'</a></li>' : '');
       //$multipage .=  ' <li><a href="#" ><input type="text" size="3"  onkeydown="if(event.keyCode==13) {self.window.location=\'?page=\'+this.value+\''.$para.'\'; return false;}" ></a></li>';


        $multipage = $multipage ? '<ul class="pagination">'.$multipage.'</ul>' : '';
    }

    return $multipage;
}

//用php从身份证中提取生日,包括15位和18位身份证 
function getIDCardInfo($IDCard){ 
    $result['error']=0;//0：未知错误，1：身份证格式错误，2：无错误 
    $result['flag']='';//0标示成年，1标示未成年 
    $result['tdate']='';//生日，格式如：2012-11-15 
    if(!eregi("^[1-9]([0-9a-zA-Z]{17}|[0-9a-zA-Z]{14})$",$IDCard)){ 
        $result['error']=1; 
        return $result; 
    }else{ 
        if(strlen($IDCard)==18){ 
            $tyear=intval(substr($IDCard,6,4)); 
            $tmonth=intval(substr($IDCard,10,2)); 
            $tday=intval(substr($IDCard,12,2)); 
            if($tyear>date("Y")||$tyear<(date("Y")-100)){ 
                $flag=0; 
            }elseif($tmonth<0||$tmonth>12){ 
                $flag=0; 
            }elseif($tday<0||$tday>31){ 
                $flag=0; 
            }else{ 
                $tdate=$tyear."-".$tmonth."-".$tday." 00:00:00"; 
                if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){ 
                    $flag=0; 
                }else{ 
                    $flag=1; 
                } 
            } 
        }elseif(strlen($IDCard)==15){ 
            $tyear=intval("19".substr($IDCard,6,2)); 
            $tmonth=intval(substr($IDCard,8,2)); 
            $tday=intval(substr($IDCard,10,2)); 
            if($tyear>date("Y")||$tyear<(date("Y")-100)){ 
                $flag=0; 
            }elseif($tmonth<0||$tmonth>12){ 
                $flag=0; 
            }elseif($tday<0||$tday>31){ 
                $flag=0; 
            }else{ 
                $tdate=$tyear."-".$tmonth."-".$tday; 
                if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60){ 
                    $flag=0; 
                }else{ 
                    $flag=1; 
                } 
            } 
        } 
    } 
    $result['error']=2;//0：未知错误，1：身份证格式错误，2：无错误 
    $result['isAdult']=$flag;//0标示成年，1标示未成年 
    $result['birthday']=$tdate;//生日日期 
    return $result; 
} 