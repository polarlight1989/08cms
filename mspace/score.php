<?php
include_once dirname(dirname(__FILE__)).'/include/general.inc.php';
include_once M_ROOT.'./include/common.fun.php';
$forward = empty($forward) ? M_REFERER : $forward;

$inajax = empty($inajax) ? 0 : 1;
$mid = empty($mid) ? 0 : max(0,intval($mid));
if(!$mid) cumessage('choosemember');

if(!($mcommu = read_cache('mcommu',1))) cumessage('choosecommuitem');
if(empty($mcommu['ucadd'])){
	if(empty($mcommu['available'])) cumessage('scorefunclosed');
	if(!$curuser->pmbypmids('cuadd',$mcommu['setting']['apmid'])) cumessage('younoscorepermis',$forward);
	
	if(!empty($mcommu['setting']['norepeat']) || !empty($mcommu['setting']['repeattime'])){
		if(empty($m_cookie['08cms_mcuid_1_'.$mid])){
			msetcookie('08cms_mcuid_1_'.$mid,'1',!empty($mcommu['setting']['norepeat']) ? 365 * 24 * 3600 : $mcommu['setting']['repeattime'] * 60);
		}else cumessage(empty($mcommu['setting']['norepeat']) ? 'overquick' : 'dontrepeatscore',$forward);
	}
	
	$actuser = new cls_userinfo;
	$actuser->activeuser($mid,1);
	if(!$actuser->info['mid']) cumessage('choosemember',$forward);
	
	$score = empty($score) ? 0 : max(0,intval($score));
	$score = max(1,min(5,$score));
	
	//统计原有评分数
	$counts = 0;
	for($i = 1;$i <=5;$i ++) $counts += $actuser->info['mscores'.$i];
	
	$actuser->updatefield('mscores'.$score,$actuser->info['mscores'.$score] + 1,'sub');
	$actuser->updatefield('mavgscore',round(($counts * $actuser->info['mavgscore'] + $score) / ($counts + 1),2),'sub');//平均分
	$actuser->updatedb();
	
	cumessage($inajax ? 'succeed' : 'scoresucceed',$forward);
}else include(M_ROOT.$mcommu['ucadd']);
?>