<?php
class admin extends base{
	function __construct()
	{
		parent::__construct();
	}

	function display($view,$param = array())
	{
		header("Content-type: text/html; charset=utf-8");
		$param['u'] = $this->u;
		$param['menu'] = array('home'=>'首页',
				'page'=>'内容',
				'template'=>'模板',
				'model'=>'模型',
				'setting'=>'设置',
				'plugin'=>'插件',
				'account'=>'帐号',
				'../logout'=>'退出'
		);

		if( $this->u['level'] > 10) $param['menu']['user']= '用户';
		$param['content'] = view($view,$param,TRUE);
		view('v/admin/temp',$param);
	}
}
