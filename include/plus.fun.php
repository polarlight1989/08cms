<?
//本脚本内容请勿随意修改，否则有可能产生不可预料的后果。
!defined('M_COM') && exit('No Permisson');
function cnsql($coid,$ccids,$pre = ''){
	global $cotypes;
	if(empty($ccids)) return '';
	if(!is_array($ccids)) $ccids = array($ccids);
	$self = $coid && $cotypes[$coid]['self_reg'] ? 1 : 0;
	$fname = $pre.($coid ? "ccid$coid" : 'caid'); 
	if($self){
		$sqlstr = self_sqlstr($coid,$ccids,$pre);
	}else $sqlstr = $fname.' '.multi_str($ccids);
	return $sqlstr;
}
function caccsql($fname,$ids,$smode=0){
	if(!$ids || !$fname) return '';
	if(!is_array($ids)) $ids = array($ids);
	return $fname.' '.multi_str($ids);
}
function select_alter($nmode,$omode,$fname = 'caid',$tbl = 'archives'){
	return false;
}
function emode_alter($nmode,$omode,$fname,$tbl){
	return false;
}
function select_fnew($nmode){
	return 0;
}
function cn_tplname($cnstr,&$cnode,$addno=0){
	$var = defined('WAP_MODE') ? 'wtpls' : 'tpls';
	if($$var = $cnode[$var]) $$var = explode(',',$$var);
	return empty(${$var}[$addno]) ? '' : ${$var}[$addno];
}
function mcn_tplname($cnstr,$addno=0){
	global $db,$tblprefix,$m_index_tpl;
	if(!$cnstr) return @$m_index_tpl;
	$cnode = read_mcnode($cnstr);
	if($tpls = $cnode['tpls']) $tpls = explode(',',$tpls);
	return empty($tpls[$addno]) ? '' : $tpls[$addno];
}
function cnodes_update($cname,$sid){
	global $db,$tblprefix;
	$cnodes = array();
	$query = $db->query("SELECT * FROM {$tblprefix}cnodes WHERE sid=$sid AND inconfig='1'");
	while($row = $db->fetch_array($query)) $cnodes[$row['ename']] = $row;
	cache2file($cnodes,'cnodes','',$sid);
	unset($cnodes,$row);
}
function mcnodes_update($cname){
	global $db,$tblprefix;
	$mcnodes = array();
	$query = $db->query("SELECT * FROM {$tblprefix}mcnodes");
	while($row = $db->fetch_array($query)) $mcnodes[$row['ename']] = $row;
	cache2file($mcnodes,'mcnodes','',0);
	unset($mcnodes,$row);
}
function arc_checkend(&$item){
	return;
}
function cu_checkend(&$item,$mode=''){
	return;
}
function multi_val_arr($val = '',&$field){//HH
	return false;
}
function mapsql($x,$y,$diff = 0,$mode = 1,$fname = ''){
	return '';
}

?>