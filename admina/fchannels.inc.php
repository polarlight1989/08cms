<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('freeinfo') || amessage('no_apermission');
load_cache('fchannels,mtpls,initfields,rprojects');
sys_cache('fieldwords');
include_once M_ROOT."./include/fields.fun.php";
$url_type = 'fchannel';include 'urlsarr.inc.php';

if($action == 'fchannelsedit') {
	if(!submitcheck('bfchannelsedit') && !submitcheck('bfchanneladd')) {
		url_nav(lang('pluginframework'),$urlsarr,'channel');
		tabheader(lang('channel_manager'),'fchannelsedit','?entry=fchannels&action=fchannelsedit','4');
		trcategory(array(lang('id'),lang('channel_name'),lang('edit'),lang('delete')));
		foreach($fchannels as $k => $fchannel) {
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w30\">$k</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"fchannelnew[$k][cname]\" value=\"$fchannel[cname]\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=fchannels&action=fchanneldetail&chid=$fchannel[chid]\" onclick=\"return floatwin('open_fchannelsedit',this)\">".lang('detail')."</a></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=fchannels&action=fchanneldel&chid=$fchannel[chid]\">".lang('delete')."</a></td>\n".
				"</tr>\n";
		}
		tabfooter('bfchannelsedit',lang('modify'));

		tabheader(lang('add_channel'),'fchanneladd','?entry=fchannels&action=fchannelsedit',2,0,1);
		trbasic(lang('channel_name'),'fchanneladd[cname]');
		tabfooter('bfchanneladd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('fchanneladd[cname]',1,0,3,30);
		check_submit_func($submitstr);
		a_guide('fchannelsedit');
	}elseif(submitcheck('bfchannelsedit')) {
		if(isset($fchannelnew)) {
			foreach($fchannelnew as $k => $v) {
				$v['cname'] = trim(strip_tags($v['cname']));
				$v['cname'] = $v['cname'] ? $v['cname'] : $fchannels[$k]['cname'];
				if($v['cname'] != $fchannels[$k]['cname']){
					$db->query("UPDATE {$tblprefix}fchannels SET cname='$v[cname]' WHERE chid='$k'");
				}
			}
			adminlog(lang('edit_freeinfo_channel_list'));
			updatecache('fchannels');
			amessage('frechaedifin',"?entry=fchannels&action=fchannelsedit");
		}
	}elseif(submitcheck('bfchanneladd')) {
		$fchanneladd['cname'] = trim(strip_tags($fchanneladd['cname']));
		empty($fchanneladd['cname']) && amessage('channelnamemiss','?entry=fchannels&action=fchannelsedit');
		$db->query("INSERT INTO {$tblprefix}fchannels SET cname='$fchanneladd[cname]'");
		if($chid = $db->insert_id()){
			$db->query("CREATE TABLE {$tblprefix}farchives_$chid (
						aid mediumint(8) unsigned NOT NULL default '0',
						PRIMARY KEY (aid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
			$db->query("INSERT INTO {$tblprefix}ffields SET 
						ename='subject', 
						cname='".lang('message_title')."', 
						chid='$chid', 
						issystem='1', 
						available='1', 
						innertext='', 
						length='255', 
						datatype='text', 
						notnull='1', 
						mlimit='0', 
						rpid='0'");
			updatecache('ffields',$chid);
			updatecache('fchannels');
		}
		adminlog(lang('add_freeinfo_channel'));
		amessage('frechaaddfin',"?entry=fchannels&action=fchannelsedit");
	}
}elseif($action == 'ffieldadd' && $chid){
	if(!submitcheck('bffieldadd')){
		url_nav(lang('pluginframework'),$urlsarr,'channel');
		tabheader(lang('add')."&nbsp;[".$fchannels[$chid]['cname']."]&nbsp;".lang('field'),'ffieldadd',"?entry=fchannels&action=ffieldadd&chid=$chid",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bffieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('fa',true,false);
			include_once M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bffieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('ffieldadd');
	}else{
		$enamearr = $usednames['ffields'];
		$fields = read_cache('ffields',$chid);
		foreach($fields as $ename => $field){
			if(!in_array($ename,$enamearr)) $enamearr[] = $ename;
		}
		$fconfigarr = array(
			'errorurl' => '?entry=fchannels&action=fchanneldetail&chid='.$chid,
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'farchives_'.$chid,
			'fieldtable' => $tblprefix.'ffields',
			'sqlstr' => "chid=$chid,available='1'",
		);
		list($fmode,$fnew,$fsave) = array('fa',true,true);
		include_once M_ROOT."./include/fields/$fieldnew[datatype].php";
		adminlog(lang('add_free_channel_field'));
		updatecache('ffields',$chid);
		amessage('fieldaddfinish', '?entry=fchannels&action=fchanneldetail&chid='.$chid);
	}
}elseif($action == 'fchanneldetail' && $chid) {
	$fchannel = $fchannels[$chid];
	$fields = read_cache('ffields',$chid);
	if(!submitcheck('bfchanneldetail')){
		//url_nav(lang('pluginframework'),$urlsarr,'channel');
		tabheader("[".$fchannel['cname']."]".lang('field_edit')."&nbsp; &nbsp; &nbsp; >><a href=\"?entry=fchannels&action=ffieldadd&chid=$chid\">".lang('add_field')."</a>",'fchanneldetail',"?entry=fchannels&action=fchanneldetail&chid=$chid",'7');
		trcategory(array(lang('delete'),lang('field_name'),lang('admin_self'),lang('order'),lang('field_ename'),lang('field_type'),lang('detail')));
		foreach($fields as $k => $field){
			fieldlist($k,$field,'fch');
		}
		tabfooter('bfchanneldetail');
		a_guide('fchanneldetail');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				if(!$fields[$id]['issystem']){
					dropfieldfromtbl("farchives_$chid",$id,$fields[$id]['datatype']);
					$db->query("DELETE FROM {$tblprefix}ffields WHERE ename='$id' AND chid='$chid'"); 
					unset($fields[$id],$fieldsnew[$id]);
				}
			}
		}
		foreach($fields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = $field['issystem'] ? '0' : (empty($fieldsnew[$id]['isadmin']) ? 0 : 1);
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}ffields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND chid='$chid'");
		}
		adminlog(lang('detail0_modify_freeinfo_channel_field'));
		updatecache('ffields',$chid);
		amessage('channelmodifyfinish', axaction(6,'?entry=fchannels&action=fchanneldetail&chid='.$chid));
	}
}
elseif($action == 'fchanneldel' && $chid) {
	if(empty($confirm)){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick')."[<a href=?entry=fchannels&action=fchanneldel&chid=".$chid."&confirm=1>".lang('delete')."</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick')."[<a href=?entry=fchannels&action=fchannelsedit>".lang('goback')."</a>]";
		amessage($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}fcatalogs WHERE chid='$chid'")) {
		amessage('chaoutrelocdel', '?entry=fchannels&action=fchannelsedit');
	}
	$db->query("DROP TABLE IF EXISTS {$tblprefix}farchives_$chid");
	$db->query("DELETE FROM {$tblprefix}fchannels WHERE chid='$chid'");
	$db->query("DELETE FROM {$tblprefix}ffields WHERE chid='$chid'");

	del_cache('ffields',$chid);
	@unlink(M_ROOT.'./dynamic/mguides/free_'.$chid.'.php');

	adminlog(lang('delete_freeinfo_channel'));
	updatecache('fchannels');
	amessage('frechadelfin',"?entry=fchannels&action=fchannelsedit");

}elseif($action == 'fielddetail' && $chid && $fieldename){
	!isset($fchannels[$chid]) && amessage('choosechannel', '?entry=fchannels&action=fchannelsedit');
	$field = read_cache('ffield',$chid,$fieldename);
	empty($field) && amessage('choosefield', '?entry=fchannels&action=fchanneldetail&chid='.$chid);
	if(!submitcheck('bfielddetail')){
		tabheader("[".$fchannels[$chid]['cname']."]&nbsp;&nbsp;".lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'fielddetail',"?entry=fchannels&action=fielddetail&chid=$chid&fieldename=$fieldename",2,0,1);
		$submitstr = '';
		list($fmode,$fnew,$fsave) = array('fa',false,false);
		include_once M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bfielddetail',lang('submit'));
		check_submit_func($submitstr);
		a_guide('ffielddetail');
	}else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'farchives_'.$chid,
			'fieldtable' => $tblprefix.'ffields',
			'wherestr' => "WHERE ename='$fieldename' AND chid=$chid",
		);
		list($fmode,$fnew,$fsave) = array('fa',false,true);
		include_once M_ROOT."./include/fields/$field[datatype].php";
		adminlog(lang('detail0_modify_freeinfo_channel_field'));
		updatecache('ffields',$chid);
		amessage('fieldeditfinish',axaction(6,'?entry=fchannels&action=fchanneldetail&chid='.$chid));
	}
}

?>
