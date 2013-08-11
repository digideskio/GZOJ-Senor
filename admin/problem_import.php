<?php function writable($path){
	$ret=false;
	$fp=fopen($path."/testifwritable.tst","w");
	$ret=!($fp===false);
	fclose($fp);
	unlink($path."/testifwritable.tst");
	return $ret;
}
require_once("admin-header.php");
if (!(isset($_SESSION['administrator']))){
	echo "<a href='../'>请先登录!</a>";
	exit(1);
}
   $maxfile=min(ini_get("upload_max_filesize"),ini_get("post_max_size"));

?>
导入FPS（FreeProblemset，免费题库）数据 ,请保证你的文件小于 [<?php echo $maxfile?>] <br/>
或者设置最大上传文件大小：upload_max_filesize 和最大HTTP POST请求大小：post_max_size （在 PHP.ini中）<br/>
如果你在上传较大文件[10M+]遇到了困难, 试着增大你在php.ini中的设置项：[memory_limit].<br>
<?php 
    $show_form=true;
   if(!isset($OJ_SAE)||!$OJ_SAE){
	   if(!writable($OJ_DATA)){
		   echo " 你必须将地址  $OJ_DATA 添加到你的php.ini中的 open_basedir 项中,<br>
					或者你必须将你的这个目录的属性设为可读写，且拥有读写权限<br>
					所以，此时你还不能上传题目。<br>"; 
			$show_form=false;
	   }
	   if(!writable("../upload")){
		   echo "没有写入../upload文件夹的权限！请检查<br>";
		   $show_form=false;
	   }
	}	
	if($show_form){
?>
<br>
<form action='problem_import_xml.php' method=post enctype="multipart/form-data"><br />
	<h1>导入问题:</h1><br />
	
	<input type=file name=fps >
	<?php require_once("../include/set_post_key.php");?>
    <input type=submit value='导入'>
</form>
<?php 
  
   	}
   
?>
<br>

免费题库格式（FPS-xml文件）可以在此处下载：<a href=http://code.google.com/p/freeproblemset/downloads/list target=_blank>FPS-Googlecode</a>
<br /><br /><br />