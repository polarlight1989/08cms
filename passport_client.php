<?php
error_reporting(0);
define('M_COM', TRUE);
define('M_ROOT',dirname(__FILE__).'/');

$timestamp = time();
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
}
require M_ROOT.'base.inc.php';
require M_ROOT.'dynamic/cache/mconfigs.cac.php';
require M_ROOT.'include/mysql.cls.php';
extract($mconfigs_0);
if($pptout_file == '08cms'){
	$verify = md5("$_GET[action]$_GET[auth]$_GET[forward]$pptin_key");
}elseif($pptout_file == 'phpwind'){
	$verify = md5("$_GET[action]$_GET[userdb]$_GET[forward]$pptin_key");
	$_GET['action'] == 'quit' && $_GET['action'] = 'logout';
}else{
	die('No passport interface!');
}
if(empty($enable_pptin) || $_GET['verify'] != $verify){
	empty($_GET['forward']) || header("Location: $_GET[forward]");
	exit();
}
if($_GET['action'] == 'login'){
	$userinfos = $ckinfos = array();
	if($pptout_file == '08cms'){
		parse_str(passport_decrypt($_GET['auth'],$pptin_key),$datas);
		foreach($datas as $k => $v){
			if(in_array($k,array('mname','password','email'))){
				$userinfos[$k] = addslashes($v); 
			}elseif(in_array($k,array('cookietime','time'))){
				$ckinfos[$k] = $v;
			}
		}
	}elseif($pptout_file == 'phpwind'){
		include M_ROOT."include/charset.fun.php";
		$db_hash = $pptin_key;
		parse_str(StrCode($_GET['userdb'], 'DECODE'), $userdb);
		$userinfos['mname']		= addslashes(convert_encoding($pptout_charset,$mcharset,$userdb['username']));
		$userinfos['password']	= md5($userdb['password']);
		$userinfos['email']		= addslashes($userdb['email']);
		$ckinfos['time']		= $userdb['time'];
	}
	$userinfos['mname'] = preg_replace("/(c:\\con\\con$|[%,\*\"\s\t\<\>\&])/i","",$userinfos['mname']);
	if(strlen($userinfos['mname']) > 15){
		$userinfos['mname'] = substr($userinfos['mname'],0,15);
	}
	if(empty($ckinfos['time']) || empty($userinfos['mname']) || empty($userinfos['password'])){
		empty($_GET['forward']) || header("Location: $_GET[forward]");
		exit('member data missing!'); 
	}elseif($timestamp - $ckinfos['time'] > $pptin_expire){
		empty($_GET['forward']) || header("Location: $_GET[forward]");
		exit('member data expired!');
	}
	$db = new cls_mysql;
	$db->connect($dbhost,$dbuser,$dbpw,$dbname,$pconnect,true,$dbcharset);
	if($cmember = $db->fetch_one("SELECT mid,checked FROM {$tblprefix}members WHERE mname='$userinfos[mname]'")){
		$cmember['password'] != $userinfos['password'] && $db->query("UPDATE {$tblprefix}members SET password='$userinfos[password]' WHERE mid=$cmember[mid]");
		msetcookie('msid','',-86400 * 365);
		msetcookie('userauth',authcode("$userinfos[password]\t$cmember[mid]",'ENCODE'));
	}else{//只是写入会员资料记录，当第一次登录时需要激活,将checked设为2。
		$sqlstr = '';
		$userinfos['regip'] = empty($userinfos['regip']) ? onlineip() : $userinfos['regip'];
		$userinfos['regdate'] = empty($userinfos['regdate']) ? $timestamp : $userinfos['regdate'];
		foreach(array('mname','password','email','regip','regdate') as $var){
			$sqlstr .= (empty($sqlstr) ? '' : ',')."$var='$userinfos[$var]'";
		}
		$sqlstr .= ",checked='2'";
		$db->query("INSERT INTO {$tblprefix}members SET $sqlstr");//没有写入模型记录//没有初始化积分
		$userinfos['mid'] = $db->insert_id();
		$db->query("INSERT INTO {$tblprefix}members_sub SET mid='$userinfos[mid]'");
	}
	empty($_GET['forward']) || header("Location: $_GET[forward]");
	exit();

}elseif($_GET['action'] == 'logout'){
	msetcookie('msid','',-86400 * 365);
	msetcookie('userauth','',-86400 * 365);
	empty($_GET['forward']) || header("Location: $_GET[forward]");
	exit;
}

function passport_decrypt($txt, $key) {
	$txt = passport_key(base64_decode($txt), $key);
	$tmp = '';
	for($i = 0;$i < strlen($txt); $i++) {
		$md5 = $txt[$i];
		$tmp .= $txt[++$i] ^ $md5;
	}
	return $tmp;
}
function passport_key($txt, $encrypt_key) {
	$encrypt_key = md5($encrypt_key);
	$ctr = 0;
	$tmp = '';
	for($i = 0; $i < strlen($txt); $i++) {
		$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
		$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
	}
	return $tmp;
}
function onlineip() {
	global $_SERVER;
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineip = preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
	return $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
}
function msetcookie($ckname, $ckvalue, $cklife = 0) {
	global $ckpre, $ckdomain, $ckpath, $timestamp, $_SERVER;
	setcookie($ckpre.$ckname, $ckvalue, $cklife ? $timestamp + $cklife : 0, $ckpath, $ckdomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}
function authcode($string, $operation, $key = '') {
	global $authkey;
	$authorization = md5($authkey);
	$key = md5($key ? $key : $authorization);
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}
}
function StrCode($string,$action='ENCODE'){
	global $db_hash;
	$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$db_hash),8,18);
	$string	= $action == 'ENCODE' ? $string : base64_decode($string);
	$len	= strlen($key);
	$code	= '';
	for($i=0; $i<strlen($string); $i++)
	{
		$k		= $i % $len;
		$code  .= $string[$i] ^ $key[$k];
	}
	$code = $action == 'DECODE' ? $code : base64_encode($code);
	return $code;
}

?>