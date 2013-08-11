<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../'>请先登录!</a>";
	exit(1);
}
echo "<title>权限列表</title>"; 
echo "<center><h2>权限列表</h2></center>";
$sql="select * FROM privilege where rightstr in ('administrator','source_browser','contest_creator','http_judge','problem_editor') ";
$result=mysql_query($sql) or die(mysql_error());
echo "<center><table class='table table-striped' width=60% border=1>";
echo "<thead><tr><td>用户<td>权限<td>删除</tr></thead>";
for (;$row=mysql_fetch_object($result);){
	echo "<tr>";
	echo "<td>".$row->user_id;
	echo "<td>".$row->rightstr;
	echo "<td><a href=privilege_delete.php?uid=$row->user_id&rightstr=$row->rightstr&getkey=".$_SESSION['getkey'].">删除</a>";
	echo "</tr>";
}
echo "</table></center>";
?>
