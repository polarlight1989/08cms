<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('mchannel') || amessage('no_apermission');
load_cache('mchannels,initmfields,rprojects,cotypes,mtpls,mcommus');
sys_cache('fieldwords');
include_once M_ROOT."./include/fields.fun.php";
$url_type = 'mchannel';include 'urlsarr.inc.php';
if($action == 'mchannelsedit'){
	url_nav(lang('mchannel'),$urlsarr,'channel');
	if(!submitcheck('bmchannelsedit')){
		tabheader(lang('member_channel_manager')."&nbsp; &nbsp; >><a href=\"?entry=mchannels&action=mchanneladd\">".lang('add')."</a>",'mchanneledit','?entry=mchannels&action=mchannelsedit','10');
		trcategory(array(lang('id'),lang('available'),lang('channel_name'),lang('admin'),lang('delete'),lang('copy'),lang('field'),lang('edit')));
		foreach($mchannels as $k => $mchannel){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w30\">$k</td>\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"mchannelnew[$k][available]\" value=\"1\"".($mchannel['available'] ? " checked" : "").($mchannel['issystem'] ? ' disabled' : '')."></td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"mchannelnew[$k][cname]\" value=\"$mchannel[cname]\"></td>\n".
				"<td class=\"txtC w30\">".(empty($mchannel['userforbidadd']) ? '-' : 'Y')."</td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=mchannels&action=mchanneldel&mchid=$k\">".lang('delete')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=mchannels&action=mchannelcopy&mchid=$k\" onclick=\"return floatwin('open_mchanneledit',this)\">".lang('copy')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=mchannels&action=mchannelfields&mchid=$k\" onclick=\"return floatwin('open_mchanneledit',this)\">".lang('field')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=mchannels&action=mchanneldetail&mchid=$k\" onclick=\"return floatwin('open_mchanneledit',this)\">".lang('detail')."</a></td>\n".
				"</tr>\n";
		}
		tabfooter('bmchannelsedit',lang('modify'));
		a_guide('mchannelsedit');
	}else{
		if(isset($mchannelnew)){
			foreach($mchannelnew as $k => $v) {
				$v['available'] = isset($v['available']) ? $v['available'] : 0;
				$v['cname'] = trim(strip_tags($v['cname']));
				$v['cname'] = $v['cname'] ? $v['cname'] : $mchannels[$k]['cname'];
				if(($v['cname'] != $mchannels[$k]['cname']) || ($v['available'] != $mchannels[$k]['available'])) {
					$db->query("UPDATE {$tblprefix}mchannels SET cname='$v[cname]', available='$v[available]' WHERE mchid='$k'");
				}
			}
			adminlog(lang('edit_member_channel_list'));
			updatecache('mchannels');
			amessage('mchanneleditfinish',"?entry=mchannels&action=mchannelsedit");
		}
	}
}elseif($action == 'mchanneladd'){
	url_nav(lang('mchannel'),$urlsarr,'channel');
	if(!submitcheck('bmchanneladd')){
		tabheader(lang('add_member_channel'),'mchanneladd','?entry=mchannels&action=mchanneladd',2,0,1);
		trbasic(lang('member_channel_name'),'mchanneladd[cname]');
		tabfooter('bmchanneladd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('mchanneladd[cname]',1,0,3,30);
		check_submit_func($submitstr);
	}else{
		$mchanneladd['cname'] = trim(strip_tags($mchanneladd['cname']));
		empty($mchanneladd['cname']) && amessage('datamissing', '?entry=mchannels&action=mchanneledit');
		$db->query("INSERT INTO {$tblprefix}mchannels SET cname='$mchanneladd[cname]'");
		if($mchid = $db->insert_id()){
			$customtable = 'members_'.$mchid;
			$db->query("CREATE TABLE {$tblprefix}$customtable (
						mid mediumint(8) unsigned NOT NULL default '0',
						PRIMARY KEY (mid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
			foreach($initmfields as $field){
				$sqlstr = "mchid='$mchid',available='1'";
				foreach($field as $k => $v) if(!in_array($k,array('mfid','mchid','available'))) $sqlstr .= ",$k='".addslashes($v)."'";
				$db->query("INSERT INTO {$tblprefix}mfields SET $sqlstr");
			}
			updatecache('mchannels');
			updatecache('mfields',$mchid);
		}
		adminlog(lang('add_member_channel'));
		amessage('mchanneladdfinish',"?entry=mchannels&action=mchannelsedit");
	}

}elseif($action == 'mchannelcopy' && $mchid){
	$forward = empty($forward) ? M_REFERER : $forward;
	$mchannel = $mchannels[$mchid];
	if(!submitcheck('bmchannelcopy')){
		tabheader(lang('member_channel_copy'),'mchannelcopy',"?entry=mchannels&action=mchannelcopy&mchid=$mchid&forward=".rawurlencode($forward),2,0,1);
		trbasic(lang('soc_channel_name'),'',$mchannel['cname'],'');
		trbasic(lang('new_channel_name'),'mchannelnew[cname]');
		tabfooter('bmchannelcopy');
		$submitstr = '';
		$submitstr .= makesubmitstr('mchannelnew[cname]',1,0,0,30);
		check_submit_func($submitstr);
		a_guide('mchannelcopy');
	}else{
		$mchannelnew['cname'] = trim(strip_tags($mchannelnew['cname']));
		if(empty($mchannelnew['cname'])) amessage('datamissing',M_REFERER);
		$sqlstr = "cname='$mchannelnew[cname]'";
		foreach($mchannel as $k => $v) if(!in_array($k,array('mchid','cname'))) $sqlstr .= ",$k='".addslashes($v)."'";
		$db->query("INSERT INTO {$tblprefix}mchannels SET $sqlstr");
		if($nchid = $db->insert_id()){
			$customtable = 'members_'.$nchid;
			$db->query("CREATE TABLE {$tblprefix}$customtable (
						mid mediumint(8) unsigned NOT NULL default '0',
						PRIMARY KEY (mid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
			$fields = read_cache('mfields',$mchid);
			foreach($fields as $k => $v){
				$fieldnew = read_cache('mfield',$mchid,$k);
				if($fieldnew['tbl'] == 'custom'){
					$fieldnew = maddslashes($fieldnew);
					$fconfigarr = array(
						'errorurl' => M_REFERER,
						'enamearr' => array(),
						'altertable' => $tblprefix.$customtable,
						'fieldtable' => $tblprefix.'mfields',
						'sqlstr' => "mchid=$nchid,issystem='0',iscustom='1',available='$fieldnew[available]',vieworder='$fieldnew[vieworder]',tbl='custom'",
						'filterstr' => "",
					);
					list($fmode,$fnew,$fsave) = array('m',true,true);
					include M_ROOT."./include/fields/$fieldnew[datatype].php";
				}else{
					$sqlstr = "mchid='$nchid'";
					foreach($fieldnew as $k => $v) if(!in_array($k,array('mfid','mchid'))) $sqlstr .= ",$k='".addslashes($v)."'";
					$db->query("INSERT INTO {$tblprefix}mfields SET $sqlstr");
				}
			}
			updatecache('mchannels');
			updatecache('mfields',$nchid);
		}
		adminlog(lang('copy_arc_channel'));
		amessage('mchannelcopyfinish',axaction(6,"?entry=mchannels&action=mchannelsedit"));
	}
}elseif($action == 'mchanneldetail' && $mchid) {
	$mchannel = $mchannels[$mchid];
	$fields = read_cache('mfields',$mchid);
	if(!submitcheck('bmchanneldetail')){
		$autocheckarr = array(0 => lang('handwork_check'),1 => lang('auto_check'),2 => lang('mail_active'));
		tabheader("[$mchannel[cname]]".lang('member_channel_set'),'mchanneldetail','?entry=mchannels&action=mchanneldetail&mchid='.$mchid,'4');
		trbasic(lang('admin_self_channel'),'mchannelnew[userforbidadd]',$mchannel['userforbidadd'],'radio');
		trbasic(lang('reg_member_check_mode'),'',makeradio('mchannelnew[autocheck]',$autocheckarr,$mchannel['autocheck']),'');
		trbasic(lang('member_comment_commu_set'),'mchannelnew[comment]',makeoption(array(0 => lang('noset')) + mcuidsarr('comment'),$mchannel['comment']),'select');
		trbasic(lang('member_reply_commu_set'),'mchannelnew[reply]',makeoption(array(0 => lang('noset')) + mcuidsarr('reply'),$mchannel['reply']),'select');
		$itemsarr['mtcid'] = lang('spacetpl');
		$itemsarr['dirname'] = lang('mdirname');
		foreach($grouptypes as $k => $v) if(!$v['mode']) $itemsarr["grouptype$k"] = $v['cname'];
		foreach($fields as $k => $v) if(!$v['issystem']) $itemsarr[$k] = $v['cname'];
		trbasic(lang('additems_m')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_5\" onclick=\"checkall(this.form,'additemsnew','chkall_5')\">".lang('selectall'),'',makecheckbox('additemsnew[]',$itemsarr,empty($mchannel['additems']) ? array() : explode(',',$mchannel['additems']),5),'',lang('agadditems_m'));
		trbasic(lang('useredits')."<br><input class=\"checkbox\" type=\"checkbox\" name=\"chkall_6\" onclick=\"checkall(this.form,'usereditsnew','chkall_6')\">".lang('selectall'),'',makecheckbox('usereditsnew[]',$itemsarr,empty($mchannel['useredits']) ? array() : explode(',',$mchannel['useredits']),5),'',lang('aguseredits'));
		tabfooter('bmchanneldetail');
		a_guide('mchanneldetail');
	}else{
		$mchannelnew['additems'] = empty($additemsnew) ? '' : implode(',',$additemsnew);
		$mchannelnew['useredits'] = empty($usereditsnew) ? '' : implode(',',$usereditsnew);

		$db->query("UPDATE {$tblprefix}mchannels SET 
			userforbidadd='$mchannelnew[userforbidadd]', 
			autocheck='$mchannelnew[autocheck]',
			comment='$mchannelnew[comment]',
			reply='$mchannelnew[reply]',
			additems='$mchannelnew[additems]',
			useredits='$mchannelnew[useredits]'
			WHERE mchid='$mchid'");
		adminlog(lang('det_modify_mchannel'));
		updatecache('mchannels');
		amessage('channelmodifyfinish', '?entry=mchannels&action=mchanneldetail&mchid='.$mchid);
	}
}elseif($action == 'mchannelfields' && $mchid) {
	$mchannel = $mchannels[$mchid];
	$fields = read_cache('mfields',$mchid);
	if(!submitcheck('bmchanneldetail')){
		tabheader($mchannel['cname'].'-'.lang('field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=mchannels&action=mfieldadd&mchid=$mchid\">".lang('add_field')."</a>",'mchanneldetail','?entry=mchannels&action=mchannelfields&mchid='.$mchid,'8');
		trcategory(array(lang('delete'),lang('available'),lang('field_name'),lang('field_ename'),lang('admin_self'),lang('order'),lang('field_type'),lang('edit')));
		foreach($fields as $k => $field){
			fieldlist($k,$field,'member');
		}
		tabfooter('bmchanneldetail');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				if(!$fields[$id]['mcommon']){
					dropfieldfromtbl("members_$mchid",$id,$fields[$id]['datatype']);
					$db->query("DELETE FROM {$tblprefix}mfields WHERE ename='$id' AND mchid='$mchid'"); 
					unset($fields[$id],$fieldsnew[$id]);
				}
			}
		}
		foreach($fields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['available'] = $field['issystem'] ? $field['available'] : (empty($fieldsnew[$id]['available']) ? 0 : 1);
			$field['isadmin'] = $field['issystem'] ? '0' : (empty($fieldsnew[$id]['isadmin']) ? 0 : 1);
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}mfields SET cname='$field[cname]',available='$field[available]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND mchid='$mchid'");
		}
		adminlog(lang('det_modify_mchannel'));
		updatecache('mchannels');
		updatecache('mfields',$mchid);
		amessage('channelmodifyfinish', '?entry=mchannels&action=mchannelfields&mchid='.$mchid);
	}
}
elseif($action == 'mfieldadd' && $mchid){
	if(!submitcheck('bmfieldadd')){
		tabheader(lang('add')."&nbsp;[".$mchannels[$mchid]['cname']."]&nbsp;".lang('field'),'mfieldadd',"?entry=mchannels&action=mfieldadd&mchid=$mchid",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bmfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('m',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bmfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('mfieldadd');
	}else{
		$enamearr = $usednames['mfields'];
		$fields = read_cache('mfields',$mchid);
		foreach($fields as $ename => $field){
			if(!in_array($ename,$enamearr)) $enamearr[] = $ename;
		}
		$fconfigarr = array(
			'errorurl' => '?entry=mchannels&action=mchanneldetail&mchid='.$mchid,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'members_'.$mchid,
			'fieldtable' => $tblprefix.'mfields',
			'sqlstr' => "mchid=$mchid,iscustom='1',available='1',tbl='custom'",
			'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^grouptype(.*?)|^currency(.*?)/",
		);
		list($fmode,$fnew,$fsave) = array('m',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		adminlog(lang('add_member_msg_field'));
		updatecache('mfields',$mchid);
		amessage('fieldaddfinish','?entry=mchannels&action=mchannelfields&mchid='.$mchid);
	}
}
elseif($action == 'mchanneldel' && $mchid) {
	$mchannel = $mchannels[$mchid];
	if($mchannel['issystem']) amessage('schannelcannotdelete', '?entry=mchannels&action=mchannelsedit');
	if(empty($confirm)){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick')."[<a href=?entry=mchannels&action=mchanneldel&mchid=".$mchid."&confirm=1>".lang('delete')."</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick')."[<a href=?entry=mchannels&action=mchannelsedit>".lang('goback')."</a>]";
		amessage($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}members WHERE mchid='$mchid'")){
		amessage('delchannelnomember', '?entry=mchannels&action=mchannelsedit');
	}
	$customtable = 'members_'.$mchid;
	$db->query("DROP TABLE IF EXISTS {$tblprefix}$customtable",'SILENT');
	$db->query("DELETE FROM {$tblprefix}mchannels WHERE mchid='$mchid'",'SILENT');
	$db->query("DELETE FROM {$tblprefix}mfields WHERE mchid='$mchid'",'SILENT');
	//清除相关缓存
	del_cache('mfields',$mchid);
	adminlog(lang('del_mchannel'));
	updatecache('mchannels');
	amessage('mchanneldeletefinish',"?entry=mchannels&action=mchannelsedit");
}
elseif($action == 'mfielddetail' && $mchid && $fieldename){
	!isset($mchannels[$mchid]) && amessage('choosememberchannel', '?entry=mchannels&action=mchannelsedit');
	$field = read_cache('mfield',$mchid,$fieldename);
	empty($field) && amessage('choosefield', '?entry=mchannels&action=mchanneldetail&mchid='.$mchid);
	if(!submitcheck('bmfielddetail')){
		$submitstr = '';
		tabheader("[".$mchannels[$mchid]['cname']."]&nbsp;&nbsp;".lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'mfielddetail',"?entry=mchannels&action=mfielddetail&mchid=$mchid&fieldename=$fieldename",2,0,1,1);
		list($fmode,$fnew,$fsave) = array('m',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bmfielddetail');
		check_submit_func($submitstr);
		a_guide('mfielddetail');
	}else{
		$customtable = $field['tbl'] == 'main' ? 'members' : ($field['tbl'] == 'sub' ? 'members_sub' : ('members_'.$mchid));
		$fconfigarr = array(
			'altertable' => $tblprefix.$customtable,
			'fieldtable' => $tblprefix.'mfields',
			'wherestr' => "WHERE ename='$fieldename' AND mchid=$mchid",
		);
		list($fmode,$fnew,$fsave) = array('m',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('mfields',$mchid);
		adminlog(lang('det_modify_mch_msg_field'));
		amessage('fieldeditfinish',axaction(10,'?entry=mchannels&action=mchannelsedit'));
	}
}
elseif($action == 'initmfieldadd'){
	if(!submitcheck('binitmfieldadd')){
		url_nav(lang('mchannel'),$urlsarr,'field');

		tabheader(lang('add_member_cfield'),'initmfieldadd',"?entry=mchannels&action=initmfieldadd",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('binitmfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('im',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('binitmfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('initmfieldadd');
	}else{
		$enamearr = $usednames['mfields'];
		foreach($mchannels as $k => $v){
			$fields = read_cache('mfields',$k);
			$enamearr = array_unique(array_merge($enamearr,array_keys($fields)));
		}
		$fconfigarr = array(
			'errorurl' => '?entry=mchannels&action=initmfieldsedit',
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'members_sub',
			'fieldtable' => $tblprefix.'mfields',
			'sqlstr' => "iscustom='1',mcommon='1',available='1',tbl='sub'",
			'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+|^grouptype(.*?)|^currency(.*?)/",
		);
		list($fmode,$fnew,$fsave) = array('im',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		updatecache('initmfields');
		$mchids = array_keys($mchannels);
		foreach($mchids as $mchid){
			$sqlstr = "ename='$fieldnew[ename]',cname='$fieldnew[cname]',issystem='0',iscustom='1',mcommon='1',mchid='$mchid',tbl='sub'";
			foreach(array('datatype','length','notnull','nohtml','mode','guide','isadmin','mlimit','rpid','issearch','innertext','mcommon','min','max','regular','isfunc','func','vdefault','custom_1','custom_2',) as $var){
				isset($fieldnew[$var]) && $sqlstr .= (!$sqlstr ? '' : ',')."$var='".$fieldnew[$var]."'";
			}
			$db->query("INSERT INTO {$tblprefix}mfields SET $sqlstr");
			updatecache('mfields',$mchid);
		}
		adminlog(lang('add_member_cmsg_field'));
		updatecache('usednames','mfields');
		amessage('fieldaddfinish', '?entry=mchannels&action=initmfieldsedit');
	}
}
elseif($action == 'initmfieldsedit'){
	if(!submitcheck('binitmfieldsedit')){
		url_nav(lang('mchannel'),$urlsarr,'field');

		tabheader(lang('member_cfield_manager')."&nbsp; &nbsp; >><a href=\"?entry=mchannels&action=initmfieldadd\">".lang('add')."</a>",'initmfieldsedit','?entry=mchannels&action=initmfieldsedit','5');
		trcategory(array(lang('delete'),lang('field_name'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($initmfields as $k => $field) {
			fieldlist($k,$field,'initm');
		}
		tabfooter('binitmfieldsedit');
		a_guide('initmfieldsedit');
	}else{
		if(!empty($delete)){
			$mchids = array_keys($mchannels);
			foreach($delete as $k){
				if($initmfields[$k]['iscustom']){
					dropfieldfromtbl('members_sub',$k,$initmfields[$k]['datatype']);
					$db->query("DELETE FROM {$tblprefix}mfields WHERE ename='$k'");
					unset($initmfields[$k],$fieldsnew[$k]);
				}
			}
			foreach($mchids as $mchid){
				updatecache('mfields',$mchid);
			}
			updatecache('usednames','mfields');
		}
		foreach($initmfields as $id => $field){
			$field['cname'] = trim($fieldsnew[$id]['cname']) ? trim($fieldsnew[$id]['cname']) : $field['cname'];
			$db->query("UPDATE {$tblprefix}mfields SET cname='$field[cname]' WHERE ename='$id' AND mchid='0'");
		}
		adminlog(lang('emcmsg_field_mlist'));
		updatecache('initmfields');
		amessage('fieldeditfinish','?entry=mchannels&action=initmfieldsedit');
	}
}elseif($action == 'initmfielddetail' && $fieldename){
	if(empty($initmfields[$fieldename])) amessage('choosefield', '?entry=mchannels&action=initmfieldsedit');
	$field = $initmfields[$fieldename];
	if(!submitcheck('binitfielddetail')){
		tabheader(lang('member_cfield_manager'),'initmfielddetail',"?entry=mchannels&action=initmfielddetail&fieldename=$fieldename",2,0,1,1);
		$submitstr = '';
		list($fmode,$fnew,$fsave) = array('im',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('binitfielddetail');
		check_submit_func($submitstr);
		a_guide('binitmfielddetail');
	}else{
		$tblname = $field['tbl'] == 'main' ? 'members' : 'members_sub';
		$fconfigarr = array(
			'altertable' => $tblprefix.$tblname,
			'fieldtable' => $tblprefix.'mfields',
			'wherestr' => "WHERE ename='$fieldename' AND mchid=0",
		);
		list($fmode,$fnew,$fsave) = array('im',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		adminlog(lang('det_modify_mcmsg_field'));
		updatecache('initmfields');
		if(($field['datatype'] == 'cacc') && ($fieldnew['max'] != $field['max'])){
			$db->query("UPDATE {$tblprefix}mfields SET max='$fieldnew[max]' WHERE ename='$field[ename]' AND mchid<>'0'");
			foreach($mchannels as $k => $v) updatecache('mfields',$k);
		}
		amessage('fieldmodifyfinish',axaction(10,'?entry=mchannels&action=initmfieldsedit'));
	}
}
/*
function fetch_arr(){
	global $db,$tblprefix;
	$items = array();
	$query = $db->query("SELECT * FROM {$tblprefix}mchannels ORDER BY mchid");
	while($item = $db->fetch_array($query)){
		$items[$item['mchid']] = $item;
	}
	return $items;
}
function fetch_one($mchid){
	global $db,$tblprefix;
	$item = $db->fetch_one("SELECT * FROM {$tblprefix}mchannels WHERE mchid='$mchid'");
	return $item;
}

*/

?>
