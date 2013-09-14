<?php
    require_once('./include/db_info.inc.php');
	require_once("./include/my_func.inc.php");
	require_once("./include/const.inc.php");

$str2="";
$lock=false;
$lock_time=date("Y-m-d H:i:s",time());
$sql="SELECT * FROM `solution` WHERE problem_id>0 ";
if (isset($_GET['cid']))
{
    $cid=intval($_GET['cid']);
    $sql=$sql." AND `contest_id`='$cid' and num>=0 ";
    $str2=$str2."&cid=$cid";
    $sql_lock="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
    $result=mysql_query($sql_lock) or die(mysql_error());
    $rows_cnt=mysql_num_rows($result);
    $start_time=0;
    $end_time=0;
    if ($rows_cnt>0)
	{
        $row=mysql_fetch_array($result);
		$start_time=strtotime($row[0]);
        $title=$row[1];
        $end_time=strtotime($row[2]);       
	}
    $lock_time=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;
    $lock_time=date("Y-m-d H:i:s",$lock_time);
    $time_sql="";
    if(time()>$lock_time&&time()<$end_time)
		$lock=true;
    else
		$lock=false;       
}
$start_first=true;
$order_str=" ORDER BY `solution_id` DESC ";

// check the top arg
if (isset($_GET['top']))
{
    $top=strval(intval($_GET['top']));
    if ($top!=-1) $sql=$sql."AND `solution_id`<=".$top." ";
}

$problem_id="";
if (isset($_GET['problem_id'])&&$_GET['problem_id']!="")
{
	if(isset($_GET['cid']))
	{
		$problem_id=$_GET['problem_id'];
		$num=strpos($PID,$problem_id);
		$sql=$sql."AND `num`='".$num."' ";
		$str2=$str2."&problem_id=".$problem_id;
	}else
	{
		$problem_id=strval(intval($_GET['problem_id']));
		if ($problem_id!='0')
		{
			$sql=$sql."AND `problem_id`='".$problem_id."' ";
			$str2=$str2."&problem_id=".$problem_id;
		}
		else $problem_id="";
	}
}
// check the user_id arg
$user_id="";
if (isset($_GET['user_id']))
{
    $user_id=trim($_GET['user_id']);
    if (is_valid_user_name($user_id) && $user_id!="")
	{
        $sql=$sql."AND `user_id`='".$user_id."' ";
        if ($str2!="") $str2=$str2."&";
        $str2=$str2."user_id=".$user_id;
    }
	else $user_id="";
}
if (isset($_GET['language'])) $language=intval($_GET['language']);
	else $language=-1;

if ($language>count($language_ext) || $language<0) 
	$language=-1;
if ($language!=-1)
{
    $sql=$sql."AND `language`='".strval($language)."' ";
    $str2=$str2."&language=".$language;
}
if (isset($_GET['jresult'])) $result=intval($_GET['jresult']);
	else $result=-1;

if ($result>12 || $result<0) $result=-1;
if ($result!=-1&&!$lock)
{
    $sql=$sql."AND `result`='".strval($result)."' ";
    $str2=$str2."&jresult=".$result;
}

$sql=$sql.$order_str." LIMIT 20";

	$result = mysql_query($sql);
	if($result) $rows_cnt=mysql_num_rows($result);
	else $rows_cnt=0;

$top=$bottom=-1;
$cnt=0;
if ($start_first){
        $row_start=0;
        $row_add=1;
}else{
        $row_start=$rows_cnt-1;
        $row_add=-1;
}
	
$view_status=Array();

