<?php require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../'>如果你是管理员，先登录，否则就什么也不干</a>";
	exit(1);
}
?>

<form action='problem_export_xml.php' method=post>
	<b>导出问题:</b><br />
	从问题:<input type=text size=10 name="start" value=1000>
	到问题:<input type=text size=10 name="end" value=1000><br />
	或，为下所列：<input type=text size=40 name="in" value=""><br />
	<input type='hidden' name='do' value='do'>
	<input type=submit name=submit value='导出'>
   <input type=submit value='下载(会弹出保存对话框)'>
   <?php require_once("../include/set_post_key.php");?>
</form>
* 当“为下所列”为空时，从问题、到问题得到执行。 <br>
* 如果使用了“为下所列”，从问题、到问题将会被忽略。<br>
* “为下所列”使用英文半角标点：逗号（,）来分隔不同的题号，如：  1000,1002,1007  <br /><br />
