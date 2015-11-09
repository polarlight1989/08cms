<?php
define('M_ADMIN', TRUE);
define('NOROBOT', TRUE);
include_once dirname(__FILE__).'/include/general.inc.php';
include_once M_ROOT.'./include/admin.fun.php';
if($sid){
	load_cache('alangs,amsgs,langs,mnlangss');
	$langs = $alangs + $mnlangss;
	$langs = $langs + $mnlangss;
}else{
	load_cache('alangs,amsgs,langs,mnlangs');
	$langs = $alangs + $mnlangs;
	$langs = $langs + $mnlangs;
}
 
$lan_title = '网站管理后台';
$aflag = '';

if(!$memberid || !$curuser->isadmin()){
	$aflag = 'off';
}elseif($adminipaccess && !ipaccess($onlineip, $adminipaccess)){
	$aflag = 'ipdenied';
}else{
	$query = $db->query("SELECT * FROM {$tblprefix}asession WHERE mid='$memberid' AND dateline+3600>'$timestamp'", 'SILENT');
	if($db->error()){
		$db->query("DROP TABLE IF EXISTS {$tblprefix}asession");
		$db->query("CREATE TABLE {$tblprefix}asession (mid mediumint(8) UNSIGNED NOT NULL default '0',
		ip char(15) NOT NULL default '',
		dateline int(10) unsigned NOT NULL default '0',
		errorcount tinyint(1) NOT NULL default '0',
		PRIMARY KEY (mid))".(mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM DEFAULT CHARSET=$dbcharset" : " TYPE=MYISAM"));
		#$aflag = 'recheck';
	}else{
		if($asession = $db->fetch_array($query)){
			if($asession['errorcount'] == -1){
				$db->query("UPDATE {$tblprefix}asession SET dateline='$timestamp' WHERE mid='$memberid'", 'UNBUFFERED');
				$aflag = 'on';
			}elseif($asession['errorcount'] <= 3){
				#$aflag = 'recheck';
			}else{
				$aflag = 'off';
			}
		}else{//超时
			$db->query("DELETE FROM {$tblprefix}asession WHERE mid='$memberid' OR dateline+3600<'$timestamp'",'UNBUFFERED');
			$db->query("INSERT INTO {$tblprefix}asession (mid, ip, dateline, errorcount) VALUES ('$memberid', '$onlineip', '$timestamp', '0')",'SILENT');
			#$aflag = 'recheck';
		}
	}
}
 
if($aflag == 'off'){
	if($admin_mname && $admin_password){
 
		$md5_password = md5(md5($admin_password));
		 
		$curuser->activeuserbyname($admin_mname);
		if($curuser->info['mid'] && ( $curuser->info['password'] == $md5_password)){
			$curuser->updatefield('lastvisit', $timestamp);
			$curuser->updatefield('lastip', $onlineip);
			$curuser->updatedb();
			$memberid = $curuser->info['mid'];
			msetcookie('userauth', authcode("$md5_password\t".$curuser->info['mid'],'ENCODE'),$expires);
			echo "<script> location.reload();</script>";
			exit;
		}else{
			login_msg('账号/密码错误','','error');
		}
		print_r($curuser);
		
		exit;
	}
	login_msg('','','error');
	
	 
}elseif($aflag == 'ipdenied'){
	login_msg(lang('backarea_ip_forbid'),'','error');
}elseif($aflag == 'recheck'){
	if(empty($admin_password) || md5(md5($admin_password)) != $curuser->info['password'] || !regcode_pass('admin',empty($regcode) ? '' : trim($regcode))){
		if(!empty($admin_password) || !empty($regcode)){
			$db->query("UPDATE {$tblprefix}asession SET errorcount=errorcount+1 WHERE mid='$memberid'");
		}
		login_msg('','','login');
	} else{
		$db->query("UPDATE {$tblprefix}asession SET errorcount='-1' WHERE mid='$memberid'");
		login_msg(lang('admin_login_finish'),'?'.$_SERVER['QUERY_STRING'].'');
		if(!empty($url_forward)){
			echo "<meta http-equiv=refresh content=\"0;URL=$url_forward\">";
			exit;
		}
	}
}
include_once M_ROOT.'./include/cache.fun.php';
load_cache('usednames,fcatalogs,mchannels');
load_cache('catalogs',$sid);

