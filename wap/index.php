<?php
define('WAP_MODE', 1);
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include(M_ROOT.'wap/wap.fun.php');
parse_str($_SERVER['QUERY_STRING'],$temparr);
//处理子站id
$nsid = max(0,intval($sid));
$addno = max(0,intval(@$addno));
$page = max(1, intval(@$page));

if($nsid && empty($subsites[$nsid])) $nsid = 0;
switch_cache($nsid);
$sid = $nsid;
if_siteclosed($sid);
@extract($btags);

$cnstr = cnstr($temparr);
if($cnstr && ($cnode = cnodearr($cnstr,$sid))){
	if(!$curuser->pmbypmids('cread',cn_pmids($cnstr,$sid))) message('wap_nocatabrowseperm');
}else{
	$cnstr = '';
	$addno = 0;
}

$_da = array();
if(!$cnstr){
	$tplname = !$sid ? $w_index_tpl : $btags['w_index_tpl'];
	$_da['rss'] = $cms_abs.'rss.php'.($sid ? "?sid=$sid" : '');
}else{
	$_da = cn_parse($cnstr,$sid,-1);
	re_cnode($_da,$cnstr,$cnode);
	$tplname = cn_tplname($cnstr,$cnode,$addno);
}
empty($tplname) && message('definereltem');

$_mp = array(
'durlpre' => view_url("wap/index.php?".substr(($cnstr ? "&$cnstr" : '').($addno ? "&addno=$addno" : '').($sid ? "&sid=$sid" : '').'&page={$page}',1)),
'static' => 0,
'nowpage' => $page,
);
_aenter($_da,1);
extract($_da,EXTR_OVERWRITE);
tpl_refresh($tplname);
@include M_ROOT."template/$templatedir/pcache/$tplname.php";
$_content = ob_get_contents();
ob_clean();
wap_exit($_content);
?>