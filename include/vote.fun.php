<?php
!defined('M_COM') && exit('No Permission');
function field_votes($fname,$type,$id,$onlyvote = 1){
	global $db,$tblprefix;
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
	if(!$fname || !$type || !$arr[$type][0] || !$id || !($item = $db->fetch_one("SELECT * FROM {$tblprefix}$type WHERE ".$arr[$type][1]."='$id'",'SILENT')))  return array();
	$typeid = $arr[$type][2] ? $item[$arr[$type][2]] : '';
	$fields = read_cache($arr[$type][0],$typeid);
	if(!($field = @$fields[$fname]) || $field['datatype'] != 'vote') return array();
	
	$needadd = true;
	if($type == 'archives' && !$field['mcommon']){
		$tbl = $type."_$typeid";
	}elseif($type == 'members'){
		$tbl = $type.($field['mcommon'] ? '_sub' : "_$typeid");
	}elseif($type == 'farchives'){
		$tbl = $type."_$typeid";
	}else $needadd = false;
	if($needadd && $r = $db->fetch_one("SELECT * FROM {$tblprefix}$tbl WHERE ".$arr[$type][1]."='$id'",'SILENT')) $item += $r;
	return empty($item[$fname]) || !($votes = @unserialize($item[$fname])) ? array() : ($onlyvote ? $votes : $item);
}
?>