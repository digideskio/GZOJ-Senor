<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
date_default_timezone_set('Asia/Shanghai');
$con = mysql_connect('localhost',"root","zhs123");
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
$db="test";
mysql_select_db($db, $con);
if(!mysql_set_charset("utf8",$con))
{
	echo "设定字符集失败;an error occured when program set charset";
}
//lyxss:id=1

//$lyxss_cookie=mysql_fetch_object(mysql_query("select `cookies` from `tb_user` where `id`=1 "))->cookies;	//连云小森森的账号
//echo $lyxss_cookie;
$lyxss_cookie='BDUSS=NmU2hva0ZuU3p1LUctbUJsWk9qa3p-STJoMDUwbjNteHo4cHo5MnMxWTZHLUpSQVFBQUFBJCQAAAAAAAAAAAEAAACRRTIwwazUxtChya3JrQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADqOulE6jrpRRn; BAIDUID=4B72758FA61DBE69A7C233776F601E15:FG=1; BDSVRTM=77; H_PS_PSSID=2600_2586_1433_1788_2249_2543_2361';
$banid=intval($_GET['id']);
$row=mysql_fetch_object(mysql_query("select `username`,`postdata` from `ban_list` where `id`=$banid"));
if(!$row) die('查询错误');
echo "封禁:".$row->username." 一天<br>";
$str=curlFetch('http://tieba.baidu.com/bawu/cm',array("Cookie: $lyxss_cookie"),'http://tieba.baidu.com/p/2134123',$row->postdata);
echo '返回结果：<br><br>'.$str.'<br>';
$arr=json_decode($str,true);
if($arr['error']['retval']===0&&$arr['error']['errmsg']==='') echo "<br>成功！"; else echo "<br>失败，".$arr['error']['errmsg'];
?>

<?php 

function curlFetch($url, $addheader=null, $referer = "", $data = null)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而非直接输出
	curl_setopt($ch, CURLOPT_HEADER, false);   // 不返回header部分
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间
	if (!empty($referer))
	{
		curl_setopt($ch, CURLOPT_REFERER, $referer);   // 设置引用网址
	}
	if (!is_null($addheader))
	{
		curl_setopt($ch,CURLOPT_HTTPHEADER,$addheader);
		//print_r($addheader);
	}
	
	if (is_null($data))
	{
		// GET
	}
	else if (is_string($data))
	{
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		// POST
	}
	else if (is_array($data))
	{
		// POST
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	}
	//set_time_limit(120); // 设置自己服务器超时时间
	$str = curl_exec($ch);
	curl_close($ch);
	return $str;
}
exit;
?>