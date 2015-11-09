<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($submitmode)){
	$tnstr = "<input type=\"text\" size=\"25\" id=\"aurlnew[tplname]\" name=\"aurlnew[tplname]\" value=\"$aurl[tplname]\">&nbsp; 
			<input class=\"checkbox\" type=\"checkbox\" name=\"aurlnew[onlyview]\" id=\"aurlnew[onlyview]\" value=\"1\"".(empty($aurl['onlyview']) ? '' : ' checked').">".lang('onlyview');
	trbasic(lang('customapage'),'',$tnstr,'',lang('agcustomapage'));
	tabfooter();
}else{
	foreach(array('coids',) as $var){
		$aurlnew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
	}
	$aurlnew['url'] = "?entry=archives&action=archivesupdate&nauid=$auid";
}
?>