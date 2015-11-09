<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
load_cache('vcatalogs');
$url_type = 'vote';include 'urlsarr.inc.php';
url_nav(lang('voteadmin'),$urlsarr,'vcata');
if($action == 'vcatalogsedit'){
	if(!submitcheck('bvcatalogsedit') && !submitcheck('bvcatalogadd')){
		tabheader(lang('votcocman'),'vcatalogsedit','?entry=vcatalogs&action=vcatalogsedit','6');
		trcategory(array(lang('sn'),lang('cocname'),lang('order'),lang('delete')));
		$k = 0;
		foreach($vcatalogs as $caid => $vcatalog) {
			$k ++;
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\">$k</td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"vcatalogsnew[$caid][title]\" value=\"".mhtmlspecialchars($vcatalog['title'])."\" size=\"25\" maxlength=\"30\"></td>\n".
				"<td class=\"txtC w50\"><input type=\"text\" name=\"vcatalogsnew[$caid][vieworder]\" value=\"$vcatalog[vieworder]\" size=\"2\"></td>\n".
				"<td class=\"txtC w50\"><a href=\"?entry=vcatalogs&action=vcatalogdelete&caid=$caid\">[".lang('delete')."]</a></td>\n".
				"</tr>";
		}
		tabfooter('bvcatalogsedit');
		tabheader(lang('addvotcoc'),'vcatalogadd','?entry=vcatalogs&action=vcatalogsedit');
		trbasic(lang('cocname'),'vcatalogadd[title]','','text');
		tabfooter('bvcatalogadd',lang('add'));
		a_guide('vcatalogsedit');
	}elseif(submitcheck('bvcatalogsedit')){
		if(!empty($vcatalogsnew)){
			foreach($vcatalogsnew as $caid => $vcatalognew){
				$vcatalognew['title'] = $vcatalognew['title'] ? $vcatalognew['title'] : $vcatalogs[$caid]['title'];
				$vcatalognew['vieworder'] = max(0,intval($vcatalognew['vieworder']));
				if(($vcatalognew['title'] != $vcatalogs[$caid]['title']) || ($vcatalognew['vieworder'] != $vcatalogs[$caid]['vieworder'])){
					$db->query("UPDATE {$tblprefix}vcatalogs SET 
								title='$vcatalognew[title]', 
								vieworder='$vcatalognew[vieworder]' 
								WHERE caid='$caid'
								");
				}
			}
			updatecache('vcatalogs');
		}
		amessage('cocledifin', '?entry=vcatalogs&action=vcatalogsedit');
	}elseif(submitcheck('bvcatalogadd')){
		empty($vcatalogadd['title']) && amessage('datamissing','?entry=vcatalogs&action=vcatalogsedit');
		$db->query("INSERT INTO {$tblprefix}vcatalogs SET title='$vcatalogadd[title]'");
		updatecache('vcatalogs');
		amessage('votcocaddfin', '?entry=vcatalogs&action=vcatalogsedit');
	}
}elseif($action == 'vcatalogdelete' && $caid) {
	if(!isset($confirm) || $confirm != 'ok') {
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick').">><a href=?entry=vcatalogs&action=vcatalogdelete&caid=".$caid."&confirm=ok>".lang('delete')."</a><br>";
		$message .= lang('giveupclick').">><a href=?entry=vcatalogs&action=vcatalogsedit>".lang('goback')."</a>";
		amessage($message);
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}votes WHERE caid='$caid'")) amessage('coclwitvotcandel', '?entry=vcatalogs&action=vcatalogsedit');
	$db->query("DELETE FROM {$tblprefix}vcatalogs WHERE caid='$caid'");
	updatecache('vcatalogs');
	amessage('cocdelefini', '?entry=vcatalogs&action=vcatalogsedit');
}

?>
