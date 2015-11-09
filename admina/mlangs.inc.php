<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('lang') || amessage('no_apermission');
load_cache('mlangs');
if(empty($action)) $action = 'mlangsedit';
$url_type = 'langs';include 'urlsarr.inc.php';
url_nav(lang('lanpackmanage'),$urlsarr,'mlang');
if($action == 'mlangsedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}mlangs";
	$keyword && $wheresql = "WHERE ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR content LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('keyword') as $k) $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	if(!submitcheck('bmlangsedit')){
		echo form_str($actionid.'arcsedit',"?entry=mlangs&action=mlangsedit&page=$page");
		tabheader_e();
		echo "<tr><td class=\"txt txtleft\">";
		echo lang('search_keyword')."&nbsp; <input class=\"text\" name=\"keyword\" type=\"text\" value=\"$keyword\" size=\"10\">&nbsp; ";
		echo strbutton('bfilter','filter0');
		echo "</td></tr>";
		tabfooter();

		$pagetmp = $page;
		do{
			$query = $db->query("SELECT * $fromsql $wheresql ORDER BY ename LIMIT ".(($pagetmp - 1) * $atpp).",$atpp");
			$pagetmp--;
		}while(!$db->num_rows($query) && $pagetmp);
		$itemstr = '';
		while($item = $db->fetch_array($query)){
			$itemstr .= "<tr class=\"txt\"><td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$item[ename]]\" value=\"$item[ename]\">\n".
				"<td class=\"txtL\">$item[ename]</td>\n".
				"<td class=\"txtL\"><input type=\"text\" size=\"80\" name=\"mlangsnew[$item[ename]][content]\" value=\"". htmlspecialchars($item['content']) . "\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=mlangs&action=mlangdetail&ename=$item[ename]\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=mlangs&action=mlangsedit$filterstr");
	
		tabheader(lang('mlangadmin')."&nbsp; &nbsp; >><a href=\"?entry=mlangs&action=mlangadd\">".lang('addmlang').'</a>','','',7);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('ename'),lang('remark'),lang('detail')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"bmlangsedit\" value=\"".lang('submit')."\"></form>\n";
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}mlangs WHERE ename='$k'");
				unset($mlangsnew[$k]);
			}
		}
		if(!empty($mlangsnew)){
			$tmp = ",createdate='$timestamp'";
			foreach($mlangsnew as $k => $v){
				$v['content'] = trim($v['content']);
				if(!($mlang = $db->fetch_one("SELECT * FROM {$tblprefix}mlangs WHERE ename='$k'")))continue;
				$sql = $v['content'] != $mlang['content'] ? $tmp : '';
				$db->query("UPDATE {$tblprefix}mlangs SET content='$v[content]'$sql WHERE ename='$k'");
			}
		}
		adminlog(lang('editmlanglist'));
		updatecache('mlangs');
		amessage('mlangedifin', "?entry=mlangs&action=mlangsedit&page=$page$filterstr");
	}

}elseif($action == 'mlangadd'){
	if(!submitcheck('bmlangadd')){
		tabheader(lang('addmlang'),'mlangadd',"?entry=mlangs&action=mlangadd");
		trbasic(lang('mlangename'),'mlangnew[ename]','','text');
		trbasic(lang('mlangcontent'),'mlangnew[content]','','textarea');
		tabfooter('bmlangadd');
		a_guide('mlangadd');
	}else{
		if(preg_match("/[^a-z_A-Z0-9]+/",$mlangnew['ename'])) amessage('enameillegal','?entry=mlangs&action=mlangsedit');
		$mlangnew['ename'] = strtolower(trim(strip_tags($mlangnew['ename'])));
		if(in_array($mlangnew['ename'],array_keys($mlangs))) amessage('enamerepeat','?entry=mlangs&action=mlangsedit');
		$mlangnew['content'] = trim($mlangnew['content']);
		$db->query("INSERT INTO {$tblprefix}mlangs SET 
					ename='$mlangnew[ename]', 
					content='$mlangnew[content]',
					createdate='$timestamp'
					");
		adminlog(lang('addmlang'));
		updatecache('mlangs');
		amessage('mlanaddfin', "?entry=mlangs&action=mlangsedit");
	}
}elseif($action == 'mlangdetail' && $ename){
	if(!($mlang = $db->fetch_one("SELECT * FROM {$tblprefix}mlangs WHERE ename='$ename'"))) amessage('choosemlan');
	if(!submitcheck('bmlangdetail')){
		tabheader(lang('editmlang'),'mlangdetail',"?entry=mlangs&action=mlangdetail&ename=$ename");
		trbasic(lang('mlangename'),'',$mlang['ename'],'');
		trbasic(lang('mlangcontent'),'mlangnew[content]',$mlang['content'],'textarea');
		tabfooter('bmlangdetail');
		a_guide('mlangdetail');
	}else{
		$mlangnew['content'] = trim($mlangnew['content']);
		$sql = $mlangnew['content'] != $mlang['content'] ? ",createdate='$timestamp'" : '';
		$db->query("UPDATE {$tblprefix}mlangs SET 
					content='$mlangnew[content]'
					$sql
					WHERE ename='$ename'");
		adminlog(lang('edinmlandet'));
		updatecache('mlangs');
		amessage('mlangmodfin', "?entry=mlangs&action=mlangsedit");
	}
}

?>