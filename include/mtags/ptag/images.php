<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('usource'),'mtagnew[setting][tname]',isset($mtag['setting']['tname']) ? $mtag['setting']['tname'] : '','text',lang('agusource'));
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('plimits'),'mtagnew[setting][limits]',isset($mtag['setting']['limits']) ? $mtag['setting']['limits'] : '10');
	trbasic(lang('imawidlim'),'mtagnew[setting][maxwidth]',isset($mtag['setting']['maxwidth']) ? $mtag['setting']['maxwidth'] : '');
	trbasic(lang('imaheilim'),'mtagnew[setting][maxheight]',isset($mtag['setting']['maxheight']) ? $mtag['setting']['maxheight'] : '');
	trbasic(lang('createthumb'),'mtagnew[setting][thumb]',isset($mtag['setting']['thumb']) ? $mtag['setting']['thumb'] : 0,'radio');
	trspecial(lang('emptyurl'),'mtagnew[setting][emptyurl]',isset($mtag['setting']['emptyurl']) ? $mtag['setting']['emptyurl'] : '','image');
	trbasic(lang('emptytitle'),'mtagnew[setting][emptytitle]',isset($mtag['setting']['emptytitle']) ? $mtag['setting']['emptytitle'] : '');
	tabfooter();
	tabheader(lang('ptnaviset'));
	trbasic(lang('nav_simple'),'mtagnew[setting][simple]',empty($mtag['setting']['simple']) ? '0' : $mtag['setting']['simple'],'radio');
	trbasic(lang('nav_length'),'mtagnew[setting][length]',isset($mtag['setting']['length']) ? $mtag['setting']['length'] : '');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('pleinptatem');//生成代码出错的提示信息
	}
	$mtagnew['setting']['length'] = $mtagnew['setting']['length'] ? $mtagnew['setting']['length'] : '10';
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
