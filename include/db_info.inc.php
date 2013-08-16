<?php 
$lifeTime = 24 * 3600 * 365; 
session_set_cookie_params($lifeTime); 
@session_start();
	ini_set("display_errors","Off");
static 	$DB_HOST="localhost";
static 	$DB_NAME="jol";
static 	$DB_USER="jol";
static 	$DB_PASS="jol";
	// connect db 
static 	$OJ_NAME="HUSTOJ";
static 	$OJ_HOME="./";
static 	$OJ_ADMIN="root@localhost";
static 	$OJ_DATA='d:/gzojdata/';
static 	$OJ_BBS="discuss";//"bbs" for phpBB3 bridge or "discuss" for mini-forum
static  $OJ_ONLINE=false;
static  $OJ_LANG="cn";
static  $OJ_SIM=false; 
static  $OJ_DICT=false;
static  $OJ_LANGMASK=4080; //1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 1008 for security reason to mask all other language
static  $OJ_EDITE_AREA=true;//true: syntax highlighting is active
static  $OJ_AUTO_SHARE=true;//true: One can view all AC submit if he/she has ACed it onece.
static  $OJ_CSS="hoj.css";
static  $OJ_SAE=false; //using sina application engine
static  $OJ_VCODE=true;
static  $OJ_APPENDCODE=false;
static  $OJ_MEMCACHE=false;
static  $OJ_MEMSERVER="127.0.0.1";
static  $OJ_MEMPORT=11211;
static  $SAE_STORAGE_ROOT="http://hustoj-web.stor.sinaapp.com/";
static  $OJ_TEMPLATE="bs";
static  $OJ_LOGIN_MOD="hustoj";
static  $OJ_RANK_LOCK_PERCENT=0;
static  $OJ_SHOW_DIFF=false;
static  $OJ_TEST_RUN=false;
static $OJ_OPENID_PWD = '8a367fe87b1e406ea8e94d7d508dcf01';

/* weibo config here */
static  $OJ_WEIBO_AUTH=false;
static  $OJ_WEIBO_AKEY='1124518951';
static  $OJ_WEIBO_ASEC='df709a1253ef8878548920718085e84b';
static  $OJ_WEIBO_CBURL='http://192.168.0.108/JudgeOnline/login_weibo.php';

/* renren config here */
static  $OJ_RR_AUTH=false;
static  $OJ_RR_AKEY='d066ad780742404d85d0955ac05654df';
static  $OJ_RR_ASEC='c4d2988cf5c149fabf8098f32f9b49ed';
static  $OJ_RR_CBURL='http://192.168.0.108/JudgeOnline/login_renren.php';
/* qq config here */
static  $OJ_QQ_AUTH=false;
static  $OJ_QQ_AKEY='1124518951';
static  $OJ_QQ_ASEC='df709a1253ef8878548920718085e84b';
static  $OJ_QQ_CBURL='192.168.0.108';

//log real ip
if(isset($_SERVER['HTTP_X_REAL_IP']))
{
	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];
}


//if(date('H')<5||date('H')>21||isset($_GET['dark'])) $OJ_CSS="dark.css";


{
		//for normal install
		if(!mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)) 
			die('Could not connect: ' . mysql_error());
	}
	// use db
	mysql_query("set names utf8");
  //if(!$OJ_SAE)mysql_set_charset("utf8");
	
	if(!mysql_select_db($DB_NAME))
		die('Can\'t use foo : ' . mysql_error());
	//sychronize php and mysql server
	date_default_timezone_set("PRC");
	mysql_query("SET time_zone ='+8:00'");
	
?>
