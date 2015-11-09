<?
//两种模式：会员管理自已发布的所有评论，管理员针对某一个文档的所有评论的管理
!defined('M_COM') && exit('No Permission');
load_cache('channels,acatalogs,currencys,commus');
$catalogs = &$acatalogs;
$aid = empty($aid) ? 0 : max(0,intval($aid));
$page = !empty($page) ? max(1, intval($page)) : 1;
$forward = empty($forward) ? M_URI : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
if(!$aid){//会员管理自已发布的所有评论
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? '' : $viewdetail;
	$caid = empty($caid) ? '0' : $caid;
	$chid = empty($chid) ? '0' : $chid;
	$subject = empty($subject) ? '' : $subject;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));
	$filterstr = '';
	foreach(array('viewdetail','caid','chid','subject','indays','outdays') as $k){
		$filterstr .= "&$k=".rawurlencode($$k);
	}

	$fromsql = "FROM {$tblprefix}reports cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";
	$wheresql = "WHERE cu.mid='$memberid'";
	if(!empty($caid)){
		$caids = cnsonids($caid,$catalogs);
		$wheresql .= " AND a.caid ".multi_str($caids);
	}
	if($chid) $wheresql .= " AND a.chid='$chid'";
	if($subject) $wheresql .= " AND a.subject LIKE '%".str_replace(array(' ','*'),'%',addcslashes($subject,'%_'))."%'";
 	if($indays) $wheresql .= " AND cu.updatedate>'".($timestamp - 86400 * $indays)."'";
	if($outdays) $wheresql .= " AND cu.updatedate<'".($timestamp - 86400 * $outdays)."'";
	if(!submitcheck('breportsedit')){
		$caidsarr = array('0' => lang('allcatalog')) + caidsarr($catalogs);
		$chidsarr = array('0' => lang('allchannel')) + chidsarr();
		tabheader(lang('filtersetting').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'arcsedit',"?action=reports&page=$page");
		echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trbasic(lang('belongcatalog'),'caid',makeoption($caidsarr,$caid),'select');
		trbasic(lang('archivechannel'),'chid',makeoption($chidsarr,$chid),'select');
		trbasic(lang('archivetitle'),'subject',$subject);
		trrange(lang('reportupdatedate'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('daybefore').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('dayin'),5));
		echo "</tbody>";
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.updatedate DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)){
			$aid = $item['aid'];
			$cid = $item['cid'];
			$arcurl = view_arcurl($item);
			$updatedate = date("$dateformat", $item['updatedate']);
			$catalogstr = @$catalogs[$item['caid']]['title'];
			$channelstr = @$channels[$item['chid']]['cname'];
			$editstr = "<a href=\"?action=report&aid=$item[aid]&cid=$cid$forwardstr\">".lang('edit')."</a>";
			$itemstr .= "<tr><td align=\"center\" class=\"item1\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$cid]\" value=\"$cid\"></td>\n".
				"<td class=\"item2\"><a href=\"$arcurl\" target=\"_blank\">".mhtmlspecialchars($item['asubject'])."</td>\n".
				"<td align=\"center\" class=\"item1\" width=\"80\">$catalogstr</td>\n".
				"<td align=\"center\" class=\"item2\" width=\"80\">$channelstr</td>\n".
				"<td align=\"center\" class=\"item1\" width=\"70\">$updatedate</td>\n".
				"<td align=\"center\" class=\"item2\" width=\"30\">$editstr</td></tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=reports$filterstr");

		tabheader(lang('reportlist'),'','',8);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('reportobject'),lang('catalog'),lang('channel'),lang('updatedate'),lang('edit')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"breportsedit\" value=\"".lang('delete')."\"></form>";
	}else{
		if(empty($selectid) && empty($select_all)) mcmessage('selectreport',"?action=reports&page=$page$filterstr");
		$query = $db->query("SELECT * FROM {$tblprefix}reports WHERE cid ".multi_str($selectid));
		while($row = $db->fetch_array($query)){
			$db->query("UPDATE {$tblprefix}archives_sub SET reports=GREATEST(0,reports-1) WHERE aid='$row[aid]'");
		}
		unset($row);
		$db->query("DELETE FROM {$tblprefix}reports WHERE cid ".multi_str($selectid),'UNBUFFERED');
		mcmessage('reportdelsucceed',"?action=reports&page=$page$filterstr");
	}

}else{//管理员管理某文档的所有评论
	if(!$curuser->isadmin()) mcmessage('noadminpermi',$forward);
	$fromsql = "FROM {$tblprefix}reports cu LEFT JOIN {$tblprefix}archives a ON a.aid=cu.aid";
	$wheresql = "WHERE cu.aid='$aid'";
	if(!submitcheck('breportsedit')){
		$pagetmp = $page;
		do{
			$query = $db->query("SELECT cu.*,cu.createdate AS ucreatedate,a.sid,a.createdate,a.caid,a.chid,a.customurl,a.subject AS asubject $fromsql $wheresql ORDER BY cu.updatedate DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)){
			$aid = $item['aid'];
			$cid = $item['cid'];
			$arcurl = view_arcurl($item);
			$updatedate = date("$dateformat", $item['updatedate']);
			$catalogstr = @$catalogs[$item['caid']]['title'];
			$channelstr = @$channels[$item['chid']]['cname'];
			$editstr = "<a href=\"?action=report&aid=$item[aid]&cid=$cid$forwardstr\">".lang('edit')."</a>";
			$itemstr .= "<tr><td align=\"center\" class=\"item1\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$cid]\" value=\"$cid\"></td>\n".
				"<td class=\"item2\"><a href=\"$arcurl\" target=\"_blank\">".mhtmlspecialchars($item['asubject'])."</td>\n".
				"<td align=\"center\" class=\"item1\">$item[mname]</td>\n".
				"<td align=\"center\" class=\"item2\">$catalogstr</td>\n".
				"<td align=\"center\" class=\"item1\">$channelstr</td>\n".
				"<td align=\"center\" class=\"item2\" width=\"70\">$updatedate</td>\n".
				"<td align=\"center\" class=\"item1\" width=\"30\">$editstr</td></tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=reports&aid=$aid");

		tabheader(lang('reportlist'),'arcsedit',"?action=reports&aid=$aid&page=$page",8);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('reportobject'),lang('member'),lang('catalog'),lang('channel'),lang('updatedate'),lang('edit')));
		echo $itemstr;
		tabfooter();
		echo $multi;

		tabfooter('breportsedit',lang('delete'));
	}else{
		if(empty($selectid)) mcmessage('confirmselectreport',"?action=reports&aid=$aid&page=$page$filterstr");
		$db->query("UPDATE {$tblprefix}archives_sub SET reports=GREATEST(0,reports-".count($selectid).") WHERE aid='$aid'");
		$db->query("DELETE FROM {$tblprefix}reports WHERE cid ".multi_str($selectid),'UNBUFFERED');
		mcmessage('reportsucceed',$forward);
	}

}
?>