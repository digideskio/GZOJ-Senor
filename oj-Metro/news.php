<?php
	require_once('./include/db_info.inc.php');
	
	$page="1";
	if(isset($_GET['page'])) $page = intval($_GET['page']);
	
	$p_start = 15*($page - 1) + 1;
	$p_end = $p_start + 14;
	
	$sql="SELECT max(`news_id`) as upid FROM `news`";
	$result=mysql_query($sql);
	$row=mysql_fetch_object($result);
	$cnt=intval($row->upid);
	$page_cnt = intval($cnt/15) + 1;
	
	$newsstr = "";
	$newsres=mysql_query("select `news_id`, `title`, `time` from `news` where (`defunct`='N' and `news_id`>='$p_start' and `news_id`<='$p_end') order by `news_id` desc");
	if(!$newsres||mysql_num_rows($newsres)==0) $newsstr = '暂无新闻';
	else {
		$cnt = 0;
		$newsstr='<table class="striped">';
		while($newsrow = mysql_fetch_object($newsres)) {
			$newsstr .= '<tr><td><a href="shownews.php?news_id='.$newsrow->news_id.'">'.htmlspecialchars($newsrow->title).'</a></td><td class="fg-color-green">'.$newsrow->time.'</td></tr>';
			$cnt++;
		}
		$newsstr .='</table>';
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>新闻</title>
	
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
	</ul>
	</div>
<div class="page-region">
<div class="page-region-content">
<div class="grid">
	<h2>新闻</h2>

	<?php echo $newsstr; ?>

	<?php
		for($i=1; $i<=$page_cnt; $i++) {
			if($i==$page) echo $i;
			else echo "<a href='news.php?page=".$i."'>".$i."</a>";
		}
	?>
</div>
</div>
</div>
</div>
	
	<?php require_once("oj-footer.php");?>
