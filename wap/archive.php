<?php
define('WAP_MODE', 1);
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include(M_ROOT.'wap/wap.fun.php');
$page = empty($page) ? 1 : max(1, intval($page));
empty($aid) && message('wap_choosearchive');
$aid = max(0,intval($aid));
$arc = new cls_archive();
if(!$arc->arcid($aid)) message('wap_choosearchive');
if(!$arc->archive['checked'] && !$curuser->isadmin()) message('wap_poinarcnoche'); 
$addno = empty($addno) ? 0 : max(0,intval($addno));
if($addno > $arc->channel['addnum']) $addno = 0;
//分析所在子站
switch_cache($arc->archive['sid']);
$sid = $arc->archive['sid'];
if_siteclosed($sid);

//分析权限与扣积分，文章出售
if(!arc_allow($arc->archive,'aread')) message('wap_noarchbrowsperm');
if($crids = $arc->arc_crids()){//需要对当前用户扣值
	message('wap_cantdeductions');
	$cridstr = '';
	foreach($crids['total'] as $k => $v) $cridstr .= ($cridstr ? ',' : '').abs($v).$currencys[$k]['unit'].$currencys[$k]['cname'];
	if(!$curuser->crids_enough($crids['total'])) message('wap_cantdeductions');
	$curuser->updatecrids($crids['total'],0,lang('subscribearchive'));
	$curuser->payrecord($arc->aid,0,$cridstr,1);
	if(!empty($crids['sale'])){
		$actuser = new cls_userinfo;
		$actuser->activeuser($arc->archive['mid']);
		foreach($crids['sale'] as $k => $v) $crids['sale'][$k] = -$v;
		$actuser->updatecrids($crids['sale'],1,lang('salearchive'));
		unset($actuser);
	}
}
//分析模板来源
$tplname = arc_tplname($addno,'',$arc->channel['warctpls']);
$tplname || message('wap_definereltem');

$arc->detail_data();
$durlpre = $arc->urlpre($addno);
$_da = &$arc->archive;
arc_parse($_da);
$_mp = array();
$_mp['durlpre'] = $durlpre;
$_mp['static'] = 0;
$_mp['nowpage'] = max(1,intval($page));
_aenter($_da,1);
@extract($btags);
extract($_da,EXTR_OVERWRITE);
tpl_refresh($tplname);
include M_ROOT."template/$templatedir/pcache/$tplname.php";

$_content = ob_get_contents();
ob_clean();
wap_exit($_content);
?>