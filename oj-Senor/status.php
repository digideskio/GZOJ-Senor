<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
require_once("include/my_func.inc.php");
require_once("include/const.inc.php");

//if($OJ_TEMPLATE!="classic") 
	$judge_color=Array("btn gray","btn btn-info","btn btn-warning","btn btn-warning","btn btn-success","btn btn-danger","btn btn-danger","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-warning","btn btn-info");

$str2="";
$lock=false;
$lock_time=date("Y-m-d H:i:s",time());
$sql="SELECT * FROM `solution` WHERE problem_id>0 ";
if (isset($_GET['cid'])){
        $cid=intval($_GET['cid']);
        $sql=$sql." AND `contest_id`='$cid' and num>=0 ";
        $str2=$str2."&cid=$cid";
          $sql_lock="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
        $result=mysql_query($sql_lock) or die(mysql_error());
        $rows_cnt=mysql_num_rows($result);
        $start_time=0;
        $end_time=0;
        if ($rows_cnt>0){
                $row=mysql_fetch_array($result);
                $start_time=strtotime($row[0]);
                $title=$row[1];
                $end_time=strtotime($row[2]);       
        }
        $lock_time=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;
        $lock_time=date("Y-m-d H:i:s",$lock_time);
        $time_sql="";
        //echo $lock.'-'.date("Y-m-d H:i:s",$lock);
        if(time()>$lock_time&&time()<$end_time){
          //$lock_time=date("Y-m-d H:i:s",$lock_time);
          //echo $time_sql;
           $lock=true;
        }else{
           $lock=false;
        }
}else{
  if(isset($_SESSION['administrator'])||isset($_SESSION['source_browser'])||(isset($_SESSION['user_id'])&&$_GET['user_id']==$_SESSION['user_id'])){
      //if ($_SESSION['user_id']!="guest")
      		$sql="SELECT * FROM `solution` WHERE 1 ";
  }else{
      $sql="SELECT * FROM `solution` WHERE problem_id>0 and 1 ";
  }
}
$start_first=true;
$order_str=" ORDER BY `solution_id` DESC ";



// check the top arg
if (isset($_GET['top'])){
        $top=strval(intval($_GET['top']));
        if ($top!=-1) $sql=$sql."AND `solution_id`<='".$top."' ";
}

// check the problem arg
$problem_id="";
if (isset($_GET['problem_id'])&&$_GET['problem_id']!=""){
	
	if(isset($_GET['cid'])){
		$problem_id=$_GET['problem_id'];
		$num=strpos($PID,$problem_id);
		$sql=$sql."AND `num`='".$num."' ";
        $str2=$str2."&problem_id=".$problem_id;
        
	}else{
        $problem_id=strval(intval($_GET['problem_id']));
        if ($problem_id!='0'){
                $sql=$sql."AND `problem_id`='".$problem_id."' ";
                $str2=$str2."&problem_id=".$problem_id;
        }
        else $problem_id="";
	}
}
// check the user_id arg
$user_id="";
if (isset($_GET['user_id'])){
        $user_id=trim($_GET['user_id']);
        if (is_valid_user_name($user_id) && $user_id!=""){
                $sql=$sql."AND `user_id`='".$user_id."' ";
                if ($str2!="") $str2=$str2."&";
                $str2=$str2."user_id=".$user_id;
        }else $user_id="";
}
if (isset($_GET['language'])) $language=intval($_GET['language']);
else $language=-1;

if ($language>count($language_ext) || $language<0) $language=-1;
if ($language!=-1){
        $sql=$sql."AND `language`=".strval($language)." ";
		//echo $sql;
        $str2=$str2."&language=".$language;
}
if (isset($_GET['jresult'])) $result=intval($_GET['jresult']);
else $result=-1;

if ($result>12 || $result<0) $result=-1;
if ($result!=-1&&!$lock){
        $sql=$sql."AND `result`='".strval($result)."' ";
        $str2=$str2."&jresult=".$result;
}

