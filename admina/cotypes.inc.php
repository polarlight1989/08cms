<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cotype') || amessage('no_apermission');
load_cache('cotypes,channels,mtpls,rprojects,ccfields');
sys_cache('fieldwords');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.fun.php";
include_once M_ROOT."./include/fields.cls.php";
include_once M_ROOT."./include/cnode.fun.php";

if($action=='cotypesedit') {
	if(!submitcheck('bcotypesedit') && !submitcheck('bcotypesadd')) {
		$addfieldstr = "&nbsp; &nbsp; >><a href=\"?entry=cotypes&action=ccfieldsedit\">".lang('iscustom_coclass_field').'</a>';
		tabheader(lang('cotypem_manager').$addfieldstr,'cotypesedit','?entry=cotypes&action=cotypesedit','10');
		trcategory(array(lang('id'),array(lang('cotype_name'),'txtL'),lang('order'),lang('self_reg'),lang('cnode_leaguer'),lang('delete'),lang('detail'),lang('coclass')));
		foreach($cotypes as $k => $cotype){
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w35\">$k</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"cotypesnew[$k][cname]\" value=\"$cotype[cname]\"></td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"cotypesnew[$k][vieworder]\" value=\"$cotype[vieworder]\"></td>\n".
				"<td class=\"txtC w60\">".($cotype['self_reg'] ? 'Y' : '-')."</td>\n".
				"<td class=\"txtC w60\"><input class=\"checkbox\" type=\"checkbox\" name=\"cotypesnew[$k][sortable]\" value=\"1\"".($cotype['sortable'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=cotypes&action=cotypesdelete&coid=$k\">".lang('delete')."</a></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=cotypes&action=cotypedetail&coid=$k\" onclick=\"return floatwin('open_cotypesedit',this)\">".lang('setting')."</a></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=coclass&action=coclassedit&coid=$k\" onclick=\"return floatwin('open_cotypesedit',this)\">".lang('admin')."</a></td>\n".
				"</tr>";
		}
		tabfooter('bcotypesedit',lang('modify'));

		tabheader(lang('add_cotypem'),'cotypesadd','?entry=cotypes&action=cotypesedit',2,0,1);
		trbasic(lang('cotype_name'),'cotypeadd[cname]');
		trbasic(lang('is_self_reg'),'cotypeadd[self_reg]',0,'radio');
		tabfooter('bcotypesadd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('cotypeadd[cname]',1,0,0,30);
		check_submit_func($submitstr);
		a_guide('cotypesedit');
	}elseif(submitcheck('bcotypesedit')){
		if(!empty($cotypesnew)){
			foreach($cotypesnew as $k => $cotype) {
				$cotype['vieworder'] = max(0,intval($cotype['vieworder']));
				$cotype['notblank'] = empty($cotype['notblank']) ? 0 : 1;
				$cotype['sortable'] = empty($cotype['sortable']) ? 0 : 1;
				$cotype['cname'] = trim(strip_tags($cotype['cname']));
				$cotype['cname'] = $cotype['cname'] ? $cotype['cname'] : $cotypes[$k]['cname'];
				$db->query("UPDATE {$tblprefix}cotypes SET 
							cname='$cotype[cname]', 
							vieworder='$cotype[vieworder]', 
							notblank='$cotype[notblank]', 
							sortable='$cotype[sortable]'
							WHERE coid='$k'
							");
			}
			adminlog(lang('edit_cotype_mlist'));
			updatecache('cotypes');
			amessage('cotypeeditfinish',"?entry=cotypes&action=cotypesedit");
		}
	}
	elseif(submitcheck('bcotypesadd')) {
		empty($cotypeadd['cname']) && amessage('cotypenamemiss', '?entry=cotypes&action=cotypesedit');
		$db->query("INSERT INTO {$tblprefix}cotypes SET 
			cname='$cotypeadd[cname]',
			self_reg='$cotypeadd[self_reg]'
			");
		if($coid = $db->insert_id()){
			$db->query("ALTER TABLE {$tblprefix}archives ADD ccid$coid smallint(6) unsigned NOT NULL default 0",'SILENT');
			adminlog(lang('add_cotype'));
			updatecache('cotypes');
			update_cnconfigs($coid,1);
			amessage('cotypeaddfinish',"?entry=cotypes&action=cotypesedit");
		}else{
			amessage('cotypeaddfailed',"?entry=cotypes&action=cotypesedit");
		}
	}
}elseif($action == 'cotypedetail' && $coid){
	$forward = empty($forward) ? M_REFERER : $forward;
	!($cotype = $cotypes[$coid]) && amessage('choosecotype',$forward);
	if(!submitcheck('bcotypedetail')){
		tabheader(lang('cotypem_detail_edit'),'cotypedetail',"?entry=cotypes&action=cotypedetail&coid=$coid&forward=".rawurlencode($forward));
		trbasic(lang('cotype_name'),'',$cotype['cname'],'');
		trbasic(lang('cnode_leaguer_cotype'),'cotypenew[sortable]',$cotype['sortable'],'radio');
		$vmodearr = array('0' => lang('vmode0'),'1' => lang('vmode1'),'2' => lang('vmode2'),'3' => lang('vmode3'),'4' => lang('vmode4'),);
		trbasic(lang('coclassvmode'),'',makeradio('cotypenew[vmode]',$vmodearr,empty($cotype['vmode']) ? 0 : $cotype['vmode']),'');
		if(empty($cotype['self_reg'])){
			trbasic(lang('is_notblank_catas'),'cotypenew[notblank]',$cotype['notblank'],'radio');
			$relatearr = array(0 => lang('schoise'),2 => lang('smax2'),3 => lang('smax3'),4 => lang('smax4'),5 => lang('smax5'));
			trbasic(lang('asmode'),'',makeradio('cotypenew[asmode]',$relatearr,empty($cotype['asmode']) ? 0 : $cotype['asmode']),'',lang('agrelatecaid'));
			$emodearr = array(0 => lang('emode0'),1 => lang('emode1'),2 => lang('emode2'));
			trbasic(lang('emode'),'',makeradio('cotypenew[emode]',$emodearr,empty($cotype['emode']) ? 0 : $cotype['emode']),'',lang('agemode'));
			trbasic(lang('ctrl_permission'),'cotypenew[permission]',$cotype['permission'],'radio');
			trbasic(lang('ctrl_awardcp'),'cotypenew[awardcp]',$cotype['awardcp'],'radio');
			trbasic(lang('ctrl_taxcp'),'cotypenew[taxcp]',$cotype['taxcp'],'radio');
			trbasic(lang('ctrl_ftaxcp'),'cotypenew[ftaxcp]',$cotype['ftaxcp'],'radio');
			trbasic(lang('ctrl_sale'),'cotypenew[sale]',$cotype['sale'],'radio');
			trbasic(lang('ctrl_fsale'),'cotypenew[fsale]',$cotype['fsale'],'radio');
		}
		tabfooter('bcotypedetail');
		a_guide('cotypedetail');
	}else{
		$cotypenew['notblank'] = empty($cotypenew['notblank']) ? 0 : 1;
		$cotypenew['permission'] = empty($cotypenew['permission']) ? 0 : 1;
		$cotypenew['awardcp'] = empty($cotypenew['awardcp']) ? 0 : 1;
		$cotypenew['taxcp'] = empty($cotypenew['taxcp']) ? 0 : 1;
		$cotypenew['ftaxcp'] = empty($cotypenew['ftaxcp']) ? 0 : 1;
		$cotypenew['sale'] = empty($cotypenew['sale']) ? 0 : 1;
		$cotypenew['fsale'] = empty($cotypenew['fsale']) ? 0 : 1;
		$cotypenew['asmode'] = empty($cotypenew['asmode']) ? 0 : max(2,intval($cotypenew['asmode']));
		$cotypenew['emode'] = empty($cotypenew['emode']) ? 0 : max(0,intval($cotypenew['emode']));
		if(empty($cotype['self_reg'])){
			if(!select_alter($cotypenew['asmode'],@$cotype['asmode'],'ccid'.$coid,$tblprefix.'archives')) $cotypenew['asmode'] = @$cotype['asmode'];
			if(!emode_alter($cotypenew['emode'],@$cotype['emode'],'ccid'.$coid,$tblprefix.'archives')) $cotypenew['emode'] = @$cotype['emode'];
		}
		$db->query("UPDATE {$tblprefix}cotypes SET 
			notblank='$cotypenew[notblank]',
			sortable='$cotypenew[sortable]',
			vmode='$cotypenew[vmode]',
			asmode='$cotypenew[asmode]',
			emode='$cotypenew[emode]',
			permission='$cotypenew[permission]',
			awardcp='$cotypenew[awardcp]',
			taxcp='$cotypenew[taxcp]',
			ftaxcp='$cotypenew[ftaxcp]',
			sale='$cotypenew[sale]',
			fsale='$cotypenew[fsale]'
			WHERE coid='$coid'");
		adminlog(lang('det_modify_cotype'));
		updatecache('cotypes');
		amessage('cotypemsetfinish',axaction(6,$forward));
	}
}elseif($action == 'cotypesdelete' && $coid) {//删除类系，与节点的关系
	if(!isset($confirm) || $confirm != 'ok'){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=cotypes&action=cotypesdelete&coid=$coid&confirm=ok>".lang('delete')."</a><br>";
		$message .= lang('giveupclick').">><a href=?entry=cotypes&action=cotypesedit>".lang('goback')."</a>";
		amessage($message);
	}
	//删除相应的类目字段及资料
	$query = $db->query("SELECT * FROM {$tblprefix}fields WHERE datatype='cacc' AND length='$coid'");
	while($row = $db->fetch_array($query)) !($row['mcommon'] && $row['chid']) && $db->query("ALTER TABLE {$tblprefix}archives".(!$row['chid'] ? '' : "_$row[chid]")." DROP $row[ename]",'SILENT');
	$query = $db->query("SELECT * FROM {$tblprefix}mfields WHERE datatype='cacc' AND length='$coid'");
	while($row = $db->fetch_array($query)) !($row['mcommon'] && $row['mchid']) && $db->query("ALTER TABLE {$tblprefix}members_".(!$row['mchid'] ? 'sub' : $row['mchid'])." DROP $row[ename]",'SILENT');
	$query = $db->query("SELECT * FROM {$tblprefix}ffields WHERE datatype='cacc' AND length='$coid'");
	while($row = $db->fetch_array($query)) $db->query("ALTER TABLE {$tblprefix}farchives_".$row['chid']." DROP $row[ename]",'SILENT');
	$query = $db->query("SELECT * FROM {$tblprefix}mafields WHERE datatype='cacc' AND length='$coid'");
	while($row = $db->fetch_array($query)) $db->query("ALTER TABLE {$tblprefix}marchives_".$row['matid']." DROP $row[ename]",'SILENT');
	$query = $db->query("SELECT * FROM {$tblprefix}cnfields WHERE datatype='cacc' AND length='$coid'");
	while($row = $db->fetch_array($query)) $db->query("ALTER TABLE $tblprefix".($row['iscc'] ? 'coclass' : 'catalogs')." DROP $row[ename]",'SILENT');
	$query = $db->query("SELECT * FROM {$tblprefix}cufields WHERE datatype='cacc' AND length='$coid'");
	$arr = array(1 => 'purchases',2 => 'offers',3 => 'replys',4 => 'comments',5 => 'reports',);
	while($row = $db->fetch_array($query)) $db->query("ALTER TABLE $tblprefix".$arr[$row['cu']]." DROP $row[ename]",'SILENT');
	$query = $db->query("SELECT * FROM {$tblprefix}mcufields WHERE datatype='cacc' AND length='$coid'");
	$arr = array(2 => 'mflinks',3 => 'mcomments',4 => 'mreplys',5 => 'mreports',);
	while($row = $db->fetch_array($query)) $db->query("ALTER TABLE $tblprefix".$arr[$row['cu']]." DROP $row[ename]",'SILENT');
	foreach(array('fields','mfields','ffields','mafields','cnfields','cufields','mcufields',) as $k) $db->query("DELETE FROM {$tblprefix}$k WHERE datatype='cacc' AND length='$coid'",'SILENT');
	foreach(array('channels' => 'fields','fchannels' => 'ffields','matypes' => 'mafields','mchannels' => 'mfields',) as $k => $v){
		load_cache($k);
		foreach($$k as $x => $y) updatecache($v,$x);
	}
	foreach(array('initfields','initmfields','cafields','ccfields','pfields','ofields','rfields','cfields','bfields','mlfields','mcfields','mrfields','mbfields',) as $v) updatecache($v);
	foreach(array('fields','mfields','cafields','ccfields','ffields','pfields','ofields','rfields','cfields','bfields','mffields','mlfields','mcfields','mrfields','mbfields',) as $k) updatecache('usednames',$k);

	//删除相关的节点
	$db->query("DELETE FROM {$tblprefix}cnodes WHERE ename REGEXP 'ccid$coid='");
	foreach($subsites as $k => $v) updatecache('cnodes',1,$k);
	$db->query("DELETE FROM {$tblprefix}mcnodes WHERE mcnvar='ccid$coid'");
	updatecache('mcnodes');
	
	//更新相应的节点结构
	update_cnconfigs($coid,0);

	$db-> query("ALTER TABLE {$tblprefix}archives DROP ccid$coid",'SILENT'); 
	$db-> query("ALTER TABLE {$tblprefix}archives DROP ccid{$coid}date",'SILENT'); 
	$db->query("DELETE FROM {$tblprefix}coclass WHERE coid='$coid'",'SILENT');
	$db->query("DELETE FROM {$tblprefix}cotypes WHERE coid='$coid'",'SILENT');
	updatecache('cotypes');
	@unlink(M_ROOT."./dynamic/cache/coclasses".$coid.".cac.php");
	adminlog(lang('del_cotype'));
	amessage('cotypedelfinish',"?entry=cotypes&action=cotypesedit");
}elseif($action == 'ccfieldadd'){
	if(!submitcheck('bccfieldadd')){
		tabheader(lang('add_class_msg_field'),'ccfieldadd',"?entry=cotypes&action=ccfieldadd",2,0,1);
		$submitstr = '';
		if(empty($fieldnew['datatype'])){
			trbasic(lang('field_type'),'fieldnew[datatype]',makeoption($datatypearr),'select');
			tabfooter('bccfieldaddpre',lang('continue'));
		}elseif(empty($baddpre1) && $fieldnew['datatype'] == 'cacc'){
			trbasic(lang('field_type'),'',$datatypearr[$fieldnew['datatype']],'');
			trhidden('fieldnew[datatype]',$fieldnew['datatype']);
			$coidsarr = array('0' => lang('catalog'));
			load_cache('cotypes');
			foreach($cotypes as $k => $v) !$v['self_reg'] && $coidsarr[$k] = $v['cname'];
			trbasic(lang('sourcecotype'),'fieldnew[length]',makeoption($coidsarr),'select');
			tabfooter('baddpre1',lang('continue'));
		}else{
			list($fmode,$fnew,$fsave) = array('cn',true,false);
			include_once M_ROOT."./include/fields/$fieldnew[datatype].php";
			tabfooter('bccfieldadd',lang('add'));
		}
		check_submit_func($submitstr);
		a_guide('ccfieldadd');
	}else{
		$enamearr = $usednames['ccfields'];
		$fconfigarr = array(
			'errorurl' => '?entry=cotypes&action=ccfieldsedit',
			'enamearr' => $enamearr,
			'altertable' => $tblprefix.'coclass',
			'fieldtable' => $tblprefix.'cnfields',
			'sqlstr' => "iscc='1'",
		);
		list($fmode,$fnew,$fsave) = array('cn',true,true);
		include_once M_ROOT."./include/fields/$fieldnew[datatype].php";
		adminlog(lang('add_cotype_msg_field'));
		updatecache('ccfields');
		updatecache('usednames','ccfields');
		amessage('fieldaddfinish','?entry=cotypes&action=ccfieldsedit');
	}
}elseif($action == 'ccfieldsedit'){
	if(!submitcheck('bccfieldsedit')){
		tabheader(lang('class_msg_field_manager')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>><a href=\"?entry=cotypes&action=ccfieldadd\">".lang('add_field')."</a>",'ccfieldsedit','?entry=cotypes&action=ccfieldsedit','5');
		trcategory(array(lang('delete'),lang('field_name'),lang('order'),lang('field_ename'),lang('field_type'),lang('edit')));
		foreach($ccfields as $k => $ccfield) {
			fieldlist($k,$ccfield,'cc');
		}
		tabfooter('bccfieldsedit');
		a_guide('ccfieldsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $id){
				$fieldename = $ccfields[$id]['ename'];
				dropfieldfromtbl('coclass',$fieldename,$ccfields[$fieldename]['datatype']);
				$db->query("DELETE FROM {$tblprefix}cnfields WHERE iscc=1 AND ename='$id'");
				unset($ccfields[$id],$fieldsnew[$id]);
			}
		}
		foreach($ccfields as $id => $field){
			$fieldsnew[$id]['cname'] = trim(strip_tags($fieldsnew[$id]['cname']));
			$field['cname'] = $fieldsnew[$id]['cname'] ? $fieldsnew[$id]['cname'] : $field['cname'];
			$field['vieworder'] = max(0,intval($fieldsnew[$id]['vieworder']));
			$db->query("UPDATE {$tblprefix}cnfields SET 
						vieworder='$field[vieworder]',
						cname='$field[cname]'
						WHERE iscc=1 AND ename='$id'");
		}
		adminlog(lang('edit_cotype_msg_field'));
		updatecache('ccfields');
		updatecache('usednames','ccfields');
		amessage('fieldmodifyfinish','?entry=cotypes&action=ccfieldsedit');
	}
}elseif($action == 'ccfielddetail' && $fieldename){
	!isset($ccfields[$fieldename]) && amessage('choosefield', '?entry=cotypes&action=ccfieldsedit');
	$field = $ccfields[$fieldename];
	if(!submitcheck('bccfielddetail')){
		$submitstr = '';
		tabheader(lang('field_edit')."&nbsp;&nbsp;[$field[cname]]",'ccfielddetail',"?entry=cotypes&action=ccfielddetail&fieldename=$fieldename",2,0,1);
		list($fmode,$fnew,$fsave) = array('cn',false,false);
		include_once M_ROOT."./include/fields/$field[datatype].php";
		tabfooter('bccfielddetail');
		check_submit_func($submitstr);
		a_guide('ccfielddetail');
	}else{
		$fconfigarr = array(
			'altertable' => $tblprefix.'coclass',
			'fieldtable' => $tblprefix.'cnfields',
			'wherestr' => "WHERE ename='$fieldename' AND iscc=1",
		);
		list($fmode,$fnew,$fsave) = array('cn',false,true);
		include_once M_ROOT."./include/fields/$field[datatype].php";
		adminlog(lang('det_modify_cotype_msg_field'));
		updatecache('ccfields');
		amessage('fieldmodifyfinish','?entry=cotypes&action=ccfieldsedit');
	}
} 
?>
