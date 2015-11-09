<?
!defined('M_COM') && exit('No Permisson');
include_once M_ROOT."./include/plus.fun.php";
include_once M_ROOT.'./include/ios.fun.php';
include_once M_ROOT."./include/cparse.fun.php";
include_once M_ROOT."./include/arcplus.inc.php";
include_once M_ROOT."./include/archive.fun.php";
function lang($str=''){
	global $langs;
	$str = @$langs[$str] ? $langs[$str] : $str;
	if(($num = func_num_args())>1){
		$ars = func_get_args();
		$ars[0] = &$str;
		return call_user_func_array('sprintf',$ars);
	}
	return $str;
}
function un_virtual($str){
	if(empty($str)) return $str;
	$str = str_replace(array('/','-'),array('&','='),$str);
	$str = preg_replace("/\.html$/i",'',$str);
	return $str;
}
function en_virtual($str,$suffix=0,$novu=0){
	global $virtualurl,$rewritephp;
	if(empty($str) || empty($virtualurl) || $novu || defined('WAP_MODE')) return $str;
	$str = str_replace('=','-',str_replace('&','/',$str));
	$suffix && $str .= '.html';
	$rewritephp && $str = str_replace('.php?',$rewritephp,$str);
	return $str;
}
function marc_parse(&$item){
	global $cms_abs;
	view_marcurl($item);
	$item['cms_counter'] = "<script type=\"text/javascript\" src=\"".$cms_abs."tools/counter.php?maid=".$item['maid']."&matid=".$item['matid']."\"></script>";
	arr_tag2atm($item,'ma');
}
function arr_tag2atm(&$item,$fmode=''){
	if(in_array($fmode,array('','f','m','ma',))){
		$fields = read_cache($fmode.'fields',$item[$fmode == 'm' ? 'mchid' : ($fmode == 'ma' ? 'matid' : 'chid')]);
	}elseif(in_array($fmode,array('ca','cc',))){
		$fields = reload_cache($fmode.'fields');
	}
	foreach($fields as $k => $v) if(isset($item[$k]) && $v['datatype'] == 'htmltext') $item[$k] = tag2atm($item[$k],1);
}
function cnoneid($id){
	return intval(ltrim($id,','));
}
function cnstitle($id,$mode,&$sarr,$num=0){
	if(!$id || !$sarr) return '';
	if(!$mode) return @$sarr[$id]['title'];
	$ids = array_filter(explode(',',$id));
	$ret = '';$i = 0;
	foreach($ids as $k){
		if($num && $num >= $i) break;
		$ret .= ','.@$sarr[$k]['title'];
	}
	return substr($ret,1);
}

