<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('lang') || amessage('no_apermission');
load_cache('cmsgs');
if(empty($action)) $action = 'cmsgsedit';
$url_type = 'langs';include 'urlsarr.inc.php';
url_nav(lang('lanpackmanage'),$urlsarr,'cmsg');
if($action == 'cmsgsedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}cmsgs";
	$keyword && $wheresql = "WHERE ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR content LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('keyword') as $k) $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	if(!submitcheck('bcmsgsedit')){
		echo form_str($actionid.'arcsedit',"?entry=cmsgs&action=cmsgsedit&page=$page");
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
				"<td class=\"txtL\"><input type=\"text\" size=\"80\" name=\"cmsgsnew[$item[ename]][content]\" value=\"". htmlspecialchars($item['content']) . "\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=cmsgs&action=cmsgdetail&ename=$item[ename]\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=cmsgs&action=cmsgsedit$filterstr");
	
		tabheader(lang('cmsg_admin')."&nbsp; &nbsp; >><a href=\"?entry=cmsgs&action=cmsgadd\">".lang('add_cmsg').'</a>','','',7);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('ename'),lang('remark'),lang('detail')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"bcmsgsedit\" value=\"".lang('submit')."\"></form>\n";
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}cmsgs WHERE ename='$k'");
				unset($cmsgsnew[$k]);
			}
		}
		if(!empty($cmsgsnew)){
			$tmp = ",createdate='$timestamp'";
			foreach($cmsgsnew as $k => $v){
				$v['content'] = trim($v['content']);
				if(!($cmsg = $db->fetch_one("SELECT * FROM {$tblprefix}cmsgs WHERE ename='$k'")))continue;
				$sql = $v['content'] != $cmsg['content'] ? $tmp : '';
				$db->query("UPDATE {$tblprefix}cmsgs SET content='$v[content]'$sql WHERE ename='$k'");
			}
		}
		adminlog(lang('edit_cmsg_list'));
		updatecache('cmsgs');
		amessage('cmsgeditfinish', "?entry=cmsgs&action=cmsgsedit&page=$page$filterstr");
	}

}elseif($action == 'cmsgadd'){
	if(!submitcheck('bcmsgadd')){
		tabheader(lang('add_cmsg'),'cmsgadd',"?entry=cmsgs&action=cmsgadd");
		trbasic(lang('cmsg_ename'),'cmsgnew[ename]','','text');
		trbasic(lang('cmsg_content'),'cmsgnew[content]','','textarea');
		//trbasic(lang('cmsg_jump_url'),'cmsgnew[jump]','','btext');
		//trbasic(lang('cmsg_view_url'),'cmsgnew[urls]','','textarea');
		tabfooter('bcmsgadd');
		a_guide('cmsgadd');
	}else{
		if(preg_match("/[^a-z_A-Z0-9]+/",$cmsgnew['ename'])) amessage('enameillegal','?entry=cmsgs&action=cmsgsedit');
		$cmsgnew['ename'] = strtolower(trim(strip_tags($cmsgnew['ename'])));
		if(in_array($cmsgnew['ename'],array_keys($cmsgs))) amessage('enamerepeat','?entry=cmsgs&action=cmsgsedit');
		$cmsgnew['content'] = trim($cmsgnew['content']);
		$db->query("INSERT INTO {$tblprefix}cmsgs SET 
					ename='$cmsgnew[ename]', 
					content='$cmsgnew[content]',
					createdate='$timestamp'
					");
		adminlog(lang('add_cmsg'));
		updatecache('cmsgs');
		amessage('cmsgaddfinish', "?entry=cmsgs&action=cmsgsedit");
	}
}elseif($action == 'cmsgdetail' && $ename){
	if(!($cmsg = $db->fetch_one("SELECT * FROM {$tblprefix}cmsgs WHERE ename='$ename'"))) amessage('confirmchoosecmsg');
	if(!submitcheck('bcmsgdetail')){
		tabheader(lang('edit_cmsg'),'cmsgdetail',"?entry=cmsgs&action=cmsgdetail&ename=$ename");
		trbasic(lang('cmsg_ename'),'',$cmsg['ename'],'');
		trbasic(lang('cmsg_content'),'cmsgnew[content]',$cmsg['content'],'textarea');
		//trbasic(lang('cmsg_jump_url'),'cmsgnew[jump]',$cmsg['jump'],'btext');
		//trbasic(lang('cmsg_view_url'),'cmsgnew[urls]',$cmsg['urls'],'textarea');
		tabfooter('bcmsgdetail');
		a_guide('cmsgdetail');
	}else{
		$cmsgnew['content'] = trim($cmsgnew['content']);
		$sql = $cmsgnew['content'] != $cmsg['content'] ? ",createdate='$timestamp'" : '';
		$db->query("UPDATE {$tblprefix}cmsgs SET 
					content='$cmsgnew[content]'
					$sql
					WHERE ename='$ename'");
		adminlog(lang('edit_cmsg_detail'));
		updatecache('cmsgs');
		amessage('cmsgmodifyfinish', "?entry=cmsgs&action=cmsgsedit");
	}
}

?>