<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	$htmlarr = array(
		'0' => lang('nodeal'),
		'clearhtml' => lang('clearhtml'),
		'disablehtml' => lang('disablehtml'),
		'safehtml' => lang('safehtml'),
		'wapcode' => lang('wapcode'),
	);
	trbasic(lang('usource'),'mtagnew[setting][tname]',isset($mtag['setting']['tname']) ? $mtag['setting']['tname'] : '','text',lang('agusource'));
	trbasic(lang('deal_html_code'),'mtagnew[setting][dealhtml]',makeoption($htmlarr,empty($mtag['setting']['dealhtml']) ? '0' : $mtag['setting']['dealhtml']),'select');
	trbasic(lang('txt_length_trim'),'mtagnew[setting][trim]',isset($mtag['setting']['trim']) ? $mtag['setting']['trim'] : 0,'text',lang('byte_len_trim'));
	trbasic(lang('filter_badword'),'mtagnew[setting][badword]',empty($mtag['setting']['badword']) ? '0' : $mtag['setting']['badword'],'radio');
	trbasic(lang('deal_wordlink'),'mtagnew[setting][wordlink]',empty($mtag['setting']['wordlink']) ? '0' : $mtag['setting']['wordlink'],'radio');
	trbasic(lang('deal_face'),'mtagnew[setting][face]',empty($mtag['setting']['face']) ? '0' : $mtag['setting']['face'],'radio');
	trbasic(lang('multitext_newline'),'mtagnew[setting][nl2br]',empty($mtag['setting']['nl2br']) ? '0' : $mtag['setting']['nl2br'],'radio');
	trbasic(lang('add_randstr'),'mtagnew[setting][randstr]',empty($mtag['setting']['randstr']) ? '0' : $mtag['setting']['randstr'],'radio');
	tabfooter();
}else{
	$mtagnew['setting']['tname'] = trim($mtagnew['setting']['tname']);
	if(empty($mtagnew['setting']['tname']) || !preg_match("/^[a-zA-Z_\$][a-zA-Z0-9_\[\]]*$/",$mtagnew['setting']['tname'])){
		if(!submitcheck('bmtagcode')){
			amessage('sourceillegal',M_REFERER); 
		}else $errormsg = lang('sourceillegal');//生成代码出错的提示信息
	}
	$mtagnew['setting']['trim'] = max(0,intval($mtagnew['setting']['trim']));
}
?>