function cnsonids($id,&$arr){
	if(!$id) return array();
	$ret = array($id);
	$ids = array();
	$ids = son_ids($arr,$id,$ids);
	return array_merge($ret,$ids);
}
function multi_str($arr = array(),$no = 0){
	if(count($arr) == 1) return ($no ? '!=' : '=')."'".array_shift($arr)."'";
	else return ($no ? 'NOT ' : '')."IN (".mimplode($arr).")";
}
function self_sqlstr($coid,$ccids,$pre = ''){
	global $cotypes,$timestamp;
	$sqlstr = '';
	if(empty($ccids)) return $sqlstr;
	if(!is_array($ccids)) $ccids = array($ccids);
	$multi = 0;
	foreach($ccids as $ccid){
		$sqlstr1 = '';
		if(!($coclass = read_cache('coclass',$coid,$ccid)) || empty($coclass['conditions'])) continue;
		foreach(array('createdate','clicks','comments','praises','debases','favorites','orders','price','answers','currency',) as $var){
			if(isset($coclass['conditions'][$var.'from'])){
				$sqlstr1 .= ($sqlstr1 ? ' AND ' : '').$pre.$var.">='".$coclass['conditions'][$var.'from']."'";
			}
			if(isset($coclass['conditions'][$var.'to'])){
				$sqlstr1 .= ($sqlstr1 ? ' AND ' : '').$pre.$var."<'".$coclass['conditions'][$var.'to']."'";
			}
		}
		if(isset($coclass['conditions']['indays'])) $sqlstr1 .= ($sqlstr1 ? ' AND ' : '').$pre."createdate>='".($timestamp - 86400 * $coclass['conditions']['indays'])."'";
		if(isset($coclass['conditions']['outdays'])) $sqlstr1 .= ($sqlstr1 ? ' AND ' : '').$pre."createdate<'".($timestamp - 86400 * $coclass['conditions']['outdays'])."'";
		if(isset($coclass['conditions']['closed'])){
			if($coclass['conditions']['closed']){
				$sqlstr1 .= ($sqlstr1 ? ' AND ' : '').'('.$pre.'closed=1 OR '.$pre.'finishdate<'.$timestamp.')';
			}else{
				$sqlstr1 .= ($sqlstr1 ? ' AND ' : '').'('.$pre.'closed=0 AND '.$pre.'finishdate>'.$timestamp.')';
			}
		}
		if(isset($coclass['conditions']['sqlstr'])){
			$coclass['conditions']['sqlstr'] = stripslashes(str_replace('{$pre}',$pre,$coclass['conditions']['sqlstr']));
			$sqlstr1 .= ($sqlstr1 ? ' AND ' : '').'('.$coclass['conditions']['sqlstr'].')';
		}
		($sqlstr1 && $sqlstr) && $multi = 1;
		$sqlstr1 && $sqlstr .= ($sqlstr ? ' OR ' : '').'('.$sqlstr1.')';
	}
	$multi && $sqlstr = '('.$sqlstr.')';
	return $sqlstr;	
}
function direct_html($url=''){
	global $cms_abs;
	return "<html><head><meta http-equiv=\"expires\" content=\"0\"><meta http-equiv=\"refresh\" content=\"0;url=".$cms_abs.$url."\"></head></html>";
}
function uccidsarr($coid,$chid = 0,$framein = 0,$nospace = 0,$viewp = 0){//$viewp需要pid资料，隐藏无效
	global $catalogs,$cotypes,$catahidden;
	$rets = array();
	if($coid && empty($cotypes[$coid])) return $rets;
	$arr = $coid ? read_cache('coclasses',$coid) : $catalogs;
	foreach($arr as $k => $v){
		$rets[$k]['title'] = ($nospace ? '' : str_repeat('&nbsp; ',$v['level'])).$v['title'];
		$ids = !empty($v['chids']) ? explode(',',$v['chids']) : array();
		if(($chid && !in_array($chid,$ids)) || (!$framein && $v['isframe'])){
			if(empty($catahidden) || $viewp){
				$rets[$k]['unsel'] = 1;
			}else unset($rets[$k]);
		}
		$viewp && $rets[$k]['pid'] = $v['pid'];
	}
	return $rets;
}
function read_htmlcac($cacfile){
	return (@include $cacfile) ? $caccnt : '';
}
function save_htmlcac($cnt,$cacfile){
	if(@$fp = fopen($cacfile,'wb')){
		fwrite($fp,"<?php\n\$caccnt = '".addcslashes($cnt,'\'\\')."';\n?>");
		fclose($fp);
	}
}
function array_alter_key(&$arr,$pre='m_'){
	$keys = array_keys($arr);
	foreach($keys as $k){
		$arr[$pre.$k] = $arr[$k];
		array_shift($arr);
	}
	unset($keys);
}
function cn_upid($id,&$arr,$level=0){
	if(empty($arr[$id])) return 0;
	return $arr[$id]['level'] < $level ? 0 : (!$arr[$id]['pid'] || $arr[$id]['level'] == $level ? $id : cn_upid($arr[$id]['pid'],$arr,$level));
}
function saveastxt($str,$namepre=''){//需要未转义的字串
	global $archtmlmode,$timestamp;
	if($str == '') return '';
	$dir = M_ROOT.'dynamic/htmltxt/';
	if(!$namepre){
		$sub_dir = date($archtmlmode == 'month' ? 'Ym' : 'Ymd',$timestamp).'/';
		mmkdir($dir.$sub_dir);
		$namepre = $sub_dir.substr(md5(microtime()),5,15).random(6,1);
	}
	save_htmlcac($str,$dir.$namepre.'.php');
	return $namepre;
}
function readfromtxt($namepre=''){
	if(!$namepre) return '';
	return read_htmlcac(M_ROOT.'dynamic/htmltxt/'.$namepre.'.php');
}
function txtunlink($namepre=''){
	if(!$namepre) return;
	@unlink(M_ROOT.'dynamic/htmltxt/'.$namepre.'.php');
}
function mmkdir($dir,$create=1,$isfile=0){
	if(is_dir($dir)) return true;
	if($isfile){
		return mmkdir(dirname($dir),0);
	}else{
		if(!mmkdir(dirname($dir),0) || @!mkdir($dir,0777)) return false;
		if($create) foreach(array('htm','html') as $var) @touch($dir.'/index.'.$var);
		return true;
	}
}

function clear_dir($dir,$self = false,$expstr = ''){
	if(is_dir($dir)){
		$exp_arr = array('.','..',);
		if($expstr) foreach(explode(',',$expstr) as $v) $exp_arr[] = $v;
		$p = opendir($dir);
		while(false !== ($f = readdir($p))){
			if(!in_array($f,$exp_arr)) clear_dir("$dir/$f",true,$expstr);
		}
		closedir($p);
		if($self) @rmdir($dir);
	}elseif(is_file($dir)) @unlink($dir);
}

function sqlstr_replace($str,&$temparr){
	return preg_replace("/\{\\$(.+?)\}/ies","sqlstrval('\\1',\$temparr)",$str);
}
function sqlstrval($tname,&$temparr){
	global $timestamp,$u_params;
	$temparr['timestamp'] = $timestamp;
	if(isset($temparr[$tname])){
		return $temparr[$tname];
	}elseif(isset($u_params[$tname])){
		return $u_params[$tname];
	}else return '';
}

function btagval($tname,&$sarr){
	global $btags,$debugtag;
	if(isset($sarr[$tname])){
		return str_tagcode($sarr[$tname]);
	}elseif(isset($btags[$tname])){
		return str_tagcode($btags[$tname]);
	}else return $debugtag ? "{ \$$tname}" : '';
}
function str_tagcode(&$source,$decode=0){
	return $decode ? str_replace(array(' $','? }'),array('$','?}'),$source) : str_replace(array('$','?}'),array(' $','? }'),$source);
}

