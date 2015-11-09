<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	$datearr = array(
		'' => lang('not_view_date'),
		'y-m-d' => lang('eg').'09-04-07',
		'Y-m-d' => lang('eg').'2009-04-07',
		'm-d-y' => lang('eg').'04-07-09',
		'm-d-Y' => lang('eg').'04-07-2009',
		'y-m' => lang('eg').'09-04',
		'Y-m' => lang('eg').'2009-04',
		'm-d' => lang('eg').'04-07',
		'M-d' => lang('eg').'Apr-07',
		'F-d' => lang('eg').'April-07',
		'M-d-Y' => lang('eg').'Apr-07-09',
		'M-d-y' => lang('eg').'Apr-07-2009',
	);
	$timearr = array(
		'' => lang('not_view_time'),
		'H:i:s' => lang('eg').'14:07:05',
		'h:i:s a' => lang('eg').'02:07:05 pm',
		'H:i' => lang('eg').'14:07',
		'A h:i' => lang('eg').'PM 02:07',
		'i:s' => lang('eg').'07:05',
	);
	trbasic(lang('usource'),'mtagnew[setting][tname]',isset($mtag['setting']['tname']) ? $mtag['setting']['tname'] : '','text',lang('agusource'));
	trbasic(lang('date_view_format'),'mtagnew[setting][date]',makeoption($datearr,empty($mtag['setting']['date']) ? '0' : $mtag['setting']['date']),'select');
	trbasic(lang('time_view_format'),'mtagnew[setting][time]',makeoption($timearr,empty($mtag['setting']['time']) ? '0' : $mtag['setting']['time']),'select');
	tabfooter();
}else{
	$mtagnew['setting']['tname'] = trim($mtagnew['setting']['tname']);
	if(empty($mtagnew['setting']['tname'])) amessage('input_usource');
	if(empty($mtagnew['setting']['tname']) || !preg_match("/^[a-zA-Z_\$][a-zA-Z0-9_\[\]]*$/",$mtagnew['setting']['tname'])){
		if(!submitcheck('bmtagcode')){
			amessage('sourceillegal',M_REFERER); 
		}else $errormsg = lang('sourceillegal');//生成代码出错的提示信息
	}
}
?>
