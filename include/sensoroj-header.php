<?php
	if(!isset($_SESSION["user_id"]))
	{
?>
<div class="nav-li drp"><a href="javascript:" id="login_drp">登录<span class="dropdown"></span></a><div class="dropdown-menu" style="left:-100px;width:255px;"><div id="login-dropdown-menu" style="margin-top:10px;margin-bottom:10px;height:150px;"><table border="0" style="margin-left:10px;;text-align:left;line-height:33px;"><tr><td width="26%">用户名</td><td><input type="text" id="loginusr" class="textbox"/></td></tr><tr><td>密码</td><td><input type="password" id="loginpwd" class="textbox" /></td></tr><tr><td>验证码</td><td style="vertical-align:middle;"><input type="text" id="loginvrc" style="width:60px" class="textbox" onkeydown="if(pswkey(event))submitlogin();" autocomplete="off" /><div style="display:inline;vertical-align:middle;margin-left:10px;margin-top:10px;"><a href="javascript:" onclick="refresh_verifycode();" title="看不清？轻轻滴戳一下" ><div id="vcode-layer" style="display:inline;vertical-align:middle;" styleno="padding:4px;top:-6px;left:14px;position:absolute;"></div></a></div></td></tr></table><input type="button" value="登录" class="button button-def" onclick="submitlogin();" style="position:absolute;left:80px;top:120px;" /><div id="loading-login" class="loading-gif" style="position:absolute;top:123px;left:170px;"></div><div id="login-err-text" style="display:none;margin-top:8px;"></div></div></div></div><div class="nav-li" style=""><a href="registerpage.php">注册</a></div>
<?php 
	}
	else
	{ 
		$user_id_show=$_SESSION["user_id"];
		$result_nick=mysql_query("select `language` from `users` where `user_id`='$user_id_show'");
		$row_nick=mysql_fetch_array($result_nick);
		$optlang=$row_nick['language'];
		mysql_free_result($result_nick);
		$result_mail=mysql_query("select 1 from `mail` where `to_user`='$user_id_show' and `new_mail`=1");
		$new_mail_cnt=mysql_num_rows($result_mail);
		mysql_free_result($result_mail);
		
?>
<div class="nav-li drp" style="right:60px;padding:0 15px 0 15px;text-align:center;"><a href="#user_menu" id="username_link" style=""><?=$user_id_show?> <span class="number" id="new_mail_cnt"><?=$new_mail_cnt?></span><span class="dropdown"></span></a><div class="dropdown-menu" style="width=100%;left:0px;padding:10px 5px 10px 6px;"><span class="dropdown-menu-i grey">当前没有新消息</span><div class="split"></div><span class="dropdown-menu-i"><a href="userinfo.php?user=<?=$user_id_show?>">我的状态</a></span><span class="dropdown-menu-i"><a href="settings.php">个人设置</a></span><div class="split"></div><?php if(isset($_SESSION['administrator'])||isset($_SESSION['problem_editor'])){ ?><span class="dropdown-menu-i"><a href="admin" target="_blank">后台管理</a></span><div class="split"></div><?php }?><span class="dropdown-menu-i"><a href="javascript:" onclick="exitLogin();">登出</a></span></div></div>
<script>optlang=<?=$optlang?>;</script>
<?php 
	} 
?>