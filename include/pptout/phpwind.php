<?php 
!defined('M_COM') && exit('No Permisson');
$userdb = array();
if(strpos($pptout_url, ',') !== FALSE){
	$clienturl = explode(',', $pptout_url);
	$jumpurl = array_shift($clienturl);
	$userdb['url'] = implode(',', $clienturl);
}else $jumpurl = $pptout_url;
include_once M_ROOT."./include/charset.fun.php";
$userdb['username']	= convert_encoding($mcharset,$pptout_charset,!empty($username) ? $username : $cmember['mname']);
$userdb['password']	= !empty($password) ? md5($password) : $cmember['password'];
$userdb['email']	= !empty($email) ? $email : $cmember['email'];
$userdb['time']		= $timestamp;

$userdb_encode = '';
foreach($userdb as $key=>$val){
	$userdb_encode .= $userdb_encode ? "&$key=$val" : "$key=$val";
}
$db_hash = $pptout_key;
$userdb_encode = str_replace('=', '', StrCode($userdb_encode));

if(substr($jumpurl, -1, 1) != '/') $jumpurl .= '/';
if($action=='login'){
	$verify = md5("login$userdb_encode$forward$pptout_key");
	$url = $jumpurl."passport_client.php?action=login&userdb=".rawurlencode($userdb_encode)."&forward=".rawurlencode($forward)."&verify=".rawurlencode($verify);
}elseif($action=='logout'){
	$verify = md5("quit$userdb_encode$forward$pptout_key");
    $url = $jumpurl."passport_client.php?action=quit&userdb=".rawurlencode($userdb_encode)."&forward=".rawurlencode($forward)."&verify=".rawurlencode($verify);
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
