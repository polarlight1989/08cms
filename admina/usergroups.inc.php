<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('grouptype') || amessage('no_apermission');
load_cache('grouptypes,currencys,mchannels');
if(empty($gtid) || empty($grouptypes[$gtid])) amessage('choosegroup');
$grouptype = $grouptypes[$gtid];
$usergroups = read_cache('usergroups',$gtid);
$gtcname = $grouptypes[$gtid]['cname'];
if($action == 'usergroupsedit'){
	if(!submitcheck('busergroupsadd') && !submitcheck('busergroupsedit')){
		$items = '';
		foreach($usergroups as $k => $usergroup){
			$items .= "<tr  class=\"txtcenter txt\"><td class=\"txtC\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"></td>\n".
					"<td class=\"txtC\"><input type=\"text\" size=\"12\" name=\"usergroupsnew[$k][cname]\" value=\"$usergroup[cname]\"></td>\n".
					"<td class=\"txtC\">$gtcname</td>\n".
					"<td class=\"txtC\"><input type=\"text\" size=\"3\" maxlength=\"3\" name=\"usergroupsnew[$k][prior]\" value=\"$usergroup[prior]\"></td>\n".
					"<td class=\"txtC\">".($grouptype['mode'] < 2 ? '-' : "<input type=\"text\" size=\"12\" name=\"usergroupsnew[$k][currency]\" value=\"$usergroup[currency]\">")."</td>\n".
					"<td class=\"txtC\"><a href=\"?entry=usergroups&action=usergroupcopy&gtid=$gtid&ugid=$k\" onclick=\"return floatwin('open_usergroupsedit',this)\">".lang('copy')."</a></td>\n".
					"<td class=\"txtC\"><a href=\"?entry=usergroups&action=usergroupdetail&gtid=$gtid&ugid=$k\" onclick=\"return floatwin('open_usergroupsedit',this)\">".lang('setting')."</a></td></tr>\n";
		}
		tabheader(lang('editusergroup').'-'.$grouptype['cname'],'usergroupsedit','?entry=usergroups&action=usergroupsedit&gtid='.$gtid,'7');
		$cr_title = lang('related_currency');
		if($grouptype['mode'] == 2){
			$cr_title = lang('base_currency').'('.$currencys[$grouptype['crid']]['cname'].')';
		}elseif($grouptype['mode'] == 3){
			$cr_title = lang('exchange_currency').'('.(empty($grouptype['crid']) ? lang('cash') : $currencys[$grouptype['crid']]['cname']).')';
		}
		trcategory(array('<input class="checkbox" type="checkbox" name="chkall" onclick="checkall(this.form)">'.lang('del'),lang('usergroup'),lang('belonggroupty'),lang('order'),$cr_title,lang('copy'),lang('edit')));
		echo $items;
		tabfooter('busergroupsedit',lang('modify'));

		tabheader(lang('addusergroup').'-'.$grouptype['cname'],'usergroupsadd','?entry=usergroups&action=usergroupsedit&gtid='.$gtid);
		trbasic(lang('usergroupcname'),'usergroupadd[cname]');
		($grouptype['mode'] > 1) && trbasic($cr_title,'usergroupadd[currency]');
		tabfooter('busergroupsadd',lang('add'));
		a_guide('usergroupsedit');
	}elseif(submitcheck('busergroupsadd')){
		if(!$usergroupadd['cname']){
			amessage('userdatamiss','?entry=usergroups&action=usergroupsedit&gtid='.$gtid);
		}
		$usergroupadd['currency'] = $grouptype['mode'] < 2 ? 0 : max(0,intval($usergroupadd['currency']));
		$db->query("INSERT INTO {$tblprefix}usergroups SET 
					cname='$usergroupadd[cname]', 
					currency='$usergroupadd[currency]', 
					gtid='$gtid'");

		adminlog(lang('addusergroup'));
		updatecache('usergroups',$gtid);
		amessage('usergroupaddfin', '?entry=usergroups&action=usergroupsedit&gtid='.$gtid);
	}elseif(submitcheck('busergroupsedit')){
		if(!empty($delete)){
			foreach($delete as $ugid) {
				$db->query("DELETE FROM {$tblprefix}mcnodes WHERE mcnvar='ugid$gtid' AND mcnid='$ugid'");
				$db->query("DELETE FROM {$tblprefix}usergroups WHERE ugid='$ugid'");
				$db->query("UPDATE {$tblprefix}members SET grouptype$gtid=0 WHERE grouptype$gtid='$ugid'",'SILENT');
				unset($usergroupsnew[$ugid]);
			}
			updatecache('mcnodes');
		}

		if(!empty($usergroupsnew)){
			foreach($usergroupsnew as $ugid => $usergroup){
				$usergroup['currency'] = $grouptype['mode'] < 2 ? 0 : max(0,intval($usergroup['currency']));
				$usergroup['prior'] = max(0,intval($usergroup['prior']));
				$usergroup['cname'] = empty($usergroup['cname']) ? $usergroups[$ugid]['cname'] : $usergroup['cname'];
				if(($usergroup['cname'] != $usergroups[$ugid]['cname']) || ($usergroup['prior'] != $usergroups[$ugid]['prior'] || ($usergroup['currency'] != $usergroups[$ugid]['currency']))){
					$db->query("UPDATE {$tblprefix}usergroups SET
								cname='$usergroup[cname]',
								currency='$usergroup[currency]',
								prior='$usergroup[prior]'
								WHERE ugid='$ugid'");
				}
			}
		}
		adminlog(lang('editusergroup'));
		updatecache('usergroups',$gtid);
		amessage('usergroupmodfin', '?entry=usergroups&action=usergroupsedit&gtid='.$gtid);
	}
}elseif($action == 'usergroupcopy' && $gtid && $ugid){
	$usergroup = $db->fetch_one("SELECT * FROM {$tblprefix}usergroups WHERE gtid='$gtid' AND ugid='$ugid'");
	if(!submitcheck('busergroupcopy')){
		tabheader(lang('usergroupcopy').'-'.$grouptype['cname'],'usergroupcopy',"?entry=usergroups&action=usergroupcopy&gtid=$gtid&ugid=$ugid",2,0,1);
		trbasic(lang('sousergname'),'',$usergroup['cname'],'');
		trbasic(lang('newusergroupname'),'usergroupnew[cname]');
		tabfooter('busergroupcopy');
		$submitstr = '';
		$submitstr .= makesubmitstr('usergroupnew[cname]',1,0,0,30);
		check_submit_func($submitstr);
		a_guide('usergroupcopy');
	}else{
		$usergroupnew['cname'] = trim(strip_tags($usergroupnew['cname']));
		if(empty($usergroupnew['cname'])) amessage('datamissing',M_REFERER);
		$sqlstr = "cname='$usergroupnew[cname]'";
		foreach($usergroup as $k => $v) if(!in_array($k,array('ugid','cname'))) $sqlstr .= ",$k='".addslashes($v)."'";
		$db->query("INSERT INTO {$tblprefix}usergroups SET $sqlstr");
		$ugid = $db->insert_id();
		adminlog(lang('copyusergroup'));
		updatecache('usergroups',$gtid);
		amessage('usercopyfin',"?entry=usergroups&action=usergroupdetail&gtid=$gtid&ugid=$ugid");
	}
}elseif(($action == 'usergroupdetail') && $gtid && $ugid){
	$forward = empty($forward) ? M_REFERER : $forward;
	$usergroup = read_cache('usergroup',$gtid,$ugid);
	if(!submitcheck('busergroupdetail')){
		tabheader(lang('editusergroup').'-'.$grouptype['cname'],'usergroupdetail',"?entry=usergroups&action=usergroupdetail&gtid=$gtid&ugid=$ugid&forward=".rawurlencode($forward),2,0,0,1);
		trbasic(lang('usergroupcname'),'usergroupnew[cname]',$usergroup['cname']);
		trbasic(lang('inchallowuse'),'',makecheckbox('usergroupnew[mchids][]',mchidsarr(),!empty($usergroup['mchids']) ? explode(',',$usergroup['mchids']) : array(),5),'');
		trbasic(lang('uservalid').'('.lang('day').')','usergroupnew[limitday]',$usergroup['limitday']);
		if(!$grouptype['issystem'] && $grouptype['mode'] != 2) trbasic(lang('autoinit'),'usergroupnew[autoinit]',$usergroup['autoinit'],'radio',lang('agautoinit'));
		if($grouptype['forbidden']){
			trbasic(lang('alloissuearch'),'usergroupnew[issuepermit]',$usergroup['issuepermit'],'radio');
			trbasic(lang('allissuecomm'),'usergroupnew[commentpermit]',$usergroup['commentpermit'],'radio');
			trbasic(lang('allpurcgoods'),'usergroupnew[purchasepermit]',$usergroup['purchasepermit'],'radio');
			trbasic(lang('alloissans'),'usergroupnew[answerpermit]',$usergroup['answerpermit'],'radio');
			trbasic(lang('allouploattach'),'usergroupnew[uploadpermit]',$usergroup['uploadpermit'],'radio');
			trbasic(lang('allodownattach'),'usergroupnew[downloadpermit]',$usergroup['downloadpermit'],'radio');
		}elseif($grouptype['afunction']){
			$amconfigs = reload_cache('amconfigs');
			$sidsarr = array('m' => lang('msite'));
			foreach($subsites as $k => $v) $sidsarr[$k] = $v['sitename'];
			foreach($sidsarr as $k => $v){
				$amcidarr = array('0' => lang('noset'));
				foreach($amconfigs as $k1 => $v1) if($v1['sid'] == intval($k)) $amcidarr[$k1] = $v1['cname'];
				trbasic($v.lang('admibackaproje'),"usergroupnew[amcids][$k]",makeoption($amcidarr,empty($usergroup['amcids'][$k]) ? 0 : $usergroup['amcids'][$k]),'select');
			}
			trbasic(lang('allouploattach'),'usergroupnew[uploadpermit]',$usergroup['uploadpermit'],'radio');
			trbasic(lang('allinsisear'),'usergroupnew[searchpermit]',$usergroup['searchpermit'],'radio');
			trbasic(lang('freeupdatecheck'),'usergroupnew[freeupdatecheck]',$usergroup['freeupdatecheck'],'radio');
			trbasic(lang('freelooktaxcon'),'usergroupnew[denyarc]',$usergroup['denyarc'],'radio');
			trbasic(lang('freelooktaxattach'),'usergroupnew[denyatm]',$usergroup['denyatm'],'radio');
			trbasic(lang('pmmountlimi'),'usergroupnew[maxpms]',$usergroup['maxpms']);
			trbasic(lang('uploadlimited').'(M)','usergroupnew[maxuptotal]',$usergroup['maxuptotal']);
			trbasic(lang('downloadlimited').'(M)','usergroupnew[maxdowntotal]',$usergroup['maxdowntotal']);
			trbasic(lang('purgoodiscount'),'usergroupnew[discount]',$usergroup['discount'],'text');
		}else{
			($grouptype['mode'] > 1) && trbasic(lang('relatcurramou'),'usergroupnew[currency]',$usergroup['currency']);
			trbasic(lang('allouploattach'),'usergroupnew[uploadpermit]',$usergroup['uploadpermit'],'radio');
			trbasic(lang('allinsisear'),'usergroupnew[searchpermit]',$usergroup['searchpermit'],'radio');
			trbasic(lang('freeupdatecheck'),'usergroupnew[freeupdatecheck]',$usergroup['freeupdatecheck'],'radio');
			trbasic(lang('freelooktaxcon'),'usergroupnew[denyarc]',$usergroup['denyarc'],'radio');
			trbasic(lang('freelooktaxattach'),'usergroupnew[denyatm]',$usergroup['denyatm'],'radio');
			trbasic(lang('pmmountlimi'),'usergroupnew[maxpms]',$usergroup['maxpms']);
			trbasic(lang('uploadlimited').'(M)','usergroupnew[maxuptotal]',$usergroup['maxuptotal']);
			trbasic(lang('downloadlimited').'(M)','usergroupnew[maxdowntotal]',$usergroup['maxdowntotal']);
			trbasic(lang('purgoodiscount'),'usergroupnew[discount]',$usergroup['discount'],'text');
			trbasic(lang('allarcdefamo'),'usergroupnew[arcallows]',$usergroup['arcallows']);
			trbasic(lang('allcomdefamomon'),'usergroupnew[cuallows]',$usergroup['cuallows']);
			trbasic(lang('extract_discount'),'usergroupnew[ex_discount]',$usergroup['ex_discount']);
		}
		tabfooter('busergroupdetail',lang('modify'));
		a_guide('usergroupdetail');
	}else{
		$sqlstr = '';
		if($grouptype['forbidden']){
			$sqlstr .= "issuepermit='$usergroupnew[issuepermit]',".
						"commentpermit='$usergroupnew[commentpermit]',".
						"purchasepermit='$usergroupnew[purchasepermit]',".
						"answerpermit='$usergroupnew[answerpermit]',".
						"uploadpermit='$usergroupnew[uploadpermit]',".
						"downloadpermit='$usergroupnew[downloadpermit]',";
			
		}elseif($grouptype['afunction']){
			$usergroupnew['discount'] = round(min(100,max(0,floatval($usergroupnew['discount']))),2);
			$usergroupnew['maxuptotal'] = !max(0,intval($usergroupnew['maxuptotal'])) ? 0 : max(0,intval($usergroupnew['maxuptotal']));
			$usergroupnew['maxdowntotal'] = !max(0,intval($usergroupnew['maxdowntotal'])) ? 0 : max(0,intval($usergroupnew['maxdowntotal']));
			$usergroupnew['amcids'] = empty($usergroupnew['amcids']) ? '' : addslashes(serialize($usergroupnew['amcids']));
			$sqlstr .= "denyarc='$usergroupnew[denyarc]',".
						"uploadpermit='$usergroupnew[uploadpermit]',".
						"searchpermit='$usergroupnew[searchpermit]',".
						"freeupdatecheck='$usergroupnew[freeupdatecheck]',".
						"denyatm='$usergroupnew[denyatm]',".
						"maxpms='$usergroupnew[maxpms]',".
						"discount='$usergroupnew[discount]',".
						"maxuptotal='$usergroupnew[maxuptotal]',".
						"maxdowntotal='$usergroupnew[maxdowntotal]',".
						"amcids='$usergroupnew[amcids]',";
		}else{
			$usergroupnew['currency'] = ($grouptype['mode'] < 1) || empty($usergroupnew['currency']) ? 0 : max(0,intval($usergroupnew['currency']));
			$usergroupnew['discount'] = round(min(100,max(0,floatval($usergroupnew['discount']))),2);
			$usergroupnew['maxuptotal'] = !max(0,intval($usergroupnew['maxuptotal'])) ? 0 : max(0,intval($usergroupnew['maxuptotal']));
			$usergroupnew['maxdowntotal'] = !max(0,intval($usergroupnew['maxdowntotal'])) ? 0 : max(0,intval($usergroupnew['maxdowntotal']));
			$usergroupnew['arcallows'] = empty($usergroupnew['arcallows']) ? 0 : max(0,intval($usergroupnew['arcallows']));
			$usergroupnew['cuallows'] = empty($usergroupnew['cuallows']) ? 0 : max(0,intval($usergroupnew['cuallows']));
			$usergroupnew['ex_discount'] = empty($usergroupnew['ex_discount']) ? 0 : round(max(0,min(100,floatval($usergroupnew['ex_discount']))), 2);
			$sqlstr .=  "currency='$usergroupnew[currency]',".
						"uploadpermit='$usergroupnew[uploadpermit]',".
						"searchpermit='$usergroupnew[searchpermit]',".
						"freeupdatecheck='$usergroupnew[freeupdatecheck]',".
						"denyarc='$usergroupnew[denyarc]',".
						"denyatm='$usergroupnew[denyatm]',".
						"maxpms='$usergroupnew[maxpms]',".
						"discount='$usergroupnew[discount]',".
						"maxuptotal='$usergroupnew[maxuptotal]',".
						"maxdowntotal='$usergroupnew[maxdowntotal]',".
						"arcallows='$usergroupnew[arcallows]',".
						"ex_discount='$usergroupnew[ex_discount]',".
						"cuallows='$usergroupnew[cuallows]',";
			
		}
		$usergroupnew['cname'] = !$usergroupnew['cname'] ? $usergroup['cname'] : $usergroupnew['cname'];
		$usergroupnew['mchids'] = !empty($usergroupnew['mchids']) ? implode(',',$usergroupnew['mchids']) : '';
		$usergroupnew['limitday'] = !intval($usergroupnew['limitday']) ? 0 : intval($usergroupnew['limitday']);
		$usergroupnew['autoinit'] = $grouptype['issystem'] || $grouptype['mode'] == 2 || empty($usergroupnew['autoinit']) ? 0 : 1;
		$sqlstr .= "cname='$usergroupnew[cname]',
				mchids='$usergroupnew[mchids]',
				autoinit='$usergroupnew[autoinit]',
				limitday='$usergroupnew[limitday]'
				";
		$db->query("UPDATE {$tblprefix}usergroups SET $sqlstr WHERE ugid='$ugid'");
		adminlog(lang('detmoduserg'));
		updatecache('usergroups',$gtid);
		amessage('usereditfin',axaction(10,$forward));
	}
}
?>
