<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>添加新闻</title>
</head>
<body leftmargin="30" >

<?php require_once("../include/db_info.inc.php");?>
<?php require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../'>请先登录!</a>";
	exit(1);
}
?>
<?php
include_once("../fckeditor/fckeditor.php") ;
?>
<form method=POST action=news_add.php>

<p align=left>添加新闻</p>
<p align=left>标题:<input type=text name=title size=71></p>

<p align=left>正文:<br>
<?php
$description = new FCKeditor('content') ;
$description->BasePath = '../fckeditor/' ;
$description->Height = 450 ;
$description->Width=800;

$description->Value = '<p></p>' ;
$description->Create() ;
?>
</p>
<?php require_once("../include/set_post_key.php");?>
<input type=submit value=添加 name=submit>
</div></form>
<p>

</body></html>

