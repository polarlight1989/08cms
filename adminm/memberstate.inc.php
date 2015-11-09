<?php
!defined('M_COM') && exit('No Permission');
load_cache('currencys');
$curuser->sub_data();
tabheader(lang('basestate'));
trbasic(lang('membercheckstate'),'',$curuser->info['checked'] ? lang('checked') : lang('checking'),'');
trbasic(lang('memberregtime'),'',$curuser->info['regdate'] ? date("$dateformat   $timeformat",$curuser->info['regdate']) : '','');
trbasic(lang('memberregip'),'',$curuser->info['regip'] ? $curuser->info['regip'] : '-','');
trbasic(lang('lastlogintime'),'',$curuser->info['lastvisit'] ? date("$dateformat   $timeformat",$curuser->info['lastvisit']) : '','');
trbasic(lang('lastactivetime'),'',$curuser->info['lastactive'] ? date("$dateformat   $timeformat",$curuser->info['lastactive']) : '','');
trbasic(lang('lastloginip'),'',$curuser->info['lastip'] ? $curuser->info['lastip'] : '-','');
trbasic(lang('memberclicks'),'',$curuser->info['clicks'],'');
tabfooter();
tabheader(lang('otherstate'));
trbasic(lang('addarcamount1'),'',$curuser->info['archives'],'');
trbasic(lang('issuearcamount1'),'',$curuser->info['checks'],'');
trbasic(lang('membercomments1'),'',$curuser->info['comments'],'');
trbasic(lang('arcsubscribeamount1'),'',$curuser->info['subscribes'],'');
trbasic(lang('adjsubscribeamount1'),'',$curuser->info['fsubscribes'],'');
trbasic(lang('uploadedadjunct1'),'',$curuser->info['uptotal'],'');
trbasic(lang('downloadedadjunct1'),'',$curuser->info['downtotal'],'');
tabfooter();
tabheader(lang('membercurrency'));
trbasic(lang('cashaccount'),'',$curuser->info['currency0'].lang('yuan'),'');
foreach($currencys as $crid => $currency){
	trbasic($currency['cname'],'',$curuser->info['currency'.$crid].$currency['unit'],'');
}
tabfooter();
tabheader(lang('memberstate'),'','',4);
foreach($grouptypes as $gtid => $grouptype){
	$usergroups = read_cache('usergroups',$gtid);
	$curuser->info['grouptype'.$gtid.'date'] = !$curuser->info['grouptype'.$gtid.'date'] ? '-' : date('Y-m-d',$curuser->info['grouptype'.$gtid.'date']);
	echo "<tr>\n".
		"<td width=\"15%\" class=\"item1\">$grouptype[cname]</td>\n".
		"<td width=\"35%\" class=\"item2\">".(!$curuser->info['grouptype'.$gtid] ? '-' : $usergroups[$curuser->info['grouptype'.$gtid]]['cname'])."</td>\n".
		"<td width=\"15%\" class=\"item1\">".lang('enddate')."</td>\n".
		"<td width=\"35%\" class=\"item2\">".$curuser->info['grouptype'.$gtid.'date']."</td>\n".
		"</tr>";
}
tabfooter();
?>