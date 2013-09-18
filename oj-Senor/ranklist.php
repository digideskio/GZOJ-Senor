<?php
    require_once('include/db_info.inc.php');
	require_once('include/const.inc.php');
	
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $scope="";
        if(isset($_GET['scope']))
                $scope=$_GET['scope'];
        if($scope!=""&&$scope!='d'&&$scope!='w'&&$scope!='m')
                $scope='y';
        $rank = 0;
		$currank=0;
        if(isset( $_GET ['start'] ))
		{
                $rank = intval ( $_GET ['start'] );
				$currank = intval ( $_GET ['start'] );
		}
                $page_size=50;
                if ($rank < 0)
                        $rank = 0;

                $sql = "SELECT `user_id`,`solved`,`submit` FROM `users` ORDER BY `solved` DESC,submit,reg_time  LIMIT  " . strval ( $rank ) . ",$page_size";
                if($scope){
                        $s="";
                        switch ($scope){
                                case 'd':
                                        $s=date('Y').'-'.date('m').'-'.date('d');
                                        break;
                                case 'w':
                                        $monday=mktime(0, 0, 0, date("m"),date("d")-(date("w")+7)%8+1, date("Y"));
                                        $s=strftime("%Y-%m-%d",$monday);
                                        break;
                                case 'm':
                                        $s=date('Y').'-'.date('m').'-01';
                                        ;break;
                                default :
                                        $s=date('Y').'-01-01';
                        }
                        $sql="SELECT users.`user_id`,s.`solved`,t.`submit` FROM `users`
                                        right join
                                        (select count(distinct problem_id) solved ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') and result=4 group by user_id order by solved desc limit " . strval ( $rank ) . ",$page_size) s on users.user_id=s.user_id
                                        left join
                                        (select count( problem_id) submit ,user_id from solution where in_date>str_to_date('$s','%Y-%m-%d') group by user_id order by submit desc limit " . strval ( $rank ) . ",".($page_size*2).") t on users.user_id=t.user_id
                                ORDER BY s.`solved` DESC,t.submit,reg_time  LIMIT  0,50 ";
                }
		       $result = mysql_query($sql) or die("Error! ".mysql_error());
                if($result) $rows_cnt=mysql_num_rows($result);
                else $rows_cnt=0;

                $view_rank=Array();
                $i=0;
                for ( $i=0;$i<$rows_cnt;$i++ ) {
                        if($OJ_MEMCACHE)
                                $row=$result[$i];
                        else
                                $row=mysql_fetch_array($result);
                        $rank ++;
						$fontstyle='';
						if($rank<=3) $fontstyle=' style="color:rgb(0,200,0);text-shadow: 2px 2px 3px #999999;" ';
                        $view_rank[$i][0]="<div align=center$fontstyle>$rank</div>";
                        $view_rank[$i][1]="<div align=left><a href='user-info-".$row['user_id']."'>".$row['user_id']."</a>"."</div>";
                        //$view_rank[$i][2]= "<div align=left$fontstyle>".htmlspecialchars($row['nick'])."</div>";
                        $view_rank[$i][2]="<div align=right$fontstyle><a href='problem-status-user_id=".$row['user_id']."&jresult=4'>".$row['solved']."</a>"."</div>";
                        $view_rank[$i][3]="<div align=right$fontstyle><a href='problem-status-user_id=".$row['user_id']."'>".$row['submit']."</a>"."</div>";

                        if ($row['submit'] == 0)	//防止被0除
                                $view_rank[$i][4]= "<font color=red>0%</font>";
                        else
						{
							$thisscore=intval(100*$row['solved']/$row['submit']);
                        	$view_rank[$i][4]="<font style='color:rgb(".$ScoreColor[$thisscore][0].",".$ScoreColor[$thisscore][1].",".$ScoreColor[$thisscore][2].")'>$thisscore%</font>";
						}
						$view_rank[$i][4]="<div $fontstyle>".$view_rank[$i][4]."</div>";
                }


                $sql = "SELECT count(1) as `mycount` FROM `users`";
                $result = mysql_query($sql);// or die("Error! ".mysql_error());
                if($result) $rows_cnt=mysql_num_rows($result);
                else $rows_cnt=0;
                $row=mysql_fetch_array($result);
                echo mysql_error ();
                $view_total=$row['mycount'];

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>排行榜 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();});</script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="mainboard" class="board" style="top:-20px;padding:0;padding-bottom:10px;"><h1 style="margin-left:5px;margin-top:5px">排行榜</h1>
<div id="ranklist-brd" style="width:100%;margin-left:auto;margin-right:auto;">

  <table width="100%" border="0" style="border-collapse: collapse;border-spacing: 0;">
    <tr style="background-color:rgba(20%,80%,100%,0.1);font-size:15px;line-height:30px">
      <td width="12%" style="text-align:center">No.</td>
      <td width="26%">用户名</td>
      <td width="19%" style="text-align:right">AC</td>
      <td width="17%" style="text-align:right">提交</td>
      <td width="17%" style="text-align:right">通过率</td>
      <td width="9%" style=""></td>
    </tr>
    <?php
	foreach($view_rank as $row)
	{
	?><tr style="line-height:20px"><td><?=$row[0]?></td><td><?=$row[1]?></td><td><?=$row[2]?></td><td><?=$row[3]?></td><td style="text-align:right"><?=$row[4]?></td><td></td></tr><?php }?>
  </table>
  	<?php 
	   echo "<center>";//$view_total
		for($i = 0; $i < $view_total; $i += $page_size) {
			echo "<a href='./ranklist-".strval($i).($scope?"&scope=$scope":"")."' class='btn".($i==$currank?" btn-warning' style='color:white":"' style='color:black").";min-width:70px'>";
			echo strval ( $i + 1 );
			echo "-";
			echo strval ( $i + $page_size );
			echo "</a>&nbsp;";
			if ($i % 400 == 1)
				echo "<br>";
		}
		echo "</center>";
	
	?>
 </div>
</div>
</div>
</body>
</html>