<?php require("admin-header.php");

if (!(isset($_SESSION['administrator']))){
	echo "<a href='../'>请先登录!</a>";
	exit(1);
}?>
<?php if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	if (isset($_POST['rjpid'])){
		$rjpid=intval($_POST['rjpid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `problem_id`=".$rjpid;
		mysql_query($sql) or die(mysql_error());
		$sql="delete from `sim` WHERE `s_id` in (select solution_id from solution where `problem_id`=".$rjpid.")";
		mysql_query($sql) or die(mysql_error());
		$url="../status.php?problem_id=".$rjpid;
		echo "已经重判题目： ".$rjpid;
		echo "<script>location.href='$url';</script>";
	}
	else if (isset($_POST['rjsid'])){
		$rjsid=intval($_POST['rjsid']);
		$sql="UPDATE `solution` SET `result`=1 WHERE `solution_id`=".$rjsid;
		mysql_query($sql) or die(mysql_error());
		$sql="delete from `sim` WHERE `s_id`=".$rjsid;
		mysql_query($sql) or die(mysql_error());
		$url="../status.php?top=".($rjsid+1);
		echo "已经重判记录： ".$rjsid;
		echo "<script>location.href='$url';</script>";
	}
}
?>
<h1>重判</h1>
	<ol>
	<li>题目
	<form action='rejudge.php' method=post>
		<input type=input name='rjpid'>	<input type='hidden' name='do' value='do'>
		<input type=submit value=提交>
		<?php require_once("../include/set_post_key.php");?>
	</form>
	<li>记录
	<form action='rejudge.php' method=post>
		<input type=input name='rjsid'>	<input type='hidden' name='do' value='do'>
		<input type=hidden name="postkey" value="<?php echo $_SESSION['postkey']?>">
		<input type=submit value=提交><Br /><Br />
	</form>
