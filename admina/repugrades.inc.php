<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('repu') || amessage('no_apermission');
load_cache('repugrades');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
$url_type = 'repus';include 'urlsarr.inc.php';
url_nav(lang('repurelate'),$urlsarr,'grade');
if(!submitcheck('brepugradesedit')){
	$itemstr = '';
	tabheader(lang('repugradeadmin'),'repugradesedit','?entry=repugrades','7');
	trcategory(array(lang('id'),lang('repugrade').lang('cname'),lang('rgbase'),lang('available'),lang('ico'),lang('preview')));
	$query = $db->query("SELECT * FROM {$tblprefix}repugrades ORDER BY rgid ASC");
	while($row = $db->fetch_array($query)){
		$rgid = $row['rgid'];
		$validstr = (empty($invalid) && (!isset($oldvalue) || $row['rgbase'] > $oldvalue)) ? 'Y' : '-';
		$oldvalue = $row['rgbase'];
		$invalid = $validstr != '-' ? false : true;
		$thumbstr = $row['thumb'] ? "<img src=\"".view_atmurl($row['thumb'])."\" height=\"18\">" : '';
		echo "<tr class=\"txtcenter txt\"><td class=\"txtC\">$rgid</td>\n".
			"<td class=\"txtC\"><input type=\"text\" size=\"25\" name=\"repugradesnew[$rgid][cname]\" value=\"$row[cname]\"></td>\n".
			"<td class=\"txtC\"><input type=\"text\" size=\"5\" name=\"repugradesnew[$rgid][rgbase]\" value=\"$row[rgbase]\"></td>\n".
			"<td class=\"txtC\">$validstr</td>\n".
			"<td class=\"txtC\">";
		echo singlemodule("repugradesnew[$rgid][thumb]",$row['thumb'],'image');
		echo "</td>\n".
			"<td class=\"txtC\">$thumbstr</td>\n".
			"</tr>\n";
	}
	tabfooter('brepugradesedit',lang('modify'));
	a_guide('repugradesedit');
}else{
	$c_upload = new cls_upload;	
	foreach($repugradesnew as $rgid => $repugrade){
		$repugrade['rgbase'] = intval($repugrade['rgbase']);
		$repugrade['cname'] = trim($repugrade['cname']);
		$sqlstr = "rgbase='$repugrade[rgbase]'";
		$repugrade['cname'] && $sqlstr .= ",cname='$repugrade[cname]'";
		$repugrade['thumb'] = upload_s($repugrade['thumb'],@$repugrades[$rgid]['thumb'],'image');
		if($k = strpos($repugrade['thumb'],'#')) $repugrade['thumb'] = substr($repugrade['thumb'],0,$k);
		$repugrade['thumb'] && $sqlstr .= ",thumb='$repugrade[thumb]'";
		$db->query("UPDATE {$tblprefix}repugrades SET $sqlstr WHERE rgid='$rgid'");
	}
	$c_upload->closure(2, $sid, 'repugrades');
	$c_upload->saveuptotal(1);
	unset($c_upload);
	adminlog(lang('editrepugrade'));
	updatecache('repugrades');
	amessage('repugrademodfin',M_REFERER);
}
?>