$sql=$sql.$order_str." LIMIT 20";
//echo $sql;	
	$result = mysql_query($sql);// or die("Error! ".mysql_error());
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
        $row=mysql_fetch_array($result);
        //$view_status[$i]=$row;
        if($i==0&&$row['result']<4) $last=$row['solution_id'];

	
		if ($top==-1) $top=$row['solution_id'];
        $bottom=$row['solution_id'];
		$flag=(!is_running(intval($row['contest_id']))) ||
                        isset($_SESSION['source_browser']) ||
                        isset($_SESSION['administrator']) || 
                        (isset($_SESSION['user_id'])&&!strcmp($row['user_id'],$_SESSION['user_id']));


	

        $view_status[$i][0]='R'.$row['solution_id'];
       
        
                $view_status[$i][1]= "<a href='user-info-".$row['user_id']."' title='用户名：".$row['user_id']."'>".$row['user_id']."</a>";
        
		$view_status[$i][2]='<div style=text-align:right;margin-right:20px >';
       if ($row['contest_id']!==NULL) {
		   $view_status[$i][2].='<a href="contest-'.$row['contest_id'].'"><span style="font-size:8px;color:red">[比赛]</span></a>';
                //$view_status[$i][2]= "<div class=center><a href='problem.php?cid=".$row['contest_id']."&pid=".$row['num']."'>";
                //if(isset($cid)){
                 //       $view_status[$i][2].= $PID[$row['num']];
                //}else{
                 //       $view_status[$i][2].=$row['problem_id']; //"C".$row['contest_id']."-".$PID[$row['num']].'-'.$row['problem_id'];
                //}
				//$view_status[$i][2].="</div></a>";
        }
		
                $view_status[$i][2].="<a href='problem-".$row['problem_id']."'>".$row['problem_id']."</a></div>";
        

      /* 
       if ((intval($row['result'])==6||$row['result']==10||$row['result']==13) && ((isset($_SESSION['user_id'])&&$row['user_id']==$_SESSION['user_id']) || isset($_SESSION['source_browser']))){
		   */
                //$view_status[$i][3]= "<a href='reinfo.php?sid=".$row['solution_id']."' class='".$judge_color[$row['result']]."'>".$judge_result[$row['result']]."</a>";
				$view_status[$i][3]= "<a href='javascript:'".(($row['contest_id']===NULL||1)?(" onclick='view_solution_status(".$row['solution_id'].");'"):"")."><span class='".$judge_color[$row['result']]."' style='font-size:12px;min-width:70px'>".$judge_result[$row['result']]."</span></a>";
/*
        }else{
              if(!$lock||$lock_time>$row['in_date']||$row['user_id']==$_SESSION['user_id']){
               		$view_status[$i][3]= "<span class='".$judge_color[$row['result']]."'>".$judge_result[$row['result']]."</span>";
          }else{
              echo "<td>----";
          }
		  
                
        }
        if (isset($row['pass_rate'])&&$row['pass_rate']>=0&&$row['pass_rate']<=1) */
		$flag_can_show=1;
		if($row['contest_id']!==NULL)
		{
			$res_ctinfo=mysql_query("select count(*) as count from `contest` where `contest_id`=".$row['contest_id']." and `end_time`>now() and `defunct`='N'");
			$row_ctinfo=mysql_fetch_array($res_ctinfo);
			if($row_ctinfo['count']>0) $flag_can_show=0;
		}
		
		if($flag_can_show||isset($_SESSION['administrator']))
		{
				$view_status[$i][4]="<div style='text-align:right;color:rgb(".$ScoreColor[intval($row['pass_rate']*100)][0].",".$ScoreColor[intval($row['pass_rate']*100)][1].",".$ScoreColor[intval($row['pass_rate']*100)][2].")'>". ($row['pass_rate']*100)."</div>";
				
        //if ($flag){


               
                        $view_status[$i][5]= "<div align=right class=red>".$row['memory']."</div>";
                        $view_status[$i][6]= "<div align=right class=red>".$row['time']."&nbsp;&nbsp;&nbsp;</div>";
		}
		else
		{
				$view_status[$i][4]='<a href="contest-'.$row['contest_id'].'">比赛:</a>';
				$view_status[$i][5]='<a href="contest-'.$row['contest_id'].'">'.$row['contest_id'].'</a>';
				$view_status[$i][6]='<a href="contest-p-'.$row['contest_id'].'-'.$row['num'].'">题：'.$PID[$row['num']].'</a>';
				if($row['result']!=11&&$row['result']>=4) $view_status[$i][3]='<span class="btn" style="min-width:70px">???</span>';
		}
				//echo $row['result'];
                if (!(isset($_SESSION['user_id'])&&strtolower($row['user_id'])==strtolower($_SESSION['user_id']) || isset($_SESSION['source_browser'])||isset($_SESSION['administrator']))){
                        $view_status[$i][7]=$language_name[$row['language']];
                }else{
                        $view_status[$i][7]= "&nbsp;&nbsp;<a title='点击查看源码' href='javascript:' onclick='showmysrc(".$row['solution_id'].",\"".$lang_syntax[$row['language']]."\");'>".$language_name[$row['language']]."</a>";
/*
                        if (isset($cid)) {
                                $view_status[$i][7].= " <a target=_self href=\"submitpage.php?cid=".$cid."&pid=".$row['num']."&sid=".$row['solution_id']."\">Edit</a>";
                        }else{
                                $view_status[$i][7].= " <a target=_self href=\"submitpage.php?id=".$row['problem_id']."&sid=".$row['solution_id']."\">Edit</a>";
                        }
						*/
                }
                $view_status[$i][8]= ''.$row['code_length']." B";
				
        //}else
	if(0)	{
			$view_status[$i][5]="----";
			$view_status[$i][6]="----";
			$view_status[$i][7]="----";
			$view_status[$i][8]="----";
		}
        $view_status[$i][9]= $row['in_date'];
        
   
   

}
mysql_free_result($result);


?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>记录页 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/highlight.js/highlight.pack.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<script language="javascript" src="js_senor/problem.js"></script>
<script language="javascript" src="js_senor/status.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<link rel="stylesheet" type="text/css" href="js_senor/highlight.js/styles/github.css" />