$last=0;
for ($i=0;$i<$rows_cnt;$i++){
if($OJ_MEMCACHE)
        $row=$result[$i];
else
        $row=mysql_fetch_array($result);
        if($i==0&&$row['result']<4) $last=$row['solution_id'];
	
		if ($top==-1) $top=$row['solution_id'];
        $bottom=$row['solution_id'];
		$flag=!is_running(intval($row['contest_id']));

        $cnt=1-$cnt;
	
        $view_status[$i][0]=$row['solution_id'];
       
        if ($row['contest_id']>0) {
                $view_status[$i][1]= "<a href='contestrank.php?cid=".$row['contest_id']."&user_id=".$row['user_id']."#".$row['user_id']."'>".$row['user_id']."</a>";
        }else{
                $view_status[$i][1]= "<a href='userinfo.php?user=".$row['user_id']."'>".$row['user_id']."</a>";
        }

       if ($row['contest_id']>0) {
                $view_status[$i][2]= "<div class=center><a href='problem.php?cid=".$row['contest_id']."&pid=".$row['num']."'>";
                if(isset($cid)){
                        $view_status[$i][2].= $PID[$row['num']];
                }else{
                        $view_status[$i][2].= $row['problem_id'];
                }
				$view_status[$i][2].="</div></a>";
        }else{
                $view_status[$i][2]= "<div class=center><a href='problem.php?id=".$row['problem_id']."'>".$row['problem_id']."</a></div>";
        }

		$view_status[$i][3]="<a href='showsource.php?id=".$row['solution_id']."' class='fg-color-white ".$judge_color[$row['result']]."' style='min-width:45px;display:block'>".$judge_result[$row['result']]."</a>";	
        
		if (isset($row['pass_rate']))
			$view_status[$i][4]=$row['pass_rate']*100;
		else $view_status[$i][4]=0;

			$view_status[$i][5]="----";
			$view_status[$i][6]="----";		
			
		if ($flag){
                if ($row['result']>=4){
                        $view_status[$i][5]= "<div id=center class='fg-color-red'>".$row['memory']."</div>";
                        $view_status[$i][6]= "<div id=center class='fg-color-red'>".$row['time']."</div>";
                }                        
        }else
		{
			$view_status[$i][2]="<a href='problem.php?cid=".$row['contest_id']."&pid=".$row['num']."'>C".$row['contest_id']." ".$PID[$row['num']]."</a>";
			if (!isset($_SESSION['administrator'])){			
				$view_status[$i][3]="----";
				$view_status[$i][4]="----";
			}
		}
		$view_status[$i][7]=$language_name[$row['language']];
        $view_status[$i][8]= $row['in_date'];

}

