<?php require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../'>鹳狸猿，请先登录!</a>";
	exit(1);
}
if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	$fp=fopen("msg.txt","w");
	fputs($fp, stripslashes($_POST['msg']));
	fclose($fp);
	echo "于 ".date('Y-m-d H:i:s')." 成功更新了公告<Br>";
}


$msg=file_get_contents("msg.txt");

?>
	<h1>设置公告</h1>
	<form action='setmsg.php' method='post'>
		<textarea name='msg' rows=25 class="input input-xxlarge" style="width:600px;height:360px;" ><?php echo htmlspecialchars($msg)?></textarea><br>
		<input type='hidden' name='do' value='do'><br />
		<input type='submit' value='  更 新  ' style="width:70px;height:40px;">
		<?php require_once("../include/set_post_key.php");?>
	</form>
	
<?php require_once('../oj-footer.php');
?>
