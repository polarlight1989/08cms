<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($mcommu['uconfig'])){
	if($action == 'mcommudetail'){
		if(empty($submitmode)){
			trbasic(lang('operate_permi_set'),'mcommunew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($mcommu['setting']['apmid']) ? 0 : $mcommu['setting']['apmid']),'select');
			trbasic(lang('friautche'),'mcommunew[setting][autocheck]',isset($mcommu['setting']['autocheck']) ? $mcommu['setting']['autocheck'] : 0,'radio');
			trbasic(lang('frimaxamo'),'mcommunew[setting][max]',isset($mcommu['setting']['max']) ? $mcommu['setting']['max'] : 0);
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'mcommunew[ucadd]',empty($mcommu['ucadd']) ? '' : $mcommu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('custom_uadetail'),'mcommunew[uadetail]',empty($mcommu['uadetail']) ? '' : $mcommu['uadetail'],'text',lang('agmucustom'));
			trbasic(lang('custom_umdetail'),'mcommunew[umdetail]',empty($mcommu['umdetail']) ? '' : $mcommu['umdetail'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'mcommunew[usetting]',empty($mcommu['usetting']) ? '' : $mcommu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			$mcommunew['setting']['fields'] = empty($fieldsnew) ? '' : implode(',',$fieldsnew);
		}
	}
}else include(M_ROOT.$mcommu['uconfig']);


?>