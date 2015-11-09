<?php
include_once './../include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.cls.php';
$cmsclosed && message(empty($cmsclosedreason) ? lang('defaultclosedreason') : mnl2br($cmsclosedreason));
$mspacedisabled && message(lang('mspacedisabled'));
parse_str(un_virtual($_SERVER['QUERY_STRING']));
$mid = empty($mid) ? 0 : max(0,intval($mid));
$cid = empty($cid) ? 0 : max(0,intval($cid));
$page = empty($page) ? 1 : max(1, intval($page));
empty($cid) && message('chooseproduct');
include_once M_ROOT.'./include/mparse.fun.php';

if($cachemscircle && (!$mslistcachenum || $page <= $mslistcachenum)){
	$cachefile = htmlcac_dir('ms','m'.($mid % 100),1).cac_namepre($mid,'product'.$cid).'_'.$page.'.php';
	if(is_file($cachefile) && (filemtime($cachefile) > ($timestamp - $cachemscircle * 60))){
		mexit(read_htmlcac($cachefile));
	}
}

if(!$_offer = $db->fetch_one("SELECT * FROM {$tblprefix}offers WHERE cid='$cid' AND mid='$mid'")) message('chooseproduct');

$arc = new cls_archive();
if(!$arc->arcid($_offer['aid'])) message('choosearchive');
if(!$arc->archive['checked'] && !$curuser->isadmin()) message('poinarcnoche'); 

$tplname = ms_arctpl($arc->archive['chid'],'product');
if(!$tplname) message('definereltem');
$arc->detail_data();
$_arc = &$arc->archive;
arc_parse($_arc);

$_mp = array();
$_mp['durlpre'] = $mspaceurl.en_virtual('product.php?mid='.$mid.'&cid='.$cid.'&page={$page}',1);
$_mp['static'] = 0;
$_mp['nowpage'] = max(1,intval($page));
_aenter($_arc,1);
_aenter($_da);
_aenter($_offer);
@extract($btags);
extract($_arc,EXTR_OVERWRITE);
extract($_da,EXTR_OVERWRITE);
extract($_offer,EXTR_OVERWRITE);
$sid = 0;
tpl_refresh($tplname);
@include M_ROOT."template/$templatedir/pcache/$tplname.php";

$_content = ob_get_contents();
ob_clean();
if($cachemscircle && (!$mslistcachenum || $page <= $mslistcachenum)) save_htmlcac($template,$_content);
mexit($_content);
?>