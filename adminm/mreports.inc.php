<?
//好友管理
!defined('M_COM') && exit('No Permission');
$cuid=6;
$mcommu = read_cache('mcommu',$cuid);
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$page = empty($page) ? 1 : max(1, intval($page));
submitcheck('bfilter') && $page = 1;

$listname = $mcommu['cname'];
//好友个人分类
$uclasses = loaduclasses($curuser->info['mid']);
$ucidsarr = array();
foreach($uclasses as $k => $v) if($v['cuid']==-$cuid) $ucidsarr[$k] = $v['title'];

if(empty($deal)){
	$filterstr = '';
	isset($checked)||$checked='-1';
	isset($viewdetail)||$viewdetail='';
	foreach(array('mid','mname','ucid','indays','outdays') as $k){
		isset($$k)||$$k='';
		$filterstr .= "&$k=".urlencode($$k);
	}
	$fromsql = "FROM {$tblprefix}mreports";
	$wheresql = "WHERE fromid='$memberid'";
	if($mid) $wheresql .= " AND mid='$mid'";
	if($mname) $wheresql .= " AND mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
	if($ucid) $wheresql .= " AND ucid='$ucid'";
	if($checked != '-1') $wheresql .= " AND checked='$checked'";
	if($indays) $wheresql .= " AND createdate>'".($timestamp - 86400 * $indays)."'";
	if($outdays) $wheresql .= " AND createdate<'".($timestamp - 86400 * $outdays)."'";
	if(!submitcheck('breportsedit')){
		tabheader(lang('filtersetting').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'reportsedit',"?action=mreports&page=$page");
		echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trbasic(lang('memberid'),'mid',$mid);
		trbasic(lang('membercname'),'mname',$mname,'text',lang('agsearchkey'));
		trrange(lang('reportdate'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('daybefore').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('dayin'),5));
		echo "</tbody>";
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)){
			$createdatestr = date("$dateformat", $item['createdate']);
			$itemstr .= "<tr><td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[cid]]\" value=\"$item[cid]\"></td>\n".
				"<td align=\"center\" class=\"item2\" width=\"30\">$item[mid]</td>\n".
				"<td class=\"item\"><a href=\"{$mspaceurl}index.php?mid=$item[mid]\" target=\"_blank\">$item[mname]</a></td>\n".
				"<td class=\"item\" width=\"60\">$createdatestr</td>\n".
				"<td class=\"item\" width=\"60\"><a href=\"?action=mreport&mid=$item[mid]&cid=$item[cid]\">".lang('edit')."</a> <a href=\"?action=$action&deal=delete&cid=$item[cid]\">".lang('delete')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT COUNT(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=mreports$filterstr");

		tabheader($listname.lang('list'),'','',11);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),lang('member'),lang('add time'),lang('operate')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		tabheader(lang('operateitem'));
		trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"reportdeal[delete]\" value=\"1\">&nbsp;".lang('delete').$listname,'');
		tabfooter('breportsedit');
	}else{
		if(empty($reportdeal))mcmessage('selectoperateitem', M_REFERER);
		if(empty($selectid))mcmessage('confirmselectreport', M_REFERER);
		if(!empty($reportdeal['delete'])){
			$db->query("DELETE FROM {$tblprefix}mreports WHERE cid in (" . join(',', $selectid) . ") AND fromid=$memberid");
		}else{
			if(!empty($reportdeal['ucid'])){
				$db->query("UPDATE {$tblprefix}mreports SET ucid='$reportucid' WHERE cid in (" . join(',', $selectid) . ") AND fromid=$memberid");
			}
		}
		mcmessage('reportadminfin', M_REFERER);
	}
}elseif($deal == 'delete' && $cid){
	if(empty($confirm)){
		mcmessage('delreportcon' . '<br /><br /><a href="' . "?action=$action&deal=$deal&cid=$cid&confirm=1&$forwardstr" . '">[' . lang('confirm') . ']</a>&nbsp;&nbsp;<a href="' . $forward . '">[' . lang('cancel') . ']</a>');
	}else{
		$db->query("DELETE FROM {$tblprefix}mreports WHERE cid=$cid AND fromid=$memberid");
		mcmessage('sucdelete' . $db->affected_rows() . ' piece0 report', $forward);
	}
}
?>