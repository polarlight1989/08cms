<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
parse_str(un_virtual($_SERVER['QUERY_STRING']),$temparr);
$cnstr = mcnstr($temparr);
$addno = max(0,intval(@$temparr['addno']));
$page = max(1, intval(@$temparr['page']));
if(!$cnode = read_mcnode($cnstr)){
	$cnstr = '';
	$addno = 0;
}

$cache1circle && $cachefile = htmlcac_dir('mcn','',1).cac_namepre($cnstr,$addno).'_'.$page.'.php';
if($cache1circle && is_file($cachefile) && (filemtime($cachefile) > ($timestamp - $cache1circle * 60))) mexit(read_htmlcac($cachefile));

$_da = $temparr;
if($cnstr){
	$_da += m_cnparse($cnstr);
	$_da += mcnodearr($cnstr);
	$tplname = mcn_tplname($cnstr,$addno);
	$statics = empty($cnode['statics']) ? array() : explode(',',$cnode['statics']);
	$enablestatic = empty($statics[$addno]) ? $enablestatic : ($statics[$addno] == 1 ? 0 : 1);
}else $tplname = @$m_index_tpl;
empty($tplname) && message('definereltem');

$_mp = array(
'durlpre' => $memberurl.en_virtual("index.php?".substr(($cnstr ? "&$cnstr" : '').($addno ? "&addno=$addno" : '').'&page={$page}',1),1),
'static' => 0,
'nowpage' => $page,
);
_aenter($_da,1);
@extract($btags);
extract($_da,EXTR_OVERWRITE);
$sid = 0;
tpl_refresh($tplname);
@include M_ROOT."template/$templatedir/pcache/$tplname.php";
$_content = ob_get_contents();
ob_clean();
if($enablestatic){
	$_content .= "<script language=\"javascript\" src=\"".$cms_abs."tools/static.php?mode=mcnode".($cnstr ? "&$cnstr" : '').($addno ? "&addno=$addno" : '')."\"></script>";
}elseif($cache1circle) save_htmlcac($_content,$cachefile);
mexit($_content);


?>