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
	'channel' => lang('achannel'),
	'subsite' => lang('subsite'),
	'catalog' => lang('catalog'),
	);
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($inurl['setting']['filters']) ? array() : explode(',',$inurl['setting']['filters']),5),'',lang('agnoselect1'));

	$listsarr = array(
	'mname' => lang('member'),
	'catalog' => lang('catalog'),
	'channel' => lang('arctype'),
	'subsite' => lang('subsite'),
	'check' => lang('check_state'),
	'incheck' => lang('incheck'),
	'vol' => lang('vol'),
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
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($inurl['setting']['lists']) ? array() : explode(',',$inurl['setting']['lists']),5),'',lang('agnoselect1'));
	$operatesarr = array(
	'delete' => lang('delete'),
	'check' => lang('check'),
	'uncheck' => lang('uncheck'),
	'inclear' => lang('inclear'),
	'incheck' => lang('incheck'),
	'inuncheck' => lang('inuncheck'),
	'vol' => lang('vol'),
	'abover' => lang('setting_album_abover'),
	'unabover' => lang('cancel_album_abover'),
	'readd' => lang('archive_readd'),
	'rpmid' => lang('read_pmid'),
	'dpmid' => lang('down_pmid'),
	'sale' => lang('arc_price'),
	'fsale' => lang('annex_price'),
	'catalog' => lang('set').lang('catalog'),
	);
	foreach($cotypes as $k => $v){
		if(!$v['self_reg']){
			$operatesarr["ccid$k"] = lang('set').$v['cname'];
		}
	}
	trbasic(lang('view_operates')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalloperates\" onclick=\"checkall(this.form,'operatesnew','chkalloperates')\">".lang('selectall'),'',makecheckbox('operatesnew[]',$operatesarr,empty($inurl['setting']['operates']) ? array() : explode(',',$inurl['setting']['operates']),5),'',lang('agnoselect1'));

}else{
	foreach(array('sids','chids','filters','lists','operates',) as $var){
		$inurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$inurlnew['url'] = "?entry=inarchive&action=archives&niuid=$iuid&aid=";
}
?>