<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('repu') || amessage('no_apermission');
$action = empty($action) ? 'repusedit' : $action;
$url_type = 'repus';include 'urlsarr.inc.php';
if($action == 'repusedit'){
	url_nav(lang('repurelate'),$urlsarr,'record');
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$viewdetail = empty($viewdetail) ? 0 : 1;
	$keyword = empty($keyword) ? '' : $keyword;
	$indays = empty($indays) ? 0 : max(0,intval($indays));
	$outdays = empty($outdays) ? 0 : max(0,intval($outdays));

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}repus";
	$indays && $wheresql .= ($wheresql ? ' AND ' : '')."createdate>'".($timestamp - 86400 * $indays)."'";
	$outdays && $wheresql .= ($wheresql ? ' AND ' : '')."createdate<'".($timestamp - 86400 * $outdays)."'";
	$keyword && $wheresql .= ($wheresql ? ' AND ' : '')."(mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR reason LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%')";
	$wheresql = $wheresql ? "WHERE $wheresql" : '';

	$filterstr = '';
	foreach(array('keyword','viewdetail','indays','outdays',) as $k) $$k && $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	if(!submitcheck('barcsedit')){
		echo form_str($actionid.'arcsedit',"?entry=repus&action=repusedit&page=$page");
		tabheader_e();
		echo "<tr><td colspan=\"2\" class=\"txt txtleft\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"8\" style=\"vertical-align: middle;\">&nbsp; ";
		echo "<input class=\"btn\" type=\"submit\" name=\"bfilter\" id=\"bfilter\" value=\"".lang('filter0')."\">".viewcheck('viewdetail',$viewdetail,$actionid.'tbodyfilter');
		echo "</td></tr>";
		echo "<tbody id=\"{$actionid}tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trrange(lang('add_date'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('day_before').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('day_in'),5));
		echo "</tbody>";
		tabfooter();

		//列表区	
		tabheader(lang('repualterlist')."&nbsp; <input class=\"checkbox\" type=\"checkbox\" name=\"select_all\" value=\"1\">&nbsp;".lang('selectallpage'),'','',10);
		$cy_arr = array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('member'),'txtL'),);
		$cy_arr[] = array(lang('repualter'),'txtR');
		$cy_arr[] = lang('add_time');
		$cy_arr[] = array(lang('reason'),'txtL');
		trcategory($cy_arr);

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY rid DESC LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);

		$itemstr = '';
		while($row = $db->fetch_array($query)){
			$selectstr = "<input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$row[rid]]\" value=\"$row[rid]\">";
			$mnamestr = $row['mname'];
			$repustr = $row['repus'];
			$adddatestr = $row['createdate'] ? date('Y-m-d',$row['createdate']) : '-';
			$reasonstr = cutstr($row['reason'],50,'..');

			$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\">$selectstr</td>\n";
			$itemstr .= "<td class=\"txtL w120\">$mnamestr</td>\n";
			$itemstr .= "<td class=\"txtR w80\">$repustr</td>\n";
			$itemstr .= "<td class=\"txtC w120\">$adddatestr</td>\n";
			$itemstr .= "<td class=\"txtL\">$reasonstr</td>\n";
			$itemstr .= "</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=repus&action=repusedit$filterstr");

		echo $itemstr;
		tabfooter();
		echo $multi;
		echo '<br><br>'.strbutton('barcsedit','delete').'</form>';
	}else{
		if(empty($selectid) && empty($select_all)) amessage('selectoperateitem',M_REFERER);
		if(!empty($select_all)){
			$selectid = array();
			$npage = empty($npage) ? 1 : $npage;
			if(empty($pages)){
				$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
				$pages = @ceil($counts / $atpp);
			}
			if($npage <= $pages){
				$fromstr = empty($fromid) ? "" : "rid<$fromid";
				$nwheresql = !$wheresql ? ($fromstr ? "WHERE $fromstr" : "") : ($wheresql.($fromstr ? " AND " : "").$fromstr);
				$query = $db->query("SELECT rid $fromsql $nwheresql ORDER BY rid DESC LIMIT 0,$atpp");
				while($item = $db->fetch_array($query)) $selectid[] = $item['rid'];
			}
		}
		if($selectid) $db->query("DELETE FROM {$tblprefix}repus WHERE rid ".multi_str($selectid));
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
				amessage('operating',"?entry=$entry&action=$action&page=$page$filterstr$transtr",$pages,$npage,"<a href=\"?entry=$entry&action=$action&page=$page$filterstr\">",'</a>');
			}
		}
		adminlog(lang('delrepusrecord'),lang('delrepusrecord'));
		amessage('arcfinish',"?entry=$entry&action=$action&page=$page$filterstr");
	}
}elseif($action == 'repuadd'){
	url_nav(lang('repurelate'),$urlsarr,'hand');
	if(!submitcheck('brepuadd')){
		$modearr = array('0' => lang('increase'),'1' => lang('deductvalue'));
		tabheader(lang('hand_repu'),'repuadd',"?entry=$entry&action=$action");
		trbasic(lang('member_cname'),'repuadd[mname]','','text',lang('agmultiuser'));
		trbasic(lang('operate_type'),'',makeradio('repuadd[mode]',$modearr),'');
		trbasic(lang('amount'),'repuadd[repus]');
		trbasic(lang('reason'),'repuadd[reason]','','btext');
		tabfooter('brepuadd');
	}else{
		$repuadd['mname'] = trim($repuadd['mname']);
		$repuadd['repus'] = max(0,round($repuadd['repus'],2));
		if(empty($repuadd['mname']) || empty($repuadd['repus'])) amessage('datamissing',M_REFERER);
		$repus = empty($repuadd['mode']) ? $repuadd['repus'] : -$repuadd['repus'];
		$mnames = array_filter(explode(',',$repuadd['mname']));
		$actuser = new cls_userinfo;
		foreach($mnames as $v){
			$v = trim($v);
			if(empty($v)) continue;
			$actuser->activeuserbyname($v);
			$actuser->repuadd($repus,$repuadd['reason'],1);
		}
		unset($actuser);
		adminlog(lang('hand_repu'),lang('hand_repu'));
		amessage('handrepufin',"?entry=$entry&action=$action");
	}
}

?>
