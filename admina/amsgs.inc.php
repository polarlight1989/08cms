<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('lang') || amessage('no_apermission');
load_cache('amsgs');
if(empty($action)) $action = 'amsgsedit';
$url_type = 'langs';include 'urlsarr.inc.php';
url_nav(lang('lanpackmanage'),$urlsarr,'amsg');
if($action == 'amsgsedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}amsgs";
	$keyword && $wheresql = "WHERE ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR content LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('keyword') as $k) $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	if(!submitcheck('bamsgsedit')){
		echo form_str($actionid.'arcsedit',"?entry=amsgs&action=amsgsedit&page=$page");
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
				"<td class=\"txtL\"><input type=\"text\" size=\"80\" name=\"amsgsnew[$item[ename]][content]\" value=\"". htmlspecialchars($item['content']) . "\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=amsgs&action=amsgdetail&ename=$item[ename]\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=amsgs&action=amsgsedit$filterstr");
	
		tabheader(lang('amsgadmin')."&nbsp; &nbsp; >><a href=\"?entry=amsgs&action=amsgadd\">".lang('addamsg').'</a>','','',7);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('ename'),lang('remark'),lang('detail')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"bamsgsedit\" value=\"".lang('submit')."\"></form>\n";
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}amsgs WHERE ename='$k'");
				unset($amsgsnew[$k]);
			}
		}
		if(!empty($amsgsnew)){
			$tmp = ",createdate='$timestamp'";
			foreach($amsgsnew as $k => $v){
				$v['content'] = trim($v['content']);
				if(!($amsg = $db->fetch_one("SELECT * FROM {$tblprefix}amsgs WHERE ename='$k'")))continue;
				$sql = $v['content'] != $amsg['content'] ? $tmp : '';
				$db->query("UPDATE {$tblprefix}amsgs SET content='$v[content]'$sql WHERE ename='$k'");
			}
		}
		adminlog(lang('editamsglist'));
		updatecache('amsgs');
		amessage('amsgeditfinish', "?entry=amsgs&action=amsgsedit&page=$page$filterstr");
	}

}elseif($action == 'amsgadd'){
	if(!submitcheck('bamsgadd')){
		tabheader(lang('addamsg'),'amsgadd',"?entry=amsgs&action=amsgadd");
		trbasic(lang('amsgename'),'amsgnew[ename]','','text');
		trbasic(lang('amsgcontent'),'amsgnew[content]','','textarea');
//		trbasic(lang('amsg_jump_url'),'amsgnew[jump]','','btext');
//		trbasic(lang('amsg_view_url'),'amsgnew[urls]','','textarea');
		tabfooter('bamsgadd');
		a_guide('amsgadd');
	}else{
		if(preg_match("/[^a-z_A-Z0-9]+/",$amsgnew['ename'])) amessage('enameillegal','?entry=amsgs&action=amsgsedit');
		$amsgnew['ename'] = strtolower(trim(strip_tags($amsgnew['ename'])));
		if(in_array($amsgnew['ename'],array_keys($amsgs))) amessage('enamerepeat','?entry=amsgs&action=amsgsedit');
		$amsgnew['content'] = trim($amsgnew['content']);
		$db->query("INSERT INTO {$tblprefix}amsgs SET 
					ename='$amsgnew[ename]', 
					content='$amsgnew[content]',
					createdate='$timestamp'
					");
		adminlog(lang('addamsg'));
		updatecache('amsgs');
		amessage('amsgaddfinish', "?entry=amsgs&action=amsgsedit");
	}
}elseif($action == 'amsgdetail' && $ename){
	if(!($amsg = $db->fetch_one("SELECT * FROM {$tblprefix}amsgs WHERE ename='$ename'"))) amessage('chooseamsg');
	if(!submitcheck('bamsgdetail')){
		tabheader(lang('editamsg'),'amsgdetail',"?entry=amsgs&action=amsgdetail&ename=$ename");
		trbasic(lang('amsgename'),'',$amsg['ename'],'');
		trbasic(lang('amsgcontent'),'amsgnew[content]',$amsg['content'],'textarea');
		//trbasic(lang('amsg_jump_url'),'amsgnew[jump]',$amsg['jump'],'btext');
		//trbasic(lang('amsg_view_url'),'amsgnew[urls]',$amsg['urls'],'textarea');
		tabfooter('bamsgdetail');
		a_guide('amsgdetail');
	}else{
		$amsgnew['content'] = trim($amsgnew['content']);
		$sql = $amsgnew['content'] != $amsg['content'] ? ",createdate='$timestamp'" : '';
		$db->query("UPDATE {$tblprefix}amsgs SET 
					content='$amsgnew[content]'
					$sql
					WHERE ename='$ename'");
		adminlog(lang('editamsgdetail'));
		updatecache('amsgs');
		amessage('amsgmodifyfinish', "?entry=amsgs&action=amsgsedit");
	}
}

?>