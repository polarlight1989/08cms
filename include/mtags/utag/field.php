<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	$typearr = array(
		'archive' => lang('archive'),
		'member' => lang('member'),
		'farchive' => lang('freeinfo'),
		'marchive' => lang('marchive'),
		'catalog' => lang('catalog'),
		'coclass' => lang('coclass'),
		'comment' => lang('comment'),
		'purchase' => lang('purchase'),
		'offer' => lang('offer'),
		'reply' => lang('reply'),
		'report' => lang('report'),
		'mcomment' => lang('mcomment'),
		'mreply' => lang('mreply'),
		'mreport' => lang('memberreport'),
		'mflink' => lang('flink'),
	);
	trbasic(lang('sfield_name'),'mtagnew[setting][tname]',isset($mtag['setting']['tname']) ? $mtag['setting']['tname'] : '','text',lang('agsfname'));
	trbasic(lang('field_type'),'mtagnew[setting][type]',makeoption($typearr,empty($mtag['setting']['type']) ? '0' : $mtag['setting']['type']),'select');
	trbasic(lang('resultnum'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? '' : $mtag['setting']['limits'],'text',lang('agresultnum'));
	tabfooter();
}else{
	$mtagnew['setting']['tname'] = trim($mtagnew['setting']['tname']);
	$mtagnew['setting']['limits'] = max(0,intval($mtagnew['setting']['limits']));
	if(empty($mtagnew['setting']['tname'])) amessage('input_usource');
	if(empty($mtagnew['setting']['tname']) || !preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/",$mtagnew['setting']['tname'])){
		if(!submitcheck('bmtagcode')){
			amessage('sourceillegal',M_REFERER); 
		}else $errormsg = lang('sourceillegal');//生成代码出错的提示信息
	}
	$mtagnew['setting']['fname'] = $mtagnew['setting']['tname'];
}
?>