load_cache($sid ? 'mnmenuss,mnheaderss' : 'mnmenus,mnheaders');
$a_menus = $sid ? $mnmenuss : $mnmenus;
$a_mheaders = $sid ? $mnheaderss : $mnheaders;
unset($mnmenus,$mnheaders,$mnmenuss,$mnheaderss);
if($curuser->info['isfounder'] && empty($foundercontent)) unset($a_menus[1],$a_menus[2],$a_menus[3]);

$a_funcs = $a_caids = $a_mchids = $a_fcaids = $a_cuids = $a_mcuids = $a_matids = $a_vmchids = $curuser->info['isfounder'] ? array('-1') : array();;
$a_vcaids = $a_ucaids = $a_vfcaids = $a_ufcaids = $a_checks = ($curuser->info['isfounder'] && !empty($foundercontent)) ? array('-1') : array();

if(!$curuser->info['isfounder']){
	load_cache('amconfigs');
	$ausergroup = read_cache('usergroup',2,$curuser->info['grouptype2']);
	if(($a_amcid = @$ausergroup['amcids'][$sid ? $sid : 'm']) && ($a_amconfig = @$amconfigs[$a_amcid])){
		$menuids = explode(',',$a_amconfig['menus']);
		foreach($a_menus as $k0 => $v0){
			foreach($v0 as $k1 => $v1) if(!in_array($k1,$menuids)) unset($a_menus[$k0][$k1]);
			if(empty($a_menus[$k0])) unset($a_menus[$k0],$a_mheaders[$k0]);
		}
		foreach(array('funcs','caids','mchids','fcaids','cuids','mcuids','matids','checks',) as $var) ${'a_'.$var} = $a_amconfig[$var] ?  explode(',',$a_amconfig[$var]) : array();
		if($a_amconfig['abcustom'] || !in_array('-1',$a_caids)){
			if($a_amconfig['abcustom']){
				$a_ucaids = array_keys($a_amconfig['anodes']);
				if(!in_array('-1',$a_caids)) $a_ucaids = array_intersect($a_ucaids,array(0) + $a_caids);
			}else $a_ucaids = array(0) + $a_caids;
		
			$a_vcaids = array();
			foreach($a_ucaids as $v) $a_vcaids = array_merge($a_vcaids,!$v ? array($v) : pccidsarr($v,0,1));//所有显示栏目的上级栏目需要显示出来
			$a_vcaids = array_unique($a_vcaids);
			//if(!in_array('-1',$a_caids)) $a_ucaids = array_intersect($a_caids,$a_ucaids);
		}else $a_ucaids = $a_vcaids = array(-1,0);
		
		if($a_amconfig['fbcustom'] || !in_array('-1',$a_fcaids)){
			if($a_amconfig['fbcustom']){
				$a_ufcaids = array_keys($a_amconfig['fnodes']);
				if(!in_array('-1',$a_fcaids)) $a_ufcaids = array_intersect($a_ufcaids,$a_fcaids);
			}else $a_ufcaids = $a_fcaids; 
			$a_vfcaids = array();
			foreach($a_ufcaids as $v){
				$a_vfcaids[] = $v;
				if(@$fcatalogs[$v]['pid']) $a_vfcaids[] = $fcatalogs[$v]['pid'];//所有显示栏目的上级栏目需要显示出来
			}
			$a_vfcaids = array_unique($a_vfcaids);
			//if(!in_array('-1',$a_fcaids)) $a_ufcaids = array_intersect($a_fcaids,$a_ufcaids);
		}else $a_ufcaids = $a_vfcaids = array('-1');

		if($a_amconfig['mbcustom'] || !in_array('-1',$a_mchids)){
			if($a_amconfig['mbcustom']){
				$a_vmchids = array_keys($a_amconfig['mnodes']);
				if(!in_array('-1',$a_mchids)) $a_vmchids = array_intersect($a_vmchids,$a_mchids + array(0));
			}else $a_vmchids = $a_mchids + array(0);
		}else $a_vmchids = array(-1,0);
	}else{
		$msgstr = lang('nobackareapm',$sid ? $subsites[$sid]['sitename'] : lang('msite')).'<br><br>'.lang('rechoosebackarea').'<br><br>';
		$sidsarr = array('0' => lang('msite'));
		foreach($subsites as $k => $v) $sidsarr[$k] = $v['sitename'];
		foreach($sidsarr as $k => $v) $msgstr .= ">><a href=\"?sid=$k\">$v</a>&nbsp; ";
		login_msg($msgstr);
	
	}
	unset($ausergroup,$amconfigs,$a_amconfig);
}

