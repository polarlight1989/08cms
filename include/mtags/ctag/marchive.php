<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$matidsarr = array();
	foreach($matypes as $k => $v) $matidsarr[$k] = $v['cname'];
	trbasic(lang('pointmatype'),'mtagnew[setting][matid]',makeoption($matidsarr,empty($mtag['setting']['matid']) ? '0' : $mtag['setting']['matid']),'select');
	trbasic(lang('direct_maid'),'mtagnew[setting][mid]',empty($mtag['setting']['mid']) ? '' : $mtag['setting']['mid']);
	trbasic(lang('tplpermi_set'),'mtagnew[setting][pmid]',makeoption(pmidsarr('tpl'),empty($mtag['setting']['pmid']) ? 0 : $mtag['setting']['pmid']),'select',lang('agtplpermi_set'));
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}else{
		$mtagnew['setting']['mid'] = max(0,intval($mtagnew['setting']['mid']));
	}
}
?>
