<?php
    require_once('include/db_info.inc.php');
    require_once('include/const.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	
$solution_result_button='';
$solution_result='';
if (isset($_SESSION['user_id'])){
	$id=intval($_GET["id"]);
$sql="SELECT `result`,`contest_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."' and `problem_id`=".$id." and `contest_id` is NULL order by `solution_id`";
$result_r=@mysql_query($sql) or die(mysql_error());
$acc_st=-1;
while ($row_r=mysql_fetch_array($result_r))
{
	//if($row['contest_id']===NULL) continue;
	$acc_st=$row_r['result'];
	$solution_result=$judge_result[$acc_st];
	$solution_result_button='<div class="'.$judge_color_btn[$acc_st].'">'.$judge_result[$acc_st].'</div>';
	if($acc_st==4)
	{
		break;
	}
	//echo $row["result"];
}
if(isset($_SESSION["administrator"])) require_once("include/set_get_key.php");

		echo json_encode((object)array("no"=>0,"err"=>"ERR_SUCCESS","html"=>$solution_result_button,"statnum"=>$acc_st,"str"=>$solution_result,"privilage"=>isset($_SESSION["administrator"])?1:0,"html2"=>$acc_st!=-1?('<a href="problem-status-problem_id='.$id.'&user_id='.$_SESSION['user_id'].'" target=>查看我的提交记录</a>'):'您未提交过本题，没有你的记录',"editgetkey"=>isset($_SESSION["administrator"])?'<a class="button" style="padding:0;width:80px;text-align:center;" href="admin/problem_edit.php?id='.$id.'&getkey='.$_SESSION['getkey'].'" target=_blank style=cursor:pointer>编辑</a>':""));
		exit;

}
?>
{"no":10001,"err":"ERR_LOGIN_STATUS_INVALID"}