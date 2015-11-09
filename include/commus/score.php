<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			trbasic(lang('operate_permi_set'),'communew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($commu['setting']['apmid']) ? 0 : $commu['setting']['apmid']),'select');
			trbasic(lang('allow_repeat'),'communew[setting][repeat]',isset($commu['setting']['repeat']) ? $commu['setting']['repeat'] : 0,'radio');
			trbasic(lang('repeat_time_m'),'communew[setting][repeattime]',isset($commu['setting']['repeattime']) ? $commu['setting']['repeattime'] : 0);
			trbasic(lang('scorestr'),'communew[setting][scorestr]',isset($commu['setting']['scorestr']) ? $commu['setting']['scorestr'] : '','text',lang('agscorestr'));
			trbasic(lang('scorepics'),'communew[setting][pics]',empty($commu['setting']['pics']) ? 0 : 1,'radio',lang('agscorepics'));
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'communew[ucadd]',empty($commu['ucadd']) ? '' : $commu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'communew[usetting]',empty($commu['usetting']) ? '' : $commu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			$communew['setting']['repeattime'] = max(0,intval($communew['setting']['repeattime']));
			$scorearr = empty($communew['setting']['scorestr']) ? array() : array_filter(explode(',',$communew['setting']['scorestr']));
			foreach($scorearr as $k => $v) $scorearr[$k] = max(1,min(99,intval($v)));
			$scorearr = array_unique($scorearr);
			$communew['setting']['scorestr'] = empty($scorearr) ? '' : implode(',',$scorearr);
		}
	}elseif($action == 'commulink'){
		trbasic(lang('arc_score_operate'),'','{$cms_abs}tools/score.php?aid={aid}&score=xx (xx-'.lang('score_amount').')','');
	}
}else include(M_ROOT.$commu['uconfig']);


?>