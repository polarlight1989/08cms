<?php
//区分主站与子站
error_reporting(0);
define('M_COM', TRUE);
define('M_ROOT','');
$timestamp = time();
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
}
include_once M_ROOT.'./base.inc.php';
$m_cookie = array();
$prelength = strlen($ckpre);
foreach($_COOKIE as $key => $val) {
	if(substr($key, 0, $prelength) == $ckpre) {
		$m_cookie[(substr($key, $prelength))] = $val;
	}
}
include_once M_ROOT.'./include/general.fun.php';
include_once M_ROOT.'./include/parse/general.php';
load_cache('mconfigs');
@extract($mconfigs);

!empty($cmsclosed) && exit('System Closed');
empty($rss_enabled) && exit('RSS Disabled');
$rss_num = 20;

include_once M_ROOT.'./include/mysql.cls.php';
$db = new cls_mysql;
$db->connect($dbhost,$dbuser,$dbpw,$dbname,$pconnect,true,$dbcharset);
include_once M_ROOT.'./include/userinfo.cls.php';
load_cache('grouptypes');
sys_cache('nouserinfos');
$authorization = md5($authkey);
$curuser = new cls_userinfo;
$curuser->rss_user();
load_cache('cotypes,permissions');

$querystr = $_SERVER['QUERY_STRING'];
parse_str($querystr,$temparr);//参数有先后排序
$sid = empty($temparr['sid']) ? 0 : max(0,intval($temparr['sid']));
load_cache('catalogs,cnodes',$sid);

$vararr = array('caid');
foreach($cotypes as $coid => $cotype){
	$cotype['sortable'] && $vararr[] = 'ccid'.$coid; 
}
$cnstr = '';
$cn_name = $cmsname;
$sqlstr = '';
foreach($temparr as $k => $v){
	if(in_array($k,$vararr)){
		$v = max(0,intval($v));
		$temparr[$k] = $v;
		$cnstr .= ($cnstr ? '&' : '').$k.'='.$v;
		if($k == 'caid' && !empty($catalogs[$v])){
			$cn_name .= '-'.$catalogs[$v]['title'];
			$caids = cnsonids($caid,$catalogs);
			$sqlstr .= (empty($sqlstr) ? '' : ' AND ')."caid='".$v."'";
		}elseif(preg_match("/^ccid(\d+)/is",$k,$matches)){
			$coid = $matches[1];
			$coclasses = read_cache('coclasses',$coid);
			$cn_name .= '-'.$coclasses[$v]['title'];
			
			
			if(empty($cotypes[$coid]['self_reg'])){
				$sqlstr .= (empty($sqlstr) ? '' : ' AND ')."ccid$coid='".$v."'";
			}else{
				$tempstr = self_sqlstr($coid,$v,'');
				$tempstr && $sqlstr .= (empty($sqlstr) ? '' : ' AND ').$tempstr;
				unset($tempstr);
			} 
		}
	}
}
$mode = 0;
if(empty($cnstr) || !isset($cnodes[$cnstr])){
	$mode = 1;
	$cn_link = $cms_abs;
}else{
	if(!$curuser->pmbypmids('cread',cn_pmids($cnstr,$sid))) exit(lang('nocatasbrowsepermis'));
	$cnode = cnodearr($cnstr);
	$cn_link = $cnode['indexurl'];
}
$cn_name = mhtmlspecialchars($cn_name);
$rss_str = "<?xml version=\"1.0\" encoding=\"".$mcharset."\"?>\n".
			"<rss version=\"2.0\">\n".
			"  <channel>\n".
			"    <title>$cn_name</title>\n".
			"    <link>".mhtmlspecialchars($archive['subject'])."$cn_link</link>\n".
			"    <description>Latest $rss_num archives of $cn_name</description>\n".
			"    <copyright>Copyright(C) ".mhtmlspecialchars($cmsname)."</copyright>\n".
			"    <generator>www.08cms.com</generator>\n".
			"    <lastBuildDate>".date('r', $timestamp)."</lastBuildDate>\n".
			"    <ttl>$rss_ttl</ttl>\n".
			"    <image>\n".
			"      <url>".view_atmurl($cmslogo)."</url>\n".
			"      <title>".mhtmlspecialchars($cmsname)."</title>\n".
			"      <link>".$cms_abs."</link>\n".
			"    </image>\n";
$cachefile = htmlcac_dir('rss','',1).cac_namepre('rss',$cnstr).'.php';

if($timestamp - @filemtime($cachefile) > $rss_ttl * 60){
	$rsscaches = rss_cache($sqlstr);
}else{
	include $cachefile;
}
foreach($rsscaches as $aid => $archive){
	$archive['arcurl'] = view_arcurl($archive);
	$rss_str .= "    <item>\n".
				"      <title>".mhtmlspecialchars($archive['subject'])."</title>\n".
				"      <link>$archive[arcurl]</link>\n".
				"      <description><![CDATA[".mhtmlspecialchars($archive['abstract'])."]]></description>\n".
				"      <category>$cn_name</category>\n".
				"      <author>".mhtmlspecialchars($archive['mname'])."</author>\n".
				"      <pubDate>".date('r',$archive['createdate'])."</pubDate>\n".
				"    </item>\n";

}
$rss_str .= "  </channel>\n".
			"</rss>";
mheader("Content-type: application/xml");
echo $rss_str;
exit;
function rss_cache($sqlstr=''){
	global $db,$tblprefix,$rss_num,$sqlstr,$cnstr,$cotypes;
	$sqlstr = "WHERE checked=1".(empty($sqlstr) ? '' : ' AND ').$sqlstr;
	$query = $db->query("SELECT * FROM {$tblprefix}archives $sqlstr ORDER BY aid DESC LIMIT 0,$rss_num");
	while($row = $db->fetch_array($query)){
		$row['abstract'] = cutstr(html2text($row['abstract']),255);
		$rsscaches[$row['aid']] = $row;
	}
	rsscache2file($rsscaches,cac_namepre('rss',$cnstr));
	return $rsscaches;
}
function rsscache2file($array,$cachefile){
	$cachestr = var_export($array,TRUE);
	$cachedir = htmlcac_dir('rss','',1);
	if(@$fp = fopen("$cachedir$cachefile.php", 'wb')) {
		fwrite($fp, "<?php\n\$rsscaches = $cachestr ;\n?>");
		fclose($fp);
	}else{
		exit('Can not write to cache files.');
	}
}

?>