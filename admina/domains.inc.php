<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('domain') || amessage('no_apermission');
load_cache('domains');
if(empty($action)) $action = 'domainsedit';
if($action == 'domainsedit'){
	if(!submitcheck('bdomainsedit')){
		tabheader(lang('domain_admin')."&nbsp; &nbsp; >><a href=\"?entry=$entry&action=domainadd\" onclick=\"return floatwin('open_domains',this)\">".lang('add_domain').'</a>',$actionid.'arcsedit',"?entry=$entry&action=$action");
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),array(lang('folder'), 'txtL'),array(lang('domain'), 'txtL'),lang('isregular'),lang('order')));
		$query = $db->query("SELECT * FROM {$tblprefix}domains ORDER BY vieworder,id");
		while($item = $db->fetch_array($query)){
			$id = $item['id'];
			echo "<tr class=\"txt\">".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$id]\" value=\"$id\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"40\" name=\"domainsnew[$id][folder]\" value=\"$item[folder]\"></td>\n".
			"<td class=\"txtL\"><input type=\"text\" size=\"40\" name=\"domainsnew[$id][domain]\" value=\"$item[domain]\"></td>\n".
			"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"domainsnew[$id][isreg]\" value=\"1\" ".(empty($item['isreg']) ? '' : 'checked')."></td>\n".
			"<td class=\"txtC w40\"><input type=\"text\" size=\"4\" name=\"domainsnew[$id][vieworder]\" value=\"$item[vieworder]\"></td>\n".
			"</tr>\n";
		}
		tabfooter('bdomainsedit');
		a_guide('domainsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}domains WHERE id='$k'");
				unset($domainsnew[$k]);
			}
		}
		if(!empty($domainsnew)){
			foreach($domainsnew as $k => $v){
				$v['folder'] = trim(strip_tags($v['folder']));
				$v['domain'] = trim(strip_tags($v['domain']));
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$v['isreg'] = empty($v['isreg']) ? 0 : 1;
				if(!$v['folder'] || !$v['domain']) continue;
				$db->query("UPDATE {$tblprefix}domains SET domain='$v[domain]',folder='$v[folder]',isreg='$v[isreg]',vieworder='$v[vieworder]' WHERE id='$k'");
			}
		}
		adminlog(lang('edit_domain_list'));
		updatecache('domains'); 
		amessage('domaineditfin', "?entry=$entry&action=$action");
	}

}elseif($action == 'domainadd'){
	if(!submitcheck('bdomainadd')){
		tabheader(lang('add_domain'),'domainadd',"?entry=$entry&action=$action");
		trbasic(lang('folder'),'domainnew[folder]','','btext');
		trbasic(lang('domain'),'domainnew[domain]','','btext');
		trbasic(lang('isregular'),'domainnew[isreg]',0,'radio');
		tabfooter('bdomainadd');
		a_guide('domainsedit');
	}else{
		$domainnew['folder'] = trim(strip_tags($domainnew['folder']));
		$domainnew['domain'] = trim(strip_tags($domainnew['domain']));
		//if(!preg_match("/^(?:[A-Z0-9-]+\\.)?[A-Z0-9-]+\\.[A-Z]{2,4}$/i",$domainnew['domain'])) amessage('domainillegal',"?entry=$entry&action=domainsedit");
		//if(in_array($domainnew['domain'],array_keys($domains))) amessage('domainrepeat',"?entry=$entry&action=domainsedit");
		if(!$domainnew['folder'] || !$domainnew['domain']) amessage('datamissing',M_REFERER);
		$db->query("INSERT INTO {$tblprefix}domains SET 
					domain='$domainnew[domain]', 
					folder='$domainnew[folder]',
					isreg='$domainnew[isreg]'
					");
		adminlog(lang('add_domain'));
		updatecache('domains');
		amessage('domainaddfin', axaction(6,"?entry=$entry&action=domainsedit"));
	}
}

?>