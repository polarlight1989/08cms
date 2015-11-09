<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;

$inajax = empty($inajax) ? 0 : 1;
$mid = empty($mid) ? 0 : max(0,intval($mid));
if(!$mid) cumessage('choosemember');
if(!($mcommu = read_cache('mcommu',7))) cumessage('choosecommuitem');
if(empty($mcommu['ucadd'])){
	if(!$memberid) cumessage('nousernofavoritepermis');
	if($mid == $memberid) cumessage('cannotfavoritemember');
	
	if(empty($mcommu['available'])) cumessage('favoriatefunclos');
	if(!$curuser->pmbypmids('cuadd',$mcommu['setting']['apmid'])) cumessage('younofavoriatpermis',$forward);
	
	$actuser = new cls_userinfo;
	$actuser->activeuser($mid);
	if(!$actuser->info['mid']) cumessage('choosemember',$forward);
	
	if(!empty($mcommu['setting']['max'])){
		$counts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}mfavorites WHERE fromid='$memberid'");
		if($counts >= $mcommu['setting']['max']) cumessage('favoriteamooverlimit');
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}mfavorites WHERE fromid='$memberid' AND mid='$mid'")){
		cumessage('memalrefavorite');
	}
	$db->query("INSERT INTO {$tblprefix}mfavorites SET 
		mid='$mid',
		mname='".$actuser->info['mname']."',
		fromid='$memberid',
		fromname='".$curuser->info['mname']."',
		createdate='$timestamp'",'SILENT');
	
	cumessage($inajax ? 'succeed' : 'favoritesucceed',$forward);
}else include(M_ROOT.$mcommu['ucadd']);
?>