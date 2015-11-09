<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($mcommu['uconfig'])){
	if($action == 'mcommudetail'){
		if(empty($submitmode)){
			$dealmodearr = array('0' => lang('increase'),'1' => lang('decrease'));
			trbasic(lang('operate_permi_set'),'mcommunew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($mcommu['setting']['apmid']) ? 0 : $mcommu['setting']['apmid']),'select');
			trbasic(lang('forbid_reoperate'),'mcommunew[setting][norepeat]',isset($mcommu['setting']['norepeat']) ? $mcommu['setting']['norepeat'] : 0,'radio');
			trbasic(lang('reoperate_time_m'),'mcommunew[setting][repeattime]',isset($mcommu['setting']['repeattime']) ? $mcommu['setting']['repeattime'] : 0);
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'mcommunew[ucadd]',empty($mcommu['ucadd']) ? '' : $mcommu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'mcommunew[usetting]',empty($mcommu['usetting']) ? '' : $mcommu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			$mcommunew['setting']['repeattime'] = max(0,intval($mcommunew['setting']['repeattime']));
		}
	}
}else include(M_ROOT.$mcommu['uconfig']);


?>