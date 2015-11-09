<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
if(!backallow('lang')) amessage('no_apermission');
load_cache('alangs');
if(empty($action)) $action = 'alangsedit';
$url_type = 'langs';include 'urlsarr.inc.php';
url_nav(lang('lanpackmanage'),$urlsarr,'alang');
if($action == 'alangsedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}alangs";
	$keyword && $wheresql = "WHERE ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR content LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('keyword') as $k) $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	if(!submitcheck('balangsedit')){
		echo form_str($actionid.'arcsedit',"?entry=alangs&action=alangsedit&page=$page");
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
				"<td class=\"txtL\"><input type=\"text\" size=\"80\" name=\"alangsnew[$item[ename]][content]\" value=\"". htmlspecialchars($item['content']) . "\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=alangs&action=alangdetail&ename=$item[ename]\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=alangs&action=alangsedit$filterstr");
	
		tabheader(lang('alang_admin')."&nbsp; &nbsp; >><a href=\"?entry=alangs&action=alangadd\">".lang('add_alang').'</a>','','',7);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('ename'),lang('remark'),lang('detail')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"balangsedit\" value=\"".lang('submit')."\"></form>\n";
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}alangs WHERE ename='$k'");
				unset($alangsnew[$k]);
			}
		}
		if(!empty($alangsnew)){
			$tmp = ",createdate='$timestamp'";
			foreach($alangsnew as $k => $v){
				$v['content'] = trim($v['content']);
				if(!($alang = $db->fetch_one("SELECT * FROM {$tblprefix}alangs WHERE ename='$k'")))continue;
				$sql = $v['content'] != $alang['content'] ? $tmp : '';
				$db->query("UPDATE {$tblprefix}alangs SET content='$v[content]'$sql WHERE ename='$k'");
			}
		}
		adminlog(lang('edit_alang_list'));
		updatecache('alangs');
		amessage('alangedifin', "?entry=alangs&action=alangsedit&page=$page$filterstr");
	}

}elseif($action == 'alangadd'){
	if(!submitcheck('balangadd')){
		tabheader(lang('add_alang'),'alangadd',"?entry=alangs&action=alangadd");
		trbasic(lang('alang_ename'),'alangnew[ename]','','text');
		trbasic(lang('alang_content'),'alangnew[content]','','textarea');
		tabfooter('balangadd');
		a_guide('alangadd');
	}else{
		if(preg_match("/[^a-z_A-Z0-9]+/",$alangnew['ename'])) amessage('enameillegal','?entry=alangs&action=alangsedit');
		$alangnew['ename'] = strtolower(trim(strip_tags($alangnew['ename'])));
		if(in_array($alangnew['ename'],array_keys($alangs))) amessage('enamerepeat','?entry=alangs&action=alangsedit');
		$alangnew['content'] = trim($alangnew['content']);
		$db->query("INSERT INTO {$tblprefix}alangs SET 
					ename='$alangnew[ename]', 
					content='$alangnew[content]',
					createdate='$timestamp'
					");
		adminlog(lang('add_alang'));
		updatecache('alangs');
		amessage('alangaddfin', "?entry=alangs&action=alangsedit");
	}
}elseif($action == 'alangdetail' && $ename){
	if(!($alang = $db->fetch_one("SELECT * FROM {$tblprefix}alangs WHERE ename='$ename'"))) amessage('chooseclang');
	if(!submitcheck('balangdetail')){
		tabheader(lang('edit_alang'),'alangdetail',"?entry=alangs&action=alangdetail&ename=$ename");
		trbasic(lang('alang_ename'),'',$alang['ename'],'');
		trbasic(lang('alang_content'),'alangnew[content]',$alang['content'],'textarea');
		tabfooter('balangdetail');
		a_guide('alangdetail');
	}else{
		$alangnew['content'] = trim($alangnew['content']);
		$sql = $alangnew['content'] != $alang['content'] ? ",createdate='$timestamp'" : '';
		$db->query("UPDATE {$tblprefix}alangs SET 
					content='$alangnew[content]'
					$sql
					WHERE ename='$ename'");
		adminlog(lang('edit_alang_detail'));
		updatecache('alangs');
		amessage('alangmodfin', "?entry=alangs&action=alangsedit");
	}
}

?>