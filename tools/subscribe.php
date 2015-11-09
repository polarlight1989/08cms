<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/archive.cls.php';
$inajax = empty($inajax) ? 0 : 1;
$aid = empty($aid) ? 0 : max(0,intval($aid));
$isatm = empty($isatm) ? 0 : 1;
!$aid && cumessage('confchoosarchi');
!$memberid && cumessage('nousernosubper');

$commu = read_cache('commu',8);
empty($commu) && cumessage('choosecommuitem');
if(empty($commu['ucadd'])){
	!$curuser->pmbypmids('cuadd',$commu['setting']['apmid']) && cumessage('younoitempermis');
	$arc = new cls_archive();
	!$arc->arcid($aid) && cumessage('choosearchive');
	!$arc->archive['checked'] && cumessage('poinarcnoche'); 
	switch_cache($arc->archive['sid']);
	$sid = $arc->archive['sid'];
	
	$stritem = $isatm ? 'attachment' : 'archive';
	if(!($crids = $arc->arc_crids($isatm))) cumessage("youalrpurchasestritem",'',$stritem); 
	
	$cridstr = '';
	foreach($crids['total'] as $k => $v) $cridstr .= ($cridstr ? ',' : '').abs($v).$currencys[$k]['unit'].$currencys[$k]['cname'];
	if(!$curuser->crids_enough($crids['total'])) cumessage('younopurcstriwanenocurr','',$stritem);
	$curuser->updatecrids($crids['total'],0,lang("purchasestritem",$stritem));
	$curuser->payrecord($arc->aid,$isatm,$cridstr,1);
	if(!empty($crids['sale'])){
		$actuser = new cls_userinfo;
		$actuser->activeuser($arc->archive['mid']);
		foreach($crids['sale'] as $k => $v) $crids['sale'][$k] = -$v;
		$actuser->updatecrids($crids['sale'],1,lang("salestritem",$stritem));
		unset($actuser);
	}
	cumessage($inajax ? 'succeed' : 'operatesucceed');
}else include(M_ROOT.$commu['ucadd']);
?>