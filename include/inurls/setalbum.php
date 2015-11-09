<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('arangeset'));
	trbasic(lang('arange').lang('achannel')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallchids\" onclick=\"checkall(this.form,'chidsnew','chkallchids')\">".lang('selectall'),'',makecheckbox('chidsnew[]',chidsarr(1),empty($inurl['setting']['chids']) ? array() : explode(',',$inurl['setting']['chids']),5),'',lang('agnoselect'));
	trbasic(lang('arange').lang('subsite')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallsids\" onclick=\"checkall(this.form,'sidsnew','chkallsids')\">".lang('selectall'),'',makecheckbox('sidsnew[]',array('m' => lang('msite')) + sidsarr(),empty($inurl['setting']['sids']) ? array() : explode(',',$inurl['setting']['sids']),5),'',lang('agnoselect'));
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"inurlnew[tplname]\" name=\"inurlnew[tplname]\" value=\"$inurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"inurlnew[onlyview]\" id=\"inurlnew[onlyview]\" value=\"1\"".(empty($inurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	$filtersarr = array(
	'channel' => lang('channel'),
	'subsite' => lang('subsite'),
	'catalog' => lang('catalog'),
	);
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($inurl['setting']['filters']) ? array() : explode(',',$inurl['setting']['filters']),5),'',lang('agnoselect1'));

	$listsarr = array(
	'id' => lang('album').lang('id'),
	'mname' => lang('member'),
	'catalog' => lang('catalog'),
	'channel' => lang('channel'),
	'subsite' => lang('subsite'),
	'view' => lang('view_info'),//显示详细信息
	);
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($inurl['setting']['lists']) ? array() : explode(',',$inurl['setting']['lists']),5),'',lang('agnoselect1'));
}else{
	foreach(array('chids','sids','filters','lists',) as $var){
		$inurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$inurlnew['url'] = "?entry=inarchive&action=setalbum&niuid=$iuid&aid=";
}
?>