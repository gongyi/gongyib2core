<?php
class user_m extends m {
  function __construct()
  {
    parent::__construct();
    $this->table = 'user';
    $this->fields = array('email','username','password','post_time','update_time','level','info');
    $this->auth = 'auth';
    $this->login_err = '';  
  }
  
  function add_user($elem = FALSE)
  {
    $query_list = array();
    if(!$elem)$elem = $_POST;
    // 验证重复
    // 加密
    $elem['password'] = $this->encode($elem['password']);
    $this->add($elem);
  }
  
  function update_user($id , $elem = FALSE)
  {
    $query_list = array();
    if(!$elem)$elem = $_POST;
    // 验证重复
    // 加密
    $elem['password'] = $this->encode($elem['password']);
    $this->update($id , $elem);
  }
  
  private function encode($string)
  {
    return md5($string);
  }

  function login($username,$password)
  {
    $username = addslashes($username);
    $user = $this->db->query("select * from user where LOWER(username)=lower('$username')");
    if(!isset($user[0])){
      $this->login_err = '用户不存在！';
      return FALSE;
    }
    
    if($user[0]['password'] != $this->encode($password) )
    {
      $this->login_err = '密码错误！';
      return FALSE;
    }
    
    $auth = array(
      'id'    =>  $user[0]['id'],
      'level' =>  $user[0]['level'],
      'name'=>$user[0]['username'],
      'seed' => md5(SEED.$user[0]['id'].$user[0]['level'])
    );
    
    $value = serialize($auth);
    setcookie($this->auth, $value, time()+360000,"/"); /* expire in 10 hour */
    $this->update($user[0]['id'],array('update_time'=>time()));
    return TRUE;
  }

  function check()
  {
    if(isset($_COOKIE[$this->auth])){
      $u = unserialize($_COOKIE[$this->auth]);
      if(md5(SEED.$u['id'].$u['level'] == $u['seed'])){
        return $u;
      }
    }
    return array('id'=>0,'level'=>0);
  }
  
  function logout()
  {
    setcookie($this->auth, '', time()-36000,"/"); 
  }
}

/* validate functions */

function val_dist_email($email)
{
  $email = addslashes($email);
  $user = load('m/user_m')->get(" and lower(email) = lower('$email') ");
  if(isset($user[0])) return 'email 地址已经存在';
  return true;
}

function val_dist_username($username)
{
  $username = addslashes($username);
  $user = load('m/user_m')->get(" and lower(username) = lower('$username') ");
  if(isset($user[0])) return '用户名已经存在';
  return true;  
}
