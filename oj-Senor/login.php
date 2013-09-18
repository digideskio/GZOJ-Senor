<?php 
	//刚刚Gitub抽了，我只好加上这样一句注释看看，会不会好
    require_once("include/db_info.inc.php");
	$vcode=trim($_POST['vcode']);
	$utype=trim($_POST["utype"]);
	if(!isset($_POST["utype"])||$utype!=='problembot')
	{
		if($OJ_VCODE&&($vcode!= $_SESSION["vcode"]||$vcode==""||$vcode==null) ){
			echo '{"no":2,"err":"验证码输入错误"}';
			exit;
		}
	}
	require_once("include/login-gzoj.php");
    $user_id=$_POST['user_id'];
	$password=$_POST['password'];
   if (get_magic_quotes_gpc ()) {
        $user_id= stripslashes ( $user_id);
        $password= stripslashes ( $password);
   }
    $sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".mysql_real_escape_string($user_id)."'";
    $result=mysql_query($sql);
	$login=check_login($user_id,$password);
	
	if ($login)
    {
		$_SESSION['user_id']=$login;
		
		echo mysql_error();
		while ($result&&$row=mysql_fetch_assoc($result))
			$_SESSION[$row['rightstr']]=true;
		echo '{"no":0,"err":"Success"}';
	}else{
		
		echo '{"no":1,"err":"用户名或密码错误"}';
	}
?>