function mailto($to,$subject,$msg,$sarr=array(),$from = '',$ischeck=0){
	include_once M_ROOT.'./include/mail.fun.php';
	$ret = sys_mail($to,splang($subject,$sarr),splang($msg,$sarr),$from);
	if(!$ischeck && $ret){
		global $curuser,$timestamp;
		$record = mhtmlspecialchars($timestamp."\t".$curuser->info['mid']."\t".$curuser->info['mname']."\t".$ret);
		record2file('smtp',$record);
	}
	return $ret;
}
function splang($key,&$sarr){
	global $splangs,$btags;
	load_cache('btags');
	$ret = $key;
	load_cache('splangs');
	if(isset($splangs[$key])) $ret = preg_replace("/\{\\$(.+?)\}/ies","btagval('\\1',\$sarr)",$splangs[$key]);
	return $ret;
}
function field_func($func='',&$arr1,&$arr2){
	if(!$func) return '';
	include_once M_ROOT."./dynamic/function/fields.fun.php";
	$arr1 && $func = preg_replace("/\{\\$(.+?)\}/ies","funcval('\\1',\$arr1)",$func);
	$arr2 && $func = preg_replace("/\{\\$(.+?)\}/ies","funcval('\\1',\$arr2)",$func);
	$ret = @eval($func);
	return $ret;
}
function funcval($tname,&$sarr){
	return isset($sarr[$tname]) ? $sarr[$tname] : '';
}
function pccidsarr($ccid,$coid,$self){
	$rets = array();
	if($arr = $coid ? read_cache('coclasses',$coid) : read_cache('acatalogs')){
		$ccid0 = $ccid;
		for($i = $arr[$ccid0]['level']; $i > 0; $i--) $rets[] = $ccid = $arr[$ccid]['pid'];
		count($rets) > 1 && $rets = array_reverse($rets);
		if($self == 1) $rets[] = $ccid0;
	}
	return $rets;
}
function cacc_arr($coid = 0,$source=0,$ids = ''){
	$arr = $coid ? read_cache('coclasses',$coid) : read_cache('acatalogs');
	if(!empty($source)){
		if($source == 1){
			$ids = array_filter(explode(',',$ids));
			$nids = array();
			foreach($ids as $v) $nids = array_merge($nids,pccidsarr($v,$coid,1));
			if($nids){
				$nids = array_unique($nids);
				foreach($arr as $k => $v){
					if(!in_array($k,$nids)){
						unset($arr[$k]);
					}elseif(!in_array($k,$ids)) $arr[$k]['unsel'] = 1;
				}
			}
		}elseif(!$coid) $arr = read_cache('catalogs','','',$source < 0 ? abs($source) : 0);
	}
	return $arr;
}
function marray_slice($arr,$offset = 0,$length = 0){//只允许正序//保留键值
	$length = empty($length) ? count($arr) : $length;
	if(version_compare(phpversion(), "5.0.2", ">=")){
		return array_slice($arr,$offset,$length,true);
	}else{
		$result = array();
		$i = 0;
		foreach($arr as $k => $v){
			if($length == count($result)) break; 
			if($i >= $offset) $result[$k] = $v;
			$i ++;
		}
		return $result;
	}
}
function marray_intersect_key($arr1,$arr2){
	if(version_compare(phpversion(), "5.1.0", ">=")){
		return array_intersect_key($arr1,$arr2);
	}else{
		foreach($arr1 as $k => $v) if(!isset($arr2[$k])) unset($arr1[$k]);
		return $arr1;
	}
}
function marray_flip_keys($arr) {
	$arr2 = array();
	$arrkeys = array_keys($arr);
	list(, $first) = each(array_slice($arr, 0, 1));
	if($first) {
		foreach($first as $k=>$v) {
			foreach($arrkeys as $key) {
				$arr2[$k][$key] = $arr[$key][$k];
			}
		}
	}
	return $arr2;
}

