<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$urlmodearr = array('0' => lang('default'),'caid' => lang('catalog'));
	foreach($cotypes as $k => $cotype){
		$cotype['sortable'] && $urlmodearr['ccid'.$k] = $cotype['cname'];
	}
	trbasic(lang('urlmode'),'mtagnew[setting][urlmode]',makeoption($urlmodearr,empty($mtag['setting']['urlmode']) ? '0' : $mtag['setting']['urlmode']),'select');
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
