<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
include_once M_ROOT.'./include/arcedit.cls.php';

$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$inajax = empty($inajax) ? 0 : 1;
$aid = empty($aid) ? 0 : max(0,intval($aid));
!$aid && cumessage('choosearchive');
!($commu = read_cache('commu',2)) && cumessage('choosecommuitem');
if(empty($commu['ucadd'])){
	!$curuser->pmbypmids('cuadd',$commu['setting']['apmid']) && cumessage('younoscorepermis');
	$score = empty($score) ? 0 : max(0,intval($score));
	$scorearr = empty($commu['setting']['scorestr']) ? array() : array_filter(explode(',',$commu['setting']['scorestr']));
	if(!in_array($score,$scorearr)) cumessage('scoreoptionerr');
	if(empty($commu['setting']['repeat']) || !empty($commu['setting']['repeattime'])){
		if(empty($m_cookie['08cms_cuid_'.$commu['cuid'].'_'.$aid])){
			msetcookie('08cms_cuid_'.$commu['cuid'].'_'.$aid,'1',empty($commu['setting']['repeat']) ? 365 * 24 * 3600 : $commu['setting']['repeattime'] * 60);
		}else cumessage(empty($commu['setting']['repeat']) ? 'norepeatoper' : 'overquick',$forward);
	}
	$aedit = new cls_arcedit();
	$aedit->set_aid($aid);
	$aedit->basic_data();
	!$aedit->aid && cumessage('choosearchive');
	!$aedit->archive['checked'] && cumessage('poinarcnoche'); 
	
	$aedit->updatefield('avgscore',round(($aedit->archive['avgscore'] * $aedit->archive['scores'] + $score) / ($aedit->archive['scores'] + 1),2),'main');//平均分
	if(!empty($commu['setting']['pics']) && isset($aedit->archive['score_'.$score])) $aedit->updatefield('score_'.$score,$aedit->archive['score_'.$score] + 1,'main');
	$aedit->arc_nums('scores',1,1);//文档统计
	$curuser->basedeal('score',1,1,1);//会员统计及积分
	cumessage($inajax ? 'succeed' : 'scoresucceed');
}else include(M_ROOT.$commu['ucadd']);
?>