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
<title>关于 - GZOJ</title>
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
<div id="mainboard" class="board" style="padding-bottom:40px;"><?=$_SERVER['REMOTE_ADDR']?><br><h1>关于赣中OJ</h1><div class="em-l"><div class="em-t">GZOJ是由江苏省赣榆高级中学主办的信息学奥林匹克竞赛在线评测系统，旨在为我校参赛选手和OIer提供优质的在线评测服务。</div><div class="em-t">
基于<a href="https://code.google.com/p/hustoj/" target="_blank">HustOJ</a>的基本框架（一部分的数据库表结构，以及后台部分代码）。
</div></div>
<div class="em-l"><div class="em-t">由2010级张森(<a href="https://github.com/zhs490770/" target="_blank">zhs</a>)、陈政儒(<a href="https://github.com/ZeroClad" target="_blank">zero</a>)和匡振宇(<a href="http://bitex.me" target="_blank">Bitex</a>)暂时负责管理。多皮肤接口、后台翻译、转接：zhs</div></div>
<h1>关于本皮肤</h1><div class="em-l" style="margin-bottom:14px;"><b>Senor Style for GZOJ - 现代感</b></div><div class="em-l"><div class="em-t">作者：<a href="https://github.com/zhs490770/" target="_blank">zhs (Senor)</a></div></div><div class="em-l"><div class="em-t">版本：β</div></div><div class="em-l"><div class="em-t">特色：多处使用Ajax页面动态载入技术，登录、题库换页，提交评测等等都无需刷新，原处等待即可得到结果。</div></div><div class="em-l"><div class="em-t">GitHub：<a href="https://github.com/zhs490770/GZOJ-Senor" target="_blank">GZOJ-Senor</a></div><div class="em-t">更新日志：<a href="https://github.com/zhs490770/GZOJ-Senor/commits/master" target="_blank">查看GZOJ-Senor在GitHub上的提交记录</a></div></div>
<h1>关于评测核心</h1><div class="em-l"><div class="em-t">Senor Judge(by zhs) for windows，目前已实现了全部评测功能，但是安全方面还没开始做。马上做，不要着急~</div></div>
<div class="em-l"><div class="em-t">一点点的FAQ：有可能出现最大占用时间极小（未超过题目限制），但是报告超时的情况，这是因为最大占用时间是按用户态时间算的，同时本评测机会检测程序的内核态时间是否超出题目限制太多（500ms），如果超出太多，即使用户态时间很少，也会报告超时。</div></div>
<div class="em-l"><div class="em-t">同时，欢迎大家报告各种评测机以及本皮肤的bug：<span id="email-src"></span><script>$(function(){document.getElementById('email-src').innerHTML='z'+'h'+'s'+'4907'+'70'+'@'+'fo'+'xm'+'ail'+'.co'+'m'+'';});</script></div></div>
<h1>权利与许可声明</h1><div class="em-l"><div class="em-t">©2013 GZOJ   保留所有权利。</div></div><div class="em-l"><div class="em-t">HustOJ: <a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPL2.0</a> 2003-2013 <a href="http://code.google.com/p/hustoj/" target="_blank">HUSTOJ Project</a> TEAM</div></div>
<h1>鸣谢</h1><div class="em-l"><div class="em-t">江苏省赣榆高级中学 祁进、祁志强老师</div></div><div class="em-l"><div class="em-t">参与策划、部署、测试的赣中OIer们</div></div><div class="em-l"><div class="em-t">HUSTOJ Project TEAM</div></div>
</div>
</div>
</body>
</html>