<?php
!defined('M_COM') && exit('No Permission');
load_cache('acatalogs,channels');
$catalogs = &$acatalogs;
$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;
$type = empty($type) ? '' : $type;
isset($table) || $table = -1;
$aids = empty($aids) ? '' : $aids;

$wheresql = "WHERE mid='".$curuser->info['mid']."'";
if(!empty($type)){
	$wheresql .= " AND type='$type'";
}
if(!empty($aids)){
	$aidsarr = array_filter(explode(',',$aids));
	$wheresql .= " AND aid ".multi_str($aidsarr);
}
$table != -1 && $wheresql .= ($wheresql ? " AND " : "")."tid='$table'";

$filterstr = '';
foreach(array('aids','type','table') as $k)$filterstr .= "&$k=".urlencode($$k);
if(!submitcheck('buserfilesedit')){
	//同include/upload.cls.php中closure函数的$tids变量对应
	$tabsarr = array('-1' => lang('alltype'),1 => lang('archive'), 2 => lang('freeinfo'), 3 => lang('member'), 4 => lang('marchive'), 16 => lang('comment'), 17 => lang('reply'), 18 => lang('offer'), 32 => lang('mcomment'), 33 => lang('mreply'), '0' => lang('other'));
	$linkarr = array(1 => 'archive&aid=', 2 => 'farchive&aid=', 3 => 'memberinfo&mid=', 4 => 'marchive&maid=', 16 => 'comment&cid=', 17 => 'reply&cid=', 18 => 'offer&cid=', 32 => 'mcomment&cid=', 33 => 'mreply&cid=');
	$typearr = array('0' => lang('alltype'),'image' => lang('image'),'flash' => lang('flash'),'media' => lang('media'),'file' => lang('other'),);
	echo form_str($action.'arcsedit',"?action=userfiles");
	tabheader_e();
	echo "<tr><td class=\"item2\">";
	echo lang('aidstxt')."&nbsp; <input class=\"text\" name=\"aids\" type=\"text\" value=\"$aids\" style=\"vertical-align: middle;\">&nbsp; ";
	echo "<select style=\"vertical-align: middle;\" name=\"type\">".makeoption($typearr,$type)."</select>&nbsp; ";
	echo "<select style=\"vertical-align: middle;\" name=\"table\">".makeoption($tabsarr,$table)."</select>&nbsp; ";
	echo strbutton('bfilter','filter0').'</td></tr>';
	tabfooter();

	$pagetmp = $page;
	do{
		$query = $db->query("SELECT * FROM {$tblprefix}userfiles $wheresql ORDER BY ufid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
		$pagetmp--;
	} while(!$db->num_rows($query) && $pagetmp);
	$itemstr = '';
	while($item = $db->fetch_array($query)) {
		$item['createdate'] = date("$dateformat", $item['createdate']);
		$item['preview'] = ($item['type'] == 'image') ? "<a href=\"".view_atmurl($item['url'])."\" target=\"_blank\">".lang('preview')."</a>" : "-";
		$item['type'] = $typearr[$item['type']];
		$item['thumbedstr'] = $item['thumbed'] ? 'Y' : '-';
		$item['size'] = ceil($item['size'] / 1024);
		$item['source'] = $item['aid'] && $item['tid'] ? "<a href=\"?action=".$linkarr[$item['tid']]."$item[aid]\" target=\"_blank\" onclick=\"return floatwin('open_editbyatt',this)\">".lang('look')."</a>" : "-";
		$itemstr .= "<tr><td align=\"center\" class=\"item1\" width=\"40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid['$item[ufid]']\" value=\"$item[ufid]\">\n".
			"<td class=\"item2\">$item[filename]</td>\n".
			"<td class=\"item\" width=\"40\">$item[type]</td>\n".
			"<td class=\"item\" width=\"60\">$item[size]</td>\n".
			"<td class=\"item\" width=\"40\">$item[preview]</td>\n".
			"<td class=\"item\" width=\"50\">$item[thumbedstr]</td>\n".
			"<td class=\"item\" width=\"78\">$item[createdate]</td>\n".
			"<td class=\"item\" width=\"40\">$item[source]</td></tr>\n";
	}
	$itemcount = $db->result_one("SELECT count(*) FROM {$tblprefix}userfiles $wheresql");
	$multi = multi($itemcount, $mrowpp, $page, "?action=userfiles$filterstr");

	tabheader(lang('attachmentlist'),'','',9);
	trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">".lang('del'),array(lang('cname'),'left'),lang('type'),lang('size_k'),lang('preview'),lang('thumb'),lang('uploaddate'),lang('source')));
	echo $itemstr;
	tabfooter();
	echo $multi;
	echo "<br><input class=\"button\" type=\"submit\" name=\"buserfilesedit\" value=\"".lang('submit')."\"></form>";
}else{
	empty($selectid) && mcmessage('selectarchive',"?action=userfiles&page=$page$filterstr");
	$items = array();
	$query = $db->query("SELECT * FROM {$tblprefix}userfiles WHERE ufid ".multi_str($selectid)." ORDER BY ufid");
	while($item = $db->fetch_array($query)){
		$items[$item['ufid']] = $item;
	}

	foreach($items as $item){
		if($item['thumbed']){
			$curuser->updateuptotal(ceil(@filesize(local_file($item['url']).'.s.jpg') / 1024),'reduce');
			@unlink(local_file($item['url']).'.s.jpg');
		}
		$curuser->updateuptotal(ceil($item['size'] / 1024),'reduce');
		@unlink(local_file($item['url']));
	}
	$curuser->updatedb();
	$db->query("DELETE FROM {$tblprefix}userfiles WHERE ufid ".multi_str(array_keys($items)),'UNBUFFERED');
	unset($actuser);

	if(!empty($select_all)){
		$npage ++;
		if($npage <= $pages){
			$fromid = min(array_keys($items));
			$transtr = '';
			$transtr .= "&select_all=1";
			$transtr .= "&pages=$pages";
			$transtr .= "&npage=$npage";
			$transtr .= "&buserfilesedit=1";
			$transtr .= "&fromid=$fromid";
			mcmessage('operating'."<br>
					".lang('all')." $pages ".lang('page0')."，".lang('dealing')." $npage ".lang('page0')."<br><br>
					<a href=\"?action=userfiles&page=$page$filterstr\">>>".lang('pause')."</a>",
					"?action=userfiles&page=$page$filterstr$transtr",
					500);
		}
	}
	mcmessage('archiveoperatefinish',"?action=userfiles&page=$page$filterstr",500);

}
?>