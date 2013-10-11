<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$isadmin = isset($_SESSION['administrator']);
	$sql="SELECT `title`, `cid`, `pid`, `status`, `top_level` FROM `topic` WHERE `tid` = '".mysql_escape_string($_REQUEST['tid'])."' AND `status` <= 1";
	$result=mysql_query($sql) or die("Error! ".mysql_error());
	$rows_cnt = mysql_num_rows($result) or die("Error! ".mysql_error());
	$row= mysql_fetch_object($result);
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title><?php echo nl2br(htmlspecialchars($row->title));?> - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();});</script>
</head>

<body>
<div id="preloader"></div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">


<?php if ($isadmin){
	?><div style="font-size:40%; float:right"> <?php $adminurl = "threadadmin.php?target=thread&tid={$_REQUEST['tid']}&action=";
	if ($row->top_level == 0) echo " <a href=\"{$adminurl}sticky&level=3\">总置顶</a>  <a href=\"{$adminurl}sticky&level=2\">分区置顶</a>  <a href=\"{$adminurl}sticky&level=1\">题目置顶</a>"; else echo " <a href=\"{$adminurl}sticky&level=0\">取消置顶</a> ";
	?> | <?php if ($row->status != 1) echo ("  <a  href=\"{$adminurl}lock\">锁住主题</a> "); else echo("  <a href=\"{$adminurl}resume\">解锁主题</a> ");
	?> | <?php echo ("  <a href=\"{$adminurl}delete\">删除主题</a> ");
	?></div><?php }
?>
<table style="width:100%; clear:both">
<tr align=center class='toprow'>
	<td style="text-align:left">
	<a href="<?php if ($row->pid!=0) echo "forum-problem-".$row->pid; else echo 'forum';?>">
	<?php if ($row->pid!=0) echo "Problem ".$row->pid; else echo "主版块";?></a> <?php if ($row->pid!=0) {?><a href="problem-<?=$row->pid?>" style="font-size:50%">[进入题目]</a><?php }?> >> <?php echo nl2br(htmlspecialchars($row->title));?></td>
</tr>
</table>
<?php
	$sql="SELECT `rid`, `author_id`, `time`, `content`, `status` FROM `reply` WHERE `topic_id` = '".mysql_escape_string($_REQUEST['tid'])."' AND `status` <=1 ORDER BY `rid`";
	$result=mysql_query($sql) or die("Error! ".mysql_error());
	$rows_cnt = mysql_num_rows($result);
	$cnt=0;

	for ($i=0;$i<$rows_cnt;$i++){
		?>
        <div id="post-layer-<?=$row->pid?>" class="disp-board" style="height:auto;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;">
        <?php
		mysql_data_seek($result,$i);
		$row=mysql_fetch_object($result);
		$url = "threadadmin.php?target=reply&rid={$row->rid}&tid={$_REQUEST['tid']}&action=";
		$isuser = strtolower($row->author_id)==strtolower($_SESSION['user_id']);
?>
		<table><tr><td>
		<a name=post<?php echo $row->rid;?>></a>
     <div style="display:inline;text-align:left; float:left; margin:0 10px"><span style="font-size:90%"> <a href="user-info-<?php echo $row->author_id?>"><?php echo $row->author_id; ?> </a> <?php echo $row->time; ?></span></div>
		<div class="mon" style="display:inline;text-align:right; float:right;font-size:40%">
			<?php if (isset($_SESSION['administrator'])) {?>  
			<span> <a href="
				<?php if ($row->status==0) echo $url."disable\">去使能";
				else echo $url."resume\">恢复使能";
				?> </a> </span>
			<?php } ?>
			<span> <a <?php if ($isuser || $isadmin) echo "href=".$url."delete";?> >删除</a> </span>
			<span style="width:5em;text-align:right;display:inline-block;font-weight:normal;margin:0 10px">
			<?php echo $i+1;?></span>
		</div>
		<div class=content style="text-align:left; clear:both; margin:10px 30px">
			<?php	if ($row->status == 0) echo nl2br(htmlspecialchars($row->content));
					else {
						if (!$isuser || $isadmin)echo "<div style=\"border-left:10px solid gray\"><font color=red><i>注意 : <br>该回复已被管理员封锁</i></font></div>";
						if ($isuser || $isadmin) echo nl2br(htmlspecialchars($row->content));
					}
			?>
		</div>
		<div style="text-align:left; clear:both; margin:10px 30px; font-weight:bold; color:red"></div>
	</td>
</tr></table></div><Br>
<?php
	}
?>


<?php if (isset($_SESSION['user_id'])){?>
        <div id="post-new-cr" class="disp-board" style="height:auto;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;">
<div style="font-size:80%;"><div style="margin:0 10px">发表新回复:</div></div>
<form action="post.php?action=reply" method=post>
<input type=hidden name=tid value=<?php echo $_REQUEST['tid'];?>>
<div><textarea name=content style="border:1px dashed #8080FF; width:700px; height:200px; font-size:75%;margin:0 10px; padding:10px"></textarea></div>
<div><input type="submit" style="margin:5px 10px" value="发表" class="button"></input></div>
</form>
</div>
<?php }
?>


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