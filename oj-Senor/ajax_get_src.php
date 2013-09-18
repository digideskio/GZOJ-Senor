<?php
require_once("include/db_info.inc.php");
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

if(!isset($_GET['id']))
{
	echo '{"no":1,"err":"未指定有效的id！"}';
	exit;
}
if(!isset($_SESSION['user_id']))
{
	echo '{"no":-1,"err":"请先登录！"}';
	exit;
}
$id=intval($_GET['id']);
if(!(isset($_SESSION['administrator'])||isset($_SESSION['source_browser'])))
$result=mysql_query("select `source` from `source_code` where `solution_id`=$id and `solution_id` in (
					select `solution_id` from `solution` where `solution_id`=$id and `user_id`='".$_SESSION['user_id']."')
			");
else
$result=mysql_query("select `source` from `source_code` where `solution_id`=$id");

$row=mysql_fetch_array($result);

if(!$row) die('{"no":2,"err":"不存在的记录，或是其他人的源码"}');

echo json_encode((object)array('no'=>0,'src'=>$row['source']));
?>