<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('pageresult'));
	$listsarr = array('email' => lang('email'));
	foreach($grouptypes as $k => $v) if(!$v['mode']) $listsarr["grouptype$k"] = $v['cname'];
	$listsarr['mtcid'] = lang('space_tpl_prj');
	foreach($mchannels as $mchid => $channel){
		$mfields = read_cache('mfields',$mchid);
		foreach($mfields as $k => $v) if(!$v['issystem']) $listsarr[$k] = $v['cname'];
	}
	trbasic(lang('arange_field')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($murl['setting']['lists']) ? array() : explode(',',$murl['setting']['lists']),5),'',lang('agnoselect1'));
	trbasic(lang('adm_title'),'murlnew[mtitle]',$murl['mtitle'],'text',lang('aga_title'));
	trbasic(lang('adm_guide'),'murlnew[guide]',$murl['guide'],'textarea',lang('aga_title'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"murlnew[tplname]\" name=\"murlnew[tplname]\" value=\"$murl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"murlnew[onlyview]\" id=\"murlnew[onlyview]\" value=\"1\"".(empty($murl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
}else{
	foreach(array('lists',) as $var){
		$murlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$murlnew['url'] = "?action=memberinfo&nmuid=$muid";
}
?>