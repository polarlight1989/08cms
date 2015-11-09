<?php
!defined('M_COM') && exit('No Permission');
load_cache('currencys');
$mchid = $curuser->info['mchid'];
$cashgtids = array();
foreach($grouptypes as $k => $v){
	if($v['mode'] == 3 && !in_array($mchid,explode(',',$v['mchids']))){
		if(empty($gtid) || $gtid == $k) $cashgtids[$k] = $v;
	}
}
empty($cashgtids) && mcmessage('addcrexusergroup');
if(!submitcheck('bgtexchange')){
	foreach($cashgtids as $k => $v){
		$usergroups = read_cache('usergroups',$k);
		$ugidsarr = array();
		foreach($usergroups as $x => $y){
			if(in_array($mchid,explode(',',$y['mchids']))){
				$ugidsarr[$x] = $y['cname'].'('.$y['currency'].')';
				if($x == $curuser->info['grouptype'.$k]){
					if(!$curuser->info['grouptype'.$k.'date']) unset($ugidsarr[$x]);
					break;
				}
			}
		}
		$crname = empty($v['crid']) ? lang('cash') : $currencys[$v['crid']]['cname'];
		tabheader(lang('gtex',$crname,$v['cname']),'gtexchagne'.$k,"?action=gtexchange&gtid=$k");
		trbasic(lang('membercurrent',$crname),'',$curuser->info['currency'.$v['crid']],'');
		trbasic(lang('currentusergroup'),'',$curuser->info['grouptype'.$k] ? $usergroups[$curuser->info['grouptype'.$k]]['cname'] : '-','');
		trbasic(lang('curusergroupenddate'),'',$curuser->info['grouptype'.$k.'date'] ? date($dateformat,$curuser->info['grouptype'.$k.'date']) : '-','');
		$ugidsarr && trbasic(lang('exchangeusergroup'),'exchangeugid',makeoption($ugidsarr),'select');
		$ugidsarr ? tabfooter('bgtexchange',lang('exchange')) : tabfooter();
	}
}else{
	(empty($gtid) || empty($grouptypes[$gtid]) || in_array($mchid,explode(',',$grouptypes[$gtid]['mchids']))) && mcmessage('getgrouptype',M_REFERER);
	$grouptype = $grouptypes[$gtid];
	$crid = $grouptype['crid']; 
	$usergroups = read_cache('usergroups',$gtid);
	(empty($exchangeugid) || empty($usergroups[$exchangeugid]) || !in_array($mchid,explode(',',$usergroups[$exchangeugid]['mchids']))) && mcmessage('getusergroup',M_REFERER);
	$curuser->info['currency'.$crid] < $usergroups[$exchangeugid]['currency'] && mcmessage('noenoughcurrency',M_REFERER);
	$usergroup = read_cache('usergroup',$gtid,$exchangeugid);
	if($curuser->info['grouptype'.$gtid] == $exchangeugid){//续期
		if($usergroup['limitday'] && $curuser->info['grouptype'.$gtid.'date']){
			$curuser->updatefield('grouptype'.$gtid.'date',$curuser->info['grouptype'.$gtid.'date'] + $usergroup['limitday'] * 86400);
		}else{
			$curuser->updatefield('grouptype'.$gtid.'date',0);
		}
	}else{//变更
		$curuser->updatefield('grouptype'.$gtid,$exchangeugid);
		if($usergroup['limitday']){
			$curuser->updatefield('grouptype'.$gtid.'date',$timestamp + $usergroup['limitday'] * 86400);
		}else{
			$curuser->updatefield('grouptype'.$gtid.'date',0);
		}
		if($grouptypes[$gtid]['allowance']) $curuser->reset_allowance();//如果会员组变更分析限额变化
	}
	$curuser->updatecrids(array($crid => -$usergroup['currency']),1,lang('currencyexusergroup'));
	mcmessage('cyexusergroupfinish',M_REFERER);
}
?>
