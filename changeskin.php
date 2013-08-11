<?php
if(isset($_GET['id']))
{
	require_once "./_loadskin.php";
	SetSkin(intval($_GET['id']));
	header('Location: ./');
	exit;
}
	require_once "./_loadskin.php";
	$skin=GetSkinName();
	include "./oj-$skin/changeskin.php";
?>