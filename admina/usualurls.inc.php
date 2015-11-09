<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('permissions');
include_once M_ROOT."./include/upload.cls.php";
include_once M_ROOT."./include/fields.cls.php";
$ismc = empty($ismc) ? 0 : 1;
$mc_suffix = !$ismc ? '' : '&ismc=1';
$mc_str = $ismc ? lang('membercenter1') : lang('abackarea');
$url_type = $ismc ? 'mcenter' : 'backarea';include 'urlsarr.inc.php';
backallow($ismc ? 'mcconfig' : 'bkconfig') || amessage('no_apermission');
if($action == 'usualurladd'){
	if(!submitcheck('busualurladd')){
		url_nav(lang($ismc ? 'mcenterconfig' : 'backareaconfig'),$urlsarr,$ismc ? 'musual' : 'ausual',10);
		tabheader(lang('addusualurl',$mc_str),'usualurladd',"?entry=usualurls&action=usualurladd$mc_suffix");
		trbasic(lang('usuatitle'),'usualurlnew[title]','','text');
		trbasic(lang('usualurl'),'usualurlnew[url]','','btext');
		trbasic(lang('usuorder'),'usualurlnew[vieworder]','','text');
		trspecial(lang('urlimage'),'usualurlnew[logo]','','image');
		trbasic(lang('newwin'),'usualurlnew[newwin]',0,'radio');
		if(!$ismc){
			$sidsarr = array('m' => lang('msite')) + sidsarr(1);
			trbasic(lang('belsitforuse'),'',makecheckbox('sidsnew[]',$sidsarr,array(),5),'');
			trbasic(lang('inhitatt'),'usualurlnew[actsid]',0,'radio');
		}else{
			trbasic(lang('onclick'),'usualurlnew[onclick]','','btext');
		}
		trbasic(lang('u_permission_set'),'usualurlnew[pmid]',makeoption(pmidsarr('menu')),'select');
		tabfooter('busualurladd');
		a_guide('usualurladd');
	}else{
		$usualurlnew['title'] = trim(strip_tags($usualurlnew['title']));
		$usualurlnew['url'] = trim(strip_tags($usualurlnew['url']));
		$usualurlnew['vieworder'] = max(0,intval($usualurlnew['vieworder']));
		if(!$usualurlnew['title'] || !$usualurlnew['url']) amessage('pleinpusutitandurl');
		$c_upload = new cls_upload;	
		$usualurlnew['logo'] = upload_s($usualurlnew['logo'],'','image');
		$usualurlnew['sids'] = !empty($sidsnew) ? implode(',',$sidsnew) : '';
		$usualurlnew['actsid'] = !empty($usualurlnew['actsid']) ? 1 : 0;
		$usualurlnew['onclick'] = empty($usualurlnew['onclick']) ? '' : trim($usualurlnew['onclick']);
		$db->query("INSERT INTO {$tblprefix}usualurls SET 
					title='$usualurlnew[title]', 
					url='$usualurlnew[url]', 
					logo='$usualurlnew[logo]', 
					pmid='$usualurlnew[pmid]', 
					sids='$usualurlnew[sids]', 
					newwin='$usualurlnew[newwin]',
					onclick='$usualurlnew[onclick]',
					actsid='$usualurlnew[actsid]',
					vieworder='$usualurlnew[vieworder]',
					ismc='$ismc'
					");
		adminlog(lang('addusualurl'));
		$c_upload->closure(1, $db->insert_id(), 'usualurls');
		updatecache('usualurls');
		amessage('usuaddfin', "?entry=usualurls&action=usualurlsedit$mc_suffix");
	}
}elseif($action == 'usualurlsedit'){
	url_nav(lang($ismc ? 'mcenterconfig' : 'backareaconfig'),$urlsarr,$ismc ? 'musual' : 'ausual',10);
	$usualurls = array();
	$query = $db->query("SELECT * FROM {$tblprefix}usualurls WHERE ismc='$ismc' ORDER BY vieworder,uid");
	while($row = $db->fetch_array($query)) $usualurls[$row['uid']] = $row;
	if(!submitcheck('busualurlsedit')){
		tabheader(lang('urlusualurlmana',$mc_str)."&nbsp; &nbsp; >><a href=\"?entry=usualurls&action=usualurladd$mc_suffix\">".lang('aaddusualurl').'</a>','usualurlsedit',"?entry=usualurls&action=usualurlsedit$mc_suffix",'8');
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'delete', 'chkall')\">".lang('del'),lang('title'),'URL',lang('enable'),lang('order'),lang('edit')));
		foreach($usualurls as $k => $v){
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$k]\" value=\"$k\"></td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"usualurlsnew[$k][title]\" value=\"$v[title]\" size=\"30\"></td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"usualurlsnew[$k][url]\" value=\"$v[url]\" size=\"50\"></td>\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"usualurlsnew[$k][available]\" value=\"1\"".($v['available'] ? " checked" : "")."></td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" name=\"usualurlsnew[$k][vieworder]\" value=\"$v[vieworder]\" size=\"4\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=usualurls&action=usualurldetail&uid=$k$mc_suffix\" onclick=\"return floatwin('open_usualurlsedit',this)\">".lang('detail')."</a></td>\n".
				"</tr>";
		}
		tabfooter('busualurlsedit');
		a_guide('usualurlsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $k){
				$db->query("DELETE FROM {$tblprefix}usualurls WHERE uid='$k'");
				unset($usualurlsnew[$k]);
			}
		}
		if(!empty($usualurlsnew)){
			foreach($usualurlsnew as $k => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['url'] = trim(strip_tags($v['url']));
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$v['available'] = empty($v['available']) ? 0 : 1;
				$sqlstr = "vieworder='$v[vieworder]',available='$v[available]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$v['url'] && $sqlstr .= ",url='$v[url]'";
				$db->query("UPDATE {$tblprefix}usualurls SET $sqlstr WHERE uid='$k'");
			}
		}
		adminlog(lang('ediusuli'));
		updatecache('usualurls');
		amessage('usuedifin', "?entry=usualurls&action=usualurlsedit$mc_suffix");
	}
}elseif($action == 'usualurldetail' && $uid){
	if(!($usualurl = $db->fetch_one("SELECT * FROM {$tblprefix}usualurls WHERE uid='$uid'"))) amessage('chooseusu');
	if(!submitcheck('busualurldetail')){
		tabheader(lang('edit').$mc_str.lang('usualurl'),'usualurldetail',"?entry=usualurls&action=usualurldetail&uid=$uid$mc_suffix");
		trbasic(lang('usuatitle'),'usualurlnew[title]',$usualurl['title'],'text');
		trbasic(lang('usualurl'),'usualurlnew[url]',$usualurl['url'],'btext');
		trbasic(lang('usuorder'),'usualurlnew[vieworder]',$usualurl['vieworder'],'text');
		trspecial(lang('urlimage'),'usualurlnew[logo]',$usualurl['logo'],'image');
		trbasic(lang('newwin'),'usualurlnew[newwin]',$usualurl['newwin'],'radio');
		if(!$ismc){
			$sidsarr = array('m' => lang('msite')) + sidsarr(1);
			trbasic(lang('belsitforuse'),'',makecheckbox('sidsnew[]',$sidsarr,$usualurl['sids'] === '' ? array() : explode(',',$usualurl['sids']),5),'');
			trbasic(lang('inhitatt'),'usualurlnew[actsid]',$usualurl['actsid'],'radio');
		}else{
			trbasic(lang('onclick'),'usualurlnew[onclick]',$usualurl['onclick'],'btext');
		}
		trbasic(lang('u_permission_set'),'usualurlnew[pmid]',makeoption(pmidsarr('menu'),$usualurl['pmid']),'select');
		tabfooter('busualurldetail');
		a_guide('usualurldetail');
	}else{
		$usualurlnew['title'] = trim(strip_tags($usualurlnew['title']));
		$usualurlnew['url'] = trim(strip_tags($usualurlnew['url']));
		$usualurlnew['vieworder'] = max(0,intval($usualurlnew['vieworder']));
		$usualurlnew['title'] = empty($usualurlnew['title']) ? $usualurl['title'] : $usualurlnew['title'];
		$usualurlnew['url'] = empty($usualurlnew['url']) ? $usualurl['url'] : $usualurlnew['url'];
		$c_upload = new cls_upload;	
		$usualurlnew['logo'] = upload_s($usualurlnew['logo'],$usualurl['logo'],'image');
		$usualurlnew['sids'] = !empty($sidsnew) ? implode(',',$sidsnew) : '';
		$usualurlnew['actsid'] = empty($usualurlnew['actsid']) ? 0 : 1;
		$usualurlnew['onclick'] = empty($usualurlnew['onclick']) ? '' : trim($usualurlnew['onclick']);
		$db->query("UPDATE {$tblprefix}usualurls SET 
					title='$usualurlnew[title]', 
					url='$usualurlnew[url]', 
					logo='$usualurlnew[logo]', 
					pmid='$usualurlnew[pmid]', 
					sids='$usualurlnew[sids]', 
					newwin='$usualurlnew[newwin]',
					onclick='$usualurlnew[onclick]',
					actsid='$usualurlnew[actsid]',
					vieworder='$usualurlnew[vieworder]'
					WHERE uid='$uid'");
		$c_upload->closure(1, $uid, 'usualurls');
		adminlog(lang('edusudet'));
		updatecache('usualurls');
		amessage('usuamodifin', axaction(6,"?entry=usualurls&action=usualurlsedit$mc_suffix"));
	}
}
?>