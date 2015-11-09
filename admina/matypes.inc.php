<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('matype') || amessage('no_apermission');
load_cache('matypes,mtpls,permissions');
sys_cache('fieldwords');
include_once M_ROOT."./include/fields.fun.php";

if(empty($action)) $action = 'matypesedit';
if($action == 'matypesedit'){
	if(!submitcheck('bmatypesedit') && !submitcheck('bmatypeadd')){
		$cuidsarr = cuidsarr('answer') + cuidsarr('purchase');
		tabheader(lang('matype'),'matypesedit',"?entry=matypes&action=matypesedit$param_suffix",10);
		trcategory(array(lang('id'),lang('typename'),lang('order'),lang('delete'),lang('edit')));
		foreach($matypes as $k => $matype){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\">$k</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"matypesnew[$k][cname]\" value=\"$matype[cname]\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" size=\"4\" name=\"matypesnew[$k][vieworder]\" value=\"$matype[vieworder]\"></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=matypes&action=matypedel&matid=$k\">".lang('delete')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=matypes&action=matypedetail&matid=$k$param_suffix\" onclick=\"return floatwin('open_matypesedit',this)\">".lang('detail')."</a></td>\n".
				"</tr>\n";
		}
		tabfooter('bmatypesedit',lang('modify'));

		tabheader(lang('add_marc_type'),'matypeadd','?entry=matypes&action=matypesedit',2,0,1);
		trbasic(lang('matype_name'),'matypeadd[cname]');
		tabfooter('bmatypeadd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('matypeadd[cname]',1,0,0,50);
		check_submit_func($submitstr);
		a_guide('matypesedit');
	}elseif(submitcheck('bmatypesedit')){
		if(isset($matypesnew)){
			foreach($matypesnew as $k => $v){
				$v['cname'] = trim(strip_tags($v['cname']));
				$v['cname'] = $v['cname'] ? $v['cname'] : $matypes[$k]['cname'];
				$db->query("UPDATE {$tblprefix}matypes SET cname='$v[cname]',vieworder='$v[vieworder]' WHERE matid='$k'");
			}
			adminlog(lang('edit_matype_list'));
			updatecache('matypes');
		}
		amessage('marcmodifysuccess',"?entry=matypes&action=matypesedit$param_suffix");
	}elseif(submitcheck('bmatypeadd')){
		$matypeadd['cname'] = trim(strip_tags($matypeadd['cname']));
		empty($matypeadd['cname']) && amessage('inputmatypename', '?entry=matypes&action=matypesedit');
		$db->query("INSERT INTO {$tblprefix}matypes SET cname='$matypeadd[cname]'");
		if($matid = $db->insert_id()){
			$sqlstr = '';
			$db->query("CREATE TABLE {$tblprefix}marchives_$matid (
						maid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
						matid smallint(6) unsigned NOT NULL default '0',
						mid mediumint(8) unsigned NOT NULL default '0',
						mname varchar(15) NOT NULL default '',
						arcurl varchar(100) NOT NULL default '',
						checked tinyint(1) unsigned NOT NULL default '0',
						createdate int(10) unsigned NOT NULL default '0',
						updatedate int(10) unsigned NOT NULL default '0',
						refreshdate int(10) unsigned NOT NULL default '0',
						editor varchar(15) NOT NULL default '',
						PRIMARY KEY (maid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
			updatecache('matypes');
			updatecache('mafields',$matid);
		}
		adminlog(lang('add_marc_type'));
		amessage('matypeaddsuccess',"?entry=matypes&action=matypesedit$param_suffix");
	}
}elseif($action == 'matypedetail' && $matid){
	$matype = read_cache('matype',$matid);
	$fields = read_cache('mafields',$matid);
	if(!submitcheck('bmatypedetail')){
		tabheader("[$matype[cname]]".lang('matype_set'),'matypedetail',"?entry=matypes&action=matypedetail&matid=$matid$param_suffix",4,0,0,1);
		trbasic(lang('add_arc_autocheck'),'matypenew[autocheck]',$matype['autocheck'],'radio');
		trbasic(lang('add_arc_autostatic'),'matypenew[autostatic]',$matype['autostatic'],'radio');
		trbasic(lang('allow_update_checked_arc'),'matypenew[allowupdate]',$matype['allowupdate'],'radio');
		trbasic(lang('issue_permission_set'),'matypenew[apmid]',makeoption(pmidsarr('aadd'),$matype['apmid']),'select');//会员档案权限方案与常规文档同步
		trbasic(lang('read_permi_set'),'matypenew[rpmid]',makeoption(pmidsarr('aread'),$matype['rpmid']),'select');
		tabfooter();

		tabheader("[$matype[cname]]".lang('field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=matypes&action=fieldadd&matid=$matid\">".lang('add_field')."</a>",'','','8');
		trcategory(array(lang('delete'),lang('field_name'),lang('field_ename'),lang('admin_self'),lang('order'),lang('field_type'),lang('edit')));
		foreach($fields as $k => $field) fieldlist($k,$field,'ma');
		tabfooter('bmatypedetail');
		a_guide('matypedetail');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				dropfieldfromtbl("marchives_$matid",$id,$fields[$id]['datatype']);
				$db->query("DELETE FROM {$tblprefix}mafields WHERE ename='$id' AND matid='$matid'"); 
				unset($fields[$id],$fieldsnew[$id]);
			}
		}
		foreach($fields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['isadmin'] = $field['issystem'] ? '0' : (empty($fieldsnew[$id]['isadmin']) ? 0 : 1);
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}mafields SET cname='$field[cname]',vieworder='$field[vieworder]',isadmin='$field[isadmin]' WHERE ename='$id' AND matid='$matid'");
		}
		$db->query("UPDATE {$tblprefix}matypes SET 
			autocheck='$matypenew[autocheck]', 
			autostatic='$matypenew[autostatic]', 
			allowupdate='$matypenew[allowupdate]', 
			apmid='$matypenew[apmid]',
			rpmid='$matypenew[rpmid]'
			WHERE matid='$matid'");
		updatecache('matypes');
		updatecache('mafields',$matid);
		adminlog(lang('det_set_matype'));
		amessage('matypesetsuccess', axaction(2,"?entry=matypes&action=matypesedit$param_suffix"));
	}
}elseif($action == 'fieldadd' && $matid){
	if(!submitcheck('bfieldadd')){
		tabheader(lang('add_matype_field')."&nbsp; -&nbsp; ".$matypes[$matid]['cname'],'fieldadd',"?entry=matypes&action=fieldadd&matid=$matid",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			trbasic(lang('is_func_field'),'fieldnew[isfunc]',0,'radio');
			tabfooter('bfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('ma',true,false);
			include M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('fieldadd');
	}else{
		$fields = read_cache('mafields',$matid);
		$enamearr = array('maid','mid','mname','createdate','checked');
		foreach($fields as $ename => $field) $enamearr[] = $ename;
		$fconfigarr = array(
		'errorurl' => '?entry=matypes&action=matypedetail&matid='.$matid,
		'enamearr' => $enamearr,
		'altertable' => $tblprefix.'marchives_'.$matid,
		'fieldtable' => $tblprefix.'mafields',
		'sqlstr' => "matid=$matid",
		'filterstr' => "/[^a-zA-Z_0-9]+|^[0-9_]+/",
		);
		list($fmode,$fnew,$fsave) = array('ma',true,true);
		include M_ROOT."./include/fields/$fieldnew[datatype].php";
		adminlog(lang('add_matype_field'));
		updatecache('mafields',$matid);
		amessage('mafieldaddsuccess', '?entry=matypes&action=matypedetail&matid='.$matid);
	}
}
elseif($action == 'matypedel' && $matid) {
	$matype = $matypes[$matid];
	if(empty($confirm)){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick')."[<a href=?entry=matypes&action=matypedel&matid=".$matid."&confirm=1>".lang('delete')."</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$message .= lang('giveupclick')."[<a href=?entry=matypes&action=matypesedit>".lang('goback')."</a>]";
		amessage($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}marchives_$matid")){
		amessage('deluserasmatype', '?entry=matypes&action=matypesedit');
	}

	$db->query("DELETE FROM {$tblprefix}mcnodes WHERE mcnvar='matid' AND mcnid='$matid'");
	updatecache('mcnodes');
	$db->query("DROP TABLE IF EXISTS {$tblprefix}marchives_$matid",'SILENT');
	$db->query("DELETE FROM {$tblprefix}matypes WHERE matid='$matid'",'SILENT');
	$db->query("DELETE FROM {$tblprefix}mafields WHERE matid='$matid'",'SILENT');
	//清除相关缓存
	del_cache('mafields',$matid);
	adminlog(lang('del_matype'));
	updatecache('matypes');
	amessage('matypedelsuccess',"?entry=matypes&action=matypesedit");
}elseif($action == 'fielddetail' && $matid && $fieldename){
	!isset($matypes[$matid]) && amessage('choosematype');
	$field = read_cache('mafield',$matid,$fieldename);
	empty($field) && amessage('choosefield');
	if(!submitcheck('bfielddetail')){
		$submitstr = '';
		tabheader(lang('edit_matype_field')."&nbsp; -&nbsp; ".$matypes[$matid]['cname']."&nbsp; -&nbsp; $field[cname]",'fielddetail',"?entry=matypes&action=fielddetail&matid=$matid&fieldename=$fieldename",2,0,1,1);
		list($fmode,$fnew,$fsave) = array('ma',false,false);
		include M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bfielddetail');
		check_submit_func($submitstr);
		a_guide('fielddetail');
	}else{
		$fconfigarr = array(
		'altertable' => $tblprefix.'marchives_'.$matid,
		'fieldtable' => $tblprefix.'mafields',
		'wherestr' => "WHERE ename='$fieldename' AND matid=$matid",
		);
		list($fmode,$fnew,$fsave) = array('ma',false,true);
		include M_ROOT."./include/fields/$field[datatype].php";
		updatecache('mafields',$matid);
		adminlog(lang('edit_matype_field'));
		amessage('mafieldmodifysuccess',axaction(10,'?entry=matypes&action=matypedetail&matid='.$matid));
	}
}
?>