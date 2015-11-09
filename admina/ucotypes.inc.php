<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cfcommu') || amessage('no_apermission');
load_cache('ucotypes');
$cclassarr = array(
'comment' => lang('comment'),
'answer' => lang('answer'),
'purchase' => lang('purchase'),
'offer' => lang('offer'),
'reply' => lang('reply'),
'report' => lang('report'),
);

if($action=='ucotypesedit') {
		$url_type = 'commu';include 'urlsarr.inc.php';
		url_nav(lang('docinterconfig'),$urlsarr,'coclass');
	if(!submitcheck('bucotypesedit') && !submitcheck('bucotypesadd')){
		tabheader(lang('ucotype_manager'),'ucotypesedit','?entry=ucotypes&action=ucotypesedit','10');
		trcategory(array(lang('id'),lang('cotype_name'),lang('commu_type'),lang('order'),lang('delete'),lang('setting'),lang('coclass')));
		foreach($ucotypes as $k => $ucotype){
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w35\">$k</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"30\" maxlength=\"30\" name=\"ucotypesnew[$k][cname]\" value=\"$ucotype[cname]\"></td>\n".
				"<td class=\"txtC\">".$cclassarr[$ucotype['cclass']]."</td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"ucotypesnew[$k][vieworder]\" value=\"$ucotype[vieworder]\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=ucotypes&action=ucotypesdelete&ucoid=$k\">".lang('delete')."</a></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=ucotypes&action=ucotypedetail&ucoid=$k\" onclick=\"return floatwin('open_ucotypesedit',this)\">".lang('setting')."</a></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=ucoclass&action=ucoclassedit&ucoid=$k\" onclick=\"return floatwin('open_ucotypesedit',this)\">".lang('admin')."</a></td>\n".
				"</tr>";
		}
		tabfooter('bucotypesedit',lang('modify'));

		tabheader(lang('add_ucotype'),'ucotypesadd','?entry=ucotypes&action=ucotypesedit',2,0,1);
		trbasic(lang('cotype_name'),'ucotypeadd[cname]');
		trbasic(lang('commu_type'),'',makeradio('ucotypeadd[cclass]',$cclassarr),'');
		tabfooter('bucotypesadd',lang('add'));
		$submitstr = '';
		$submitstr .= makesubmitstr('ucotypeadd[cname]',1,0,0,30);
		check_submit_func($submitstr);
		a_guide('ucotypesedit');
	}elseif(submitcheck('bucotypesedit')){
		if(!empty($ucotypesnew)){
			foreach($ucotypesnew as $k => $ucotype) {
				$ucotype['vieworder'] = max(0,intval($ucotype['vieworder']));
				$ucotype['cname'] = trim(strip_tags($ucotype['cname']));
				$ucotype['cname'] = $ucotype['cname'] ? $ucotype['cname'] : $ucotypes[$k]['cname'];
				$db->query("UPDATE {$tblprefix}ucotypes SET cname='$ucotype[cname]',vieworder='$ucotype[vieworder]' WHERE ucoid='$k'");
			}
			adminlog(lang('edit_cotype_mlist'));
			updatecache('ucotypes');
			amessage('cotypeeditfinish',"?entry=ucotypes&action=ucotypesedit");
		}
	}elseif(submitcheck('bucotypesadd')) {
		empty($ucotypeadd['cname']) && amessage('cotypenamemiss', '?entry=ucotypes&action=ucotypesedit');
		$db->query("INSERT INTO {$tblprefix}ucotypes SET cname='$ucotypeadd[cname]',cclass='$ucotypeadd[cclass]'");
		if($ucoid = $db->insert_id()){
			$customtable = $ucotypeadd['cclass'].'s';
			$db->query("ALTER TABLE {$tblprefix}$customtable ADD uccid$ucoid smallint(6) unsigned NOT NULL default 0",'SILENT');
			adminlog(lang('add_ucotype'));
			updatecache('ucotypes');
			amessage('cotypeaddfinish',"?entry=ucotypes&action=ucotypesedit");
		}else{
			amessage('cotypeaddfailed',"?entry=ucotypes&action=ucotypesedit");
		}
	}
}elseif($action == 'ucotypedetail' && $ucoid){
	$forward = empty($forward) ? M_REFERER : $forward;
	!($ucotype = $ucotypes[$ucoid]) && amessage('chooseucotype');
	if(!submitcheck('bucotypedetail')){
		tabheader(lang('ucotypem_detail_edit'),'ucotypedetail',"?entry=ucotypes&action=ucotypedetail&ucoid=$ucoid&forward=".rawurlencode($forward));
		trbasic(lang('cotype_name'),'',$ucotype['cname'],'');
		$umodearr = array('0' => lang('umode0'),'1' => lang('umode1'),'2' => lang('umode2'));
		trbasic(lang('coclassumode'),'',makeradio('ucotypenew[umode]',$umodearr,empty($ucotype['umode']) ? 0 : $ucotype['umode']),'');
		$vmodearr = array('0' => lang('vmode0'),'1' => lang('vmode1'));
		trbasic(lang('coclassvmode'),'',makeradio('ucotypenew[vmode]',$vmodearr,empty($ucotype['vmode']) ? 0 : $ucotype['vmode']),'');
		trbasic(lang('is_notblank_catas'),'ucotypenew[notblank]',$ucotype['notblank'],'radio');
		if(in_array($ucotype['cclass'],array('comment','offer','reply',))){
			$emodearr = array(0 => lang('emode0'),1 => lang('emode1'),2 => lang('emode2'));
			trbasic(lang('emode'),'',makeradio('ucotypenew[emode]',$emodearr,empty($ucotype['emode']) ? 0 : $ucotype['emode']),'',lang('agemode'));
		}
		tabfooter('bucotypedetail');
		a_guide('ucotypedetail');
	}else{
		$ucotypenew['notblank'] = empty($ucotypenew['notblank']) ? 0 : 1;
		$ucotypenew['emode'] = empty($ucotypenew['emode']) ? 0 : max(0,intval($ucotypenew['emode']));
		if(!emode_alter($ucotypenew['emode'],@$ucotype['emode'],'uccid'.$ucoid,$tblprefix.$ucotype['cclass'].'s')) $ucotypenew['emode'] = @$ucotype['emode'];
		$db->query("UPDATE {$tblprefix}ucotypes SET 
			notblank='$ucotypenew[notblank]',
			umode='$ucotypenew[umode]',
			vmode='$ucotypenew[vmode]',
			emode='$ucotypenew[emode]'
			WHERE ucoid='$ucoid'");
		adminlog(lang('det_modify_ucotype'));
		updatecache('ucotypes');
		amessage('cotypemsetfinish',axaction(6,$forward));
	}
}elseif($action == 'ucotypesdelete' && $ucoid) {
	if(!isset($confirm) || $confirm != 'ok'){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=ucotypes&action=ucotypesdelete&ucoid=$ucoid&confirm=ok>".lang('delete')."</a><br>";
		$message .= lang('giveupclick').">><a href=?entry=ucotypes&action=ucotypesedit>".lang('goback')."</a>";
		amessage($message);
	}
	if(!($ucotype = $ucotypes[$ucoid])) amessage('choosecotype');
	$customtable = $ucotype['cclass'].'s';
	$db-> query("ALTER TABLE {$tblprefix}$customtable DROP uccid$coid",'SILENT'); 
	$db-> query("ALTER TABLE {$tblprefix}$customtable DROP uccid{$coid}date",'SILENT'); 
	$db->query("DELETE FROM {$tblprefix}ucoclass WHERE ucoid='$ucoid'",'SILENT');
	$db->query("DELETE FROM {$tblprefix}ucotypes WHERE ucoid='$ucoid'",'SILENT');
	updatecache('ucotypes');
	@unlink(M_ROOT."./dynamic/cache/ucoclasses".$ucoid.".cac.php");
	adminlog(lang('del_ucotype'));
	amessage('cotypedelfinish',"?entry=ucotypes&action=ucotypesedit");
}
?>
