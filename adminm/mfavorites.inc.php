<?
//会员收藏管理
!defined('M_COM') && exit('No Permission');
$cuid=7;
$mcommu = read_cache('mcommu',$cuid);
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$page = empty($page) ? 1 : max(1, intval($page));
submitcheck('bfilter') && $page = 1;

$listname = $mcommu['cname'];
//收藏个人分类
$uclasses = loaduclasses($curuser->info['mid']);
$ucidsarr = array();
foreach($uclasses as $k => $v) if($v['cuid']==-$cuid) $ucidsarr[$k] = $v['title'];

if(empty($deal)){
	$filterstr = '';
	isset($checked)||$checked='-1';
	isset($viewdetail)||$viewdetail='';
	foreach(array('mid','mname','ucid','checked','indays','outdays') as $k){
		isset($$k)||$$k='';
		$filterstr .= "&$k=".urlencode($$k);
	}
	$fromsql = "FROM {$tblprefix}mfavorites";
	$wheresql = "WHERE fromid='$memberid'";
	if($mid) $wheresql .= " AND mid='$mid'";
	if($mname) $wheresql .= " AND mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($mname,'%_'))."%'";
	if($ucid) $wheresql .= " AND ucid='$ucid'";
	if($indays) $wheresql .= " AND createdate>'".($timestamp - 86400 * $indays)."'";
	if($outdays) $wheresql .= " AND createdate<'".($timestamp - 86400 * $outdays)."'";
	if(!submitcheck('bmembersedit')){
		$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nocheckmember'),'1' => lang('checkedmember'));
		tabheader(lang('filtersetting').viewcheck('viewdetail',$viewdetail,'tbodyfilter').'&nbsp; &nbsp; '.strbutton('bfilter','filter0'),'membersedit',"?action=$action&page=$page");
		echo "<tbody id=\"tbodyfilter\" style=\"display: ".(empty($viewdetail) ? 'none' : '')."\">";
		trbasic(lang('memberid'),'mid',$mid);
		trbasic(lang('membercname'),'mname',$mname,'text',lang('agsearchkey'));
		trbasic(lang('uclass'),'ucid',makeoption(array(0 => lang('allcoclass')) + $ucidsarr,$ucid),'select');
		trbasic(lang('checkstate'),'',makeradio('checked',$checkedarr,$checked),'');
		trrange(lang('adddate'),array('outdays',empty($outdays) ? '' : $outdays,'','&nbsp; '.lang('daybefore').'&nbsp; -&nbsp; ',5),array('indays',empty($indays) ? '' : $indays,'','&nbsp; '.lang('dayin'),5));
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
			$uclassstr = empty($ucidsarr[$item['ucid']]) ? '-' : $ucidsarr[$item['ucid']];
			$itemstr .= "<tr><td align=\"center\" class=\"item1\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[cid]]\" value=\"$item[cid]\"></td>\n".
				"<td align=\"center\" class=\"item2\" width=\"30\">$item[mid]</td>\n".
				"<td class=\"item1\"><a href=\"{$mspaceurl}index.php?mid=$item[mid]\" target=\"_blank\">$item[mname]</a></td>\n".
				"<td class=\"item2\">$uclassstr</td>\n".
				"<td align=\"center\" class=\"item1\" width=\"60\">$createdatestr</td>\n".
				"<td align=\"center\" class=\"item2\" width=\"30\"><a href=\"?action=$action&deal=delete&cid=$item[cid]\">".lang('delete')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT COUNT(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=$action$filterstr");

		tabheader($listname.lang('list'),'','',11);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),lang('member'),lang('uclass'),lang('addtime'),lang('operate')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		tabheader(lang('operateitem'));
		echo "<tr><td class=\"item1\">".
		"<input class=\"checkbox\" type=\"checkbox\" name=\"favoritedeal[delete]\" value=\"1\">&nbsp;".lang('delete').$listname."&nbsp; &nbsp; &nbsp; ".
		"</td><td class=\"item2\"></td></tr>";
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"favoritedeal[ucid]\" value=\"1\">&nbsp;".lang('uclass'),'favoriteucid',makeoption(array('0' => lang('cancelcoclass')) + $ucidsarr),'select');
		tabfooter('bmembersedit');
	}else{
		if(empty($favoritedeal))mcmessage('selectoperateitem', M_REFERER);
		if(empty($selectid))mcmessage('selectmember', M_REFERER);
		if(!empty($favoritedeal['delete'])){
			$db->query("DELETE FROM {$tblprefix}mfavorites WHERE cid in (" . join(',', $selectid) . ") AND fromid=$memberid");
		}else{
			if(!empty($favoritedeal['ucid'])){
				$db->query("UPDATE {$tblprefix}mfavorites SET ucid='$favoriteucid' WHERE cid in (" . join(',', $selectid) . ") AND fromid=$memberid");
			}
		}
		mcmessage('favadminfinish', M_REFERER);
	}
}elseif($deal == 'delete' && $cid){
	if(empty($confirm)){
		mcmessage('dellinkconfirm','',"<a href=\"?action=$action&deal=$deal&cid=$cid&confirm=1&$forwardstr\">",'</a>',"<a href=\"$forward\">",'</a>');
	}else{
		$db->query("DELETE FROM {$tblprefix}mfavorites WHERE cid=$cid AND fromid=$memberid");
		mcmessage('succeeddellink', $forward, $db->affected_rows());
	}
}
?>