<script>$(function(){common_init();status_init();});</script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="disp-board" class="disp-board" style="height:100px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;"><h1>记录页</h1>
<form id=simform action="status.php" method="get">
题目编号:<input class="input-mini textbox" style="height:24px" type=text size=4 name=problem_id value='<?php echo $problem_id?>'>
用户名:<input class="input-mini textbox" style="height:24px" type=text size=4 name=user_id value='<?php echo $user_id?>'>
<?php if (isset($cid)) echo "<input type='hidden' name='cid' value='$cid'>";?>
语言:<select class="input-small " size="1" name="language" style="height:30px">
<?php if (isset($_GET['language'])) $language=$_GET['language'];
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
结果:<select class="input-small " size="1" name="jresult" style="height:30px">
<?php if (isset($_GET['jresult'])) $jresult_get=intval($_GET['jresult']);
else $jresult_get=-1;
if ($jresult_get>=12||$jresult_get<0) $jresult_get=-1;
     /*if ($jresult_get!=-1){
        $sql=$sql."AND `result`='".strval($jresult_get)."' ";
        $str2=$str2."&jresult=".strval($jresult_get);
     }*/
if ($jresult_get==-1) echo "<option value='-1' selected>All</option>";
else echo "<option value='-1'>All</option>";
?><option value='4' <?=($jresult_get==4?"selected":"")?>>AC</option><option value='6' <?=($jresult_get==6?"selected":"")?>>WA</option><option value='7' <?=($jresult_get==7?"selected":"")?>>TLE</option><option value='8' <?=($jresult_get==8?"selected":"")?>>MLE</option><option value='10' <?=($jresult_get==10?"selected":"")?>>RE</option><option value='11' <?=($jresult_get==11?"selected":"")?>>CE</option><option value='0' <?=($jresult_get==0?"selected":"")?>>Pending</option><option value='1' <?=($jresult_get==1?"selected":"")?>>PendRejudge</option><option value='3' <?=($jresult_get==3?"selected":"")?>>Running</option><option value='2' <?=($jresult_get==2?"selected":"")?>>Compiling</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=button class='input button button-def'  value=' 搜 索 ' onclick='location.href="problem-status-"+$("#simform").serialize()'></form></div>
<div class="board" style="margin-top:20px;padding-top:0px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<table id="result-tab" class="table table-striped content-box-header" style="font-size:13px;border-collapse:collapse;border-spacing:0;" align=center width=100%><thead><tr  style='background-color:rgba(20%,80%,100%,0.3);font-size:15px;line-height:30px' ><th width="5%" style="padding-left:10px">ID</th><th width="1%"></th><th width="11%" >用户</th><th width="9%" style='text-align:center'>题目</th><th width="10%" >结果</th><th width="5%" style="text-align:right">分数</th><th width="6%" style="text-align:right" >内存KB</th><th width="9%" style="text-align:right;" >时间ms</th><th width="8%" >&nbsp;&nbsp;语言</th><th width="13%" style="text-align:right;padding-right:15px">代码长度</th><th width="18%" >提交时间</th></tr></thead><tbody><?php 
			$cnt=0;
			foreach($view_status as $row){
				echo "<tr onmouseover='$(this).css(\"background-color\",\"rgba(0,0,0,0.1);\")' onmouseout='$(this).css(\"background-color\",\"rgba(0,0,0,0);\")'>";
				?><td style="padding-left:10px"><?=$row[0]?></td><td></td><td><?=$row[1]?></td><td><?=$row[2]?></td><td><?=$row[3]?></td><td><?=$row[4]?></td><td><?=$row[5]?></td><td><?=$row[6]?></td><td style="text-align:center"><?=$row[7]?></td><td style="text-align:right;padding-right:15px"><?=$row[8]?></td><td style="color:#666;font-size:12px"><?=$row[9]?></td><?php
				
				echo "</tr>";
				
				$cnt=1-$cnt;
			}
			?><td width="4%"></td><td width="1%"></tbody></table><div align=center><?php echo "[<a href=status.php?".$str2.">第一页</a>]&nbsp;&nbsp;";
if (isset($_GET['prevtop']))
        echo "[<a href=status.php?".$str2."&top=".$_GET['prevtop'].">上一页</a>]&nbsp;&nbsp;";
else
        echo "[<a href=status.php?".$str2."&top=".($top+20).">上一页</a>]&nbsp;&nbsp;";
echo "[<a href=status.php?".$str2."&top=".$bottom."&prevtop=$top>下一页</a>]";
?></div>
</div>
<div id="stat-l2" class="board" style="display:none;margin-top:20px;padding-top:10px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<h2 style="border-bottom:#ff7373 2px solid;margin-left:10px">查看状态</h2><div class="" style="width:77%;min-height:300px;text-align:left;color:green;margin-left:auto;margin-right:auto;" id="stat_text">ajax_post_disp_area</div><div style="position:absolute;bottom:10px;right:20px;"><Br></div><br>
</div>
<!--problem 残留：nowtype不等于3就能停止刷新。-->
</div>
</body>
</html>