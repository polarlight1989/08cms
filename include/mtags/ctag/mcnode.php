<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$cnsourcearr = array('caid' => lang('catalog'));
	foreach($cotypes as $k => $v) !$v['self_reg'] && $cnsourcearr['ccid'.$k] = $v['cname'];
	foreach($grouptypes as $k => $v) !$v['issystem'] && $cnsourcearr['ugid'.$k] = $v['cname'];
	$cnsourcearr['matid'] = lang('matype');
	$cnsourcearr['mcnid'] = lang('customnode');
	trbasic(lang('point_mcntype'),'mtagnew[setting][cnsource]',makeoption($cnsourcearr,isset($mtag['setting']['cnsource']) ? $mtag['setting']['cnsource'] : '0'),'select');
	trbasic(lang('directidmcn'),'mtagnew[setting][cnid]',empty($mtag['setting']['cnid']) ? '' : $mtag['setting']['cnid']);
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	$mtagnew['setting']['cnid'] = trim($mtagnew['setting']['cnid']);
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');
	}
	if(empty($mtagnew['setting']['cnid'])){
		if(!submitcheck('bmtagcode')){
			amessage(lang('directidmcn'),M_REFERER); 
		}else $errormsg = lang('directidmcn');
	}
}
?>
