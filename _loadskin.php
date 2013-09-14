<?php
	//Written By: Senor - zhs (张森)
	require_once "./include/db_info.inc.php";
	$SKINSTR=array('Senor','Simple','Metro');
	$SKINCODE=array();
	$SKINI=0;
	foreach($SKINSTR as $SKINSTREACH)
	{
		$SKINCODE[$SKINSTREACH]=$SKINI++;
	}
	
	/////////////////////////////////////////////
	
	function GetSkinName()
	{
		global $SKINSTR;
		global $SKINCODE;
		$skin=0;
		$ret='';
		if(isset($_COOKIE['SkinID']))
		{
			$skin=$_COOKIE['SkinID'];
		}
		if(!($skin>=0&&$skin<=2)) $skin=0;
		if(!isset($_SESSION['user_id']))
		{
			return $SKINSTR[$skin];
		}
		$res_skin=mysql_query("select `skin` from `users` where `user_id`='".$_SESSION['user_id']."'");
		$row_skin=mysql_fetch_array($res_skin);
		$skin=($row_skin['skin']>=0&&$row_skin['skin']<=2)?$row_skin['skin']:$skin;
		if(!($row_skin['skin']>=0&&$row_skin['skin']<=2)) @mysql_query("update `users` set `skin`=".$skin." where `user_id`='".$_SESSION['user_id']."'");
		return $SKINSTR[$skin];
	}
	function SetSkin($set_skin_id)
	{
		global $SKINSTR;
		global $SKINCODE;
		$skin=$SKINSTR[$set_skin_id];
		if(!($set_skin_id>=0&&$set_skin_id<=2)) {$skin='Senor';$set_skin_id=0;}
		setcookie("SkinID",$set_skin_id,time()+3600*24*365);
		if(isset($_SESSION['user_id'])) mysql_query("update `users` set `skin`='$set_skin_id.' where `user_id`='".$_SESSION['user_id']."'");
		//更新数据库
	}
?>