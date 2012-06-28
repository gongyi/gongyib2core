<?php
load('lib/utility',false);
class base extends c{
  function __construct(){
    global $db_config,$var;
    if(isset($db_config)){
      $this->u = load('m/user')->check();
    }
  }

  function display($view,$param = array())
  {
    $param['al_content'] = view($view,$param,TRUE);
    header("Content-type: text/html; charset=utf-8");
    view('tmp/template',$param);
  }
}