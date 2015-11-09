<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
load_cache('matypes');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	$sourcearr = array('0' => lang('alltype'),'1' => lang('handpoint'),);
	$atidsarr = array();
	foreach($matypes as $k => $v) $atidsarr[$k] = $v['cname'];
	sourcemodule(lang('matypelist'),
				'mtagnew[setting][source]',
				$sourcearr,
				empty($mtag['setting']['source']) ? '' : $mtag['setting']['source'],
				'1',
				'mtagnew[setting][matids][]',
				$atidsarr,
				!empty($mtag['setting']['matids']) ? explode(',',$mtag['setting']['matids']) : array()
				);
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	//数组参数的处理
	$idvars = array('matids');
	foreach($idvars as $k){
		if(empty($mtagnew['setting'][$k])){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = implode(',',$mtagnew['setting'][$k]);
	}
}
?>
