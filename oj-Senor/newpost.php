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
<title>撰写新主题 - GZOJ</title>
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
<div id="mainboard" class="board" style="padding-bottom:40px;"><h1>撰写新主题</h1></div>
</div>
</body>
</html>

<center>
<div style="width:90%; text-align:left">
<h2 style="margin:0px 10px">Post New Thread<?php if (array_key_exists('cid',$_REQUEST) && $_REQUEST['cid']!='') echo ' For Contest '.$_REQUEST['cid'];?></h2>
<form action="post.php?action=new" method=post>
<input type=hidden name=cid value="<?php if (array_key_exists('cid',$_REQUEST)) echo $_REQUEST['cid'];?>">
<div style="margin:0px 10px">Problem : </div>
<div><input name=pid style="border:1px dashed #8080FF; width:100px; height:20px; font-size:75%;margin:0 10px; padding:2px 10px" value="<?php if(array_key_exists('pid',$_REQUEST)) echo $_REQUEST['pid']; ?>"></div>
<div style="margin:0px 10px">Title : </div>
<div><input name=title style="border:1px dashed #8080FF; width:700px; height:20px; font-size:75%;margin:0 10px; padding:2px 10px"></div>
<div style="margin:0px 10px">Content : </div>
<div><textarea name=content style="border:1px dashed #8080FF; width:700px; height:400px; font-size:75%; margin:0 10px; padding:10px"></textarea></div>
<div><input type="submit" style="margin:5px 10px" value="Submit"></input></div>
</form>
</div>
</center>

