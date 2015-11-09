<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;

$inajax = empty($inajax) ? 0 : 1;
$mid = empty($mid) ? 0 : max(0,intval($mid));
if(!$mid) cumessage('choosemember');
if(!($mcommu = read_cache('mcommu',2))) cumessage('choosecommuitem');
if(empty($mcommu['ucadd'])){
	if(!$memberid) cumessage('nousernoaddfripermis');
	if($mid == $memberid) cumessage('cannotaddyourself');
	
	if(empty($mcommu['available'])) cumessage('favoriatefunclos');
	if(!$curuser->pmbypmids('cuadd',$mcommu['setting']['apmid'])) cumessage('nousernoaddfripermis',$forward);
	
	$actuser = new cls_userinfo;
	$actuser->activeuser($mid);
	if(!$actuser->info['mid']) cumessage('choosemember');
	
	if(!empty($mcommu['setting']['max'])){
		$counts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}mfriends WHERE fromid='$memberid'");
		if($counts >= $mcommu['setting']['max']) cumessage('friamountoverlim',$forward);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}mfriends WHERE fromid='$memberid' AND mid='$mid'")){
		cumessage('memberalreadyadd',$forward);
	}
	
	$db->query("INSERT INTO {$tblprefix}mfriends SET 
		mid='$mid',
		mname='".$actuser->info['mname']."',
		fromid='$memberid',
		fromname='".$curuser->info['mname']."',
		checked='".($mcommu['setting']['autocheck'] ? 1 : 0)."',
		createdate='$timestamp'",'SILENT');
	
	cumessage($inajax ? 'succeed' : 'friendaddsucce',$forward);
}else include(M_ROOT.$mcommu['ucadd']);
?>