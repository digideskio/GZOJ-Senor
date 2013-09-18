<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
function FetchFriendLink()
{
	$flres=mysql_query("select `title`,`link` from `friendlink` where `enable`=1 order by `sort`");
	if(!$flres||mysql_num_rows($flres)==0) return '暂无友链~欢迎交换';
	$cnt=0;
	$retstr='<table style="">';
	while($flrow=mysql_fetch_object($flres))
	{
		if($cnt!==0&&$cnt%5==0) $retstr.='</tr>';
		if($cnt%5==0) $retstr.='<tr style="line-height:22px">';
		$retstr.='<td style="width:170px;text-align:center"><a href="'.$flrow->link.'" target="_blank">'.htmlspecialchars($flrow->title).'</a></td>';
		$cnt++;
	}
	$retstr.='</tr></table>';
	return $retstr;
}
for($imgcnt=1;$imgcnt<=10;$imgcnt++)
{
	if(!file_exists('./splash/'.$imgcnt.'.jpg'))
	{
		$imgcnt--;
		break;
	}
}
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="GZOJ,GanZhongOJ,gygjzx,OI,GZOI,赣榆高级中学OI,赣榆高级中学,赣榆高级中学信息学,赣榆高级中学信息学奥林匹克" />
<title>首页 - GZOJ</title>
<script language="javascript" src="js_senor/jquery.js"></script>
<script language="javascript" src="js_senor/jquery.color.js"></script>
<script language="javascript" src="../js_senor/jquery.imageswitch.js"></script>
<script language="javascript" src="js_senor/perfect-scrollbar.js"></script>
<script language="javascript" src="js_senor/common.js"></script>
<?php require_once("include/senoroj-festival.php");?>
<link rel="stylesheet" type="text/css" href="css_senor/common.css" />
<link rel="stylesheet" type="text/css" href="css_senor/input.css" />
<link rel="stylesheet" type="text/css" href="css_senor/perfect-scrollbar.css" />
<script>$(function(){common_init();index_init();});</script>
<script>var imgCnt=<?php echo $imgcnt;?></script>
</head>

<body>
<div id="preloader">
</div>
<?php require('include/senoroj-navbar.php');?>

<div id="container">

<div id="disp-board" class="disp-board"><div id="btn-prev" class="btn-trans"></div><img id="splash" alt="splash" src="splash/1.jpg"/><div id="btn-next" class="btn-trans"></div></div>
<div id="mainboard" class="board" style="min-height:0px;width:790px;padding-bottom:30px;"><h1 style="border-bottom:#F69 2px solid">近期公告</h1><div style="width:90%;font-size:14px;margin-left:auto;margin-right:auto;"><?=file_get_contents("admin/msg.txt");?></div><br><div style="position:relative"><h1>资讯快递</h1><div id="newserr" style="display:inline;font-size:10px;position:relative"></div><div style="position:absolute;right:40px; font-size:12px;top: 27px"><a href="javascript:void(0);" onclick="show_prev_news();">上一页</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="show_next_news();">下一页</a></div><div id="news-title-show" style="width:90%;margin-left:auto;margin-right:auto;font-size:15px;opacity:0;">
<!--
<table width="100%" border="0">
    <tr>
      <td width="81%" style="font-size:14px"><a href="javascript:" onclick="show_news(id)" target="_blank">title</a></td>
      <td width="19%" style="font-size:12px">time</td>
    </tr>
</table>
-->
 </div></div><br>
 <h1 style="border-bottom:#6C9 2px solid">友情链接</h1>
 <div style="width:97%;font-size:13px;margin-left:auto;margin-right:auto;"><?=FetchFriendLink()?></div>
</div>
<div id="newsshowboard-layer" class="board" style="padding-bottom:40px;display:none;height:700px;width:100%;"><h1 id="newstitle">标题</h1>
<span style="position:absolute;right:40px; font-size:14px;">作者：<div id="news-author" style="font-size:12px;display:inline"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="news_back_to_list();">返回标题</a></span>
<div id="newsshowboard-l2" style="overflow:hidden;width:97%;height:95%;margin-left:auto;margin-right:auto;position:relative;">
<div id="newsshowboard" style="padding:0;margin:0;">加载新闻中……</div>
</div>
</div>
</div>
<div id="festival-eg"></div>
</body>
</html>