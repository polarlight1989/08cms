<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			trbasic(lang('operate_permi_set'),'communew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($commu['setting']['apmid']) ? 0 : $commu['setting']['apmid']),'select');
			trbasic(lang('nouservote'),'communew[setting][nouservote]',isset($commu['setting']['nouservote']) ? $commu['setting']['nouservote'] : 0,'radio');
			trbasic(lang('repeatvote'),'communew[setting][repeatvote]',isset($commu['setting']['repeatvote']) ? $commu['setting']['repeatvote'] : 0,'radio');
			trbasic(lang('answer_minlength'),'communew[setting][minlength]',isset($commu['setting']['minlength']) ? $commu['setting']['minlength'] : 0);
			trbasic(lang('answer_maxlength'),'communew[setting][maxlength]',isset($commu['setting']['maxlength']) ? $commu['setting']['maxlength'] : 0);
			trbasic(lang('item_ava_days'),'communew[setting][vdays]',isset($commu['setting']['vdays']) ? $commu['setting']['vdays'] : 0);
			trbasic(lang('reward_currency_type'),'communew[setting][crid]',makeoption(cridsarr(),isset($commu['setting']['crid']) ? $commu['setting']['crid'] : 0),'select');
			trbasic(lang('allow_reward_mini_cu'),'communew[setting][mini]',isset($commu['setting']['mini']) ? $commu['setting']['mini'] : 0);
			trbasic(lang('allow_reward_max_cu'),'communew[setting][max]',isset($commu['setting']['max']) ? $commu['setting']['max'] : 0);
			trbasic(lang('credit_val_reward_cu'),'communew[setting][credit]',isset($commu['setting']['credit']) ? $commu['setting']['credit'] : 0);
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'communew[ucadd]',empty($commu['ucadd']) ? '' : $commu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('custom_ucvote'),'communew[ucvote]',empty($commu['ucvote']) ? '' : $commu['ucvote'],'text',lang('agmucustom'));
			trbasic(lang('custom_uadetail'),'communew[uadetail]',empty($commu['uadetail']) ? '' : $commu['uadetail'],'text',lang('agmucustom'));
			trbasic(lang('custom_umdetail'),'communew[umdetail]',empty($commu['umdetail']) ? '' : $commu['umdetail'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'communew[usetting]',empty($commu['usetting']) ? '' : $commu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			if(empty($communew['setting']['crid'])) amessage('choose_reward_cutype',axaction(2,M_REFERER));
			$communew['setting']['minlength'] = max(0,intval($communew['setting']['minlength']));
			$communew['setting']['maxlength'] = max(0,intval($communew['setting']['maxlength']));
			$communew['setting']['vdays'] = max(0,intval($communew['setting']['vdays']));
			$communew['setting']['mini'] = max(0,intval($communew['setting']['mini']));
			$communew['setting']['max'] = max(0,intval($communew['setting']['max']));
			$communew['setting']['credit'] = max(0,intval($communew['setting']['credit']));
		}
	}elseif($action == 'commulink'){
		trbasic(lang('answer_pick_url'),'','{$cms_abs}tools/answer.php?aid={aid}','');
		trbasic(lang('vote_url'),'','{$cms_abs}tools/answer.php?action=vote&cid={cid}&option=xx (xx-'.lang('vote_option').')','');
		trbasic(lang('answer_list'),'','{$cms_abs}answers.php?aid={aid}','');
	}
}else include(M_ROOT.$commu['uconfig']);


?>