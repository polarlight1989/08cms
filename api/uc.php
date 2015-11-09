<?php
define('UC_CLIENT_VERSION','1.5.0');
define('UC_CLIENT_RELEASE','20090121');
define('API_DELETEUSER',1);
define('API_RENAMEUSER',0);
define('API_GETTAG',0);
define('API_SYNLOGIN',1);
define('API_SYNLOGOUT',1);
define('API_UPDATEPW',0);
define('API_UPDATEBADWORDS',0);
define('API_UPDATEHOSTS',0);
define('API_UPDATEAPPS',0);
define('API_UPDATECLIENT',0);
define('API_UPDATECREDIT',1);
define('API_GETCREDITSETTINGS',1);
define('API_GETCREDIT',1);
define('API_UPDATECREDITSETTINGS',1);
define('API_RETURN_SUCCEED','1');
define('API_RETURN_FAILED','-1');
define('API_RETURN_FORBIDDEN','-2');
define('M_COM',TRUE);
define('M_ROOT',substr(dirname(__FILE__),0,-3));

error_reporting(0);
set_magic_quotes_runtime(0);
defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
require_once M_ROOT.'./base.inc.php';
require_once M_ROOT.'./dynamic/cache/mconfigs.cac.php';
@extract($mconfigs_0);
require_once M_ROOT.'./include/ucenter/config.inc.php';
if(!$enable_uc) exit(API_RETURN_FAILED);
$_DCACHE = $get = $post = array();
$code = @$_GET['code'];
parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
if(MAGIC_QUOTES_GPC) $get = _stripslashes($get);
$timestamp = time();
$authorization = md5($authkey);
if(empty($get)) exit('Invalid Request');
if($timestamp - $get['time'] > 3600) exit('Authracation has expiried');
$action = $get['action'];
require_once M_ROOT.'./uc_client/lib/xml.class.php';
$post = xml_unserialize(file_get_contents('php://input'));
if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings'))) {
	require_once M_ROOT.'./include/mysql.cls.php';
	$GLOBALS['db'] = new cls_mysql;
	$GLOBALS['db']->connect($dbhost,$dbuser,$dbpw,$dbname,$pconnect,true,$dbcharset);
	$GLOBALS['tablepre'] = $tblprefix;
	unset($dbhost,$dbuser,$dbpw,$dbname,$pconnect);
	$uc_note = new uc_note();
	exit($uc_note->$get['action']($get, $post));
}else exit(API_RETURN_FAILED);

class uc_note {

