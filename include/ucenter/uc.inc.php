<?php
require_once M_ROOT.'./include/ucenter/config.inc.php';
require_once M_ROOT.'./uc_client/client.php';
if($action == 'login'){
	$ret = uc_user_login($username,$password);
	list($uid,$username,,$email) = maddslashes($ret);
	if($uid < 0){
		login_safecheck($username, $errtimes);
		$password = preg_replace("/^(.{".round(strlen($password) / 4)."})(.+?)(.{".round(strlen($password) / 6)."})$/s", "\\1***\\3", $password);
		$record = mhtmlspecialchars($timestamp."\t".stripslashes($username)."\t".$password."\t".$onlineip);
		record2file('badlogin',$record);
#		$msgfunc(lang($uid == -1 ? 'memcnameerror' : 'passerror'),axaction(1,$forward));
		message($cantimes ? 'loginfailed' : 'mloginerrtimes', axaction(1, $forward), $cantimes);
	}
	hidden(uc_user_synlogin($uid));

}elseif($action == 'memactive'){
	$ret = uc_user_login($username,$password);
	list($uid,$username,,$email) = maddslashes($ret);
	if($uid < 0){
		$password = preg_replace("/^(.{".round(strlen($password) / 4)."})(.+?)(.{".round(strlen($password) / 6)."})$/s", "\\1***\\3", $password);
		$record = mhtmlspecialchars($timestamp."\t".stripslashes($username)."\t".$password."\t".$onlineip);
		record2file('badlogin',$record);
		mcmessage(lang($uid == -1 ? 'memcnameerror' : 'passerror'),$forward);
	}
	hidden(uc_user_synlogin($uid));

}elseif($action == 'logout'){
	hidden(uc_user_synlogout());
}elseif($action == 'memberpwd'){
	$ucresult = uc_user_edit($curuser->info['mname'],$opassword,$npassword,'',0);
	if($ucresult == -1){
		mcmessage(lang('oldpasserr'),'adminm.php?action=memberpwd');
	}elseif($ucresult != 1){
		mcmessage(lang('mempassmodfai'),'adminm.php?action=memberpwd');
	}

}elseif($action == 'getpwd'){
	$ucresult = uc_user_edit($cmember['mname'],$npassword,$npassword,'',1);
	if($ucresult != 1){
		mcmessage(lang('mempassmodfai'));
	}
}
function hidden($html){
	echo "<div style=\"display:none\">$html</div>";
}
?>