<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('arangeset'));
	trbasic(lang('arange').lang('achannel')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallchids\" onclick=\"checkall(this.form,'chidsnew','chkallchids')\">".lang('selectall'),'',makecheckbox('chidsnew[]',chidsarr(1),empty($aurl['setting']['chids']) ? array() : explode(',',$aurl['setting']['chids']),5),'',lang('agnoselect'));
	$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheck'),'1' => lang('checked'));
	trbasic(lang('arange').lang('check_state'),'',makeradio('aurlnew[setting][checked]',$checkedarr,!isset($aurl['setting']['checked']) ? '-1' : $aurl['setting']['checked']),'');
	$validarr = array('-1' => lang('nolimit'),'0' => lang('invalid'),'1' => lang('available'));
	trbasic(lang('arange').lang('validperiod_state'),'',makeradio('aurlnew[setting][valid]',$validarr,!isset($aurl['setting']['valid']) ? '-1' : $aurl['setting']['valid']),'');
	foreach($cotypes as $k => $v){
		global ${'ccidsnew'.$k};
		$ccidsarr = array(0 => lang('nococlass'));
		$coclasses = read_cache('coclasses',$k);
		foreach($coclasses as $k1 => $v1) $ccidsarr[$k1] = $v1['title'].'('.$v1['level'].')';
		trbasic(lang('arange').$v['cname']."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallccids$k\" onclick=\"checkall(this.form,'ccidsnew$k','chkallccids$k')\">".lang('selectall'),'',makecheckbox('ccidsnew'.$k.'[]',$ccidsarr,empty($aurl['setting']["ccids$k"]) ? array() : explode(',',$aurl['setting']["ccids$k"]),5),'',lang('agnoselect'));
	}
	unset($coclasses,$coclass);
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"aurlnew[tplname]\" name=\"aurlnew[tplname]\" value=\"$aurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"aurlnew[onlyview]\" id=\"aurlnew[onlyview]\" value=\"1\"".(empty($aurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	$filtersarr = array(
	'channel' => lang('achannel'),
	'myid' => lang('myid'),
	'check' => lang('check_state'),
	'valid' => lang('validperiod_state'),
	'date' => lang('daterange'),
	);
	foreach($cotypes as $k => $v) $filtersarr["ccid$k"] = $v['cname'];
	trbasic(lang('view_filters')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkallfilters\" onclick=\"checkall(this.form,'filtersnew','chkallfilters')\">".lang('selectall'),'',makecheckbox('filtersnew[]',$filtersarr,empty($aurl['setting']['filters']) ? array() : explode(',',$aurl['setting']['filters']),5),'',lang('agnoselect1'));

	$listsarr = array(
	'catalog' => lang('catalog'),
	'channel' => lang('arctype'),
	'check' => lang('check_state'),
	'valid' => lang('validperiod_state'),
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
	'vieworder' => lang('order'),
	'adddate' => lang('add_time'),
	'updatedate' => lang('update_time'),
	'refreshdate' => lang('readd_time'),
	'enddate' => lang('end1_time'),
	'view' => lang('view_info'),//显示详细信息
	'admin' => lang('admin'),
	);
	foreach($cotypes as $k => $v) if(!$v['self_reg']) $listsarr["ccid$k"] = $v['cname'];
	trbasic(lang('view_lists')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($aurl['setting']['lists']) ? array() : explode(',',$aurl['setting']['lists']),5),'',lang('agnoselect1'));
	$operatesarr = array(
	'delete' => lang('delete'),
	'check' => lang('check'),
	'uncheck' => lang('uncheck'),
	'abover' => lang('setting_album_abover'),
	'unabover' => lang('cancel_album_abover'),
	'readd' => lang('archive_readd'),
	'thumb' => lang('auto_thumb'),
	'abstract' => lang('auto_abstract'),
	'atmsize' => lang('stat_attachment_size'),
	'keyword' => lang('auto_keyword'),
	'valid' => lang('reset_validperiod'),
	'setalbum' => lang('setalbum'),
	'rpmid' => lang('read_pmid'),
	'dpmid' => lang('down_pmid'),
	'sale' => lang('arc_price'),
	'fsale' => lang('annex_price'),
	'prior' => lang('order_prior'),
	'catalog' => lang('set').lang('catalog'),
	'cpcatalog' => lang('addcp',lang('catalog')),
	);
	foreach($cotypes as $k => $v){
		if(!$v['self_reg']){
			$operatesarr["ccid$k"] = lang('set').$v['cname'];
			$operatesarr["cpccid$k"] = lang('addcp',$v['cname']);
		}
	}
	trbasic(lang('view_operates')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalloperates\" onclick=\"checkall(this.form,'operatesnew','chkalloperates')\">".lang('selectall'),'',makecheckbox('operatesnew[]',$operatesarr,empty($aurl['setting']['operates']) ? array() : explode(',',$aurl['setting']['operates']),5),'',lang('agnoselect1'));
	
	$iuidsarr = array();
	foreach($inurls as $k => $v) $iuidsarr[$k] = '<b>'.$v['cname'].'</b>&nbsp; '.$v['remark'];
	trbasic(lang('view_inurls')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalliuids\" onclick=\"checkall(this.form,'iuidsnew','chkalliuids')\">".lang('selectall'),'',makecheckbox('iuidsnew[]',$iuidsarr,empty($aurl['setting']['iuids']) ? array() : explode(',',$aurl['setting']['iuids']),3),'',lang('agnoselect1'));
}else{
	foreach(array('chids','filters','lists','operates','iuids',) as $var){
		$aurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	foreach($cotypes as $k => $v){
		$aurlnew['setting']['ccids'.$k] = empty(${'ccidsnew'.$k}) ? '' : implode(',',${'ccidsnew'.$k});
	}
	$aurlnew['url'] = "?entry=archives&action=archivesedit&nauid=$auid";
}
?>