?>
<!--/////////////////web///////////////-->
<?php if (isset($_GET['cid'])){?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>记录</title>
	
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
		<li class="divider"></li>
		<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-search"></i>搜索</a>
			<ul class="sub-menu light sidebar-dropdown-menu open">
				<form id="simform" action="status.php" method="get">
					<div class="input-control text"><input type="text" name="problem_id" placeholder="题目编号" value='<?php echo $problem_id?>'/></div>
					<div class="input-control text"><input type="text" name="user_id" placeholder="用户" value='<?php echo $user_id?>'/></div>
					<?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
					<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;语言:			
						<select class="input-control" name="language">
						<?php 
							if (isset($_GET['language'])) $language=$_GET['language'];
							else $language=-1;
							if ($language<0||$language>=count($language_name)) $language=-1;
							if ($language==-1) echo "<option value='-1' selected>All</option>";
							else echo "<option value='-1'>All</option>";
							$i=0;
							foreach ($language_name as $lang){
  					      if ($i==$language) 
							echo "<option value=$i selected>$language_name[$i]</option>";
   					     else 
							echo "<option value=$i>$language_name[$i]</option>";
     					   $i++;
							}
						?>		
						</select>	
					</div>
					<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结果:
						<select class="input-control" name="jresult">
						<?php 
							if (isset($_GET['jresult'])) $jresult_get=intval($_GET['jresult']);
							else $jresult_get=-1;
							if ($jresult_get>=12||$jresult_get<0) $jresult_get=-1;
							if ($jresult_get==-1) echo "<option value='-1' selected>All</option>";
							else echo "<option value='-1'>All</option>";
							for ($j=0;$j<12;$j++)
							{
								$i=($j+4)%12;
								if ($i==$jresult_get) echo "<option value='".strval($jresult_get)."' selected>".$jresult[$i]."</option>";
								else echo "<option value='".strval($i)."'>".$jresult[$i]."</option>"; 
							}		
						?>		
						</select>	
					</div>
					<center><input type="submit" value='搜索'/></center>
				</form>
            </ul>
        </li>		
	</ul>
	</div>

	<div class="page-region">	
	<div class="grid">
	<div class="row">
	<table id="result-tab" class="striped" align="center">
		<thead>	
			<tr>	
				<th><center>运行编号</center></th>
				<th><center>用户</center></th>
				<th><center>问题</center></th>
				<th><center>结果</center></th>
				<th><center>得分</center></th>
				<th><center>内存</center></th>
				<th><center>耗时</center></th>
				<th><center>语言</center></th>
				<th><center>提交时间</center></th>
			</tr>
		</thead>

		<tbody>
		<?php 
			$cnt=0;
			foreach($view_status as $row)
			{
				echo "<tr>";
				foreach($row as $table_cell)
				{
					echo "<td><center>";
					echo "\t".$table_cell;
					echo "</center></td>";
				}				
				echo "</tr>";
			}
		?>
		</tbody>
	</table>
	<div class="grid">
	<div class="row">
		<div class="span4"></div>
	<?php 
		echo "<div class='span1'><a href=status.php?".$str2.">首页</a></div>";
		if (isset($_GET['prevtop']))
			echo "<div class='span1'><a href=status.php?".$str2."&top=".$_GET['prevtop'].">上一页</a></div>";
		else
			echo "<div class='span1'><a href=status.php?".$str2."&top=".($top+20).">上一页</a></div>";
		echo "<div class='span1'><a href=status.php?".$str2."&top=".$bottom."&prevtop=$top>下一页</a></div>";
	?>
	</div>
	</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
  var i=0;
  var judge_result=[<?php
  foreach($judge_result as $result){
    echo "'$result',";
  }
?>''];
//alert(judge_result[0]);
function findRow(solution_id){
    var tb=window.document.getElementById('result-tab');
     var rows=tb.rows;

      for(var i=1;i<rows.length;i++){
                var cell=rows[i].cells[0];
//              alert(cell.innerHTML+solution_id);
        if(cell.innerHTML==solution_id) return rows[i];
      }
}

function fresh_result(solution_id)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     var tb=window.document.getElementById('result-tab');
     var row=findRow(solution_id);
     //alert(row);
     var r=xmlhttp.responseText;
     var ra=r.split(",");
//     alert(r);
//     alert(judge_result[r]);
      var loader="<img width=18 src=image/loader.gif>";
      row.cells[3].innerHTML="<span class='btn btn-warning'>"+judge_result[ra[0]]+"</span>"+loader;

     row.cells[4].innerHTML=ra[1];
     row.cells[5].innerHTML=ra[2];
     if(ra[0]<4)
        window.setTimeout("fresh_result("+solution_id+")",2000);
     else
        window.location.reload();

    }
  }
xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
xmlhttp.send();
}
<?php if ($last>0&&$_SESSION['user_id']==$_GET['user_id']) echo "fresh_result($last);";?>
</script>
<?php require_once("oj-footer.php");?>