function updaterecent($aid = 0,$type='clicks',$add = 0){//更新当天的统计记录//只加不减
	global $db,$tblprefix,$timestamp,$monthstats,$weekstats;
	if(!$aid || $add <= 0) return;
	$mstatarr = empty($monthstats) ? array() : array_filter(explode(',',$monthstats));
	$wstatarr = empty($weekstats) ? array() : array_filter(explode(',',$weekstats));
	if(!in_array($type,$mstatarr) && !in_array($type,$wstatarr)) return;
	$vardate = date('Ymd',$timestamp);
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}arecents WHERE aid='$aid' AND vardate='$vardate'")){
		$db->query("UPDATE {$tblprefix}arecents SET $type=$type + $add WHERE aid='$aid' AND vardate='$vardate'",'SILENT');
	}else $db->query("INSERT INTO {$tblprefix}arecents SET aid='$aid',vardate='$vardate',$type='$add'",'SILENT');
}
function load_tpl($tplname,$rt=1){
	global $templatedir,$sid,$subsites;
	$sid && $templatedir = $subsites[$sid]['templatedir'];//子站分别指定模板目录
	$template = @file2str(M_ROOT."template/$templatedir/".$tplname);
	$rt && $template = preg_replace("/\{tpl\\$(.+?)\}/ies", "rtagval('\\1')",$template);
	return $template;
}
function rtagval($tname){
	global $rtags,$templatedir,$sid,$subsites;
	$rtag = read_tag('rtag',$tname);
	$sid && $templatedir = $subsites[$sid]['templatedir'];//子站分别指定模板目录
	$template = @file2str(M_ROOT."template/$templatedir/".@$rtag['template']);
	return $template ? $template : "{tpl\$$tname}";
}
function cnodearr($urlstr,$sid=0){
	global $subsites,$cmsinfos;
	if($cnode = read_cnode($urlstr,$sid)){
		view_cnurl($urlstr,$cnode);
		$cnode['sitename'] = empty($cnode['sid']) ? lang('msite') : $subsites[$cnode['sid']]['sitename'];
		$cnode['siteurl'] = view_siteurl($cnode['sid']);
	}
	return $cnode ? $cnode : false;
}
function mcnodearr($cnstr){
	global $mcnodes,$mcn_max_addno;
	load_cache('mcnodes');
	if($cnode = read_mcnode($cnstr)){
		$cnode['cname'] = $cnode['alias'];
		view_mcnurl($cnstr,$cnode);
	}else{
		$cnode = array('alias' => '');
		for($i = 0;$i <= $mcn_max_addno;$i++) $cnode['mcnurl'.($i ? $i : '')] = '#';
	}
	return $cnode;
}
function mcnstr($temparr){
	global $cotypes,$grouptypes;
	$vararr = array('caid','matid','mcnid');
	foreach($cotypes as $k => $v) !$v['self_reg'] && $vararr[] = 'ccid'.$k;
	foreach($grouptypes as $k => $v) !$v['issystem'] && $vararr[] = 'ugid'.$k;
	$cnstr = '';
	foreach($temparr as $k => $v){
		if(in_array($k,$vararr) && $v = max(0,intval($v))){
			$cnstr = $k.'='.$v;
			break;
		}
	}
	return $cnstr;
}

