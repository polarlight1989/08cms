<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('arangeset'));
	$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheck'),'1' => lang('checked'));
	trbasic(lang('arange').lang('check_state'),'',makeradio('aurlnew[setting][checked]',$checkedarr,!isset($aurl['setting']['checked']) ? '-1' : $aurl['setting']['checked']),'');
	$validarr = array('-1' => lang('nolimit'),'0' => lang('invalid'),'1' => lang('available'));
	trbasic(lang('arange').lang('validperiod_state'),'',makeradio('aurlnew[setting][valid]',$validarr,!isset($aurl['setting']['valid']) ? '-1' : $aurl['setting']['valid']),'');
	trbasic(lang('arange').lang('isconsult'),'aurlnew[setting][consult]',empty($aurl['setting']['consult']) ? 0 : 1,'radio');
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"aurlnew[tplname]\" name=\"aurlnew[tplname]\" value=\"$aurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"aurlnew[onlyview]\" id=\"aurlnew[onlyview]\" value=\"1\"".(empty($aurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	trbasic(lang('adm_title'),'aurlnew[mtitle]',$aurl['mtitle'],'text',lang('aga_title'));
	trbasic(lang('adm_guide'),'aurlnew[guide]',$aurl['guide'],'textarea',lang('aga_title'));
	$filtersarr = array(
	'check' => lang('check_state'),
	'valid' => lang('validperiod_state'),
	'qstate' => lang('qstate'),
	);
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($aurl['setting']['filters']) ? array() : explode(',',$aurl['setting']['filters']),5),'',lang('agnoselect1'));

	$listsarr = array(
	'catalog' => lang('coclass'),
	'mname' => lang('member'),
	'check' => lang('check_state'),
	'valid' => lang('validperiod_state'),
	'qstate' => lang('qstate'),
	'adddate' => lang('add_time'),
	'updatedate' => lang('update_time'),
	'startdate' => lang('startdate'),
	'enddate' => lang('end1_time'),
	'vieworder' => lang('order'),//显示详细信息
	'qadmin' => lang('qadmin'),//咨询管理
	'edit' => lang('edit'),//编辑
	);
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($aurl['setting']['lists']) ? array() : explode(',',$aurl['setting']['lists']),5),'',lang('agnoselect1'));
	$operatesarr = array(
	'delete' => lang('delete'),
	'check' => lang('check'),
	'uncheck' => lang('uncheck'),
	'static' => lang('static'),
	'unstatic' => lang('unstatic'),
	);
	trbasic(lang('view_operates')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalloperates\" onclick=\"checkall(this.form,'operatesnew','chkalloperates')\">".lang('selectall'),'',makecheckbox('operatesnew[]',$operatesarr,empty($aurl['setting']['operates']) ? array() : explode(',',$aurl['setting']['operates']),5),'',lang('agnoselect1'));
}else{
	foreach(array('filters','lists','operates',) as $var){
		$aurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$aurlnew['url'] = "?entry=farchives&action=farchivesedit&nauid=$auid";
}
?>