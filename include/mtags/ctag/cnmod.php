<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$cnsourcearr = array('0' => lang('catalog'));
	foreach($cotypes as $k => $v) $cnsourcearr[$k] = $v['cname'];
	trbasic(lang('point_cotypem'),'mtagnew[setting][cnsource]',makeoption($cnsourcearr,isset($mtag['setting']['cnsource']) ? $mtag['setting']['cnsource'] : '0'),'select');
	trbasic(lang('directid2'),'mtagnew[setting][cnid]',empty($mtag['setting']['cnid']) ? '' : $mtag['setting']['cnid']);
	$levelarr = array('0' => lang('not_trace'),'1' => lang('topic'),'2' => lang('level1'),'3' => lang('level2'),);
	trbasic(lang('catas_upcata'),'',makeradio('mtagnew[setting][level]',$levelarr,isset($mtag['setting']['level']) ? $mtag['setting']['level'] : '0'),'');
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['cnid'] = max(0,intval(trim($mtagnew['setting']['cnid'])));
}
?>
