<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!isset($_SESSION['administrator'])){
	echo "<a href='../'>请先登录！</a>";
	exit(1);
}
echo "<title>新闻列表</title>";
echo "<center><h2>新闻列表</h2></center>";
if(isset($_GET['succdel'])) echo '<script>alert("成功删除了一条新闻");</script>';
$sql="select `news_id`,`user_id`,`title`,`time`,`defunct` FROM `news` order by `news_id` desc";
$result=mysql_query($sql) or die(mysql_error());
echo "<center><table width=90% border=1>";

echo "<tr><td>NID<td>标题<td>日期<td>状态<td>编辑<td>删</tr>";
for (;$row=mysql_fetch_object($result);){
	echo "<tr>";
	echo "<td>".$row->news_id;
	//echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
	echo "<td><a href='news_edit.php?id=$row->news_id'>".$row->title."</a>";
	echo "<td>".$row->time;
	echo "<td><a href=news_df_change.php?id=$row->news_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span style=color:green>可见</span>":"<span style=color:red>不可见</span>")."</a>";
		echo "<td><a href=news_edit.php?id=$row->news_id>编辑</a>";
	echo "<td><a href=news_delete.php?id=$row->news_id onclick='if(!confirm(\"确定要删除这条新闻吗？\\n\"+".json_encode((object)array($row->title))."[0]))return false;'>删</a>";
	echo "</tr>";
}

echo "</tr></form>";
echo "</table></center>";
?><br><br><br>

