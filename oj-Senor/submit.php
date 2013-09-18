<?php

require_once("include/const.inc.php");
require_once("include/db_info.inc.php");

if (!isset($_SESSION['user_id'])){
	die('{"no":10001,"err":"登录状态无效，请重新登登录"}');
}
  $now=strftime("%Y-%m-%d %H:%M",time());
$user_id=$_SESSION['user_id'];

if (isset($_POST['cid'])){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
	$sql="SELECT `problem_id` from `contest_problem` 
				where `num`='$pid' and contest_id=$cid";
}else{
	$id=intval($_POST['id']);
	$sql="SELECT `problem_id` from `problem` where `problem_id`='$id' and problem_id not in (select distinct problem_id from contest_problem where `contest_id` IN (
			SELECT `contest_id` FROM `contest` WHERE 
			(`end_time`>'$now' )and `defunct`='N'
			))";
	if(!isset($_SESSION['administrator']))
		$sql.=" and defunct='N'";
}
//echo $sql;	

$res=mysql_query($sql);
if ($res&&mysql_num_rows($res)<1&&!isset($_SESSION['administrator'])&&!((isset($cid)&&$cid==0)||(isset($id)&&$id==0))){
		mysql_free_result($res);
		echo '{"no":1,"err":"不存在的问题"}';
		exit(0);
}
mysql_free_result($res);



if (isset($_POST['id'])) {
	$id=intval($_POST['id']);
	
}else if (isset($_POST['pid']) && isset($_POST['cid'])&&$_POST['cid']!=0){
	$pid=intval($_POST['pid']);
	$cid=intval($_POST['cid']);
	// check user if private
	$sql="SELECT `private` FROM `contest` WHERE `contest_id`='$cid' AND `start_time`<='$now' AND `end_time`>'$now'";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	if ($rows_cnt!=1){
		echo '{"no":2,"err":"不能提交，因为你未被邀请到比赛中，或比赛尚未开始"}';
		mysql_free_result($result);
		exit(0);
	}else{
		$row=mysql_fetch_array($result);
		$isprivate=intval($row[0]);
		mysql_free_result($result);
		if ($isprivate==1){
			$sql="SELECT count(*) FROM `privilege` WHERE `user_id`='$user_id' AND `rightstr`='c$cid'";
			$result=mysql_query($sql) or die (mysql_error()); 
			$row=mysql_fetch_array($result);
			$ccnt=intval($row[0]);
			mysql_free_result($result);
			if ($ccnt==0&&!isset($_SESSION['administrator'])){
				echo '{"no":3,"err":"错误：你尚未被邀请"}';
				exit(0);
			}
		}
	}
	$sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`='$cid' AND `num`='$pid'";
	$result=mysql_query($sql);
	$rows_cnt=mysql_num_rows($result);
	if ($rows_cnt!=1){
		echo '{"no":4,"err":"错误：问题不存在于指定的竞赛中"}';
		mysql_free_result($result);
		exit(0);
	}else{
		$row=mysql_fetch_object($result);
		$id=intval($row->problem_id);
		mysql_free_result($result);
	}
}else{
       $id=0;
}

$language=intval($_POST['language']);
if ($language>count($language_name) || $language<0) $language=0;
mysql_query("update `users` set `language`=$language where `user_id`='".$_SESSION['user_id']."'");
$language=strval($language);


$source=$_POST['source'];
$input_text=$_POST['input_text'];
if(get_magic_quotes_gpc()){
	$source=stripslashes($source);
	$input_text=stripslashes($input_text);

}
$source=mysql_real_escape_string($source);
$input_text=mysql_real_escape_string($input_text);
//$source=trim($source);
//use append Main code
$append_file="$OJ_DATA/$id/append.$language_ext[$language]";
if(isset($OJ_APPENDCODE)&&$OJ_APPENDCODE&&file_exists($append_file)){
     $source.=mysql_real_escape_string("\n".file_get_contents($append_file));
}
//end of append 


$len=strlen($source);
//echo $source;




setcookie('lastlang',$language,time()+360000);

$ip=$_SERVER['REMOTE_ADDR'];

if ($len<2){
	echo '{"no":5,"err":"错误：代码太短，无法提交"}';
	exit(0);
}
if ($len>65536){
	echo '{"no":6,"err":"错误：代码太长，无法提交"}';
	exit(0);
}

// last submit
if(!isset($_SESSION['administrator']))
{
$now=strftime("%Y-%m-%d %X",time()-10);
$sql="SELECT `in_date` from `solution` where `user_id`='$user_id' and in_date>'$now' order by `in_date` desc limit 1";
$res=mysql_query($sql);
if (mysql_num_rows($res)==1){
	//$row=mysql_fetch_row($res);
	//$last=strtotime($row[0]);
	//$cur=time();
	//if ($cur-$last<10){
		echo '{"no":7,"err":"错误：提交两次的时间间隔至少要有10秒钟！请返回等待"}';
		exit(0);
	//}
}
}

if((~$OJ_LANGMASK)&(1<<$language)){

	if (!isset($pid)){
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
	}else{
	$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,contest_id,num)
		VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$cid','$pid')";
	}
	mysql_query($sql);
	$insert_id=mysql_insert_id();
	
	echo '{"no":0,"sid":'.$insert_id.'}';
	
	$sql="INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$source')";
	mysql_query($sql);

}

?>
