<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($commu['uconfig'])){
	if($action == 'commudetail'){
		if(empty($submitmode)){
			global $bfields;
			load_cache('bfields');
			trbasic(lang('operate_permi_set'),'communew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($commu['setting']['apmid']) ? 0 : $commu['setting']['apmid']),'select');
			trbasic(lang('allow_repeat'),'communew[setting][repeat]',isset($commu['setting']['repeat']) ? $commu['setting']['repeat'] : 0,'radio');
			trbasic(lang('repeat_time_m'),'communew[setting][repeattime]',isset($commu['setting']['repeattime']) ? $commu['setting']['repeattime'] : 0);
			$itemsarr = array();
			foreach($ucotypes as $k => $v) if($v['cclass'] == $commu['cclass']) $itemsarr['uccid'.$k] = $v['cname'];
			foreach($bfields as $k => $v) $itemsarr[$k] = $v['cname'];
			trbasic(lang('cu_citems')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_1\" onclick=\"checkall(this.form,'citemsnew','chkall_1')\">".lang('selectall'),'',makecheckbox('citemsnew[]',$itemsarr,empty($commu['setting']['citems']) ? array() : explode(',',$commu['setting']['citems']),5),'');
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'communew[ucadd]',empty($commu['ucadd']) ? '' : $commu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('custom_uadetail'),'communew[uadetail]',empty($commu['uadetail']) ? '' : $commu['uadetail'],'text',lang('agmucustom'));
			trbasic(lang('custom_umdetail'),'communew[umdetail]',empty($commu['umdetail']) ? '' : $commu['umdetail'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'communew[usetting]',empty($commu['usetting']) ? '' : $commu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			foreach(array('citems',) as $var) $communew['setting'][$var] = empty(${$var.'new'}) ? '' : implode(',',${$var.'new'});
			$communew['setting']['repeattime'] = max(0,intval($communew['setting']['repeattime']));
		}
	}elseif($action == 'commulink'){
		trbasic(lang('add_comment'),'','{$cms_abs}tools/report.php?aid={aid}','');
		trbasic(lang('admin_arc_comment'),'','{$cms_abs}tools/report.php?action=admin&aid={aid}','');
		trbasic(lang('edit_point_comment'),'','{$cms_abs}tools/report.php?action=edit&aid={aid}&cid={cid}','');
	}
}else include(M_ROOT.$commu['uconfig']);


?>