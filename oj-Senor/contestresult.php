<?
    require_once('include/db_info.inc.php');
	require_once('include/const.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
if(!isset($_GET['cid']))
{
	$view_errors="错误：竞赛号码未指定";
	include "error.php";
	exit;
}
$cid=intval($_GET['cid']);
if(isset($_SESSION['administrator']))
{
	if(!(mysql_fetch_object(mysql_query("select count(1) as count from `contest` where `contest_id`=$cid "))->count==1))
	{
		$view_errors="错误：错误。";
		include "error.php";
		exit;
	}
}
else if(!(mysql_fetch_object(mysql_query("select count(1) as count from `contest` where `contest_id`=$cid and `end_time`<now()"))->count==1))
{
	$view_errors="错误：错误。";
	include "error.php";
	exit;
}
$probarr=intval(mysql_fetch_object(mysql_query("select count(`num`) as `count` from `contest_problem` where `contest_id`=$cid order by `num`"))->count);
$row=mysql_fetch_array(mysql_query("select `contest_id`,`title`,`start_time`,`end_time` from `contest` where `contest_id`=$cid"));
$ctitle=htmlspecialchars($row['title']);
$res=(mysql_query("select `num`,`user_id`,`pass_rate`,`time`,`memory`,`problem_id` from `solution` where `contest_id`=$cid order by `solution_id` desc "));
//var_dump($row);
$totscore=array();
$tscore=array();
$tottime=array();
$totmem=array();
$isstated=array();
while($row=mysql_fetch_array($res))
{
	if(!isset($totscore[$row['user_id']]))
	{
		$totscore[$row['user_id']]=0;
		$tottime[$row['user_id']]=0;
		$totmem[$row['user_id']]=0;
	}
	if(isset($isstated[$row['user_id']][$row['num']])) continue;
	$isstated[$row['user_id']][$row['num']]=1;
	$totscore[$row['user_id']]+=intval($row['pass_rate']*100);
	$tottime[$row['user_id']]+=intval($row['time']);
	$totmem[$row['user_id']]+=intval($row['memory']);
	$tscore[$row['user_id']][$row['num']]=intval($row['pass_rate']*100);
}
array_multisort($totscore,SORT_DESC,SORT_NUMERIC,$tottime,SORT_ASC,SORT_NUMERIC,$totmem,SORT_ASC,SORT_NUMERIC);
$arr=array();
foreach($totscore as $key=>$val)
{
	array_push($arr,array('user'=>$key,'totscore'=>$totscore[$key],'tottime'=>$tottime[$key],'totmem'=>$totmem[$key],'problemdata'=>$tscore[$key]));
}
//var_dump($arr);
/*
var_dump($totscore);
var_dump($tottime);
var_dump($totmem);
var_dump($tscore);
*/
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title><?=$ctitle?> - GZOJ</title>
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
<div id="disp-board" class="disp-board" style="height:100px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;"><h1>比赛 <?=$ctitle?> - 结果</h1>
<div id="page-switcher-layer" style="margin-left:30px"></div>
</div>
<div class="board" style="margin-top:20px;padding-top:0px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<table id="result-tab" class="table table-striped content-box-header" style="font-size:15px;border-collapse:collapse;border-spacing:0;" align=center width=100%><tr  style='background-color:rgba(20%,80%,100%,0.3);font-size:15px;line-height:30px;padding-left:10px;padding-right:10px' ><td style="text-align:right;padding-right:10px;width:55px">排名</td><td>用户名</td><td style="text-align:center;width:60px">总分</td><?php for($i=0;$i<$probarr;$i++) echo '<td style="text-align:center">题目 '.$PID[$i].'</td>';?><td style="width:100px">总最大时间</td><td style="width:100px">总最大内存</td></tr>
<?php
foreach($arr as $key=>&$value)
{
?>
<tr style="line-height:22px;"><td style="text-align:right;padding-right:10px;width:55px;"><?=$key+1?></td><td><a target=_self href=user-info-<?=$value['user']?>><?=$value['user']?></a></td><td style="text-align:center;color:rgb(<?=$ScoreColor[$value['totscore']/$probarr][0]?>,<?=$ScoreColor[$value['totscore']/$probarr][1]?>,<?=$ScoreColor[$value['totscore']/$probarr][2]?>);background-color:rgb(<?=255-$ScoreColor[$value['totscore']/$probarr][0]?>,<?=255-$ScoreColor[$value['totscore']/$probarr][1]?>,<?=255-$ScoreColor[$value['totscore']/$probarr][2]?>);"><?=$value['totscore']?></td>
<?php 
for($i=0;$i<$probarr;$i++)
{
	if(!isset($value['problemdata'][$i])) $value['problemdata'][$i]=0;
	echo '<td style="text-align:center;color:rgb('.$ScoreColor[$value['problemdata'][$i]][0].','.$ScoreColor[$value['problemdata'][$i]][1].','.$ScoreColor[$value['problemdata'][$i]][2].')">'.$value['problemdata'][$i].'</td>';
}
?><td><?=$value['tottime']?></td><td><?=$value['totmem']?></td></tr>
<?php }?>
</table>
</div>
</div>
</body>
</html>