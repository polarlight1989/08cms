<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	tabfooter();

	tabheader(lang('pick_setting'));
	$dsidsarr = array(0 => lang('current_system'));
	foreach($dbsources as $k => $v) $dsidsarr[$k] = $v['cname'];
	$str = "<select style=\"vertical-align: middle;\" name=\"mtagnew[setting][dsid]\" onchange=\"\$id('link_mtagnew_setting_dsid').innerHTML='>><a href=\'?entry=dbsources&action=viewconfigs&dsid=' + this.options[this.selectedIndex].value + '\' target=\'_blank\'>".lang('look_configs')."</a>';\">".
		makeoption($dsidsarr,empty($mtag['setting']['dsid']) ? 0 : $mtag['setting']['dsid']).
		"</select>&nbsp; &nbsp; <span id=\"link_mtagnew_setting_dsid\">>><a href=\"?entry=dbsources&action=viewconfigs&dsid=".(empty($mtag['setting']['dsid']) ? 0 : $mtag['setting']['dsid'])."\" target=\"_blank\">".lang('look_configs')."</a></span>";
	trbasic(lang('dbsource'),'',$str,'');
	trbasic(lang('define_content_query_string'),'mtagnew[setting][sqlstr]',empty($mtag['setting']['sqlstr']) ? '' : $mtag['setting']['sqlstr'],'textarea');
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	$mtagnew['setting']['sqlstr'] = empty($mtagnew['setting']['sqlstr']) ? '' : stripslashes(trim($mtagnew['setting']['sqlstr']));
	if(empty($mtagnew['setting']['sqlstr'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_query_string',M_REFERER); 
		}else $errormsg = lang('inpquerstr');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
}
?>
