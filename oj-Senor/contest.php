<?php
    require_once('include/db_info.inc.php');
    require_once('include/const.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
  function formatTimeLength($length)
{
	$hour = 0;
	$minute = 0;
	$second = 0;
	$result = '';
	
	if ($length >= 60)
	{
		$second = $length % 60;
		if ($second > 0)
		{
			$result = $second . '秒';
		}
		$length = floor($length / 60);
		if ($length >= 60)
		{
			$minute = $length % 60;
			if ($minute == 0)
			{
				if ($result != '')
				{
					$result = '0分' . $result;
				}
			}
			else
			{
				$result = $minute . '分' . $result;
			}
			$length = floor($length / 60);
			if ($length >= 24)
			{
				$hour = $length % 24;
				if ($hour == 0)
				{
					if ($result != '')
					{
						$result = '0小时' . $result;
					}
				}
				else
				{
					$result = $hour . '小时' . $result;
				}
				$length = floor($length / 24);
				$result = $length . '天' . $result;
			}
			else
			{
				$result = $length . '小时' . $result;
			}
		}
		else
		{
			$result = $length . '分' . $result;
		}
	}
	else
	{
		$result = $length . '秒';
	}
	return $result;
}

	if (isset($_GET['cid'])){
			$cid=intval($_GET['cid']);
			$view_cid=$cid;
		//	print $cid;
			
			
			// check contest valid
			$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' ";
			$result=mysql_query($sql);
			$rows_cnt=mysql_num_rows($result);
			$contest_ok=true;
			
			
			if ($rows_cnt==0){
				mysql_free_result($result);
				$view_errors= "指定的比赛不存在。";
				require("error.php");
				exit(0);
			}else{
				$row=mysql_fetch_object($result);
				$view_private=$row->private;
				if ($row->private && !isset($_SESSION['c'.$cid])) $contest_ok=false;
				if ($row->defunct=='Y') $contest_ok=false;
				if (isset($_SESSION['administrator'])) $contest_ok=true;
									
				$now=time();
				$start_time=strtotime($row->start_time);
				$end_time=strtotime($row->end_time);
				$view_description=$row->description;
				$view_title= $row->title;
				$view_start_time=$row->start_time;
				$view_end_time=$row->end_time;				
				
				if (!isset($_SESSION['administrator']) && $now<$start_time){
					$view_errors=  "本比赛为私有制比赛，您不在参赛选手之列";
					require("error.php");
					exit(0);
				}
			}
			if (!$contest_ok){
				$view_errors=  "本比赛为私有制比赛，您不在参赛选手之列";
				require("error.php");
				exit(0);
			}
			$sql="select * from (SELECT `problem`.`title` as `title`,`problem`.`problem_id` as `pid`,source as source

		FROM `contest_problem`,`problem`

		WHERE `contest_problem`.`problem_id`=`problem`.`problem_id` AND `problem`.`defunct`='N'

		AND `contest_problem`.`contest_id`=$cid ORDER BY `contest_problem`.`num` 
                ) problem
                left join (select problem_id pid1,count(1) accepted from solution where result=4 and contest_id=$cid group by pid1) p1 on problem.pid=p1.pid1
                left join (select problem_id pid2,count(1) submit from solution where contest_id=$cid  group by pid2) p2 on problem.pid=p2.pid2
                
                ";

		
			$result=mysql_query($sql);
			$view_problemset=Array();
			
			$cnt=0;
			while ($row=mysql_fetch_object($result)){
				$view_problemset[$cnt][0]="";
				if (isset($_SESSION['user_id'])) 
					;//$view_problemset[$cnt][0]=check_ac($cid,$cnt);
				$view_problemset[$cnt][1]= "$row->pid Problem &nbsp;".(chr($cnt+ord('A')));
				$view_problemset[$cnt][2]= "<a href='contest-p-$cid-$cnt'>$row->title</a>";
				$view_problemset[$cnt][3]=$row->submit ;
				//$view_problemset[$cnt][3]=$row->source ;
				//$view_problemset[$cnt][4]=$row->accepted ;
				//$view_problemset[$cnt][5]=$row->submit ;
				$cnt++;
			}
		
			mysql_free_result($result);

}else{

  $sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 100";
			$result=mysql_query($sql);
			
			$view_contest=Array();
			$i=0;
			while ($row=mysql_fetch_object($result)){
				
				$view_contest[$i][0]= $row->contest_id;
				$view_contest[$i][1]= "<a href='contest-$row->contest_id'>$row->title</a>";
				$start_time=strtotime($row->start_time);
				$end_time=strtotime($row->end_time);
				$now=time();
                                
                                
        $length=$end_time-$start_time;
        $left=$end_time-$now;
	// past

  if ($now>$end_time) {
  	$view_contest[$i][2]= "<span style=color:green>已结束于 $row->end_time</span>";
	
	// pending

  }else if ($now<$start_time){
  	$view_contest[$i][2]= "<span style=color:blue>尚未开始 $row->start_time</span>&nbsp;";
    $view_contest[$i][2].= "<span style=color:green>总时间".formatTimeLength($length)."</span>";
	// running

  }else{
  	$view_contest[$i][2]= "<span style=color:red>进行中   </font>&nbsp;";
    $view_contest[$i][2].= "<span style=color:green>剩余时间 ".formatTimeLength($left)." </span>";
  }
                                
                                
                                
                                
				
				$private=intval($row->private);
				if ($private==0)
                                        $view_contest[$i][4]= "<span style=color:green>公开</span>";
                                else
                                        $view_contest[$i][5]= "<span style=color:red>私有</span>";


			
				$i++;
			}
			
			mysql_free_result($result);

}
if(!isset($_GET['cid']))
{
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>比赛 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();});</script>
<script>
$(function(){
var diff=new Date("<?=date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
function clock()
    {
      var x,h,m,s,n,xingqi,y,mon,d;
      var x = new Date(new Date().getTime()+diff);
      y = x.getYear()+1900;
      if (y>3000) y-=1900;
      mon = x.getMonth()+1;
      d = x.getDate();
      xingqi = x.getDay();
      h=x.getHours();
      m=x.getMinutes();
      s=x.getSeconds();
  
      n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      //alert(n);
      document.getElementById('nowdate').innerHTML=n;
      setTimeout(clock,1000);
    } 
    clock();});
</script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="disp-board" class="disp-board" style="height:100px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;"><h1 style="display:block;width:50px">比赛</h1>
<div style="margin-left:30px;color:grey;font-size:15px">在这里与高手们一决高下！&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当前时间：<span id='nowdate'><?=date("Y/m/d H:i:s")?></span></div>
</div>
<div class="board" style="margin-top:20px;padding-top:0px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<table id="result-tab" class="table table-striped content-box-header" style="font-size:13px;border-collapse:collapse;border-spacing:0;" align=center width=100%><tr  style='background-color:rgba(20%,80%,100%,0.3);font-size:15px;line-height:30px;padding-left:10px;padding-right:10px' ><td width="3%"></td><td width="13%" style="padding-left:10px;padding-right:10px">ID</td><td width="39%">标题</td><td width="31%">状态</td><td width="14%">是否公开</td></tr>
<?php 
			$cnt=0;
			foreach($view_contest as $row){?><tr style="line-height:25px"><td></td><?php
				foreach($row as $table_cell){
					echo "<td>";
					echo "\t".$table_cell;
					echo "</td>";
				}
				$cnt=1-$cnt;
				?></tr><?php
			}
			?>
</table>
</div>
</body>
</html>
<?php }
else
{
?><!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title><?=$view_title?> - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();});</script>
<script>
$(function(){
var diff=new Date("<?=date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
function clock()
    {
      var x,h,m,s,n,xingqi,y,mon,d;
      var x = new Date(new Date().getTime()+diff);
      y = x.getYear()+1900;
      if (y>3000) y-=1900;
      mon = x.getMonth()+1;
      d = x.getDate();
      xingqi = x.getDay();
      h=x.getHours();
      m=x.getMinutes();
      s=x.getSeconds();
  
      n=y+"-"+(mon>=10?mon:"0"+mon)+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      //alert(n);
      document.getElementById('nowdate').innerHTML=n;
      setTimeout(clock,1000);
    } 
    clock();});
</script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="disp-board" class="disp-board" style="height:100px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;"><h1 style="display:block;min-width:50px;cursor:pointer" onclick='location.href="contest.php"' title="返回比赛列表">比赛 - <?=$view_title?></h1>
<div style="margin-left:30px;color:grey;font-size:15px">开始时间：<?=$view_start_time?> 结束时间：<?=$view_end_time?> <br>当前时间：<span id=nowdate><?=date("Y-m-d H:i:s")?></span> 状态：<?php
				if ($now>$end_time) 
					echo "<span style=color:red>已结束</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='contest-result-".$cid."'>查看比赛结果</a>";
				else if ($now<$start_time) 
					echo "<span style=color:grey>尚未开始</span>";
				else 
					echo "<span style=color:green>进行中</span>";
			?></div>
</div>
<div class="board" style="margin-top:20px;padding-top:0px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<table id="result-tab" class="table table-striped content-box-header" style="font-size:15px;border-collapse:collapse;border-spacing:0;" align=center width=100%><tr  style='background-color:rgba(20%,80%,100%,0.3);font-size:15px;line-height:30px;padding-left:10px;padding-right:10px' ><td width="3%"></td><td width="20%" style="padding-left:10px;padding-right:10px">ID</td><td width="67%">标题</td><td width="10%">提交数</td></tr>
			<?php 
			$cnt=0;
			foreach($view_problemset as $row){
				?><tr><?php
				if ($cnt) 
					echo "<tr class='oddrow'>";
				else
					echo "<tr class='evenrow'>";
				foreach($row as $table_cell){
					echo "<td>";
					echo "\t".$table_cell;
					echo "</td>";
				}
				?></tr><?php
				
				$cnt=1-$cnt;
			}
			?>
</table>
</div>
</body>
</html><?php
	}
?>