<?
//链接申请管理
!defined('M_COM') && exit('No Permission');
$cuid=3;
$mcommu = read_cache('mcommu',$cuid);
$forward = empty($forward) ? M_REFERER : $forward;
$forwardstr = '&forward='.rawurlencode($forward);
$page = empty($page) ? 1 : max(1, intval($page));
submitcheck('bfilter') && $page = 1;

$listname = $mcommu['cname'];
$uclasses = loaduclasses($curuser->info['mid']);
$ucidsarr = array();
foreach($uclasses as $k => $v) if($v['cuid']==-$cuid) $ucidsarr[$k] = $v['title'];

if(empty($deal)){
	$filterstr = '';
	foreach(array('keyword') as $k){
		isset($$k)||$$k='';
		$filterstr .= "&$k=".urlencode($$k);
	}
	$fromsql = "FROM {$tblprefix}mflinks";
	$wheresql = "WHERE mid='$memberid' AND checked='0'";
	if($keyword) $wheresql .= " AND fromname LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	if(!submitcheck('bflinksedit')){
		echo form_str($action.'flinksedit',"?action=$action&page=$page");
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
			$itemstr .= "<tr><td class=\"item\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$item[cid]]\" value=\"$item[cid]\"></td>\n".
				"<td class=\"item\" width=\"30\">$item[mid]</td>\n".
				"<td class=\"item2\"><a href=\"{$mspaceurl}index.php?mid=$item[mid]\" target=\"_blank\">$item[mname]</a></td>\n".
				"<td class=\"item\" width=\"60\">$createdatestr</td>\n".
				"<td class=\"item\" width=\"60\"><a href=\"?action=mflink&mid=$item[mid]&cid=$item[cid]\">".lang('look')."</a> <a href=\"?action=$action&deal=delete&cid=$item[cid]\">".lang('delete')."</a></td></tr>\n";
		}
		$counts = $db->result_one("SELECT COUNT(*) $fromsql $wheresql");
		$multi = multi($counts,$mrowpp,$page,"?action=mflinks$filterstr");

		tabheader($listname.lang('list'),'','',11);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" class=\"category\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('id'),lang('member'),lang('addtime'),lang('operate')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		tabheader(lang('operateitem'));
		trbasic(lang('choose_item'),'',"<input class=\"checkbox\" type=\"checkbox\" name=\"flinkdeal[delete]\" value=\"1\">&nbsp;".lang('delete').$listname."&nbsp;<input class=\"checkbox\" type=\"checkbox\" name=\"flinkdeal[pass]\" value=\"1\">&nbsp;".lang('check').$listname,'');
		tabfooter('bflinksedit');
	}else{
		if(empty($flinkdeal))mcmessage('selectoperateitem', M_REFERER);
		if(empty($selectid))mcmessage('selectlink', M_REFERER);
		if(!empty($flinkdeal['delete'])){
			$db->query("DELETE FROM {$tblprefix}mflinks WHERE cid in (" . join(',', $selectid) . ") AND mid=$memberid");
		}else{
			if(!empty($flinkdeal['pass'])){
				$db->query("UPDATE {$tblprefix}mflinks SET checked='1' WHERE cid in (" . join(',', $selectid) . ") AND mid=$memberid");
			}
		}
		mcmessage('linkadminfinish', M_REFERER);
	}
}elseif($deal == 'delete' && $cid){
	if(empty($confirm)){
		mcmessage('dellinkconfirm','',"<a href=\"?action=$action&deal=$deal&cid=$cid&confirm=1&$forwardstr\">",'</a>',"<a href=\"$forward\">",'</a>');
	}else{
		$db->query("DELETE FROM {$tblprefix}mflinks WHERE cid=$cid AND mid=$memberid");
		mcmessage('succeeddellink', $forward, $db->affected_rows());
	}
}
?>