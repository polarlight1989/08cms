<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('mcconfig') || amessage('no_apermission');
load_cache('permissions');
$url_type = 'mcenter';include 'urlsarr.inc.php';
if($action == 'mmtypeadd'){
	if(!submitcheck('bmmtypeadd')){
		tabheader(lang('addmemcenmenco'),'mmtypeadd',"?entry=mmenus&action=mmtypeadd");
		trbasic(lang('cocname'),'mmtypenew[title]','','text');
		trbasic(lang('coclassorder'),'mmtypenew[vieworder]','','text');
		tabfooter('bmmtypeadd');
		a_guide('mmtypeadd');
	}else{
		$mmtypenew['title'] = trim(strip_tags($mmtypenew['title']));
		$mmtypenew['vieworder'] = max(0,intval($mmtypenew['vieworder']));
		!$mmtypenew['title'] && amessage('pleinpmmecoctit');
		$db->query("INSERT INTO {$tblprefix}mmtypes SET 
					title='$mmtypenew[title]', 
					vieworder='$mmtypenew[vieworder]'
					");
	
		adminlog(lang('addmemcenmenco'));
		updatecache('mmenus');
		amessage('memcenmecocaddfin', "?entry=mmenus&action=mmenusedit");
	}
}elseif($action == 'mmenuadd' && $mtid){
	$mtid = max(0,intval($mtid));
	$mtidsarr = array();
	$query = $db->query("SELECT * FROM {$tblprefix}mmtypes ORDER BY vieworder,mtid");
	while($row = $db->fetch_array($query)){
		$mtidsarr[$row['mtid']] = $row['title'];
	}
	if(!submitcheck('bmmenuadd')){
		tabheader(lang('addmemcenmenite'),'mmenuadd',"?entry=mmenus&action=mmenuadd&mtid=$mtid");
		trbasic(lang('belongcocl'),'mmenunew[mtid]',makeoption($mtidsarr,$mtid),'select');
		trbasic(lang('menuitemcname'),'mmenunew[title]','','text');
		trbasic(lang('menuitemurl'),'mmenunew[url]','','btext');
		trbasic(lang('beluseval'),'mmenunew[pmid]',makeoption(pmidsarr('menu')),'select');
		trbasic(lang('meniteord'),'mmenunew[vieworder]','','text');
		trbasic(lang('newwin'),'mmenunew[newwin]',0,'radio');
		trbasic(lang('onclick'),'mmenunew[onclick]','','btext');
		tabfooter('bmmenuadd');
		a_guide('mmenuadd');
	}else{
		$mmenunew['title'] = trim(strip_tags($mmenunew['title']));
		$mmenunew['url'] = trim(strip_tags($mmenunew['url']));
		$mmenunew['onclick'] = trim($mmenunew['onclick']);
		$mmenunew['vieworder'] = max(0,intval($mmenunew['vieworder']));
		(!$mmenunew['title'] || !$mmenunew['url']) && amessage('pleinpmetitandurl',axaction(1,M_REFERER));
		!$mmenunew['mtid'] && amessage('poimmebelcoc');
		$db->query("INSERT INTO {$tblprefix}mmenus SET 
					title='$mmenunew[title]', 
					url='$mmenunew[url]', 
					mtid='$mmenunew[mtid]', 
					pmid='$mmenunew[pmid]', 
					newwin='$mmenunew[newwin]', 
					onclick='$mmenunew[onclick]', 
					vieworder='$mmenunew[vieworder]'
					");
	
		adminlog(lang('addmemcenmenite'));
		updatecache('mmenus');
		amessage('memcenmeniteadd', axaction(6,"?entry=mmenus&action=mmenusedit"));
	}
}elseif($action == 'mmenusedit'){
	url_nav(lang('mcenterconfig'),$urlsarr,'c',10);
	if(!submitcheck('bmmenusedit')){
		tabheader(lang('memcenmenman')."&nbsp; &nbsp; >><a href=\"?entry=mmenus&action=mmtypeadd\">".lang('admencoc').'</a>','mmenusedit',"?entry=mmenus&action=mmenusedit",'9');
		trcategory(array(lang('sn')/*,lang('id')*/,array(lang('title'),'left'),lang('enable'),lang('order'),lang('add'),lang('edit'),lang('delete')));
		$query = $db->query("SELECT * FROM {$tblprefix}mmtypes ORDER BY vieworder,mtid");
		$i = 0;
		while($mmtype = $db->fetch_array($query)){
			$mtid = $mmtype['mtid'];
			$i ++;
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\">$i</td>\n".
#				"<td class=\"txtC w30\">$mtid</td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"mmtypesnew[$mtid][title]\" value=\"$mmtype[title]\" size=\"25\"></td>\n".
				"<td class=\"txtC w30\"></td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" name=\"mmtypesnew[$mtid][vieworder]\" value=\"$mmtype[vieworder]\" size=\"4\"></td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=mmenus&action=mmenuadd&mtid=$mtid\" onclick=\"return floatwin('open_mmenusedit',this)\">+".lang('menu')."</a></td>\n".
				"<td class=\"txtC w40\"></td>\n".
#				"<td class=\"txtC w40\">".($mmtype['fixed'] ? '-' : ("<a href=\"?entry=mmenus&action=mmtypedel&mtid=$mtid\">".lang('delete')."</a>"))."</td>\n".
				"<td class=\"txtC w40\"><a href=\"?entry=mmenus&action=mmtypedel&mtid=$mtid\">".lang('delete')."</a></td>\n".
				"</tr>";
			$query1 = $db->query("SELECT * FROM {$tblprefix}mmenus WHERE mtid='$mtid' ORDER BY vieworder,mnid");
			while($row = $db->fetch_array($query1)){
				$mnid = $row['mnid'];
				$i ++;
				echo "<tr class=\"txt\">\n".
					"<td class=\"txtC w30\">$i</td>\n".
#					"<td class=\"txtC w30\">$mnid</td>\n".
					"<td class=\"txtL\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type=\"text\" name=\"mmenusnew[$mnid][title]\" value=\"$row[title]\" size=\"25\"></td>\n".
					"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"mmenusnew[$mnid][available]\" value=\"1\"".($row['available'] ? " checked" : "")."></td>\n".
					//"<td class=\"item2\" align=\"center\" width=\"30\"><input class=\"checkbox\" type=\"checkbox\" name=\"mmenusnew[$mnid][issys]\" value=\"1\"".($row['issys'] ? " checked" : "")."></td>\n".
					"<td class=\"txtC w40\"><input type=\"text\" name=\"mmenusnew[$mnid][vieworder]\" value=\"$row[vieworder]\" size=\"4\"></td>\n".
					"<td class=\"txtC w40\">-</td>\n".
					"<td class=\"txtC w40\"><a href=\"?entry=mmenus&action=mmenudetail&mnid=$mnid\" onclick=\"return floatwin('open_mmenusedit',this)\">".lang('detail')."</a></td>\n".
#					"<td class=\"txtC w40\">".($row['issys'] ? '-' : ("<a href=\"?entry=mmenus&action=mmenudel&mnid=$mnid\">".lang('delete')."</a>"))."</td>\n".
					"<td class=\"txtC w40\"><a href=\"?entry=mmenus&action=mmenudel&mnid=$mnid\">".lang('delete')."</a></td>\n".
					"</tr>";
			}
		}
		tabfooter('bmmenusedit');
		a_guide('mmenusedit');
	}else{
		if(!empty($mmtypesnew)){
			foreach($mmtypesnew as $k => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = empty($v['vieworder']) ? 0 : max(0,intval($v['vieworder']));
				$sqlstr = "vieworder='$v[vieworder]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}mmtypes SET $sqlstr WHERE mtid='$k'");
			}
		}
		if(!empty($mmenusnew)){
			foreach($mmenusnew as $k => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$v['available'] = empty($v['available']) ? 0 : 1;
				$sqlstr = "vieworder='$v[vieworder]',available='$v[available]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}mmenus SET $sqlstr WHERE mnid='$k'");
			}
		}
		adminlog(lang('edmecemeli'));
		updatecache('mmenus');
		amessage('mecemeedifin', "?entry=mmenus&action=mmenusedit");
	}
}elseif($action == 'mmenudetail' && $mnid){
	if(!($mmenu = $db->fetch_one("SELECT * FROM {$tblprefix}mmenus WHERE mnid='$mnid'"))) amessage('oosemmit');
	if(!submitcheck('bmmenudetail')){
		tabheader(lang('memcenmeitedet'),'mmenudetail',"?entry=mmenus&action=mmenudetail&mnid=$mnid");
		$mtidsarr = array();
		$query = $db->query("SELECT * FROM {$tblprefix}mmtypes ORDER BY vieworder,mtid");
		while($row = $db->fetch_array($query)){
			$mtidsarr[$row['mtid']] = $row['title'];
		}
		trbasic(lang('belongcocl'),'mmenunew[mtid]',makeoption($mtidsarr,$mmenu['mtid']),'select');
		trbasic(lang('menuitemcname'),'mmenunew[title]',$mmenu['title'],'text');
		trbasic(lang('menuitemurl'),'mmenunew[url]',$mmenu['url'],'btext');
//		trbasic(lang('menuitemurl'),$mmenu['issys'] ? '' : 'mmenunew[url]',$mmenu['url'],$mmenu['issys'] ? '' : 'btext');
		trbasic(lang('beluseval'),'mmenunew[pmid]',makeoption(pmidsarr('menu'),$mmenu['pmid']),'select');
		trbasic(lang('meniteord'),'mmenunew[vieworder]',$mmenu['vieworder'],'text');
		trbasic(lang('newwin'),'mmenunew[newwin]',$mmenu['newwin'],'radio');
		trbasic(lang('onclick'),'mmenunew[onclick]',$mmenu['onclick'],'btext');
		tabfooter('bmmenudetail');
		a_guide('mmenudetail');
	}else{
		$mmenunew['title'] = trim(strip_tags($mmenunew['title']));
		$mmenunew['url'] = trim(strip_tags($mmenunew['url']));
		$mmenunew['onclick'] = trim($mmenunew['onclick']);
//		$mmenunew['url'] = $mmenu['issys'] ? $mmenu['url'] : trim(strip_tags($mmenunew['url']));
		$mmenunew['vieworder'] = max(0,intval($mmenunew['vieworder']));
		$mmenunew['mtid'] = empty($mmenunew['mtid']) ? 0 : max(0,intval($mmenunew['mtid']));
		(!$mmenunew['title'] || !$mmenunew['url']) && amessage('inmmtiturl');
		!$mmenunew['mtid'] && amessage('poimmebelcoc');
		$db->query("UPDATE {$tblprefix}mmenus SET 
					title='$mmenunew[title]', 
					url='$mmenunew[url]', 
					mtid='$mmenunew[mtid]', 
					pmid='$mmenunew[pmid]', 
					newwin='$mmenunew[newwin]', 
					onclick='$mmenunew[onclick]', 
					vieworder='$mmenunew[vieworder]'
					WHERE mnid='$mnid'");
		adminlog(lang('memcenmeitedet'));
		updatecache('mmenus');
		amessage('menitemodfin', axaction(6,"?entry=mmenus&action=mmenusedit"));
	}
}elseif($action == 'mmtypedel' && $mtid){
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}mmenus WHERE mtid='$mtid'")){
		amessage('mecocoutmetedel', "?entry=mmenus&action=mmenusedit");
	}
	$db->query("DELETE FROM {$tblprefix}mmtypes WHERE mtid='$mtid'");
	adminlog(lang('delmemcenmencoc'));
	updatecache('mmenus');
	amessage('mecocdefi', "?entry=mmenus&action=mmenusedit");
}elseif($action == 'mmenudel' && $mnid){
	$db->query("DELETE FROM {$tblprefix}mmenus WHERE mnid='$mnid'");
	@unlink(M_ROOT."./dynamic/mguides/mguide_$mnid.php");
	adminlog(lang('delmemcenmeite'));
	updatecache('mmenus');
	amessage('menitedelfin', "?entry=mmenus&action=mmenusedit");
}
?>