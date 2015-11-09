<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'u' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	$typearr = array(
	'chid' => lang('achannel'),
	'mchid' => lang('mchannel'),
	'caid' => lang('catalog'),
	'sid' => lang('subsite'),
	'matid' => lang('matype'),
	'rgid' => lang('repugrade'),
	);
	foreach($cotypes as $k => $v) $typearr['ccid'.$k] = lang('coclass').'-'.$v['cname'];
	foreach($grouptypes as $k => $v) $typearr['grouptype'.$k] = lang('usergroup').'-'.$v['cname'];
	trbasic(lang('idsourcetype'),'mtagnew[setting][type]',makeoption($typearr,empty($mtag['setting']['type']) ? '' : $mtag['setting']['type']),'select');
	trbasic(lang('pointid'),'mtagnew[setting][idsoruce]',isset($mtag['setting']['idsoruce']) ? $mtag['setting']['idsoruce'] : 0,'text');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['idsoruce'] = max(0,intval($mtagnew['setting']['idsoruce']));
}
?>
