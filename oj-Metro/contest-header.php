<?php  
	require_once('./include/db_info.inc.php');
?>

<?php if(isset($_GET['cid']))
	$cid=intval($_GET['cid']);
if (isset($_GET['pid']))
	$pid=intval($_GET['pid']);
?>

<div class="nav-bar">
	<div class="nav-bar-inner padding10" style="z-index:1;height:50px !important">	
	</div>
</div>
<div class="page secondary page-region page-region-content">
<div class="nav-bar" style="position:absolute;top:-50px">
	<div class="nav-bar-inner padding10"style="z-index:2">
		<span class="pull-menu"></span>

		<a href="index.php"><span class="element brand">
			<img class="place-left" src="img_metro/chrome.png" style="height:20 px"/>
			GZ-OJ</span>
		</a>
		
		<div class="divider"></div>
		
		<ul class="menu">
			<li><a href="./">主页</a></li>
			<li><a href="./contest.php?cid=<?php echo $cid?>">问题</a></li>
			<li><a href="./status.php?cid=<?php echo $cid?>">记录</a></li>
			<li><a href="./contestrank.php?cid=<?php echo $cid?>">名次</a></li>
			<li><a href="./bbs.php?cid=<?php echo $cid?>">讨论</a></li>
			<li><a href="./conteststatistics.php?cid=<?php echo $cid?>">统计</a></li>
			<li><a href="about.php">ABOUT</a></li>
            <li data-role="dropdown" easefade="0" class="place-right"><a href="#"><?php if (isset($_SESSION['user_id'])) echo $_SESSION['user_id']; else echo '用户'; ?></a>
                <ul class="dropdown-menu">
				<center>
					<?php require_once("include/metro-profile.php");?>
				</center>
                </ul>
            </li>
		</ul>		
	</div>
</div>
</div>
<br>