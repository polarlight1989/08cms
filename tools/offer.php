<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/arcedit.cls.php';
$forward = empty($forward) ? M_REFERER : $forward;
if(empty($action)){
	$inajax = empty($inajax) ? 0 : 1;
	$aid = empty($aid) ? 0 : max(0,intval($aid));
	if(!$aid) cumessage('chooseproduct');
	$cuid = $db->result_one("SELECT c.offer FROM {$tblprefix}archives a LEFT JOIN {$tblprefix}channels c ON c.chid=a.chid WHERE a.aid='$aid'");
	if(!$cuid || !($commu = read_cache('commu',$cuid))) message('setcomitem');
	if(empty($commu['ucadd'])){
		if(!$memberid) cumessage('nousernoofferpermis');
		if($cid = $db->result_one("SELECT cid FROM {$tblprefix}offers WHERE mid='$memberid' AND aid='$aid'")){
			cumessage('offerexist',"adminm.php?action=offers");
		}
		$aedit = new cls_arcedit;
		$aedit->set_aid($aid);
		if($retmsg = $aedit->newoffer()) cumessage($retmsg);
		cumessage($inajax ? 'succeed' : 'offersubmitsucceed',"adminm.php?action=offers");
	}else include(M_ROOT.$commu['ucadd']);
}elseif($action == 'vote'){
	$inajax = empty($inajax) ? 0 : 1;
	$cid = empty($cid) ? 0 : max(0,intval($cid));
	if(!$cid) cumessage('choosevoteobject');
	if(!$row = $db->fetch_one("SELECT * FROM {$tblprefix}offers WHERE cid='$cid'")) cumessage('choosevoteobject',$forward);
	if(!($commu = read_cache('commu',$row['cid']))) cumessage('setcomitem',$forward);
	if(empty($commu['ucvote'])){
		if(!empty($commu['setting']['nouservote']) && !$memberid) cumessage('loginmember',$forward);
		if(empty($commu['setting']['repeatvote'])){
			if(empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_vote_'.$aid.'_'.$cid])){
				msetcookie('08cms_cuid_'.$commu['cuid'].'_vote_'.$aid.'_'.$cid,'1',365 * 24 * 3600);
			}else cumessage('dontnrepeatvote',$forward);
		}
		$option = empty($option) ? 1 : min(5,max(1,intval($option)));
		$db->query("UPDATE {$tblprefix}offers SET votes$option = votes$option + 1 WHERE cid='$cid'",'SILENT');
		cumessage($inajax ? 'succeed' : 'votesucceed',$forward);
	}else include(M_ROOT.$commu['ucvote']);
}

?>