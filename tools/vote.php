<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/parse.fun.php';
$action = empty($action) ? 'vote' : $action;
if(empty($fname)){
	if(empty($vid) || !($vote = $db->fetch_one("SELECT * FROM {$tblprefix}votes WHERE vid='$vid' AND checked=1 AND (enddate=0 OR enddate>$timestamp)"))) message('choosevoteitem');
	if($action == 'vote'){
		empty($vopids) && message('choosevoteoption');
		if($vote['enddate'] && $vote['enddate'] < $timestamp) message('invalidvoteitem',M_REFERER);
		if($vote['onlyuser'] && !$memberid) message('nousernooperatepermis',M_REFERER);
		if($vote['norepeat'] || $vote['timelimit']){
			if(empty($m_cookie['voted_'.$vid.'_timelimit'])){
				msetcookie('voted_'.$vid.'_timelimit','1',$vote['norepeat'] ? 365 * 24 * 3600 : $vote['timelimit'] * 60);
			}else message($vote['norepeat'] ? 'norepeatoper' : 'overquick',M_REFERER);
		}
		foreach($vopids as $vopid) $db->query("UPDATE {$tblprefix}voptions SET votenum=votenum+1 WHERE vopid='$vopid'");
		//将总票数写入投票数据库
		$counts = $db->result_one("SELECT SUM(votenum) FROM {$tblprefix}voptions WHERE vid='$vid'");
		$db->query("UPDATE {$tblprefix}votes SET totalnum='$counts' WHERE vid='$vid'");
		message('votesucceed',M_REFERER);
	}elseif($action == 'view'){
		$temparr = array('vid' => $vid);
		mexit(template('vote',$temparr));
	}
}else{
	include_once(M_ROOT.'include/vote.fun.php');
	empty($vopids) && message('choosevoteoption',M_REFERER);
	$fname = empty($fname) ? '' : strip_tags(trim($fname));//字段名称
	$tbl = $type = empty($type) ? 'archives' : strip_tags(trim($type));
	$id = empty($id) ? 0 : max(0,intval($id));//记录id
	if(!($item = field_votes($fname,$type,$id,0)) || !($votes = @unserialize($item[$fname]))) message('choosevoteitem',M_REFERER);
	$arr = array(
		'archives' => array('fields','aid','chid'),
		'members' => array('mfields','mid','mchid'),
		'farchives' => array('ffields','aid','chid'),
		'catalogs' => array('cafields','caid',''),
		'coclass' => array('ccfields','ccid',''),
		'offers' => array('ofields','cid',''),
		'replys' => array('rfields','cid',''),
		'comments' => array('cfields','cid',''),
		'mcfields' => array('mcomments','cid',''),
		'mrfields' => array('mreplys','cid',''),
		);
	$typeid = $arr[$type][2] ? $item[$arr[$type][2]] : '';
	$fields = read_cache($arr[$type][0],$typeid);
	if(!($field = @$fields[$fname]) || $field['datatype'] != 'vote') message('choosevoteitem');
	if($type == 'archives' && !$field['mcommon']){
		$tbl = $type."_$typeid";
	}elseif($type == 'members'){
		$tbl = $type.($field['mcommon'] ? '_sub' : "_$typeid");
	}elseif($type == 'farchives'){
		$tbl = $type."_$typeid";
	}
	if($field['nohtml'] && !$memberid) message('nousernooperatepermis',M_REFERER);
	if($field['mode'] || $field['length']){
		if(empty($m_cookie['voted_'.$type.$id.'_'.$fname.'_timelimit'])){
			msetcookie('voted_'.$type.$id.'_'.$fname.'_timelimit','1',$field['mode'] ? 365 * 24 * 3600 : $field['length'] * 60);
		}else message($field['mode'] ? 'norepeatoper' : 'overquick',M_REFERER);
	}
	$valid0 = false;
	foreach($vopids as $vid => $opids){
		if(!($vote = @$votes[$vid]) || ($vote['enddate'] && $vote['enddate'] < $timestamp)) continue;
		$valid = false;
		foreach($opids as $opid){
			if(isset($vote['options'][$opid])){
				$vote['options'][$opid]['votenum'] = @$vote['options'][$opid]['votenum'] + 1;
				$valid = true;
			}
		}
		if(!empty($valid)){
			$vote['totalnum'] = 0;
			foreach($vote['options'] as $v) $vote['totalnum'] += @$v['votenum'];
			$votes[$vid] = $vote;
			$valid0 = true;

		}
	}
	$valid0 && $db->query("UPDATE {$tblprefix}$tbl SET $fname='".addslashes(serialize($votes))."' WHERE ".$arr[$type][1]."='$id'",'SILENT');
	message('votesucceed',M_REFERER);
}
?>