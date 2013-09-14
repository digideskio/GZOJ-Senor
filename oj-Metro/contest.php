 <?php
	$OJ_CACHE_SHARE=!isset($_GET['cid']);
    require_once('./include/db_info.inc.php');
	require_once('./include/my_func.inc.php');
	$view_title='比赛';
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

			$sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' ";
			$result=mysql_query($sql);
			$rows_cnt=mysql_num_rows($result);
			$contest_ok=true;
			
			
			if ($rows_cnt==0){
				mysql_free_result($result);
				$view_title= "No Such Contest!";
				
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
					$view_errors=  "<h2>私有比赛，您无权查看题目。</h2>";
					require("error.php");
					exit(0);
				}
			}
			if (!$contest_ok){
             $view_errors=  "<h2>私有比赛，您无权查看题目。<a href=contestrank.php?cid=$cid>点击这里查看比赛排名。</a></h2>";
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
					$view_problemset[$cnt][0]=check_ac($cid,$cnt);
				$view_problemset[$cnt][1]= "$row->pid Problem &nbsp;".(chr($cnt+ord('A')));
				$view_problemset[$cnt][2]= "<a href='problem.php?cid=$cid&pid=$cnt'>$row->title</a>";
				$temp=$row->accepted/$row->submit;
				if ($temp=='') $temp=0;
				$view_problemset[$cnt][3]=$row->accepted;
				$view_problemset[$cnt][4]=$temp;
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
				$view_contest[$i][1]= "<a href='contest.php?cid=$row->contest_id'>$row->title</a>";
				$start_time=strtotime($row->start_time);
				$end_time=strtotime($row->end_time);
				$now=time();
                                
                                
        $length=$end_time-$start_time;
        $left=$end_time-$now;
	// past

  if ($now>$end_time) {
  	$view_contest[$i][2]= "<span class=green>已结束@$row->end_time</span>";
	
	// pending

  }else if ($now<$start_time){
  	$view_contest[$i][2]= "<span class=blue>开始时间@$row->start_time</span>&nbsp;";
    $view_contest[$i][2].= "<span class=green>总时间".formatTimeLength($length)."</span>";
	// running

  }else{
  	$view_contest[$i][2]= "<span class=red>进行中</font>&nbsp;";
    $view_contest[$i][2].= "<span class=green>剩余时间".formatTimeLength($left)." </span>";
  }

				$private=intval($row->private);
				if ($private==0)
                                        $view_contest[$i][4]= "<span class='fg-color-green'>公开</span>";
                                else
                                        $view_contest[$i][5]= "<span class='fg-color-red'>不公开</span>";
				$i++;
			}		
			mysql_free_result($result);

}
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

</head>
<body class="metrouicss">

	<?php require_once("oj-header.php");?>


<?php if(isset($_GET['cid'])){?>
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
<div class="row">
		<center><h3>比赛<?php echo $view_cid?> - <?php echo $view_title ?></h3></center>
		<div class="page-region-content"><p><?php echo $view_description?></p></div>
		<div class="row">
			<div class="span1"></div>
			<div class="span3">
				<h4>开始时间:<span class="fg-color-blue"><?php echo $view_start_time?></span></h4>
			</div>
			<div class="span3">
				<h4>结束时间:<span class="fg-color-red"><?php echo $view_end_time?></span></h4>
			</div>
		</div>
		<div class="row">
			<div class="span1"></div>
			<div class="span3">
				<h4>当前时间:<span class="fg-color-blue" id="nowdate"><?php echo date("Y-m-d H:i:s")?></span></h4>
			</div>
			<div class="span3">
				<h4>状态:
			<?php
				if ($now>$end_time) 
					echo "<span class='fg-color-red'>比赛已结束</span>";
				else if ($now<$start_time) 
					echo "<span class='fg-color-red'>比赛未开始</span>";
				else 
					echo "<span class='fg-color-green'>比赛进行中</span>";
			?>	</h4>
			</div>
			<div class="span1">
				<h4>
			<?php
				if ($view_private=='0') 
					echo "<span class='fg-color-green'>公开</font>";
				else 
					echo "<span class='fg-color-red'>不公开</font>"; 
			?>		
			</div>
		</div>
	
	<table class="striped">
		<thead>			
			<tr>
				<td class="span1">FLAG
				<td class="span2">题目编号
				<td class="span5">标题
				<td class="span1">AC数
				<td class="span1">AC率
			</tr>
		</thead>
		<tbody>
		<?php 
			$cnt=0;
			foreach($view_problemset as $row){
				echo "<tr>";
				foreach($row as $table_cell){
					echo "<td>";
					echo "\t".$table_cell;
					echo "</td>";
				}				
				echo "</tr>";
			}
		?>
		</tbody>
	</table>
</div></div></div></div></div>
<script>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
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
      setTimeout("clock()",1000);
    } 
    clock();
</script>

<?php }else{ ?>
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

<div class="row">	
	
	<center>
		<h2>比赛列表</h2>
		<h3><strong>当前时间：<span id="nowdate"></span></strong></h3>
	</center>

	<table class="stripped">
		<thead>
			<tr>
				<th class="span1">编号
				<th class="span3">名称
				<th class="span3">状态
				<th class="span2">是否公开
			</tr>
		</thead>

		<tbody>
			<?php 
			$cnt=0;
			foreach($view_contest as $row){
				echo "<tr>";
				foreach($row as $table_cell){
					echo "<td>";
					echo "\t".$table_cell;
					echo "</td>";
				}				
				echo "</tr>";
			}
			?>
		</tbody>		
	</table>
	
<script>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
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
      setTimeout("clock()",1000);
    } 
    clock();
</script>
</div></div></div></div></div>	
<?php } ?>
<?php require_once("oj-footer.php");?>