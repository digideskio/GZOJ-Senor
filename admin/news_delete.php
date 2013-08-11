<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!isset($_SESSION['administrator'])){
	echo "<a href='../'>请先登录！</a>";
	exit(1);
}
$id=intval($_GET['id']);
@mysql_query("delete from `news` where `news_id`=$id") or die(mysql_error());
header('Location: ./news_list.php?succdel=1');
?>
