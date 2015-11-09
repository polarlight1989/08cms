<?php
define('WAP_MODE', 1);

include(dirname(dirname(__FILE__)).'/include/general.inc.php');
include('wap.fun.php');
parse_str(un_virtual($_SERVER['QUERY_STRING']),$temparr);

//处理子站id
$nsid = empty($temparr['sid']) ? 0 : max(0,intval($temparr['sid']));
if($nsid && empty($subsites[$nsid])) $nsid = 0;
switch_cache($nsid);
$sid = $nsid;
if_siteclosed($sid);

if(!$action){
	wap_header(wap_lang('wap_login'), '', 0);
	$memberid && message('wap_login_re_ok', 'back');
	echo wap_lang('wap_username')
		.'<br/><input name="username" size="15" emptyok="false"/><br/>'
		.wap_lang('wap_password')
		.'<br/><input name="password" size="15" emptyok="false"/><br/>'
		.'<br/><a href="?action=submit'.$wap_string.'&amp;username=$(username)&amp;password=$(password)&amp;forward='.M_REFERER.'">'.wap_lang('wap_justlogin').'</a><br/>'
		.$link;
	wap_footer();
}elseif($action == 'submit'){
	wap_header(wap_lang('wap_login'), '', 0);
	if(!$username || !$password)message('wap_empty_input', 'back');

	include(M_ROOT.'include/admin.fun.php');
	strlen($username = trim($username)) < 3 && message('wap_member_name_fail', 'back');
	if(!$password || $password != addslashes($password))message('wap_password_fail', 'back');
	$guestexp = '\xA1\xA1|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
	preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|$guestexp/is", $username) && message('wap_member_name_fail', 'back');
	$errtimes = login_safecheck($username);
	$errtimes++ < $maxerrtimes || message('wap_login_error_times');
	$cantimes = $maxerrtimes - $errtimes;
	$md5_password = md5(md5($password));
	$curuser->activeuserbyname($username);
	if($curuser->info['mid'] && ($enable_uc || $curuser->info['password'] == $md5_password)){//是本站会员，检查更新密码
		if($curuser->info['password'] != $md5_password)$curuser->updatefield('password', $md5_password);
		if($curuser->info['checked'] == 1){
			$curuser->updatefield('lastvisit', $timestamp);
			$curuser->updatefield('lastip', $onlineip);
			$curuser->updatedb();
			$memberid = $curuser->info['mid'];
			$z = '_' . rawurlencode(authcode("$md5_password\t" . $curuser->info['mid'], 'ENCODE'));
			login_safecheck($username, 0, 1);
			$forward = empty($forward) ? 'index.php' : $forward;
			message('wap_login_ok', $forward . (strpos($forward, '?') !== false ? '&z=' : '?z=') . $z, 'ret_index');
		}elseif($curuser->info['checked'] == 2){//需要重新激活的会员
			message('wap_out_member_active');
		}else message('wap_nocheck_member', 'back');
	}elseif($enable_uc){//UC帐号需要激活
		message('wap_out_member_active');
	}
	login_safecheck($username, $errtimes);
	$password = preg_replace("/^(.{".round(strlen($password) / 4)."})(.+?)(.{".round(strlen($password) / 6)."})$/s", "\\1***\\3", $password);
	$record = mhtmlspecialchars(
		$timestamp."\t".
		stripslashes($username)."\t".
		$password."\t".
		$onlineip);
	record2file('badlogin',$record);
	message($cantimes ? 'wap_login_failed' : 'wap_login_error_times', 'back', $cantimes);
}elseif($action == 'logout'){
	wap_header(wap_lang('wap_logout'));
	message('wap_logout_ok', empty($forward) ? 'index.php' : $forward);
}
?>