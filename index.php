<?php
	if(isset($_SERVER['QUERY_STRING'])&&$_SERVER['QUERY_STRING']!='')
	{
		if(intval($_SERVER['QUERY_STRING'])>=0&&intval($_SERVER['QUERY_STRING'])<=2)
		header("Location: changeskin.php?id=".$_SERVER['QUERY_STRING']);
	}
	require_once "./_loadskin.php";
	$skin=GetSkinName();
	include "./oj-$skin/index.php";
?>