<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('plimits'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	trbasic(lang('palimits'),'mtagnew[setting][alimits]',isset($mtag['setting']['alimits']) ? $mtag['setting']['alimits'] : '');
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	tabfooter();

	tabheader(lang('filter0_set'));
	$matidsarr = array();
	foreach($matypes as $k => $v){
		$matidsarr[$k] = $v['cname'];
		$imatid = $k;
	}
	trbasic(lang('pointmatype'),'mtagnew[setting][matid]',makeoption($matidsarr,empty($mtag['setting']['matid']) ? '0' : $mtag['setting']['matid']),'select');

	$orderbyarr = array(
		'' => lang('nosetting'),
		'createdate_desc' => lang('createdate_desc'),
		'createdate_asc' => lang('createdate_asc'),
		'refreshdate_desc' => lang('refreshdate_desc'),
	);
	trbasic(lang('order'),'mtagnew[setting][orderby]',makeoption($orderbyarr,empty($mtag['setting']['orderby']) ? '' : $mtag['setting']['orderby']),'select');
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=marchives_$imatid\" target=\"_blank\">".lang('create_str')."</a>";
	trbasic(lang('order_str').$createurl,'mtagnew[setting][orderstr]',empty($mtag['setting']['orderstr']) ? '' : stripslashes($mtag['setting']['orderstr']),'textarea');
	trbasic(lang('startno'),'mtagnew[setting][startno]',empty($mtag['setting']['startno']) ? '' : $mtag['setting']['startno'],'text',lang('agstartno'));
	$isfuncstr = "<br><input class=\"checkbox\" type=\"checkbox\" id=\"mtagnew[setting][isfunc]\" name=\"mtagnew[setting][isfunc]\"".(empty($mtag['setting']['isfunc']) ? '' : ' checked').">".lang('fromfunc');
	trbasic(lang('filter_sql_str').$createurl.$isfuncstr,'mtagnew[setting][wherestr]',empty($mtag['setting']['wherestr']) ? '' : $mtag['setting']['wherestr'],'textarea');
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
	$mtagnew['setting']['limits'] = max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : $mtagnew['setting']['limits'];
	$mtagnew['setting']['alimits'] = max(0,intval($mtagnew['setting']['alimits']));
	$mtagnew['setting']['startno'] = trim($mtagnew['setting']['startno']);
	$mtagnew['setting']['orderstr'] = empty($mtagnew['setting']['orderstr']) ? '' : trim($mtagnew['setting']['orderstr']);
	$mtagnew['setting']['wherestr'] = empty($mtagnew['setting']['wherestr']) ? '' : stripslashes(trim($mtagnew['setting']['wherestr']));
	$mtagnew['setting']['isfunc'] = empty($mtagnew['setting']['isfunc']) || empty($mtagnew['setting']['wherestr']) ? 0 : 1;

}
?>
