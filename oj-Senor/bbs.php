<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$pid=0;
if(isset($_GET['pid'])) $pid=intval($_GET['pid']);
$prob_exist = problem_exist($pid, '');
if(!$prob_exist)
{
	$view_errors="指定的题目编号无效";
	include "error.php";
	exit(1);
}
$sql = "SELECT `tid`, `title`, `top_level`, `topic`.`status`, `cid`, `pid`, `reply`.`time` `posttime`, MAX(`reply`.`time`) `lastupdate`, `topic`.`author_id`, COUNT(`rid`) `count` FROM `topic` ,`reply` WHERE `topic`.`status`!=2 AND `reply`.`status`!=2 AND `tid` = `topic_id`";
if (array_key_exists("cid",$_REQUEST)&&$_REQUEST['cid']!='') $sql.= " AND ( `cid` = '".mysql_escape_string($_REQUEST['cid'])."'";
else $sql.=" AND ( ISNULL(`cid`)";
$sql.=" OR `top_level` = 3 )";
if (array_key_exists("pid",$_REQUEST)&&$_REQUEST['pid']!=''){
  $sql.=" AND ( `pid` = '".mysql_escape_string($_REQUEST['pid'])."' OR `top_level` >= 2 )";
  $level="";
}
else
  $level=" - ( `top_level` = 1 AND `pid` != 0 )";
$sql.=" GROUP BY `topic_id` ORDER BY `top_level`$level DESC, MAX(`reply`.`time`) DESC";
$sql.=" LIMIT 30";
//echo $sql;
$result = mysql_query($sql) or die("Error! ".mysql_error());
$rows_cnt = mysql_num_rows($result);
$cnt=0;
$isadmin = isset($_SESSION['administrator']);
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>讨论 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();});</script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="disp-board" class="disp-board" style="height:100px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;"><h1>讨论</h1>
<div style="margin-left:30px;color:grey;font-size:15px"><a href="forum">主版块</a> <?php if($pid!=0)echo "&gt;&gt;&nbsp;<a href='forum-problem-$pid'>P$pid</a>";?></div><div style="margin-left:50px;color:grey;font-size:15px"><a href="forum-new-thread">发表新主题</a></div>
</div>
<div class="board" style="margin-top:20px;padding-top:0px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<table id="result-tab" class="table table-striped content-box-header" style="font-size:12px;border-collapse:collapse;border-spacing:0;" align=center width=100%><tr  style='background-color:rgba(20%,80%,100%,0.3);font-size:15px;line-height:30px' ><td width="6%"></td><td width="6%" style="padding-left:10px">题目</td><td width="15%">作者</td><td width="29%">标题</td><td width="19%">发表时间</td><td width="19%">最后回复</td><td width="6%" style="padding-right:10px;text-align:right">回复数</td></tr><?php
for ($i=0;$i<$rows_cnt;$i++){
	mysql_data_seek($result,$i);
	$row=mysql_fetch_array($result);
	?><tr onmouseover='$(this).css("background-color","rgba(0,0,0,0.1);")' onmouseout='$(this).css("background-color","rgba(0,0,0,0);")' style="line-height:21px"><td style="padding-left:10px"><?php
		$MSG_TOP=array('','题目置顶','分区置顶','总置顶');
		if ($row['top_level']!=0){
			if ($row['top_level']!=1||$row['pid']==($pid==''?0:$pid))
			echo"<b class=\"Top{$row['top_level']}\" title='".$MSG_TOP[$row['top_level']]."'>置顶</b>";
		}
		else if ($row['status']==1) echo"<b class=\"Lock\">Lock</b>";
		else if ($row['count']>20) echo"<b class=\"Hot\">Hot</b>";?></td><td><a href="forum-problem-<?=$row['pid']>0?$row['pid']:""?>"><?=$row['pid']>0?$row['pid']:""?></a></td><td><a href="userinfo.php?user=<?=$row['author_id']?>" title="用户名：<?=$row['author_id']?>" style="color:grey" data-user="<?=htmlspecialchars($row['author_id'])?>"><?=htmlspecialchars($row['author_id'])?></a></td><td><a href="forum-thread-<?=$row['tid']?>"><?=htmlspecialchars($row['title'])?></a></td><td><?=$row['posttime']?></td><td><?=$row['lastupdate']?></td><td style="padding-right:20px;text-align:right"><?=$row['count']-1?></td></tr><?php }?></table>
</div>
</div>
</body>
</html><?php function problem_exist($pid,$cid){
	require_once("include/db_info.inc.php");
	if ($pid=='') $pid=0;
	if ($cid!='')
		$cid=intval($cid);
	else
		$cid='NULL';
	if($pid!=0)
		if($cid!='NULL')
			$sql="SELECT 1 FROM `contest_problem` WHERE `contest_id` = $cid AND `problem_id` = '".intval($pid)."'";
		else
			$sql="SELECT 1 FROM `problem` WHERE `problem_id` = ".intval($pid)."";
	else if($cid!='NULL')
		$sql="SELECT 1 FROM `contest` WHERE `contest_id` = $cid";
	else
		return true;
	$sql.=" LIMIT 1";
	//echo $sql;
	$result=mysql_query($sql) or print "db error";
	return mysql_num_rows($result)>0;
}?>