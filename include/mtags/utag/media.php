<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('usource'),'mtagnew[setting][tname]',isset($mtag['setting']['tname']) ? $mtag['setting']['tname'] : '','text',lang('agusource1'));
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'u' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('player_width_r'),'mtagnew[setting][width]',isset($mtag['setting']['width']) ? $mtag['setting']['width'] : '');
	trbasic(lang('player_height_r'),'mtagnew[setting][height]',isset($mtag['setting']['height']) ? $mtag['setting']['height'] : '');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['tname'] = trim($mtagnew['setting']['tname']);
	if(!empty($mtagnew['setting']['tname']) && !preg_match("/^[a-zA-Z_\$][a-zA-Z0-9_\[\]]*$/",$mtagnew['setting']['tname'])){
		if(!submitcheck('bmtagcode')){
			amessage('sourceillegal',M_REFERER); 
		}else $errormsg = lang('sourceillegal');
	}
	$mtagnew['setting']['width'] = max(0,intval($mtagnew['setting']['width']));
	$mtagnew['setting']['height'] = max(0,intval($mtagnew['setting']['height']));
}
?>
