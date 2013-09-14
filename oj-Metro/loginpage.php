<?php
    require_once("./include/db_info.inc.php");
	$view_title= "LOGIN";

	if (isset($_SESSION['user_id'])){
	echo "<a href=logout.php>Please logout First!</a>";
	exit(1);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>登陆</title>
	
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
	<center>
    <form action="login.php" method="post">

		<div class="row">
			<div class="span3"></div>
			<div class="span2"><h3>用户名:</h3></div>
				<div class="span3">
					<div class="input-control text">
						<input name="user_id" type="text" />
					</div>
				</div>
		</div>
		<div class="row">
			<div class="span3"></div>
			<div class="span2"><h3>密码:</h3></div>
			<div class="span3">
				<div class="input-control password">
					<input name="password" type="password" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span3"></div>
			<div class="span2"><h3>验证码:</h3></div>
			<div class="span2">
				<div class="input-control text">
					<input name="vcode" type="text" />
				</div>
			</div>
			<div class="span1">
				<img alt="click to change" src="vcode.php" onclick="this.src='vcode.php?'+Math.random()">
			</div>
		</div>
		<div class="row">
			<input name="submit" type="submit" value="登陆">
			<a href="lostpassword.php">忘记密码？</a>
		</div>
	
	</form>
	</center>
</div>
</div>
</div>
    <?php require_once("oj-footer.php");?>
<!--javascript && /body && /html  is in oj-footer.php & -->