<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? '10' : $mtag['setting']['limits']);
	trbasic(lang('list_cols'),'mtagnew[setting][cols]',empty($mtag['setting']['cols']) ? '1' : $mtag['setting']['cols']);
	trbasic(lang('functionscode'),'mtagnew[setting][func]',empty($mtag['setting']['func']) ? '' : $mtag['setting']['func'],'btextarea',lang('agfunctionscode'));
	trbasic(lang('relay_param'),'mtagnew[setting][relays]',empty($mtag['setting']['relays']) ? '' : $mtag['setting']['relays'],'btext',lang('agrelays'));
	trbasic(lang('rrelay_param'),'mtagnew[setting][rrelays]',empty($mtag['setting']['rrelays']) ? '' : $mtag['setting']['rrelays'],'btext',lang('agrrelays'));
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();
}else{
	$mtagnew['setting']['func'] = trim($mtagnew['setting']['func']);
	$mtagnew['setting']['func'] = stripslashes($mtagnew['setting']['func']);
	if(empty($mtagnew['template']) || empty($mtagnew['setting']['func'])){
		if(!submitcheck('bmtagcode')){
			amessage('tag_data_miss',M_REFERER); 
		}else $errormsg = lang('tagdatamiss');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['cols'] = max(1,intval($mtagnew['setting']['cols']));
	$mtagnew['setting']['relays'] = empty($mtagnew['setting']['relays']) ? '' : trim($mtagnew['setting']['relays']);
	$mtagnew['setting']['rrelays'] = empty($mtagnew['setting']['rrelays']) ? '' : trim($mtagnew['setting']['rrelays']);
}
?>
