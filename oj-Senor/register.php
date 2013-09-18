<?php 
require_once("include/db_info.inc.php");
require_once("include/my_func.inc.php");
$err_str=array();
$err_obj=array();
$err_cnt=0;
$pre_exit=0;
$len=0;
$user_id=trim($_POST['user_id']);
$len=strlen($user_id);
$email=trim($_POST['email']);
$school=trim($_POST['school']);
$vcode=trim($_POST['vcode']);
if($OJ_VCODE&&($vcode!= $_SESSION["vcode"]||$vcode==""||$vcode==null) ){
	$_SESSION["vcode"]=null;
	array_push($err_str,"验证码错误");
	array_push($err_obj,"vcode");
	$err_cnt++;
	$pre_exit=1;
}
$lang=intval($_POST['lang']);
if(!($lang>=0&&$lang<=3))
{
	array_push($err_str,"语言选择错误");
	array_push($err_obj,"lang");
	$err_cnt++;
}
if($len>20){
	array_push($err_str,"用户名过长");
	array_push($err_obj,"user-id");
	$err_cnt++;
}else if ($len<3){
	array_push($err_str,"用户名过短");
	array_push($err_obj,"user-id");
	$err_cnt++;
}
if (!is_valid_user_name($user_id)){
	array_push($err_str,"用户名必须为3-20位字母数字汉字或下划线");
	array_push($err_obj,"user-id");
	$err_cnt++;
}
if (strcmp($_POST['password'],$_POST['rptpassword'])!=0){
	array_push($err_str,"两次输入的密码不一致");
	array_push($err_obj,"regpwd2");
	$err_cnt++;
}
if (strlen($_POST['password'])<6){
	array_push($err_str,"两次输入的密码不一致");
	array_push($err_obj,"regpwd2");
	$err_cnt++;
}
$len=strlen($_POST['school']);
if ($len>100){
	array_push($err_str,"学校名字也太长了吧……");
	array_push($err_obj,"school");
	$err_cnt++;
}
$len=strlen($_POST['email']);
if ($len>100){
	array_push($err_str,"E-mail也太长了吧……");
	array_push($err_obj,"regemail");
	$err_cnt++;
}
if($pre_exit)
{
	if ($err_cnt>0){
		echo '{"no":'.$err_cnt.',"data":';
		$err_all=array();
		for($i=0;$i<$err_cnt;$i++)
		{
			array_push($err_all,(object)array('obj'=>$err_obj[$i],'str'=>$err_str[$i]));
		}
		echo json_encode((object)$err_all);
		echo '}';
		exit;
	}
}
$password=pwGen($_POST['password']);
$sql="SELECT `user_id` FROM `users` WHERE `users`.`user_id` = '".$user_id."'";
$result=mysql_query($sql);
$rows_cnt=mysql_num_rows($result);
mysql_free_result($result);
if ($rows_cnt == 1){
	array_push($err_obj,"user-id");
	array_push($err_str,"用户名已存在");
	$err_cnt++;
}
if ($err_cnt>0){
	echo '{"no":'.$err_cnt.',"data":';
	$err_all=array();
	for($i=0;$i<$err_cnt;$i++)
	{
		array_push($err_all,(object)array('obj'=>$err_obj[$i],'str'=>$err_str[$i]));
	}
	echo json_encode((object)$err_all);
	echo '}';
	exit;
}
$_SESSION["vcode"]='';
$school=mysql_escape_string(htmlspecialchars ($school));
$email=mysql_escape_string(htmlspecialchars ($email));
$ip=$_SERVER['REMOTE_ADDR'];
$sql="INSERT INTO `users`("
."`user_id`,`email`,`ip`,`accesstime`,`password`,`reg_time`,`school`,`language`)"
."VALUES('".$user_id."','".$email."','".$_SERVER['REMOTE_ADDR']."',NOW(),'".$password."',NOW(),'".$school."',$lang)";
mysql_query($sql) or die(json_encode((object)array('no'=>-1,'mysql_error'=>mysql_error())));
$sql="INSERT INTO `loginlog` VALUES('$user_id','$password','$ip',NOW())";
mysql_query($sql);
$_SESSION['user_id']=$user_id;

		$sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$_SESSION['user_id']."'";
		//echo $sql."<br />";
		$result=mysql_query($sql);
		//echo mysql_error();
		while ($row=mysql_fetch_assoc($result)){
			$_SESSION[$row['rightstr']]=true;
			//echo $_SESSION[$row['rightstr']]."<br />";
		}
		$_SESSION['ac']=Array();
		$_SESSION['sub']=Array();
		echo json_encode((object)array('no'=>0,'err'=>'注册成功 '.mysql_error()));
?>

