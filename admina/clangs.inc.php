<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
backallow('lang') || amessage('no_apermission');
aheader();
load_cache('clangs');
if(empty($action)) $action = 'clangsedit';
$url_type = 'langs';include 'urlsarr.inc.php';
url_nav(lang('lanpackmanage'),$urlsarr,'clang');
if($action == 'clangsedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}clangs";
	$keyword && $wheresql = "WHERE ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR content LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('keyword') as $k) $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	if(!submitcheck('bclangsedit')){
		echo form_str($actionid.'arcsedit',"?entry=clangs&action=clangsedit&page=$page");
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
				"<td class=\"txtL\"><input type=\"text\" size=\"80\" name=\"clangsnew[$item[ename]][content]\" value=\"". htmlspecialchars($item['content']) . "\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=clangs&action=clangdetail&ename=$item[ename]\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=clangs&action=clangsedit$filterstr");
	
		tabheader(lang('clangadmin')."&nbsp; &nbsp; >><a href=\"?entry=clangs&action=clangadd\">".lang('addclang').'</a>','','',7);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('ename'),lang('remark'),lang('detail')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"bclangsedit\" value=\"".lang('submit')."\"></form>\n";
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}clangs WHERE ename='$k'");
				unset($clangsnew[$k]);
			}
		}
		if(!empty($clangsnew)){
			$tmp = ",createdate='$timestamp'";
			foreach($clangsnew as $k => $v){
				$v['content'] = trim($v['content']);
				if(!($clang = $db->fetch_one("SELECT * FROM {$tblprefix}clangs WHERE ename='$k'")))continue;
				$sql = $v['content'] != $clang['content'] ? $tmp : '';
				$db->query("UPDATE {$tblprefix}clangs SET content='$v[content]'$sql WHERE ename='$k'");
			}
		}
		adminlog(lang('editclanglist'));
		updatecache('clangs');
		amessage('clangedifin', "?entry=clangs&action=clangsedit&page=$page$filterstr");
	}

}elseif($action == 'clangadd'){
	if(!submitcheck('bclangadd')){
		tabheader(lang('addclang'),'clangadd',"?entry=clangs&action=clangadd");
		trbasic(lang('clangename'),'clangnew[ename]','','text');
		trbasic(lang('clangcontent'),'clangnew[content]','','textarea');
		tabfooter('bclangadd');
		a_guide('clangadd');
	}else{
		if(preg_match("/[^a-z_A-Z0-9]+/",$clangnew['ename'])) amessage('enameillegal','?entry=clangs&action=clangsedit');
		$clangnew['ename'] = strtolower(trim(strip_tags($clangnew['ename'])));
		if(in_array($clangnew['ename'],array_keys($clangs))) amessage('enamerepeat','?entry=clangs&action=clangsedit');
		$clangnew['content'] = trim($clangnew['content']);
		$db->query("INSERT INTO {$tblprefix}clangs SET 
					ename='$clangnew[ename]', 
					content='$clangnew[content]',
					createdate='$timestamp'
					");
		adminlog(lang('addclang'));
		updatecache('clangs');
		amessage('clangaddfin', "?entry=clangs&action=clangsedit");
	}
}elseif($action == 'clangdetail' && $ename){
	if(!($clang = $db->fetch_one("SELECT * FROM {$tblprefix}clangs WHERE ename='$ename'"))) amessage('chooseclang');
	if(!submitcheck('bclangdetail')){
		tabheader(lang('editclang'),'clangdetail',"?entry=clangs&action=clangdetail&ename=$ename");
		trbasic(lang('clangename'),'',$clang['ename'],'');
		trbasic(lang('clangcontent'),'clangnew[content]',$clang['content'],'textarea');
		tabfooter('bclangdetail');
		a_guide('clangdetail');
	}else{
		$clangnew['content'] = trim($clangnew['content']);
		$sql = $clangnew['content'] != $clang['content'] ? ",createdate='$timestamp'" : '';
		$db->query("UPDATE {$tblprefix}clangs SET 
					content='$clangnew[content]'
					$sql
					WHERE ename='$ename'");
		adminlog(lang('editclangdetail'));
		updatecache('clangs');
		amessage('clangmodfin', "?entry=clangs&action=clangsedit");
	}
}

?>