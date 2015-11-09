<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	$typearr = array('down' => lang('file_download'),'media' => lang('media_play'),'flash' => lang('flash_play'));
	$tmodearr = array('0' => lang('mcontent'),'1' => lang('scontent'));
	trbasic(lang('att_page_type'),'mtagnew[setting][type]',makeoption($typearr,empty($mtag['setting']['type']) ? 'file' : $mtag['setting']['type']),'select');
	trbasic(lang('usourcemode'),'mtagnew[setting][tmode]',makeoption($tmodearr,empty($mtag['setting']['tmode']) ? '0' : $mtag['setting']['tmode']),'select');
	trbasic(lang('usource1'),'mtagnew[setting][tname]',isset($mtag['setting']['tname']) ? $mtag['setting']['tname'] : '');
	tabfooter();
}else{
	$mtagnew['setting']['tname'] = trim($mtagnew['setting']['tname']);
	if(!empty($mtagnew['setting']['tmode'])){
		if(empty($mtagnew['setting']['tname']) || preg_match("/[^a-z_A-Z0-9]+/",$mtagnew['setting']['tname'])){
			if(!submitcheck('bmtagcode')){
				amessage('usource_illegal',M_REFERER); 
			}else $errormsg = lang('usource_illegal');//生成代码出错的提示信息
		}
	}
}
?>