<?php }else {?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>记录</title>
	
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
		<li class="divider"></li>
		<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-search"></i>搜索</a>
			<ul class="sub-menu light sidebar-dropdown-menu open">
				<form id="simform" action="status.php" method="get">
					<div class="input-control text"><input type="text" name="problem_id" placeholder="题目编号" value='<?php echo $problem_id?>'/></div>
					<div class="input-control text"><input type="text" name="user_id" placeholder="用户" value='<?php echo $user_id?>'/></div>
					<?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
					<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;语言:			
						<select class="input-control" name="language">
						<?php 
							if (isset($_GET['language'])) $language=$_GET['language'];
							else $language=-1;
							if ($language<0||$language>=count($language_name)) $language=-1;
							if ($language==-1) echo "<option value='-1' selected>All</option>";
							else echo "<option value='-1'>All</option>";
							$i=0;
							foreach ($language_name as $lang){
  					      if ($i==$language) 
							echo "<option value=$i selected>$language_name[$i]</option>";
   					     else 
							echo "<option value=$i>$language_name[$i]</option>";
     					   $i++;
							}
						?>		
						</select>	
					</div>
					<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;结果:
						<select class="input-control" name="jresult">
						<?php 
							if (isset($_GET['jresult'])) $jresult_get=intval($_GET['jresult']);
							else $jresult_get=-1;
							if ($jresult_get>=12||$jresult_get<0) $jresult_get=-1;
							if ($jresult_get==-1) echo "<option value='-1' selected>All</option>";
							else echo "<option value='-1'>All</option>";
							for ($j=0;$j<12;$j++)
							{
								$i=($j+4)%12;
								if ($i==$jresult_get) echo "<option value='".strval($jresult_get)."' selected>".$jresult[$i]."</option>";
								else echo "<option value='".strval($i)."'>".$jresult[$i]."</option>"; 
							}		
						?>		
						</select>	
					</div>
					<center><input type="submit" value='搜索'/></center>
				</form>
            </ul>
        </li>		
	</ul>
	</div>
		
	<div class="page-region">	
	<div class="grid">
	<div class="row">
	<table id="result-tab" class="striped" align="center">
		<thead>
	
			<tr>	
				<th><center>运行编号</center></th>
				<th><center>用户</center></th>
				<th><center>问题</center></th>
				<th><center>结果</center></th>
				<th><center>得分</center></th>
				<th><center>内存</center></th>
				<th><center>耗时</center></th>
				<th><center>语言</center></th>
				<th><center>提交时间</center></th>
			</tr>
		
		</thead>

		<tbody>
		<?php 
			$cnt=0;
			foreach($view_status as $row)
			{
				echo "<tr>";
				foreach($row as $table_cell)
				{
					echo "<td><center>";
					echo "\t".$table_cell;
					echo "</center></td>";
				}				
				echo "</tr>";
			}
		?>
		</tbody>
	</table>
	<div class="grid">
	<div class="row">
		<div class="span4"></div>
	<?php 
		echo "<div class='span1'><a href=status.php?".$str2.">首页</a></div>";
		if (isset($_GET['prevtop']))
			echo "<div class='span1'><a href=status.php?".$str2."&top=".$_GET['prevtop'].">上一页</a></div>";
		else
			echo "<div class='span1'><a href=status.php?".$str2."&top=".($top+20).">上一页</a></div>";
		echo "<div class='span1'><a href=status.php?".$str2."&top=".$bottom."&prevtop=$top>下一页</a></div>";
	?>
	</div>
	</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
  var i=0;
  var judge_result=[<?php
  foreach($judge_result as $result){
    echo "'$result',";
  }
?>''];
//alert(judge_result[0]);
function findRow(solution_id){
    var tb=window.document.getElementById('result-tab');
     var rows=tb.rows;

      for(var i=1;i<rows.length;i++){
                var cell=rows[i].cells[0];
//              alert(cell.innerHTML+solution_id);
        if(cell.innerHTML==solution_id) return rows[i];
      }
}

function fresh_result(solution_id)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
     var tb=window.document.getElementById('result-tab');
     var row=findRow(solution_id);
     //alert(row);
     var r=xmlhttp.responseText;
     var ra=r.split(",");
//     alert(r);
//     alert(judge_result[r]);
      var loader="<img width=18 src=image/loader.gif>";
     row.cells[3].innerHTML="<span class='btn btn-warning'>"+judge_result[ra[0]]+"</span>"+loader;
     row.cells[4].innerHTML=ra[1];
     row.cells[5].innerHTML=ra[2];
     if(ra[0]<4)
        window.setTimeout("fresh_result("+solution_id+")",2000);
     else
        window.location.reload();

    }
  }
xmlhttp.open("GET","status-ajax.php?solution_id="+solution_id,true);
xmlhttp.send();
}
<?php if ($last>0&&$_SESSION['user_id']==$_GET['user_id']) echo "fresh_result($last);";?>
</script>

	<?php require_once("oj-footer.php");?>
<?php } ?>

