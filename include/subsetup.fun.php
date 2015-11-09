<?php
!defined('M_COM') && exit('No Permission');
//需要建立读取缓存，读取细分缓存，读取模板，读取模板缓存，写入缓存的函数
//需要安装包中的sid，及即将产生的sid的记录
function oread_cache($ctype='',$m='',$n='',$place=''){
	global $subsetupdir,$osid;
	$osid = empty($osid) ? 0 : intval($osid);
	$cacdir = $subsetupdir.($place == 'cache' ? 'cache/' : ($place == 'template' ? 'template/cache/' : ''));
	$cname = ocache_name($ctype,$m,$n);
	if(@include $cacdir.$cname.'.cac.php'){
		$$cname = &${$cname.'_'.(in_array($place,array('cache','template')) ? $osid : 0)};
	}else $$cname = array();
	return $$cname;
}
function ocache_name($ctype='',$m='',$n=''){
	if($ctype == 'cnode'){
		$cname = str_replace('=','_',str_replace('&','__',$m));
	}elseif(in_array($ctype,array('usergroup','coclass','field','ffield','mfield'))){
		$cname = $ctype.'_'.$m.'_'.$n;
	}else $cname = $ctype.str_replace('.','',$m).$n;
	return $cname;
}
function ocache2file($carr,$cname){//只是写入一些状态记录
	global $subsetupdir;
	if(!is_array($carr) || empty($cname)) return;
	$cacstr = var_export($carr,TRUE);
	if(@$fp = fopen($subsetupdir.$cname.'.cac.php','wb')){
		$cname .= '_0';
		fwrite($fp,"<?php\n\$$cname = $cacstr ;\n?>");
		fclose($fp);
	}
}
function str2newid($str,$mode=''){
	global $idsmap;
	if($str == '') return $str;
	$arr = explode(',',$str);
	foreach($arr as $k => $v){
		if(isset($idsmap[$mode][$v])) $arr[$k] = $idsmap[$mode][$v];
	}
	$str = implode(',',$arr);
	return $str;
}
function cnstr2newid($cnstr){
	if($cnstr == '') return $cnstr;
	parse_str($cnstr,$idsarr);
	$nidsarr = array();
	foreach($idsarr as $k => $v){
		if($k == 'caid'){
			$nidsarr['caid'] = str2newid($v,'caids');
		}else{
			$nk = 'ccid'.str2newid(str_replace('ccid','',$k),'coids');
			$nidsarr[$nk] = str2newid($v,'ccids');
		}
	}
	$ret = '';
	foreach($nidsarr as $k => $v) $ret .= ($ret ? '&' : '').$k.'='.$v;
	unset($idsarr,$nidsarr);
	return $ret;
}

function dir_copy($source,$destination,$f = 0,$d = 0){//$f-是否复制文件夹下文件，$d是否复制搜索下级文件夹
	if(!is_dir($source)) return false;
	mmkdir($destination,0);
	if($f || $d){
		$handle = dir($source);
		while($entry = $handle->read()){
			if(($entry != ".") && ($entry != "..")){
				if(is_dir($source."/".$entry)){
					$d && dir_copy($source."/".$entry,$destination."/".$entry,$f,$d);
				}else{
					$f && copy($source."/".$entry,$destination."/".$entry);
				}
			}
		}
	}
	return true;
}

function r_ugid($str,$str1){
	return '[ugid'.str2newid($str,'gtids').'='.str2newid($str1,'ugids').'/]';
}
function r_cuid($str){
	return '[cuid='.str2newid($str,'cuids').'/]';
}
function r_chids($str){
	return '[chids='.str2newid($str,'chids').'/]';
}
function r_mchids($str){
	return '[chids='.str2newid($str,'mchids').'/]';
}
function r_coinherit($str,$str1){
	return '[coinherit'.str2newid($str,'coids').'='.str2newid($str1,'ccids').'/]';
}
function r_listby($str){
	return '[listby=co'.str2newid($str,'coids').'/]';
}
function r_urlmode($str){
	return '[urlmode=ccid'.str2newid($str,'coids').'/]';
}
function r_cainherit($str){
	return '[cainherit='.str2newid($str,'caids').'/]';
}
function r_casource($str){
	return '[casource='.str2newid($str,'caids').'/]';
}
function r_fcasource($str){
	return '[casource='.str2newid($str,'fcaids').'/]';
}
function r_atid($str){
	return '[atid='.str2newid($str,'atids').'/]';
}
function r_album($str){
	return '[album='.str2newid($str,'atids').'/]';
}
function r_atsource($str){
	return '[atsource='.str2newid($str,'atids').'/]';
}
function r_caids($str){
	return '[caids='.str2newid($str,'caids').'/]';
}
function r_cosource($str){
	return '[cosource'.str2newid($str,'coids').'=';
}
function r_cosource1($str,$str1){
	return '[cosource'.str2newid($str,'coids').'='.str2newid($str1,'ccids').'/]';
}