if(empty($entry) || isset($isframe)){
	parse_str($_SERVER['QUERY_STRING'],$getarr);
	$extra = $and = '';
	foreach($getarr as $key => $value){
		if(!in_array($key, array('isframe'))){
			@$extra .= $and.$key.'='.rawurlencode($value);
			$and = '&';
		}
	}
	$extra = $extra && !empty($entry) ? $extra : "entry=home$param_suffix";

	//常用链接pmbypmids
	load_cache('usualurls');
	$usualurlstr = '';
	foreach($usualurls as $v){
		if($curuser->pmbypmids('menu',$v['pmid'])){
			$sidarr = explode(',',$v['sids']);
			$invalid = $sid ? in_array($sid,$sidarr) : in_array('m',$sidarr);
			if(!$v['ismc'] && $v['available'] && !$invalid) $usualurlstr .= "<li><em><a href=\"$v[url]&isframe=1$param_suffix\">$v[title]</a></em></li>";
		}
	}
	
	//头部菜单
	$headstr = $submenu = '';
	foreach($a_mheaders as $k => $v){
		if(empty($a_menus[$k]) || (!$a_vcaids && in_array($k,array(1,2))) || (!$a_vfcaids && $k == 3) || (!$a_vmchids && $k == 4)) continue;
		$headstr .= "<li><a id=\"mainmenu_$k\" href=\"javascript:\" onclick=\"setMenu($k);return false\">".lang('menutype_'.$k)."</a></li>";
		$submenu .= "\n		<ul id=\"submenus_$k\" style=\"display:none\">";
		foreach($a_menus[$k] as $x => $v){
			$submenu .= "\n			<li><em><a href=\"" . ($v == '#' ? 'javascript:' : ($v . $param_suffix)) . "\">" . lang('menuitem_'.$x) . "</a></em></li>";
		}
		$submenu .= "\n		</ul>";
	}
	$submenu .= "\n";

	//栏目//在这里只是使用显示方案的设置
	$catastr='';
	if($a_vcaids){
		$ncatalogs = array(0 => array('title' => lang('all_catalog'),'level' => 0)) + $catalogs;
		$i=0;$space='			';
		foreach($ncatalogs as $caid => $catalog){
			if(!array_intersect(array('-1',$caid),$a_vcaids)) continue;
			$editstr = array_intersect(array('-1',$caid),$a_ucaids) ? "<em><a href=\"javascript:\" onclick=\"get_operate($caid)\">$catalog[title]</a></em>" : "<em>$catalog[title]</em>";
			if($i<$catalog['level']){
				$i++;
				$catastr.="<ul>\n$space	<li>$editstr";
				$space.='	';
			}else{
				if($i>$catalog['level']){
					while($i-->$catalog['level']){
						$space=substr($space,0,$i+3);
						$catastr.="</li></ul>\n$space";
					}
					$i++;
				}
				$catastr.="</li>\n$space<li>$editstr";
			}
		}
		if($i>0){
			while($i-->0){
				$space=substr($space,0,$i+3);
				$catastr.="</li></ul>\n$space";
			}
		}
		$catastr = substr($catastr,5)."</li>\n".substr($space,0,-1);
	}
	unset($a_vcaids,$a_ucaids,$ncatalogs);
	
	//插件分类
	$f_catastr = '';
	if($a_vfcaids){
		$f_pid = 0;
		foreach($fcatalogs as $k => $v){
			if(!array_intersect(array('-1',$k),$a_vfcaids)) continue;
			$editstr = array_intersect(array('-1',$k),$a_ufcaids) ? "<em><a href=\"javascript:\" onclick=\"get_operate($k,1)\">$v[title]</a></em>" : "<em>$v[title]</em>";
			if($f_pid){
				if(empty($v['pid'])){//下一个顶级分类
					$f_pid = 0;
					$space=substr($space,0,-1);
					$f_catastr .= "</li></ul>\n$space";
				}
				$f_catastr.="</li>\n$space<li>$editstr";
			}else{
				if(!empty($v['pid'])){//子分类开始
					$f_pid = $v['pid'];
					$f_catastr.="<ul>\n$space	<li>$editstr";
					$space.='	';
				}else{
					$f_catastr.="</li>\n$space<li>$editstr";
				}
			}
		}
		if($f_pid){
			$f_pid = 0;
			$space=substr($space,0,-1);
			$f_catastr .= "</li></ul>\n$space";
		}
		$f_catastr = substr($f_catastr,5)."</li>\n".substr($space,0,-1);
	}

	unset($a_vfcaids,$a_ufcaids);

	//会员模型分类
	$m_chidstr = '';
	if($a_vmchids){
		$mchidsarr = array(0 => lang('allmember'));
		foreach($mchannels as $k => $v) $mchidsarr[$k] = $v['cname'];
		foreach($mchidsarr as $k => $v) if(array_intersect(array(-1,$k),$a_vmchids)) $m_chidstr .= "<li><em><a href=\"javascript:\" onclick=\"get_operate($k,2)\">$v</a></em></li>";
	}

	//站点列表
	$sidarr = array(0 => lang('msite'));
	foreach($subsites as $k => $v) $sidarr[$k] = $v['sitename'];
	$sidoptions = '';
	foreach($sidarr as $k => $v) $sidoptions .= "<option value=\"$k\"".($k == $sid ? ' selected' : '').">$v</option>";
	
	$foundernobest = empty($curuser->info['isfounder']) ? '' : lang('foundernobest');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>" />
<title><?=$lan_title?></title>
<link href="images/admina/index.css" rel="stylesheet" type="text/css" />
<link href="images/admina/contentsAdmin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">var IXDIR = 'images/admina/', site_id = <?=$sid?>, charset = '<?=$mcharset?>',allowfloatwin = '<?=$GLOBALS['aallowfloatwin']?>',floatwidth='<?=$GLOBALS['afloatwinwidth']?>',floatheight='<?=$GLOBALS['afloatwinheight']?>',floatminheight=90</script>
<script type="text/javascript" src="include/js/langs.js"></script>
<script type="text/javascript" src="include/js/common.js"></script>
<script type="text/javascript" src="include/js/aframe.js"></script>
<script type="text/javascript" src="include/js/admina.js"></script>
<script type="text/javascript" src="include/js/floatwin.js"></script>
</head>

<body scroll="no" style="overflow-y:hidden;">
<div id="append_parent"></div>
<div id="cpmap_menu" class="custom" style="display:none">
	<div class="cmain" id="cmain"></div>
	<div class="cfixbd"></div>
</div>
<table cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#f7f7f7">
<tr>
	<td colspan="2" height="117">
		<div class="area">
			<div id="header">
				<div class="left">
					<div class="logo">
						<img src="images/admina/logo.png" width="165" height="75" title="" alt="" />
					</div>
					<div class="version">V<?=$cms_version?></div>
				</div>
				<div class="right">
					<div class="headTxt">
						<h1><font><?=lang('awelcome',$curuser->info['mname'])?><?=$foundernobest?></font>[ <a href="?entry=home&isframe=1"><?=lang('adminindex')?></a> | <a href="<?=$cms_abs?>" target="_blank"><?=lang('websiteindex')?></a> | <a href="adminm.php" target="_blank"><?=lang('membercenter1')?></a> | <a href="<?=$mspaceurl?>index.php?mid=<?=$memberid?>" target="_blank"><?=lang('space')?></a> | <a href="?entry=logout"><?=lang('logoutadmin')?></a> ]</h1>
						<div class="commonActions">
							<h2><a id="cpmap" href="javascript:" onclick="showMap()"><img src="images/admina/sitmap0.png" width="91" height="22" alt="<?=lang('ba_map')?>" /></a></h2>
							<ul><?=$usualurlstr?></ul>
						</div>
					</div>
				</div>
			</div>
			<div id="globalNav">
				<h1><select id="site" onchange="setsite(this)"><?=$sidoptions?></select></h1>
				<ul id="topmenu"><?=$headstr?></ul>
				<div onclick="click_setscreen(this)" style="float:right; width:35px; margin: 7px 2px; height:25px; background:url(images/admina/arrow.jpg) -35px 0;" title="<?=lang('keydowm_setscreen')?>"></div>
			</div>
		</div>
	</td>
</tr>
<tr>
	<td valign="top" width="169" style=" padding-top:9px;"><div id="leftmenu" class="col2">
		<ul id="urlmenus"><?=$usualurlstr?></ul>
		<?=$submenu?>
		<ul id="catamenu" class="treeMenu" style="display:none"><?=$catastr?></ul>
		<ul id="plugmenu" class="treeMenu" style="display:none"><?=$f_catastr?></ul>
		<ul id="clubmenu" class="treeMenu" style="display:none"><?=$m_chidstr?></ul>
		<div id="operateitem" class="operateMenu" style="display:none"></div>
	</div>
	<script type="text/javascript">initaMenu($id('urlmenus'));initaMenu($id('catamenu'), 'bktm_cookie');initCpMap('cmain','topmenu');
var aframe_topheader_height = 117;
window.onload = function(){
	var p = $id('leftmenu'), mids = [$id('catamenu'),$id('plugmenu'),$id('clubmenu')], d, i;
	window.onresize = function(){
		var h = (document.documentElement ? document.documentElement.clientHeight : document.body.clientHeight) - aframe_topheader_height - 9;
		$id('main').style.height = h + 9 + 'px';
		p.style.height = h + 'px';
	}
	if(_ua.ie){
		for(i = 0; i < mids.length; i++){
			d = document.createElement('DIV');
			p.removeChild(mids[i]);
			p.appendChild(d);
			d.appendChild(mids[i]);
		}
		d = $id('operateitem');
		p.removeChild(d);
		p.appendChild(d);
	}
	window.onresize();
	toggleMenu(location.search);
}
</script>
</td><td valign="top" width="100%" style="min-width:753px;"><iframe onload="main_onload(this)" frameborder="0" id="main" name="main" src="?<?=$extra?>" scrolling="yes" width="100%" height="100%" style="overflow:visible;"></iframe></td>
	</tr>
</table>
</body>
</html>
<?
}else{
	if($entry == 'logout'){
		$db->query("DELETE FROM {$tblprefix}asession WHERE mid='$memberid'");
		login_msg(lang('ba_logout_finish'),$cms_abs);
	}elseif($entry){
		$actionid = $entry.(empty($action) ? '' : '_'.$action);
		include_once M_ROOT.'./admina/'.$entry.'.inc.php';
		afooter();
		if(!empty($cms_up_url)) echo '<script type="text/javascript" src="'.$cms_up_url.'"></script>';
	}
}
mexit();

