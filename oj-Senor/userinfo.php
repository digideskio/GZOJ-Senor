<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$user_id=$_GET['user'];
//if(!preg_match('/\w{3,50}/',$user_id))
if(0){
	$view_errors="数据库查询过程中出了一个小错误: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'where `user_id`='$user_id'' at line 1";	//让傻×的注入者以为这儿有一个注入点。
	require_once "error.php";
	exit(1);
}
$result=mysql_query("select `user_id`,`email`,`submit`,`solved`,`reg_time`,`language`,`school`,`skin` from `users` where `user_id`='".mysql_escape_string($user_id)."'");
$row=mysql_fetch_object($result);
if(!$row||mysql_num_rows($result)===0)
{
	$view_errors="指定的用户不存在。";
	require_once "error.php";
	exit(1);
}
$user_id=$row->user_id;
	require_once("include/const.inc.php");
	require_once("include/my_func.inc.php");
$user=$user_id;
$user_mysql=$user;
$school=$row->school;
$email=$row->email;
$skin=$row->skin;
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
/*
 if (isset($_SESSION['administrator'])){
$sql="SELECT * FROM `loginlog` WHERE `user_id`='$user_mysql' order by `time` desc LIMIT 0,10";
$result=mysql_query($sql) or die(mysql_error());
$view_userinfo=array();
$i=0;
for (;$row=mysql_fetch_row($result);){
	$view_userinfo[$i]=$row;
	$i++;
}
echo "</table>";
mysql_free_result($result);
}*/
$sql="SELECT result,count(1) FROM solution WHERE `user_id`='$user_mysql'  AND result>=4 group by result order by result";
	$result=mysql_query($sql);
	$view_userstat=array();
	$i=0;
	while($row=mysql_fetch_array($result)){
		$view_userstat[$i++]=$row;
	}
	mysql_free_result($result);

$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql'   group by md order by md desc ";
	$result=mysql_query($sql);//mysql_escape_string($sql));
	$chart_data_all= array();
//echo $sql;
    
	while ($row=mysql_fetch_array($result)){
		$chart_data_all[$row['md']]=$row['c'];
    }
    
$sql=	"SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql' and result=4 group by md order by md desc ";
	$result=mysql_query($sql);//mysql_escape_string($sql));
	$chart_data_ac= array();
//echo $sql;
    
	while ($row=mysql_fetch_array($result)){
		$chart_data_ac[$row['md']]=$row['c'];
    }
  
  mysql_free_result($result);
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>用户 <?=$user?> - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />

<script type="text/javascript" src="js_senor/wz_jsgraphics.js"></script>
<script type="text/javascript" src="js_senor/pie.js"></script>
<script language="javascript" type="text/javascript" src="js_senor/jquery.flot.js"></script>

<script>$(function(){common_init();});</script>
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
    {label:"<?php echo '总提交'?>",data:d1,lines: { show: true }},
    {label:"<?php echo '通过数'?>",data:d2,bars:{show:true}} ],{
    
        
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

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="disp-board" class="disp-board" style="height:100px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;"><h1>查看用户 - <?=$user?></h1>
<div style="margin-left:20px;color:grey;font-size:15px"><?php if((isset($_SESSION['user_id']))&&$_SESSION['user_id']!==$user_id){?><span style="margin-left:20px;margin-right:20px;"><a href="user-mail-send-<?=$user_id?>">发送站内信</a><?php }?></span></div>
</div>
<div class="board" style="margin-top:20px;padding-top:10px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<table style="border-spacing:1px;width:80%;padding:0;margin-left:auto;margin-right:auto;line-height:24px" id="statics"><colgroup><col style="background-color:rgba(20%,80%,100%,0.3);"><col style="background-color:rgba(20%,80%,100%,0.03)"></colgroup><tr><td width="18%" style="padding-left:20px;">排名</td><td width="80%" style="padding-left:20px;"><?=$Rank?></td></tr><tr><td style="padding-left:20px;">解决题数：</td><td style="padding-left:20px;"><a href='problem-status-user_id=<?php echo $user?>&jresult=4'><?php echo $AC?></a></td></tr><tr><td style="padding-left:20px;">解决的问题：</td><td id="solved-problem" style="padding-left:20px;"><script language='javascript'>$(function(){function p(id){$('#solved-problem').append(" <a href=problem-"+id+">"+id+"</a>");}<?php $sql="SELECT DISTINCT `problem_id` FROM `solution` WHERE `user_id`='$user_mysql' AND `result`=4 ORDER BY `problem_id`";	
if (!($result=mysql_query($sql))) echo mysql_error();
while ($row=mysql_fetch_array($result))
	echo "p($row[0]);";
mysql_free_result($result);
?>});</script></td></tr><tr><td style="padding-left:20px;">总提交数：</td><td align=center style="padding-left:20px;"><a href='problem-status-user_id=<?php echo $user?>'><?php echo $Submit?></a></td></tr><?php foreach($view_userstat as $row){echo "<tr><td style='padding-left:20px;'>".$judge_result[$row[0]]."</td><td align=center style=\"padding-left:20px;\"><a href='problem-status-user_id=".$user."&jresult=".$row[0]."'>".$row[1]."</a></td></tr>";}?><tr id='pie'><td style="padding-left:20px;">状态图：</td><td style="padding-left:20px;"><div id='PieDiv' style='position:relative;height:105px;width:120px;'></div></td></tr><tr><td style="padding-left:20px;">学校：</td><td style="padding-left:20px;"><?=htmlspecialchars($school)?></td></tr><tr><td style="padding-left:20px;">电子邮箱：</td><td style="padding-left:20px;"><?=htmlspecialchars($email==''?'----':$email)?></td></tr><tr><td style="padding-left:20px;">正使用的皮肤：</td><td style="padding-left:20px;"><a href="skin-<?=$skin?>" title="切换到此皮肤"><?=$SKINSTR[$skin]?></a></td></tr></table><br><br><div id=submission style="width:600px;height:300px;margin-left:auto;margin-right:auto;" ></div>
</div>
<script language="javascript">
$(function(){
	var y= new Array ();
	var x = new Array ();
	var dt=document.getElementById("statics");
	var data=dt.rows;
	var n;
	for(var i=4;dt.rows[i].id!="pie";i++){
			n=dt.rows[i].cells[0];
			n=n.innerText || n.textContent;
			x.push(n);
			n=dt.rows[i].cells[1].firstChild;
			n=n.innerText || n.textContent;
			//alert(n);
			n=parseInt(n);
			y.push(n);
	}
	var mypie=  new Pie("PieDiv");
	mypie.drawPie(y,x);
	//mypie.clearPie();
});
</script> 
</div>
</body>
</html>