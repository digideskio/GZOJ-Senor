<?php
	$OJ_CACHE_SHARE=true;
	$cache_time=30;
	require_once("./include/db_info.inc.php");
	require_once("./include/const.inc.php");
	require_once("./include/my_func.inc.php");

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);

$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<NOW()";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
if ($num==0){
	$view_errors= "Not Started!";
	require("template/".$OJ_TEMPLATE."/error.php");
	exit(0);
}
mysql_free_result($result);

$view_title= "Contest Statistics";

$sql="SELECT count(`num`) FROM `contest_problem` WHERE `contest_id`='$cid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$pid_cnt=intval($row[0]);
mysql_free_result($result);

$sql="SELECT `result`,`num`,`language` FROM `solution` WHERE `contest_id`='$cid' and num>=0"; 
$result=mysql_query($sql);
$R=array();
while ($row=mysql_fetch_object($result)){
	$res=intval($row->result)-4;
	if ($res<0) $res=8;
	$num=intval($row->num);
	$lag=intval($row->language);
	if(!isset($R[$num][$res]))
		$R[$num][$res]=1;
	else
		$R[$num][$res]++;
	if(!isset($R[$num][$lag+10]))
		$R[$num][$lag+10]=1;
	else
		$R[$num][$lag+10]++;
	if(!isset($R[$pid_cnt][$res]))
		$R[$pid_cnt][$res]=1;
	else
		$R[$pid_cnt][$res]++;
	if(!isset($R[$pid_cnt][$lag+10]))
		$R[$pid_cnt][$lag+10]=1;
	else
		$R[$pid_cnt][$lag+10]++;
	if(!isset($R[$num][8]))
		$R[$num][8]=1;
	else
		$R[$num][8]++;
	if(!isset($R[$pid_cnt][8]))
		$R[$pid_cnt][8]=1;
	else
		$R[$pid_cnt][8]++;
}
mysql_free_result($result);

$res=3600;

$sql="SELECT (UNIX_TIMESTAMP(end_time)-UNIX_TIMESTAMP(start_time))/100 FROM contest WHERE contest_id=$cid ";
        $result=mysql_query($sql);
        $view_userstat=array();
        if($row=mysql_fetch_array($result)){
              $res=$row[0];
        }
        mysql_free_result($result);

$sql=   "SELECT floor(UNIX_TIMESTAMP((in_date))/$res)*$res*1000 md,count(1) c FROM `solution` where  `contest_id`='$cid'   group by md order by md desc ";
        $result=mysql_query($sql);//mysql_escape_string($sql));
        $chart_data_all= array();
//echo $sql;
   
        while ($row=mysql_fetch_array($result)){
                $chart_data_all[$row['md']]=$row['c'];
    }
   
$sql=   "SELECT floor(UNIX_TIMESTAMP((in_date))/$res)*$res*1000 md,count(1) c FROM `solution` where  `contest_id`='$cid' and result=4 group by md order by md desc ";
        $result=mysql_query($sql);//mysql_escape_string($sql));
        $chart_data_ac= array();
//echo $sql;
   
        while ($row=mysql_fetch_array($result)){
                $chart_data_ac[$row['md']]=$row['c'];
    }
 
  mysql_free_result($result);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>比赛</title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#cs").tablesorter(); 
    } 
); 
</script>
    <script type="text/javascript">
$(function () {
    var d1 = [];
    var d2 = [];
    <?php
       foreach($chart_data_all as $k=>$d){
    ?>
        d1.push([<?php echo $k?>, <?php echo $d?>]);
        <?php }?>
    <?php
       foreach($chart_data_ac as $k=>$d){
    ?>
        d2.push([<?php echo $k?>, <?php echo $d?>]);
        <?php }?>
          //var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];

    // a null signifies separate line segments
    var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];
   
  $.plot($("#submission"), [
    {label:"<?php echo $MSG_SUBMIT?>",data:d1,lines: { show: true }},
    {label:"<?php echo $MSG_AC?>",data:d2,bars:{show:true}} ],{
   
       
            xaxis: {
              mode: "time"
              //,    max:(new Date()).getTime()
              //,min:(new Date()).getTime()-100*24*3600*1000
            },
        grid: {
            backgroundColor: { colors: ["#fff", "#333"] }
        }
        });
});
      //alert((new Date()).getTime());
</script>
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
	<center>
	<h3>数据统计</h3>
	<table class='striped'>
		<thead>
		<tr align=center>
			<td><center>题目</center><td class='fg-color-green'>AC<td>PE<td>WA<td>TLE<td>MLE<td>OLE<td>RE<td>CE<td>Total<td><td>C<td>C++<td>Pascal
		</tr>
		</thead>
		<tbody>
		<?php
		for ($i=0;$i<$pid_cnt;$i++){
			if(!isset($PID[$i])) $PID[$i]="";
			echo "<tr align=center><td><center><a href='problem.php?cid=$cid&pid=$i'>$PID[$i]</a></center>";
			for ($j=0;$j<13;$j++) {
				if(!isset($R[$i][$j])) $R[$i][$j]="";
				echo "<td>".$R[$i][$j];
			}
			echo "</tr>";
		}
		echo "<tr align=center><td class='fg-color-red'>Total";	
		for ($j=0;$j<13;$j++) {
			if(!isset($R[$i][$j])) $R[$i][$j]="";
			echo "<td>".$R[$i][$j];
		}
		echo "</tr>";
		?>
		</tbody>
	</table>
</div></div></div></div>
	<?php require_once("oj-footer.php");?>