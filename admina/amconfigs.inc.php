<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('bkconfig') || amessage('no_apermission');
$url_type = 'backarea';include 'urlsarr.inc.php';
load_cache('channels,fchannels,mchannels,catalogs,fcatalogs,cotypes,mtpls,aurls');
$amconfigs = reload_cache('amconfigs');
if($action == 'amconfigadd'){
	if(!submitcheck('bamconfigadd')){
		tabheader(lang('adminbapadd'),'amconfigadd','?entry=amconfigs&action=amconfigadd');
		trbasic(lang('adminbapname'),'amconfigadd[cname]');
		$sidsarr = array(0 => lang('msite'));
		foreach($subsites as $k => $v) $sidsarr[$k] = $v['sitename'];
		trbasic(lang('amconfigbelongsid'),'amconfigadd[sid]',makeoption($sidsarr),'select');
		tabfooter('bamconfigadd',lang('add'));
		a_guide('amconfigadd');
	}else{
		if(empty($amconfigadd['cname'])){
			amessage('bapdatamiss', '?entry=amconfigs&action=amconfigsedit');
		}
		$db->query("INSERT INTO {$tblprefix}amconfigs SET cname='$amconfigadd[cname]',sid='$amconfigadd[sid]'");
		adminlog(lang('addadminbap'));
		updatecache('amconfigs');
		amessage('bapaddfinish', '?entry=amconfigs&action=amconfigsedit');
	}
}elseif($action == 'amconfigsedit'){
	url_nav(lang('backareaconfig'),$urlsarr,'config',10);
	if(!submitcheck('bamconfigsedit')){
		tabheader(lang('adminbapmanager').'&nbsp; &nbsp; '."<a href=\"?entry=amconfigs&action=amconfigadd\">".lang('add')."</a>",'amconfigsedit','?entry=amconfigs&action=amconfigsedit',4);
		trcategory(array(lang('delete'),lang('projectname'),lang('belongsubsite'),lang('edit')));
		$sidsarr = array(0 => lang('msite'));
		foreach($subsites as $k => $v) $sidsarr[$k] = $v['sitename'];
		foreach($amconfigs as $amcid => $amconfig){
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$amcid]\" value=\"$amcid\">\n".
				"<td class=\"txtL\"><input type=\"text\" name=\"amconfigsnew[$amcid][cname]\" value=\"".mhtmlspecialchars($amconfig['cname'])."\" size=\"30\" maxlength=\"30\"></td>\n".
				"<td class=\"txtC w100\">".(empty($sidsarr[$amconfig['sid']]) ? lang('unknown') : $sidsarr[$amconfig['sid']])."</td>\n".
				"<td class=\"txtC w50\"><a href=\"?entry=amconfigs&action=amconfigdetail&amcid=$amcid\" onclick=\"return floatwin('open_amconfigsedit',this)\">[".lang('detail')."]</a></td>\n".
				"</tr>";
		}
		tabfooter('bamconfigsedit');
		a_guide('amconfigsedit');

	}else{
		if(!empty($delete)){
			foreach($delete as $amcid){
				if(empty($amconfigs[$amcid]['issystem'])){
					$db->query("DELETE FROM {$tblprefix}amconfigs WHERE amcid='$amcid'");
					unset($amconfigsnew[$amcid]);
				}
			}
		}
		if(!empty($amconfigsnew)){
			foreach($amconfigsnew as $amcid => $amconfignew){
				$amconfignew['cname'] = empty($amconfignew['cname']) ? $amconfigs[$amcid]['cname'] : $amconfignew['cname'];
				if($amconfignew['cname'] != $amconfigs[$amcid]['cname']){
					$db->query("UPDATE {$tblprefix}amconfigs SET 
								cname='$amconfignew[cname]'
								WHERE amcid='$amcid'");
				}
			}
		}
		adminlog(lang('editbaprojectlist'));
		updatecache('amconfigs');
		amessage('bapmodifyfinish', "?entry=amconfigs&action=amconfigsedit");
	}

}elseif($action == 'amconfigdetail' && !empty($amcid)){
	empty($amconfigs[$amcid]) && amessage('chooseadminbap');
	$amconfig = $amconfigs[$amcid];
	if($amconfig['sid'] && empty($subsites[$amconfig['sid']])) amessage('nosite');
	$menus = reload_cache($amconfig['sid'] ? 'mnmenuss' : 'mnmenus');
	$mnlangs = reload_cache($amconfig['sid'] ? 'mnlangss' : 'mnlangs');
	$langs += $mnlangs;
	if(!submitcheck('bamconfigdetail')){
		$url_type = 'amconfigdetail';include 'urlsarr.inc.php';
		url_nav('['.$amconfigs[$amcid]['cname'].']&nbsp;'.lang('amconfig'),$urlsarr,'base');
		foreach(array('menus','funcs','caids','fcaids','mchids','cuids','mcuids','matids','checks',) as $var) $amconfig[$var] = array_filter(explode(',',$amconfig[$var]));
		tabheader(lang('msitebasshieldmenu').'&nbsp; &nbsp; &nbsp; <input class="checkbox" type="checkbox" name="mchkall" onclick="checkall(this.form,\'menusnew\',\'mchkall\')">'.lang('selectall'),'amconfigdetail','?entry=amconfigs&action=amconfigdetail&amcid='.$amcid,6);
		foreach($menus as $k1 => $v1){
			$menusarr = array();
			foreach($v1 as $k2 => $v2) $menusarr[$k2] = lang('menuitem_'.$k2);
			trbasic(lang('menutype_'.$k1),'',makecheckbox("menusnew[]",$menusarr,empty($amconfig['menus']) ? array() : $amconfig['menus'],5),'');
		}	
		tabfooter();
		tabheader(lang('allowafuncs').'&nbsp; &nbsp; &nbsp; <input class="checkbox" type="checkbox" name="fchkall" onclick="checkall(this.form,\'funcsnew\',\'fchkall\')">'.lang('selectall'));
		$funcsarr = array(
			lang('contentmanage') => array(
				'normal' => lang('conventcontent'),
				'commu' => lang('docintercontent'),
				'static' => lang('staticpage'),
				'orders' => lang('goodsorder'),
				'farchive' => lang('plugincontent'),
			),
			lang('member_admin') => array(
				'member' => lang('member_admin'),
				'amember' => lang('managemmembermanage'),
				'mcommu' => lang('memberintercontent'),
				'marchive' => lang('marchive'),
				'mtrans' => lang('membertypechange'),
				'utrans' => lang('utrans'),
				'extract' => lang('extract').lang('admin'),
				'save' => lang('currencyinout'),
				'pay' => lang('cashsavadmin'),
				'repu' => lang('repurelate'),
			),
			lang('otherapermission') => array(
				'gather' => lang('collectmanagement'),
				'database' => lang('aboutdatabase'),
				'sitemap' => lang('sitemap'),
				'record' => lang('sitelogs'),
				'other' => lang('otherapermission'),
			),
			lang('systemstructure') => array(
				'currency' => lang('integralset'),
				'mchannel' => lang('mchannel'),
				'grouptype' => lang('membergroupset'),
				'matype' => lang('matype'),
				'cfmcommu' => lang('memberinterconfig'),
				'cftrans' => lang('memberchange'),
				'channel' => lang('achannel'),
				'catalog' => lang('catalog_manager'),
				'cotype' => lang('classmanage'),
				'cnode' => lang('cnodeadmin'),
				'mcnode' => lang('mcnodeadmin'),
				'cfcommu' => lang('docinterconfig'),
				'freeinfo' => lang('pluginframework'),
				'subsite' => lang('subsitemanager'),
				'tpl' => lang('tpl_set'),
			),
			lang('systemset') => array(
				'webparam' => lang('webparam'),
				'bkconfig' => lang('backareaconfig'),
				'mcconfig' => lang('mcenterconfig'),
				'permission' => lang('content_permissions'),
				'domain' => lang('domain_admin'),
				'lang' => lang('lanpackmanage'),
			),
		);

		foreach($funcsarr as $k => $v){
			trbasic($k,'',makecheckbox("funcsnew[]",$v,empty($amconfig['funcs']) ? array() : $amconfig['funcs'],6),'');
		}	
		tabfooter();

		$caidsarr = $cuidsarr = $mcuidsarr = $matidsarr = $fcaidsarr = $mchidsarr = array('-1' => '<b>'.lang('all').'</b>');
		tabheader(lang('cataapermission').'&nbsp; &nbsp; &nbsp; <input class="checkbox" type="checkbox" name="cachkall" onclick="checkall(this.form,\'caid0snew\',\'cachkall\')">'.lang('selectall'));
		load_cache('catalogs',$amconfig['sid']);
		foreach($catalogs as $k => $v) $caidsarr[$k] = $v['title'].'('.$v['level'].')';
		echo "<tr><td class=\"txt txtleft\">".makecheckbox("caid0snew[]",$caidsarr,empty($amconfig['caids']) ? array() : $amconfig['caids'])."</td><tr>";
		#echo "<tr><td class=\"txt txtleft\">".makecheckbox("caid0snew[]",$caidsarr,empty($amconfig['caids']) ? array() : $amconfig['caids'],$cnprow)."</td><tr>";		tabfooter(); //多了"$cnprow"在页面上显示会换行！
		tabfooter();
		
		tabheader(lang('otherapermission'));
		load_cache('commus,matypes,mcommus');
		$checkarr[-1] = lang('check_4');
		for($i = 1;$i < 4;$i ++) $checkarr[$i] = lang('check_'.$i);
		trbasic(lang('checkpm'),'',makecheckbox("checksnew[]",$checkarr,empty($amconfig['checks']) ? array() : $amconfig['checks'],6),'');
		foreach($commus as $k => $v) if(in_array($v['cclass'],array('comment','reply','offer','purchase','answer'))) $cuidsarr[$k] = $v['cname'];
		foreach($mcommus as $k => $v) if(in_array($v['cclass'],array('comment','reply'))) $mcuidsarr[$k] = $v['cname'];
		foreach($matypes as $k => $v) $matidsarr[$k] = $v['cname'];
		$fcaidsarr += fcaidsarr();
		$mchidsarr += mchidsarr();
		trbasic(lang('allowacommu').'<br /><input class="checkbox" type="checkbox" name="xchkall" onclick="checkall(this.form,\'cuid0snew\',\'xchkall\')">'.lang('selectall'),'',makecheckbox('cuid0snew[]',$cuidsarr,empty($amconfig['cuids']) ? array() : $amconfig['cuids'],8,1),'');
		if(!$amconfig['sid']){
			trbasic(lang('allowafcoclass').'<br /><input class="checkbox" type="checkbox" name="fachkall" onclick="checkall(this.form,\'fcaidsnew\',\'fachkall\')">'.lang('selectall'),'',makecheckbox('fcaidsnew[]',$fcaidsarr,empty($amconfig['fcaids']) ? array() : $amconfig['fcaids'],5,1),'');
			trbasic(lang('allowamember').'<br /><input class="checkbox" type="checkbox" name="mcchkall" onclick="checkall(this.form,\'mchidsnew\',\'mcchkall\')">'.lang('selectall'),'',makecheckbox('mchidsnew[]',$mchidsarr,empty($amconfig['mchids']) ? array() : $amconfig['mchids'],8,1),'');
			trbasic(lang('allowamcommu').'<br /><input class="checkbox" type="checkbox" name="ychkall" onclick="checkall(this.form,\'mcuidsnew\',\'ychkall\')">'.lang('selectall'),'',makecheckbox('mcuidsnew[]',$mcuidsarr,empty($amconfig['mcuids']) ? array() : $amconfig['mcuids'],8,1),'');
			trbasic(lang('allowamatype').'<br /><input class="checkbox" type="checkbox" name="zchkall" onclick="checkall(this.form,\'matidsnew\',\'zchkall\')">'.lang('selectall'),'',makecheckbox('matidsnew[]',$matidsarr,empty($amconfig['matids']) ? array() : $amconfig['matids'],8,1),'');
		}
		tabfooter('bamconfigdetail');
		a_guide('amconfigdetail');
	}else{
		$menusnew = empty($menusnew) ? '' : implode(',',$menusnew);
		$funcsnew = empty($funcsnew) ? '' : implode(',',$funcsnew);
		$checksnew = empty($checksnew) ? '' : (in_array('-1',$checksnew) ? '-1' : implode(',',$checksnew));
		foreach(array('caid0snew','fcaidsnew','mchidsnew','cuid0snew','mcuidsnew','matidsnew',) as $var) $$var = empty($$var) ? '' : (in_array('-1',$$var) ? '-1' : implode(',',$$var));
		$db->query("UPDATE {$tblprefix}amconfigs SET 
		menus='$menusnew',
		funcs='$funcsnew',
		checks='$checksnew',
		caids='$caid0snew',
		fcaids='$fcaidsnew',
		cuids='$cuid0snew',
		mcuids='$mcuidsnew',
		matids='$matidsnew',
		mchids='$mchidsnew'
		WHERE amcid='$amcid'");
		adminlog(lang('detailmodifyabap'));
		updatecache('amconfigs');
		amessage('adminbapsetfinish',axaction(2,"?entry=amconfigs&action=amconfigsedit"));
	}
}elseif($action == 'amconfigablock' && !empty($amcid)){
	empty($amconfigs[$amcid]) && amessage('chooseadminbap');
	$amconfig = $amconfigs[$amcid];
	if($amconfig['sid'] && empty($subsites[$amconfig['sid']])) amessage('nosite');
	$anodes = empty($amconfig['anodes']) ? '' : $amconfig['anodes'];
	load_cache('catalogs',$amconfig['sid']);
	if(!submitcheck('bamconfigablock')){
		$url_type = 'amconfigdetail';include 'urlsarr.inc.php';
		url_nav('['.$amconfigs[$amcid]['cname'].']&nbsp;'.lang('amconfig'),$urlsarr,'ablock');
		tabheader(lang('sysdefsetting'),'amconfigablock','?entry=amconfigs&action=amconfigablock&amcid='.$amcid,6);
		trbasic(lang('issysdef'),'abcustomnew',empty($amconfig['abcustom']) ? 0 : 1,'radio');
		tabfooter();

		tabheader(lang('anodeset'));
		$catalogs = array(0 => array('title' => lang('all_catalog'),'level' => 0)) + $catalogs;
		echo '<script type="text/javascript">var cata = [';
		foreach($catalogs as $caid => $catalog){
			$aurlstr = '';
			if(!isset($anodes[$caid])){
				$aurlstr = lang('invalid').lang('node');
			}elseif(empty($anodes[$caid])){
				$aurlstr = lang('defsetting');
			}else{
				$aurlsarr = explode(',',$anodes[$caid]);
				foreach($aurlsarr as $k) $aurlstr .= ($aurlstr ? ',' : '').$k.'-'.@$aurls[$k]['cname'];
			}
			echo "[$catalog[level],$caid,'" . str_replace("'","\\'",mhtmlspecialchars($catalog['title'])) . "','".($aurlstr)."'],";
		}
		$lang_cat = lang('anode');
		$lang_menu = lang('aurl');
		echo <<<DOT
], c, i, l = 0, ckey = 'ablockedit_', stat = [], tmp = [0], imgs = [], img = '',ico = '', ret = '';
function setTreeNode(ico, ix, img){
	var c = Cookie(ckey + ix) == 1, row = ico.parentNode.parentNode;
	row = row.parentNode.rows[row.rowIndex + 1];
	Cookie(ckey + ix, c ? 0 : 1, '9Y');
	ico.src = 'images/admina/' + (c ? 'add' : 'sub') + img + '.gif';
	if(row.firstChild.colSpan > 1)row.style.display = c ? 'none' : '';
}
for(i = 0; i< cata.length && cata[i]; i++){
	if(l > cata[i][0]){
		while(k = tmp.pop())if(cata[k][0] > cata[i][0])stat[k] = 1;else if(cata[k][0] == cata[i][0])break;
	}
	if(l == cata[i][0]){
		tmp[tmp.length - 1] = i;
	}else{
		tmp.push(i);
	}
	l = cata[i][0];
}
stat[i - 1] = 1;//last child
while((i = tmp.pop()) || i === 0)if(cata[i][0] != l)stat[i] = 1;//last child
c = l = k = 0;
for(i = 0; i< cata.length && cata[i]; i++){
	if(l < cata[i][0]){
		ret += '<tr' + (c ? '' : ' style="display:none"') + '><td class="nb" colspan="7"><table width="100%" border="0" cellpadding="0" cellspacing="0">';
		imgs.push(stat[i - 1] ? '<img src="images/admina/blank.gif" width="32" height="32" class="md" />' : '<img src="images/admina/line1.gif" width="32" height="32" class="md" />');
		img = imgs.join('');
	}else if(l > cata[i][0])while(l-- > cata[i][0]){
		ret += '</table></td></tr>';
		imgs.pop();
		img = imgs.join('');
	}
	l = cata[i][0];
	c = Cookie(ckey + i) == 1;
	if(stat[i]){//last child
		if(cata[i + 1] && cata[i + 1][0] > cata[i][0]){
			ico = '<img onclick="setTreeNode(this,' + i + ',3)" src="images/admina/' + (c ? 'sub' : 'add') + '3.gif" width="32" height="32" class="md" />';
		}else{
			ico = '<img src="images/admina/line3.gif" width="32" height="32" class="md" />';
		}
	}else{
		if(cata[i + 1] && cata[i + 1][0] > cata[i][0]){
			ico = '<img onclick="setTreeNode(this,' + i + ',2)" src="images/admina/' + (c ? 'sub' : 'add') + '2.gif" width="32" height="32" class="md" />';
		}else{
			ico = '<img src="images/admina/line2.gif" width="32" height="32" class="md" />';
		}
	}
	ret += '<tr><td width="40" align="center"><input class="checkbox" name="selectid[' + cata[i][1] + ']" value="' + cata[i][1] + '" type="checkbox" /></td>'
		+  '<td width="240" align="left">' + img + ico + cata[i][2] + '</td>'
		+  '<td width="65%"  align="center">' + cata[i][3] + '</td>'
}
while(l-- > 0)ret += '</table></td></tr>';
ret = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb0 tb2 bdbot">'
	+ '<tr align="center"><td width="40"><input class="checkbox" name="chkall" onclick="checkall(this.form, \'selectid\', \'chkall\')" type="checkbox"></td><td width="240"><b>$lang_cat</b></td><td><b>$lang_menu</b></td></tr>'
	+ ret + '</table>';
document.write(ret);
DOT;
		echo '</script>';
		tabfooter();
		tabheader(lang('operate_item'));
		$enablearr = array('1' => lang('enable'),'0' => lang('cancel'));
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[enable]\" value=\"1\">&nbsp;".lang('enable').lang('anode'),'',makeradio('arcenable',$enablearr,1),'');

		$aurlsarr = array();
		foreach($aurls as $k => $v) if(in_array($v['uclass'],array('archives','arcadd','arcupdate','comments','offers','replys','reports','answers','custom',))) $aurlsarr[$k] = $k.'-<b>'.$v['cname'].'</b>-'.$v['remark'];
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[auids]\" value=\"1\">&nbsp;".lang('set').lang('aurl'),'',makecheckbox('arcauids[]',$aurlsarr,array(),1),'');
		tabfooter('bamconfigablock');
		a_guide('amconfigablock');
	}else{
		if(!empty($selectid)){
			foreach($selectid as $id){
				if(!empty($arcdeal['enable'])){
					if($arcenable ){
						if(!isset($anodes[$id])) $anodes[$id] = '';
					}else unset($anodes[$id]);
				}
				if(!empty($arcdeal['auids'])){
					if(isset($anodes[$id])) $anodes[$id] = empty($arcauids) ? '' : implode(',',$arcauids);
				}
			}
		
		}
		foreach($anodes as $k => $v) if($k && empty($catalogs[$k])) unset($anodes[$k]);
		$anodes = addslashes(serialize($anodes));
		$db->query("UPDATE {$tblprefix}amconfigs SET 
		abcustom='$abcustomnew',
		anodes='$anodes'
		WHERE amcid='$amcid'");
		adminlog(lang('detailmodifyabap'));
		updatecache('amconfigs');
		amessage('adminbapsetfinish',M_REFERER);
	}
}elseif($action == 'amconfigfblock' && !empty($amcid)){
	empty($amconfigs[$amcid]) && amessage('chooseadminbap');
	$amconfig = $amconfigs[$amcid];
	if($amconfig['sid'] && empty($subsites[$amconfig['sid']])) amessage('nosite');
	$fnodes = empty($amconfig['fnodes']) ? '' : $amconfig['fnodes'];
	if(!submitcheck('bamconfigablock')){
		$url_type = 'amconfigdetail';include 'urlsarr.inc.php';
		url_nav('['.$amconfigs[$amcid]['cname'].']&nbsp;'.lang('amconfig'),$urlsarr,'fblock');
		tabheader(lang('sysdefsetting'),'amconfigfblock','?entry=amconfigs&action=amconfigfblock&amcid='.$amcid,6);
		trbasic(lang('issysdef'),'fbcustomnew',empty($amconfig['fbcustom']) ? 0 : 1,'radio');
		tabfooter();

		load_cache('fcatalogs');
		tabheader(lang('fnodeset'));
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('fnode'),lang('aurl')));
		foreach($fcatalogs as $caid => $catalog){
			$aurlstr = '';
			if(!isset($fnodes[$caid])){
				$aurlstr = lang('invalid').lang('node');
			}elseif(empty($fnodes[$caid])){
				$aurlstr = lang('defsetting');
			}else{
				$aurlsarr = explode(',',$fnodes[$caid]);
				foreach($aurlsarr as $k) $aurlstr .= ($aurlstr ? ',' : '').$k.'-'.@$aurls[$k]['cname'];
			}
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$caid]\" value=\"$caid\"></td>\n".
				"<td class=\"txtL\">$catalog[title]</td>\n".
				"<td class=\"txtC\">$aurlstr</td>\n".
				"</tr>\n";
		}
		tabfooter();
		tabheader(lang('operate_item'));
		$enablearr = array('1' => lang('enable'),'0' => lang('cancel'));
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[enable]\" value=\"1\">&nbsp;".lang('enable').lang('fnode'),'',makeradio('arcenable',$enablearr,1),'');

		$aurlsarr = array();
		foreach($aurls as $k => $v) if(in_array($v['uclass'],array('farchives','farcadd','custom',))) $aurlsarr[$k] = $k.'-<b>'.$v['cname'].'</b>-'.$v['remark'];
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[auids]\" value=\"1\">&nbsp;".lang('set').lang('aurl'),'',makecheckbox('arcauids[]',$aurlsarr,array(),1),'');
		tabfooter('bamconfigablock');
		a_guide('amconfigablock');
	}else{
		if(!empty($selectid)){
			foreach($selectid as $id){
				if(!empty($arcdeal['enable'])){
					if($arcenable ){
						if(!isset($fnodes[$id])) $fnodes[$id] = '';
					}else unset($fnodes[$id]);
				}
				if(!empty($arcdeal['auids'])){
					if(isset($fnodes[$id])) $fnodes[$id] = empty($arcauids) ? '' : implode(',',$arcauids);
				}
			}
		
		}
		foreach($fnodes as $k => $v) if($k && empty($fcatalogs[$k])) unset($fnodes[$k]);
		$fnodes = addslashes(serialize($fnodes));
		$db->query("UPDATE {$tblprefix}amconfigs SET 
		fbcustom='$fbcustomnew',
		fnodes='$fnodes'
		WHERE amcid='$amcid'");
		adminlog(lang('detailmodifyabap'));
		updatecache('amconfigs');
		amessage('adminbapsetfinish',M_REFERER);
	}
}elseif($action == 'amconfigmblock' && !empty($amcid)){
	empty($amconfigs[$amcid]) && amessage('chooseadminbap');
	$amconfig = $amconfigs[$amcid];
	if($amconfig['sid'] && empty($subsites[$amconfig['sid']])) amessage('nosite');
	$mnodes = empty($amconfig['mnodes']) ? '' : $amconfig['mnodes'];
	if(!submitcheck('bamconfigablock')){
		$url_type = 'amconfigdetail';include 'urlsarr.inc.php';
		url_nav('['.$amconfigs[$amcid]['cname'].']&nbsp;'.lang('amconfig'),$urlsarr,'mblock');
		tabheader(lang('sysdefsetting'),'amconfigmblock','?entry=amconfigs&action=amconfigmblock&amcid='.$amcid,6);
		trbasic(lang('issysdef'),'mbcustomnew',empty($amconfig['mbcustom']) ? 0 : 1,'radio');
		tabfooter();

		load_cache('mchannels');
		$mchidsarr = array(0 => lang('all_channel')) + mchidsarr();
		tabheader(lang('mnodeset'));
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('mnode'),lang('aurl')));
		foreach($mchidsarr as $mchid => $title){
			$aurlstr = '';
			if(!isset($mnodes[$mchid])){
				$aurlstr = lang('invalid').lang('node');
			}elseif(empty($mnodes[$mchid])){
				$aurlstr = lang('defsetting');
			}else{
				$aurlsarr = explode(',',$mnodes[$mchid]);
				foreach($aurlsarr as $k) $aurlstr .= ($aurlstr ? ',' : '').$k.'-'.@$aurls[$k]['cname'];
			}
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$mchid]\" value=\"$mchid\"></td>\n".
				"<td class=\"txtL\">$title</td>\n".
				"<td class=\"txtC\">$aurlstr</td>\n".
				"</tr>\n";
		}
		tabfooter();
		tabheader(lang('operate_item'));
		$enablearr = array('1' => lang('enable'),'0' => lang('cancel'));
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[enable]\" value=\"1\">&nbsp;".lang('enable').lang('mnode'),'',makeradio('arcenable',$enablearr,1),'');

		$aurlsarr = array();
		foreach($aurls as $k => $v) if(in_array($v['uclass'],array('members','memadd','mcomments','mreplys','mreports','marchives','mtrans','utrans','custom',))) $aurlsarr[$k] = $k.'-<b>'.$v['cname'].'</b>-'.$v['remark'];
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"arcdeal[auids]\" value=\"1\">&nbsp;".lang('set').lang('aurl'),'',makecheckbox('arcauids[]',$aurlsarr,array(),1),'');
		tabfooter('bamconfigablock');
		a_guide('amconfigablock');
	}else{
		if(!empty($selectid)){
			foreach($selectid as $id){
				if(!empty($arcdeal['enable'])){
					if($arcenable ){
						if(!isset($mnodes[$id])) $mnodes[$id] = '';
					}else unset($mnodes[$id]);
				}
				if(!empty($arcdeal['auids'])){
					if(isset($mnodes[$id])) $mnodes[$id] = empty($arcauids) ? '' : implode(',',$arcauids);
				}
			}
		
		}
		foreach($mnodes as $k => $v) if($k && empty($mchannels[$k])) unset($mnodes[$k]);
		$mnodes = empty($mnodes) ? '' : addslashes(serialize($mnodes));
		$db->query("UPDATE {$tblprefix}amconfigs SET 
		mbcustom='$mbcustomnew',
		mnodes='$mnodes'
		WHERE amcid='$amcid'");
		adminlog(lang('detailmodifyabap'));
		updatecache('amconfigs');
		amessage('adminbapsetfinish',M_REFERER);
	}
}
?>