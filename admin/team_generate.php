<?php require("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../'>请先登录!</a>";
	exit(1);
}?>
<?php if(isset($_POST['prefix'])){
	require_once("../include/check_post_key.php");
	$prefix=$_POST['prefix'];
	require_once("../include/my_func.inc.php");
	if (!is_valid_user_name($prefix)){
		echo "前缀含有不允许的字符.";
		exit(0);
	}
	$teamnumber=intval($_POST['teamnumber']);
        $pieces = explode("\n", trim($_POST['ulist']));
	
	if ($teamnumber>0){
		echo "<table border=1>";
		echo "<tr><td colspan=3>复制这些账号，并分发</td></tr>";
		echo "<tr><td>队伍前缀<td>用户名</td><td>密码</td></tr>";
		for($i=1;$i<=$teamnumber;$i++){
			
        $user_id=$prefix.($i<10?('0'.$i):$i);
			$password=strtoupper(substr(MD5($user_id.rand(0,9999999)),0,10));
                        if(isset($pieces[$i-1]))
                        	$nick=$pieces[$i-1];
                        else
				$nick="your_own_nick";
			echo "<tr><td>$nick</td><td>$user_id</td><td>$password</td></tr>";
			
			$password=pwGen($password);
			$email="your_own_email@internet";
                       
			$school="your_own_school";
			$sql="INSERT INTO `users`("."`user_id`,`email`,`ip`,`accesstime`,`password`,`reg_time`,`nick`,`school`)"."VALUES('".$user_id."','".$email."','".$_SERVER['REMOTE_ADDR']."',NOW(),'".$password."',NOW(),'".$nick."','".$school."')on DUPLICATE KEY UPDATE `email`='".$email."',`ip`='".$_SERVER['REMOTE_ADDR']."',`accesstime`=NOW(),`password`='".$password."',`reg_time`=now(),nick='".$nick."',`school`='".$school."'";
			mysql_query($sql) or die(mysql_error());
		}
		echo  "</table>";
		
		
	}
	
}
?>
<b>队伍生成器:</b>
	
	<form action='team_generate.php' method=post>
	    队伍前缀:<input type='test' name='prefix' value='team'>
		生成<input type=input name='teamnumber' value=50>个队员账号（小于一百）.
		<input type=submit value='  生 成  '><br>
                用户:<textarea name="ulist" rows="20" cols="20"><?php if (isset($ulist)) { echo $ulist; } ?></textarea>
		<?php require_once("../include/set_post_key.php");?>
	</form>


