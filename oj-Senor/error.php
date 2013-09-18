<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>出错啦 - GZOJ</title>
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
<div id="mainboard" class="board" style="height:400px;"><h1>出错啦！</h1>
<div style="width:84%;margin-left:auto;margin-right:auto;font-weight:bold;margin-top:80px;"><?=$view_errors?></div>
<div style="width:84%;margin-left:auto;margin-right:auto;font-weight:normal;margin-top:20px;"><a href="javascript:history.back(-1);">点击返回</a></div>
</div>
</div>
</body>
</html>