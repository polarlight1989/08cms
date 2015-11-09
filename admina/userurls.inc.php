<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
load_cache('permissions');
$ismc = empty($ismc) ? 0 : 1;
$mc_suffix = !$ismc ? '' : '&ismc=1';
$mc_str = $ismc ? lang('membercenter1') : lang('abackarea');
$url_type = $ismc ? 'mcenter' : 'backarea';include 'urlsarr.inc.php';
backallow($ismc ? 'mcconfig' : 'bkconfig') || amessage('no_apermission');
if($action == 'utypeadd'){
	if(!submitcheck('butypeadd')){
		$pid = empty($pid) ? 0 : max(0,intval($pid));
		url_nav(lang($ismc ? 'mcenterconfig' : 'backareaconfig'),$urlsarr,$ismc ? 'muser' : 'auser',10);
		tabheader(lang("addscoclass",$mc_str),'utypeadd',"?entry=userurls&action=utypeadd$mc_suffix");
		$utidsarr = array('0' => lang('topiccoclass'));
		$query = $db->query("SELECT * FROM {$tblprefix}utypes WHERE pid=0 AND ismc=$ismc ORDER BY vieworder,utid");
		while($row = $db->fetch_array($query)){
			$utidsarr[$row['utid']] = $row['title'];
		}
		trbasic(lang('uplevelcoclass'),'utypenew[pid]',makeoption($utidsarr,$pid),'select');
		trbasic(lang('cocname'),'utypenew[title]','','text');
		trbasic(lang('coclassorder'),'utypenew[vieworder]','','text');
		tabfooter('butypeadd');
		a_guide('utypeadd');
	}else{
		$utypenew['title'] = trim(strip_tags($utypenew['title']));
		!$utypenew['title'] && amessage('inpusecoctit', axaction(1,M_REFERER));
		$utypenew['vieworder'] = max(0,intval($utypenew['vieworder']));
		$db->query("INSERT INTO {$tblprefix}utypes SET 
					title='$utypenew[title]', 
					pid='$utypenew[pid]', 
					ismc='$ismc', 
					vieworder='$utypenew[vieworder]'
					");
	
		adminlog(lang('addusecoc'));
		updatecache('userurls');
		amessage('usercocaddfin', axaction(6,"?entry=userurls&action=userurlsedit$mc_suffix"));
	}
}elseif($action == 'userurladd' && $utid){
	$utid = max(0,intval($utid));
	$utidsarr = array();
	$query = $db->query("SELECT * FROM {$tblprefix}utypes WHERE pid!=0 AND ismc=$ismc ORDER BY pid,vieworder,utid");
	while($row = $db->fetch_array($query)){
		$utidsarr[$row['utid']] = $row['title'];
	}
	if(!submitcheck('buserurladd')){
		tabheader(lang('addsuserurl',$mc_str),'userurladd',"?entry=userurls&action=userurladd&utid=$utid$mc_suffix");
		trbasic(lang('belongcocl'),'userurlnew[utid]',makeoption($utidsarr,$utid),'select');
		trbasic(lang('userurlcname'),'userurlnew[title]','','text');
		trbasic(lang('userurl'),'userurlnew[url]','','btext');
		trbasic(lang('userurlorder'),'userurlnew[vieworder]','','text');
		$ismc && trbasic(lang('onclick'),'userurlnew[onclick]','','btext');
		tabfooter('buserurladd');
		a_guide('userurladd');
	}else{
		$userurlnew['title'] = trim(strip_tags($userurlnew['title']));
		$userurlnew['url'] = trim(strip_tags($userurlnew['url']));
		$userurlnew['vieworder'] = max(0,intval($userurlnew['vieworder']));
		(!$userurlnew['title'] || !$userurlnew['url']) && amessage('inpusetiau', axaction(1,M_REFERER));
		!$userurlnew['utid'] && amessage('please point userurl belong coclass !');
		$userurlnew['onclick'] = empty($userurlnew['onclick']) ? '' : trim($userurlnew['onclick']);
		$db->query("INSERT INTO {$tblprefix}userurls SET 
					title='$userurlnew[title]', 
					url='$userurlnew[url]', 
					utid='$userurlnew[utid]', 
					onclick='$userurlnew[onclick]', 
					vieworder='$userurlnew[vieworder]'
					");
	
		adminlog(lang('addusecoc'));
		updatecache('userurls');
		amessage('useraddfin', axaction(6,"?entry=userurls&action=userurlsedit$mc_suffix"));
	}
}elseif($action == 'userurlsedit'){
	url_nav(lang($ismc ? 'mcenterconfig' : 'backareaconfig'),$urlsarr,$ismc ? 'muser' : 'auser',10);
	if(!submitcheck('buserurlsedit')){
		tabheader(lang('suserurlmanager',$mc_str)."&nbsp; &nbsp; >><a href=\"?entry=userurls&action=utypeadd$mc_suffix\">".lang('addurlcoclass').'</a>','userurlsedit',"?entry=userurls&action=userurlsedit$mc_suffix",'8');
		trcategory(array(lang('sn'),lang('id'),lang('title'),lang('enable'),lang('order'),lang('add'),lang('edit'),lang('delete')));
		$query = $db->query("SELECT * FROM {$tblprefix}utypes WHERE pid=0 AND ismc=$ismc ORDER BY vieworder,utid");
		$i = 0;
		while($utype0 = $db->fetch_array($query)){
			$utid = $utype0['utid'];
			$i ++;
			echo "<tr>\n".
				"<td class=\"txtC w30\">$i</td>\n".
				"<td class=\"txtC w30\">$utid</td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"utypesnew[$utid][title]\" value=\"$utype0[title]\" size=\"30\"></td>\n".
				"<td class=\"txtC w30\"></td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" name=\"utypesnew[$utid][vieworder]\" value=\"$utype0[vieworder]\" size=\"4\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=utypeadd&pid=$utid$mc_suffix\" onclick=\"return floatwin('open_userurlsedit',this)\">+".lang('coclass')."</a></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=utypedetail&utid=$utid$mc_suffix\" onclick=\"return floatwin('open_userurlsedit',this)\">".lang('detail')."</a></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=utypedel&utid=$utid$mc_suffix\">".lang('delete')."</a></td>\n".
				"</tr>";
			$query1 = $db->query("SELECT * FROM {$tblprefix}utypes WHERE pid='$utid' AND ismc=$ismc ORDER BY vieworder,utid");
			while($utype1 = $db->fetch_array($query1)){
				$utid = $utype1['utid'];
				$i ++;
				echo "<tr>\n".
					"<td class=\"txtC w30\">$i</td>\n".
					"<td class=\"txtC w30\">$utid</td>\n".
					"<td class=\"txtL\">&nbsp; &nbsp; &nbsp; &nbsp; <input type=\"text\" name=\"utypesnew[$utid][title]\" value=\"$utype1[title]\" size=\"30\"></td>\n".
					"<td class=\"txtC w30\"></td>\n".
					"<td class=\"txtC w40\"><input type=\"text\" name=\"utypesnew[$utid][vieworder]\" value=\"$utype1[vieworder]\" size=\"4\"></td>\n".
					"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=userurladd&utid=$utid$mc_suffix\" onclick=\"return floatwin('open_userurlsedit',this)\">+".lang('url')."</a></td>\n".
					"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=utypedetail&utid=$utid$mc_suffix\" onclick=\"return floatwin('open_userurlsedit',this)\">".lang('detail')."</a></td>\n".
					"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=utypedel&utid=$utid$mc_suffix\">".lang('delete')."</a></td>\n".
					"</tr>";
				$query2 = $db->query("SELECT * FROM {$tblprefix}userurls WHERE utid='$utid' ORDER BY vieworder,uid");
				while($row = $db->fetch_array($query2)){
					$uid = $row['uid'];
					$i ++;
					echo "<tr class=\"txt\">\n".
						"<td class=\"txtC w30\">$i</td>\n".
						"<td class=\"txtC w30\">$uid</td>\n".
						"<td class=\"txtL\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=\"text\" name=\"userurlsnew[$uid][title]\" value=\"$row[title]\" size=\"30\"></td>\n".
						"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"userurlsnew[$uid][available]\" value=\"1\"".($row['available'] ? " checked" : "")."></td>\n".
						"<td class=\"txtC w40\"><input type=\"text\" name=\"userurlsnew[$uid][vieworder]\" value=\"$row[vieworder]\" size=\"4\"></td>\n".
						"<td class=\"txtC w40\">-</td>\n".
						"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=userurldetail&uid=$uid$mc_suffix\" onclick=\"return floatwin('open_userurlsedit',this)\">".lang('detail')."</a></td>\n".
						"<td class=\"txtC w40\"><a href=\"?entry=userurls&action=userurldel&uid=$uid$mc_suffix\">".lang('delete')."</a></td>\n".
						"</tr>";
				}
			}
		}
		tabfooter('buserurlsedit');
		a_guide('userurlsedit');
	}else{
		if(!empty($utypesnew)){
			foreach($utypesnew as $k => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = empty($v['vieworder']) ? 0 : max(0,intval($v['vieworder']));
				$sqlstr = "vieworder='$v[vieworder]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}utypes SET $sqlstr WHERE utid='$k'");
			}
		}
		if(!empty($userurlsnew)){
			foreach($userurlsnew as $k => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$v['available'] = empty($v['available']) ? 0 : 1;
				$sqlstr = "vieworder='$v[vieworder]',available='$v[available]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}userurls SET $sqlstr WHERE uid='$k'");
			}
		}
		adminlog(lang('ediuserulist'));
		updatecache('userurls');
		amessage('useedifin', "?entry=userurls&action=userurlsedit$mc_suffix");
	}
}elseif($action == 'utypedetail' && $utid){
	if(!($utype = $db->fetch_one("SELECT * FROM {$tblprefix}utypes WHERE utid='$utid'"))) amessage('chooseusecoc');
	if(!submitcheck('butypedetail')){
		tabheader(lang('editsuserurlcoclass',$mc_str),'utypedetail',"?entry=userurls&action=utypedetail&utid=$utid$mc_suffix");
		trbasic(lang('cocname'),'utypenew[title]',$utype['title'],'text');
		if($utype['pid']){
			$utidsarr = array();
			$query = $db->query("SELECT * FROM {$tblprefix}utypes WHERE pid=0 AND ismc=$ismc ORDER BY vieworder,utid");
			while($row = $db->fetch_array($query)){
				$utidsarr[$row['utid']] = $row['title'];
			}
			trbasic(lang('uplevelcoclass'),'utypenew[pid]',makeoption($utidsarr,$utype['pid']),'select');
		}else{
			trbasic(lang('coclasspickurl'),'',!$utype['ismc'] ? "?entry=userlinks&utid=$utid" : "?action=userlinks&utid=$utid",'');
		}
		trbasic(lang('coclassorder'),'utypenew[vieworder]',$utype['vieworder'],'text');
		$sidsarr = array(0 => lang('msite')) + sidsarr(1);
#		trbasic(lang('belsitforuse'),'',makecheckbox('sidsnew[]',$sidsarr,$utype['sids'] === '' ? array() : explode(',',$utype['sids']),5),'');
		trbasic(lang('u_permission_set'),'utypenew[pmid]',makeoption(pmidsarr('menu'),$utype['pmid']),'select');
		tabfooter('butypedetail');
		a_guide('utypedetail');
	}else{
		$utypenew['title'] = trim(strip_tags($utypenew['title']));
		$utypenew['vieworder'] = max(0,intval($utypenew['vieworder']));
		$utypenew['pid'] = empty($utypenew['pid']) ? 0 : max(0,intval($utypenew['pid']));
		!$utypenew['title'] && amessage('inpusecoctit');
#		$utypenew['sids'] = !empty($sidsnew) ? implode(',',$sidsnew) : '';
#					sids='$utypenew[sids]', 
		$db->query("UPDATE {$tblprefix}utypes SET 
					title='$utypenew[title]', 
					pid='$utypenew[pid]', 
					pmid='$utypenew[pmid]', 
					vieworder='$utypenew[vieworder]'
					WHERE utid='$utid'");
	
		adminlog(lang('ediusecocdet'));
		updatecache('userurls');
		amessage('usecocmodfin', axaction(6,"?entry=userurls&action=userurlsedit$mc_suffix"));
	}

}elseif($action == 'userurldetail' && $uid){
	if(!($userurl = $db->fetch_one("SELECT * FROM {$tblprefix}userurls WHERE uid='$uid'"))) amessage('chooseuserurl');
	if(!submitcheck('buserurldetail')){
		tabheader(lang('editsuserurl',$mc_str),'userurldetail',"?entry=userurls&action=userurldetail&uid=$uid$mc_suffix");
		$utidsarr = array();
		$query = $db->query("SELECT * FROM {$tblprefix}utypes WHERE pid!=0 AND ismc=$ismc ORDER BY pid,vieworder,utid");
		while($row = $db->fetch_array($query)){
			$utidsarr[$row['utid']] = $row['title'];
		}
		trbasic(lang('belongcocl'),'userurlnew[utid]',makeoption($utidsarr,$userurl['utid']),'select');
		trbasic(lang('userurlcname'),'userurlnew[title]',$userurl['title'],'text');
		trbasic(lang('userurl'),'userurlnew[url]',$userurl['url'],'btext');
		$sidsarr = array('m' => lang('msite')) + sidsarr(1);
#		trbasic(lang('belsitforuse'),'',makecheckbox('sidsnew[]',$sidsarr,$userurl['sids'] === '' ? array() : explode(',',$userurl['sids']),5),'');
		trbasic(lang('userurlorder'),'userurlnew[vieworder]',$userurl['vieworder'],'text');
		trbasic(lang('newwin'),'userurlnew[newwin]',$userurl['newwin'],'radio');
		$ismc && trbasic(lang('onclick'),'userurlnew[onclick]',$userurl['onclick'],'btext');
#		trbasic(lang('inhitatt'),'userurlnew[actsid]',$userurl['actsid'],'radio');
		trbasic(lang('u_permission_set'),'userurlnew[pmid]',makeoption(pmidsarr('menu'),$userurl['pmid']),'select');
		tabfooter('buserurldetail');
		a_guide('userurldetail');
	}else{
		$userurlnew['title'] = trim(strip_tags($userurlnew['title']));
		$userurlnew['url'] = trim(strip_tags($userurlnew['url']));
		$userurlnew['vieworder'] = max(0,intval($userurlnew['vieworder']));
		$userurlnew['utid'] = empty($userurlnew['utid']) ? 0 : max(0,intval($userurlnew['utid']));
		(!$userurlnew['title'] || !$userurlnew['url']) && amessage('inpusetiau');
		!$userurlnew['utid'] && amessage('please point userurl belong coclass !');
#		$userurlnew['sids'] = !empty($sidsnew) ? implode(',',$sidsnew) : '';
		$userurlnew['onclick'] = empty($userurlnew['onclick']) ? '' : trim($userurlnew['onclick']);
#					sids='$userurlnew[sids]', 
#					actsid='$userurlnew[actsid]',
		$db->query("UPDATE {$tblprefix}userurls SET 
					title='$userurlnew[title]', 
					url='$userurlnew[url]', 
					utid='$userurlnew[utid]', 
					pmid='$userurlnew[pmid]', 
					newwin='$userurlnew[newwin]',
					onclick='$userurlnew[onclick]',

					vieworder='$userurlnew[vieworder]'
					WHERE uid='$uid'");
		adminlog(lang('ediuserdetail'));
		updatecache('userurls');
		amessage('usermodfin', axaction(6,"?entry=userurls&action=userurlsedit$mc_suffix"));
	}
}elseif($action == 'utypedel' && $utid){
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}utypes WHERE pid='$utid'")){
		amessage('usercocwitsoncoccandel', "?entry=userurls&action=userurlsedit$mc_suffix");
	}
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}userurls WHERE utid='$utid'")){
		amessage('usercocwitusecandel', "?entry=userurls&action=userurlsedit$mc_suffix");
	}
	$db->query("DELETE FROM {$tblprefix}utypes WHERE utid='$utid'");
	adminlog(lang('delusercoc'));
	updatecache('userurls');
	amessage('usecocdelfin', "?entry=userurls&action=userurlsedit$mc_suffix");
}elseif($action == 'userurldel' && $uid){
	$db->query("DELETE FROM {$tblprefix}userurls WHERE uid='$uid'");
	adminlog(lang('deleteuserurl'));
	updatecache('userurls');
	amessage('userurldelfin', "?entry=userurls&action=userurlsedit$mc_suffix");
}
?>