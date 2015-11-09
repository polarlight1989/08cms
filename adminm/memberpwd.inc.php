<?php
!defined('M_COM') && exit('No Permission');
if(!submitcheck('bmemberpwd')){
	tabheader(lang('memberpwdsetting'),'memberpwd','?action=memberpwd',2,0,1);
	trbasic(lang('membercname'),'',$curuser->info['mname'],'');
	trbasic(lang('oldpwd'),'opassword','','password');
	trbasic(lang('newpwd'),'npassword','','password');
	trbasic(lang('repwd'),'npassword2','','password');
	$submitstr = '';
	$submitstr .= makesubmitstr('opassword',1,0,0,15);
	$submitstr .= makesubmitstr('npassword',1,0,0,15);
	$submitstr .= makesubmitstr('npassword2',1,0,0,15);
	$submitstr .= tr_regcode('login');
	tabfooter('bmemberpwd');
	check_submit_func($submitstr);
}else{
	if(!regcode_pass('login',empty($regcode) ? '' : trim($regcode))) mcmessage('regcodeerror','?action=memberpwd');
	$opassword = trim($opassword);
	$npassword = trim($npassword);
	$npassword2 = trim($npassword2);
	if(md5(md5($opassword)) != $curuser->info['password']) mcmessage('oldpasserror','?action=memberpwd');
	if($npassword != $npassword2) mcmessage('notsamepwd','?action=memberpwd');
	if(!$npassword || strlen($npassword) > 15 || $npassword != addslashes($npassword)){
		mcmessage('memberpwdillegal','?action=memberpwd');
	}
	if($enable_uc){
		include_once M_ROOT.'./include/ucenter/uc.inc.php';
	}
	$npassword = md5(md5($npassword));
	$curuser->updatefield('password',$npassword,'main');
	$curuser->updatedb();
	msetcookie('userauth',authcode("$npassword\t$memberid",'ENCODE'));
//	msetcookie('userauth',authcode("$npassword\t$memberid",'ENCODE'),31536000);
	mcmessage('mempassmodsuc','?action=memberpwd');
}
?>
