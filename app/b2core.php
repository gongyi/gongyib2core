<?php
/**
 * B2Core 是由 Brant (brantx@gmail.com)发起的基于PHP的MVC架构
 * 核心思想是在采用MVC框架的基础上最大限度的保留php的灵活性
 * Vison 1.6.3  (20120410)
 * */
 
define('VERSION','1.6.3');

// 载入配置文件：数据库、url路由等等 
require(APP.'config.php');

// 如果配置了数据库则载入
if(isset($db_config)) $db = new db($db_config);

// 获取请求的地址兼容 SAE
$uri = '';
if(isset($_SERVER['PATH_INFO'])) $uri = $_SERVER['PATH_INFO'];
if(isset($_SERVER['ORIG_PATH_INFO'])) $uri = $_SERVER['ORIG_PATH_INFO'];
if(isset($_SERVER['SCRIPT_URL'])) $uri = $_SERVER['SCRIPT_URL'];
render_url();
function render_url()
{ 
  // redirect abc/def to abc/def/ to make SEO url 
  global $uri;
  if(strpos($uri,'.'))return;
  if($_SERVER['QUERY_STRING'])return;
  if(substr($uri,-1)=='/')return;
  if($uri =='')return;
  header("HTTP/1.1 301 Moved Permanently");
  header ('Location:'.$_SERVER['REQUEST_URI'].'/');
  exit(0);
}

// 去除Magic_Quotes
if(get_magic_quotes_gpc()) // Maybe would be removed in php6
{
  function stripslashes_deep($value)
  {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : (isset($value) ? stripslashes($value) : null);
    return $value;
  }
  $_POST = stripslashes_deep($_POST);
  $_GET = stripslashes_deep($_GET);
  $_COOKIE = stripslashes_deep($_COOKIE);
} 

// 执行 config.php 中配置的url路由
foreach ($route_config as $key => $val)
{ 
  $key = str_replace(':any', '([^\/.]+)', str_replace(':num', '([0-9]+)', $key));
  if (preg_match('#^'.$key.'#', $uri))$uri = preg_replace('#^'.$key.'#', $val, $uri);
}

// 获取URL中每一段的参数
$uri = rtrim($uri,'/');
$seg = explode('/',$uri);
$des_dir = $dir = '';

/* 依次载入控制器上级所有目录的架构文件 __construct.php
* 架构文件可以包含当前目录下的所有控制器的父类，和需要调用的函数 
*/
foreach($seg as $cur_dir) 
{
  $des_dir.=$cur_dir."/";
  if(is_file(APP.'c'.$des_dir.'__construct.php')) {
    require(APP.'c'.$des_dir.'__construct.php'); 
    $dir .=array_shift($seg).'/';
  }
  else {
    break;
  }
}

/* 根据 url 调用控制器中的方法，如果不存在返回 404 错误
* 默认请求 class home->index()
*/

$dir = $dir ? $dir:'/';
array_unshift($seg,NULL);
$class  = isset($seg[1])?$seg[1]:'home';
$method = isset($seg[2])?$seg[2]:'index'; 
if(!is_file(APP.'c'.$dir.$class.'.php'))show_404();
require(APP.'c'.$dir.$class.'.php');
if(!class_exists($class))show_404();
if(!method_exists($class,$method))show_404();
$B2 = new $class();
call_user_func_array(array(&$B2, $method), array_slice($seg, 3));

/* B2 系统函数 
* load($path,$instantiate) 可以动态载入对象，如：控制器、Model、库类等
* $path 是类文件相对 app 的地址
* $instantiate 为 False 时，仅引用文件，不实例化对象
* $instantiate 为数组时，数组内容会作为参数传递给对象 
*/
function &load($path, $instantiate = TRUE )
{
  $param = FALSE;
  if(is_array($instantiate)) {
    $param = $instantiate;
    $instantiate = TRUE;
  }
  
  $file = explode('/',$path);
  $file_name = array_pop($file);
  $dir = implode('/',$file);
  $object_name = $dir.'_'.$file_name;
  static $objects = array();
  if (isset($objects[$object_name])) return $objects[$object_name];
  $class_name = $file_name;
  switch( $dir ) {
    case 'lib':
      require(APP.$path.'.php');
      break;
    case 'm':
      $class_name = $file_name.'_m';
    default:
      require(APP.$path.'.php');
  }

  if ($instantiate == FALSE)
  {
    $objects[$object_name] = TRUE;
    return $objects[$object_name];
  }

  if($param)$objects[$object_name] = new $class_name($param);
  else  $objects[$object_name] = new $class_name();
  return $objects[$object_name];
}

// 取得 url 的片段，如 url 是 /abc/def/g/  seg(1) = abc
function seg($i)
{
  global $seg;
  return isset($seg[$i])?$seg[$i]:false;
}

