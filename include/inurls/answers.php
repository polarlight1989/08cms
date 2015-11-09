<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('arangeset'));//只需要筛选文档模型即可
	$checkedarr = array('-1' => lang('nolimit').lang('adopt'),'0' => lang('noadopt'),'1' => lang('adopted'));
	trbasic(lang('arange').lang('adopt_state'),'',makeradio('inurlnew[setting][checked]',$checkedarr,!isset($inurl['setting']['checked']) ? '-1' : $inurl['setting']['checked']),'');
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"inurlnew[tplname]\" name=\"inurlnew[tplname]\" value=\"$inurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"inurlnew[onlyview]\" id=\"inurlnew[onlyview]\" value=\"1\"".(empty($inurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	$filtersarr = array(
	'check' => lang('adopt_state'),
	);
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($inurl['setting']['filters']) ? array() : explode(',',$inurl['setting']['filters']),5),'',lang('agnoselect1'));

	$listsarr = array(
	'mname' => lang('member'),
	'check' => lang('adopt_state'),
	'award' => lang('award'),
	'adddate' => lang('add_time'),
	'edit' => lang('edit'),//合辑管理工具
	);
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($inurl['setting']['lists']) ? array() : explode(',',$inurl['setting']['lists']),5),'',lang('agnoselect1'));

}else{
	foreach(array('filters','lists',) as $var){
		$inurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$inurlnew['url'] = "?entry=inarchive&action=answers&niuid=$iuid&aid=";
}
?>