function order_arr(&$arr,$pid){
	$tempids = $oarr = array();
	$tempids = son_ids($arr,$pid,$tempids);
	foreach($tempids as $tempid) {
		$oarr[$tempid] = $arr[$tempid];
	}
	unset($tempids);
	return $oarr;
}
function son_ids(&$arr,$pid,&$ids){
	$narr = $arr;
	foreach($narr as $k => $v){
		if($v['pid'] == $pid){
			$ids[] = $k;
			$ids = son_ids($arr,$k,$ids);
		}
	}
	unset($narr);
	return $ids;
}
function view_url($url){
	global $cms_abs,$cmsurl;
	if(empty($url)) return $url;
	$url = preg_replace(array(u_regcode($cms_abs),u_regcode($cmsurl)),'',$url);
	if(strpos($url,'://') === false){
		$url = remove_index(domain_bind($url));
		$url = strpos($url,'://') === false ? ($cms_abs.$url) : $url;
	}
	defined('WAP_MODE') && $url = str_replace(array('&amp;','&'),array('&','&amp;'),$url);
	return $url;
}
function domain_bind($url){
	global $domains;
	load_cache('domains');
	if(!$url || empty($domains['from'])) return $url;
	foreach($domains['from'] as $k => $v){
		$nurl = @preg_replace($v,$domains['to'][$k],$url);
		$url = $nurl ? $nurl : $url;
	}
	return $url;
}
function remove_index($url){
	global $hiddensinurl;
	if(!$url || !($arr = explode(',',$hiddensinurl))) return $url;
	return str_replace($arr,'',$url);
}
function save_atmurl($url){
	global $cmsurl,$cms_abs,$ftp_url,$dir_userfile;
	$url = preg_replace(array(u_regcode($cms_abs),u_regcode($cmsurl)),'',$url);
	if($ftp_url) $url = preg_replace(u_regcode($ftp_url),'<!ftpurl />',$url);
	return $url;
}
function html_atm2tag(&$str){//转义后的字串
	global $cmsurl,$ftp_url,$cms_abs;
	$re = preg_quote($cms_abs,"/").'|'.preg_quote($cmsurl,"/").'|'.preg_quote($ftp_url,"/");
	$str = addslashes(preg_replace("/(src\s*=\s*['\"]?)($re)(.+?['\" >])/ies",'"$1".("$2"==$ftp_url?"<!ftpurl />":"<!cmsurl />")."$3"',stripslashes($str)));
}
function tag2atm(&$str,$istext=0){
	global $cms_abs,$ftp_url;
	$str = str_replace('<!cmsurl />',$cms_abs,str_replace('<!ftpurl />',$ftp_url,$str));
	if(!$istext) $str = view_url($str);
	return $str;
}
function view_atmurl($url=''){
	if(!$url) return '';
	return tag2atm($url);
}
function view_farcurl($id,$url=''){//不需区分子站//$s:0为附属信息内容页，1为自定义独立页
	global $virtualurl,$infohtmldir;
	if(!$url){
		$url = en_virtual("info.php?aid=$id",1);
	}else $url = $infohtmldir.'/'.$url;
	return view_url($url);
}
function view_marcurl(&$archive){
	global $marchtmldir;
	$archive['parcurl'] = view_url(en_virtual('marchive.php?maid='.$archive['maid'].'&matid='.$archive['matid'].'&isp=1',1));
	if(!$archive['arcurl']){
		$archive['arcurl'] = view_url(en_virtual('marchive.php?maid='.$archive['maid'].'&matid='.$archive['matid'],1));
	}else $archive['arcurl'] = view_url($marchtmldir.'/'.$archive['arcurl']);
}
function view_siteurl($sid=0){
	global $enablestatic,$subsites,$cms_abs,$wap_suffix;
	if(defined('WAP_MODE')){
		$url = 'wap/index.php?'.substr(($sid ? "&sid=$sid" : '').$wap_suffix,1);
	}else $url = $sid ? ($enablestatic ? cn_htmldir('',$sid) : en_virtual("index.php?sid=$sid",1)) : $cms_abs;
	return view_url($url);
}
function m_parseurl($url,$source = array()){
	if(!$source || !$url) return $url;
	$url = str_replace(' ','',$url);
	foreach($source as $k => $v){
		if(($k == 'page') && ($v == 1) && !in_str('page-',$url) && preg_match("/(^|\/)[\d_-]*(?:[a-z][\d_-]*)+\{\\\$page\}\./i",$url)) $v = '';
		$url = str_replace('{$'.$k.'}',$v,$url);
	}
	$url = preg_replace(array('/\/+/','/-+/','/_+/',),array('/','-','_'),$url);
	$url = str_replace(array('/-','/_','-/','_/','-.','_.',':/'),array('/','/','/','/','.','.','://'),$url);
	return $url;
}
function mcn_dir($cnstr){
	if(!$cnstr) return ''; 
	$var = array_map('trim',explode('=',$cnstr));
	if($var[0] == 'caid'){
		$arr = read_cache('acatalogs');
	}elseif(in_str('ccid',$var[0])) $arr = read_cache('coclasses',str_replace('ccid','',$var[0]));
	return empty($arr[$var[1]]['dirname']) ? $var[0].'_'.$var[1] : $arr[$var[1]]['dirname'];
}
function view_arcurl(&$archive,$addno = 0){//$addno-1为全部页面,不返回
	global $cnhtmldir,$enablestatic,$subsites,$arccustomurl,$wap_suffix,$mspaceurl;
	$channel = read_cache('channel',$archive['chid']);
	arc_checkend($archive);
	$addarr = $addno > 0 ? array($addno) : array(0);
	if($addno == -1) for($i = 1;$i <= $channel['addnum'];$i ++) $addarr[] = $i;
	if(defined('WAP_MODE')){
		foreach($addarr as $k) $archive['arcurl'.($k ? $k : '')] = view_url("wap/archive.php?aid=$archive[aid]".($k ? "&addno=$k" : '').$wap_suffix);
	}if(!empty($archive['jumpurl'])){
		foreach($addarr as $k) $archive['arcurl'.($k ? $k : '')] = $archive['jumpurl'];
		if($addno == -1) $archive['marcurl'] = $archive['jumpurl'];
	}else{
		$staticarr = empty($channel['statics']) ? array() : explode(',',$channel['statics']);
		$novuarr = empty($channel['novus']) ? array() : explode(',',$channel['novus']);
		$arcurl = arc_format($archive);
		foreach($addarr as $k){
			if(empty($staticarr[$k]) ? $enablestatic : ($staticarr[$k] == 1 ? 0 : 1)){
				$archive['arcurl'.($k ? $k : '')] = view_url(m_parseurl($arcurl,array('addno' => arc_addno($k,$channel['addnos']),'page' => 1,)));
			}else $archive['arcurl'.($k ? $k : '')] = view_url(en_virtual("archive.php?aid=$archive[aid]".($k ? "&addno=$k" : ''),1,@$novuarr[$k]));
		}
		if($addno == -1) $archive['marcurl'] = $mspaceurl.en_virtual("archive.php?mid=".@$archive['mid']."&aid=$archive[aid]",1);
	}
	return $addno >= 0 ? $archive['arcurl'.($addno ? $addno : '')] : true;
}
function view_mcnurl(&$cnstr,&$cnode){
	global $enablestatic,$mcn_max_addno,$memberurl;
	if(empty($cnode)) return;
	if(!empty($cnode['appurl'])){
		for($i = 0;$i <= $mcn_max_addno;$i ++) $cnode['mcnurl'.($i ? $i : '')] = $cnode['appurl'];
	}else{
		$staticarr = empty($cnode['statics']) ? array() : explode(',',$cnode['statics']);
		$urlarr = empty($cnode['urls']) ? array() : explode(',',$cnode['urls']);
		for($i = 0;$i <= $mcn_max_addno;$i ++){
			if(empty($staticarr[$i]) ? $enablestatic : ($staticarr[$i] == 1 ? 0 : 1)){
				$cnode['mcnurl'.($i ? $i : '')] = $i <= @$cnode['addnum'] ? view_url($memberurl.m_parseurl(empty($urlarr[$i]) ? '{$cndir}/index{$addno}_{$page}.html' : $urlarr[$i],array('cndir' => mcn_dir($cnstr),'addno' => $i ? $i : '','page' => 1,))) : '#';
			}else $cnode['mcnurl'.($i ? $i : '')] = $i <= @$cnode['addnum'] ? $memberurl.en_virtual("index.php?$cnstr".($i ? "&addno=$i" : ''),1) : '#';
		}
	}
}
function view_cnurl(&$cnstr,&$cnode){
	global $enablestatic,$cn_max_addno,$cn_urls,$wap_suffix;
	if(empty($cnode)) return;
	if(defined('WAP_MODE')){
		for($i = 0;$i <= $cn_max_addno;$i ++) $cnode['indexurl'.($i ? $i : '')] = $i <= @$cnode['addnum'] ? view_url("wap/index.php?$cnstr".($i ? "&addno=$i" : '').(empty($cnode['sid']) ? '' : '&sid='.$cnode['sid']).$wap_suffix) : '#';
	}elseif(!empty($cnode['appurl'])){
		for($i = 0;$i <= $cn_max_addno;$i ++) $cnode['indexurl'.($i ? $i : '')] = $cnode['appurl'];
	}else{
		$staticarr = empty($cnode['statics']) ? array() : explode(',',$cnode['statics']);
		for($i = 0;$i <= $cn_max_addno;$i ++){
			if(empty($staticarr[$i]) ? $enablestatic : ($staticarr[$i] == 1 ? 0 : 1)){
				$cnode['indexurl'.($i ? $i : '')] = $i <= @$cnode['addnum'] ? view_url(m_parseurl(cn_format($cnstr,$i,$cnode),array('page' => 1))) : '#';
			}else $cnode['indexurl'.($i ? $i : '')] = $i <= @$cnode['addnum'] ? view_url(en_virtual("index.php?$cnstr".($i ? "&addno=$i" : '').(empty($cnode['sid']) ? '' : '&sid='.$cnode['sid']),1)) : '#';
		}
	}
}
function m_unlink($filepre='',$num=50){//以带可变参数{$page}的文件名来删除多页同名文件
	if(!$filepre) return;
	for($i = 1;$i <= $num;$i++){
		if(@!unlink(M_ROOT.m_parseurl($filepre,array('page' => $i,)))) break;
	}
}
function local_file($url){
	global $cmsurl,$cms_abs;
	return islocal($url) ? M_ROOT.preg_replace(array(u_regcode($cms_abs),u_regcode($cmsurl)),'',$url) : $url;
}
function local_atm($url,$isftp=0){//根据url得到本地路径//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	global $cmsurl,$cms_abs,$atm_smallsite,$ftp_url;
	$url = preg_replace(u_regcode($cmsurl),'',preg_replace(u_regcode($cms_abs),'',$url));
	$atm_smallsite && $url = preg_replace(u_regcode($atm_smallsite),'',$url);
	if($isftp && $ftp_url) $url = preg_replace(u_regcode($ftp_url),'',$url);
	$url = M_ROOT.$url;
	return $url;
}
function islocal($url,$isatm=0){//$isatm为0-非附件，1-ftp附件算本地，2-ftp附件不算本地
	global $cms_abs,$ftp_url,$atm_smallsite;
	if(strpos($url,'://') === false) return true;
	if(preg_match(u_regcode($cms_abs),$url)) return true;
	if($isatm && $atm_smallsite && preg_match(u_regcode($atm_smallsite),$url)) return true;
	if($ftp_url && ($isatm == 1) && preg_match(u_regcode($ftp_url),$url)) return true;
	return false;
}
function u_regcode($str){
	return "/^".preg_quote($str,"/")."/i";
}
function in_str($me,$source){
	return !(strpos($source,$me) === FALSE);
}
function loaduclasses($mid){
	global $db,$tblprefix;
	$uclasses = array();
	if(empty($mid)) return $uclasses;
	$query = $db->query("SELECT * FROM {$tblprefix}uclasses WHERE mid='$mid' ORDER BY vieworder");
	while($uclass = $db->fetch_array($query)){
		$uclasses[$uclass['ucid']] = $uclass;
	}
	return $uclasses;
}
function load_mtconfig($mid=0,$name='setting'){
	global $db,$tblprefix,$mtconfigs;
	$rets = array();
	if(!$mid) return $rets;
	$mtcid = $db->result_one("SELECT mtcid FROM {$tblprefix}members WHERE mid='$mid'");
	$mtcid = empty($mtcid) ? 1 : $mtcid;
	if(empty($mtconfigs[$mtcid])) return $rets;
	$rets = $mtconfigs[$mtcid][$name];
	return $rets;
}
function file_down($file, $filename = ''){
	global $timestamp;
	if(!file_exists($file)) return;
	$filename = $filename ? $filename : basename($file);
	$filetype = mextension($filename);
	$filesize = filesize($file);
    ob_end_clean();
	@set_time_limit(900);
	header('Cache-control: max-age=31536000');
	header('Expires: '.gmdate('D, d M Y H:i:s', $timestamp + 31536000).' GMT');
	header('Content-Encoding: none');
	header('Content-Length: '.$filesize);
	header('Content-Disposition: attachment; filename='.$filename);
	header('Content-Type: '.$filetype);
	readfile($file);
	exit;
}
function js_write(&$content){
	echo "document.write('".addcslashes($content, "'\\\r\n")."');";
}

