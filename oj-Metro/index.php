<?php
    require_once('./include/db_info.inc.php');

function FetchFriendLink()
{
	$flres=mysql_query("select `title`,`link` from `friendlink` where `enable`=1 order by `sort`");
	if(!$flres||mysql_num_rows($flres)==0) return '暂无友链~欢迎交换';
	$cnt=0;
	$retstr='<ul>';
	while($flrow=mysql_fetch_object($flres))
	{
		
		$retstr.='<li><a class="link" href="'.$flrow->link.'" target="_blank">'.htmlspecialchars($flrow->title).'</a></li>';
		$cnt++;
	}
	$retstr.='</ul>';
	return $retstr;
}

function FetchNews() {
	$newsres=mysql_query("select `news_id`, `title`, `time` from `news` where `defunct`='N' order by `news_id` desc limit 5");
	if(!$newsres||mysql_num_rows($newsres)==0) return '暂无新闻';
	$cnt = 0;
	$retstr='<table class="striped">';
	while($newsrow = mysql_fetch_object($newsres)) {
		$retstr .= '<tr><td><a href="shownews.php?news_id='.$newsrow->news_id.'">'.htmlspecialchars($newsrow->title).'</a></td><td class="fg-color-green">'.$newsrow->time.'</td></tr>';
		$cnt++;
	}
	$retstr .='</table>';
	$retstr .= '<h5><a href="news.php"  class="fg-color-red">更多...</a></h5>';
	
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
            </ul>
        </li>
		<li class="sticker sticker-color-pink dropdown" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-tab"></i>友情链接</a>
			<ul class="sub-menu light sidebar-dropdown-menu">
				<?php echo FetchFriendLink() ?>
            </ul>
        </li>				
	</ul>
	</div>
<div class="page-region">
<div class="page-region-content">
<div class="grid">
	<div class="carousel" style="height:300px;width:780px" data-role="carousel" data-param-period="4500" data-param-duration="2000" data-param-arrors="off">
		<div class="slides">
		<?php 
			for ($i=1;$i<=$imgcnt;$i++)
				echo "<div class='slide image' id='slide".$i."'><img src='./splash/".$i.".jpg' /></div>";
		?>
		</div>
	</div>
	<br><br>
	<h2>新闻</h2>
	<?php echo FetchNews() ?>
</div></div></div></div>
	<?php require_once("oj-footer.php");?>

<!--javascript && /body && /html  is in oj-footer.php -->
