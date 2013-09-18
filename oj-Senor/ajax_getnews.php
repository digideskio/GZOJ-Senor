<?php
    require_once('include/db_info.inc.php');
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");

	
	
?><?php
if($_GET['type']=='list')
{
	$start=intval($_GET['start']);
	$result=mysql_query("select `title`,`news_id`,`time` from `news` where `defunct`='N' order by `news_id` desc limit $start,10");
	if(mysql_num_rows($result)>0)
	{
		$obj=array();
		while($row=mysql_fetch_object($result))
		{
			array_push($obj,(object)array('id'=>$row->news_id,'title'=>$row->title,'time'=>date("Y-m-d",strtotime($row->time))));
		}
		echo json_encode((object)array('no'=>0,'data'=>(object)$obj));
	}
  	else
  	{
	 	echo '{"no":5,"err":"没有更多新闻了哦~"}';
  	}
}
else if($_GET['type']=='show')
{
	$id=intval($_GET['id']);
	$result=mysql_query("select `title`,`news_id`,`time`,`content`,`user_id` from `news` where `news_id`=$id and `defunct`='N'");
	$row=mysql_fetch_object($result);
	if($row)
	echo json_encode((object)array('no'=>0,'title'=>$row->title,'id'=>$row->news_id,'time'=>$row->time,'content'=>$row->content,'user_id'=>$row->user_id));
	else echo json_encode((object)array('no'=>1,'err'=>'错误：不存在的新闻'));
}
?>