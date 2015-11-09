<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('plimits'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	trbasic(lang('palimits'),'mtagnew[setting][alimits]',isset($mtag['setting']['alimits']) ? $mtag['setting']['alimits'] : '');
	trbasic(lang('list_cols'),'mtagnew[setting][cols]',empty($mtag['setting']['cols']) ? '1' : $mtag['setting']['cols']);
	trbasic(lang('functionscode'),'mtagnew[setting][func]',empty($mtag['setting']['func']) ? '' : $mtag['setting']['func'],'btextarea',lang('agfunctionscode'));
	trbasic(lang('functionsmpcode'),'mtagnew[setting][mpfunc]',empty($mtag['setting']['mpfunc']) ? '' : $mtag['setting']['mpfunc'],'btextarea',lang('agfunctionsmpcode'));
	trbasic(lang('relay_param'),'mtagnew[setting][relays]',empty($mtag['setting']['relays']) ? '' : $mtag['setting']['relays'],'btext',lang('agrelays'));
	trbasic(lang('rrelay_param'),'mtagnew[setting][rrelays]',empty($mtag['setting']['rrelays']) ? '' : $mtag['setting']['rrelays'],'btext',lang('agrrelays'));
	tabfooter();
	tabheader(lang('ptnaviset'));
	trbasic(lang('nav_simple'),'mtagnew[setting][simple]',empty($mtag['setting']['simple']) ? '0' : $mtag['setting']['simple'],'radio');
	trbasic(lang('nav_length'),'mtagnew[setting][length]',isset($mtag['setting']['length']) ? $mtag['setting']['length'] : '');
	tabfooter();
}else{
	$mtagnew['setting']['func'] = trim($mtagnew['setting']['func']);
	$mtagnew['setting']['mpfunc'] = trim($mtagnew['setting']['mpfunc']);
	$mtagnew['setting']['func'] = stripslashes($mtagnew['setting']['func']);
	$mtagnew['setting']['mpfunc'] = stripslashes($mtagnew['setting']['mpfunc']);
	if(empty($mtagnew['template']) || empty($mtagnew['setting']['func']) || empty($mtagnew['setting']['mpfunc'])){
		if(!submitcheck('bmtagcode')){
			amessage('tag_data_miss',M_REFERER); 
		}else $errormsg = lang('tagdatamiss');//生成代码出错的提示信息
	}
	$mtagnew['setting']['length'] = $mtagnew['setting']['length'] ? $mtagnew['setting']['length'] : '10';
	$mtagnew['setting']['limits'] = max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? '10' : $mtagnew['setting']['limits'];
	$mtagnew['setting']['alimits'] = max(0,intval($mtagnew['setting']['alimits']));
	$mtagnew['setting']['cols'] = max(1,intval($mtagnew['setting']['cols']));
	$mtagnew['setting']['relays'] = empty($mtagnew['setting']['relays']) ? '' : trim($mtagnew['setting']['relays']);
	$mtagnew['setting']['rrelays'] = empty($mtagnew['setting']['rrelays']) ? '' : trim($mtagnew['setting']['rrelays']);
}
?>
