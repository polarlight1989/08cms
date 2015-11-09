<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('sitemap') || amessage('no_apermission');
load_cache('catalogs,cotypes,channels');
if($action == 'sitemapsedit'){
	$sitemaps = fetch_arr();
	if(!submitcheck('bsitemapsedit')){
		tabheader(lang('sitepageadmin'),'sitemapsedit',"?entry=sitemaps&action=sitemapsedit",'8');
		trcategory(array(lang('available'),lang('sitemapcname'),lang('dynamicurl'),lang('xmlurl'),lang('setting'),lang('create')));
		foreach($sitemaps as $ename => $sitemap){
			echo "<tr class=\"txt\">".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"sitemapsnew[$ename][available]\" value=\"1\"".(empty($sitemap['available']) ? '' : ' checked')."></td>\n".
				"<td class=\"txtL\">".mhtmlspecialchars($sitemap['cname'])."</td>\n".
				"<td class=\"txtL\">".($cms_abs.$sitemap['d_url'])."</td>\n".
				"<td class=\"txtL\">".($cms_abs.$sitemap['xml_url'])."</td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=sitemaps&action=sitemapdetail&ename=$ename\">".lang('setting')."</a></td>\n".
				"<td class=\"txtC w30\"><a href=\"?entry=sitemaps&action=sitemapcreate&ename=$ename\">".lang('create')."</a></td></tr>\n";
		}
		tabfooter('bsitemapsedit');
		a_guide('sitemapsedit');
	}else{
		foreach($sitemaps as $ename => $sitemap){
			$sitemap['available'] = empty($sitemapsnew[$ename]['available']) ? 0 : 1;
			$db->query("UPDATE {$tblprefix}sitemaps SET available='$sitemap[available]' WHERE ename='$ename'");
		}
		updatecache('sitemaps');
		amessage('sitmodfin', "?entry=sitemaps&action=sitemapsedit");
	}
}
elseif($action == 'sitemapdetail' && $ename){
	$sitemap = fetch_one($ename);
	empty($sitemap) && amessage('choosesite', '?entry=sitemaps&action=sitemapsedit');
	if($ename == 'baidu'){
		$chids = array();
		foreach($channels as $chid => $channel){
			$channel['baidu'] && $chids[$chid] = $channel['cname'];
		}
		empty($chids) && amessage('nobaidunews', '?entry=sitemaps&action=sitemapsedit');
	}else{
		$chids = chidsarr();
	}

	if(!submitcheck('bsitemapdetail')){
		tabheader(lang('sitemapsetting'),'sitemapdetail','?entry=sitemaps&action=sitemapdetail&ename='.$ename);
		trbasic(lang('sitemapcname'),'',$sitemap['cname'],'');
		trbasic(lang('dynapickurl'),'',$cms_abs.$sitemap['d_url'],'');
		trbasic(lang('xmlpickurl'),'',$cms_abs.$sitemap['xml_url'],'');
		trbasic(lang('isenable'),'sitemapnew[available]',isset($sitemap['available']) ? $sitemap['available'] : 0,'radio');
		trbasic(lang('upperiodhours'),'sitemapnew[setting][life]',empty($sitemap['setting']['life']) ? '' : $sitemap['setting']['life']);
		trbasic(lang('limitdayarchive'),'sitemapnew[setting][indays]',empty($sitemap['setting']['indays']) ? '' : $sitemap['setting']['indays']);
		$sourcearr = array('0' => lang('nolimitcatas'),'1' => lang('handpoint'));
		sourcemodule(lang('cataloglimi'),
					'sitemapnew[setting][casource]',
					$sourcearr,
					empty($sitemap['setting']['casource']) ? '0' : $sitemap['setting']['casource'],
					'1',
					'sitemapnew[setting][caids][]',
					caidsarr($catalogs),
					empty($sitemap['setting']['caids']) ? array() : $sitemap['setting']['caids']
					);

		foreach($cotypes as $k => $cotype) {
			sourcemodule("$cotype[cname]".lang('limited'),
						"sitemapnew[setting][cosource$k]",
						$sourcearr,
						empty($sitemap['setting']['cosource'.$k]) ? '0' : $sitemap['setting']['cosource'.$k],
						'1',
						"sitemapnew[setting][ccids$k][]",
						ccidsarr($k),
						empty($sitemap['setting']['ccids'.$k]) ? array() : $sitemap['setting']['ccids'.$k]
						);
		}
		$chsourcearr = array('0' => lang('nolimitchannel'),'1' => lang('handpoint'),);
		sourcemodule(lang('chid_attr'),
					'sitemapnew[setting][chsource]',
					$chsourcearr,
					empty($sitemap['setting']['chsource']) ? '0' : $sitemap['setting']['chsource'],
					'1',
					'sitemapnew[setting][chids][]',
					$chids,
					!empty($sitemap['setting']['chids']) ? $sitemap['setting']['chids'] : array()
					);
		tabfooter('bsitemapdetail',lang('modify'));
		a_guide('sitemapdetail');
	}else{
		if(!empty($sitemapnew['setting']['casource']) && empty($sitemapnew['setting']['caids'])) amessage('selectcatg','?entry=sitemaps&action=sitemapdetail&ename='.$ename);
		if(!empty($sitemapnew['setting']['chsource']) && empty($sitemapnew['setting']['chids'])) amessage('selectcha','?entry=sitemaps&action=sitemapdetail&ename='.$ename);
		if(empty($sitemapnew['setting']['casource'])) unset($sitemapnew['setting']['caids']);
		if(empty($sitemapnew['setting']['chsource'])) unset($sitemapnew['setting']['chids']);
		foreach($cotypes as $k => $cotype){
			if(!empty($sitemapnew['setting']['cosource'.$k]) && empty($sitemapnew['setting']['ccids'.$k])) amessage('confirmselect'.$cotype['cname'].lang('coclass'),'?entry=sitemaps&action=sitemapdetail&ename='.$ename);
		}
		$sitemapnew['available'] = empty($sitemapnew['available']) ? 0 : 1;
		$sitemapnew['setting']['life'] = max(0,intval($sitemapnew['setting']['life']));
		$sitemapnew['setting']['indays'] = max(0,intval($sitemapnew['setting']['indays']));
		$sitemapnew['setting'] = addslashes(serialize($sitemapnew['setting']));
		$db->query("UPDATE {$tblprefix}sitemaps SET 
					available='$sitemapnew[available]',
					setting='$sitemapnew[setting]'
					WHERE ename='$ename'");
		updatecache('sitemaps');
		amessage('sitsetfin','?entry=sitemaps&action=sitemapdetail&ename='.$ename);
	}

}elseif($action == 'sitemapcreate' && $ename){
	$sitemap = fetch_one($ename);
	empty($sitemap) && amessage('choosesite', '?entry=sitemaps&action=sitemapsedit');
	empty($sitemap['available']) && amessage('sitemapclo', '?entry=sitemaps&action=sitemapsedit');
	if($sitemap['ename'] == 'baidu'){
		$chids = array();
		foreach($channels as $chid => $channel){
			$channel['baidu'] && $chids[] = $chid;
		}
		empty($chids) && amessage('nobaidunews', '?entry=sitemaps&action=sitemapsedit');
	}
	$cachefile = M_ROOT.$sitemap['xml_url'];
	include_once M_ROOT.'./include/sitemap.inc.php';
	str2file($datastr,$cachefile);
	amessage('sitcrefin', '?entry=sitemaps&action=sitemapsedit');
}
function fetch_arr(){
	global $db,$tblprefix;
	$sitemaps = array();
	$query = $db->query("SELECT * FROM {$tblprefix}sitemaps ORDER BY vieworder");
	while($sitemap = $db->fetch_array($query)){
		if($sitemap['setting'] && is_array($setting = unserialize($sitemap['setting']))){$sitemap['setting'] = $setting;}
		else{$sitemap['setting'] = array();}
		$sitemaps[$sitemap['ename']] = $sitemap;
	}
	return $sitemaps;
}
function fetch_one($ename){
	global $db,$tblprefix;
	$sitemap = $db->fetch_one("SELECT * FROM {$tblprefix}sitemaps WHERE ename='$ename'");
	if($sitemap['setting'] && is_array($setting = unserialize($sitemap['setting']))){$sitemap['setting'] = $setting;}
	else{$sitemap['setting'] = array();}
	return $sitemap;
}
?>
