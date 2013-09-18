<?php
require_once('include/db_info.inc.php');
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	$OJ_CACHE_SHARE=false;
	$cache_time=60;
	require_once('include/db_info.inc.php');
	require_once('include/const.inc.php');
	

if (isset($_GET['page'])){
    $page=intval($_GET['page']);
}

$first=1000;
  //if($OJ_SAE) $first=1;
$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt=50;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$cnt=intval($row->upid)-$first;
$cnt=$cnt/$page_cnt;
$pstart=$first+$page_cnt*intval($page)-$page_cnt;
$pend=$pstart+$page_cnt;
$view_total_page=floor($cnt+1);

$sub_arr=Array();
// submit
if (isset($_SESSION['user_id'])){
	//先更新最后访问
	if(isset($page))
	{
		$sql="update `users` set `volume`=$page where `user_id`='".$_SESSION['user_id']."' ";
		@mysql_query($sql) or die('{"no":1,"err":"'.mysql_error().'"}');
	}
$sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
                                                                       //  " AND `problem_id`>='$pstart'".
                                                                       // " AND `problem_id`<'$pend'".
	" group by `problem_id`";
$result=@mysql_query($sql) or die('{"no":1,"err":"'.mysql_error().'"}');
while ($row=mysql_fetch_array($result))
	$sub_arr[$row[0]]=true;
}

$acc_arr=Array();
$acc_str=Array();
// ac

if (isset($_SESSION['user_id'])){
$sql="SELECT `problem_id`,`result` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'";
$result=@mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result))
{
	$problem_id=$row["problem_id"];
	
	if(isset($acc_arr[$problem_id]))
	{
		if($acc_arr[$problem_id]==4)
		{
			continue;
		}
	}
	$acc_arr[$problem_id]=$row["result"];
	//echo $acc_arr[$problem_id];
	$acc_str[$problem_id]=$judge_result[$acc_arr[$problem_id]];
	
	//echo $row["result"];
}
}
if(isset($_GET['search'])&&trim($_GET['search'])!=""){
	$search=mysql_real_escape_string($_GET['search']);
    $filter_sql=" ( title like '%$search%' or source like '%$search%')";
    
}else{
     $filter_sql="  `problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";
}
if(isset($_SESSION['administrator']))
{
	$sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted` FROM `problem` where `defunct`='N' and $filter_sql"; 
}
else
{
	$now=strftime("%Y-%m-%d %H:%M",time());
	$sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted` FROM `problem` ".
	"WHERE `defunct`='N' and $filter_sql AND `problem_id` NOT IN(
		SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
			SELECT `contest_id` FROM `contest` WHERE 
			(`end_time`>'$now')and `defunct`='N'
			
		)
	)";
}
$sql.=" ORDER BY `problem_id` desc";
$result=mysql_query($sql) or die(mysql_error());
$cnt=0;
$view_problemset=Array();
$i=0;
while ($row=mysql_fetch_object($result)){
	$view_problemset[$i]=Array();
	if (isset($sub_arr[$row->problem_id])){
		if (isset($acc_arr[$row->problem_id])) 
			$view_problemset[$i][0]='<div class="center" style=""><div x-data="'.$row->problem_id.'" class="'.$judge_color_btn[$acc_arr[$row->problem_id]].'" style="min-width:70px">'.$acc_str[$row->problem_id].'</div></div>';
		else 
			$view_problemset[$i][0]= "<div class=none> </div>";
	}else{
		$view_problemset[$i][0]= "<div class=none> </div>";
	}
	$view_problemset[$i][1]="<div class='center'>".$row->problem_id."</div>";;
	$view_problemset[$i][2]="<div class='left'><a href='problem-".$row->problem_id."'>".$row->title."</a></div>";;
	$view_problemset[$i][3]="<div class='center'><nobr>".mb_substr($row->source,0,8,'utf8')."</nobr></div >";
	$view_problemset[$i][4]="<div class='center'><a href='problem-status-problem_id=".$row->problem_id."&jresult=4'>".$row->accepted."</a></div>";
	$view_problemset[$i][5]="<div class='center'><a href='problem-status-problem_id=".$row->problem_id."'>".$row->submit."</a></div>";
	
	
	$i++;
}
mysql_free_result($result);
$outstr='<table border="0" style="padding:0px;margin-left:auto;margin-right:auto;width:100%;border-collapse:collapse;border-spacing:0;">
<tr style="background-color:rgba(20%,80%,100%,0.3);text-align:center;"><td width="14%" style="text-align:center;">Flag</td><td width="11%" style="text-align:center;">PID</td><td width="50%" style="text-align:left;">标题</td><td width="13%" style="text-align:center;">通过数</td><td width="13%" style="text-align:center;">提交数</td></tr>';
while($i--)
{
	$outstr.='<tr height=30 style="font-size:15px"><td>'.$view_problemset[$i][0].'</td><td>'.$view_problemset[$i][1].'</td><td>'.$view_problemset[$i][2].'</td><td>'.$view_problemset[$i][4].'</td><td>'.$view_problemset[$i][5].'</td><td></tr>';
}
$outstr.='</table>';
echo json_encode((object)array('no'=>0,'err'=>$outstr));
?>