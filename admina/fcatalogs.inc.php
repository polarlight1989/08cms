<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('freeinfo') || amessage('no_apermission');
load_cache('fcatalogs,fchannels,currencys,grouptypes,mtpls,permissions,cotypes');
$fchidsarr = fchidsarr();
empty($fchidsarr) && amessage('deffrecha');
$url_type = 'fcata';include'urlsarr.inc.php';

if($action == 'fcatalogsedit'){
url_nav(lang('pluginframework'),$urlsarr,'coclass');
	if(!submitcheck('bfcatalogsedit') && !submitcheck('bfcatalogadd')){
		tabheader(lang('msg_coclass_manager'),'fcatalogsedit','?entry=fcatalogs&action=fcatalogsedit','7');
		trcategory(array(lang('id'),lang('cocname'),lang('order'),lang('channel'),lang('consult'),lang('detail'),lang('delete')));
		$mcatalogarr = array(0 => lang('topiccoclass'));
		foreach($fcatalogs as $fcaid => $fcatalog){
			$fcatalog = read_cache('fcatalog',$fcaid);
			empty($fcatalog['pid']) && $mcatalogarr[$fcaid] = $fcatalog['title'];
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\">$fcaid</td>\n".
				"<td class=\"txtL\">" . (empty($fcatalog['pid']) ? '' : ' &nbsp; &nbsp; &nbsp; &nbsp;'). "<input type=\"text\" name=\"fcatalogsnew[$fcaid][title]\" value=\"".mhtmlspecialchars($fcatalog['title'])."\" size=\"25\" maxlength=\"30\"></td>\n".
				"<td class=\"txtC w50\"><input type=\"text\" name=\"fcatalogsnew[$fcaid][vieworder]\" value=\"$fcatalog[vieworder]\" size=\"2\"></td>\n".
				"<td class=\"txtC w100\">".mhtmlspecialchars($fchannels[$fcatalog['chid']]['cname'])."</td>\n".
				"<td class=\"txtC w30\">".($fcatalog['cumode'] ? 'Y' : '-')."</td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=fcatalogs&action=fcatalogdetail&fcaid=$fcaid\" onclick=\"return floatwin('open_fcatalogsedit',this)\">".lang('setting')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=fcatalogs&action=fcatalogdelete&fcaid=$fcaid\">".lang('delete')."</a></td>\n".
				"</tr>";
		}
		tabfooter('bfcatalogsedit');
		tabheader(lang('add_msg_coclass'),'fcatalogadd','?entry=fcatalogs&action=fcatalogsedit');
		trbasic(lang('cocname'),'fcatalognew[title]','','text');
		trbasic(lang('freeinfo_channel'),'fcatalognew[chid]',makeoption($fchidsarr),'select');
		trbasic(lang('belongcocl'),'fcatalognew[fcaid]',makeoption($mcatalogarr),'select');
		trbasic(lang('weather_consult_coclass'),'fcatalognew[cumode]',0,'radio');
		tabfooter('bfcatalogadd');
		a_guide('fcatalogsedit');
	}elseif(submitcheck('bfcatalogsedit')){
		if(!empty($fcatalogsnew)){
			foreach($fcatalogsnew as $fcaid => $fcatalognew){
				$fcatalognew['title'] = $fcatalognew['title'] ? $fcatalognew['title'] : $fcatalogs[$fcaid]['title'];
				$fcatalognew['vieworder'] = max(0,intval($fcatalognew['vieworder']));
				if(($fcatalognew['title'] != $fcatalogs[$fcaid]['title']) || ($fcatalognew['vieworder'] != $fcatalogs[$fcaid]['vieworder'])){
					$db->query("UPDATE {$tblprefix}fcatalogs SET 
								title='$fcatalognew[title]', 
								vieworder='$fcatalognew[vieworder]' 
								WHERE fcaid='$fcaid'
								");
				}
			}
			updatecache('fcatalogs');
		}
		adminlog(lang('edit_freeinfo_list'));
		amessage('cocledifin', '?entry=fcatalogs&action=fcatalogsedit');
	}elseif(submitcheck('bfcatalogadd')){
		if(!$fcatalognew['title'])amessage('datamissing','history.go(-1)');
		
		($fcatalog = read_cache('fcatalog',$fcatalognew['fcaid'])) && !empty($fcatalog['pid']) && amessage('invoperate');
		$fcatalog || $fcatalognew['fcaid'] = 0;
		$db->query("INSERT INTO {$tblprefix}fcatalogs SET 
					title='$fcatalognew[title]', 
					pid='$fcatalognew[fcaid]',
					chid='$fcatalognew[chid]', 
					cumode='$fcatalognew[cumode]'
					");
		updatecache('fcatalogs');
		adminlog(lang('add_freeinfo_coclass'));
		amessage('mescocaddfin', '?entry=fcatalogs&action=fcatalogsedit');
	}
}elseif($action =='fcatalogdetail' && $fcaid){
	$fcatalog = read_cache('fcatalog',$fcaid);
	if(!submitcheck('bfcatalogdetail')){
		tabheader(lang('msg_coclass_set')."&nbsp;&nbsp;[$fcatalog[title]]",'fcatalogdetail','?entry=fcatalogs&action=fcatalogdetail&fcaid='.$fcaid,2,1);
		trbasic(lang('freeinfo_channel'),'',$fchannels[$fcatalog['chid']]['cname'],'');
		if(!$db->result_one("SELECT COUNT(*) FROM {$tblprefix}fcatalogs WHERE pid='$fcaid'")){
			$mcatalogarr = array(0 => lang('topiccoclass'));
			foreach($fcatalogs as $pid => $fcg)empty($fcg['pid']) && $pid != $fcaid && $mcatalogarr[$pid] = $fcg['title'];
			trbasic(lang('belongcocl'),'fcatalognew[pid]',makeoption($mcatalogarr,$fcatalog['pid']),'select');
		}
		trbasic(lang('issue_permission_set'),'fcatalognew[apmid]',makeoption(pmidsarr('fadd'),$fcatalog['apmid']),'select');
		trbasic(lang('msg_auto_check'),'fcatalognew[autocheck]',$fcatalog['autocheck'],'radio');
		trbasic(lang('author_update_checked_msg'),'fcatalognew[allowupdate]',$fcatalog['allowupdate'],'radio');
		trbasic(lang('nodurat'),'fcatalognew[nodurat]',$fcatalog['nodurat'],'radio');
		trbasic(lang('msg_con_tpl'),'fcatalognew[arctpl]',makeoption(array('' => lang('noset')) + mtplsarr('freeinfo'),$fcatalog['arctpl']),'select');
		if($fcatalog['cumode']){
			tabfooter();
			tabheader(lang('consult_set'));
			if($fcatalog['cumode']) trbasic(lang('reply_permission_set'),'fcatalognew[rpmid]',makeoption(pmidsarr('fadd'),$fcatalog['rpmid']),'select');//资讯回复权限设置，使用插件添加权限组
			trbasic(lang('consult_pics'),'fcatalognew[culength]',$fcatalog['culength']);
		}		
		tabfooter();
		if(!empty($fcatalog['usetting'])){
			$str = '';
			foreach($fcatalog['usetting'] as $k => $v) $str .= $k.'='.$v."\n";	
			$fcatalog['usetting'] = $str;
			unset($str);
		}
		tabheader(lang('advsetting')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"viewdetail2\" value=\"1\" onclick=\"alterview('advsetting')\">".lang('view'));
		echo "<tbody id=\"advsetting\" style=\"display: none;\">";
		trbasic(lang('custom_ucadd'),'fcatalognew[ucadd]',empty($fcatalog['ucadd']) ? '' : $fcatalog['ucadd'],'text',lang('agmucustom'));
		trbasic(lang('custom_uaadd'),'fcatalognew[uaadd]',empty($fcatalog['uaadd']) ? '' : $fcatalog['uaadd'],'text',lang('agmucustom'));
		trbasic(lang('custom_uadetail'),'fcatalognew[uadetail]',empty($fcatalog['uadetail']) ? '' : $fcatalog['uadetail'],'text',lang('agmucustom'));
		trbasic(lang('custom_umdetail'),'fcatalognew[umdetail]',empty($fcatalog['umdetail']) ? '' : $fcatalog['umdetail'],'text',lang('agmucustom'));
		trbasic(lang('customsetting'),'fcatalognew[usetting]',empty($fcatalog['usetting']) ? '' : $fcatalog['usetting'],'btextarea',lang('agcustomsetting'));
		echo "</tbody>";
		tabfooter('bfcatalogdetail');
		a_guide('fcatalogdetail');
	}else{
		$fcatalognew['culength'] = empty($fcatalognew['culength']) ? 0 : max(0,intval($fcatalognew['culength']));
		$fcatalognew['pid'] = !$db->result_one("SELECT COUNT(*) FROM {$tblprefix}fcatalogs WHERE pid='$fcaid'") && $fcaid != $fcatalognew['pid'] ? max(0, intval($fcatalognew['pid'])) : 0;
		empty($fcatalognew['rpmid']) && $fcatalognew['rpmid'] = 0;
		$fcatalognew['ucadd'] = empty($fcatalognew['ucadd']) ? '' : trim($fcatalognew['ucadd']);
		$fcatalognew['uaadd'] = empty($fcatalognew['uaadd']) ? '' : trim($fcatalognew['uaadd']);
		$fcatalognew['uadetail'] = empty($fcatalognew['uadetail']) ? '' : trim($fcatalognew['uadetail']);
		$fcatalognew['umdetail'] = empty($fcatalognew['umdetail']) ? '' : trim($fcatalognew['umdetail']);
		if(!empty($fcatalognew['usetting'])){
			$fcatalognew['usetting'] = str_replace("\r","",$fcatalognew['usetting']);
			$temps = explode("\n",$fcatalognew['usetting']);
			$fcatalognew['usetting'] = array();
			foreach($temps as $v){
				$temparr = explode('=',str_replace(array("\r","\n"),'',$v));
				if(!isset($temparr[1]) || !($temparr[0] = trim($temparr[0]))) continue;
				$fcatalognew['usetting'][$temparr[0]] = trim($temparr[1]);
			}
			unset($temps,$temparr);
		}
		$fcatalognew['usetting'] = !empty($fcatalognew['usetting']) ? addslashes(serialize($fcatalognew['usetting'])) : '';
		$db->query("UPDATE {$tblprefix}fcatalogs SET
			pid='$fcatalognew[pid]', 
			autocheck='$fcatalognew[autocheck]', 
			allowupdate='$fcatalognew[allowupdate]', 
			nodurat='$fcatalognew[nodurat]', 
			arctpl='$fcatalognew[arctpl]', 
			culength='$fcatalognew[culength]',
			apmid='$fcatalognew[apmid]',
			rpmid='$fcatalognew[rpmid]',
			ucadd='$fcatalognew[ucadd]',
			uaadd='$fcatalognew[uaadd]',
			uadetail='$fcatalognew[uadetail]',
			umdetail='$fcatalognew[umdetail]',
			usetting='$fcatalognew[usetting]'
			WHERE fcaid='$fcaid'");
		updatecache('fcatalogs');
		adminlog(lang('detail0_modify_freeinfo'));
		amessage('coclasssetfinish', axaction(6,'?entry=fcatalogs&action=fcatalogsedit'));
	}

}
elseif($action == 'fcatalogdelete' && $fcaid) {
	if(!isset($confirm) || $confirm != 'ok') {
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=fcatalogs&action=fcatalogdelete&fcaid=".$fcaid."&confirm=ok>".lang('delete')."</a><br>";
		$message .= lang('giveupclick').">><a href=?entry=fcatalogs&action=fcatalogsedit>".lang('goback')."</a>";
		amessage($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}farchives WHERE fcaid='$fcaid'") || $db->result_one("SELECT COUNT(*) FROM {$tblprefix}fcatalogs WHERE pid='$fcaid'")){
		amessage('cocwitarccandel', '?entry=fcatalogs&action=fcatalogsedit');
	}
	$db->query("DELETE FROM {$tblprefix}fcatalogs WHERE fcaid='$fcaid'");
	updatecache('fcatalogs');
	adminlog(lang('del_freeinfo_coclass'));
	amessage('cocdelefini', '?entry=fcatalogs&action=fcatalogsedit');
}else amessage('errorparament');

?>
