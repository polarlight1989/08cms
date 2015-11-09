<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('lang') || amessage('no_apermission');
$types = array(
	'email' => lang('Email'),
	'pm' => lang('insitepm'),
);
$url_type = 'langs';include 'urlsarr.inc.php';
url_nav(lang('lanpackmanage'),$urlsarr,'email');

if($action == 'splangsedit'){
	$ftype = empty($ftype) ? '' : $ftype;
	$splangs = fetch_arr($ftype);
	if(!submitcheck('bsplangsedit')) {
/*		$ftypearr = array('' => lang('nolimittype')) + $types;
		$filterbox = lang('filtersplang').'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
		$filterbox .= "<select style=\"vertical-align: middle;\" name=\"ftype\" onchange=\"redirect('?entry=splangs&action=splangsedit&ftype=' + this.options[this.selectedIndex].value);\">";
		foreach($ftypearr as $k => $v){
			$filterbox .= "<option value=\"$k\"".($ftype == $k ? ' selected' : '').">$v</option>";
		}
		$filterbox .= "</select>";
		tabheader($filterbox);
		tabfooter();
*/
		tabheader(lang('spltemadmin'),'','','7');
		trcategory(array(lang('sn'),lang('splangcname'),lang('type'),lang('detail')));
		$sn = 0;
		foreach($splangs as $slid => $splang){
			if(empty($ftype) || $ftype == $splang['type']){
			$sn ++;
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w40\">$sn</td>\n".
				"<td class=\"txtL\">".$splang['cname']."</td>\n".
				"<td class=\"txtC w120\">".$types[$splang['type']]."</td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=splangs&action=splangdetail&slid=$slid\">".lang('edit')."</a></td></tr>\n";
			}
		}
		tabfooter();
		a_guide('splangsedit');
	}
}
elseif($action == 'splangdetail' && $slid){
	$forward = empty($forward) ? M_REFERER : $forward;
	$splang = fetch_one($slid);
	if(!submitcheck('bsplangdetail')){
		tabheader(lang('splangset'),'splangsdetail','?entry=splangs&action=splangdetail&slid='.$slid.'&forward='.urlencode($forward));
		trbasic(lang('splangcname'),'',$splang['cname'],'');
		trbasic(lang('splangtype'),'',$types[$splang['type']],'');
		trbasic(lang('splangcontent'),'splangnew[content]',$splang['content'],'btextarea');
		tabfooter('bsplangdetail');
		a_guide('splangdetail');
	}
	else{
		if(empty($splangnew['content'])) amessage('datamissing',M_REFERER);
		$db->query("UPDATE {$tblprefix}splangs SET content='$splangnew[content]' WHERE slid='$slid'");
		updatecache('splangs');
		adminlog(lang('detaimodifysplang'));
		amessage('splmodfin',$forward);
	}
}
function fetch_arr($type){
	global $db,$tblprefix;
	$items = array();
	$query = $db->query("SELECT * FROM {$tblprefix}splangs ".($type ? "WHERE type='$type'" : '')." ORDER BY vieworder,slid");
	while($item = $db->fetch_array($query)){
		$items[$item['slid']] = $item;
	}
	return $items;
}
function fetch_one($slid){
	global $db,$tblprefix;
	$item = $db->fetch_one("SELECT * FROM {$tblprefix}splangs WHERE slid='$slid'");
	return $item;
}

?>
