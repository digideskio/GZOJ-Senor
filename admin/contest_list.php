<?php require("admin-header.php");
echo "<title>竞赛列表</title>";
echo "<center><h2>竞赛列表</h2></center>";
require_once("../include/set_get_key.php");
$sql="SELECT max(`contest_id`) as upid, min(`contest_id`) as btid  FROM `contest`";
$page_cnt=50;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$base=intval($row->btid);
$cnt=intval($row->upid)-$base;
$cnt=intval($cnt/$page_cnt)+(($cnt%$page_cnt)>0?1:0);
if (isset($_GET['page'])){
        $page=intval($_GET['page']);
}else $page=$cnt;
$pstart=$base+$page_cnt*intval($page-1);
$pend=$pstart+$page_cnt;
for ($i=1;$i<=$cnt;$i++){
        if ($i>1) echo '&nbsp;';
        if ($i==$page) echo "<span class=red>$i</span>";
        else echo "<a href='contest_list.php?page=".$i."'>".$i."</a>";
}
$sql="select `contest_id`,`title`,`start_time`,`end_time`,`private`,`defunct` FROM `contest` where contest_id>=$pstart and contest_id <=$pend order by `contest_id` desc";

$result=mysql_query($sql) or die(mysql_error());
echo "<center><table width=90% border=1>";
echo "<tr><td>ContestID<td>标题<td>开始时间<td>结束时间<td>是否公开<td>状态<td>编辑<td>复制<td>导出<td>记录"; 
echo "</tr>";
for (;$row=mysql_fetch_object($result);){
	echo "<tr>";
	echo "<td>".$row->contest_id;
	echo "<td><a href='../contest.php?cid=$row->contest_id'>".$row->title."</a>";
	echo "<td>".$row->start_time;
	echo "<td>".$row->end_time;
	$cid=$row->contest_id;
	if(isset($_SESSION['administrator'])||isset($_SESSION["m$cid"])){
		echo "<td><a href=contest_pr_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->private=="0"?"公开，点击转为不公开":"不公开，点击转为公开")."</a>";
		echo "<td><a href=contest_df_change.php?cid=$row->contest_id&getkey=".$_SESSION['getkey'].">".($row->defunct=="N"?"<span style=color:green>Available</span>":"<span style=color:red>Reserved</span>")."</a>";
		echo "<td><a href=contest_edit.php?cid=$row->contest_id>编辑</a>";
		echo "<td><a href=contest_add.php?cid=$row->contest_id>复制</a>";
		if(isset($_SESSION['administrator'])){
			echo "<td><a href=\"problem_export_xml.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">导出</a>";
		}else{
		  echo "<td>";
		}
     echo "<td> <a href=\"../export_contest_code.php?cid=$row->contest_id&getkey=".$_SESSION['getkey']."\">比赛记录</a>";
	}else{
		echo "<td colspan=5 align=right><a href=contest_add.php?cid=$row->contest_id>复制</a><td>";
		
	}
		
	echo "</tr>";
}
echo "</table></center>";
?>
