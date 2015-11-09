<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	load_cache('fcatalogs');
	tabfooter();

	tabheader(lang('arangeset'));
	$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheck'),'1' => lang('checked'));
	trbasic(lang('arange').lang('check_state'),'',makeradio('murlnew[setting][checked]',$checkedarr,!isset($murl['setting']['checked']) ? '-1' : $murl['setting']['checked']),'');
	$validarr = array('-1' => lang('nolimit'),'0' => lang('invalid'),'1' => lang('available'));
	trbasic(lang('arange').lang('validperiod_state'),'',makeradio('murlnew[setting][valid]',$validarr,!isset($murl['setting']['valid']) ? '-1' : $murl['setting']['valid']),'');
	trbasic(lang('arange').lang('coclass')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallcaids\" onclick=\"checkall(this.form,'caidsnew','chkallcaids')\">".lang('selectall'),'',makecheckbox('caidsnew[]',fcaidsarr(),empty($murl['setting']['caids']) ? array() : explode(',',$murl['setting']['caids']),5),'',lang('agnoselect'));
	tabfooter();

	tabheader(lang('pageresult'));
	trbasic(lang('customapage'),'murlnew[tplname]',$murl['tplname'],'text',lang('agcustomapage'));
	$filtersarr = array(
	'catalog' => lang('coclass'),
	'check' => lang('check_state'),
	'valid' => lang('validperiod_state'),
	'qstate' => lang('qstate'),
	);
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($murl['setting']['filters']) ? array() : explode(',',$murl['setting']['filters']),5),'',lang('agnoselect1'));

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
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($murl['setting']['lists']) ? array() : explode(',',$murl['setting']['lists']),5),'',lang('agnoselect1'));
	$operatesarr = array(
	'delete' => lang('delete'),
	'check' => lang('check'),
	'uncheck' => lang('uncheck'),
	'static' => lang('static'),
	'unstatic' => lang('unstatic'),
	);
	trbasic(lang('view_operates')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalloperates\" onclick=\"checkall(this.form,'operatesnew','chkalloperates')\">".lang('selectall'),'',makecheckbox('operatesnew[]',$operatesarr,empty($murl['setting']['operates']) ? array() : explode(',',$murl['setting']['operates']),5),'',lang('agnoselect1'));
	trbasic(lang('adm_title'),'murlnew[mtitle]',$murl['mtitle'],'text',lang('aga_title'));
	trbasic(lang('adm_guide'),'murlnew[guide]',$murl['guide'],'textarea',lang('aga_title'));
}else{
	foreach(array('caids','filters','lists','operates',) as $var){
		$murlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$murlnew['url'] = "?action=farchives&nmuid=$muid";
}
?>