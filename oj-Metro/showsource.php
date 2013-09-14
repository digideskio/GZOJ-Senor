<?php
    require_once('./include/db_info.inc.php');
	require_once("./include/const.inc.php");
	require_once("./include/my_func.inc.php");
	
if (!isset($_GET['id'])){
	$view_errors="无此记录\n";
	require("error.php");
	exit(0);
}

$ok=false;
$id=strval(intval($_GET['id']));
$sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
$result=mysql_query($sql);
$row=mysql_fetch_object($result);
$slanguage=$row->language;
$sresult=$row->result;
$stime=$row->time;
$smemory=$row->memory;
$sproblem_id=$row->problem_id;
$view_user_id=$suser_id=$row->user_id;
$cid=$row->contest_id;
mysql_free_result($result);

if (is_running($cid)) 
{
	$view_errors="比赛进行中，无法查看\n";
	require("error.php");
	exit(0);
}

if (isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id']) 
	$ok=true;
if (isset($_SESSION['administrator'])) 
	$ok=true;

	$sql="SELECT `source` FROM `source_code` WHERE `solution_id`=".$id;
	$result=mysql_query($sql);
	$row=mysql_fetch_object($result);
	if($row)
		$view_source=$row->source;
/////////////////////////compile
$compile_flag=false;
$sql="SELECT `error` FROM `compileinfo` WHERE `solution_id`=".$id;
$result=mysql_query($sql);
$trow=mysql_fetch_object($result);
if ($result && mysql_num_rows($result))
{
	$compile_flag=true;
	$compile=$trow->error;
}
mysql_free_result($result);

/////////////////////////problem-status
$re1=array();
$re2=array();
$i=0;
$sql="SELECT `judgeinfo` FROM `solution` WHERE `solution_id`=".$id;
$result=mysql_query($sql);
$trow=mysql_fetch_object($result);
if ($result) 
{
	$re1=explode(";",$trow->judgeinfo);
	foreach ($re1 as $row)
	{
		$re2[$i]=explode(",",$row);	
		$i++;
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>查看结果 P<?php echo "$id $row->title"?></title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">
	
</head>
<body class="metrouicss" onload="prettyPrint()">

	<?php require_once("oj-header.php");?>
<div class="page secondary">
<div class="page-region">	
<div class="page-region-content">
<div class="grid">
	
	<?php if ($compile_flag){ ?>
		<div class="row">
		<div style="overflow:hidden">
			<div class="bg-color-gray">
				<div class="page snapped bg-color-blue" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<br>
					<h2>fgfgf</h2>
				</div>
				<div class="page fill bg-color-gray span4" style= "padding-bottom:32767px !important;margin-bottom:-32767px   !important; ">
					<br>
					<?php echo $compile."\n" ?>
					<br>
				</div>
			</div>
		</div>
		</div>
		<br>
	<?php }else{ ?>
	
	<table>
		<thead>
			<th>测试点</th>
			<th>状态</th>
			<th>内存</th>
			<th>用时</th>
		</thead>
		
		<tbody>
	<?php
		foreach ($re2 as $row)
		{
			echo "<tr>";
			echo "<td>#".$row[0]."</td>";
			echo "<td class='fg-color-white ".$judge_color[$row[1]]."'>".$judge_result[$row[1]]."</td>";
			echo "<td>".$row[2]."KB</td>";
			echo "<td>".$row[3]."s</td>";
			echo "</tr>";
		}		
	?>
		</tbody>
	</table>
	<?php } ?>
		<pre class="prettyprint linenums">
<?php

   if ($ok==true){
    echo htmlspecialchars($view_source);
	}else{		
		echo "I am sorry, You could not view this code!";
	}
?>
		</pre>
</div>
</div>
</div>
</div>
<?php require_once("oj-footer.php");?>
<!--javascript && /body && /html  is in oj-footer.php & -->
