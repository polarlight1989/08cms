<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	global $ofields,$ucotypes;
	load_cache('ofields,ucotypes');
	tabfooter();
	tabheader(lang('pageresult'));
	$listsarr = array();
	$listsarr['oprice'] = lang('price');
	$listsarr['storage'] = lang('stock');
	foreach($ucotypes as $k => $v) if($v['cclass'] == 'offer') $listsarr['uccid'.$k] = $v['cname'];
	foreach($ofields as $k => $v) $listsarr[$k] = $v['cname'];
	trbasic(lang('arange_field')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($inmurl['setting']['lists']) ? array() : explode(',',$inmurl['setting']['lists']),5),'',lang('agnoselect1'));
	trbasic(lang('adm_title'),'inmurlnew[mtitle]',$inmurl['mtitle'],'text',lang('aga_title'));
	trbasic(lang('adm_guide'),'inmurlnew[guide]',$inmurl['guide'],'textarea',lang('aga_title'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"inmurlnew[tplname]\" name=\"inmurlnew[tplname]\" value=\"$inmurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"inmurlnew[onlyview]\" id=\"inmurlnew[onlyview]\" value=\"1\"".(empty($inmurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
}else{
	foreach(array('lists',) as $var){
		$inmurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$inmurlnew['url'] = "?action=offer&nimuid=$imuid&cid=";
}

?>