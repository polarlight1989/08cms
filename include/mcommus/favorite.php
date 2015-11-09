<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($mcommu['uconfig'])){
	if($action == 'mcommudetail'){
		if(empty($submitmode)){
			trbasic(lang('max_favorite_amount'),'mcommunew[setting][max]',isset($mcommu['setting']['max']) ? $mcommu['setting']['max'] : 0);
			trbasic(lang('operate_permi_set'),'mcommunew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($mcommu['setting']['apmid']) ? 0 : $mcommu['setting']['apmid']),'select');
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'mcommunew[ucadd]',empty($mcommu['ucadd']) ? '' : $mcommu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('custom_uadetail'),'mcommunew[uadetail]',empty($mcommu['uadetail']) ? '' : $mcommu['uadetail'],'text',lang('agmucustom'));
			trbasic(lang('custom_umdetail'),'mcommunew[umdetail]',empty($mcommu['umdetail']) ? '' : $mcommu['umdetail'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'mcommunew[usetting]',empty($mcommu['usetting']) ? '' : $mcommu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			$mcommunew['setting']['max'] = max(0,intval($mcommunew['setting']['max']));
		}
	}
}else include(M_ROOT.$mcommu['uconfig']);

?>