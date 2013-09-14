<?php 
$cache_time=30; 
$OJ_CACHE_SHARE=false;
    require_once('./include/db_info.inc.php');
	$now=strftime("%Y-%m-%d %H:%M",time());
if (isset($_GET['cid'])) $ucid="&cid=".intval($_GET['cid']);
else $ucid="";
require_once("./include/db_info.inc.php");

$news_id = intval($_GET['news_id']);
$newsres = mysql_query('select `title`, `content`, `time`, `user_id` from `news` where `news_id`='.$news_id);

$arr = array('title'=>'新闻', 'content'=>'无法获取新闻内容', 'time'=>'YYYY-MM-DD', 'user_id'=>'Anonymous');

if($newsres&&mysql_num_rows($newsres)>0) {
	$newsrow = mysql_fetch_object($newsres);
	$arr['title']=$newsrow->title;
	$arr['content']=$newsrow->content;
	$arr['time']=$newsrow->time;
	$arr['user_id']=$newsrow->user_id;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>GZ-OJ</title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">

</head>
<body class="metrouicss">

	<?php require_once("oj-header.php");?>
<div class="page secondary with-sidebar">
	<div class="page-sidebar">
	<ul>
		<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-user"></i>用户</a>
			<ul class="sub-menu light sidebar-dropdown-menu open">
				<?php require_once("include/metro-profile.php") ?>
	<?php
		if(isset($_SESSION['user_id']) && $_SESSION['user_id']==$arr['user_id']) {
	?>
    <li class='divider'></li><li><a class='button-tool' href='admin/news_edit.php?id=<?php echo $news_id;?>'>编辑</a></li> <?php } ?>				
            </ul>
        </li>			
	</ul>
	</div>
<div class="page-region">
<div class="page-region-content">
<div class="grid">
	<h2><?php echo $arr['title']?></h2>
	<p>时间: <span class='fg-color-green'><?php echo $arr['time'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;作者: <a href="userinfo.php?user=<?php echo $arr['user_id'];?>"><span class='fg-color-blue'><?php echo $arr['user_id'];?></span></a></p>
	

	<?php echo $arr['content'];?>
</div>	
</div>
</div>
</div>
	
	<?php require_once("oj-footer.php");?>