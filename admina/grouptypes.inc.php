<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('grouptype') || amessage('no_apermission');
load_cache('grouptypes,currencys,mchannels');
if($action == 'grouptypesedit'){
	if(!submitcheck('bgrouptypesadd') && !submitcheck('bgrouptypesedit')){
		$modearr = array('0' => lang('user_handwork'),'1' => lang('admin_handwork'),'2' => lang('crbase'),'3' => lang('crex'),);
		$cridsarr = array(0 => lang('noset')) + cridsarr();
		$itemstr = '';
		foreach($grouptypes as $k => $grouptype){
			$modestr = $modearr[$grouptype['mode']];
			$cridstr = empty($grouptype['crid']) || empty($cridsarr[$grouptype['crid']]) ? '-' : $cridsarr[$grouptype['crid']];
			if(empty($grouptype['crid']) && $grouptype['mode'] == 3) $cridstr = lang('cash');
			$itemstr .= "<tr class=\"txt\"><td class=\"txtC w30\">$k</td>\n".
					"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"".($grouptype['issystem'] ? ' disabled' : '')."></td>\n".
					"<td class=\"txtC\"><input type=\"text\" size=\"25\" maxlength=\"30\" name=\"grouptypesnew[$k][cname]\" value=\"$grouptype[cname]\"></td>\n".
					"<td class=\"txtC w60\">$modestr</td>\n".
					"<td class=\"txtC w60\">$cridstr</td>\n".
					"<td class=\"txtC w40\"><a href=\"?entry=grouptypes&action=grouptypedetail&gtid=$k\" onclick=\"return floatwin('open_grouptypesedit',this)\">".lang('setting')."</a></td>\n".
					"<td class=\"txtC w50\"><a href=\"?entry=usergroups&action=usergroupsedit&gtid=$k\" onclick=\"return floatwin('open_grouptypesedit',this)\">".lang('admin')."</a></td></tr>\n";
		}
		tabheader(lang('edit_grouptype'),'grouptypesedit','?entry=grouptypes&action=grouptypesedit','7');
		trcategory(array(lang('id'),lang('delete'),lang('grouptype_name'),lang('deal_mode'),lang('related_currency'),lang('detail'),lang('usergroup')));
		echo $itemstr;
		tabfooter('bgrouptypesedit',lang('modify'));

		tabheader(lang('add_grouptype'),'grouptypesadd','?entry=grouptypes&action=grouptypesedit');
		trbasic(lang('grouptype_name'),'grouptypeadd[cname]');
		trbasic(lang('deal_mode'),'grouptypeadd[mode]',makeoption($modearr),'select');
		trbasic(lang('related_cutype'),'grouptypeadd[crid]',makeoption($cridsarr),'select');
		tabfooter('bgrouptypesadd',lang('add'));
		a_guide('grouptypesedit');
	}elseif(submitcheck('bgrouptypesadd')){
		if(empty($grouptypeadd['cname']) || (($grouptypeadd['mode'] == 2) && empty($grouptypeadd['crid']))){
			amessage('groupdatamis','?entry=grouptypes&action=grouptypesedit');
		}
		$grouptypeadd['crid'] = $grouptypeadd['mode'] < 2 ? 0 : $grouptypeadd['crid'];
		$db->query("INSERT INTO {$tblprefix}grouptypes SET
					cname='$grouptypeadd[cname]',
					mode='$grouptypeadd[mode]',
					crid='$grouptypeadd[crid]'");
		if(!$gtid = $db->insert_id()){
			amessage('grouperrsave','?entry=grouptypes&action=grouptypesedit');
		}else{
			$addfieldid = 'grouptype'.$gtid;
			$addfielddate = 'grouptype'.$gtid.'date';
			$db->query("ALTER TABLE {$tblprefix}members ADD $addfieldid smallint(6) unsigned NOT NULL default 0", 'SILENT');
			$db->query("ALTER TABLE {$tblprefix}members ADD $addfielddate int(10) unsigned NOT NULL default 0", 'SILENT');
		}
		adminlog(lang('add_grouptype'));
		updatecache('grouptypes');
		amessage('grouaddfin',"?entry=grouptypes&action=grouptypesedit");
	}elseif(submitcheck('bgrouptypesedit')){
		if(!empty($delete)){
			foreach($delete as $gtid) {
				if(empty($grouptypes[$gtid]['issystem'])){
					if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}usergroups WHERE gtid='$gtid'")) continue;//包含相关会员组时不能删除
					$db->query("DELETE FROM {$tblprefix}grouptypes WHERE gtid='$gtid'","SILENT");
					$deletefield = 'grouptype'.$gtid;
					$deletefielddate = 'grouptype'.$gtid.'date';
					$db->query("ALTER TABLE {$tblprefix}members DROP $deletefield,DROP $deletefielddate","SILENT"); 
					@unlink(M_ROOT."./dynamic/cache/usergroups$gtid.cac.php");
					unset($grouptypesnew[$gtid]);
				}
			}
		}
		
		if(!empty($grouptypesnew)){
			foreach($grouptypesnew as $gtid => $grouptype){
				if(empty($grouptypes[$gtid]['issystem'])){
					$grouptype['cname'] = empty($grouptype['cname']) ? $grouptypes[$gtid]['cname'] : $grouptype['cname'];
					if($grouptype['cname'] != $grouptypes[$gtid]['cname']){
						$db->query("UPDATE {$tblprefix}grouptypes SET 
									cname='$grouptype[cname]'
									WHERE gtid='$gtid'");
					}
				}
			}
		}
		adminlog(lang('edit_grouptype_mlist'));
		updatecache('grouptypes');
		amessage('grouedifin',"?entry=grouptypes&action=grouptypesedit");
	}
}elseif($action == 'grouptypedetail' && $gtid){
	$grouptype = $grouptypes[$gtid];
	if(!submitcheck('bgrouptypedetail')){
		tabheader(lang('edit_grouptype'),'grouptypedetail',"?entry=grouptypes&action=grouptypedetail&gtid=$gtid");
		$modearr = array('0' => lang('user_handwork'),'1' => lang('admin_handwork'),'2' => lang('crbase'),'3' => lang('crex'),);
		$cridsarr = array(0 => $grouptype['mode'] == 3 ? lang('cash') : lang('noset')) + cridsarr();
		trbasic(lang('grouptype_name'),'grouptypenew[cname]',$grouptype['cname']);
		if($grouptype['issystem']){
			trbasic(lang('deal_mode'),'',$modearr[$grouptype['mode']],'');
			trbasic(lang('related_cutype'),'',$cridsarr[$grouptype['crid']],'');
		}else{
			trbasic(lang('deal_mode'),'grouptypenew[mode]',makeoption($modearr,$grouptype['mode']),'select');
			trbasic(lang('related_cutype'),'grouptypenew[crid]',makeoption($cridsarr,$grouptype['crid']),'select');
			trbasic(lang('usergroup_alter_reset'),'grouptypenew[allowance]',$grouptype['allowance'],'radio');
		}
		trbasic(lang('inchids_forbid_use'),'',makecheckbox('grouptypenew[mchids][]',mchidsarr(),!empty($grouptype['mchids']) ? explode(',',$grouptype['mchids']) : array(),5),'');
		tabfooter('bgrouptypedetail',lang('modify'));
		a_guide('grouptypedetail');
	}else{
		$grouptypenew['mode'] = empty($grouptypenew['mode']) ? 0 : $grouptypenew['mode'];
		$grouptypenew['crid'] = empty($grouptypenew['crid']) ? 0 : $grouptypenew['crid'];
		if(empty($grouptypenew['cname']) || (($grouptypenew['mode'] == 2) && empty($grouptypenew['crid']))){
			amessage('groupdatamis',M_REFERER);
		}
		$grouptypenew['crid'] = $grouptypenew['mode'] < 2 ? 0 : $grouptypenew['crid'];
		$grouptypenew['mchids'] = !empty($grouptypenew['mchids']) ? implode(',',$grouptypenew['mchids']) : '';
		$grouptypenew['allowance'] = empty($grouptypenew['allowance']) ? 0 : $grouptypenew['allowance'];
		$sqlstr = $grouptype['issystem'] ? '' : "mode='$grouptypenew[mode]',crid='$grouptypenew[crid]',allowance='$grouptypenew[allowance]',";
		$db->query("UPDATE {$tblprefix}grouptypes SET 
					cname='$grouptypenew[cname]',
					$sqlstr
					mchids='$grouptypenew[mchids]'
					WHERE gtid='$gtid'");
		adminlog(lang('detail_modify_grouptype'));
		updatecache('grouptypes',$gtid);
		amessage('grouedifin',"?entry=grouptypes&action=grouptypedetail&gtid=$gtid");
	}
}
?>