function record2file($rname,$record){
	global $timestamp;
	$recorddir = M_ROOT.'./dynamic/records/';
	$recordfile_pre = $recorddir.date('Ym',$timestamp).'_'.$rname;
	$recordfile = $recordfile_pre.'.php';
	if(@filesize($recordfile) > 1024*1024){
		$dir = opendir($recorddir);
		$length = strlen($rname);
		$maxid = $id = 0;
		while($file = readdir($dir)){
			if(in_str($recordfile_pre,$file)){
				$id = intval(substr($file,$length +8,-4));
				($id > $maxid) && ($maxid = $id);
			}
		}
		closedir($dir);
		$recordfilebk = $recordfile_pre.'_'.($maxid +1).'.php';
		@rename($recordfile,$recordfilebk);
	}
	if($fp = @fopen($recordfile, 'a')){
		@flock($fp, 2);
		$record = is_array($record) ? $record : array($record);
		foreach($record as $tmp) {
			fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>'), '', $tmp)."\n");
		}
		fclose($fp);
	}
}

function findfiles($absdir,$str='',$inc=0){//$inc 0按扩展名查询，1按包含字串查询
	$tempfiles = array();
	if(is_dir($absdir)){
		if($tempdir = opendir($absdir)){
			while(($tempfile = readdir($tempdir)) !== false){
				if(filetype($absdir."/".$tempfile) == 'file'){
					if(!$str){
						$tempfiles[] = $tempfile;
					}elseif(!$inc && mextension($tempfile) == $str){
						$tempfiles[] = $tempfile;
					}elseif($inc && in_str($str,$tempfile)){
						$tempfiles[] = $tempfile;
					}
				}
			}
			closedir($tempdir);
		}
	}
	return $tempfiles;
}
function keywords($nstr,$ostr=''){//转义过的词
	global $hotkeywords,$db,$tblprefix;
	if(empty($nstr)) return '';
	$nstr = stripslashes($nstr);
	$nstr = str_replace(array(chr(0xa3).chr(0xac),chr(0xa1).chr(0x41),chr(0xef).chr(0xbc).chr(0x8c),chr(0xa1).chr(0xa1),chr(0xa1).chr(0x40),chr(0xe3).chr(0x80).chr(0x80),chr(0x2c),),' ',$nstr);
	$narr = array_unique(explode(' ',$nstr));
	$oarr = $ostr ? explode(' ',$ostr) : array();
	$i = 0;
	$ret = $sqlstr = '';
	foreach($narr as $str){
		$str = strip_tags(trim($str));
		if(preg_match('/^([\x20-\xff_-]|\s){3,24}$/',$str)){
			$ret .= ($ret ? ' ' : '').$str;
			($hotkeywords && !in_array($str,$oarr)) && $sqlstr .= ($sqlstr ? ',' : '')."('".addslashes($str)."')";
		}
		$i ++;
		if($i > 4){
			unset($narr,$oarr);
			break;
		}
	}
	$sqlstr && $db->query("INSERT INTO {$tblprefix}keywords (keyword) VALUES $sqlstr");
	return addslashes($ret);
}


