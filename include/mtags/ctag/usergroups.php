<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
empty($mtag['setting']['listby']) && $mtag['setting']['listby'] = '1';
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? '10' : $mtag['setting']['limits']);
	tabfooter();

	tabheader(lang('list_item_setting'));
	$caco_same_fix = 'caco_same_fix_';
	$caco_diff_fix = 'caco_diff_fix_';
	$cacoarr = array();
	foreach($grouptypes as $k => $v) $cacoarr[$k] = $v['cname'];
	trbasic(lang('list_item'),'',makeradio('mtagnew[setting][listby]', $cacoarr, $mtag['setting']['listby'],'',"single_list_set(this, '$caco_same_fix')"), '');
	
	$sourcearr = array(0 => lang('allusergroup'),1 => lang('handpoint'),);
	foreach($grouptypes as $k => $v){
		sourcemodule($v['cname'],"mtagnew[setting][ugsource$k]",$sourcearr,empty($mtag['setting']['ugsource'.$k]) ? 0 : $mtag['setting']['ugsource'.$k],
		'1',
		"mtagnew[setting][ugids$k][]",ugidsarr($k),empty($mtag['setting']['ugids'.$k]) ? array() : explode(',',$mtag['setting']['ugids'.$k]),
		'25%',
		$mtag['setting']['listby'] == $k,$caco_same_fix.$k);
	}
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{//?????????????????????????过滤非listby的参数
	if(empty($mtagnew['template'])) amessage('tag_data_miss',M_REFERER); 
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));

	//数组参数的处理
	$idvars = array();
	foreach($grouptypes as $k => $v) $idvars[] = 'ugids'.$k;
	foreach($idvars as $k){
		if(empty($mtagnew['setting'][$k])){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = implode(',',$mtagnew['setting'][$k]);
	}
}
?>
