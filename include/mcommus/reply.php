<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
if(empty($mcommu['uconfig'])){
	if($action == 'mcommudetail'){
		if(empty($submitmode)){
			load_cache('mrfields');
			trbasic(lang('operate_permi_set'),'mcommunew[setting][apmid]',makeoption(pmidsarr('cuadd'),empty($mcommu['setting']['apmid']) ? 0 : $mcommu['setting']['apmid']),'select');
			trbasic(lang('reply_autocheck'),'mcommunew[setting][autocheck]',isset($mcommu['setting']['autocheck']) ? $mcommu['setting']['autocheck'] : 0,'radio');
			trbasic(lang('forbid_repeat_add'),'mcommunew[setting][norepeat]',isset($mcommu['setting']['norepeat']) ? $mcommu['setting']['norepeat'] : 0,'radio');
			trbasic(lang('repeat_add_time_m'),'mcommunew[setting][repeattime]',isset($mcommu['setting']['repeattime']) ? $mcommu['setting']['repeattime'] : 0);
			$fieldsarr = array();
			foreach($mrfields as $k => $v) $fieldsarr[$k] = $v['cname'];
			trbasic(lang('ava_msg_field'),'fieldsnew[]',makecheckbox('fieldsnew[]',$fieldsarr,empty($mcommu['setting']['fields']) ? array() : explode(',',$mcommu['setting']['fields']),5),'');
			tabfooter();
	
			tabheader(lang('udef_func')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail\" value=\"1\" onclick=\"alterview('funcsetting')\">".lang('view'));
			echo "<tbody id=\"funcsetting\" style=\"display: none;\">";
			trbasic(lang('php_func_code'),'mcommunew[func]',empty($mcommu['func']) ? '' : $mcommu['func'],'btextarea');
			echo "</tbody>";
			tabfooter();
			tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
			echo "<tbody id=\"advsetting\" style=\"display: none;\">";
			trbasic(lang('custom_ucadd'),'mcommunew[ucadd]',empty($mcommu['ucadd']) ? '' : $mcommu['ucadd'],'text',lang('agmucustom'));
			trbasic(lang('custom_uadetail'),'mcommunew[uadetail]',empty($mcommu['uadetail']) ? '' : $mcommu['uadetail'],'text',lang('agmucustom'));
			trbasic(lang('custom_umdetail'),'mcommunew[umdetail]',empty($mcommu['umdetail']) ? '' : $mcommu['umdetail'],'text',lang('agmucustom'));
			trbasic(lang('customsetting'),'mcommunew[usetting]',empty($mcommu['usetting']) ? '' : $mcommu['usetting'],'btextarea',lang('agcustomsetting'));
			echo "</tbody>";
		}else{
			$mcommunew['setting']['repeattime'] = max(0,intval($mcommunew['setting']['repeattime']));
			$mcommunew['setting']['fields'] = empty($fieldsnew) ? '' : implode(',',$fieldsnew);
		}
	}
}else include(M_ROOT.$mcommu['uconfig']);


?>