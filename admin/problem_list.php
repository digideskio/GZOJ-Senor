<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator'])
                ||isset($_SESSION['contest_creator'])
                ||isset($_SESSION['problem_editor'])
                )){
        echo "<a href='../'>请先登录!</a>";
        exit(1);
}

$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt=50;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$cnt=intval($row->upid)-1000;
$cnt=intval($cnt/$page_cnt)+(($cnt%$page_cnt)>0?1:0);
if (isset($_GET['page'])){
        $page=intval($_GET['page']);
}else $page=$cnt;
$pstart=1000+$page_cnt*intval($page-1);
$pend=$pstart+$page_cnt;

echo "<title>题目列表</title>";
echo "<center><h2>题目列表</h2></center>";

for ($i=1;$i<=$cnt;$i++){
        if ($i>1) echo '&nbsp;';
        if ($i==$page) echo "<span class=red>$i</span>";
        else echo "<a href='problem_list.php?page=".$i."'>".$i."</a>";
}

$sql="select `problem_id`,`title`,`in_date`,`defunct` FROM `problem` where problem_id>=$pstart and problem_id<=$pend order by `problem_id` ";
//echo $sql;
$result=mysql_query($sql) or die(mysql_error());
echo "<center><table width=90% border=1>";
echo "<form method=post action=contest_add.php>";
echo "<tr><td colspan=7><input type=submit name='problem2contest' value='将选中项添加到比赛'>";
echo "<tr><td>PID<td>题目标题<td>添加日期";
if(isset($_SESSION['administrator'])||isset($_SESSION['problem_editor'])){
        if(isset($_SESSION['administrator']))   echo "<td>状态<td>删除";
        echo "<td>编辑<td>测试数据</tr>";
}
for (;$row=mysql_fetch_object($result);){
        echo "<tr>";
        echo "<td>".$row->problem_id;
        echo "<input type=checkbox name='pid[]' value='$row->problem_id'>";
        echo "<td><a href='../problem.php?id=$row->problem_id'>".$row->title."</a>";
        echo "<td>".$row->in_date;
        if(isset($_SESSION['administrator'])||isset($_SESSION['problem_editor'])){
                if(isset($_SESSION['administrator'])){
                        echo "<td><a href=problem_df_change.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">"
                        .($row->defunct=="N"?"<span titlc='点击使不可见' style=color:green>公开可见</span>":"<span style=color:red title='点击转换为可见'>暂时不可见</span>")."</a><td>";
                        if(1||$OJ_SAE||function_exists("system")){
                              ?>
                              <a href=# onclick='javascript:if(confirm("Delete?")) location.href="problem_del.php?id=<?php echo $row->problem_id?>&getkey=<?php echo $_SESSION['getkey']?>";'>
                              删除</a>
                              <?php
                        }
                }
                if(isset($_SESSION['administrator'])||isset($_SESSION["p".$row->problem_id])){
                        echo "<td><a href=problem_edit.php?id=$row->problem_id&getkey=".$_SESSION['getkey'].">编辑题目</a>";
                        echo "<td><a href=quixplorer/index.php?action=list&dir=$row->problem_id&order=name&srt=yes>测试数据</a>";
                }
        }
        echo "</tr>";
}
echo "<tr><td colspan=7><input type=submit name='problem2contest' value='将选中项添加到比赛'>";
echo "</tr></form>";
echo "</table></center>";
?>
									 
									 