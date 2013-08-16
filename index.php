<?php
	if(in_array($_SERVER['QUERY_STRING'],array("0","1","2")))
	{
		if(intval($_SERVER['QUERY_STRING'])>=0&&intval($_SERVER['QUERY_STRING'])<=2)
		header("Location: changeskin.php?id=".$_SERVER['QUERY_STRING']);
	}
	require_once "./_loadskin.php";
	$skin=GetSkinName();
	include "./oj-$skin/index.php";
?>