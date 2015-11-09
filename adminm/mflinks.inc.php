<?
//好友管理
!defined('M_COM') && exit('No Permission');
$cuid=3;
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
	foreach(array('keyword','ucid','checked') as $k){
		isset($$k)||$$k='';
		$filterstr .= "&$k=".urlencode($$k);
	}
	$fromsql = "FROM {$tblprefix}mflinks";
	$wheresql = "WHERE fromid='$memberid'";
	if($keyword) $wheresql .= " AND mname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	if($ucid) $wheresql .= " AND ucid='$ucid'";
	if($checked != '-1') $wheresql .= " AND checked='$checked'";
	if(!submitcheck('bflinksedit')){
		$checkedarr = array('-1' => lang('nolimit'),'0' => lang('nochecklink'),'1' => lang('checkedlink'));
		echo form_str($action.'arcsedit',"?action=mflinks&page=$page");
		tabheader_e();
		echo "<tr><td class=\"item2\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" style=\"vertical-align: middle;\">&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"ucid\">".makeoption(array(0 => lang('allcoclass')) + $ucidsarr,$ucid)."</select>&nbsp; ";
		echo "<select style=\"vertical-align: middle;\" name=\"checked\">".makeoption($checkedarr,$checked)."</select>&nbsp; ";
		echo strbutton('bfilter','filter0').'</td></tr>';
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY cid DESC LIMIT ".(($pagetmp - 1) * $mrowpp).",$mrowpp");
			$pagetmp--;
		} while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)){
			$createdatestr = date("$dateformat", $item['createdate']);
			$checkedstr = $item['checked'] ? 'Y' : '-';
			$uclassstr = empty($ucidsarr[$item['ucid']]) ? '-' : $ucidsarr[$item['ucid']];
			$itemstr .= "<tr><td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[cid]]\" value=\"$item[cid]\"></td>\n".
				"<td class=\"item\" width=\"30\">$item[mid]</td>\n".
				"<td class=\"item2\"><a href=\"{$mspaceurl}index.php?mid=$item[mid]\" target=\"_blank\">$item[mname]</a></td>\n".
				"<td class=\"item\" width=\"120\">$uclassstr</td>\n".
				"<td class=\"item\" width=\"30\">$checkedstr</td>\n".
				"<td class=\"item\" width=\"70\">$createdatestr</td>\n".
				"<td class=\"item\" width=\"66\"><a href=\"?action=mflink&mid=$item[mid]&cid=$item[cid]\">".lang('edit')."</a> <a href=\"?action=$action&deal=delete&cid=$item[cid]\">".lang('delete')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT COUNT(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=mflinks$filterstr");

		tabheader($listname.lang('list'),'','',11);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),array(lang('member'),'left'),lang('uclass'),lang('check'),lang('addtime'),lang('operate')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		tabheader(lang('operateitem'));
		trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"flinkdeal[delete]\" value=\"1\">&nbsp;".lang('delete').$listname,'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"flinkdeal[ucid]\" value=\"1\">&nbsp;".lang('uclass'),'flinkucid',makeoption(array('0' => lang('cancelcoclass')) + $ucidsarr),'select');
		tabfooter('bflinksedit');
	}else{
		if(empty($flinkdeal))mcmessage('selectoperateitem', M_REFERER);
		if(empty($selectid))mcmessage('selectlink', M_REFERER);
		if(!empty($flinkdeal['delete'])){
			$db->query("DELETE FROM {$tblprefix}mflinks WHERE cid in (" . join(',', $selectid) . ") AND fromid=$memberid");
		}else{
			if(!empty($flinkdeal['ucid'])){
				$db->query("UPDATE {$tblprefix}mflinks SET ucid='$flinkucid' WHERE cid in (" . join(',', $selectid) . ") AND fromid=$memberid");
			}
		}
		mcmessage('linkadminfinish', M_REFERER);
	}
}elseif($deal == 'delete' && $cid){
	if(empty($confirm)){
		mcmessage('dellinkconfirm','',"<a href=\"?action=$action&deal=$deal&cid=$cid&confirm=1&$forwardstr\">",'</a>',"<a href=\"$forward\">",'</a>');
	}else{
		$db->query("DELETE FROM {$tblprefix}mflinks WHERE cid=$cid AND fromid=$memberid");
		mcmessage('succeeddellink', $forward, $db->affected_rows());
	}
}
?>