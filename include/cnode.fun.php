<?php
!defined('M_COM') && exit('No Permission');
function save_cnode($cnstr,$sid,$cncid,$tpls =''){
	global $timestamp,$db,$tblprefix,$cn_max_addno;
	parse_str($cnstr,$idsarr);
	$cnconfigs = reload_cache('cnconfigs','','',$sid);
	if(!($cnconfig = @$cnconfigs[$cncid])) return;
	$cnode = array('caid' => 0,'cnlevel' => count($idsarr),'mainline' => 0);
	$i = 0;
	foreach($idsarr as $k => $v){
		!$i && $cnode['mainline'] = $k == 'caid' ? 0 : str_replace('ccid','',$k);
		$k == 'caid' && $cnode['caid'] = $v;
		$i ++;
	}
	unset($idsarr);
	if($cnid = $db->result_one("SELECT cnid FROM {$tblprefix}cnodes WHERE sid=$sid AND ename='$cnstr'")){
		$sqlstr = '';
		foreach(array('tpls','wtpls','urls','statics','periods',) as $var) $cnconfig[$var.'mode'] == 2 && $sqlstr .= ','.$var."='".$cnconfig[$var]."'";
		$db->query("UPDATE {$tblprefix}cnodes SET cncids = CONCAT(cncids,'|$cncid|') $sqlstr WHERE cnid='$cnid' AND sid='$sid'");
		return;
	}
	$needstatics = '';
	for($i = 0;$i <= $cn_max_addno;$i ++) $needstatics = $timestamp.',';
	$sqlstr = '';
	$tpls && $sqlstr .= ",tpls='$tpls'";
	$vararr = array('wtpls','urls','statics','periods',);
	!$tpls && $vararr[] = 'tpls';
	foreach($vararr as $var) $cnconfig[$var.'mode'] && $sqlstr .= ','.$var."='".$cnconfig[$var]."'";
	$db->query("INSERT INTO {$tblprefix}cnodes SET 
		ename='$cnstr', 
		sid='$sid', 
		inconfig='1',
		cncids='|$cncid|',
		mainline='$cnode[mainline]',
		caid='$cnode[caid]',
		cnlevel='$cnode[cnlevel]',
		needstatics='$needstatics' 
		$sqlstr
		");
	return;
}

function cnodesfromcnc(&$cnconfig,$sid = 0){
	global $db,$tblprefix;
	$cncid = $cnconfig['cncid'];
	$idsarr = cfgs2ids($cnconfig['configs'],$sid);
	$cnodes1 = array();
	$i = 0;
	foreach($idsarr as $k =>$ids){
		if(!$i && empty($ids)) return $cnodes1;
		if(empty($ids)) unset($idsarr[$k]);
		$i ++;
	}
	$i = 0;
	$j = count($idsarr) - 1;
	foreach($idsarr as $k =>$ids){
		$kvar = !$k ? 'caid' : 'ccid'.$k;
		if(!$i){
			foreach($ids as $id){
				$k2 = $kvar.'='.$id;
				save_cnode($k2,$sid,$cncid);
				$cnodes1[$k2] = '';
			}
		}else{
			foreach($cnodes1 as $k1 => $v1){
				foreach($ids as $id){
					$k2 = $k1.'&'.$kvar.'='.$id;
					save_cnode($k2,$sid,$cncid);
					if($i != $j) $cnodes1[$k2] = '';
				}
			}
		}
		$i ++;
	}
	unset($idsarr,$ids);
	return;
}
function cfgs2ids($configs,$sid){
	global $cotypes,$catalogs;
	if(!$configs) return array();
	$rets = array();
	foreach($configs as $k => $v){
		$sarr = $k ? read_cache('coclasses',$k) : read_cache('catalogs','','',$sid);
		$ret = array();
		if(empty($v['mode'])){
			foreach($sarr as $x => $y) $ret[] = $x;
		}elseif($v['mode'] > 0){
			foreach($sarr as $x => $y) if($y['level'] == $v['mode'] - 1) $ret[] = $x;
		}elseif($v['mode'] == -1){
			if($ids = array_filter(explode(',',@$v['ids']))){
				if(!empty($v['son'])){
					$sonids = array();
					foreach($ids as $id) $sonids = son_ids($sarr,$id,$sonids);
					$ids = array_unique(array_merge($ids,$sonids));
				}
				foreach($sarr as $x => $y) in_array($x,$ids) && $ret[] = $x;
			}
		}
		if($noids = array_filter(explode(',',@$v['noids']))){
			if(!empty($v['noson'])){
				foreach($noids as $id) $sonids = son_ids($sarr,$id,$sonids);
				$noids = array_unique(array_merge($noids,$sonids));
			}
			foreach($ret as $x => $y) if(in_array($y,$noids)) unset($ret[$x]);
		}
		$rets[$k] = $ret;
	}
	return $rets;
}

function cnode_cname($cnstr){
	global $sid;
	parse_str($cnstr,$idsarr);
	$ret = '';
	foreach($idsarr as $k => $v){
		$item = $k == 'caid' ? read_cache('catalog',$v,'',$sid) : read_cache('coclass',str_replace('ccid','',$k),$v);
		$ret .= ($ret ? '=>' : '').$item['title'];
	}
	unset($item,$idsarr);
	return $ret;
}
function alter_cnode(&$cnode,$arr,$mode='tpls'){//用来设置多值字段的值
	$oarr = explode(',',$cnode[$mode]);
	$narr = array();
	for($i = 0;$i <= $cnode['addnum'];$i ++) $narr[$i] = isset($arr[$i]) ? $arr[$i] : (isset($oarr[$i]) ? $oarr[$i] : '');
	$cnode[$mode] = implode(',',$narr);
}
function fetch_mlclass($coid,$config){
	global $catalogs,$cotypes;
	$modearr = array(0 => lang('allcoclass'),1 => lang('all_topic_catas'),2 => lang('all_1_catas'),3 => lang('all_2_catas'),4 => lang('all_3_catas'),-1 => lang('handpoint'));
	if($config['mode'] >= 0){
		return $modearr[$config['mode']];
	}else{
		$ret = '';
		$arr = !$coid ? $catalogs : read_cache('coclasses',$coid);
		$i = 0;
		$ids = array_filter(explode(',',$config['ids']));
		foreach($ids as $k){
			if($i >= 10) break; 
			if(!empty($arr[$k]['title'])){
				$ret .= ','.$arr[$k]['title'];
				$i ++;
			}
		}
		return cutstr(substr($ret,1),40,'..');
	}
}
function modify_cnconfig(&$cncfg,$coid = 0,$ccids = array(),$mode = 0){
	global $db,$tblprefix;
	if(empty($cncfg)) return false;
	$configs = $cncfg['configs'];
	if(($cfg = @$configs[$coid]) && (@$cfg['mode'] == -1)){
		$ids = empty($cfg['ids']) ? array() : explode(',',$cfg['ids']);
		$ids = !$mode ? $ccids : ($mode == 1 ? array_filter(array_merge($ids,$ccids)) : array_diff($ids,$ccids));
		$configs[$coid]['ids'] = !$ids ? '' : implode(',',$ids);
		$configs = addslashes(serialize($configs));
		$db->query("UPDATE {$tblprefix}cnconfigs SET configs='$configs' WHERE cncid='$cncfg[cncid]' AND sid='$cncfg[sid]'",'SILENT');
		return true;
	}
	return false;
}
function relate_cncid($coid,$ccid,$level = 0,$sid = 0,$mode = 0){//查找是否有某类系的及与变动id有关的单层结构
	if(!$ccid) return false;
	$cncfgs = read_cache('cnconfigs','','',$sid);
	$level ++;
	$cncid = 0;
	foreach($cncfgs as $k => $v){
		if(!empty($v['configs'][$coid]) && count($v['configs']) == 1 && in_array($v['configs'][$coid]['mode'],array(-1,0,$level))){
			$cncid = $k;
			if($v['configs'][$coid]['mode'] == -1) modify_cnconfig($v,$coid,array($ccid),$mode ? 1 : 2);
			break;
		}
	}
	return $cncid;
}
function update_cnconfigs($coid,$mode=0){
	global $db,$tblprefix,$subsites;
	if(!$coid) return;
	$sids = array_merge(array(0),array_keys($subsites));
	if(!$mode){//删除类系
		$db->query("DELETE FROM {$tblprefix}cnconfigs WHERE mainline='$coid'");
		$query = $db->query("SELECT * FROM {$tblprefix}cnconfigs WHERE level>1");
		while($r = $db->fetch_array($query)){
			$r['configs'] = empty($r['configs']) ? array() : unserialize($r['configs']);
			if(isset($r['configs'][$coid])){
				unset($r['configs'][$coid]);
				$r['level'] --;
				$r['configs'] = addslashes(serialize($r['configs']));
				$db->query("UPDATE {$tblprefix}cnconfigs SET configs='$r[configs]',level='$r[level]' WHERE cncid='$r[cncid]'");
			}
		}
	}else{//添加类系
		$cname = $db->result_one("SELECT cname FROM {$tblprefix}cotypes WHERE coid='$coid'");
		$configs = addslashes(serialize(array($coid => array('mode' => '-1','ids' => '','son' => '0','noids' => '','noson' => '0',))));
		foreach($sids as $k) $db->query("INSERT INTO {$tblprefix}cnconfigs SET cname='$cname',configs='$configs',level='1',mainline='$coid',sid='$k'");
	}
	foreach($sids as $k) updatecache('cnconfigs','',$k);	
}
?>