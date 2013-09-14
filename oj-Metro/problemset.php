<?php 
	require_once('./include/db_info.inc.php');
    $view_title= "题库";
$first=1000;

$sql="SELECT max(`problem_id`) as upid FROM `problem`";
$page_cnt=100;
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_object($result);
$cnt=intval($row->upid)-$first;
$cnt=$cnt/$page_cnt;

  //remember page
  $page="1";
if (isset($_GET['page'])){
    $page=intval($_GET['page']);
    if(isset($_SESSION['user_id'])){
         $sql="update users set volume=$page where user_id='".$_SESSION['user_id']."'";
         mysql_query($sql);
    }
}else{
    if(isset($_SESSION['user_id'])){
            $sql="select volume from users where user_id='".$_SESSION['user_id']."'";
            $result=@mysql_query($sql);
            $row=mysql_fetch_array($result);
            $page=intval($row[0]);
    }
    if(!is_numeric($page))
        $page='1';
}
  //end of remember page

$pstart=$first+$page_cnt*intval($page)-$page_cnt;
$pend=$pstart+$page_cnt;

$sub_arr=Array();
// submit
if (isset($_SESSION['user_id'])){
$sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
	" group by `problem_id`";
$result=@mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result))
	$sub_arr[$row[0]]=true;
}

$acc_arr=Array();
// ac
if (isset($_SESSION['user_id'])){
$sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'".
	" AND `result`=4".
	" group by `problem_id`";
$result=@mysql_query($sql) or die(mysql_error());
while ($row=mysql_fetch_array($result))
	$acc_arr[$row[0]]=true;
}

if(isset($_GET['search'])&&trim($_GET['search'])!=""){
	$search=mysql_real_escape_string($_GET['search']);
    $filter_sql=" ( title like '%$search%' or source like '%$search%')";
    
}else{
     $filter_sql="  `problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";
}

	$now=strftime("%Y-%m-%d %H:%M",time());
	$sql="SELECT `problem_id`,`title`,`source`,`submit`,`accepted` FROM `problem` ".
	"WHERE `defunct`='N' and $filter_sql AND `problem_id`";

$sql.=" ORDER BY `problem_id`";

$result=mysql_query($sql) or die(mysql_error());

$view_total_page=$cnt+1;

$cnt=0;
$view_problemset=Array();
$i=0;
while ($row=mysql_fetch_object($result)){
	
	$view_problemset[$i]=Array();
	if (isset($sub_arr[$row->problem_id])){
		if (isset($acc_arr[$row->problem_id])) 
			$view_problemset[$i][0]="<div class='icon-checkmark fg-color-green'></div>";
		else 
			$view_problemset[$i][0]= "<div class='icon-cancel-2 fg-color-red'></div>";
	}else{
		$view_problemset[$i][0]= "<div class=none> </div>";
	}
	$view_problemset[$i][1]="<div class='center'>".$row->problem_id."</div>";;
	$view_problemset[$i][2]="<div class='left'><a href='problem.php?id=".$row->problem_id."'>".$row->title."</a></div>";;
	$view_problemset[$i][3]="<div class='center'><a href='status.php?problem_id=".$row->problem_id."&jresult=4'>".$row->accepted."</a></div>";
	$sub=intval($row->accepted*100/$row->submit).'%';
	if ($sub=='0%')$sub=0;
	$view_problemset[$i][4]="<div class='center'><a href='status.php?problem_id=".$row->problem_id."'>".$sub."</a></div>";
		
	$i++;
}
mysql_free_result($result);
$view_total_page=floor($view_total_page);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>题库</title>
	
	<link rel="stylesheet" type="text/css" href="css_metro/modern.css">
	<link rel="stylesheet" type="text/css" href="css_metro/modern-responsive.css">
	<link rel="stylesheet" type="text/css" href="css_metro/site.css">
	<link href="js_metro/google-code-prettify/prettify.css" rel="stylesheet" type="text/css">

</head>
<body class="metrouicss">
	<?php require_once("oj-header.php");?>
<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#problemset").tablesorter(); 
    } 
); 
</script>
<p>&nbsp;</p>

<div class="page secondary with-sidebar">
	<div class="page-sidebar">
	<ul>
		<li class="sticker sticker-color-green dropdown active" data-role="dropdown">
			<a style="text-align:center;font-weight:bold"><i class="icon-user"></i>用户</a>
			<ul class="sub-menu light sidebar-dropdown-menu open">
				<?php require_once("include/metro-profile.php") ?>
            </ul>
        </li>
			
		<li class="sticker sticker-color-pink dropdown active" data-role="dropdown">	
			<a style="text-align:center;font-weight:bold"><i class="icon-search"></i>搜索</a>
				
			<ul class="sub-menu light sidebar-dropdown-menu">
			
			<form class="form-search" action="problem.php">
				<div class="input-control text place-right">
					<input type="text" name="id" placeholder="输入题目的编号"/>
					<button class="btn-search" type="submit"></button>
				</div>
			</form>

			<form class="form-search">
				<div class="input-control text place-right">
					<input type="text" name="search" placeholder="搜索题目的名称"/>
					<button class="btn-search" type="submit"></button>
				</div>
			</form>	
			</ul>
		</li>
		
	</ul>
	</div>
	
	
	
<div class="page-region">
<div class="grid span12">
	
	<div class="row span9">
	<div class="page-control span8" data-role="page-control">
		<span class="menu-pull"></span>
		<div class="menu-pull-bar"></div>
		<ul>
		<?php 
			for ($i=0;$i<$view_total_page;$i++)
			{
				$ii=$i+1;
				if ($ii==$page) echo "<li class='active'><a href='problemset.php?page=".$ii."'>P1".$i."00+</a></li>";
				else echo "<li><a href='problemset.php?page=".$ii."'>P1".$i."00</a></li>";
			}
		?>
		</ul>
				
		<div class="frames">
		<div class='frame active' id='page1'>
			<table class="striped">
						
				<thead>
					<tr>
						<th class="span1">FLAG</th>
						<th class="span1_5">题目编号</th>
						<th class="span5">题目名称</th>
						<th class="span1">AC数</th>
						<th class="span1">AC率</th>
					<tr>
				</thead>
					
				<tbody>
				<?php 
					foreach($view_problemset as $row)
					{
							echo "<tr>";
						foreach($row as $table_cell)
						{
							echo "<td>";
							echo "\t".$table_cell;
							echo "</td>";
						}				
						echo "</tr>";				
					}
				?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
	</div>
	


</div>
</div>
</div>
<?php require_once("oj-footer.php");?>
<!--javascript && /body && /html  is in oj-footer.php & -->
