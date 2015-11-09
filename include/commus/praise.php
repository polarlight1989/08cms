<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			trbasic(lang('operate_permi_set'),'communew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($commu['setting']['apmid']) ? 0 : $commu['setting']['apmid']),'select');
			trbasic(lang('allow_repeat'),'communew[setting][repeat]',isset($commu['setting']['repeat']) ? $commu['setting']['repeat'] : 0,'radio');
			trbasic(lang('repeat_time_m'),'communew[setting][repeattime]',isset($commu['setting']['repeattime']) ? $commu['setting']['repeattime'] : 0);
			echo "</tbody>";
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'communew[ucadd]',empty($commu['ucadd']) ? '' : $commu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'communew[usetting]',empty($commu['usetting']) ? '' : $commu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			$communew['setting']['repeattime'] = max(0,intval($communew['setting']['repeattime']));
		}
	}elseif($action == 'commulink'){
		trbasic(lang('arc_praise_operate'),'','{$cms_abs}tools/praise.php?aid={aid}','');
		trbasic(lang('arc_debase_operate'),'','{$cms_abs}tools/debase.php?aid={aid}','');
	}
}else include(M_ROOT.$commu['uconfig']);


?>