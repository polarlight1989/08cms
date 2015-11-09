<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('lang') || amessage('no_apermission');
load_cache('mmsgs');
if(empty($action)) $action = 'mmsgsedit';
$url_type = 'langs';include 'urlsarr.inc.php';
url_nav(lang('lanpackmanage'),$urlsarr,'mmsg');
if($action == 'mmsgsedit'){
	$page = !empty($page) ? max(1, intval($page)) : 1;
	submitcheck('bfilter') && $page = 1;
	$keyword = empty($keyword) ? '' : $keyword;

	$wheresql = '';
	$fromsql = "FROM {$tblprefix}mmsgs";
	$keyword && $wheresql = "WHERE ename LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%' OR content LIKE '%".str_replace(array(' ','*'),'%',addcslashes($keyword,'%_'))."%'";
	
	$filterstr = '';
	foreach(array('keyword') as $k) $filterstr .= "&$k=".rawurlencode(stripslashes($$k));
	if(!submitcheck('bmmsgsedit')){
		echo form_str($actionid.'arcsedit',"?entry=mmsgs&action=mmsgsedit&page=$page");
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
				"<td class=\"txtL\"><input type=\"text\" size=\"80\" name=\"mmsgsnew[$item[ename]][content]\" value=\"". htmlspecialchars($item['content']) . "\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=mmsgs&action=mmsgdetail&ename=$item[ename]\">".lang('edit')."</a></td>\n".
				"</tr>\n";
		}
		$counts = $db->result_one("SELECT count(*) $fromsql $wheresql");
		$multi = multi($counts,$atpp,$page,"?entry=mmsgs&action=mmsgsedit$filterstr");
	
		tabheader(lang('mmsg_admin')."&nbsp; &nbsp; >><a href=\"?entry=mmsgs&action=mmsgadd\">".lang('add_mmsg').'</a>','','',7);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('ename'),lang('remark'),lang('detail')));
		echo $itemstr;
		tabfooter();
		echo $multi;
		echo "<input class=\"button\" type=\"submit\" name=\"bmmsgsedit\" value=\"".lang('submit')."\"></form>\n";
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}mmsgs WHERE ename='$k'");
				unset($mmsgsnew[$k]);
			}
		}
		if(!empty($mmsgsnew)){
			$tmp = ",createdate='$timestamp'";
			foreach($mmsgsnew as $k => $v){
				$v['content'] = trim($v['content']);
				if(!($mmsg = $db->fetch_one("SELECT * FROM {$tblprefix}mmsgs WHERE ename='$k'")))continue;
				$sql = $v['content'] != $mmsg['content'] ? $tmp : '';
				$db->query("UPDATE {$tblprefix}mmsgs SET content='$v[content]'$sql WHERE ename='$k'");
			}
		}
		adminlog(lang('edit_mmsg_list'));
		updatecache('mmsgs');
		amessage('mmsgeditfin', "?entry=mmsgs&action=mmsgsedit&page=$page$filterstr");
	}

}elseif($action == 'mmsgadd'){
	if(!submitcheck('bmmsgadd')){
		tabheader(lang('add_mmsg'),'mmsgadd',"?entry=mmsgs&action=mmsgadd");
		trbasic(lang('mmsg_ename'),'mmsgnew[ename]','','text');
		trbasic(lang('mmsg_content'),'mmsgnew[content]','','textarea');
		//trbasic(lang('mmsg_jump_url'),'mmsgnew[jump]','','btext');
		//trbasic(lang('mmsg_view_url'),'mmsgnew[urls]','','textarea');
		tabfooter('bmmsgadd');
		a_guide('mmsgadd');
	}else{
		if(preg_match("/[^a-z_A-Z0-9]+/",$mmsgnew['ename'])) amessage('enameillegal','?entry=mmsgs&action=mmsgsedit');
		$mmsgnew['ename'] = strtolower(trim(strip_tags($mmsgnew['ename'])));
		if(in_array($mmsgnew['ename'],array_keys($mmsgs))) amessage('enamerepeat','?entry=mmsgs&action=mmsgsedit');
		$mmsgnew['content'] = trim($mmsgnew['content']);
		$db->query("INSERT INTO {$tblprefix}mmsgs SET 
					ename='$mmsgnew[ename]', 
					content='$mmsgnew[content]',
					createdate='$timestamp'
					");
		adminlog(lang('add_mmsg'));
		updatecache('mmsgs');
		amessage('mmsgaddfin', "?entry=mmsgs&action=mmsgsedit");
	}
}elseif($action == 'mmsgdetail' && $ename){
	if(!($mmsg = $db->fetch_one("SELECT * FROM {$tblprefix}mmsgs WHERE ename='$ename'"))) amessage('choosemmsg');
	if(!submitcheck('bmmsgdetail')){
		tabheader(lang('edit_mmsg'),'mmsgdetail',"?entry=mmsgs&action=mmsgdetail&ename=$ename");
		trbasic(lang('mmsg_ename'),'',$mmsg['ename'],'');
		trbasic(lang('mmsg_content'),'mmsgnew[content]',$mmsg['content'],'textarea');
		//trbasic(lang('mmsg_jump_url'),'mmsgnew[jump]',$mmsg['jump'],'btext');
		//trbasic(lang('mmsg_view_url'),'mmsgnew[urls]',$mmsg['urls'],'textarea');
		tabfooter('bmmsgdetail');
		a_guide('mmsgdetail');
	}else{
		$mmsgnew['content'] = trim($mmsgnew['content']);
		$sql = $mmsgnew['content'] != $mmsg['content'] ? ",createdate='$timestamp'" : '';
		$db->query("UPDATE {$tblprefix}mmsgs SET 
					content='$mmsgnew[content]'
					$sql
					WHERE ename='$ename'");
		adminlog(lang('edit_mmsg_detail'));
		updatecache('mmsgs');
		amessage('mmsgmodfin', "?entry=mmsgs&action=mmsgsedit");
	}
}

?>