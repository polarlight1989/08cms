<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? '10' : $mtag['setting']['limits']);
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
	tabheader(lang('setting_list_item'));
	$sourcearr = array('0' => lang('all_space0_catalog'),'1' => lang('handpoint'),);
	sourcemodule(lang('space0catalog')."&nbsp;&nbsp;&nbsp;<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][listby]\" value=\"ca\"".((empty($mtag['setting']['listby']) || $mtag['setting']['listby'] == 'ca') ? " checked" : "").">".lang('list_item'),
				'mtagnew[setting][casource]',
				$sourcearr,
				empty($mtag['setting']['casource']) ? '0' : $mtag['setting']['casource'],
				'1',
				'mtagnew[setting][caids][]',
				mcaidsarr(),
				(!empty($mtag['setting']['caids']) ? explode(',',$mtag['setting']['caids']) : array())
				);
	$sourcearr = array('0' => lang('catalog_all_coclass'),);
	trbasic(lang('uclass')."&nbsp;&nbsp;&nbsp;<input class=\"radio\" type=\"radio\" name=\"mtagnew[setting][listby]\" value=\"uc\"".((!empty($mtag['setting']['listby']) && $mtag['setting']['listby'] == 'uc') ? " checked" : "").">".lang('list_item'),'mtagnew[setting][ucsource]',makeoption($sourcearr,isset($mtag['setting']['ucsource']) ? $mtag['setting']['ucsource'] : '0'),'select');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	//数组参数的处理
	$idvars = array('caids');
	foreach($idvars as $k){
		if(empty($mtagnew['setting'][$k])){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = implode(',',$mtagnew['setting'][$k]);
	}
}
?>
