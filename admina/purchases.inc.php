<?
//因为订单不区子站，购物记录也不能区分子站
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('orders') || amessage('no_apermission');
load_cache('channels,currencys');
load_cache('catalogs',$sid);
$url_type = 'order';include 'urlsarr.inc.php';

if($action == 'purchasesedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$caid = empty($caid) ? '0' : $caid;
	$chid = empty($chid) ? '0' : $chid;
	$viewdetail = empty($viewdetail) ? '' : $viewdetail;
	$subject = empty($subject) ? '' : $subject;
	$mname = empty($mname) ? '' : $mname;
	$checked = isset($checked) ? $checked : '-1';
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$filterstr = '';
	foreach(array('viewdetail','caid','chid','checked','subject','mname','indays','outdays') as $k){
		$filterstr .= "&$k=".rawurlencode($$k);
	}
	$wheresql = "WHERE a.sid=$sid";
	if(!empty($caid)){
		$caids = cnsonids($caid,$catalogs);
		$wheresql .= " AND a.caid ".multi_str($caids);
	}
	$chid && $wheresql .= " AND a.chid='$chid'";
	$checked != '-1' && $wheresql .= " AND cu.oid".($checked ? '>' : '=')."'0'";
	$subject && $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($subject,'%_'))."%'";
	$mname && $wheresql .= " AND cu.mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
	$indays && $wheresql .= " AND cu.createdate>'".($timestamp - 86400 * $indays)."'";
	$outdays && $wheresql .= " AND cu.createdate<'".($timestamp - 86400 * $outdays)."'";
	if(!submitcheck('barcsedit')){
		url_nav(lang('goodsorder'),$urlsarr,'purchase');

		$caidsarr = array('0' => lang('all_catalog')) + caidsarr($catalogs);
		$chidsarr = array('0' => lang('all_channel')) + chidsarr();
		$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nopurgoods'),'1' => lang('purcgood'));
		tabheader(lang('filtgoods').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'purchasesedit',"?entry=purchases&action=purchasesedit$param_suffix&page=$page");
		echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trbasic(lang('ispurchased'),'',makeradio('checked',$checkedarr,$checked),'');
		trbasic(lang('be_catalog'),'caid',makeoption($caidsarr,$caid),'select');
		trbasic(lang('belongchannel'),'chid',makeoption($chidsarr,$chid),'select');
		trbasic(lang('search_arc_title'),'subject',$subject,'text',lang('agsearchkey'));
		trbasic(lang('search_member'),'mname',$mname,'text',lang('agsearchkey'));
		trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
		echo "</tbody>";
		tabfooter();
	
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject FROM {$tblprefix}purchases cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $wheresql ORDER BY cu.cid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)){
			$cid = $item['cid'];
			$item['arcurl'] = view_arcurl($item);
			$item['catalog'] = @$catalogs[$item['caid']]['title'];
			$item['createdate'] = date("$dateformat", $item['ucreatedate']);
			$item['orderstr'] = $item['oid'] ? "<a href=\"?entry=orders&action=orderdetail&oid=$item[oid]$param_suffix\">".lang('look')."</a>" : '-';
			$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$cid]\" value=\"$cid\"></td>\n".
				"<td class=\"txtL\"><a href=\"$item[arcurl]\" target=\"_blank\">".mhtmlspecialchars($item['asubject'])."</a></td>\n".
				"<td class=\"txtC w80\">$item[catalog]</td>\n".
				"<td class=\"txtC w80\">$item[mname]</td>\n".
				"<td class=\"txtC w40\">$item[nums]</td>\n".
				"<td class=\"txtC w40\">$item[orderstr]</td>\n".
				"<td class=\"txtC w80\">$item[createdate]</td></tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}purchases cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=purchases&action=purchasesedit$param_suffix$filterstr");
	
		tabheader(lang('goodslist')."&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',9);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">".lang('del'),lang('goodscname'),lang('catalog'),lang('purchasemember'),lang('amount'),lang('orders'),lang('add_time')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"barcsedit\" value=\"".lang('submit')."\">";
	}else{
		if(empty($selectid) && empty($select_all)) amessage('selectgoods',M_REFERER);
		if(!empty($select_all)){
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) FROM {$tblprefix}purchases cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "cid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT cid FROM {$tblprefix}purchases cu LEFT JOIN {$tblprefix}archives a ON (a.aid=cu.aid) $nwheresql ORDER BY cu.cid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)){
					$selectid[] = $item['cid'];
				}
			}
		}
		$db->query("DELETE FROM {$tblprefix}purchases WHERE cid ".multi_str($selectid)." AND oid=0",'SILENT');
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
				amessage('operating',"?entry=purchases&action=purchasesedit$param_suffix&page=$page$filterstr$transtr",$pages,$npage,"<a href=\"?entry=purchases&action=purchasesedit$param_suffix&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('gooliadm'),lang('goolisdmope'));
		amessage('goolisopefin',"?entry=purchases&action=purchasesedit$param_suffix&page=$page$filterstr");
	}
}
?>