<?php
    require_once('./include/db_info.inc.php');
	require_once("./include/const.inc.php");
	require_once("./include/my_func.inc.php");
 // check user
$user=$_GET['user'];
if (!is_valid_user_name($user)){
	echo "No such User!";
	exit(0);
}
$view_title=$user ."@".$OJ_NAME;
$user_mysql=mysql_real_escape_string($user);
$sql="SELECT `school`,`email`,`skin` FROM `users` WHERE `user_id`='$user_mysql'";
$result=mysql_query($sql);
$row_cnt=mysql_num_rows($result);
if ($row_cnt==0){ 
	$view_errors= "No such User!";
	require("./error.php");
	exit(0);
}

$row=mysql_fetch_object($result);
$skin=$row->skin;
$school=$row->school;
$email=$row->email;
if ($email=='') $email='NO SUBMIT';
mysql_free_result($result);
// count solved
$sql="SELECT count(DISTINCT problem_id) as `ac` FROM `solution` WHERE `user_id`='".$user_mysql."' AND `result`=4";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_object($result);
$AC=$row->ac;
mysql_free_result($result);
// count submission
$sql="SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`='".$user_mysql."'";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_object($result);
$Submit=$row->Submit;
mysql_free_result($result);
// update solved 
$sql="UPDATE `users` SET `solved`='".strval($AC)."',`submit`='".strval($Submit)."' WHERE `user_id`='".$user_mysql."'";
$result=mysql_query($sql);
$sql="SELECT count(*) as `Rank` FROM `users` WHERE `solved`>$AC";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$Rank=intval($row[0])+1;
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
			<ul class="sub-menu light sidebar-dropdown-menu open">
				<?php require_once("include/metro-profile.php") ?>
            </ul>
        </li>
	</ul>
	</div>
<div class="page-region">
<div class="page-region-content">
<div class="grid">
<table>
	<tr>
		<td>名次</td>
		<td><?php echo $Rank ?>
	</tr>
	<tr>
		<td>AC题数</td>
		<td><?php echo $AC ?></td>
	</tr>
	<tr>
		<td>AC题目</td>
		<td>
			<?php $sql="SELECT DISTINCT `problem_id` FROM `solution` WHERE `user_id`='$user_mysql' AND `result`=4 ORDER BY `problem_id` ASC";	
			if (!($result=mysql_query($sql))) echo mysql_error();
			$i=0;
			while ($row=mysql_fetch_array($result))
			{
				echo "<a href='problem.php?id=".$row[0]."'>".$row[0]."</a> ";
				if(++$i%14==0) echo '<br>';
			}
			mysql_free_result($result);
			?>
		</td>
	</tr>
	<tr>
		<td>提交数</td>
		<td><?php echo $Submit ?></td>
	</tr>
	<tr>
		<td>通过率</td>
		<td><?php echo intval($AC/$Submit*100) ?>%</td>
	</tr>
	<tr>
		<td>学校</td>
		<td><?php echo $school ?></td>
	</tr>
	<tr>
		<td>邮箱</td>
		<td><?php echo $email ?></td>
	</tr>	<tr><td>正使用的皮肤</td><td><a href="changeskin.php?id=<?=$skin?>" title="切换到此皮肤"><?=$SKINSTR[$skin]?></a></td></tr>
</table>	
</div></div></div></div>
	<?php require_once("oj-footer.php");?>

