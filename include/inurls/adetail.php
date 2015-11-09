<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	tabfooter();

	tabheader(lang('pageresult'));
	$tnstr = "<input type=\"text\" size=\"25\" id=\"inurlnew[tplname]\" name=\"inurlnew[tplname]\" value=\"$inurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"inurlnew[onlyview]\" id=\"inurlnew[onlyview]\" value=\"1\"".(empty($inurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	$listsarr = array('caid' => lang('be_catalog'));
	foreach($cotypes as $k => $v) if(!$v['self_reg']) $listsarr["ccid$k"] = lang('belongcocl').'-'.$v['cname'];
	$listsarr['jumpurl'] = lang('jumpurl');
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
	$listsarr['rpmid'] = lang('read_pmid');
	$listsarr['dpmid'] = lang('down_pmid');
	$listsarr['salecp'] = lang('arc_price');
	$listsarr['fsalecp'] = lang('annex_price');
	$listsarr['arctpl'] = lang('archive_content_template');
	trbasic(lang('arange_field')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkalllists\" onclick=\"checkall(this.form,'listsnew','chkalllists')\">".lang('selectall'),'',makecheckbox('listsnew[]',$listsarr,empty($inurl['setting']['lists']) ? array() : explode(',',$inurl['setting']['lists']),5),'',lang('agnoselect1'));
	trbasic(lang('adm_title'),'inurlnew[mtitle]',$inurl['mtitle'],'text',lang('aga_title'));
	trbasic(lang('adm_guide'),'inurlnew[guide]',$inurl['guide'],'textarea',lang('aga_title'));
}else{
	foreach(array('lists',) as $var){
		$inurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$inurlnew['url'] = "?entry=archive&action=archivedetail&niuid=$iuid&aid=";
}
?>