<? 
class home extends admin{

  function __construct()
  {
    parent::__construct();
    //$this->m = load('m/elem_m');
  }

  function index()
  {
  	$param = array();
  	view('admin/home');exit(0);
  	return;
    $tot = $this->m->count("and `mod`='page'");
    $user = load('m/user_m')->count();
    $param =  array('tot'=>$tot,'user'=>$user);
    $this->display('v/admin/home',$param);
  }
}
