<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cfcommu') || amessage('no_apermission');
load_cache('ucotypes');
if(empty($ucoid) || !($ucotype = $ucotypes[$ucoid])) amessage('choosecotypem');
$uclass = lang($ucotype['cclass']);
$action = empty($action) ? 'ucoclassedit' : $action;
if($action == 'ucoclassedit'){
	if(!submitcheck('bucoclassedit') && !submitcheck('bucoclassadd')){
		tabheader($ucotype['cname'].'-'.lang('coclass_manager'),'ucoclassedit',"?entry=ucoclass&action=ucoclassedit&ucoid=$ucoid",'4');
		trcategory(array(lang('id'),lang('cocname'),lang('commu_type'),lang('order'),lang('delete')));
		$query = $db->query("SELECT * FROM {$tblprefix}ucoclass WHERE ucoid='$ucoid' ORDER BY vieworder,uccid");
		while($row = $db->fetch_array($query)){
			$uccid = $row['uccid'];
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\">$uccid</td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"ucoclassnew[$uccid][title]\" value=\"".mhtmlspecialchars($row['title'])."\" size=\"25\"></td>\n".
				"<td class=\"txtC\">$uclass</td>\n".
				"<td class=\"txtC w50\"><input type=\"text\" name=\"ucoclassnew[$uccid][vieworder]\" value=\"$row[vieworder]\" size=\"4\"></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=ucoclass&action=ucoclassdel&ucoid=$ucoid&uccid=$uccid\">".lang('delete')."</a></td>\n".
				"</tr>";
		}
		tabfooter('bucoclassedit');
		tabheader($ucotype['cname'].'-'.lang('add_coclass'),'ucoclassadd',"?entry=ucoclass&action=ucoclassedit&ucoid=$ucoid");
		trbasic(lang('cocname'),'ucoclassnew[title]','','text');
		tabfooter('bucoclassadd',lang('add'));
		a_guide('ucoclassedit');
	}elseif(submitcheck('bucoclassedit')){
		if(!empty($ucoclassnew)){
			foreach($ucoclassnew as $uccid => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$sqlstr = "vieworder='$v[vieworder]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}ucoclass SET $sqlstr WHERE uccid='$uccid'");
			}
			updatecache('ucotypes');
		}
		adminlog(lang('e_re_class_mlist'));
		amessage('replycoclasseditfinish', "?entry=ucoclass&action=ucoclassedit&ucoid=$ucoid");
	}elseif(submitcheck('bucoclassadd')){
		if(!$ucoclassnew['title']) amessage('datamissing',M_REFERER);
		$db->query("INSERT INTO {$tblprefix}ucoclass SET title='$ucoclassnew[title]',ucoid='$ucoid'");
		updatecache('ucotypes');
		adminlog(lang('add_ucoclass'));
		amessage('coclassaddfinish', "?entry=ucoclass&action=ucoclassedit&ucoid=$ucoid");
	}
}elseif($action == 'ucoclassdel' && $uccid){
	if(!isset($confirm) || $confirm != 'ok'){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=ucoclass&action=ucoclassdel&ucoid=$ucoid&uccid=".$uccid."&confirm=ok>".lang('delete')."</a><br>";
		$message .= lang('giveupclick').">><a href=?entry=ucoclass&action=ucoclassedit&ucoid=$ucoid>".lang('goback')."</a>";
		amessage($message);
	}
	$customtable = $ucotype['cclass'].'s';
	$db->query("UPDATE {$tblprefix}$customtable SET uccid$ucoid='0' WHERE uccid$ucoid='$uccid'");
	$db->query("DELETE FROM {$tblprefix}ucoclass WHERE uccid='$uccid'");
	updatecache('ucotypes');
	adminlog(lang('del_ucoclass'));
	amessage('coclassdelfinish',"?entry=ucoclass&action=ucoclassedit&ucoid=$ucoid");
}

?>
