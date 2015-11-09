<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
if($action == 'userfilesedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$type = empty($type) ? '' : $type;
	isset($table) || $table = -1;
	$aids = empty($aids) ? '' : $aids;
	
	$wheresql = '';
	if(!empty($type)){
		$wheresql .= ($wheresql ? " AND " : "")."type='$type'";
	}
	if(!empty($aids)){
		$aidsarr = array_filter(explode(',',$aids));
		$wheresql .= ($wheresql ? " AND " : "")."aid ".multi_str($aidsarr);
	}
	$table != -1 && $wheresql .= ($wheresql ? " AND " : "")."tid='$table'";
	
	$filterstr = '';
	foreach(array('aids','type','table') as $k)$filterstr .= "&$k=".urlencode($$k);

	$wheresql = $wheresql ? ("WHERE $wheresql") : "";
	if(!submitcheck('buserfilesedit')){
		//同include/upload.cls.php中closure函数的$tids变量对应
		$tabsarr = array('-1' => lang('alltype'),1 => lang('archive'), 2 => lang('freeinfo'), 3 => lang('member'), 4 => lang('marchive'), 16 => lang('comment'), 17 => lang('reply'), 18 => lang('offer'), 32 => lang('mcomment'), 33 => lang('mreply'), '0' => lang('other'));
		$linkarr = array(1 => 'archive&action=archivedetail&aid=', 2 => 'farchive&action=farchivedetail&aid=', 3 => 'member&action=memberdetail&mid=', 4 => 'marchives&action=marchivedetail&maid=', 16 => 'comments&action=commentdetail&cid=', 17 => 'replys&action=replydetail&cid=', 18 => 'offers&action=offerdetail&cid=', 32 => 'mcomments&action=mcommentdetail&cid=', 33 => 'mreplys&action=mreplydetail&cid=');
		$typearr = array('0' => lang('alltype'),'image' => lang('image'),'flash' => lang('flash'),'media' => lang('media'),'file' => lang('other'),);
		echo form_str($action.'arcsedit',"?entry=$entry&action=$action");
		tabheader_e();
		echo "<tr><td class=\"txtL\">";
		echo lang('aidstxt')."&nbsp; <input class=\"text\" name=\"aids\" type=\"text\" value=\"$aids\" style=\"vertical-align: middle;\">&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"type\">".makeoption($typearr,$type)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"table\">".makeoption($tabsarr,$table)."</select>&nbsp; ";
		echo strbutton('bfilter','filter0').'</td></tr>';
		tabfooter();
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * FROM {$tblprefix}userfiles $wheresql ORDER BY ufid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)) {
			$item['createdate'] = date("$dateformat", $item['createdate']);
			$item['preview'] = ($item['type'] == 'image') ? "<a href=\"".view_atmurl($item['url'])."\" target=\"_blank\">".lang('preview')."</a>" : "-";
			$item['type'] = $typearr[$item['type']];
			$item['thumbedstr'] = $item['thumbed'] ? 'Y' : '-';
			$item['size'] = ceil($item['size'] / 1024);
			$item['source'] = $item['aid'] ? "<a href=\"?entry=".$linkarr[$item['tid']]."$item[aid]\" target=\"_blank\" onclick=\"return floatwin('open_editbyatt',this)\">".lang('look')."</a>" : "-";
			$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid['$item[ufid]']\" value=\"$item[ufid]\">\n".
				"<td class=\"txtL\">$item[filename]</td>\n".
				"<td class=\"txtC w40\">$item[type]</td>\n".
				"<td class=\"txtC w50\">$item[size]</td>\n".
				"<td class=\"txtC w40\">$item[preview]</td>\n".
				"<td class=\"txtC w40\">$item[thumbedstr]</td>\n".
				"<td class=\"txtC w80\">$item[mname]</td>\n".
				"<td class=\"txtC w100\">$item[createdate]</td>\n".
				"<td class=\"txtC w40\">$item[source]</td></tr>\n";
		}
		$itemcount = $db->result_one("SELECT count(*) FROM {$tblprefix}userfiles $wheresql");
		$multi = multi($itemcount, $atpp, $page, "?entry=userfiles&action=userfilesedit$filterstr");

		tabheader(lang('attalistdeloper')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">".lang('del'),array(lang('cname'),'txtL'),lang('type'),lang('sizek'),lang('preview'),lang('thumb'),lang('member'),lang('uploaddate'),lang('source')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<br><input class=\"button\" type=\"submit\" name=\"buserfilesedit\" value=\"".lang('submit')."\"></form>";
	}else{
		if(empty($selectid) && empty($select_all)){
			amessage('selectarchive',"?entry=userfiles&action=userfilesedit&page=$page$filterstr");
		}
		$items = array();
		if(!empty($select_all)){
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$itemcount = $db->result_one("SELECT count(*) FROM {$tblprefix}userfiles $wheresql");
				$pages = @ceil($itemcount / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "ufid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT * FROM {$tblprefix}userfiles $nwheresql ORDER BY ufid DESC LIMIT 0,$atpp");
			}
		}else{
			$query = $db->query("SELECT * FROM {$tblprefix}userfiles WHERE ufid ".multi_str($selectid)." ORDER BY ufid");
		}
		while($item = $db->fetch_array($query)){
			$items[$item['ufid']] = $item;
		}

		$actuser = new cls_userinfo;
		foreach($items as $item){
			$actuser->activeuser($item['mid']);
			if($item['thumbed']){
				$actuser->updateuptotal(ceil(@filesize(local_file($item['url']).'.s.jpg') / 1024),'reduce');
				@unlink(local_file($item['url']).'.s.jpg');
			}
			$actuser->updateuptotal(ceil($item['size'] / 1024),'reduce','1');
			@unlink(local_file($item['url']));
			$actuser->init();
		}
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
				amessage('operating',"?entry=userfiles&action=userfilesedit&page=$page$filterstr$transtr",$pages,$npage,"<a href=\"?entry=userfiles&action=userfilesedit&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('uploadattadm'),lang('attalistdeloper'));
		amessage('attopefin',"?entry=userfiles&action=userfilesedit&page=$page$filterstr",500);
	
	}
}
?>