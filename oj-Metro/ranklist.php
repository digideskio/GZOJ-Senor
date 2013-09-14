<?php
	require_once('./include/db_info.inc.php');
	
    $scope="";

    $rank = 0;
    if(isset( $_GET ['start'] ))
        $rank = intval ( $_GET ['start'] );

    $page_size=50;

    if ($rank < 0)
        $rank = 0;

    $sql = "SELECT `user_id`,`solved`,`submit` FROM `users` ORDER BY `solved` DESC,submit,reg_time  LIMIT  " . strval ( $rank ) . ",$page_size";

    $result = mysql_query($sql) or die("Error! ".mysql_error());
    if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
        
    $view_rank=Array();
    $i=0;
    for ( $i=0;$i<$rows_cnt;$i++ ) 
	{
        $row=mysql_fetch_array($result);
        $rank ++;

        $view_rank[$i][0]= $rank;
        $view_rank[$i][1]=  "<div class=center><a href='userinfo.php?user=" . $row['user_id'] . "                                                            '>" . $row['user_id'] . "</a>" ."</div>";
        $view_rank[$i][2]=  "<div class=center><a href='status.php?user_id=" . $row['user_id'] .                                                             "&jresult=4'>" . $row['solved'] . "</a>" ."</div>";
        $view_rank[$i][3]=  "<div class=center><a href='status.php?user_id=" . $row['user_id'] .                                                             "'>" . $row['submit'] . "</a>" ."</div>";

        if ($row['submit'] == 0)
            $view_rank[$i][4]= "0.000%";
        else
            $view_rank[$i][4]= sprintf ( "%.03lf%%", 100 * $row['solved'] / $row['submit'] );
    }

	mysql_free_result($result);

    $sql = "SELECT count(1) as `mycount` FROM `users`";

    $result = mysql_query($sql);
    if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;

    $row=mysql_fetch_array($result);
    echo mysql_error ();

    $view_total=$row['mycount'];

	mysql_free_result($result);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>排名</title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">

</head>
<body class="metrouicss">
	<?php require_once("oj-header.php");?>
<div class="page secondary with-sidebar">
	<div class="page-sidebar">
	<ul>
		<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-user"></i>用户</a>
			<ul class="sub-menu light sidebar-dropdown-menu open">
				<?php require_once("include/metro-profile.php") ?>
            </ul>
        </li>
		
		<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-search"></i>搜索</a>
			<ul class="sub-menu light sidebar-dropdown-menu">
				<form action="userinfo.php">
					<div class="input-control text place-right">
						<input type="text" name="user" placeholder="输入用户名称"/>
						<button class="btn-search" type="submit"></button>
					</div>
				</form>
            </ul>
        </li>				
		
	</ul>
	</div>
<div class="page-region">	
<div class="grid">
<div class="row">
	<table class="striped">
		<thead>
		<tr class='toprow'>
			<td class="span1"><b>名次</b>
			<td class="span2"><b>用户</b>
			<td class="span1"><b>正确数</b>
			<td class="span1"><b>提交数</b>
			<td class="span1"><b>AC率</b>
		</tr>
		</thead>
		<tbody>
			<?php 
			$cnt=0;
			foreach($view_rank as $row){
					echo "<tr>";
				foreach($row as $table_cell){
					echo "<td>";
					echo "\t".$table_cell;
					echo "</td>";
				}				
				echo "</tr>";
			}
			?>
		</tbody>		
	</table>
	<?php 
	   echo "<center>";
		for($i = 0; $i <$view_total ; $i += $page_size) {
			echo "<a href='./ranklist.php?start=" . strval ( $i ).($scope?"&scope=$scope":"") . "'>";
			echo strval ( $i + 1 );
			echo "-";
			echo strval ( $i + $page_size );
			echo "</a>&nbsp;";
			if ($i % 250 == 200)
				echo "<br>";
		}
		echo "</center>";
	
	?>
</div>
</div>
</div>
</div>	
	<?php require_once("oj-footer.php");?>
<!--javascript && /body && /html  is in oj-footer.php & -->