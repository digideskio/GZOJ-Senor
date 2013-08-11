<?php @session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href='../include/hoj.css' type='text/css'>
<?php if (!(isset($_SESSION['administrator'])||
			isset($_SESSION['contest_creator'])||
			isset($_SESSION['problem_editor']))){
	echo "<a href='../'>请先登录!</a>";
	exit(1);
}
require_once("../include/db_info.inc.php");
?>

<link rel="stylesheet" type="text/css" href="../css_sensor/common.css" />
<link rel="stylesheet" type="text/css" href="../css_sensor/input.css" />
<script src="../js_sensor/jquery.js"></script>
<script>$(function(){$("body").wrapInner('<div style="background-color:rgba(255,255,255,0.7)"></div>');$(".btn").css('color','white');});</script>
<!-- added by 张森 -->