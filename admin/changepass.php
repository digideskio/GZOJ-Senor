<?php require_once("admin-header.php");?>
<?php if (!(isset($_SESSION['administrator'])|| isset($_SESSION['password_setter']) )){
	echo "<a href='../'>请先登录</a>";
	exit(1);
}
if(isset($_POST['do'])){
	//echo $_POST['user_id'];
	require_once("../include/check_post_key.php");
	//echo $_POST['passwd'];
	require_once("../include/my_func.inc.php");
	
	$user_id=$_POST['user_id'];
    $passwd =$_POST['passwd'];
    if (get_magic_quotes_gpc ()) {
		$user_id = stripslashes ( $user_id);
		$passwd = stripslashes ( $passwd);
	}
	$user_id=mysql_real_escape_string($user_id);
	$passwd=pwGen($passwd);
	$sql="update `users` set `password`='$passwd' where `user_id`='$user_id'  and user_id not in( select user_id from privilege where rightstr='administrator') ";
	mysql_query($sql);
	if (mysql_affected_rows()==1) echo "密码已经重设!";
  else echo "用户不存在，或者Ta是一个管理员!";
}
?>
<script>
function checkpost()
{
	if(frmchangepwd.pwd2.value==frmchangepwd.passwd.value) frmchangepwd.submit();
	else alert('错误：两次输入的密码不一致 <!-- added by 张森 -->');
}
</script>
<form action='changepass.php' method=post name=frmchangepwd><Br />
	<h1>密码重设：</h1><br />
	　用户名:<input type=text size=10 name="user_id"><br />
	　密　码:<input type=text size=10 name="passwd"><br />
    重复密码:<input type=text size=10 name="pwd2" /><br />
	<?php require_once("../include/set_post_key.php");?>
	<input type='hidden' name='do' value='do'>
	<input type=button value='  重设该用户的密码  ' onclick='checkpost();'>
</form>
