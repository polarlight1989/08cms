<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$nextarr = array('0' => lang('pre'),'1' => lang('next'));
	trbasic(lang('context_choose'),'',makeradio('mtagnew[setting][next]',$nextarr,isset($mtag['setting']['next']) ? $mtag['setting']['next'] : '0'),'');
	trbasic(lang('parent_altype'),'mtagnew[setting][chid]',makeoption(chidsarr(1),empty($mtag['setting']['chid']) ? '0' : $mtag['setting']['chid']),'select');
	$chsourcearr = array('0' => lang('all_archive')) + chidsarr(1);
	trbasic(lang('chid_attr'),'mtagnew[setting][chsource]',makeoption($chsourcearr,empty($mtag['setting']['chsource']) ? '0' : $mtag['setting']['chsource']),'select');
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	if(empty($mtagnew['setting']['chid'])){
		if(!submitcheck('bmtagcode')){
			amessage('point_altype',M_REFERER); 
		}else $errormsg = lang('point_altype');//生成代码出错的提示信息
	}
}
?>
