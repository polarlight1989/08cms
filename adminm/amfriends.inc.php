<?
//好友管理
!defined('M_COM') && exit('No Permission');
$mcommu = read_cache('mcommu',2);
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$page = empty($page) ? 1 : max(1, intval($page));
submitcheck('bfilter') && $page = 1;
$listname = $mcommu['cname'];

if(empty($deal)){
	$filterstr = '';
	foreach(array('keyword') as $k){
		isset($$k)||$$k='';
		$filterstr .= "&$k=".urlencode($$k);
	}
	isset($viewdetail)||$viewdetail='';
	$fromsql = "FROM {$tblprefix}mfriends";
	$wheresql = "WHERE mid='$memberid' AND checked='0'";
	if($keyword) $wheresql .= " AND fromname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	if(!submitcheck('bfriendsedit')){
		echo form_str($action.'arcsedit',"?action=$action&page=$page");
		tabheader_e();
		echo "<tr><td class=\"item2\">";
		echo lang('keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" style=\"vertical-align: middle;\">&nbsp; ";
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
			$itemstr .= "<tr><td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[cid]]\" value=\"$item[cid]\"></td>\n".
				"<td class=\"item\" width=\"30\">$item[fromid]</td>\n".
				"<td class=\"item\"><a href=\"{$mspaceurl}index.php?mid=$item[fromid]\" target=\"_blank\">$item[fromname]</a></td>\n".
				"<td class=\"item\" width=\"60\">$createdatestr</td>\n".
				"<td class=\"item\" width=\"100\"><a href=\"?action=$action&deal=agree&cid=$item[cid]\">".lang('agree')."</a> <a href=\"?action=$action&deal=delete&cid=$item[cid]\">".lang('delete')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT COUNT(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=mfriends$filterstr");

		tabheader(lang('needlist'),'','',11);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),lang('membercname'),lang('needtime'),lang('operate')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		tabheader(lang('operateitem'));
		trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"frienddeal[delete]\" value=\"1\">&nbsp;".lang('delete').$listname."&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"frienddeal[pass]\" value=\"1\">&nbsp;".lang('check').$listname,'');
		tabfooter('bfriendsedit');
	}else{
		if(empty($frienddeal))mcmessage('selectoperateitem', M_REFERER);
		if(empty($selectid))mcmessage('chooseoperatemember', M_REFERER);
		if(!empty($frienddeal['delete'])){
			$db->query("DELETE FROM {$tblprefix}mfriends WHERE cid in (" . join(',', $selectid) . ") AND mid=$memberid");
		}else{
			if(!empty($frienddeal['pass'])){
				$db->query("UPDATE {$tblprefix}mfriends SET checked='1' WHERE cid in (" . join(',', $selectid) . ") AND mid=$memberid");
			}
		}
		mcmessage('friendneedadminok', M_REFERER);
	}
}elseif($deal == 'delete' && $cid){
	if(empty($confirm)){
		mcmessage('delfriendconfirm','',"<a href=\"?action=$action&deal=$deal&cid=$cid&confirm=1&$forwardstr\">",'</a>',"<a href=\"$forward\">",'</a>');
	}else{
		$db->query("DELETE FROM {$tblprefix}mfriends WHERE cid=$cid AND mid=$memberid");
		mcmessage('succeeddelfriend', $forward, $db->affected_rows());
	}
}elseif($deal == 'agree' && $cid){
	$db->query("UPDATE {$tblprefix}mfriends SET checked='1' WHERE cid=$cid AND mid=$memberid");
	mcmessage('sagreefriendadd', $forward, $db->affected_rows());
}
?>