function r_ccidson($str){
	return '[ccidson'.str2newid($str,'coids').'=';
}
function r_ccid($str){
	return '[ccid='.str2newid($str,'ccids').'/]';
}
function r_ccids($str,$str1){
	return '[ccids'.str2newid($str,'coids').'='.str2newid($str1,'ccids').'/]';
}

function oreplace(&$content,$mode='c'){
	$content = preg_replace("/\{".$mode."\\$(.+?)\s+(.*?)\{\/".$mode."\\$\\1\}/ies",$mode."tagtrans('\\1','\\2')",$content);
}
function ptagtrans($tname,$tstr){
	$tag = o_tag_arr($tname,stripslashes($tstr));
	if(empty($tag['tclass'])) return '{p$'.$tname.' '.stripslashes($tstr).'{/p$'.$tname.'}';
	//先转模板中的标识
	oreplace($tag['template'],'c');//标识中只转复合标识
	//再转标识本身的设置
	$needstrans = '';
	if(in_array($tag['tclass'],array('archives','alarchives'))){
		$needstrans = 'caids,ccidson,cosource,ccids,atsource,chids';
	}elseif(in_array($tag['tclass'],array('farchives'))){
		$needstrans = 'fcasource';
	}elseif(in_array($tag['tclass'],array('commus'))){
		$needstrans = 'cuid';
	}elseif(in_array($tag['tclass'],array('members'))){
		$needstrans = 'ugid,mchids';
	}
	$tstr = otagreplace($tstr,$needstrans);
	$tstr = str_replace('#<#template#>#',$tag['template'],$tstr);
	return '{p$'.$tname.' '.stripslashes($tstr).'{/p$'.$tname.'}';
}
function ctagtrans($tname,$tstr){
	$tag = o_tag_arr($tname,stripslashes($tstr));
	if(empty($tag['tclass'])) return '{c$'.$tname.' '.stripslashes($tstr).'{/c$'.$tname.'}';
	//先转模板中的标识
	oreplace($tag['template'],'c');//标识中只转复合标识
	//再转标识本身的设置
	$needstrans = '';
	if(in_array($tag['tclass'],array('archives','alarchives','arcscount',))){
		$needstrans = 'caids,ccidson,cosource,ccids,atsource,chids';
	}elseif(in_array($tag['tclass'],array('archive'))){
		$needstrans = 'album';
	}elseif(in_array($tag['tclass'],array('catalogs','mcatalogs'))){
		$needstrans = 'urlmode,listby,caids,cainherit,coinherit,cosource,ccids';
	}elseif(in_array($tag['tclass'],array('cnode'))){
		$needstrans = 'listby,casource,cosource1';
	}elseif(in_array($tag['tclass'],array('nownav'))){
		$needstrans = 'urlmode';
	}elseif(in_array($tag['tclass'],array('acontext'))){
		$needstrans = 'atid,atsource';
	}elseif(in_array($tag['tclass'],array('context'))){
		$needstrans = 'ccid';
	}elseif(in_array($tag['tclass'],array('farchives'))){
		$needstrans = 'fcasource';
	}elseif(in_array($tag['tclass'],array('commus'))){
		$needstrans = 'cuid';
	}elseif(in_array($tag['tclass'],array('members','memscount'))){
		$needstrans = 'ugid,mchids';
	}elseif(in_array($tag['tclass'],array('channels'))){
		$needstrans = 'chids';
	}elseif(in_array($tag['tclass'],array('mchannels'))){
		$needstrans = 'mchids';
	}
	$tstr = otagreplace($tstr,$needstrans);
	$tstr = str_replace('#<#template#>#',$tag['template'],$tstr);
	return '{c$'.$tname.' '.stripslashes($tstr).'{/c$'.$tname.'}';
}
function otagreplace($str = '',$needstr = ''){
	if(!$str || !$needstr) return $str;
	$needarr = explode(',',$needstr);
	in_array('caids',$needarr) && $str = preg_replace("/\[\s*caids\s*=\s*(.*?)\s*\/\]/ies","r_caids('\\1')",$str);
	in_array('cosource',$needarr) && $str = preg_replace("/\[cosource(.*?)\s*=/ies","r_cosource('\\1')",$str);
	in_array('cosource1',$needarr) && $str = preg_replace("/\[cosource(.*?)\s*=\s*(.*?)\s*\/\]/ies","r_cosource1('\\1','\\2')",$str);
	in_array('ccidson',$needarr) && $str = preg_replace("/\[ccidson(.*?)\s*=/ies","r_ccidson('\\1')",$str);
	in_array('ccids',$needarr) && $str = preg_replace("/\[ccids(.*?)\s*=\s*(.*?)\s*\/\]/ies","r_ccids('\\1','\\2')",$str);
	in_array('ccid',$needarr) && $str = preg_replace("/\[ccid\s*=\s*(.*?)\s*\/\]/ies","r_ccid('\\1')",$str);
	in_array('atsource',$needarr) && $str = preg_replace("/\[atsource\s*=\s*(.*?)\s*\/\]/ies","r_atsource('\\1')",$str);
	in_array('album',$needarr) && $str = preg_replace("/\[album\s*=\s*(.*?)\s*\/\]/ies","r_album('\\1')",$str);
	in_array('atid',$needarr) && $str = preg_replace("/\[atid\s*=\s*(.*?)\s*\/\]/ies","r_atid('\\1')",$str);
	in_array('cainherit',$needarr) && $str = preg_replace("/\[cainherit\s*=\s*(.*?)\s*\/\]/ies","r_cainherit('\\1')",$str);
	in_array('casource',$needarr) && $str = preg_replace("/\[casource\s*=\s*(.*?)\s*\/\]/ies","r_casource('\\1')",$str);
	in_array('urlmode',$needarr) && $str = preg_replace("/\[urlmode\s*=\s*ccid(.*?)\s*\/\]/ies","r_urlmode('\\1')",$str);
	in_array('listby',$needarr) && $str = preg_replace("/\[listby\s*=\s*co(.*?)\s*\/\]/ies","r_listby('\\1')",$str);
	in_array('coinherit',$needarr) && $str = preg_replace("/\[coinherit(.*?)\s*=\s*(.*?)\s*\/\]/ies","r_coinherit('\\1','\\2')",$str);
	in_array('chids',$needarr) && $str = preg_replace("/\[chids\s*=\s*(.*?)\s*\/\]/ies","r_chids('\\1')",$str);
	in_array('cuid',$needarr) && $str = preg_replace("/\[cuid\s*=\s*(.*?)\s*\/\]/ies","r_cuid('\\1')",$str);
	in_array('ugid',$needarr) && $str = preg_replace("/\[ugid(.*?)\s*=\s*(.*?)\s*\/\]/ies","r_ugid('\\1','\\2')",$str);
	in_array('fcasource',$needarr) && $str = preg_replace("/\[casource\s*=\s*(.*?)\s*\/\]/ies","r_fcasource('\\1')",$str);
	in_array('mchids',$needarr) && $str = preg_replace("/\[chids\s*=\s*(.*?)\s*\/\]/ies","r_mchids('\\1')",$str);
	return $str;
}
function oheadrelpace($str = ''){
	global $osid,$nsid;
	if(!$str) return $str;
	$str = preg_replace("/\\$(.*?)_".$osid." = array/is","\$\\1_".$nsid." = array",$str);
	return $str;
}

function o_tag_arr($tname,&$tstr){
	$arr = array();
	if(preg_match("/^\s*(.+?)\/\]\s*\}/is",$tstr,$matches)){
		if($str = $matches[0]){
			if(preg_match_all("/\[\s*(.+?)\s*\=\s*(.*?)\s*\/\]/is",$str, $matches)){
				foreach($matches[1] as $k => $v) $arr[$v] = $matches[2][$k];
			}
		}
		$arr['template'] = preg_replace("/^\s*(.+?)\/\]\s*\}/is",'',$tstr);
		$tstr = preg_replace("/^(.+?)\/\]\s*\}(.*?)$/is","\\1/]}#<#template#>#",$tstr);
	}
	unset($matches);
	return $arr;
}

?>
