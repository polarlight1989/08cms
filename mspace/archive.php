<?php
include_once './../include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.cls.php';
$cmsclosed && message(empty($cmsclosedreason) ? lang('defaultclosedreason') : mnl2br($cmsclosedreason));
$mspacedisabled && message(lang('mspacedisabled'));
parse_str(un_virtual($_SERVER['QUERY_STRING']));
$mid = empty($mid) ? 0 : max(0,intval($mid));
$aid = empty($aid) ? 0 : max(0,intval($aid));
$page = empty($page) ? 1 : max(1, intval($page));
$addno = empty($addno) ? 0 : min($arcplusnum,max(0,intval($addno)));
$addno = empty($addno) ? '' : $addno;
empty($aid) && message('choosearchive');
include_once M_ROOT.'./include/mparse.fun.php';

if($cachemscircle && (!$mslistcachenum || $page <= $mslistcachenum)){
	$cachefile = htmlcac_dir('ms','m'.($mid % 100),1).cac_namepre($mid,'archive'.$aid.$addno).'_'.$page.'.php';
	if(is_file($cachefile) && (filemtime($cachefile) > ($timestamp - $cachemscircle * 60))){
		mexit(read_htmlcac($cachefile));
	}
}

$arc = new cls_archive();
if(!$arc->arcid($aid)) message('choosearchive');
if(!$arc->archive['checked'] && !$curuser->isadmin()) message('poinarcnoche');

$tplname = ms_arctpl($arc->archive['chid'],'archive');
if(!$tplname) message('definereltem');
$arc->detail_data();
$durlpre = $arc->m_urlpre($addno);
$_arc = &$arc->archive;
arc_parse($_arc);

$_mp = array();
$_mp['durlpre'] = $durlpre;
$_mp['static'] = 0;
$_mp['nowpage'] = max(1,intval($page));
_aenter($_arc,1);
_aenter($_da);
@extract($btags);
extract($_arc,EXTR_OVERWRITE);
extract($_da,EXTR_OVERWRITE);
$sid = 0;
tpl_refresh($tplname);
@include M_ROOT."template/$templatedir/pcache/$tplname.php";

$_content = ob_get_contents();
ob_clean();
if($cachemscircle && (!$mslistcachenum || $page <= $mslistcachenum)) save_htmlcac($template,$_content);
mexit($_content);

?>