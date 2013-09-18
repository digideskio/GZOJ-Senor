<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
?>
<?php
$now=strftime("%Y-%m-%d %H:%M",time());
if (isset($_GET['cid'])) $ucid="&cid=".intval($_GET['cid']);
else $ucid="";
$pr_flag=false;
$co_flag=false;
if (isset($_GET['id'])){
	// practice
	$id=intval($_GET['id']);
	if (!isset($_SESSION['administrator']) &&!isset($_SESSION['contest_creator']))
		$sql="SELECT * FROM `problem` WHERE `problem_id`=$id AND `defunct`='N' AND `problem_id` NOT IN (
				SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
						SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' ))
                                ";
	else
		$sql="SELECT * FROM `problem` WHERE `problem_id`=$id";

	$pr_flag=true;
}else if (isset($_GET['cid']) && isset($_GET['pid'])){
	// contest
	$cid=intval($_GET['cid']);
	$pid=intval($_GET['pid']);

	if (!isset($_SESSION['administrator']))
		$sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid AND `start_time`<'$now' and `end_time`>'$now'";
	else
		$sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid ";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	$row=mysql_fetch_row($result);
	$contest_ok=true;
	if ($row[1] && !isset($_SESSION['c'.$cid])) $contest_ok=false;
	if ($row[2]=='Y') $contest_ok=false;
	if (isset($_SESSION['administrator'])) $contest_ok=true;
				
	
    $ok_cnt=$rows_cnt==1;		
	$langmask=$row[0];
	mysql_free_result($result);
	if ($ok_cnt!=1){
		// not started
		$view_errors=  "指定的竞赛不存在，或还没有开始，或已经结束";
		//显示错误
		require "error.php";
		exit(0);
	}else{
		// started
		$sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
			SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid
			)";
	}
	// public
	if (!$contest_ok){
		$view_errors= "你并未被邀请到这个竞赛中";
		//显示错误
		require "error.php";
		exit(0);
	}
	$co_flag=true;
}else{
	$view_errors=  "该问题不存在";
	//显示错误
	require "error.php";
	exit(0);
}

$result=mysql_query($sql) or die(mysql_error());


if (mysql_num_rows($result)!=1){
   $view_errors="";
   if(isset($_GET['id'])){
      $id=intval($_GET['id']);
	   mysql_free_result($result);
	   $sql="SELECT  contest.`contest_id` , contest.`title`,contest_problem.num FROM `contest_problem`,`contest` WHERE contest.contest_id=contest_problem.contest_id and `problem_id`=$id and defunct='N'  ORDER BY `num`";
	   //echo $sql;
           $result=mysql_query($sql);
	   if($i=mysql_num_rows($result)){
	      $view_errors.= "这个问题已经在下列竞赛中了哦~<br>";
		   for (;$i>0;$i--){
				$row=mysql_fetch_row($result);
				$view_errors.= '<a href="contest-p-'.$row[0].'-'.$row[2].'">竞赛 '.$row[0].':'.$row[1].'</a><br>';
				
			}
			require "error.php";
				
		}else{
			$view_errors.= "题目不存在";
			require "error.php";
		}
   }else{
		$view_errors.= "题目不存在";
		require "error.php";
	}
	//显示错误
	exit(0);
}else{
	$row=mysql_fetch_object($result);

	
	$view_title= $row->title;

		
}
mysql_free_result($result);
$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$id=$row->problem_id;
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title><?=$view_title?> - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<script language="javascript" src="js_senor/problem.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/problem.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();problem_init();});</script>
<script><?php  
if($pr_flag)
	echo "var id=$id,iscontest=0;";
else echo "var id=$id,pid=$pid,cid=$cid,iscontest=1;";
if(isset($_SESSION['user_id']))
{
$res_lang=mysql_query("select `language` from `users` where `user_id`='".$_SESSION['user_id']."'");
$row_lang=mysql_fetch_array($res_lang);
echo "var optlang=".$row_lang['language'].";";
}
else
{
	echo "var optlang=1;";
}
 ?></script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>
<div id="container">

<div id="ptop-info" class="disp-board" style="height:90px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;"><h1 style="inline-block">P<?=$pr_flag?$id:'roblem '.$PID[$pid]?>: <?=$view_title?></h1><div id="solution_button"></div>
<div id="disp-problem-info">时间限制：<?=$row->time_limit?>秒&nbsp;&nbsp;内存限制：<?=$row->memory_limit?> MiB&nbsp;&nbsp;加入时间：<?=$row->in_date?>&nbsp;&nbsp;通过：<?=$row->accepted?>&nbsp;&nbsp;提交：<?=$row->submit?> <div style="margin-top:10px;margin-left:30px;"><?php if($pr_flag){?><a href="problem-status-problem_id=<?=$id?>">查看本题提交记录</a> <?php }?><div id="view_tot_stat" style="display:none;margin-left:30px"></div></div></div>
<div id="submitlayer" style="line-height:30px;display:none"><div id="sbutton_layer"><a class="button button-def" style="padding:0;width:80px;text-align:center;" onclick="submitProblem();">提交</a></div><div id="talk-problem"><?php if($pr_flag){?><a class="button" style="padding:0;width:80px;text-align:center;" href="forum-problem-<?=$id?>" target=_blank>讨论</a><?php }?></div><div id="btn-editproblem-layer"></div></div>
</div>
<div id="problem-show-board" class="board"><div><h2>描述</h2></div><div class="p-info"><?=SenorOp($row->description)?></div><?php if($row->input!=''){?><div><h2>输入格式</h2></div><div class="p-info"><?=str_replace("\n","<br>",$row->input)?></div><?php }?><?php if($row->output!=''){?><div><h2>输出格式</h2></div><div class="p-info"><?=str_replace("\n","\n",$row->output)?></div><?php }?><div><h2>样例输入</h2></div><div class="p-info"><?=str_replace("<br><br>","<br>",str_replace("\n","<br>",$row->sample_input))?></div><div><h2>样例输出</h2></div><div class="p-info"><?=str_replace("<br><br>","<br>",str_replace("\n","<br>",$row->sample_output))?></div><?php if($row->hint!=''){?><div><h2>Hint</h2></div><div class="p-info"><?=SenorOp($row->hint)?></div><?php }?><?php if($row->source!=''){?><div><h2>来源</h2></div><div class="p-info"><?=str_replace("\n","<br>",$row->source)?></div><?php }?>
<div id="problem-footer"></div></div>
</div>
</body>
</html>
<?php
function SenorOp($t)
{
	return $t;	//撤销
	$t=str_replace("[/b]","</strong>",str_replace("[b]","<strong>",$t));
	if(!preg_match('/<br>|< \/br>/',$t))
		$t=str_replace("\n","<br>",$t);
	//if(!preg_match('/nbsp/',$t))
	//	$t=str_replace(" ","&nbsp;",$t);
	//else
	//	$t=str_replace(" ","",$t);
	return $t;
}

?>

<!--
张森暂留

-->