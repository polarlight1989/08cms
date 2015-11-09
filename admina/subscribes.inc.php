<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('other') || amessage('no_apermission');
load_cache('channels');
load_cache('catalogs',$sid);
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$page = !empty($page) ? max(1, intval($page)) : 1;
submitcheck('bfilter') && $page = 1;
$viewdetail = empty($viewdetail) ? '' : $viewdetail;
$isatm = empty($isatm) ? '0' : $isatm;
$caid = empty($caid) ? '0' : $caid;
$chid = empty($chid) ? '0' : $chid;
$mname = empty($mname) ? '' : $mname;
$subject = empty($subject) ? '' : $subject;
$indays = empty($indays) ? 0 : max(0,intval($indays));
$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
$filterstr = '';
foreach(array('viewdetail','caid','chid','subject','mname','indays','outdays') as $k){
	$filterstr .= "&$k=".rawurlencode($$k);
}
$wheresql = " WHERE a.sid=$sid AND s.isatm='$isatm'";
if(!empty($caid)){
	$caids = cnsonids($caid,$catalogs);
	$wheresql .= " AND a.caid ".multi_str($caids);
}
if(!empty($chid)) $wheresql .= " AND a.chid='$chid'";
if(!empty($subject)) $wheresql .= " AND a.subject='$subject'";
if(!empty($mname)) $wheresql .= " AND a.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
if(!empty($indays)) $wheresql .= " AND s.createdate>'".($timestamp - 86400 * $indays)."'";
if(!empty($outdays)) $wheresql .= " AND s.createdate<'".($timestamp - 86400 * $outdays)."'";
if(!submitcheck('barcsedit')){
	$caidsarr = array('0' => lang('all_catalog')) + caidsarr($catalogs);
	$chidsarr = array('0' => lang('all_channel')) + chidsarr();
	$isatmarr = array('0' => lang('archive'),'1' => lang('attachment'));
	tabheader(lang('filsubrec').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'archivesedit',"?entry=subscribes$param_suffix$forwardstr");
	trbasic(lang('subscribetype'),'',makeradio('isatm',$isatmarr,$isatm),'');
	echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
	trbasic(lang('be_catalog'),'caid',makeoption($caidsarr,$caid),'select');
	trbasic(lang('achannel'),'chid',makeoption($chidsarr,$chid),'select');
	trbasic(lang('search_member'),'mname',$mname,'text',lang('agsearchkey'));
	trbasic(lang('archive_title'),'subject',$subject);
	trrange(lang('contpurchdat'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
	echo "</tbody>";
	tabfooter();

	$pagetmp = $page;
	do{
		$query = $db->query("SELECT s.*,a.* FROM {$tblprefix}subscribes s LEFT JOIN {$tblprefix}archives a ON a.aid=s.aid $wheresql ORDER BY s.id DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
		$pagetmp--;
	} while(!$db->num_rows($query) && $pagetmp);
	$itemstr = '';
	while($item = $db->fetch_array($query)){
		$id = $item['id'];
		$item['arcurl'] = view_arcurl($item);
		$item['createdate'] = date("$dateformat", $item['createdate']);
		$itemstr .= "<tr><td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$id]\" value=\"$id\"></td>\n".
			"<td class=\"txtL\"><a href=\"$item[arcurl]\" target=\"_blank\">".mhtmlspecialchars($item['subject'])."</a></td>\n".
			"<td class=\"txtC w80\">".$catalogs[$item['caid']]['title']."</td>\n".
			"<td class=\"txtC w80\">".($item['isatm'] ? 'Y' : '-')."</td>\n".
			"<td class=\"txtC w80\">$item[mname]</td>\n".
			"<td class=\"txtC w80\">$item[cridstr]</td>\n".
			"<td class=\"txtC w70\">$item[createdate]</td></tr>\n";
	}
	$itemcount = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}subscribes s LEFT JOIN {$tblprefix}archives a ON a.aid=s.aid $wheresql");
	$multi = multi($itemcount, $atpp, $page, "?entry=subscribes$param_suffix$filterstr");

	tabheader(lang('subsrecolis')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
	trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('title'),lang('catalog'),lang('attachment'),lang('member'),lang('currency'),lang('purchasedate')));
	echo $itemstr;
	tabfooter();
	echo $multi;
	echo "<input class=\"button\" type=\"submit\" name=\"barcsedit\" value=\"".lang('delete')."\"></form>";
}else{
	if(empty($selectid) && empty($select_all)){
		amessage('confirmselect subscribe content');
	}
	if(!empty($select_all)){
		$selectid = array();
		$npage = empty($npage) ? 1 : $npage;
		if(empty($pages)){
			$itemcount = $db->result_one("SELECT COUNT(*) FROM {$tblprefix}subscribes s LEFT JOIN {$tblprefix}archives a ON a.aid=s.aid $wheresql");
			$pages = @ceil($itemcount / $atpp);
		}
		if($npage <= $pages){
			$fromstr = empty($fromid) ? "" : "s.id<$fromid";
			$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
			$query = $db->query("SELECT s.id FROM {$tblprefix}subscribes s LEFT JOIN {$tblprefix}archives a ON a.aid=s.aid $nwheresql ORDER BY s.id DESC LIMIT 0,$atpp");
			while($item = $db->fetch_array($query)){
				$selectid[] = $item['id'];
			}
		}
	}
	$selectid && $db->query("DELETE FROM {$tblprefix}subscribes WHERE id ".multi_str($selectid),'UNBUFFERED');
	if(!empty($select_all)){
		$npage ++;
		if($npage <= $pages){
			$fromid = min($selectid);
			$transtr = '';
			$transtr .= "&select_all=1";
			$transtr .= "&pages=$pages";
			$transtr .= "&npage=$npage";
			$transtr .= "&barcsedit=1";
			$transtr .= "&fromid=$fromid";
			amessage('operating',"?entry=subscribes$param_suffix$transtr&forward=".urlencode($forward),$pages,$npage,"<a href=\"?entry=userfiles&action=userfilesedit&page=$page$filterstr\">",'</a>');
		}
	}
	adminlog(lang('subconadm'),lang('subcoliadmoper'));
	amessage('subdelsuc',"?entry=subscribes$param_suffix&page=$page$filterstr");
}
?>