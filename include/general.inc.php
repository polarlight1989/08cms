<?
//error_reporting(2047);
error_reporting(0);
set_magic_quotes_runtime(0);
define('M_COM', TRUE);
define('M_ROOT', substr(dirname(__FILE__), 0, -7));
if(PHP_VERSION < '4.1.0'){
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}
include_once M_ROOT.'./base.inc.php';
include_once M_ROOT.'./include/general.fun.php';

define('ISROBOT',is_robots());
if(defined('NOROBOT') && ISROBOT) exit(header("HTTP/1.1 403 Forbidden"));
define('QUOTES_GPC', get_magic_quotes_gpc());
(isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) && exit('08cms Error');
if(!QUOTES_GPC && $_FILES) $_FILES = maddslashes($_FILES);
foreach(array('_POST','_GET') as $_request){
	foreach($$_request as $k => $v){
		$k{0} != '_' && $$k = maddslashes($v);
	}
}
$m_cookie = array();
$cklen = strlen($ckpre);
foreach($_COOKIE as $k => $v){
	if(substr($k,0,$cklen) == $ckpre) $m_cookie[(substr($k,$cklen))] = QUOTES_GPC ? $v : maddslashes($v);
}
unset($cklen,$_request,$k,$v);

load_cache('mconfigs,subsites');
@extract($mconfigs);
ini_set('date.timezone','ETC/GMT'.(empty($timezone) ? 0 : $timezone));
$timestamp = time();
include_once M_ROOT.'./include/mysql.cls.php';
include_once M_ROOT.'./include/userinfo.cls.php';

$sid = empty($_GET['sid']) ? (empty($_POST['sid']) ? 0 : $_POST['sid']) : $_GET['sid'];
$sid = max(0,intval($sid));
empty($subsites[$sid]) && $sid = 0;
isset($infloat)||$infloat='';
isset($inajax)||$inajax='';
$sid && $templatedir = $subsites[$sid]['templatedir'];
$param_suffix = $sid ? "&sid=$sid" : '';
$infloat && $param_suffix .= '&infloat=1';
!empty($disable_htmldir) && $cnhtmldir = '';

if(defined('WAP_MODE'))empty($z) ? $m_cookie = array() : @list($m_cookie['msid'], $m_cookie['userauth']) = explode('_', $z);

define('M_REFERER',isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
define('M_URI',isset($_SERVER['REQUEST_URI']) ? rawurldecode($_SERVER['REQUEST_URI']) : '');
if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')){
	$onlineip = getenv('HTTP_CLIENT_IP');
}elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown')){
	$onlineip = getenv('HTTP_X_FORWARDED_FOR');
}elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
	$onlineip = getenv('REMOTE_ADDR');
}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')){
	$onlineip = $_SERVER['REMOTE_ADDR'];
}
preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
$onlineip = $onlineipmatches[0] ? $onlineipmatches[0] : '';
unset($onlineipmatches);
if(empty($_GET['use_push'])){
	if($gzipenable && function_exists('ob_gzhandler')){
		ob_start('ob_gzhandler');
	}else{
		$gzipenable = 0;
		ob_start();
	}
}
$dbcharset = !$dbcharset && in_array(strtolower($mcharset),array('gbk','big5','utf-8')) ? str_replace('-', '', $mcharset) : $dbcharset;
$db = new cls_mysql;
$db->connect($dbhost,$dbuser,$dbpw,$dbname,$pconnect,true,$dbcharset);
$authorization = md5($authkey);
load_cache('grouptypes,permissions,repugrades');
sys_cache('nouserinfos');
$curuser = new cls_userinfo;
if(defined('M_ANONYMOUS')){
	$curuser->activeuser(1);
}else{
	$curuser->currentuser();
	$memberid = $curuser->info['mid'];
	if($phpviewerror == 2 || ($phpviewerror == 1 && $curuser->isadmin())){
//		error_reporting(2047);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
	}
}
?>
