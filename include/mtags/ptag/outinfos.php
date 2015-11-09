<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('plimits'),'mtagnew[setting][limits]',isset($mtag['setting']['limits']) ? $mtag['setting']['limits'] : '10');
	trbasic(lang('palimits'),'mtagnew[setting][alimits]',isset($mtag['setting']['alimits']) ? $mtag['setting']['alimits'] : '');
	tabfooter();
	tabheader(lang('pick_setting'));
	$dsidsarr = array(0 => lang('current_system'));
	foreach($dbsources as $k => $v) $dsidsarr[$k] = $v['cname'];
	$str = "<select style=\"vertical-align: middle;\" name=\"mtagnew[setting][dsid]\" onchange=\"\$id('link_mtagnew_setting_dsid').innerHTML='>><a href=\'?entry=dbsources&action=viewconfigs&dsid=' + this.options[this.selectedIndex].value + '\' target=\'_blank\'>".lang('look_configs')."</a>';\">".
		makeoption($dsidsarr,empty($mtag['setting']['dsid']) ? 0 : $mtag['setting']['dsid']).
		"</select>&nbsp; &nbsp; <span id=\"link_mtagnew_setting_dsid\">>><a href=\"?entry=dbsources&action=viewconfigs&dsid=".(empty($mtag['setting']['dsid']) ? 0 : $mtag['setting']['dsid'])."\" target=\"_blank\">".lang('look_configs')."</a></span>";
	trbasic(lang('dbsource'),'',$str,'');
	trbasic(lang('define_content_query_string'),'mtagnew[setting][sqlstr]',empty($mtag['setting']['sqlstr']) ? '' : $mtag['setting']['sqlstr'],'textarea');
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
	$mtagnew['setting']['sqlstr'] = empty($mtagnew['setting']['sqlstr']) ? '' : stripslashes(trim($mtagnew['setting']['sqlstr']));
	if(empty($mtagnew['setting']['sqlstr'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_query_string',M_REFERER); 
		}else $errormsg = lang('inpquerstr');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? '10' : $mtagnew['setting']['limits'];
	$mtagnew['setting']['alimits'] = max(0,intval($mtagnew['setting']['alimits']));
}
?>
