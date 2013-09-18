<?php
    require_once('include/db_info.inc.php');
    require_once('include/const.inc.php');
	require_once('include/my_func.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
if(!isset($_SESSION['user_id'])) header("Location: ./");
$result=mysql_query("select `user_id`,`email`,`school`,`language`,`password` from `users` where `user_id`='".$_SESSION['user_id']."'");
$row=mysql_fetch_object($result);
$user_id=$row->user_id;
$email=$row->email;
$school=$row->school;
$lang=$row->language;
$orgpassword=$row->password;
if(isset($_GET['POSTSENSORDATA']))
{
	$err_str=array();
	$err_obj=array();
	
	if(intval($_POST['lang'])<0 || intval($_POST['lang'])>3)
	{
		array_push($err_str,"语言编号有误");
		array_push($err_obj,"lang");
	}
	if(strcmp($_POST['orgpwd'],"")!=0)
	{
		if(!pwCheck($_POST['orgpwd'],$orgpassword))
		{
			array_push($err_str,"原密码输入错误");
			//echo $orgpassword;
			array_push($err_obj,"orgpwd");
		}
		if (strcmp($_POST['password'],$_POST['rptpassword'])!=0)
		{
			array_push($err_str,"两次输入的密码不一致");
			array_push($err_obj,"regpwd2");
		}
	}
	$len=strlen($_POST['school']);
	if ($len>100)
	{
		array_push($err_str,"学校名字也太长了吧……");
		array_push($err_obj,"school");
	}
	$len=strlen($_POST['email']);
	if ($len>100)
	{
		array_push($err_str,"E-mail也太长了吧……");
		array_push($err_obj,"regemail");
	}
	
	if(count($err_obj)!=0)
	{
		echo '{"no":'.count($err_obj).',"data":';
		$err_all=array();
		for($i=0;$i<count($err_obj);$i++)
		{
			array_push($err_all,(object)array('obj'=>$err_obj[$i],'str'=>$err_str[$i]));
		}
		echo json_encode((object)$err_all);
		echo '}';
		exit;
	}
	$school=$_POST['school'];
	$email=$_POST['email'];
	
	if(get_magic_quotes_gpc()){
		$school=stripslashes($school);
		$email=stripslashes($email);
	}
	$res=mysql_query("update `users` set `email`='".mysql_escape_string($email)."',`school`='".mysql_real_escape_string($school)."',`language`=".intval($_POST['lang']).(strcmp($_POST['orgpwd'],'')===0?'':" ,`password`='".pwGen($_POST['password'])."'")." where `user_id`='".$_SESSION['user_id']."'");
	if(!$res)
	{
		echo json_encode((object)array("no"=>-1,"err"=>mysql_error()));
	}
	else
	{
		echo '{"no":0,"err":"修改个人信息成功"}';
	}
	exit;
}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>个人设置 - GZOJ</title>
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
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="mainboard" class="board" style="padding-bottom:40px;"><h1>个人设置</h1>
<div style="margin-left:30px;font-size:13px">其他任务：<a href="skin">更换皮肤</a></div>
<div align="center" style="margin-top:30px;font-weight:normal;">
<form id="frmSet" autocomplete="off">
  <table width="633" height="304" border="0">
  <colgroup>
  <col>
  <col>
  <col>
  </colgroup>
  <tbody>
    <tr>
      <th width="114" scope="row">用户名</th>
      <td width="208"><input type="text" class="textbox disabled showerr" style="width:180px" value="<?=htmlspecialchars($_SESSION['user_id'])?>" disabled></td>
      <td width="5"></font></td>
      <td width="292"></td>
    </tr>
    <tr>
      <th scope="row">学校</th>
      <td><input type="text" id="school" class="textbox showerr" style="width:180px" name="school" value="<?=htmlspecialchars($school)?>"></td>
      <td></td>
      <td><div id="regpwd2-notice" class="regnotice">如“<a href="javascript:" onclick="$('#school').val('江苏省赣榆高级中学');">江苏省赣榆高级中学</a>”</div><div id="school-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">电子邮箱</th>
      <td><input type="text" id="regemail" class="textbox showerr" style="width:180px" name="email" value="<?=htmlspecialchars($email)?>"></td>
      <td></td>
      <td><div id="regemail-notice" class="regnotice"></div><div id="regemail-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">首选语言</th>
      <td><div><input type="hidden" name="lang" id="lang" class="showerr" value="<?=$lang?>"><div class="select enabled" id="lang2" name="lang" data-value="<?=$lang?>" tabindex="0" style="width:80px;"><div class="c"><?=$language_name[$lang]?></div><ul class="popform" style="display:none;opacity:1;"><li data-value="0">C</li><li data-value="1">C++</li><li data-value="2">Pascal</li></ul><span class="downarrow"></span></div></div></td>
      <td></td>
      <td><div id="lang-notice" class="regnotice"></div><div id="lang-alert" class="reg-alert"></div></td>
    </tr>    <tr>
      <th scope="row">原密码</th>
      <td><input type="password" id="orgpwd" class="textbox showerr" maxlength="16" style="width:180px" name="orgpwd"></td>
      <td></td>
      <td><div id="orgpwd-notice" class="regnotice">不改密码请留空</div><div id="orgpwd-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">密码</th>
      <td><input type="password" id="regpwd" class="textbox showerr" maxlength="16" style="width:180px" name="password"></td>
      <td></td>
      <td><div id="regpwd-notice" class="regnotice">6-16位字符</div><div id="regpwd-alert" class="reg-alert"></div></td>
    </tr>
    <tr>
      <th scope="row">重复密码</th>
      <td><input type="password" id="regpwd2" class="textbox showerr" maxlength="16" style="width:180px" name="rptpassword"></td>
      <td></td>
      <td><div id="regpwd2-notice" class="regnotice"></div><div id="regpwd2-alert" class="reg-alert"></div></td>
    </tr>
    </tbody>
  </table><br>  <div id="loading-register" class="loading-gif" style="bottom:35px;left:300px;display:none;" addcsst="background" addcssv="url(./img_senor/ok.png) no-repeat" addcssn="url(./img_senor/no.png) no-repeat"></div><input type="button" id="btn-settings-ch" class="button button-def" onclick="submitSettings();" value="应 用" style="height:33px;width:90px;" ></form><div id="regstate" style="font-size:13px;color:green;display:inline-block;position:absolute;bottom:38px;left:500px;trasition:all 0.3s linear;"></div></div>
</div>
</div>
</body>
</html>