	var $db = '';
	var $tablepre = '';
	var $appdir = '';

	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once M_ROOT.'./uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->appdir = M_ROOT;
		$this->db = $GLOBALS['db'];
		$this->tablepre = $GLOBALS['tablepre'];
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
		require_once M_ROOT.'./include/ucenter/config.inc.php';
		require_once M_ROOT.'./uc_client/client.php';
		$uids = array_filter(explode(',',$get['ids']));
		$mnamestr = '';
		foreach($uids as $uid){
			$ucresult = uc_get_user($uid,1);
			is_array($ucresult) && $mnamestr .= ($mnamestr ? ',' : '')."'".addslashes($ucresult[1])."'";
		}
		if($mnamestr){
			$this->db->query("DELETE FROM ".$this->tablepre."members WHERE mname IN ($mnamestr)",'UNBUFFERED');
			$this->db->query("DELETE FROM ".$this->tablepre."members_sub WHERE mname IN ($mnamestr)",'UNBUFFERED');
			include_once M_ROOT.'./dynamic/cache/mchannels.cac.php';
			foreach($mchannels_0 as $k => $v) $this->db->query("DELETE FROM ".$this->tablepre."members_$k WHERE mname IN ($mnamestr)",'UNBUFFERED');
		}
		return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		if(!API_RENAMEUSER) return API_RETURN_FORBIDDEN;
		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		if(!API_GETTAG) return API_RETURN_FORBIDDEN;
	}

	function synlogin($get, $post) {
		if(!API_SYNLOGIN) return API_RETURN_FORBIDDEN;
		require_once M_ROOT.'./include/general.fun.php';
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$mname = $get['username'];
		if($cmember = $this->db->fetch_one("SELECT mid,mname,password,email FROM ".$this->tablepre."members WHERE mname='$mname' AND checked=1")){
			msetcookie('userauth',authcode("$cmember[password]\t$cmember[mid]",'ENCODE'),2592000);
		}else mclearcookie();
	}

	function synlogout($get, $post) {
		if(!API_SYNLOGOUT) return API_RETURN_FORBIDDEN;
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		require_once M_ROOT.'./include/general.fun.php';
		mclearcookie();
	}

	function updatepw($get, $post) {
		if(!API_UPDATEPW) return API_RETURN_FORBIDDEN;
		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) return API_RETURN_FORBIDDEN;
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) return API_RETURN_FORBIDDEN;
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		if(!API_UPDATEAPPS) return API_RETURN_FORBIDDEN;
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) return API_RETURN_FORBIDDEN;
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		if(!API_UPDATECREDIT) return API_RETURN_FORBIDDEN;
		$credit = $get['credit'];
		$amount = $get['amount'];
		$uid = $get['uid'];
		$time = $get['time'];
		require_once M_ROOT.'./include/ucenter/config.inc.php';
		require_once M_ROOT.'./uc_client/client.php';
		$ucresult = uc_get_user($uid,1);
		if(!is_array($ucresult)) return API_RETURN_FAILED;
		$mname = $ucresult[1];
		$row = $this->db->fetch_one("SELECT mid,mname FROM ".$this->tablepre."members WHERE mname='$mname'");
		$this->db->query("UPDATE ".$this->tablepre."members SET currency$credit=currency$credit+'$amount' WHERE mid='$row[mid]'");
		include_once M_ROOT.'./include/general.fun.php';
		include_once M_ROOT.'./dynamic/cache/currencys.cac.php';
		$record = mhtmlspecialchars($time."\t".$row['mid']."\t".$row['mname']."\t".$currencys_0[$credit]['cname']."\t".'+'."\t".$amount."\t".'ucenter currency exchange');
		record2file('currencylog',$record);
		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
		if(!API_GETCREDIT) return API_RETURN_FORBIDDEN;
		$uid = intval($get['uid']);
		$credit = intval($get['credit']);
		include_once M_ROOT.'./dynamic/cache/currencys.cac.php';
		if(empty($currencys_0[$credit])) return 0;
		require_once M_ROOT.'./include/ucenter/config.inc.php';
		require_once M_ROOT.'./uc_client/client.php';
		$ucresult = uc_get_user($uid,1);
		if(!is_array($ucresult)) return 0;
		$mname = $ucresult[1];
		return $this->db->result_first("SELECT currency$credit FROM ".$this->tablepre."members WHERE mname='$mname'");
	}

	function getcreditsettings($get, $post){
		if(!API_GETCREDITSETTINGS) return API_RETURN_FORBIDDEN;
		include_once M_ROOT.'./dynamic/cache/currencys.cac.php';
		$credits = array();
		foreach($currencys_0 as $k => $v)  $credits[$k] = array(strip_tags($v['cname']),$v['unit']);
		return $this->_serialize($credits);
	}

	function updatecreditsettings($get, $post) {
		if(!API_UPDATECREDITSETTINGS) return API_RETURN_FORBIDDEN;
		$credit = $get['credit'];
		$outextcredits = array();
		if($credit) {
			foreach($credit as $appid => $credititems) {
				if($appid == UC_APPID) {
					foreach($credititems as $value) {
						$outextcredits[] = array(
							'appiddesc' => $value['appiddesc'],
							'creditdesc' => $value['creditdesc'],
							'creditsrc' => $value['creditsrc'],
							'title' => $value['title'],
							'unit' => $value['unit'],
							'ratiosrc' => $value['ratiosrc'],
							'ratiodesc' => $value['ratiodesc'],
							'ratio' => $value['ratio']
						);
					}
				}
			}
		}
		$this->db->query("REPLACE INTO ".$this->tablepre."mconfigs (varname,value,cftype) VALUES ('outextcredits','".addslashes(serialize($outextcredits))."','uc');", 'UNBUFFERED');
		include_once M_ROOT.'./include/general.fun.php';
		include_once M_ROOT.'./include/cache.fun.php';
		updatecache('mconfigs');
		return API_RETURN_SUCCEED;
	}
}

function _setcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	setcookie(($prefix ? $cookiepre : '').$var, $value,
		$life ? $timestamp + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
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
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}