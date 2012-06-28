<?php
class home extends base{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$param = array();
		view('home',$param);
		exit(0);
		global $db_config;
		if(!isset($db_config)){
			$this->install();
			exit;
		}
		load('c/page')->view();
	}

	function test()
	{

		$a = 'a:2:{s:11:"title_label";s:6:"标题";s:6:"fields";a:4:{i:0;a:5:{s:4:"name";s:7:"xinghao";s:5:"label";s:6:"型号";s:5:"model";s:4:"text";s:4:"enum";s:25:"选项1
		选项2
		选项3";s:5:"order";s:1:"2";}i:1;a:5:{s:4:"name";s:3:"pic";s:5:"label";s:6:"图片";s:5:"model";s:3:"pic";s:4:"enum";s:25:"选项1
		选项2
		选项3";s:5:"order";s:1:"3";}i:2;a:5:{s:4:"name";s:5:"price";s:5:"label";s:6:"价格";s:5:"model";s:4:"text";s:4:"enum";s:25:"选项1
		选项2
		选项3";s:5:"order";s:1:"6";}i:3;a:5:{s:4:"name";s:7:"content";s:5:"label";s:6:"描述";s:5:"model";s:3:"rte";s:4:"enum";s:25:"选项1
		选项2
		选项3";s:5:"order";s:1:"9";}}}';

		print_r(_decode($a));
	}

}