function mnl2br($string){
	return nl2br(str_replace(array("\t", '   ', '  '), array('&nbsp; &nbsp; &nbsp; &nbsp; ', '&nbsp; &nbsp;', '&nbsp;&nbsp;'),$string));
}
function br2nl($string){
	return str_replace("<br />", '',$string);
}
function safestr($string){
	$searcharr = array("/(javascript|jscript|js|vbscript|vbs|about):/i","/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i","/<script([^>]*)>/i","/<iframe([^>]*)>/i","/<frame([^>]*)>/i","/<link([^>]*)>/i","/@import/i");
	$replacearr = array("\\1\n:","on\n\\1","&lt;script\\1&gt;","&lt;iframe\\1&gt;","&lt;frame\\1&gt;","&lt;link\\1&gt;","@\nimport");
	$string = preg_replace($searcharr,$replacearr,$string);
	$string = str_replace("&#","&\n#",$string);
	return $string;
}
function file2str($filename){
	if(!@$fp = fopen($filename, 'r')) return false;
	$str = fread($fp, filesize($filename));
	fclose($fp);
	return $str;
}
function str2file($result,$filename){
	mmkdir($filename,0,1);
	if(!@$fp = fopen($filename, 'w')) return false;
	flock($fp, 2);
	fwrite($fp, $result);
	fclose($fp);
	return true;
}
function regcode_pass($rname,$code=''){
	global $m_cookie,$timestamp,$cms_regcode;
	if(!$cms_regcode || !in_array($rname,explode(',',$cms_regcode))) return true;
	list($inittime, $initcode) = maddslashes(explode("\t", @authcode($m_cookie['08cms_regcode'],'DECODE')),1);
	if(($timestamp - $inittime) > 1800 || $initcode != $code){
		return false;
	}
	return true;
}

function authcode($string, $operation, $key = '') {
	global $authorization;
	$key = md5($key ? $key : $authorization);
	$key_length = strlen($key);

	$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
	$string_length = strlen($string);

	$rndkey = $box = array();
	$result = '';

	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}

}

function is_robots(){
	global $_SERVER;
	if(!defined('IS_ROBOT')) {
		$kw_spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
		$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
		if(preg_match("/($kw_browsers)/", $_SERVER['HTTP_USER_AGENT'])){
			define('IS_ROBOT', FALSE);
		}elseif(preg_match("/($kw_spiders)/", $_SERVER['HTTP_USER_AGENT'])){
			define('IS_ROBOT', TRUE);
		}else{
			define('IS_ROBOT', FALSE);
		}
	}
	return IS_ROBOT;
}
function mheader($string,$replace = true,$http_response_code = 0){
	$string = str_replace(array("\r","\n"),'',$string);
	if(empty($http_response_code) || PHP_VERSION < '4.3'){
		@header($string,$replace);
	}else{
		@header($string,$replace,$http_response_code);
	}
	if(preg_match('/^\s*location:/is',$string)){
		exit();
	}
}

