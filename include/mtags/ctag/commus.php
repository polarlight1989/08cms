<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(!submitcheck('bmtagadd') && !submitcheck('bmtagsdetail') && !submitcheck('bmtagcode')){
	templatebox(lang('tagtemplate'),'mtagnew[template]',empty($mtag['template']) ? '' : $mtag['template'],10,110);
	trbasic(lang('arr_pre'),'mtagnew[setting][val]',empty($mtag['setting']['val']) ? 'v' : $mtag['setting']['val'],'text',lang('agarr_pre'));
	trbasic(lang('list_result'),'mtagnew[setting][limits]',empty($mtag['setting']['limits']) ? 10 : $mtag['setting']['limits']);
	$cuidsarr = array();
	foreach($commus as $cuid => $commu){
		if($commu['available'] && $commu['sortable']){
			$cuidsarr[$cuid] = $commu['cname'];
			$icuid = $cuid;
		}
	}
	$idsarr = array('aid' => lang('active_archive'),'mid' => lang('active_member'),'tomid' => lang('receive_member'));
	$checkedarr = array('0' => lang('nolimit'),'1' => lang('checked'));
	trbasic(lang('point_commu_item'),'mtagnew[setting][cuid]',makeoption($cuidsarr,empty($mtag['setting']['cuid']) ? '0' : $mtag['setting']['cuid']),'select');
	trbasic(lang('relate_id_source'),'',makeradio('mtagnew[setting][idsource]',$idsarr,empty($mtag['setting']['idsource']) ? 'aid' : $mtag['setting']['idsource']),'');
	$sourcearr = array('0' => lang('nolimit_coclass'),'1' => lang('active_coclass'),);
	trbasic(lang('active_uclass'),'',makeradio('mtagnew[setting][ucsource]',$sourcearr,empty($mtag['setting']['ucsource']) ? 0 : $mtag['setting']['ucsource']),'',lang('aguclass'));
	tabfooter();

	tabheader(lang('adv_options')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('advancedfilter')\">".lang('view'));
	echo "<tbody id=\"advancedfilter\" style=\"display: none;\">";
	trbasic(lang('only_valid_period'),'mtagnew[setting][validperiod]',empty($mtag['setting']['validperiod']) ? 0 : $mtag['setting']['validperiod'],'radio');
	trbasic(lang('tagjspick'),'mtagnew[setting][js]',empty($mtag['setting']['js']) ? 0 : $mtag['setting']['js'],'radio');
	trbasic(lang('check_state'),'',makeradio('mtagnew[setting][checked]',$checkedarr,empty($mtag['setting']['checked']) ? '0' : $mtag['setting']['checked']),'');
	trbasic(lang('indays'),'mtagnew[setting][indays]',!isset($mtag['setting']['indays']) ? '' : $mtag['setting']['indays']);
	trbasic(lang('outdays'),'mtagnew[setting][outdays]',!isset($mtag['setting']['outdays']) ? '' : $mtag['setting']['outdays']);
	trbasic(lang('add_time_asc'),'mtagnew[setting][orderby]',isset($mtag['setting']['orderby']) ? $mtag['setting']['orderby'] : 0,'radio');
	$createurl = "&nbsp; >><a href=\"?entry=liststr&tclass=commus_$icuid\" target=\"_blank\">".lang('create_str')."</a>";
	trbasic(lang('order_str').$createurl,'mtagnew[setting][orderstr]',empty($mtag['setting']['orderstr']) ? '' : stripslashes($mtag['setting']['orderstr']),'textarea');
	$isfuncstr = "<br><input class=\"checkbox\" type=\"checkbox\" id=\"mtagnew[setting][isfunc]\" name=\"mtagnew[setting][isfunc]\"".(empty($mtag['setting']['isfunc']) ? '' : ' checked').">".lang('fromfunc');
	trbasic(lang('filter_sql_str').$createurl.$isfuncstr,'mtagnew[setting][wherestr]',empty($mtag['setting']['wherestr']) ? '' : $mtag['setting']['wherestr'],'textarea');
	echo "</tbody>";
	tabfooter();
}else{
	if(empty($mtagnew['template'])){
		if(!submitcheck('bmtagcode')){
			amessage('input_tag_tpl',M_REFERER); 
		}else $errormsg = lang('input_tag_tpl');//生成代码出错的提示信息
	}
	if(empty($mtagnew['setting']['cuid'])){
		if(!submitcheck('bmtagcode')){
			amessage('choose_commu_item',M_REFERER); 
		}else $errormsg = lang('confirmcomitem');//生成代码出错的提示信息
	}
	$mtagnew['setting']['limits'] = empty($mtagnew['setting']['limits']) ? 10 : max(0,intval($mtagnew['setting']['limits']));
	$mtagnew['setting']['orderstr'] = empty($mtagnew['setting']['orderstr']) ? '' : trim($mtagnew['setting']['orderstr']);
	$mtagnew['setting']['wherestr'] = empty($mtagnew['setting']['wherestr']) ? '' : stripslashes(trim($mtagnew['setting']['wherestr']));
	$mtagnew['setting']['isfunc'] = empty($mtagnew['setting']['isfunc']) || empty($mtagnew['setting']['wherestr']) ? 0 : 1;
	foreach(array('indays','outdays',) as $k){
		$mtagnew['setting'][$k] = trim($mtagnew['setting'][$k]);
		if($mtagnew['setting'][$k] == ''){
			unset($mtagnew['setting'][$k]);
		}else $mtagnew['setting'][$k] = max(0,intval($mtagnew['setting'][$k]));
	}
}
?>
