<?php
////////////////////////////Common head
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
    require_once('./include/db_info.inc.php');
	$view_title= "Welcome To Online Judge";
	
///////////////////////////MAIN	
	
	$view_news="";
	$sql=	"SELECT * "
			."FROM `news` "
			."WHERE `defunct`!='Y'"
			."ORDER BY `importance` ASC,`time` DESC "
			."LIMIT 5";
	$result=mysql_query($sql);
	if (!$result){
		$view_news= "<h3>No News Now!</h3>";
		$view_news.= mysql_error();
	}else{
		$view_news.= "<table width=96%>";
		
		while ($row=mysql_fetch_object($result)){
			$view_news.= "<tr><td><td><big><b>".$row->title."</b></big>-<small>[".$row->user_id."]</small></tr>";
			$view_news.= "<tr><td><td>".$row->content."</tr>";
		}
		mysql_free_result($result);
		$view_news.= "<tr><td width=20%><td>This <a href=http://cm.baylor.edu/welcome.icpc>ACM/ICPC</a> OnlineJudge is a GPL product from <a href=http://code.google.com/p/hustoj>hustoj</a></tr>";
		$view_news.= "</table>";
	}
$view_apc_info="";

if(function_exists('apc_cache_info')){
	 $_apc_cache_info = apc_cache_info(); 
		$view_apc_info =_apc_cache_info;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>注册</title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">

</head>
<body class="metrouicss">
	<?php require_once("oj-header.php");?>
<div class="page secondary">
<div class="page-region">	
<div class="grid">
	<center><h2>注册</h2></center>
	<br>
	<form action="register.php" method="post">
	<table class="none_bordered">
		<tr class="span5">
			<td class="span2">用户名</td>
			<td class="span3">
				<div class="input-control text">
					<input type="text" name="user_id"/>
				</div>
			</td>
			<td>*</td>
		</tr>
		<tr class="span5">
			<td class="span2">密码</td>
			<td class="span3">
				<div class="input-control password">
					<input type="password" name="password"/>
				</div>
			</td>
			<td>*</td>
		</tr>
		<tr class="span5">
			<td class="span2">重复密码</td>
			<td class="span3">
				<div class="input-control password">
					<input type="password" name="rptpassword"/>
				</div>
			</td>
			<td>*</td>
		</tr>		
		<tr class="span5">
			<td class="span2">学校</td>
			<td class="span3">
				<div class="input-control text">
					<input type="text" name="school"/>
				</div>
			</td>
		</tr>
		<tr class="span5">
			<td class="span2">E-mail</td>
			<td class="span3">
				<div class="input-control text">
					<input type="text" name="email"/>
				</div>
			</td>
		</tr>
		<tr class="span6">
			<td class="span2">验证码</td>
			<td class="span3">
				<div class="input-control text">
					<input type="text" name="vcode"/>					
				</div>
			</td>
			<td class="span1">
				<img alt="click to change" src="vcode.php" onclick="this.src='vcode.php#'+Math.random()">
			</td>
		</tr>
	</table>
	<br><br>
	<center><input type="submit" name="submit" value="提交"/></center>

	</form>
</div>
</div>
</div>
<?php require_once("oj-footer.php");?>
<!--javascript && /body && /html  is in oj-footer.php & -->