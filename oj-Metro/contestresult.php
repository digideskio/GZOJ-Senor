<?
    require_once('./include/db_info.inc.php');
	require_once('./include/const.inc.php');
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
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>GZ-OJ</title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">

</head>
<body class="metrouicss">

	<?php require_once("oj-header.php");?>
<div class="page secondary with-sidebar">
	<div class="page-sidebar">
	<ul>
		<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-user"></i>用户</a>
			<ul class="sub-menu light sidebar-dropdown-menu">
				<?php require_once("include/metro-profile.php") ?>
            </ul>
        </li>
		<li class="sticker sticker-color-pink dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-user"></i>比赛相关</a>
			<ul class="sub-menu light sidebar-dropdown-menu open">
				<li><a href="./contest.php?cid=<?php echo $cid?>">问题</a></li>
				<li><a href="./status.php?cid=<?php echo $cid?>">记录</a></li>
				<li><a href="./contestresult.php?cid=<?php echo $cid?>">结果</a></li>
				<li><a href="./conteststatistics.php?cid=<?php echo $cid?>">统计</a></li>				
            </ul>
        </li>	
	</ul>
	</div>
<div class="page-region">
<div class="page-region-content">
<div class="grid">

	<center><h2>比赛 <?=$ctitle?> - 结果</h2></center>
	<br>
	<center>
	<table class="striped">
		<thead>
			<tr>
				<td><center>排名</center>
				<td><center>用户名</center>
				<td><center>总分</center>
				<?php
					for ($i=0;$i<$probarr;$i++)
						echo "<td><center>题目".$PID[$i]."</center></td>";
				?>
				<td><center>总时间</center>
				<td><center>总内存</center>
			</tr>
		</thead>

	<?php
		foreach($arr as $key=>&$value)
		{
	?>
		<tr>
			<td><center><?=$key+1?></center></td>
			<td><center><?=$value['user']?></center></td>
			<td class="fg-color-blue"><center><?=$value['totscore']?></center></td>
			<?php 
				for($i=0;$i<$probarr;$i++)
				{
					if(!isset($value['problemdata'][$i])) $value['problemdata'][$i]=0;
					echo "<td class='";
					if ($value['problemdata'][$i]==100)
						echo "fg-color-green";
					else echo "fg-color-orange";
					echo "'><center>".$value['problemdata'][$i]."</center></td>";
				}
			?>
			<td class="fg-color-pink"><center><?=$value['tottime']?></center></td>
			<td class="fg-color-blueDark"><center><?=$value['totmem']?></center></td>
		</tr>
	<?php }?>
</table>
</center>
</div></div></div></div>
<?php require_once("oj-footer.php");?>