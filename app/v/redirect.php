<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="1;url=<?=$url?>" />
<title> <?=$msg?> </title>
<link type="text/css" href="<?=BASE?>static/alpa.css" rel="stylesheet" />
</head>
<body>
<div id="wrap" style="padding:30px;">
  <div id="tuan_main" class="box" >
    <h1><?=$msg?></h1>
    <div><?=$ext_msg?></div>
    <div class="msga1" >页面跳转至: <a href="<?=$url?>" ><?=$url?></a>
     <br />你可以点击 <a href="<?=$url?>" >直接前往</a></div>
  </div>
</div>
</body>
</html>
