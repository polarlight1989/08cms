<?
!defined('M_COM') && exit('No Permisson');
function load_cache($cacstr='',$sid=0){
	global $_08CACHE, $templatedir;
	$ret = '';
	if(empty($cacstr))return $ret;
	$arr = array_filter(explode(',',$cacstr));
	foreach($arr as $cac){
		$cac = trim($cac);
		$key = "{$cac}_$sid";
		global $$cac;
		if(!isset($_08CACHE['read'][$key])){
			@include cache_dir($cac,$sid).'/'.$cac.'.cac.php';
			$_08CACHE['read'][$key] = empty($$key) ? array() : $$key;
		}
		$$cac = $_08CACHE['read'][$key];
	}
	return $ret;
}
function cache_merge(&$sources,$cactype,$sid=0){
	if(!$sid) return;
	global $subsites;
	load_cache('subsites');
	if($cactype == 'channels'){
		foreach($sources as $k => $v) $sources[$k]['available'] = empty($subsites[$sid]['channels'][$k]['available']) ? 0 : 1;
	}elseif($cactype == 'channel'){
		$sources['available'] = empty($subsites[$sid]['channels'][$sources['chid']]['available']) ? 0 : 1;
		$arr = array('arctpls','warctpls','pretpl','srhtpl','addtpl',);
		foreach($arr as $k) $sources[$k] = empty($subsites[$sid]['channels'][$sources['chid']][$k]) ? '' : $subsites[$sid]['channels'][$sources['chid']][$k];
		unset($arr);
	}elseif($cactype == 'commus'){
		foreach($sources as $k => $v) $sources[$k]['available'] = empty($subsites[$sid]['commus'][$k]['available']) ? 0 : 1;
	}elseif($cactype == 'commu'){
		$sources['available'] = empty($subsites[$sid]['commus'][$sources['cuid']]['available']) ? 0 : 1;
		$arr = array('addtpl',);
		foreach($arr as $k) $sources[$k] = empty($subsites[$sid]['commus'][$sources['cuid']][$k]) ? '' : $subsites[$sid]['commus'][$sources['cuid']][$k];
	}elseif($cactype == 'btags'){
		global $cms_abs,$timestamp,$wap_suffix;
		$sources['tplurl'] = $cms_abs."template/".$subsites[$sid]['templatedir']."/";
		$sources['siteurl'] = $cms_abs.(defined('WAP_MODE') ? 'wap/' : '')."index.php?sid=$sid".(defined('WAP_MODE') ? $wap_suffix : '');//静态与动态之分
		$sources['sid'] = $sid;
		foreach(array('cmslogo','cmstitle','cmskeyword','cmsdescription','hometpl','w_index_tpl') as $var){
			$sources[$var] = @$subsites[$sid][$var];
		}
	}
}
function switch_cache($nsid = 0){
	global $channels,$catalogs,$cnodes,$commus,$mtpls,$sptpls,$btags,$utags,$ctags,$ptags,$rtags,$sid,$subsites,$templatedir;
	if($nsid == $sid) return;
	load_cache('catalogs,cnodes,mtpls,sptpls,utags,ctags,ptags,rtags',$nsid);
	foreach(array('channels','btags','commus',) as $var) cache_merge($$var,$var,$nsid);
	$nsid && $templatedir = $subsites[$nsid]['templatedir'];
}

