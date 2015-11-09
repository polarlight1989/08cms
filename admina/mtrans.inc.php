<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('mtrans') || amessage('no_apermission');
load_cache('mchannels,mprojects,mtconfigs,grouptypes,currencys,rprojects,cotypes,acatalogs');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
if($action == 'mtransedit'){
	if($sid && $sid_self) amessage('msiteadmitem');
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$checked = isset($checked) ? $checked : '-1';
	$toid = empty($toid) ? 0 : max(0,intval($toid));
	$fromid = empty($fromid) ? 0 : max(0,intval($fromid));
	$keyword = empty($keyword) ? '' : $keyword;

	$wheresql = '';
	$checked != '-1' && $wheresql .= ($wheresql ? " AND " : "")."checked='$checked'";
	$toid && $wheresql .= ($wheresql ? " AND " : "")."toid='$toid'";
	$fromid && $wheresql .= ($wheresql ? " AND " : "")."fromid='$fromid'";
	$keyword && $wheresql .= ($wheresql ? " AND " : "")."mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";

	$filterstr = '';
	foreach(array('checked','toid','fromid','keyword') as $k){
		$filterstr .= "&$k=".urlencode($$k);
	}
	$wheresql = $wheresql ? "WHERE ".$wheresql : "";

	if(!submitcheck('bmtransedit')){
		echo form_str($actionid.'utransedit',"?entry=mtrans&action=mtransedit&page=$page");
		tabheader_e();
		echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		$checkedarr = array('-1' => lang('altchesta'),'0' => lang('nocheckalter'),'1' => lang('checkedalter'));
		echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"fromid\">".makeoption(array(0 => lang('sourcechannel')) + mchidsarr(),$fromid)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"toid\">".makeoption(array(0 => lang('targetchannel')) + mchidsarr(),$toid)."</select>&nbsp; ";
		echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">";
		echo "</td></tr>";
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}mtrans $wheresql ORDER BY trid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($row = $db->fetch_array($query)){
			$createdatestr = date("$dateformat", $row['createdate']);
			$checkstr = $row['checked'] ? 'Y' : "<input class=\"checkbox\" type=\"checkbox\" name=\"checkid[$row[trid]]\" value=\"$row[trid]\">";
			$detailstr = $row['checked'] ? '-' : "<a href=\"?entry=mtrans&action=mtrandetail&trid=$row[trid]\" onclick=\"return floatwin('open_transdetail',this)\">".lang('detail')."</a>";
			$itemstr .= "<tr class=\"txt\">\n".
			"<td class=\"txtC w50\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$row[trid]]\" value=\"$row[trid]\"></td>\n".
			"<td class=\"txtL\">$row[mname]</td>\n".
			"<td class=\"txtC\">".$mchannels[$row['fromid']]['cname']."</td>\n".
			"<td class=\"txtC\">".$mchannels[$row['toid']]['cname']."</td>\n".
			"<td class=\"txtC w50\">$checkstr</td>\n".
			"<td class=\"txtC w70\">$createdatestr</td>\n".
			"<td class=\"txtC w30\">$detailstr</td>\n".
			"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}mtrans $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=mtrans&action=mtransedit$filterstr");

		tabheader(lang('memchanaltli'),'','',8);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkdel\" onclick=\"checkall(this.form,'delete','chkdel')\">".lang('del'),
		lang('member_cname'),lang('sourcechannel'),lang('targetchannel'),"<input class=\"checkbox\" type=\"checkbox\" name=\"chkcheck\" onclick=\"checkall(this.form,'checkid','chkcheck')\">".lang('check'),lang('add_date'),lang('detail')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"bmtransedit\" value=\"".lang('submit')."\">";
	}else{
		if(empty($delete) && empty($checkid)) amessage('selectaltrec',"?entry=mtrans&action=mtransedit&page=$page$filterstr");
		if(!empty($delete)) $db->query("DELETE FROM {$tblprefix}mtrans WHERE trid ".multi_str($delete));
		if(!empty($checkid)){
			$actuser = new cls_userinfo;
			foreach($checkid as $trid){//?????????????????????????????????????????
				if(empty($delete) || !in_array($trid,$delete)){
					if($minfos = $db->fetch_one("SELECT * FROM {$tblprefix}mtrans WHERE trid='$trid' AND checked='0'")){
						$minfos = array_merge($minfos,$minfos['contentarr'] ? unserialize($minfos['contentarr']) : array());
						unset($minfos['contentarr']);
						$actuser->activeuser($minfos['mid'],2);
						$omchid = $minfos['fromid'];
						$mchid = $minfos['toid'];
						$mchannel = $mchannels[$mchid];
						foreach(array('additems') as $var) $$var = $mchannel[$var] ? explode(',',$mchannel[$var]) : array();
						$mfields = read_cache('mfields',$mchid);

						if(in_array('mtcid',$additems)){
							$actuser->updatefield('mtcid',@$minfos['mtcid'],'main');
						}
						foreach($grouptypes as $k => $v){
							if(!$v['mode'] && in_array("grouptype$k",$additems)){
								$actuser->updatefield("grouptype$k",$minfos["grouptype$k"],'main');
							}
						}
						foreach($mfields as $k => $v){
							if($v['available'] && !$v['issystem'] && !$v['isfunc'] && !$v['isadmin'] && in_array($k,$additems)){
								$actuser->updatefield($k,@$minfos[$k],$v['tbl']);
								if($arr = multi_val_arr(@$minfos[$k],$v)) foreach($arr as $x => $y) $actuser->updatefield($k.'_'.$x,$y,$v['tbl']);
							}
						}
						$actuser->updatefield('mchid',$mchid,'main');
						//在更新会员资料之前一定要将原模型表中的记录删除，插入新模型表中的记录。
						$db->query("DELETE FROM {$tblprefix}members_$omchid WHERE mid='$minfos[mid]'");
						$db->query("INSERT INTO {$tblprefix}members_$mchid SET mid='$minfos[mid]'");
						$actuser->gtidbymchid();//检查因模型改变是否有不生效会员组
						$actuser->autoinit();
						$actuser->updatedb();
						$db->query("UPDATE {$tblprefix}mtrans SET contentarr='',remark='',reply='',checked='1' WHERE trid='$trid'");
						$actuser->init();
					}
				}
			}
			unset($actuser);
		}
		adminlog(lang('memchaaltadm'),lang('memchaalliadope'));
		amessage('memchaaltopefin',"?entry=mtrans&action=mtransedit&page=$page$filterstr");
	
	}
}elseif($action == 'mtrandetail' && $trid){
	if(!($minfos = $db->fetch_one("SELECT * FROM {$tblprefix}mtrans WHERE trid='$trid'"))) amessage('choosealtrec');
	$minfos = array_merge($minfos,$minfos['contentarr'] ? unserialize($minfos['contentarr']) : array());
	unset($minfos['contentarr']);
	$omchid = $minfos['fromid'];
	$mchid = $minfos['toid'];
	$mchannel = $mchannels[$mchid];
	$mfields = read_cache('mfields',$mchid);
	foreach(array('additems') as $var) $$var = $mchannel[$var] ? explode(',',$mchannel[$var]) : array();
	if(!submitcheck('bmtrandetail')){
		$a_field = new cls_field;
		$submitstr = '';
		$muststr = '<span style="color:red">*</span>';

		tabheader(lang('memtypneeopt').'&nbsp; -&nbsp; '.$minfos['mname'],'mtrans',"?entry=mtrans&action=mtrandetail&trid=$trid",2,1,1);
		trbasic(lang('memchaaltmod'),'',@$mchannels[$omchid]['cname'].'&nbsp; ->&nbsp; '.@$mchannels[$mchid]['cname'],'');
		trbasic(lang('altneetim'),'',date("Y-m-d H:i",$minfos['createdate']),'');
		trbasic(lang('alterremark'),'mtran[remark]',$minfos['remark'],'textarea');
		trbasic(lang('adminreply'),'mtran[reply]',$minfos['reply'],'textarea');
		tabfooter();

		tabheader(lang('memaltmes'));

		if(in_array('mtcid',$additems)){
			trbasic(lang('space_tpl_prj'),'minfosnew[mtcid]',makeoption(mtcidsarr($mchid),$minfos['mtcid']),'select');
		}
		foreach($grouptypes as $k => $v){
			if(!$v['mode'] && !in_array($mchid,explode(',',$v['mchids'])) && in_array("grouptype$k",$additems)){
				trbasic($v['cname'],"minfosnew[grouptype$k]",makeoption(ugidsarr($k,$mchid),$minfos["grouptype$k"]),'select');
			}
		}
		foreach($mfields as $k => $field){
			if($field['available'] && !$field['issystem'] && !$field['isfunc'] && !$field['isadmin'] && in_array($k,$additems)){
				$a_field->init();
				$a_field->field = $field;
				$a_field->oldvalue = !isset($minfos[$k]) ? '' : $minfos[$k];
				$a_field->trfield('minfosnew','','m',$mchid);
				$submitstr .= $a_field->submitstr;
			}
		}
		unset($a_field);
		tabfooter('bmtrandetail');
		check_submit_func($submitstr);
		a_guide('mtrandetail');
	}else{
		if(in_array('mtcid',$additems)){
			$minfosnew['mtcid'] = empty($minfosnew['mtcid']) ? 1 : $minfosnew['mtcid'];
		}
		foreach($grouptypes as $k => $v){
			if(!$v['mode'] && in_array("grouptype$k",$additems)){
				$minfosnew["grouptype$k"] = empty($minfosnew["grouptype$k"]) ? 0 : $minfosnew["grouptype$k"];
			}
		}

		$c_upload = new cls_upload;	
		$mfields = fields_order($mfields);
		$a_field = new cls_field;
		foreach($mfields as $k => $v){
			if($v['available'] && !$v['issystem'] && !$v['isfunc'] && !$v['isadmin'] && in_array($k,$additems)){
				$a_field->init();
				$a_field->field = $v;
				$a_field->oldvalue = !isset($minfos[$k]) ? '' : $minfos[$k];
				$a_field->deal('minfosnew');
				if(!empty($a_field->error)){
					$c_upload->rollback();
					amessage($a_field->error,axaction(2,M_REFERER));
				}
				$minfosnew[$k] = $a_field->newvalue;//收集资料。
				if($arr = multi_val_arr($a_field->newvalue,$v)) foreach($arr as $x => $y) $minfosnew[$k.'_'.$x] = $y;
			}
		}
		unset($a_field);
		$mtran['remark'] = trim($mtran['remark']);
		$mtran['reply'] = trim($mtran['reply']);
		$mtran['contentarr'] = empty($minfosnew) ? '' : addslashes(serialize($minfosnew));
		$db->query("UPDATE {$tblprefix}mtrans SET contentarr='$mtran[contentarr]',remark='$mtran[remark]',reply='$mtran[reply]' WHERE trid='$trid'");
		$c_upload->closure(1, $minfos['mid'], 'members');
		$c_upload->saveuptotal(1);
		adminlog(lang('modmemchanalt'),lang('memaltdetmodope'));
		amessage('memchaaltrecmodfin',axaction(6,M_REFERER));
	}
}
?>
