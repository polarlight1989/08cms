<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('bkconfig') || amessage('no_apermission');
$issub = empty($issub) ? 0 : 1;
$sub_suffix = !$issub ? '' : '&issub=1';
$url_type = 'backarea';include 'urlsarr.inc.php';
if($action == 'mtypeadd'){
	if(!submitcheck('bmtypeadd')){
		tabheader(lang('add_'.($issub ? 'subsite' : 'msite').'_menu_class'),'mtypeadd',"?entry=menus&action=mtypeadd$sub_suffix");
		trbasic(lang('cocname'),'mtypenew[title]','','text');
		trbasic(lang('cocdefurl'),'mtypenew[url]','','btext');
		trbasic(lang('coclassorder'),'mtypenew[vieworder]','','text');
		tabfooter('bmtypeadd');
		a_guide('mtypeadd');
	}else{
		$mtypenew['title'] = trim(strip_tags($mtypenew['title']));
		$mtypenew['url'] = trim(strip_tags($mtypenew['url']));
		$mtypenew['vieworder'] = max(0,intval($mtypenew['vieworder']));
		!$mtypenew['title'] && amessage('inpmecoctit');
		$db->query("INSERT INTO {$tblprefix}mtypes SET 
					title='$mtypenew[title]', 
					url='$mtypenew[url]', 
					issub='$issub', 
					vieworder='$mtypenew[vieworder]'
					");
	
		adminlog(lang('admencoc'));
		updatecache('menus',$issub);
		amessage('memcenmecocaddfin', "?entry=menus&action=menusedit$sub_suffix");
	}
}elseif($action == 'menuadd' && $mtid){
	$mtid = max(0,intval($mtid));
	$mtidsarr = array();
	$query = $db->query("SELECT * FROM {$tblprefix}mtypes WHERE fixed=0 AND issub=$issub ORDER BY vieworder,mtid");
	while($row = $db->fetch_array($query)){
		$mtidsarr[$row['mtid']] = $row['title'];
	}
	if(!submitcheck('bmenuadd')){
		tabheader(lang('add_'.($issub ? 'subsite' : 'msite').'_menu_item'),'menuadd',"?entry=menus&action=menuadd&mtid=$mtid$sub_suffix");
		trbasic(lang('belongcocl'),'menunew[mtid]',makeoption($mtidsarr,$mtid),'select');
		trbasic(lang('menuitemcname'),'menunew[title]','','text');
		trbasic(lang('menuitemurl'),'menunew[url]','','btext');
		trbasic(lang('meniteord'),'menunew[vieworder]','','text');
		tabfooter('bmenuadd');
		a_guide('menuadd');
	}else{
		$menunew['title'] = trim(strip_tags($menunew['title']));
		$menunew['url'] = trim(strip_tags($menunew['url']));
		$menunew['vieworder'] = max(0,intval($menunew['vieworder']));
		(!$menunew['title'] || !$menunew['url']) && amessage('pleinpmetitandurl');
		!$menunew['mtid'] && amessage('pombecoc');
		$db->query("INSERT INTO {$tblprefix}menus SET 
					title='$menunew[title]', 
					url='$menunew[url]', 
					mtid='$menunew[mtid]', 
					issub='$issub', 
					vieworder='$menunew[vieworder]'
					");
	
		adminlog(lang('addbackmenite'));
		updatecache('menus',$issub);
		amessage('memcenmeniteadd', axaction(6,"?entry=menus&action=menusedit$sub_suffix"));
	}
}elseif($action == 'menusedit'){
	url_nav(lang('backareaconfig'),$urlsarr,$issub ? 's' : 'm',10);
	if(!submitcheck('bmenusedit')){
		tabheader(lang(($issub ? 'subsite' : 'msite').'_ba_menu_manager')."&nbsp; &nbsp; >><a href=\"?entry=menus&action=mtypeadd$sub_suffix\">".lang('add_coclass').'</a>','menusedit',"?entry=menus&action=menusedit$sub_suffix",'8');
		trcategory(array(lang('sn'),lang('title'),lang('enable'),lang('order'),lang('add'),lang('edit'),lang('delete')));
		$i = 0;
		$query = $db->query("SELECT * FROM {$tblprefix}mtypes WHERE issub=$issub ORDER BY vieworder,mtid");
		while($mtype = $db->fetch_array($query)){
			$mtid = $mtype['mtid'];
			$i ++;
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\">$i</td>\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"mtypesnew[$mtid][title]\" value=\"$mtype[title]\" size=\"25\"></td>\n".
				"<td class=\"txtC w30\"></td>\n".
				"<td class=\"txtC w40\"><input type=\"text\" name=\"mtypesnew[$mtid][vieworder]\" value=\"$mtype[vieworder]\" size=\"4\"></td>\n".
				"<td class=\"txtC w40\">".($mtype['fixed'] ? '' : "<a href=\"?entry=menus&action=menuadd&mtid=$mtid$sub_suffix\" onclick=\"return floatwin('open_menusedit',this)\">+".lang('menu')."</a>")."</td>\n".
				"<td class=\"txtC w40\">".($mtype['fixed'] ? '-' : ("<a href=\"?entry=menus&action=mtypedetail&mtid=$mtid$sub_suffix\" onclick=\"return floatwin('open_menusedit',this)\">".lang('detail')."</a>"))."</td>\n".
				"<td class=\"txtC w40\">".($mtype['fixed'] ? '-' : ("<a href=\"?entry=menus&action=mtypedel&mtid=$mtid$sub_suffix\">".lang('delete')."</a>"))."</td>\n".
				"</tr>";
			$query1 = $db->query("SELECT * FROM {$tblprefix}menus WHERE mtid='$mtid' AND isbk=0 AND issub=$issub ORDER BY vieworder,mnid");
			while($row = $db->fetch_array($query1)){
				$mnid = $row['mnid'];
				$i ++;
				echo "<tr class=\"txt\">\n".
					"<td class=\"txtC w30\">$i</td>\n".
					"<td class=\"txtL\">&nbsp; &nbsp; &nbsp; &nbsp; <input type=\"text\" name=\"menusnew[$mnid][title]\" value=\"$row[title]\" size=\"25\"></td>\n".
					"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"menusnew[$mnid][available]\" value=\"1\"".($row['available'] ? " checked" : "")."></td>\n".
					"<td class=\"txtC w40\"><input type=\"text\" name=\"menusnew[$mnid][vieworder]\" value=\"$row[vieworder]\" size=\"4\"></td>\n".
					"<td class=\"txtC w40\">-</td>\n".
					"<td class=\"txtC w40\">".($row['fixed'] ? '-' : "<a href=\"?entry=menus&action=menudetail&mnid=$mnid$sub_suffix\" onclick=\"return floatwin('open_menusedit',this)\">".lang('detail')."</a>")."</td>\n".
					"<td class=\"txtC w40\">".($row['fixed'] ? '-' : "<a href=\"?entry=menus&action=menudel&mnid=$mnid$sub_suffix\">".lang('delete')."</a>")."</td>\n".
					"</tr>";
			}
		}
		tabfooter('bmenusedit');
		a_guide('menusedit');
	}else{
		if(!empty($mtypesnew)){
			foreach($mtypesnew as $k => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = empty($v['vieworder']) ? 0 : max(0,intval($v['vieworder']));
				$sqlstr = "vieworder='$v[vieworder]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}mtypes SET $sqlstr WHERE mtid='$k'");
			}
		}
		if(!empty($menusnew)){
			foreach($menusnew as $k => $v){
				$v['title'] = trim(strip_tags($v['title']));
				$v['vieworder'] = max(0,intval($v['vieworder']));
				$v['available'] = empty($v['available']) ? 0 : 1;
				$sqlstr = "vieworder='$v[vieworder]',available='$v[available]'";
				$v['title'] && $sqlstr .= ",title='$v[title]'";
				$db->query("UPDATE {$tblprefix}menus SET $sqlstr WHERE mnid='$k'");
			}
		}
		adminlog(lang('edmenitli'));
		updatecache('menus',$issub);
		amessage('menitedfin', "?entry=menus&action=menusedit$sub_suffix");
	}
}elseif($action == 'mtypedetail' && $mtid){
	if(!($mtype = $db->fetch_one("SELECT * FROM {$tblprefix}mtypes WHERE mtid='$mtid'"))) amessage('choosemecoc');
	if(!submitcheck('bmtypedetail')){
		tabheader(lang('edit_'.($issub ? 'subsite' : 'msite').'_menu_class'),'mtypedetail',"?entry=menus&action=mtypedetail&mtid=$mtid$sub_suffix");
		trbasic(lang('cocname'),'mtypenew[title]',$mtype['title'],'text');
		trbasic(lang('cocdefurl'),'mtypenew[url]',$mtype['url'],'btext');
		trbasic(lang('coclassorder'),'mtypenew[vieworder]',$mtype['vieworder'],'text');
		tabfooter('bmtypedetail');
		a_guide('mtypedetail');
	}else{
		$mtypenew['title'] = trim(strip_tags($mtypenew['title']));
		$mtypenew['url'] = trim(strip_tags($mtypenew['url']));
		$mtypenew['vieworder'] = max(0,intval($mtypenew['vieworder']));
		!$mtypenew['title'] && amessage('inpmecoctit');
		$db->query("UPDATE {$tblprefix}mtypes SET 
					title='$mtypenew[title]', 
					url='$mtypenew[url]', 
					vieworder='$mtypenew[vieworder]'
					WHERE mtid='$mtid'");
	
		adminlog(lang('edimencocdet'));
		updatecache('menus',$issub);
		amessage('mecocmodfin', axaction(6,"?entry=menus&action=menusedit$sub_suffix"));
	}

}elseif($action == 'menudetail' && $mnid){
	if(!($menu = $db->fetch_one("SELECT * FROM {$tblprefix}menus WHERE mnid='$mnid'"))) amessage('choosemeit');
	if(!submitcheck('bmenudetail')){
		tabheader(lang('edit_'.($issub ? 'subsite' : 'msite').'_menu_item'),'menudetail',"?entry=menus&action=menudetail&mnid=$mnid$sub_suffix");
		$mtidsarr = array();
		$query = $db->query("SELECT * FROM {$tblprefix}mtypes WHERE fixed=0 AND issub=$issub ORDER BY vieworder,mtid");
		while($row = $db->fetch_array($query)){
			$mtidsarr[$row['mtid']] = $row['title'];
		}
		trbasic(lang('belongcocl'),'menunew[mtid]',makeoption($mtidsarr,$menu['mtid']),'select');
		trbasic(lang('menuitemcname'),'menunew[title]',$menu['title'],'text');
		trbasic(lang('menuitemurl'),'menunew[url]',$menu['url'],'btext');
		trbasic(lang('meniteord'),'menunew[vieworder]',$menu['vieworder'],'text');
		tabfooter('bmenudetail');
		a_guide('menudetail');
	}else{
		$menunew['title'] = trim(strip_tags($menunew['title']));
		$menunew['url'] = trim(strip_tags($menunew['url']));
		$menunew['vieworder'] = max(0,intval($menunew['vieworder']));
		$menunew['mtid'] = empty($menunew['mtid']) ? 0 : max(0,intval($menunew['mtid']));
		(!$menunew['title'] || !$menunew['url']) && amessage('pleinpmetitandurl');
		!$menunew['mtid'] && amessage('pombecoc');
		$db->query("UPDATE {$tblprefix}menus SET 
					title='$menunew[title]', 
					url='$menunew[url]', 
					mtid='$menunew[mtid]', 
					vieworder='$menunew[vieworder]'
					WHERE mnid='$mnid'");
		adminlog(lang('edimenitdet'));
		updatecache('menus',$issub);
		amessage('menitemodfin', axaction(6,"?entry=menus&action=menusedit$sub_suffix"));
	}
}elseif($action == 'mtypedel' && $mtid){
	if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}menus WHERE mtid='$mtid'")){
		amessage('mecocoutmetedel', "?entry=menus&action=menusedit$sub_suffix");
	}
	$db->query("DELETE FROM {$tblprefix}mtypes WHERE mtid='$mtid' AND fixed='0'");
	adminlog(lang('delmenucoc'));
	updatecache('menus',$issub);
	amessage('mecocdefi', "?entry=menus&action=menusedit$sub_suffix");
}elseif($action == 'menudel' && $mnid){
	$db->query("DELETE FROM {$tblprefix}menus WHERE mnid='$mnid' AND fixed='0'");
	adminlog(lang('delmenuitem'));
	updatecache('menus',$issub);
	amessage('menitedelfin', "?entry=menus&action=menusedit$sub_suffix");
}
?>