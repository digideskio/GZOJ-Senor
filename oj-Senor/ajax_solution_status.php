<?php
require_once("include/db_info.inc.php");
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//检测：管理员或不是竞赛中，就能看

$solution_id=0;
// check the top arg
if (isset($_GET['solution_id'])){
        $solution_id=intval($_GET['solution_id']);
}
else
{
	echo '{"no":1,"err":"未指定sid","end":1}';
	exit;
}
$res_thisuser=mysql_query('select `user_id` from `solution` where `solution_id`='.$solution_id);
$row_thisuser=mysql_fetch_array($res_thisuser);
$this_userid=$row_thisuser['user_id'];
//echo $this_userid;
	//$sql="select * from solution where solution_id=$solution_id limit 1";
	$now=strftime("%Y-%m-%d %H:%M",time());
	
	if (!isset($_SESSION['administrator']) &&!isset($_SESSION['contest_creator']))
		$sql="SELECT * FROM `solution` WHERE `solution_id`=$solution_id and ((`problem_id` NOT IN (
				SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
						SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' and `defunct`='N')) 
                            ) or  ((`result`=11 or `result`<3) and `problem_id` IN (
				SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
						SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' and `defunct`='N'))  )  )  ";
	else
		$sql="SELECT * FROM `solution` WHERE `solution_id`=$solution_id";
	//echo $sql;
	$result = mysql_query($sql);// or die("Error! ".mysql_error());
	if($result) $rows_cnt=mysql_num_rows($result);
	if($rows_cnt===0)
	{
		$sql="SELECT * FROM `solution` WHERE `solution_id`=$solution_id and `problem_id` IN (
				SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
						SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now'))
                                ";
		$result = mysql_query($sql);// or die("Error! ".mysql_error());
		if($result) $rows_cnt=mysql_num_rows($result);
		if($rows_cnt>0)
		{
			echo '{"no":2,"err":"<font color=green>程序状态：编译通过并已评测完。比赛时不能查看题目的评测结果哦~<br></font>","end":1}';
			exit;
		}
		//
		echo '{"no":2,"err":"记录不存在！<br>","end":1}';
		exit;
	}
	$row=mysql_fetch_array($result);

	$re='';
	$ce='';
	$res=$row['result'];
	$mem=$row['memory'];
	$tim=$row['time'];
	$jif=$row['judgeinfo'];
	$judgetime=$row['judgetime'];
	
			$sql="SELECT `error` FROM `compileinfo` WHERE `solution_id`='".$solution_id."'";
			$result_ce=mysql_query($sql);
			$row_ce=mysql_fetch_array($result_ce);
			if($row_ce) $ce=$row_ce['error'];
		
			$sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`='".$solution_id."'";
			$result_re=mysql_query($sql);
			$row_re=mysql_fetch_array($result_re);
			if($row_re) $re=$row_re['error'];
		
    $sql="delete from custominput where solution_id=".$solution_id;
    mysql_query($sql);
	//if(!$row['contest_id'])
	{
		echo json_encode((object)array('no'=>0,'result'=>$res,'memory'=>$mem,'time'=>$tim,'judgeinfo'=>$jif,"ceinfo"=>$ce,"reinfo"=>$re,"score"=>intval(100*floatval($row['pass_rate'])),'judgetime'=>$judgetime));
	}
	if(0)//else
	{
		echo json_encode((object)array('no'=>0,'result'=>$res,"ceinfo"=>$ce,'memory'=>'不予显示','time'=>'不予显示','judgeinfo'=>'',"reinfo"=>'',"score"=>'不予显示','judgetime'=>$judgetime));
	}
	
?>