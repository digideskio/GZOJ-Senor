<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
	require_once("./include/db_info.inc.php");

	$profile="";
		if (isset($_SESSION['user_id']))
		{
			$sid=$_SESSION['user_id'];
			$profile.= "<li><a href=./modifypage.php>修改帐号</a></li><li><a href='./userinfo.php?user=$sid' '>$sid-用户信息</a></li>";
			$profile.="<li><a href='./status.php?user_id=$sid'>Recent</a></li>";
			
			if (isset($_SESSION['administrator'])||isset($_SESSION['contest_creator'])||isset($_SESSION['problem_editor']))
				$profile.= "<li class='divider'></li><li><a href=./admin/>管理</a></li>";
				
			$profile.= "<li class='divider'></li><li><a href=./logout.php>注销</a></li>";
		}
		else
		{
			$profile.= "<li><a href=./loginpage.php>登录</a></li><li class='divider'></li><li><a href=./registerpage.php>注册</a></li>";
		}
?>
<?php echo $profile ?>
