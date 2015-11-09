<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('arangeset'));
	$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheck'),'1' => lang('checked'));
	trbasic(lang('arange').lang('check_state'),'',makeradio('aurlnew[setting][checked]',$checkedarr,!isset($aurl['setting']['checked']) ? '-1' : $aurl['setting']['checked']),'');
	foreach($grouptypes as $k => $v){
		global ${'ugidsnew'.$k};
		$ugidsarr = array(0 => lang('nousergroup'));
		$usergroups = read_cache('usergroups',$k);
		foreach($usergroups as $k1 => $v1) $ugidsarr[$k1] = $v1['cname'];
		trbasic(lang('arange').$v['cname']."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallugids$k\" onclick=\"checkall(this.form,'ugidsnew$k','chkallugids$k')\">".lang('selectall'),'',makecheckbox('ugidsnew'.$k.'[]',$ugidsarr,empty($aurl['setting']["ugids$k"]) ? array() : explode(',',$aurl['setting']["ugids$k"]),5),'',lang('agnoselect'));
	}
	unset($usergroups);
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"aurlnew[tplname]\" name=\"aurlnew[tplname]\" value=\"$aurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"aurlnew[onlyview]\" id=\"aurlnew[onlyview]\" value=\"1\"".(empty($aurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	$filtersarr = array(
	'mchannel' => lang('memtype'),
	'check' => lang('check_state'),
	'date' => lang('reg_date'),
	);
	foreach($grouptypes as $k => $v) $filtersarr["ugid$k"] = $v['cname'];
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($aurl['setting']['filters']) ? array() : explode(',',$aurl['setting']['filters']),5),'',lang('agnoselect1'));

	$listsarr = array(
	'mchannel' => lang('memtype'),
	'check' => lang('check_state'),
	'regip' => lang('regip'),
	'regdate' => lang('reg_date'),
	'lastvisit' => lang('recentvisit'),
	'edit' => lang('edit'),
	'usergroup' => lang('set').lang('usergroup'),
	'allowance' => lang('set').lang('allowance'),
	);
	foreach($grouptypes as $k => $v) $listsarr["ugid$k"] = $v['cname'];
	$listsarr["currency0"] = lang('cash');
	foreach($currencys as $k => $v) $listsarr["currency$k"] = $v['cname'];
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($aurl['setting']['lists']) ? array() : explode(',',$aurl['setting']['lists']),5),'',lang('agnoselect1'));
	$operatesarr = array(
	'delete' => lang('delete'),
	'check' => lang('check'),
	'uncheck' => lang('uncheck'),
	'arcallowance' => lang('arcallows'),
	'cuallowance' => lang('cuallows'),
	);
	foreach($grouptypes as $k => $v) if($v['mode'] < 2) $operatesarr["ugid$k"] = lang('set').$v['cname'];
	trbasic(lang('view_operates')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalloperates\" onclick=\"checkall(this.form,'operatesnew','chkalloperates')\">".lang('selectall'),'',makecheckbox('operatesnew[]',$operatesarr,empty($aurl['setting']['operates']) ? array() : explode(',',$aurl['setting']['operates']),5),'',lang('agnoselect1'));
}else{
	foreach(array('filters','lists','operates',) as $var){
		$aurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	foreach($grouptypes as $k => $v){
		$aurlnew['setting']['ugids'.$k] = empty(${'ugidsnew'.$k}) ? '' : implode(',',${'ugidsnew'.$k});
	}
	$aurlnew['url'] = "?entry=members&action=membersedit&nauid=$auid";
}
?>