function login_msg($message,$url_forward = '',$msgtype = 'message'){
	global $memberid,$curuser,$entry,$lan_title,$cms_regcode,$cms_abs,$mcharset,$param_suffix,$inajax,$infloat,$handlekey,$ajaxtarget;
	$url_forward .= $url_forward ? $param_suffix : '';
	
	$entry = mhtmlspecialchars($entry);
	
	$target = $infloat ? ' onclick="floatwin(\'close_'.$handlekey.'\');return floatwin(\'open_login\',this)"' : '';
	if($msgtype == 'message'){
		
		$message = '<tr><td align="center" colspan="2"><br><br>'.$message;
		if($infloat)
			$message .= '<script reload="1">setTimeout("floatwin(\'close_'.$handlekey.'\')", 1250);floatwin(\'closeparent_'.$handlekey.'\')</script><br><br><br></tr>';
		elseif($url_forward){
			if(preg_match('/[?&]entry=logout\b/i', $url_forward))$url_forward = '?entry=home';
			$message .= "<br><br><a href=\"$url_forward\">".lang('clickhere')."</a>";
			$message .= "<script reload=\"1\">setTimeout(\"redirect('$url_forward');\", 1250);</script><br><br></td></tr>";
		}else{
			$message .= '<br><br><br></tr>';
		}
	}elseif($msgtype == 'error'){
		#$message = '<tr><td align="center" colspan="2"><br>'.lang('cur_member').'&nbsp; &nbsp; '.$curuser->info['mname'].'<br><br>'.$message.
#		$message = '<tr><td align="center" colspan="2"><br>'.$message.
		#'<br><br>'.($memberid ? '<a href="login.php?action=logout">>>'.lang('logout_member').'</a>' : '<a href="login.php?action=login"'.$target.'>>>'.lang('login_member').'</a>').
		#'&nbsp;&nbsp;<a href="'.$cms_abs.'">>>'.lang('goback_index').'</a><br><br></td></tr>';
		$extra = isset($entry) && empty($isframe) && $entry != 'logout' ? '?isframe=1&'.$_SERVER['QUERY_STRING'] : (in_array($entry, array('header', 'menu', 'logout')) ? '' : '?'.$_SERVER['QUERY_STRING']);
		$message = '<tr><td><form method="post" name="login" action="'.$extra.'"'.($infloat ? " onsubmit=\"return ajaxform(this)\"" : '').'>'.
			'<input type="hidden" name="isframe" value="1">'.
			'<input type="hidden" name="url_forward" value="'.$url_forward.'">'.
			'<table width="100%" border="0" cellpadding="0" cellspacing="0">'.
			'<tr class="txt"><td class="txtC w80">'.lang('admin_account').'</td>'.
			'<td class="txt txtL"><input type="text" name="admin_mname" size="25"></td></tr>'.
			'<tr class="txt"><td class="txtC w80">'.lang('login_pwd').'</td>'.
			'<td class="txt txtL"><input type="password" name="admin_password" size="25"></td></tr>';
			if($cms_regcode && in_array('admin',explode(',',$cms_regcode))){
				$message .= '<tr class="txt"><td class="txtC w80">'.lang('regcode').'</td>'.
				'<td class="txt txtL"><input type="text" name="regcode" id="regcode" size="4" maxlength="4">&nbsp;&nbsp;'.
				'<img src="tools/regcode.php" style="vertical-align: middle;cursor:pointer;" onClick="this.src=\'tools/regcode.php\'"></td></tr>';
			}
			$message .= '<tr class="txtcenter"><td colspan="2"><input type="submit" class="btn" value="'.lang('submit').'" /></td></tr></table></form></td></tr>';
	}elseif($msgtype == 'login'){
		if(substr($handlekey,0,8)=='new_new_'){
			$message = '<script reload="1">setTimeout("floatwin(\'close_'.$handlekey.'\')", 1250)</script>'.
			'<td class="txt txtC">'.lang('passerror').'</td></tr>';
		}else{
			$extra = isset($entry) && empty($isframe) && $entry != 'logout' ? '?isframe=1&'.$_SERVER['QUERY_STRING'] : (in_array($entry, array('header', 'menu', 'logout')) ? '' : '?'.$_SERVER['QUERY_STRING']);
			$message = '<tr><td><form method="post" name="login" action="'.$extra.'"'.($infloat ? " onsubmit=\"return ajaxform(this)\"" : '').'>'.
			'<input type="hidden" name="isframe" value="1">'.
			'<input type="hidden" name="url_forward" value="'.$url_forward.'">'.
			'<table width="100%" border="0" cellpadding="0" cellspacing="0">'.
			'<tr class="txt"><td class="txtC w80">'.lang('admin_account').'</td>'.
			'<td class="txt txtL"><input type="text" name="admin_mname" size="25"></td></tr>'.
			'<tr class="txt"><td class="txtC w80">'.lang('login_pwd').'</td>'.
			'<td class="txt txtL"><input type="password" name="admin_password" size="25"></td></tr>';
			if($cms_regcode && in_array('admin',explode(',',$cms_regcode))){
				$message .= '<tr class="txt"><td class="txtC w80">'.lang('regcode').'</td>'.
				'<td class="txt txtL"><input type="text" name="regcode" id="regcode" size="4" maxlength="4">&nbsp;&nbsp;'.
				'<img src="tools/regcode.php" style="vertical-align: middle;cursor:pointer;" onClick="this.src=\'tools/regcode.php\'"></td></tr>';
			}
			$message .= '<tr class="txtcenter"><td colspan="2"><input type="submit" class="btn" value="'.lang('submit').'" /></td></tr></table></form></td></tr>';
		}
	}else{
		
		if(substr($handlekey,0,8)=='new_new_'){
			$message = '<script reload="1">setTimeout("floatwin(\'close_'.$handlekey.'\')", 1250)</script>'.
			'<td class="txt txtC">'.lang('passerror').'</td></tr>';
		}else{
			$extra = isset($entry) && empty($isframe) && $entry != 'logout' ? '?isframe=1&'.$_SERVER['QUERY_STRING'] : (in_array($entry, array('header', 'menu', 'logout')) ? '' : '?'.$_SERVER['QUERY_STRING']);
			$message = '<tr><td><form method="post" name="login" action="'.$extra.'"'.($infloat ? " onsubmit=\"return ajaxform(this)\"" : '').'>'.
			'<input type="hidden" name="isframe" value="1">'.
			'<input type="hidden" name="url_forward" value="'.$url_forward.'">'.
			'<table width="100%" border="0" cellpadding="0" cellspacing="0">'.
			'<tr class="txt"><td class="txtC w80">'.lang('admin_account').'</td>'.
			'<td class="txt txtL">'.$curuser->info['mname'].'&nbsp; >><a href=\'login.php?action=logout\'>'.lang('exit').'</a></td></tr>'.
			'<tr class="txt"><td class="txtC w80">'.lang('login_pwd').'</td>'.
			'<td class="txt txtL"><input type="password" name="admin_password" size="15"></td></tr>';
			if($cms_regcode && in_array('admin',explode(',',$cms_regcode))){
				$message .= '<tr class="txt"><td class="txtC w80">'.lang('regcode').'</td>'.
				'<td class="txt txtL"><input type="text" name="regcode" id="regcode" size="4" maxlength="4">&nbsp;&nbsp;'.
				'<img src="tools/regcode.php" style="vertical-align: middle;cursor:pointer;" onClick="this.src=\'tools/regcode.php\'"></td></tr>';
			}
			$message .= '<tr class="txtcenter"><td colspan="2"><input type="submit" class="btn" value="'.lang('submit').'" /></td></tr></table></form></td></tr>';
		}
	}
	if($infloat){
		aheader();
	}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$lan_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$mcharset?>">
<link rel="stylesheet" rev="stylesheet" href="./images/admina/contentsAdmin.css" type="text/css" media="all">
<script type="text/javascript">function redirect(url){top.location.replace(url)}</script>
</head>
<body>
<?php }?>
<div style="margin:0 auto;margin-top:<?=($inajax?0:200)?>px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb"><tr><td align="center">
<table width="400" border="0" cellpadding="8" cellspacing="0"<?=($inajax?'':' class="tabmain"')?>>
<tr style="text-align:center; text-indent:0;"><td colspan="2"><div class="conlist1 bdbot fB"><?=$lan_title?></div></td></tr>
<?=$message?>
</table>
</td></tr></table>
</div>
<?
if($infloat){
	afooter();
}else{?>
</body>
</html>
<?
}
mexit();
}
?>