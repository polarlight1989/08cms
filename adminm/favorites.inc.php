<?
!defined('M_COM') && exit('No Permission');
include_once M_ROOT."./include/arcedit.cls.php";
load_cache('acatalogs,channels,currencys');
$catalogs = &$acatalogs;
$forward = empty($forward) ? M_REFERER : $forward;
$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;
$keyword = empty($keyword) ? '' : $keyword;
$filterstr = '';
foreach(array('keyword') as $k){
	$filterstr .= "&$k=".rawurlencode($$k);
}

$wheresql = "WHERE f.mid='$memberid'";
$keyword && $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
if(!submitcheck('barcsedit')){
	echo form_str($action.'arcsedit',"?action=favorites");
	tabheader_e();
	echo "<tr><td class=\"item2\">";
	echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" style=\"vertical-align: middle;\">&nbsp; ";
	echo strbutton('bfilter','filter0').'</td></tr>';
	tabfooter();

	$pagetmp = $page;
	do{
		$query = $db->query("SELECT f.*,a.* FROM {$tblprefix}favorites f LEFT JOIN {$tblprefix}archives a ON a.aid=f.aid $wheresql ORDER BY f.aid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
		$pagetmp--;
	} while(!$db->num_rows($query) && $pagetmp);
	$itemstr = '';
	while($item = $db->fetch_array($query)){
		$aid = $item['aid'];
		$item['arcurl'] = view_arcurl($item);
		$castr = empty($catalogs[$item['caid']]) ? lang('nocata') : $catalogs[$item['caid']]['title'];
		$item['createdate'] = date("$dateformat", $item['createdate']);
		$itemstr .= "<tr><td align=\"center\" class=\"item1\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$aid]\" value=\"$aid\"></td>\n".
			"<td class=\"item2\"><a href=\"$item[arcurl]\" target=\"_blank\">".mhtmlspecialchars($item['subject'])."</a></td>\n".
			"<td class=\"item\" width=\"80\">$castr</td>\n".
			"<td class=\"item\" width=\"80\">$item[mname]</td>\n".
			"<td class=\"item\" width=\"70\">$item[createdate]</td></tr>\n";
	}
	$counts = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}favorites f LEFT JOIN {$tblprefix}archives a ON a.aid=f.aid $wheresql");
	$multi = multi($counts,$mrowpp,$page,"?action=favorites$filterstr");

	tabheader(lang('favoritearchivelist'),'','',8);
	trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),lang('catalog'),lang('author'),lang('favoritedate')));
	echo $itemstr;
	tabfooter();
	echo $multi;
	echo "<input class=\"button\" type=\"submit\" name=\"barcsedit\" value=\"".lang('delete')."\"></form>";
}else{
	empty($selectid) && mcmessage('selectfavoritearc',$forward);
	$query = $db->query("SELECT * FROM {$tblprefix}favorites WHERE mid=$memberid AND aid ".multi_str($selectid)." ORDER BY aid DESC");
	while($item = $db->fetch_array($query)){
		$items[$item['aid']] = $item;
	}
	$aedit = new cls_arcedit;
	foreach($items as $item){
		$aedit->set_aid($item['aid']);
		$aedit->arc_nums('favorites',-1,1);
		$aedit->init();
		$curuser->basedeal('favorite',0,1);
	}
	$curuser->updatedb();
	$db->query("DELETE FROM {$tblprefix}favorites WHERE aid ".multi_str(array_keys($items)),'UNBUFFERED');
	unset($aedit);
	if(!empty($select_all)){
		$npage ++;
		if($npage <= $pages){
			$fromid = min(array_keys($items));
			$transtr = '';
			$transtr .= "&select_all=1";
			$transtr .= "&pages=$pages";
			$transtr .= "&npage=$npage";
			$transtr .= "&barcsedit=1";
			$transtr .= "&fromid=$fromid";
			mcmessage('operating',"?action=favorites$transtr&forward=".urlencode($forward),$pages,$npage,"<a href=\"$forward\">",'</a>');
		}
	}
	mcmessage('favoritedelsucceed',"?action=comments&page=$page$filterstr");
}
?>