<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"inurlnew[tplname]\" name=\"inurlnew[tplname]\" value=\"$inurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"inurlnew[onlyview]\" id=\"inurlnew[onlyview]\" value=\"1\"".(empty($inurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	$listsarr = array(
	'mname' => lang('member'),
	'adddate' => lang('add_time'),
	'edit' => lang('edit'),//合辑管理工具
	);
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($inurl['setting']['lists']) ? array() : explode(',',$inurl['setting']['lists']),5),'',lang('agnoselect1'));

}else{
	foreach(array('lists',) as $var){
		$inurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$inurlnew['url'] = "?entry=inarchive&action=reports&niuid=$iuid&aid=";
}
?>