<?php
require_once('include/db_info.inc.php');
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
$first=1000;
  //if($OJ_SAE) $first=1;
$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt=50;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$cnt=intval($row->upid)-$first;
$cnt=$cnt/$page_cnt;
$view_total_page=floor($cnt+1);
//得到题目数目
 $page=1;
 
if (isset($_GET['page'])){
    $page=intval($_GET['page']);
    /*if(isset($_SESSION['user_id'])){
         $sql="update users set volume=$page where user_id='".$_SESSION['user_id']."'";
         mysql_query($sql);
    }*/
}else{
    if(isset($_SESSION['user_id'])){
            $sql="select volume from users where user_id='".$_SESSION['user_id']."'";
            $result=@mysql_query($sql);
            $row=mysql_fetch_array($result);
            $page=intval($row[0]);
    }
    if(!is_numeric($page))
        $page=1;
}
  //记住页码
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>题库 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<script language="javascript" src="js_senor/problemset.js"></script>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<script>$(function(){common_init();problemset_init();});</script>
<script><?php echo 'var page='.$page.',totalpage='.($view_total_page).';';?>if(/#page\d+$/.test(location.href))page=/#page(\d+)$/.exec(location.href)[1];</script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">
<div id="disp-board" class="disp-board" style="height:170px;text-align:left;padding:13px;background-color:rgba(255,255,255,0.7);margin-bottom:0px;width:920px;"><h1>题库</h1>
  <table>
  <tr align='center' class='evenrow'>
				<form class=form-search action=problem.php>
			<td >
					PID：<input class="input-small search-query textbox" type='text' name='id' size=11 style="">
                                  <td><button class="btn " type='submit'  >快速进入</button>
                                  </td>
</form>
     </tr>
     <tr>
			<form class="form-search" name="frmSearch">
			<td>
				<input style="" type="text" name='txtsearch' class="input-large search-query textbox"><td>
				<button type="button" class="btn " onclick="find_problemset();">搜索题目</button></td>
			</tr>
  </table>
</div>
<div class="board" style="margin-top:20px;padding-top:3px;padding-bottom:20px;padding-left:0px;padding-right:0px;width:950px;">
<div id="nav_page_switch">
<div id="loading-problemset" class="loading-gif" style="display:inline-block;position:inherit;top:6px;trasition:all 1s linear;opacity:0;"></div><div style="display:inline;" id="page-switcher-layer">页码：
<?php
//$view_total_page
for($i=1;$i<=$view_total_page;$i++)
{
	echo '<a href="#page'.$i.'" id="a-page'.$i.'" class="switch_page_btn btn ';
	if($i===$page) echo 'btn-warning';else echo 'btn-gray';
	echo '" onclick="rf_page('.$i.');">'.$i.'</a>';
}
?>
</div></div>
<div id="problemset_show"></div>
</div>
</div>

</div>
</body>
</html>