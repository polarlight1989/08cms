<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$nextarr = array('0' => lang('pre'),'1' => lang('next'));
	trbasic(lang('context_choose'),'',makeradio('mtagnew[setting][next]',$nextarr,isset($mtag['setting']['next']) ? $mtag['setting']['next'] : '0'),'');
	trbasic(lang('limitin_current_channel'),'mtagnew[setting][chid]',empty($mtag['setting']['chid']) ? '0' :  $mtag['setting']['chid'],'radio');
	trbasic(lang('limitin_current_catalog'),'mtagnew[setting][caid]',empty($mtag['setting']['caid']) ? '0' :  $mtag['setting']['caid'],'radio');
	foreach($cotypes as $k => $cotype){
		if($cotype['sortable']){
			trbasic(lang('limitin_current_coclass')."&nbsp;[$cotype[cname]]",'mtagnew[setting][ccid'.$k.']',empty($mtag['setting']['ccid'.$k]) ? '0' :  $mtag['setting']['ccid'.$k],'radio');
		}
	}
	trbasic(lang('limitin_active_member'),'mtagnew[setting][mid]',empty($mtag['setting']['mid']) ? '0' :  $mtag['setting']['mid'],'radio');
	trbasic(lang('nocp'),'mtagnew[setting][nocp]',empty($mtag['setting']['nocp']) ? 0 : $mtag['setting']['nocp'],'radio',lang('agnocp'));
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
}
?>
