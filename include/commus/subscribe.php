<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			trbasic(lang('operate_permi_set'),'communew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($commu['setting']['apmid']) ? 0 : $commu['setting']['apmid']),'select');
			$autoarcarr = array(0 => lang('auto_purchase'),1 => lang('confirm_purchase'),);
			trbasic(lang('arc_subscribe_mode'),'',makeradio('communew[setting][autoarc]',$autoarcarr,isset($commu['setting']['autoarc']) ? $commu['setting']['autoarc'] : 0),'');
			trbasic(lang('att_subscribe_mode'),'',makeradio('communew[setting][autoatm]',$autoarcarr,isset($commu['setting']['autoatm']) ? $commu['setting']['autoatm'] : 0),'');
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'communew[ucadd]',empty($commu['ucadd']) ? '' : $commu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'communew[usetting]',empty($commu['usetting']) ? '' : $commu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}
	}elseif($action == 'commulink'){
		trbasic(lang('arc_subscribe_pick_url'),'','{$cms_abs}tools/subscribe.php?aid={aid}','');
		trbasic(lang('att_subscribe_pick_url'),'','{$cms_abs}tools/subscribe.php?aid={aid}&isatm=1','');
	}
}else include(M_ROOT.$commu['uconfig']);


?>