<?php
!defined('M_COM') && exit('No Permission');
function cnstr($temparr){
	global $cotypes;
	$vararr = array('caid');
	foreach($cotypes as $coid => $cotype) $cotype['sortable'] && $vararr[] = 'ccid'.$coid;
	$cnstr = '';
	foreach($temparr as $k => $v) if(in_array($k,$vararr) && $v = max(0,intval($v))) $cnstr .= ($cnstr ? '&' : '').$k.'='.$v;
	unset($vararr,$temparr,$cotype);
	return $cnstr;
}
function cn_htmldir($cnstr,$sid=0){//返回 子站目录/顶级首序分类(/本首序分类)/次序分类/三序分类/ 的格式
	global $cnhtmldir,$subsites;
	$dirstr = $sid ? $subsites[$sid]['dirname'].'/' : ($cnhtmldir ? $cnhtmldir.'/' : '');
	if($cnstr){
		parse_str($cnstr,$idsarr);
		$topid = $i = 0;
		foreach($idsarr as $k => $v){
			$k = $k == 'caid' ? 0 : str_replace('ccid','',$k);
			$item = !$k ? read_cache('catalog',$v,'',$sid) : read_cache('coclass',$k,$v);
			if(!$i){
				$items = $k == 'caid' ? read_cache('catalogs','','',$sid) : read_cache('coclasses',$k,$v);
				$topid = cn_upid($v,$items);
				$dirstr .= $items[$topid]['dirname'].'/';
			}
			if($i || $topid != $v) $dirstr .= $item['dirname'].'/';
			$i = 1;
		}
	}
	return $dirstr;
}
function idx_format($sid = 0){
	global $homedefault;
	return ($sid ? cn_htmldir('',$sid) : '').$homedefault;
}
function cn_blank($cnstr,$sid,$addnos=array(),$force=0){//force:强行覆盖第一个文件，为0时为修复链接
	global $enablestatic;
	$suffix = $sid ? ($cnstr ? '&' : '')."sid=$sid" : '';
	if($cnstr){
		if(!$cnode = read_cnode($cnstr,$sid)) return;
		if(!is_array($addnos)) $addnos = array($addnos);
		if(!$addnos) return;
		$statics = empty($cnode['statics']) ? array() : explode(',',$cnode['statics']);
		for($i = 0;$i <= $cnode['addnum'];$i++){
			if(in_array($i,$addnos)){
				if(empty($statics[$i]) ? $enablestatic : ($statics[$i] == 1 ? 0 : 1)){
					$cnfile = M_ROOT.m_parseurl(cn_format($cnstr,$i,$cnode),array('page' => 1));
					if($force || !is_file($cnfile)) @str2file(direct_html("index.php?$cnstr$suffix".($i ? "&&addno=$i" : '')),$cnfile);
				}
			}
		}
	}elseif($sid){
		$cnfile = M_ROOT.m_parseurl(idx_format($sid),array('page' => 1));
		if($force || !is_file($cnfile)) @str2file(direct_html("index.php?$suffix"),$cnfile);
	}
}

function cn_format($cnstr,$addno,&$cnode){//含{$page}的节点文件(相对系统根目录)
	global $cn_urls;
	if(!$cnstr || !$cnode) return '';
	$urlarr = empty($cnode['urls']) ? array() : explode(',',$cnode['urls']);
	$u = empty($urlarr[$addno]) ? (empty($cn_urls[$addno]) ? '{$cndir}/index'.($addno ? $addno : '').'_{$page}.html' : $cn_urls[$addno]) : $urlarr[$addno];
	return m_parseurl($u,array('cndir' => cn_htmldir($cnstr,$cnode['sid'])));
}

function mcn_format($cnstr,$addno){//含{$page}的节点文件(相对系统根目录)
	global $memberdir,$homedefault;
	if(!$cnstr) return $memberdir.'/'.$homedefault;
	$cnode = read_mcnode($cnstr);
	$urlarr = empty($cnode['urls']) ? array() : explode(',',$cnode['urls']);
	return $memberdir.'/'.m_parseurl(empty($urlarr[$addno]) ? '{$cndir}/index'.($addno ? $addno : '').'_{$page}.html' : $urlarr[$addno],array('cndir' => mcn_dir($cnstr),));
}

function cn_pmids($cnstr,$sid=0){//类目阅读权限
	global $cotypes;
	parse_str($cnstr,$idsarr);
	$pmids = array();
	foreach($idsarr as $k => $v){
		$coid = $k == 'caid' ? 0 : str_replace('ccid','',$k);
		$item = !$coid ? read_cache('catalog',$v,'',$sid) : (@$cotypes[$coid]['permission'] ? read_cache('coclass',$coid,$v) : array());
		!empty($item['crpmid']) && $pmids[] = $item['crpmid'];
	}
	return $pmids;
}
function cn_parse($cnstr,$sid=0,$listby=-1){//$listby:-1列最后一个id,0列栏目项，数字列某个类系
	parse_str($cnstr,$idsarr);
	$infos = array();
	$i = 0;
	$num = count($idsarr);
	foreach($idsarr as $k => $v){
		$i ++;
		$coid = $k == 'caid' ? 0 : intval(str_replace('ccid','',$k));
		if($item = $k == 'caid' ? read_cache('catalog',$v,'',$sid) :  read_cache('coclass',$coid,$v)){
			$infos[$k == 'caid' ? 'caid' : "ccid$coid"] = $v;
			$infos[$k == 'caid' ? 'catalog' : 'ccid'.$coid.'title'] = $item['title'];
			if($k == 'caid') $infos['sid'] = $item['sid'];
			if((($listby == -1) && ($i == $num)) || (!$listby && $k == 'caid') || (($listby > 0) && ($listby == $coid))){
				$infos += $item;
			}
		}
	}
	if(!isset($infos['sid'])) $infos['sid'] = $sid;
	return $infos;
}
function m_cnparse($cnstr){//得到初始的资料
	$var = array_map('trim',explode('=',$cnstr));
	if($var[0] == 'mcnid') return array();
	if($var[0] == 'caid'){
		$arr = read_cache('acatalogs');
		$ret = read_cache('catalog',$var[1],0,$arr[$var[1]]['sid']);
	}elseif(in_str('ccid',$var[0])){
		$ret = read_cache('coclass',str_replace('ccid','',$var[0]),$var[1]);
	}elseif(in_str('ugid',$var[0])){
		$ret = read_cache('usergroup',str_replace('ugid','',$var[0]),$var[1]);
	}elseif($var[0] == 'matid') $ret = read_cache('matype',$var[1]);
	if(empty($ret['cname'])) $ret['cname'] = @$ret['title'];
	return $ret;
}

function re_cnode(&$item,&$cnstr,&$cnode){
	global $cms_abs,$sid,$cn_max_addno;
	if($cnode){
		for($i = 0;$i <= $cn_max_addno;$i ++) $item['indexurl'.($i ? $i : '')] = isset($cnode['indexurl'.($i ? $i : '')]) ? $cnode['indexurl'.($i ? $i : '')] : '#'; 
		$item['alias'] = empty($cnode['alias']) ? $item['title'] : $cnode['alias'];
		$item['rss'] = $cms_abs.'rss.php'.(empty($cnstr) ? '' : "?$cnstr").($sid ? ((empty($cnstr) ? '?' : '&')."sid=$sid") : '');
	}else{
		for($i = 0;$i <= $cn_max_addno;$i ++) $item['indexurl'.($i ? $i : '')] = '#'; 
	}
}
?>