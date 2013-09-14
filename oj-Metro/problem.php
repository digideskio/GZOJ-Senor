<?php 

    require_once('./include/db_info.inc.php');

	$now=strftime("%Y-%m-%d %H:%M",time());
if (isset($_GET['cid'])) $ucid="&cid=".intval($_GET['cid']);
else $ucid="";

$pr_flag=false;
$co_flag=false;
if (isset($_GET['id'])){
	// practice
	$id=intval($_GET['id']);

	$sql="SELECT * FROM `problem` WHERE `problem_id`=$id AND `defunct`='N' AND `problem_id` NOT IN (
			SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
					SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now'))
                        ";
	$pr_flag=true;
}else if (isset($_GET['cid']) && isset($_GET['pid'])){
	// contest
	$cid=intval($_GET['cid']);
	$pid=intval($_GET['pid']);

	if (!isset($_SESSION['administrator']))
		$sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid AND `start_time`<'$now' AND `end_time`>'$now'";
	else
		$sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid";
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
	
		require("error.php");
		exit(0);
	}else{
		// started
		$sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
			SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid
			)";
	}
	// public
	if (!$contest_ok){
	
		$view_errors= "你未被邀请！";
		require("error.php");
		exit(0);
	}
	$co_flag=true;
}else{
	$view_errors= "题目不存在!";
	require("error.php");
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
	      $view_errors.= "此问题被用于以下比赛<br>";
		   for (;$i>0;$i--){
				$row=mysql_fetch_row($result);
				$view_errors.= "<a href=problem.php?cid=$row[0]&pid=$row[2]>Contest $row[0]:$row[1]</a><br>";				
			}				
		}else{
			$view_errors.= "题目不存在!";
		}
   }else{
		$view_errors.="题目不存在!";
	}
	require("error.php");
	exit(0);
}else{
	$row=mysql_fetch_object($result);
	
	$view_title= $row->title;
	
}
mysql_free_result($result);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>P<?php echo "$id $row->title"?></title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">
	
</head>
<body class="metrouicss">
	<?php
	if(isset($_GET['id']))
		require_once("oj-header.php");
	else
		require_once("contest-header.php");
	?>
<div class="page secondary with-sidebar">
		
		<div class="row">
			<div class="span12">
			<?php
				if ($pr_flag)
					echo "<h2 class='bg-color-blueLight fg-color-blueDark'>Problem <strong>$id</strong><span class='fg-color-darken'> $row->title</span></h2>";
				else
				{
					$PID="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
					echo "<h2 class='bg-color-blueLight fg-color-darken'>Problem<strong> ".$PID[$pid]." </strong>$row->title</h2>";
				}
			?>
			</div>
		</div>

		<div class="page-sidebar">
			<ul>
				<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
					<a style="text-align:center;font-weight:bold"><i class="icon-user"></i>用户</a>
					<ul class="sub-menu light sidebar-dropdown-menu">
						<?php require_once("include/metro-profile.php") ?>
					</ul>
				</li>
				
				<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
					<a href="#" style="text-align:center;font-weight:bold">题目信息</a>
					<ul class="sub-menu light sidebar-dropdown-menu open keep-opened">
						<li><div class="fg-color-green">时间限制</div><?php echo $row->time_limit?>Sec</li>
						<li><div class="fg-color-green">内存限制</div><?php echo $row->memory_limit?>MB</li>
						<li><div class="fg-color-red">提交</div><?php echo $row->submit?></li>
						<li><div class="fg-color-red">通过</div><?php echo $row->accepted?></li>
					</ul>
				</li>
				
				<li class="sticker sticker-color-pink dropdown active" data-role="dropdown">
					<a href="#" style="text-align:center;font-weight:bold">功能</a>
					<ul class="sub-menu light sidebar-dropdown-menu">
					<?php
					if ($pr_flag)
					{
						echo "<li><a href='submitpage.php?id=$id'><center>提交</center></a></li>";
					}else
					{
						echo "<li><a href='submitpage.php?cid=$cid&pid=$pid&langmask=$langmask'><center>提交</center></a></li>";
					}
					echo "<li><a href='status.php?problem_id=".$row->problem_id."'><center>记录</center></a></li>";
					if(isset($_SESSION['administrator']))
					{
						require_once("include/set_get_key.php");
						echo "<li><a href=admin/problem_edit.php?id=$id&getkey=".$_SESSION['getkey']." ><center>编辑</center></a></li>";
					}
					?>
					</ul>
				</li>				
					
			</ul>
		</div>	
		
	<div class="page-region">	
	<div class="page-region-content">
	<div class="grid">
		<div class="row">
			<div class="span9" style="overflow:hidden">
				<div class="page snapped bg-color-blue" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<h2 class="fg-color-white"><strong><center>描述</center></strong></h2>
				</div>
				<div class="page fill bg-color-gray" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<?php echo $row->description?>
					<p>&nbsp;</p>
				</div>
			</div>
		</div>		
		<div class="row">
			<div class="span9" style="overflow:hidden">
				<div class="page snapped bg-color-blue" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<h3 class="fg-color-white"><strong><center>输入要求</center></strong></h3>
				</div>
				<div class="page fill bg-color-gray" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<?php echo $row->input?>
					<p>&nbsp;</p>
				</div>
			</div>
		</div>					
		<div class="row">
			<div class="span9" style="overflow:hidden">
				<div class="page snapped bg-color-blue" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<h3 class="fg-color-white"><strong><center>输出要求</center></strong></h3>
				</div>
				<div class="page fill bg-color-gray" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<?php echo $row->output?>
					<p>&nbsp;</p>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="span9" style="overflow:hidden">
				<div class="page snapped bg-color-blue" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<h3 class="fg-color-white"><strong><center>输入样例</center></strong></h3>
				</div>
				<div class="page fill bg-color-gray" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<?php echo $row->sample_input?>
					<p>&nbsp;</p>
				</div>
			</div>
		</div>	  
		<div class="row">
			<div class="span9" style="overflow:hidden">
				<div class="page snapped bg-color-blue" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<h3 class="fg-color-white"><strong><center>输出样例</center></strong></h3>
				</div>
				<div class="page fill bg-color-gray" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<?php echo $row->sample_output?>
					<p>&nbsp;</p>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="span9" style="overflow:hidden">
				<div class="page snapped bg-color-blueDark" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<h3 class="fg-color-blueLight"><strong><center>提示</center></strong></h3>
				</div>
				<div class="page fill bg-color-gray" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<?php echo nl2br($row->hint)?>
					<p>&nbsp;</p>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="span9" style="overflow:hidden">
				<div class="page snapped bg-color-blueDark" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<h3 class="fg-color-blueLight"><strong><center>来源</center></strong></h3>
				</div>
				<div class="page fill bg-color-gray" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<p>&nbsp;</p>
					<?php echo nl2br($row->source)?>
					<p>&nbsp;</p>
				</div>
			</div>
		</div>	
	</div>
	</div>
</div>
</div>
	<?php require_once("oj-footer.php");?>
<!--javascript && /body && /html  is in oj-footer.php & -->