/* 调用 view 文件
* function view($view,$param = array(),$cache = FALSE)
* $view 是模板文件相对 app/v/ 目录的地址，地址应去除 .php 文件后缀
* $param 数组中的变量会传递给模板文件
* $cache = TRUE 时，不像浏览器输出结果，而是以 string 的形式 return
*/
function view($view,$param = array(),$cache = FALSE)
{
  if(!empty($param))extract($param);
  ob_start();
  if(is_file(APP.'v/'.$view.'.php')) {
    require APP.'v/'.$view.'.php';
  }
  else {
    echo 'view '.$view.' desn\'t exsit';
    return false;
  }
  // Return the file data if requested
  if ($cache === TRUE)
  {
    $buffer = ob_get_contents();
    @ob_end_clean();
    return $buffer;
  }
}

// 写入日志
function write_log($level = 0 ,$content = 'none')
{
  file_put_contents(APP.'log/'.$level.'-'.date('Y-m-d').'.log', $content , FILE_APPEND );
}

// 显示404错误
function show_404() //显示 404 错误
{
  header("HTTP/1.1 404 Not Found");
  // 调用 模板 v/404.php 
  view('404');
  exit(1);
}

/*  B2Core 系统类 */
// 抽象的控制器类，建议所有的控制器均基层此类或者此类的子类 
class c { 
  function __construct()
  {
  }

  function index()
  {
    echo "基于 B2 v".VERSION." 创建";
  }

  function display($view,$param = array(),$cache = FALSE)
  {
    view($view,$param);
  }
}

// 数据库操作类
class db { 
  var $link;
  var $last_query;
  function __construct($conf)
  {
    $this->link = mysql_connect($conf['host'],$conf['user'], $conf['password']);
    if (!$this->link) {
      die('无法连接: ' . mysql_error());
      return FALSE;
    }

    $db_selected = mysql_select_db($conf['default_db']);
    if (!$db_selected) {
      die('无法使用 : ' . mysql_error());
    }
    mysql_query('set names utf8',$this->link);
  }

  //执行 query 查询，如果结果为数组，则返回数组数据
  function query($query)
  {
    $ret = array();
    $this->last_query = $query;
    $result = mysql_query($query,$this->link);
    if (!$result) {
      echo "DB Error, could not query the database\n";
      echo 'MySQL Error: ' . mysql_error();
      echo 'Error Query: ' . $query;
      exit;
    }
    if($result == 1 )return TRUE;
    while($record = mysql_fetch_assoc($result))
    {
      $ret[] = $record;
    }
    return $ret;
  }

  function insert_id() {return mysql_insert_id();}
  
  // 执行多条 SQL 语句
  function muti_query($query)
  {
    $sq = explode(";\n",$query);
    foreach($sq  as $s){
      if(trim($s)!= '')$this->query($s);
    }
  }
}

// 模块类，封装了通用CURD模块操作，建议所有模块都继承此类。
class m { 
  var $db;
  var $table;
  var $fields;
  var $last_query;
  var $key;
  function __construct()
  {
    global $db;
    $this->db = $db;
    $this->key = 'id';
  }

  public function __call($name, $arg) {
    return call_user_func_array(array($this, $name), $arg);
  }

  // 向数据库插入数组格式数据
  function add($elem = FALSE)
  {
    $query_list = array();
    if(!$elem)$elem = $_POST;
    foreach($this->fields as $f) {
      if(isset($elem[$f])){
        $elem[$f] = addslashes($elem[$f]);
        $query_list[] = "`$f` = '$elem[$f]'";
      }
    }
    $this->db->query("insert into `$this->table` set ".implode(',',$query_list));
    return $this->db->insert_id();
  }

  // 删除某一条数据
  function del($id)
  {
    $this->db->query("delete from `$this->table` where ".$this->key."='$id'");
  }

  // 更新数据
  function update($id , $elem = FALSE)
  {
    $query_list = array();
    if(!$elem)$elem = $_POST;
    foreach($this->fields as $f) {
      if(isset($elem[$f])){
        $elem[$f] = addslashes($elem[$f]);
        $query_list[] = "`$f` = '$elem[$f]'";
      }
    }
    $this->db->query("update `$this->table` set ".implode(',',$query_list)." where ".$this->key." ='$id'" );
  }

  // 统计数量
  function count($where='')
  {
    $res =  $this->db->query("select count(*) as a from `$this->table` where 1 $where");
    return $res[0]['a'];
  }

  /* get($id) 取得一条数据 或 
  * get($postquery = '',$cur = 1,$psize = 30) 取得多条数据
  */
  function get()
  {
    $args = func_get_args();
    if(is_numeric($args[0])) return $this->__call('get_one', $args);
    return $this->__call('get_many', $args);
  }

  function get_one($id)
  {
    $res =  $this->db->query("select * from `$this->table` where ".$this->key."='$id'");
    if(isset($res[0]))return $res[0];
    return false;
  }

  function get_many($postquery = '',$cur = 1,$psize = 30)
  {
    $cur = $cur > 0 ?$cur:1;
    $start = ($cur - 1) * $psize;
    $this->last_query = "select * from `$this->table` where 1 $postquery limit $start , $psize";
    return $this->db->query($this->last_query);
  }
}
