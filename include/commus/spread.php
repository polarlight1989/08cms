<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			tabfooter();
			tabheader(lang('spread_idx'));
			trbasic(lang('choose_cutype'),'communew[setting][0][crid]',makeoption(cridsarr(),isset($commu['setting'][0]['crid']) ? $commu['setting'][0]['crid'] : 0),'select');
			trbasic(lang('awardcurrency'),'communew[setting][0][value]',isset($commu['setting'][0]['value']) ? $commu['setting'][0]['value'] : 0);
			trbasic(lang('spread_maxlimit'),'communew[setting][0][max]',isset($commu['setting'][0]['max']) ? $commu['setting'][0]['max'] : 0,'text',lang('use_record_limit'));
			tabfooter();
			tabheader(lang('spread_reg'));
			trbasic(lang('choose_cutype'),'communew[setting][1][crid]',makeoption(cridsarr(),isset($commu['setting'][1]['crid']) ? $commu['setting'][1]['crid'] : 0),'select');
			trbasic(lang('awardcurrency'),'communew[setting][1][value]',isset($commu['setting'][1]['value']) ? $commu['setting'][1]['value'] : 0);
			trbasic(lang('spread_maxlimit'),'communew[setting][1][max]',isset($commu['setting'][1]['max']) ? $commu['setting'][1]['max'] : 0,'text',lang('use_record_limit'));/*
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'communew[ucadd]',empty($commu['ucadd']) ? '' : $commu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('custom_umdetail'),'communew[umdetail]',empty($commu['umdetail']) ? '' : $commu['umdetail'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'communew[usetting]',empty($commu['usetting']) ? '' : $commu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";*/
		}else{
			(empty($communew['setting'][0]['crid']) || empty($communew['setting'][1]['crid'])) && amessage('choose_cutype',axaction(2,M_REFERER));
			$communew['setting'][0]['value'] = max(0,intval($communew['setting'][0]['value']));
			$communew['setting'][0]['max'] = max(0,intval($communew['setting'][0]['max']));
			$communew['setting'][0]['count'] = $communew['setting'][0]['value'] ? intval($communew['setting'][0]['max'] / $communew['setting'][0]['value']) : 0;
			$communew['setting'][1]['value'] = max(0,intval($communew['setting'][1]['value']));
			$communew['setting'][1]['max'] = max(0,intval($communew['setting'][1]['max']));
			$communew['setting'][1]['count'] = $communew['setting'][1]['value'] ? intval($communew['setting'][1]['max'] / $communew['setting'][1]['value']) : 0;
		}
	}elseif($action == 'commulink'){
		trbasic(lang('spread_url'),'','{$cms_abs}tools/spread.php?uid={mname}','');
		trbasic(lang('spread_reg'),'','{$cms_abs}register.php?uid={mname}','');
		trbasic(lang('spread_js_mode'),'','&lt;script type="text/javascript" src="{$cms_abs}tools/spread.php">&lt;/script>','',lang('spread_js_tip'));
	}
}else include(M_ROOT.$commu['uconfig']);


?>