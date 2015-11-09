<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('permission') || amessage('no_apermission');
load_cache('permissions,grouptypes');

if($action == 'permissionsedit'){
	if(!submitcheck('bpermissionsedit') && !submitcheck('bpermissionsadd')) {
		tabheader(lang('permprojmana'),'permissionsedit','?entry=permissions&action=permissionsedit','6');
		trcategory(array(lang('delete'),lang('projectname'),lang('order'),lang('archivebrowse'),lang('cnbrowse'),lang('arc_issue'),lang('farcissue'),lang('cuissue'),lang('check'),lang('atmdown'),lang('mcmenu'),lang('fieldpm'),lang('template'),lang('edit')));
		foreach($permissions as $k => $v){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"></td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" name=\"permissionsnew[$k][cname]\" value=\"$v[cname]\"></td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" size=\"4\" name=\"permissionsnew[$k][vieworder]\" value=\"$v[vieworder]\"></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][aread]\" value=\"1\"".($v['aread'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][cread]\" value=\"1\"".($v['cread'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][aadd]\" value=\"1\"".($v['aadd'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][fadd]\" value=\"1\"".($v['fadd'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][cuadd]\" value=\"1\"".($v['cuadd'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][chk]\" value=\"1\"".($v['chk'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][down]\" value=\"1\"".($v['down'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][menu]\" value=\"1\"".($v['menu'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][field]\" value=\"1\"".($v['field'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"permissionsnew[$k][tpl]\" value=\"1\"".($v['tpl'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=permissions&action=permissionsdetail&pmid=$k\" onclick=\"return floatwin('open_permissionsedit',this)\">".lang('detail')."</a></td></tr>\n";
		}
		tabfooter('bpermissionsedit',lang('modify'));

		tabheader(lang('addpermiproj'),'permissionsadd','?entry=permissions&action=permissionsedit');
		trbasic(lang('projectname'),'permissionadd[cname]');
		tabfooter('bpermissionsadd',lang('add'));
		a_guide('permissionsedit');
	}elseif(submitcheck('bpermissionsadd')){
		if(!$permissionadd['cname']) amessage('datamissing',M_REFERER);
		$db->query("INSERT INTO {$tblprefix}permissions SET 
					cname='$permissionadd[cname]'
					");
		adminlog(lang('addpermiproj'));
		updatecache('permissions');
		amessage('proaddfin',M_REFERER);
	}elseif(submitcheck('bpermissionsedit')){
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}permissions WHERE pmid=$k");
				unset($permissionsnew[$k]);
			}
		}
		foreach($permissionsnew as $k => $v){
			$v['cname'] = !$v['cname'] ? $permissions[$k]['cname'] : $v['cname'];
			$sqlstr = '';
			foreach(array('aread','cread','aadd','fadd','cuadd','chk','down','menu','field','tpl',) as $var) $sqlstr .= "$var='".(empty($v[$var]) ? 0 : 1)."',";
			$v['vieworder'] = max(0,intval($v['vieworder']));
			$db->query("UPDATE {$tblprefix}permissions SET 
						cname='$v[cname]',
						$sqlstr
						vieworder='$v[vieworder]'
						WHERE pmid='$k'");
		}
		adminlog(lang('edipermpromanlist'));
		updatecache('permissions');
		amessage('promodfin',M_REFERER);
	}
}
if($action == 'permissionsdetail' && $pmid){
	$permission = $permissions[$pmid];
	if(!submitcheck('bpermissionsdetail')) {
		tabheader(lang('content_permissions'),'permissionsdetail','?entry=permissions&action=permissionsdetail&pmid='.$pmid);
		trbasic(lang('projectname'),'',$permission['cname'],'');
		tr_ugids(lang('pugidsbelow'),'ugidsnew',empty($permission['ugids']) ? array() : explode(',',$permission['ugids']));
		tr_ugids(lang('fugidsbelow'),'ugidsfnew',empty($permission['fugids']) ? array() : explode(',',$permission['fugids']),1);
		tabfooter('bpermissionsdetail',lang('modify'));
		a_guide('permissionsdetail');
	}
	else{
		$ugidsnew = empty($ugidsnew) ? array() : (in_array('-1',$ugidsnew) ? array(-1) : $ugidsnew);
		$ugidsnew = empty($ugidsnew) ? '' : implode(',',$ugidsnew);
		$ugidsfnew = empty($ugidsfnew) ? '' : implode(',',$ugidsfnew);
		$db->query("UPDATE {$tblprefix}permissions SET ugids='$ugidsnew',fugids='$ugidsfnew' WHERE pmid='$pmid'");
		adminlog(lang('detmodpermpro'));
		updatecache('permissions');
		amessage('promodfin', axaction(6,'?entry=permissions&action=permissionsedit'));
	}
}
?>
