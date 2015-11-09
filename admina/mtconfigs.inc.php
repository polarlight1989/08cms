<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
load_cache('channels,catalogs,cotypes,mtconfigs,mtpls,mchannels,mcatalogs');
if($action == 'mtconfigsedit'){
	if(!submitcheck('bmtconfigsedit') && !submitcheck('bmcatalogsedit')){
		tabheader(lang('spatemproman').'&nbsp; &nbsp; &nbsp; &nbsp; >><a href="?entry=mtconfigs&action=mtconfigadd" onclick="return floatwin(\'open_mtconfigsedit\',this)">'.lang('addspatempro').'</a>','mtconfigsedit','?entry=mtconfigs&action=mtconfigsedit','4');
		trcategory(array(lang('delete'),lang('projectname'),lang('inchuse'),lang('edit')));
		foreach($mtconfigs as $mtcid => $mtconfig) {
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w35\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$mtcid]\" value=\"$mtcid\"".(empty($mtconfig['issystem']) ? '' : ' disabled').">\n".
				"<td class=\"txtL w100\"><input type=\"text\" name=\"mtconfigsnew[$mtcid][cname]\" value=\"".mhtmlspecialchars($mtconfig['cname'])."\" size=\"25\" maxlength=\"30\"></td>\n".
				"<td class=\"txtC\">".makecheckbox("mtconfigsnew[$mtcid][mchids][]",mchidsarr(),empty($mtconfig['mchids']) ? array() : explode(',',$mtconfig['mchids']),5)."</td>\n".
				"<td class=\"txtC w35\"><a href=\"?entry=mtconfigs&action=mtconfigdetail&mtcid=$mtcid\" onclick=\"return floatwin('open_mtconfigsedit',this)\">".lang('detail')."</a></td>\n".
				"</tr>";
		}
		tabfooter('bmtconfigsedit');

		tabheader(lang('spacatamana').'&nbsp; &nbsp; &nbsp; &nbsp; >><a href="?entry=mtconfigs&action=mcatalogadd" onclick="return floatwin(\'open_mtconfigsedit\',this)">'.lang('addspaccata').'</a>','mcatalogsedit','?entry=mtconfigs&action=mtconfigsedit','6');
		trcategory(array(lang('delete'),lang('id'),lang('catalog_name'),lang('cocllimi'),lang('order'),lang('remark')));
		foreach($mcatalogs as $mcaid => $mcatalog) {
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$mcaid]\" value=\"$mcaid\"></td>\n".
				"<td class=\"txtC w30\">$mcaid</td>\n".
				"<td class=\"txtL w120\"><input type=\"text\" name=\"mcatalogsnew[$mcaid][title]\" value=\"".mhtmlspecialchars($mcatalog['title'])."\" size=\"25\" maxlength=\"30\"></td>\n".
				"<td class=\"txtC w80\"><input type=\"text\" name=\"mcatalogsnew[$mcaid][maxucid]\" value=\"$mcatalog[maxucid]\" size=\"4\" maxlength=\"4\"></td>\n".
				"<td class=\"txtC w80\"><input type=\"text\" name=\"mcatalogsnew[$mcaid][vieworder]\" value=\"".mhtmlspecialchars($mcatalog['vieworder'])."\" size=\"4\" maxlength=\"4\"></td>\n".
				"<td class=\"txtC\"><input type=\"text\" name=\"mcatalogsnew[$mcaid][remark]\" value=\"".mhtmlspecialchars($mcatalog['remark'])."\" size=\"60\" maxlength=\"100\"></td>\n".
				"</tr>";
		}
		tabfooter('bmcatalogsedit');
		a_guide('mtconfigsedit');
	}elseif(submitcheck('bmtconfigsedit')){
		if(!empty($delete)){
			foreach($delete as $mtcid){
				if(empty($mtconfigs[$mtcid]['issystem'])){
					$db->query("DELETE FROM {$tblprefix}mtconfigs WHERE mtcid='$mtcid'");
					unset($mtconfigsnew[$mtcid]);
				}
			}
		}
		if(!empty($mtconfigsnew)){
			foreach($mtconfigsnew as $mtcid => $mtconfignew){
				$mtconfignew['cname'] = empty($mtconfignew['cname']) ? $mtconfigs[$mtcid]['cname'] : $mtconfignew['cname'];
				$mtconfignew['mchids'] = empty($mtconfignew['mchids']) ? '' : implode(',',$mtconfignew['mchids']);
				$db->query("UPDATE {$tblprefix}mtconfigs SET 
							cname='$mtconfignew[cname]',
							mchids='$mtconfignew[mchids]'
							WHERE mtcid='$mtcid'");
			}
		}
		updatecache('mtconfigs');
		adminlog(lang('edispatepromanis'));
		amessage('spatempromodfin', "?entry=mtconfigs&action=mtconfigsedit");
	}elseif(submitcheck('bmcatalogsedit')){
		if(!empty($delete)){
			foreach($delete as $mcaid){
				$db->query("DELETE FROM {$tblprefix}mcatalogs WHERE mcaid='$mcaid'");
				$db->query("UPDATE {$tblprefix}uclasses SET mcaid='0' WHERE mcaid='$mcaid'");
				unset($mcatalogsnew[$mcaid]);
			}
		}
		if(!empty($mcatalogsnew)){
			foreach($mcatalogsnew as $mcaid => $mcatalognew){
				$mcatalognew['title'] = empty($mcatalognew['title']) ? $mcatalogs[$mcaid]['title'] : trim(strip_tags($mcatalognew['title']));
				$mcatalognew['remark'] = trim(strip_tags($mcatalognew['remark']));
				$mcatalognew['maxucid'] = max(0,intval($mcatalognew['maxucid']));
				$mcatalognew['vieworder'] = max(0,intval($mcatalognew['vieworder']));
				$db->query("UPDATE {$tblprefix}mcatalogs SET 
							title='$mcatalognew[title]',
							remark='$mcatalognew[remark]',
							maxucid='$mcatalognew[maxucid]',
							vieworder='$mcatalognew[vieworder]'
							WHERE mcaid='$mcaid'");
			}
		}
		updatecache('mcatalogs');
		adminlog(lang('edispacatmanlis'));
		amessage('spacatmodfin', "?entry=mtconfigs&action=mtconfigsedit");
	}

}elseif($action == 'mtconfigadd'){
	if(!submitcheck('bmtconfigadd')){
		tabheader(lang('spatemproadd'),'mtconfigadd','?entry=mtconfigs&action=mtconfigadd');
		trbasic(lang('temprocna'),'mtconfigadd[cname]');
		trbasic(lang('inchallowuse'),'',makecheckbox('mtconfigadd[mchids][]',mchidsarr(),array(),5),'');
		tabfooter('bmtconfigadd');
		a_guide('mtconfigadd');
	}else{
		if(empty($mtconfigadd['cname'])) amessage('temprodatmis',M_REFERER);
		$mtconfigadd['mchids'] = !empty($mtconfigadd['mchids']) ? implode(',',$mtconfigadd['mchids']) : '';
		$db->query("INSERT INTO {$tblprefix}mtconfigs SET cname='$mtconfigadd[cname]',mchids='$mtconfigadd[mchids]'");
		updatecache('mtconfigs');
		adminlog(lang('addspatempro'));
		amessage('temproaddfin', '?entry=mtconfigs&action=mtconfigsedit');
	}

}elseif($action == 'mcatalogadd'){
	if(!submitcheck('bmcatalogadd')){
		tabheader(lang('addspaccata'),'mcatalogadd','?entry=mtconfigs&action=mcatalogadd');
		trbasic(lang('spacatcna'),'mcatalogadd[title]');
		trbasic(lang('uclmaxaddamomem'),'mcatalogadd[maxucid]',0);
		trbasic(lang('catalogremark'),'mcatalogadd[remark]','','btext');
		tabfooter('bmcatalogadd');
		a_guide('mcatalogadd');
	}else{
		$mcatalogadd['title'] = trim(strip_tags($mcatalogadd['title']));
		$mcatalogadd['remark'] = trim(strip_tags($mcatalogadd['remark']));
		$mcatalogadd['maxucid'] = max(0,intval($mcatalogadd['maxucid']));
		if(empty($mcatalogadd['title'])) amessage('inpspacatcnam',M_REFERER);
		$db->query("INSERT INTO {$tblprefix}mcatalogs SET title='$mcatalogadd[title]',maxucid='$mcatalogadd[maxucid]',remark='$mcatalogadd[remark]'");
		updatecache('mcatalogs');
		adminlog(lang('addspaccata'));
		amessage('spacataddfin', axaction(6,'?entry=mtconfigs&action=mtconfigsedit'));
	}

}elseif($action == 'mtconfigdetail' && !empty($mtcid)){
	empty($mtconfigs[$mtcid]) && amessage('choosespatempro');
	$setting = $mtconfigs[$mtcid]['setting'];
	$url_type = 'mtdetail';include 'urlsarr.inc.php';
	url_nav($mtconfigs[$mtcid]['cname'],$urlsarr,'base');
	if(!submitcheck('bmtconfigdetail')){
		tabheader('['.$mtconfigs[$mtcid]['cname'].']'.lang('spatemproset'),'mtconfigdetail','?entry=mtconfigs&action=mtconfigdetail&mtcid='.$mtcid,5);
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",lang('enable'),array(lang('spacatcna'),'txtL'),lang('spaindtem'),lang('spalistemp')));
		echo "<tr class=\"txt\">\n".
			"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[0]\" value=\"0\"></td>\n".
			"<td class=\"txtC w40\">Y</td>\n".
			"<td class=\"txtL\"><b>".lang('index')."</b></td>\n".
			"<td class=\"txtC\">".(empty($setting[0]['index']) ? '-' : (empty($mtpls[$setting[0]['index']]['cname']) ? $setting[0]['index'] : $mtpls[$setting[0]['index']]['cname']))."</td>\n".
			"<td class=\"txtC\">-</td>\n".
			"</tr>";
		foreach($mcatalogs as $mcaid => $mcatalog){
			$indexstr = empty($setting[$mcaid]['index']) ? '-' : (empty($mtpls[$setting[$mcaid]['index']]['cname']) ? $setting[$mcaid]['index'] : $mtpls[$setting[$mcaid]['index']]['cname']);
			$liststr = empty($setting[$mcaid]['list']) ? '-' : (empty($mtpls[$setting[$mcaid]['list']]['cname']) ? $setting[$mcaid]['list'] : $mtpls[$setting[$mcaid]['list']]['cname']);
			$titlestr = isset($setting[$mcaid]) ? '<b>'.$mcatalog['title'].'</b>' : $mcatalog['title'];
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$mcaid]\" value=\"$mcaid\"></td>\n".
				"<td class=\"txtC w40\">".(isset($setting[$mcaid]) ? 'Y' : '-')."</td>\n".
				"<td class=\"txtL\">$titlestr</td>\n".
				"<td class=\"txtC\">$indexstr</td>\n".
				"<td class=\"txtC\">$liststr</td>\n".
				"</tr>";
		}
		tabfooter();
	
		tabheader(lang('operate_item'));
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[enable]\" value=\"1\">&nbsp;".lang('enaspacat'),'',makeradio('spaceenable',array('0' => lang('cancel'),'1' => lang('enable'))),'');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[index]\" value=\"1\">&nbsp;".lang('spaindtem'),'spaceindex',makeoption(array('' => lang('noset')) + mtplsarr('space')),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[list]\" value=\"1\">&nbsp;".lang('spalistemp'),'spacelist',makeoption(array('' => lang('noset')) + mtplsarr('space')),'select');
		tabfooter('bmtconfigdetail');
		a_guide('mtconfigdetail');
	}else{
		if(empty($selectid)) amessage('selectopecat',M_REFERER);
		if(empty($cndeal)) amessage('selectoperateitem',M_REFERER);
		foreach($selectid as $mcaid => $v){
			if(!empty($cndeal['enable']) && $mcaid){
				if(empty($spaceenable)){
					unset($setting[$mcaid]);
				}else $setting[$mcaid] = !isset($setting[$mcaid]) ? array('index' => '','list' => '',) : $setting[$mcaid];
			}
			if(!empty($cndeal['index'])){
				if(!$mcaid || isset($setting[$mcaid])) $setting[$mcaid]['index'] = $spaceindex;
			}
			if(!empty($cndeal['list'])){
				if(isset($setting[$mcaid])) $setting[$mcaid]['list'] = $spacelist;
			}
		}
		
		$setting = empty($setting) ? '' : addslashes(serialize($setting));

		$db->query("UPDATE {$tblprefix}mtconfigs SET setting='$setting' WHERE mtcid='$mtcid'");
		adminlog(lang('detmodspatempro'));
		updatecache('mtconfigs');
		amessage('tempprosetfin','?entry=mtconfigs&action=mtconfigdetail&mtcid='.$mtcid);
	}
}elseif($action == 'mtconfigtpl' && !empty($mtcid)){
	empty($mtconfigs[$mtcid]) && amessage('choosespatempro');
	$arctpls = $mtconfigs[$mtcid]['arctpls'];
	$url_type = 'mtdetail';include 'urlsarr.inc.php';
	url_nav($mtconfigs[$mtcid]['cname'],$urlsarr,'tpl');
	if(!submitcheck('bmtconfigdetail')){
		tabheader('['.$mtconfigs[$mtcid]['cname'].']'.lang('cnt_tpl'),'mtconfigdetail','?entry=mtconfigs&action=mtconfigtpl&mtcid='.$mtcid,5);
		
		trcategory(array("<input class=\"checkbox\" type=\"checkbox\" name=\"chkall\" onclick=\"checkall(this.form, 'selectid', 'chkall')\">",array(lang('achannel'),'txtL'),lang('ms_cnt_tpl'),lang('product_tpl')));
		foreach($channels as $k => $v){
			$archivetpl = empty($arctpls['archive'][$k]) ? '-' : (empty($mtpls[$arctpls['archive'][$k]]['cname']) ? $arctpls['archive'][$k] : $mtpls[$arctpls['archive'][$k]]['cname']);
			$producttpl = empty($arctpls['product'][$k]) ? '-' : (empty($mtpls[$arctpls['product'][$k]]['cname']) ? $arctpls['product'][$k] : $mtpls[$arctpls['product'][$k]]['cname']);
			echo "<tr class=\"txt\">\n".
				"<td class=\"txtC w40\"><input class=\"checkbox\" type=\"checkbox\" name=\"selectid[$k]\" value=\"$k\"></td>\n".
				"<td class=\"txtL\">$v[cname]</td>\n".
				"<td class=\"txtC\">$archivetpl</td>\n".
				"<td class=\"txtC\">$producttpl</td>\n".
				"</tr>";
		}
		tabfooter();
	
		tabheader(lang('operate_item'));
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[archive]\" value=\"1\">&nbsp;".lang('ms_cnt_tpl'),'tplarchive',makeoption(array('' => lang('noset')) + mtplsarr('space')),'select');
		trbasic("<input class=\"checkbox\" type=\"checkbox\" name=\"cndeal[product]\" value=\"1\">&nbsp;".lang('product_tpl'),'tplproduct',makeoption(array('' => lang('noset')) + mtplsarr('space')),'select');
		tabfooter('bmtconfigdetail');
		a_guide('mtconfigdetail');
	}else{
		if(empty($selectid)) amessage('selectopecat',M_REFERER);
		if(empty($cndeal)) amessage('selectoperateitem',M_REFERER);
		foreach($selectid as $k){
			if(!empty($cndeal['archive'])){
				$arctpls['archive'][$k] = $tplarchive;
			}
			if(!empty($cndeal['product'])){
				$arctpls['product'][$k] = $tplproduct;
			}
		}
		
		$arctpls = empty($arctpls) ? '' : addslashes(serialize($arctpls));

		$db->query("UPDATE {$tblprefix}mtconfigs SET arctpls='$arctpls' WHERE mtcid='$mtcid'");
		adminlog(lang('detmodspatempro'));
		updatecache('mtconfigs');
		amessage('tempprosetfin','?entry=mtconfigs&action=mtconfigtpl&mtcid='.$mtcid);
	}
}
?>