function maddslashes($string, $force = 0) {
	!defined('QUOTES_GPC') && define('QUOTES_GPC', get_magic_quotes_gpc());
	if(!QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = maddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}
function mstripslashes($string){
	if(is_array($string)) {
		foreach($string as $key => $val){
			$string[$key] = mstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
function mis_uploaded_file($file){
	return function_exists('is_uploaded_file') && (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file)));
}

function mimplode($arr){
	return empty($arr) ? '' : "'".implode("','", is_array($arr) ? $arr : array($arr))."'";
}


function mexit($message = ''){
	echo $message;
	output();
	exit();
}

function output(){
	global $gzipenable;
	$content = ob_get_contents();
	ob_end_clean();
	$gzipenable ? ob_start('ob_gzhandler') : ob_start();
	echo $content;
}

function submitcheck($var, $allowget = 0){
	return empty($GLOBALS[$var]) ? false : true;
}
function mclearcookie($ckname='userauth'){
	if($ckname == 'userauth'){
		global $memberid,$memberpwd,$curuser;
		msetcookie('userauth','',-86400 * 365);
		$memberid = 0;
		$memberpwd = '';
		unset($curuser);
	}else msetcookie($ckname,'',-86400 * 365);
}

function msetcookie($ckname, $ckvalue, $cklife = 0) {
	global $ckpre, $ckdomain, $ckpath, $timestamp, $_SERVER;
	setcookie($ckpre.$ckname, $ckvalue, $cklife ? $timestamp + $cklife : 0, $ckpath, $ckdomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function mhtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = mhtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

function mextension($filename) {
	return trim(substr(strrchr($filename, '.'), 1, 10));
}

function misuploadedfile($file) {
	return function_exists('is_uploaded_file') && (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file)));
}

function ipaccess($ip, $accesslist) {
	return preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($accesslist, '/')).")/", $ip);
}

function random($length, $onlynum = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($onlynum) {
		$result = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$result = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$result .= $chars[mt_rand(0, $max)];
		}
	}
	return $result;
}
function isemail($email){
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}
function isdate($date) {
	if(empty($date)) return FALSE;
	if(strlen($date) > 10)  return FALSE;
	if(!preg_match('/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/',$date)) return FALSE;
	list($year, $month, $day) = explode('-', $date);
	return @checkdate($month, $day, $year);
}

function cutstr($string, $length, $dot = ' ...') {
	global $mcharset;
	if(strlen($string) <= $length) {
		return $string;
	}
	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	$strcut = '';
	if(strtolower($mcharset) == 'utf-8') {
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
	} else {
		for($i = 0; $i < $length - strlen($dot) - 1; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}

function axaction($mode,$url=''){
	global $infloat,$handlekey;
	$ret='';
	if(!$infloat)return $url;
	if(!$mode && $url){//0，本窗口跳转
		$ret.="floatwin('open_$handlekey','$url');";
	}
	if($mode&1){//包含1，刷新本窗口
		$ret.="floatwin('update_$handlekey');";
	}
	if($mode&2){//包含2，关闭本窗口
		$ret.="floatwin('close_$handlekey',-1);";
	}
	$ret='javascript:'.($ret?('setDelay(\''.str_replace("'","\\'",$ret).'\',t);'):'');
	if($mode&4){//包含4，刷新父窗口
		$ret.="floatwin('updateparent_$handlekey');";
	}
	if($mode&8){//包含8，关闭父窗口
		$ret.="floatwin('closeparent_$handlekey',-1);";
	}
	if($mode&16){//包含16，刷新父父窗口
		$ret.="floatwin('updateup2_$handlekey',-1);";
	}
	return $ret;
}
function ccstrlen($str){
	global $mcharset;
	if(!($len = strlen($str))) return 0;
	$n = $i = 0;
	if(strtolower($mcharset) == 'utf-8'){
		while($n < $len){
			$t = ord($str[$n]);
			if(194 <= $t && $t <= 223){
				$n += 2;
			}elseif(224 <= $t && $t <= 239){
				$n += 3;
			}elseif(240 <= $t && $t <= 247){
				$n += 4;
			}elseif(248 <= $t && $t <= 251){
				$n += 5;
			}elseif($t == 252 || $t == 253){
				$n += 6;
			}else  $n++;
			$i ++;
		}
	}else{
		while($n < $len){
			$n += ord($str[$n]) > 127 ? 2 : 1;
			$i ++;
		}
	}
	return $i;
}

function html2text($str){
	$str = preg_replace("/<sty.*?\\/style>|<scr.*?\\/script>|<!--.*?-->/is", '', $str);
	$str = preg_replace("/<\\/?(?:p|div|dt|dd|li)\b.*?>/is", '<br>', $str);
	$str = preg_replace("/\s+/", '', $str);
	$str = preg_replace("/<br\s*\\/?>/is", "\r\n", $str);
	$str = strip_tags($str);

	return str_replace(
		array('&lt;', '&gt;', '&nbsp;', '&quot;', '&ldquo;', '&rdquo;', '&amp;'),
		array('<','>', ' ', '"', '“', '”', '&'),
		$str
	);
}
?>