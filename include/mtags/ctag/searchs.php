<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('searchtxt'),'mtagnew[setting][sqlstr]',empty($mtag['setting']['sqlstr']) ? '' : $mtag['setting']['sqlstr'],'textarea');
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	trbasic(lang('only_valid_period'),'mtagnew[setting][validperiod]',empty($mtag['setting']['validperiod']) ? 0 : $mtag['setting']['validperiod'],'radio');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['sqlstr'] = empty($mtagnew['setting']['sqlstr']) ? '' : stripslashes(trim($mtagnew['setting']['sqlstr']));
	if(empty($mtagnew['setting']['sqlstr'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_query_string',M_REFERER); 
		}else $errormsg = lang('inpquerstr');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
}
?>
