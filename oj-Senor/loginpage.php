<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	if(isset($_SESSION['user_id'])) header('Location: ./');
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>登录 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();can_refresh=1});</script>
</head>

<body>
<div id="preloader">
</div>
<?php
$NO_OJHEADER=1;
require('include/senoroj-navbar.php');?>

<div id="container">
<div id="mainboard" class="board" style="min-height:100px;width:380px;top:120px;padding-bottom:50px"><h1>登录</h1>
<div style="width:90%;margin-left:auto;margin-right:auto;"><table border="0" style="margin-left:10px;;text-align:left;line-height:33px;"><tr><td width="26%">用户名</td><td><input type="text" id="loginusr" class="textbox"/></td></tr><tr><td>密码</td><td><input type="password" id="loginpwd" class="textbox" /></td></tr><tr><td>验证码</td><td style="vertical-align:middle;"><input type="text" id="loginvrc" style="width:60px" class="textbox" onkeydown="if(pswkey(event))submitlogin();" autocomplete="off" /><div style="display:inline;vertical-align:middle;margin-left:10px;margin-top:10px;"><a href="javascript:" onclick="refresh_verifycode();" title="看不清？轻轻滴戳一下" ><div id="vcode-layer" style="display:inline;vertical-align:middle;" styleno="padding:4px;top:-6px;left:14px;position:absolute;"><img src="vcode.php" ></div></a></div></td></tr><tr><td></td><td><center><input type="button" value="登录" class="button button-def" onclick="submitlogin('not-undefined-value');" /></center></td></tr></table><div id="loading-login" class="loading-gif" style="display:none"></div><div id="login-err-text" style="display:none;position:absolute;bottom:20px;top:auto;left:auto;"></div></div>
</div>
</body>
</html>