<?php
    require_once('include/db_info.inc.php');
	require_once("include/my_func.inc.php");
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");

$user_id=trim($_POST['user_id']);
if(!is_valid_user_name($user_id)||strlen($user_id)<3)
{
	echo '{"no":2,"err":"用户名必须为3-20位字母数字汉字或下划线"}';
	exit;
}
$sql="SELECT `user_id` FROM `users` WHERE `users`.`user_id` = '".$user_id."'";
$result=mysql_query($sql);
$rows_cnt=mysql_num_rows($result);
mysql_free_result($result);
if ($rows_cnt == 1){
	echo '{"no":1,"err":"用户名已被占用，请尝试换一个"}';
	exit;
}
//没错误了？
	echo '{"no":0,"err":"ERR_SUCCESS"}';
?>
