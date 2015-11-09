<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'include/cheader.inc.php';
$inajax ? aheader() : _header(lang('membergetpwd'),'curbox');
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = 'forward='.rawurlencode($forward);
empty($action) && $action ='';
if($action == 'getpwd' && !empty($mid) && !empty($id)){
	$cmember = $db->fetch_one("SELECT m.mid,m.mname,m.email,s.confirmstr FROM {$tblprefix}members m,{$tblprefix}members_sub s WHERE m.mid='$mid' AND s.mid=m.mid");
	if(!$cmember || !$cmember['confirmstr']) mcmessage('invalidoperate');
	list($dateline,$deal,$confirmid) = explode("\t",$cmember['confirmstr']);
	if($dateline < $timestamp - 86400 * 3 || $deal != 1 || $confirmid != $id){
		mcmessage('invalidoperate');
	}
	if(!submitcheck('bgetpwd')){
		tabheader(lang('memberpwdsetting'),'getpwd',"?action=getpwd&mid=$mid&id=$id",2,0,1);
		trbasic(lang('membercname'),'',$cmember['mname'],'');
		trbasic(lang('inputnewpwd'),'npassword','','password');
		trbasic(lang('renewpwd'),'npassword2','','password');
		$submitstr = '';
		$submitstr .= makesubmitstr('npassword',1,0,3,15);
		$submitstr .= makesubmitstr('npassword2',1,0,3,15);
		$submitstr .= tr_regcode('register');
		tabfooter('bgetpwd');
		check_submit_func($submitstr);
	}else{
		if(!regcode_pass('register',empty($regcode) ? '' : trim($regcode))) mcmessage('safecodeerr');
		$npassword = trim($npassword);
		$npassword2 = trim($npassword2);
		if($npassword != $npassword2) mcmessage('notsamepwd');
		if(!$npassword || strlen($npassword) > 15 || $npassword != addslashes($npassword)){
			mcmessage('memberpwdillegal');
		}
		if($enable_uc){
			include_once M_ROOT.'./include/ucenter/uc.inc.php';
		}
		$npassword = md5(md5($npassword));
		$db->query("UPDATE {$tblprefix}members SET password='$npassword' WHERE mid='$mid'");
		$db->query("UPDATE {$tblprefix}members_sub SET confirmstr='' WHERE mid='$mid'");
		mcmessage('refindpwdsucceed');
	}
}else if(!submitcheck('blostpwd')){
	tabheader(lang('membergetpwd'),'lostpwd',"?$forwardstr",2,0,1);
	trbasic(lang('membercname'),'mname');
	trbasic(lang('memberemail'),'email');
	$submitstr = '';
	$submitstr .= makesubmitstr('mname',1,0,0,15);
	$submitstr .= makesubmitstr('email',1,'email',0,80);
	$submitstr .= tr_regcode('register');
	tabfooter('blostpwd');
	check_submit_func($submitstr);
}else{
	if(!regcode_pass('register',empty($regcode) ? '' : trim($regcode))) mcmessage('safecodeerr');
	$mname = trim($mname);
	$email = trim($email);
	if(strlen($mname) < 3 || strlen($mname) > 15) mcmessage('membernamelenillegal');
	$guestexp = '\xA1\xA1|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
	if(preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|$guestexp/is",$mname)){
		mcmessage('membercnameillegal');
	}
	if(!$email || !isemail($email)) mcmessage('emailillegal');
	$cmember = $db->fetch_one("SELECT mid,mname,email FROM {$tblprefix}members WHERE mname='$mname' AND email='$email'");
	if(!$cmember) mcmessage('nomemberemail');
	$actuser = new cls_userinfo;
	$actuser->activeuser($cmember['mid']);
	if($actuser->isadmin()) mcmessage('mastercannotuse');
	unset($actuser);
	$confirmid = random(6);
	$confirmstr = "$timestamp\t1\t$confirmid";
	$db->query("UPDATE {$tblprefix}members_sub SET confirmstr='$confirmstr' WHERE mid='$cmember[mid]'");
	mailto("$mname <$email>",'member_getpwd_subject','member_getpwd_content',array('mid' => $cmember['mid'],'mname' => $mname,'url' => "{$cms_abs}tools/lostpwd.php?action=getpwd&mid=$cmember[mid]&id=$confirmid",'onlineip' => $onlineip));
	mcmessage('lostpwd_send',$forward);

}
?>