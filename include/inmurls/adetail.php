<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('pageresult'));
	$listsarr = array('caid' => lang('be_catalog'));
	foreach($cotypes as $k => $v) if(!$v['self_reg']) $listsarr["ccid$k"] = lang('belongcocl').'-'.$v['cname'];
	$listsarr['jumpurl'] = lang('jumpurl');
	$listsarr['ucid'] = lang('uclass');
	$listsarr['cpupdate'] = lang('cpupdate');
	foreach($channels as $chid => $channel){
		$fields = read_cache('fields',$chid);
		foreach($fields as $k => $v){
			if(empty($listsarr[$k])){
				$listsarr[$k] = $v['cname'];
			}elseif(!in_str($v['cname'],$listsarr[$k])){
				$listsarr[$k] .= '/'.$v['cname'];
			}
		}
	}
	$listsarr['salecp'] = lang('arc_price');
	$listsarr['fsalecp'] = lang('annex_price');
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
	$inmurlnew['url'] = "?action=archive&nimuid=$imuid&aid=";
}

?>