<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
!checkapermission(1002) && amessage('no_apermission');
if($sid && $sid_self) amessage('msiteadmitem');
$action = empty($action) ? 'rcatalogsedit' : $action;
$url_type = 'cucata';include M_ROOT.'./include/urlsarr.inc.php';
if($action == 'rcatalogsedit'){
	if(!submitcheck('brcatalogsedit') && !submitcheck('brcatalogadd')){
		if(!$sid) url_nav(lang('docinterconfig'),$urlsarr,'rc');
		tabheader(lang('reply_coclass_manager'),'rcatalogsedit','?entry=cucatalogs&action=rcatalogsedit','4');
		trcategory(array(lang('id'),lang('cocname'),lang('order'),lang('delete')));
		$query = $db->query("SELECT * FROM {$tblprefix}cucatalogs WHERE cu='1' ORDER BY vieworder,caid");
		while($row = $db->fetch_array($query)){
			$caid = $row['caid'];
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\">$caid</td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"rcatalogsnew[$caid][title]\" value=\"".mhtmlspecialchars($row['title'])."\" size=\"25\"></td>\n".
				"<td class=\"txtC w50\"><input type=\"text\" name=\"rcatalogsnew[$caid][vieworder]\" value=\"$row[vieworder]\" size=\"4\"></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=cucatalogs&action=rcatalogdel&caid=$caid\">".lang('delete')."</a></td>\n".
				"</tr>";
		}
		tabfooter('brcatalogsedit');
		tabheader(lang('add_msg_coclass'),'rcatalogadd','?entry=cucatalogs&action=rcatalogsedit');
		trbasic(lang('cocname'),'rcatalognew[title]','','text');
		tabfooter('brcatalogadd',lang('add'));
		a_guide('rcatalogsedit');
	}elseif(submitcheck('brcatalogsedit')){
		if(!empty($rcatalogsnew)){
			foreach($rcatalogsnew as $caid => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$sqlstr = "vieworder='$v[vieworder]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}cucatalogs SET $sqlstr WHERE caid='$caid'");
			}
			updatecache('rcatalogs');
		}
		adminlog(lang('e_re_class_mlist'));
		amessage('replycoclasseditfinish', '?entry=cucatalogs&action=rcatalogsedit');
	}elseif(submitcheck('brcatalogadd')){
		if(!$rcatalognew['title']) amessage('datamissing',M_REFERER);
		$db->query("INSERT INTO {$tblprefix}cucatalogs SET title='$rcatalognew[title]',cu='1'");
		updatecache('rcatalogs');
		adminlog(lang('add_reply_class'));
		amessage('replycoclassaddfinish', '?entry=cucatalogs&action=rcatalogsedit');
	}
}elseif($action == 'rcatalogdel' && $caid){
	if(!isset($confirm) || $confirm != 'ok'){
		$message = lang('del_alert')."<br><br>";
		$message .= lang('confirmclick')."[<a href=?entry=cucatalogs&action=rcatalogdel&caid=".$caid."&confirm=ok>".lang('delete')."</a>]<br>";
		$message .= lang('giveupclick')."[<a href=?entry=cucatalogs&action=rcatalogsedit>".lang('goback')."</a>]";
		amessage($message);
	}
	$db->query("UPDATE {$tblprefix}replys SET arcaid='0' WHERE arcaid='$caid'");
	$db->query("UPDATE {$tblprefix}replys SET urcaid='0' WHERE urcaid='$caid'");
	$db->query("DELETE FROM {$tblprefix}cucatalogs WHERE caid='$caid'");
	updatecache('rcatalogs');
	adminlog(lang('del_reply_class'));
	amessage('replycoclassdelfinish', '?entry=cucatalogs&action=rcatalogsedit');
}
function view_cucatalogurls(){
	global $action;
	tabheader(lang('co_class_manager'),'','',4);
	echo "<tr class=\"txt\">\n";
	echo "<td class=\"txtL w25B\">".(in_str('rcatalog',$action) ? "<b>".lang('reply_class_manager')."</b>" : "<a href=\"?entry=cucatalogs&action=rcatalogsedit\">".lang('reply_class_manager')."</a>")."</td>\n";
	echo "<td class=\"txtL w25B\"></td>\n";
	echo "<td class=\"txtL w25B\"></td>\n";
	echo "<td class=\"txtL w25B\"></td>\n";
	echo "</tr>\n";
	tabfooter();
}

?>
