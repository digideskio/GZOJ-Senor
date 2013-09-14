<?php	
    require_once('./include/db_info.inc.php');
	if (!isset($_SESSION['user_id'])){
		$view_errors= "<a href=./loginpage.php>Please LogIn First!</a>";
		require("./error.php");
		exit(0);
	}

$sql="SELECT `school`,`email` FROM `users` WHERE `user_id`='".$_SESSION['user_id']."'";
$result=mysql_query($sql);
$row=mysql_fetch_object($result);

mysql_free_result($result);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>修改信息</title>
	
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
	<center><h2>修改信息</h2></center>
	<br>	
	<form action="modify.php" method="post">
	<table class="none_bordered">
		<tr class="span5">
			<td class="span2">用户名</td>
			<td class="span3">
				<div class="input-control text">
					<input type="text" disabled value="<?php echo $_SESSION['user_id'] ?>" />
					<?php require_once('./include/set_post_key.php');?>
				</div>
			</td>
		</tr>
		<tr class="span5">
			<td class="span2">原密码</td>
			<td class="span3">
				<div class="input-control password">
					<input type="password" name="opassword"/>
				</div>
			</td>
			<td class="span1">&nbsp;*</td>
		</tr>
		<tr class="span5">
			<td class="span2">新密码</td>
			<td class="span3">
				<div class="input-control password">
					<input type="password" name="npassword"/>
				</div>
			</td>
		</tr>
		<tr class="span5">
			<td class="span2">重复新密码</td>
			<td class="span3">
				<div class="input-control password">
					<input type="password" name="rptpassword"/>
				</div>
			</td>
		</tr>				
		<tr class="span5">
			<td class="span2">学校</td>
			<td class="span3">
				<div class="input-control text">
					<input type="text" name="school" value="<?php echo htmlspecialchars($row->school)?>" />
				</div>
			</td>
		</tr>
		<tr class="span5">
			<td class="span2">E-mail</td>
			<td class="span3">
				<div class="input-control text">
					<input type="text" name="email" value="<?php echo htmlspecialchars($row->email)?>" />
				</div>
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

