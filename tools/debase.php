<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/arcedit.cls.php';
$forward = empty($forward) ? M_REFERER : $forward;

$inajax = empty($inajax) ? 0 : 1;
$aid = empty($aid) ? 0 : max(0,intval($aid));
!$aid && cumessage('choosearchive');
!($commu = read_cache('commu',1)) && cumessage('choosecommuitem');
if(empty($commu['ucadd'])){
	!$curuser->pmbypmids('cuadd',$commu['setting']['apmid']) && cumessage('younoitempermis',$forward);
	if(empty($commu['setting']['repeat']) || !empty($commu['setting']['repeattime'])){
		if(empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_'.$aid])){
			msetcookie('08cms_cuid_'.$commu['cuid'].'_'.$aid,'1',empty($commu['setting']['repeat']) ? 365 * 24 * 3600 : $commu['setting']['repeattime'] * 60);
		}else cumessage(!empty($commu['setting']['repeat']) ? 'overquick' : 'norepeatoper',$forward);
	}
	
	$aedit = new cls_arcedit();
	$aedit->set_aid($aid);
	$aedit->basic_data();
	!$aedit->aid && cumessage('choosearchive');
	!$aedit->archive['checked'] && cumessage('poinarcnoche'); 
	$aedit->arc_nums('debases',1,1);
	$curuser->basedeal('commu',1,1,1);
	cumessage($inajax ? 'succeed' : 'operatesucceed',$forward);
}else include(M_ROOT.$commu['ucadd']);
?>