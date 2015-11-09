<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('usource'),'mtagnew[setting][tname]',isset($mtag['setting']['tname']) ? $mtag['setting']['tname'] : '','text',lang('agusource'));
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'u' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('amount_limit'),'mtagnew[setting][limits]',isset($mtag['setting']['limits']) ? $mtag['setting']['limits'] : '');
	trbasic(lang('image_width_limit'),'mtagnew[setting][maxwidth]',isset($mtag['setting']['maxwidth']) ? $mtag['setting']['maxwidth'] : '');
	trbasic(lang('image_height_limit'),'mtagnew[setting][maxheight]',isset($mtag['setting']['maxheight']) ? $mtag['setting']['maxheight'] : '');
	trbasic(lang('createthumb'),'mtagnew[setting][thumb]',isset($mtag['setting']['thumb']) ? $mtag['setting']['thumb'] : 0,'radio');
	trspecial(lang('emptyurl'),'mtagnew[setting][emptyurl]',isset($mtag['setting']['emptyurl']) ? $mtag['setting']['emptyurl'] : '','image');
	trbasic(lang('emptytitle'),'mtagnew[setting][emptytitle]',isset($mtag['setting']['emptytitle']) ? $mtag['setting']['emptytitle'] : '');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['tname'] = trim($mtagnew['setting']['tname']);
	if(empty($mtagnew['setting']['tname']) || !preg_match("/^[a-zA-Z_\$][a-zA-Z0-9_\[\]]*$/",$mtagnew['setting']['tname'])){
		if(!submitcheck('bmtagcode')){
			amessage('sourceillegal',M_REFERER); 
		}else $errormsg = lang('sourceillegal');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? '10' : $mtagnew['setting']['limits'];
	$mtagnew['setting']['maxwidth'] = max(0,intval($mtagnew['setting']['maxwidth']));
	$mtagnew['setting']['maxheight'] = max(0,intval($mtagnew['setting']['maxheight']));
	$c_upload = new cls_upload;	
	$mtagnew['setting']['emptyurl'] = upload_s($mtagnew['setting']['emptyurl'],isset($mtag['setting']['emptyurl']) ? $mtag['setting']['emptyurl'] : '','image');
	if($k = strpos($mtagnew['setting']['emptyurl'],'#')) $mtagnew['setting']['emptyurl'] = substr($mtagnew['setting']['emptyurl'],0,$k);
	$c_upload->closure(2);
	$c_upload->saveuptotal(1);
	unset($c_upload);
}
?>
