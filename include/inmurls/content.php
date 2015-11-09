<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('arangeset'));
	trbasic(lang('arange').lang('achannel')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallchids\" onclick=\"checkall(this.form,'chidsnew','chkallchids')\">".lang('selectall'),'',makecheckbox('chidsnew[]',chidsarr(1),empty($inmurl['setting']['chids']) ? array() : explode(',',$inmurl['setting']['chids']),5),'',lang('agnoselect'));
	trbasic(lang('arange').lang('subsite')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallsids\" onclick=\"checkall(this.form,'sidsnew','chkallsids')\">".lang('selectall'),'',makecheckbox('sidsnew[]',array('m' => lang('msite')) + sidsarr(),empty($inmurl['setting']['sids']) ? array() : explode(',',$inmurl['setting']['sids']),5),'',lang('agnoselect'));
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"inmurlnew[tplname]\" name=\"inmurlnew[tplname]\" value=\"$inmurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"inmurlnew[onlyview]\" id=\"inmurlnew[onlyview]\" value=\"1\"".(empty($inmurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	$filtersarr = array(
	'channel' => lang('achannel'),
	'subsite' => lang('subsite'),
	'catalog' => lang('catalog'),
	);
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($inmurl['setting']['filters']) ? array() : explode(',',$inmurl['setting']['filters']),5),'',lang('agnoselect1'));

	$listsarr = array(
	'mname' => lang('member'),
	'catalog' => lang('catalog'),
	'channel' => lang('arctype'),
	'subsite' => lang('subsite'),
	'check' => lang('check_state'),
	'incheck' => lang('incheck'),
	'clicks' => lang('clicks'),
	'comments' => lang('comments'),
	'replys' => lang('replys'),
	'offers' => lang('offers'),
	'orders' => lang('order_num'),
	'ordersum' => lang('ordersum'),
	'favorites' => lang('favorites'),
	'praises' => lang('praises'),
	'debases' => lang('debases'),
	'answers' => lang('answers'),
	'adopts' => lang('adopts'),
	'closed' => lang('closed'),
	'downs' => lang('downs'),
	'price' => lang('goods_price'),
	'currency' => lang('reward_currency'),
	'adddate' => lang('add_time'),
	'updatedate' => lang('update_time'),
	'refreshdate' => lang('readd_time'),
	'enddate' => lang('end1_time'),
	'view' => lang('view_info'),//显示详细信息
	'edit' => lang('edit'),//合辑管理工具
	);
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($inmurl['setting']['lists']) ? array() : explode(',',$inmurl['setting']['lists']),5),'',lang('agnoselect1'));
	$operatesarr = array(
	'delete' => lang('delete'),
	'inclear' => lang('inclear'),
	'incheck' => lang('incheck'),
	'inuncheck' => lang('inuncheck'),
	'abover' => lang('setting_album_abover'),
	'unabover' => lang('cancel_album_abover'),
	'readd' => lang('archive_readd'),
	);
	trbasic(lang('view_operates')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalloperates\" onclick=\"checkall(this.form,'operatesnew','chkalloperates')\">".lang('selectall'),'',makecheckbox('operatesnew[]',$operatesarr,empty($inmurl['setting']['operates']) ? array() : explode(',',$inmurl['setting']['operates']),5),'',lang('agnoselect1'));
	trbasic(lang('adm_title'),'inmurlnew[mtitle]',$inmurl['mtitle'],'text',lang('aga_title'));
	trbasic(lang('adm_guide'),'inmurlnew[guide]',$inmurl['guide'],'textarea',lang('aga_title'));
}else{
	foreach(array('sids','chids','filters','lists','operates',) as $var){
		$inmurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$inmurlnew['url'] = "?action=inarchives&nimuid=$imuid&aid=";
}

?>