<?php echo "12";exit;?>
<div class="col2" ><div class="padding sidebar">
<h4>常用操作</h4>
<ul class="square" >
<li><a href="<?=BASE?>admin/page/index/" >页面内容</a></li>
<li><a href="<?=BASE?>admin/link/index/" >菜单&链接</a></li>
<li><a href="<?=BASE?>admin/template/index/" >风格定制</a></li>
<li><a href="<?=BASE?>admin/model/index/" >数据原型</a></li>
<li><a href="<?=BASE?>admin/setting/index/" >网站设置</a></li>
</ul>

<br />

</div>
</div>
<div class="col8" ><div class="padding sidebar">
  <h4>欢迎登录 , <?=$u['name']?></h4>
  <div class="box" >今天是 <?=date('Y-m-d , 礼拜 w');?></div>
  <h4>概况</h4>
  <div class="box" >
    <table>
      <tr><th>页面总数</th><td><?=$tot?></td><th>用户数</th><td> <?=$user?></td></tr>
      <tr><th>Alpaca 版本</th><td><?=ALPA_VERSION?> </td><th>服务器</th><td><?=php_uname("s")?><?=php_uname("r")?></td></tr>
    <tr><th>PHP 版本</th><td><?=PHP_VERSION?></td><th>Mysql</th><td><?=mysql_get_server_info();?></td></tr>
  
    </table> 
  </div>
  
</div>
</div>
  <div class="col2" >
<div class="padding sidebar" >
 <h4> 实用信息</h4> 
<ul class="square" >
    <li><a href="http://alpaca.b24.cn/" target="_blank" >检查更新 </a> </li>
    <li><a href="http://alpaca.b24.cn/page/service/" target="_blank" >收费服务 </a> </li>
    <li><a href="http://alpaca.b24.cn/page/style/"  target="_blank" >风格选择</a></li>
    <li><a href="http://alpaca.b24.cn/page/help/"  target="_blank" >帮助文件</a></li>
    <li> <a href="http://alpaca.b24.cn/bbs/forum/3/"  target="_blank" >问题解答</a></li>
    <li><a href="http://colorschemedesigner.com/" target="_blank" >配色工具 </a> </li>
  </ul>  
</div>
  
  </div></div>
  
<div class="clear" ></div>
