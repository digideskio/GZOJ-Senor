<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	if(isset($_SESSION['user_id'])) {header("Location: ./"); exit; }
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>注册页 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<script language="javascript" src="js_senor/registerpage.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/registerpage.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();registerpage_init();});</script>
</head>

<body>
<div id="preloader">
</div>
<?php 
$NO_OJHEADER=1;
require_once('include/senoroj-navbar.php');?>

<div id="container">
<div class="board" style="height:auto;padding-bottom:30px;"><h1 style="border-bottom:#F66 2px solid;">用户注册</h1>
<div align="center" style="margin-top:30px;font-weight:normal;">
<form id="frmReg" autocomplete="off">
	<div align="left" style="margin-left:200px;">**带‘<font color="red">*</font>’为必填项</div>
  <table width="633" height="304" border="0">
  <colgroup>
  <col>
  <col>
  <col>
  </colgroup>
  <tbody>
    <tr>
      <th width="114" scope="row">用户名</th>
      <td width="208"><input type="text" id="user-id" class="textbox" maxlength="16" style="width:180px" name="user_id"></td>
      <td width="5"><font color="red">*&nbsp;</font></td>
      <td width="292"><div id="user-id-notice" class="regnotice">3-20位英文字母、数字或下划线</div><div id="user-id-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">密码</th>
      <td><input type="password" id="regpwd" class="textbox" maxlength="16" style="width:180px" name="password"></td>
      <td><font color="red">*&nbsp;</font></td>
      <td><div id="regpwd-notice" class="regnotice">6-16位字符</div><div id="regpwd-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">重复密码</th>
      <td><input type="password" id="regpwd2" class="textbox" maxlength="16" style="width:180px" name="rptpassword"></td>
      <td><font color="red">*&nbsp;</font></td>
      <td><div id="regpwd2-notice" class="regnotice"></div><div id="regpwd2-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">学校</th>
      <td><input type="text" id="school" class="textbox" style="width:180px" name="school"></td>
      <td></td>
      <td><div id="regpwd2-notice" class="regnotice">如“<a href="javascript:" onclick="$('#school').val('江苏省赣榆高级中学');">江苏省赣榆高级中学</a>”</div><div id="school-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">电子邮箱</th>
      <td><input type="text" id="regemail" class="textbox" style="width:180px" name="email"></td>
      <td></td>
      <td><div id="regemail-notice" class="regnotice"></div><div id="regemail-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">首选语言</th>
      <td><div><input type="hidden" name="lang" value="1"><div class="select enabled" id="lang" name="lang" data-value="1" tabindex="0" style="width:80px;"><div class="c">C++</div><ul class="popform" style="display:none;opacity:1;"><li data-value="0">C</li><li data-value="1">C++</li><li data-value="2">Pascal</li></ul><span class="downarrow"></span></div></div></td>
      <td><font color="red">*&nbsp;</font></td>
      <td><div id="lang-notice" class="regnotice"></div><div id="lang-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">验证码</th>
      <td><input type="text" id="vcode" class="textbox" maxlength="4" style="width:80px;" style="width:180px" onkeypress="if(pswkey(event)) submitRegister();" name="vcode"><div style="display:inline;padding:0;margin:0;position:relative;"><a href="#refresh_verifycode" onclick="refresh_verifycode();" title="看不清？轻轻滴戳一下" ><div id="vcode-layer" style="position:absolute;display:block;top:-2px;left:20px;"><img src="vcode.php" id="verifycode-img" alt="验证码图形" /></div></a></div></td>
      <td><font color="red">*&nbsp;</font></td>
      <td><div id="vcode-notice" class="regnotice">输入图片中的四位字符</div><div id="vcode-alert" class="reg-alert"></div></td>
    </tr>
    </tbody>
  </table>
  <br>
  <div id="loading-register" class="loading-gif" style="bottom:35px;left:300px;display:none;" addcsst="background" addcssv="url(./img_senor/ok.png) no-repeat" addcssn="url(./img_senor/no.png) no-repeat"></div>
  <input type="button" id="btn-reg" class="button button-def" onclick="submitRegister();" value="注 册" style="height:33px;width:90px;" >
</form>	<div id="regstate" style="font-size:13px;color:green;display:inline-block;position:absolute;bottom:38px;left:500px;trasition:all 0.3s linear;"></div>
</div>
  
</div>
</div>
</body>
</html>