function cache2file($carr,$cname,$ctype='',$sid=0){//$ctype细分缓存需要指定
	if(!is_array($carr) || empty($cname)) return;
	$cacstr = var_export($carr,TRUE);
	$cacdir = cache_dir($ctype,$sid);
	mmkdir($cacdir);
	if(@$fp = fopen($cacdir.'/'.$cname.'.cac.php','wb')){
		$cname .= '_'.$sid;
		fwrite($fp,"<?php\n\$$cname = $cacstr ;\n?>");
		fclose($fp);
	}
}
function reload_cache($ctype,$m='',$n='',$sid=0){//强制重载缓存
	$cacdir = cache_dir($ctype,$sid);
	$cname = cache_name($ctype,$m,$n);
	if(@include $cacdir.'/'.$cname.'.cac.php'){
		$$cname = ${$cname.'_'.$sid};
	}else $$cname = array();
	return $$cname;
}
function cache_name($ctype='',$m='',$n=''){
	if($ctype == 'cnode'){
		$cname = str_replace('=','_',str_replace('&','__',$m));
	}elseif(in_array($ctype,array('usergroup','coclass','field','ffield','mfield','mafield'))){
		$cname = $ctype.'_'.$m.'_'.$n;
	}else{
		$m = str_replace('.','',$m);
		$cname = $ctype.$m;
	}
	return $cname;
}
function read_cache($ctype='',$m='',$n='',$sid=0){//H
	global $_08CACHE;
	$key = $m || $n ? "$ctype-$m-$n-$sid" : "{$ctype}_$sid";
	if(!isset($_08CACHE['read'][$key])){
		if(!$m && !$n){
			@include cache_dir($ctype,$sid).'/'.$ctype.'.cac.php';
			$_08CACHE['read'][$key] = empty($$key) ? array() : $$key;
		}elseif(in_array($ctype,array('usergroups','coclasses','ucoclasses','fields','ffields','mfields','mafields','rtag','ptag','ctag','utag',))){
			$cname = cache_name($ctype,$m,$n);
			$cns = "{$cname}_$sid";
			@include cache_dir($ctype,$sid).'/'.$cname.'.cac.php';
			$_08CACHE['read'][$key] = empty($$cns) ? array() : $$cns;
		}elseif(in_array($ctype,array('usergroup','coclass','ucoclass','field','ffield','mfield','mafield'))){
			$arr = read_cache($ctype.(in_array($ctype,array('coclass','ucoclass',)) ? 'es' : 's'),$m,'',$sid);
			$_08CACHE['read'][$key] = empty($arr[$n]) ? array() : $arr[$n];
		}elseif(in_array($ctype,array('channel','fcatalog','player','gmodel','gmission','matype','catalog','commu','mcommu','aurl','inurl','murl','inmurl',))){
			$arr = read_cache($ctype.'s','','',$sid);
			$_08CACHE['read'][$key] = empty($arr[$m]) ? array() : $arr[$m];
		}
	}
	return @$_08CACHE['read'][$key];
}
function sys_cache($cname){
	global $$cname;
	@include_once M_ROOT.'./include/syscache/'.$cname.'.cac.php';
	if(!$$cname) $$cname = array();
}
function read_tag($ctype,$tname){
	global $sid;
	$ret = read_cache($ctype,$tname,'',$sid);
	$ret && $ret = array_merge($ret,$ret['setting']);
	unset($ret['setting']);
	return $ret;
}
function del_cache($ctype='',$m='',$n='',$sid=0){
	$cacdir = cache_dir($ctype,$sid);
	$cacname = cache_name($ctype,$m,$n);
	@unlink($cacdir.'/'.$cacname.'.cac.php');
	return;
}
function cache_dir($ctype='',$sid=0){
	global $subsites,$templatedir;
	if(in_array($ctype,array('ctag','utag','ptag','rtag','ctags','utags','ptags','rtags','mtpls','sptpls','jstpls','csstpls','usualtags','tagclasses',))){
		$tpldir = $sid ? $subsites[$sid]['templatedir'] : $templatedir;
		return M_ROOT."./template/$tpldir/cache";
	}else return M_ROOT.'./dynamic/cache'.($sid ? "/$sid" : '');
}
function cnode_dir($cnstr,$sid,$wri=0){
	$cacdir = M_ROOT.'./dynamic/cache'.($sid ? "/$sid" : '').'/cnodes';
	$wri && mmkdir($cacdir);
	return $cacdir;
}
function read_cnode($cnstr,$sid=0){
	global $_08CACHE;
	if(!$cnstr) return array();
	if(!isset($_08CACHE['read']['cnodes_'.$sid])) $_08CACHE['read']['cnodes_'.$sid] = read_cache('cnodes','','',$sid);
	return empty($_08CACHE['read']['cnodes_'.$sid][$cnstr]) ? array() : $_08CACHE['read']['cnodes_'.$sid][$cnstr];
}
function read_mcnode($cnstr){
	global $mcnodes;
	load_cache('mcnodes');
	return @$mcnodes[$cnstr];
}
?>