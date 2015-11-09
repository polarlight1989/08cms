<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			load_cache('ofields,commus');
			trbasic(lang('is_allowance_citem'),'communew[allowance]',$commu['allowance'],'radio');
			trbasic(lang('operate_permi_set'),'communew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($commu['setting']['apmid']) ? 0 : $commu['setting']['apmid']),'select');
			trbasic(lang('offer_msg_autocheck'),'communew[setting][autocheck]',isset($commu['setting']['autocheck']) ? $commu['setting']['autocheck'] : 0,'radio');
			trbasic(lang('offer_msg_ava_days'),'communew[setting][vdays]',isset($commu['setting']['vdays']) ? $commu['setting']['vdays'] : 0);
			trbasic(lang('purchase_type_set'),'communew[setting][purchase]',makeoption(array(0 => lang('nopurchse')) + cuidsarr('purchase'),empty($commu['setting']['purchase']) ? 0 : $commu['setting']['purchase']),'select');
			trbasic(lang('nouservote'),'communew[setting][nouservote]',isset($commu['setting']['nouservote']) ? $commu['setting']['nouservote'] : 0,'radio');
			trbasic(lang('repeatvote'),'communew[setting][repeatvote]',isset($commu['setting']['repeatvote']) ? $commu['setting']['repeatvote'] : 0,'radio');
			$itemsarr = array();
			foreach($ucotypes as $k => $v) if($v['cclass'] == $commu['cclass']) $itemsarr['uccid'.$k] = $v['cname'];
			foreach($ofields as $k => $v) $itemsarr[$k] = $v['cname'];
			trbasic(lang('cu_citems')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_1\" onclick=\"checkall(this.form,'citemsnew','chkall_1')\">".lang('selectall'),'',makecheckbox('citemsnew[]',$itemsarr,empty($commu['setting']['citems']) ? array() : explode(',',$commu['setting']['citems']),5),'');
			trbasic(lang('cu_useredits')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_5\" onclick=\"checkall(this.form,'usereditsnew','chkall_5')\">".lang('selectall'),'',makecheckbox('usereditsnew[]',$itemsarr,empty($commu['setting']['useredits']) ? array() : explode(',',$commu['setting']['useredits']),5),'');
			tabfooter();
	
			tabheader(lang('auto_pro_av_price'));
			trbasic(lang('is_create_av_price'),'communew[setting][average]',isset($commu['setting']['average']) ? $commu['setting']['average'] : 0,'radio');
			$tablearr = array('main' => lang('common_field'),'custom' => lang('channel_field'));
			trbasic(lang('av_price_field_type'),'communew[setting][ptable]',makeradio('communew[setting][ptable]',$tablearr,isset($commu['setting']['ptable']) ? $commu['setting']['ptable'] : 'main'),'');
			trbasic(lang('av_price_field_ename'),'communew[setting][pename]',isset($commu['setting']['pename']) ? $commu['setting']['pename'] : '');
			tabfooter();
	
			tabheader(lang('udef_func')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('funcsetting')\">".lang('view'));
			echo "<tbody id=\"funcsetting\" style=\"display: none;\">";
			trbasic(lang('php_func_code'),'communew[func]',empty($commu['func']) ? '' : $commu['func'],'btextarea');
			echo "</tbody>";
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
			foreach(array('citems','useredits',) as $var) $communew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
			$communew['setting']['vdays'] = max(0,intval($communew['setting']['vdays']));
			$communew['setting']['pename'] = trim($communew['setting']['pename']);
			if(preg_match("/[^a-z_A-Z0-9]+/",$communew['setting']['pename'])) $communew['setting']['pename'] = '';
		}
	}elseif($action == 'commulink'){
		trbasic(lang('m_add_edit_offer'),'','{$cms_abs}tools/offer.php?aid={aid}','');
		trbasic(lang('vote_url'),'','{$cms_abs}tools/offer.php?action=vote&cid={cid}&option=xx (xx-'.lang('vote_option').')','');
		trbasic(lang('arc_offer_list'),'','{$cms_abs}offers.php?aid={aid}','');
	}
}else include(M_ROOT.$commu['uconfig']);


?>