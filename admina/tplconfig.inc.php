<?php
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('tpl') || amessage('no_apermission');
load_cache('mtpls',$sid);
$url_type = 'tpl';include 'urlsarr.inc.php';
if($sid) $subsite = $subsites[$sid];
if($action == 'tplbase'){
	url_nav(lang('tplallconfig'),$urlsarr,'base',12);
	if(!submitcheck('btplbase')){
		tabheader(lang(!$sid ? 'pagebasedset' : 'subsiteset'),'tplbase',"?entry=tplconfig&action=tplbase$param_suffix");
		if(!$sid){
			trbasic(lang('index_tpl'),'mconfigsnew[hometpl]',makeoption(array('' => lang('noset')) + mtplsarr('index'),$mconfigs['hometpl']),'select');
			trbasic(lang('m_index_tpl'),'mconfigsnew[m_index_tpl]',makeoption(array('' => lang('noset')) + mtplsarr('marchive'),@$mconfigs['m_index_tpl']),'select');
			trbasic(lang('w_index_tpl'),'mconfigsnew[w_index_tpl]',makeoption(array('' => lang('noset')) + mtplsarr('wap'),@$mconfigs['w_index_tpl']),'select');
			trbasic(lang('templatedir'),'mconfigsnew[templatedir]',$mconfigs['templatedir'],'text',lang('agtemplatedir'));
			trbasic(lang('temcssdir'),'mconfigsnew[css_dir]',empty($mconfigs['css_dir']) ? 'css' : $mconfigs['css_dir'],'text',lang('agcss_dir'));
			trbasic(lang('temjsdir'),'mconfigsnew[js_dir]',empty($mconfigs['js_dir']) ? 'js' : $mconfigs['js_dir'],'text',lang('agcss_dir'));
			trbasic(lang('tepawedest'),'mconfigsnew[debugtag]',$mconfigs['debugtag'],'radio');//两个作用：出错标识显示出样式，被动静态页面每次刷新更新
			trbasic(lang('jsrefsource'),'mconfigsnew[jsrefsource]',$mconfigs['jsrefsource'],'textarea',lang('agjsrefsource'));
			tabfooter();
		}else{
			trbasic(lang('subindtem'),'subsitenew[templatedir]',$subsite['templatedir'],'text',lang('agtemplatedir'));
			trbasic(lang('subindtpl'),'subsitenew[hometpl]',makeoption(array('' => lang('noset')) + mtplsarr('index'),$subsite['hometpl']),'select');
			trbasic(lang('w_index_tpl'),'subsitenew[w_index_tpl]',makeoption(array('' => lang('noset')) + mtplsarr('wap'),@$subsite['w_index_tpl']),'select');
			trbasic(lang('temcssdir'),'subsitenew[css_dir]',empty($subsite['css_dir']) ? 'css' : $subsite['css_dir'],'text',lang('agcss_dir'));
			trbasic(lang('temjsdir'),'subsitenew[js_dir]',empty($subsite['js_dir']) ? 'js' : $subsite['js_dir'],'text',lang('agcss_dir'));
		}
		tabfooter('btplbase');
		a_guide('tplbase');

	}else{
		if(!$sid){
			$mconfigsnew['hometpl'] = empty($mconfigsnew['hometpl']) ? '' : trim($mconfigsnew['hometpl']);
			$mconfigsnew['templatedir'] = trim(strip_tags($mconfigsnew['templatedir']));//指定新的模板文件夹，所以可以有不同的模板样式
			if(empty($mconfigsnew['templatedir']) || preg_match("/[^a-zA-Z_0-9]+/",$mconfigsnew['templatedir'])){
				amessage('tpldirillegal',M_REFERER);
			}
			mmkdir(M_ROOT.'template/'.$mconfigsnew['templatedir']);
			$mconfigsnew['css_dir'] = trim(strip_tags($mconfigsnew['css_dir']));
			$mconfigsnew['js_dir'] = trim(strip_tags($mconfigsnew['js_dir']));
			$mconfigsnew['jsrefsource'] = trim(preg_replace("/(\s*(\r\n|\n\r|\n|\r)\s*)/","\r\n",$mconfigsnew['jsrefsource']));
			saveconfig('tpl');
		}else{
			$subsitenew['hometpl'] = empty($subsitenew['hometpl']) ? '' : trim($subsitenew['hometpl']);
			$subsitenew['w_index_tpl'] = empty($subsitenew['w_index_tpl']) ? '' : trim($subsitenew['w_index_tpl']);
			$subsitenew['templatedir'] = trim(strip_tags($subsitenew['templatedir']));//指定新的模板文件夹，所以可以有不同的模板样式
			if(empty($subsitenew['templatedir']) || preg_match("/[^a-zA-Z_0-9]+/",$subsitenew['templatedir'])){
				amessage('tpldirillegal',M_REFERER);
			}
			mmkdir(M_ROOT.'template/'.$subsitenew['templatedir'],0);
			$subsitenew['css_dir'] = trim(strip_tags($subsitenew['css_dir']));
			$subsitenew['js_dir'] = trim(strip_tags($subsitenew['js_dir']));
			$db->query("UPDATE {$tblprefix}subsites SET 
			hometpl='$subsitenew[hometpl]',
			w_index_tpl='$subsitenew[w_index_tpl]',
			templatedir='$subsitenew[templatedir]',
			css_dir='$subsitenew[css_dir]',
			js_dir='$subsitenew[js_dir]'
			WHERE sid='$sid'");
			updatecache('subsites');
		}
		adminlog(lang('websiteset'),lang('pagandtemset'));
		amessage('websitesetfinish',M_REFERER);
	}

}elseif($action == 'tplchannel'){
	url_nav(lang('tplallconfig'),$urlsarr,'channel',12);
	load_cache('channels');
	if(!$chids = array_keys($channels)) amessage('defineachannel');
	$chid = empty($chid) ? $chids[0] : $chid;
	$channel = read_cache('channel',$chid);
	cache_merge($channel,'channel',$sid);
	if(!submitcheck('bchannel')){
		$arr = array();
		foreach($channels as $k => $v) $arr[] = $chid == $k ? "<b>-$v[cname]-</b>" : "<a href=\"?entry=tplconfig&action=tplchannel&chid=$k$param_suffix\">$v[cname]</a>";
		echo tab_list($arr,6,0);

		tabheader("[$channel[cname]]".lang('tpl_set'),'channel',"?entry=tplconfig&action=tplchannel&chid=$chid$param_suffix");
		$arctpls = explode(',',$channel['arctpls']);
		$warctpls = explode(',',$channel['warctpls']);
		for($i = 0;$i <= $channel['addnum'];$i ++){
			$pv = $i ? lang('addp').$i : lang('arcconpage');
			trbasic($pv.lang('template'),"channelnew[arctpls][$i]",makeoption(array('' => lang('noset')) + mtplsarr('archive'),@$arctpls[$i]),'select');
			trbasic($pv.lang('wtemplate'),"channelnew[warctpls][$i]",makeoption(array('' => lang('noset')) + mtplsarr('wap'),@$warctpls[$i]),'select');
		}
		trbasic(lang('arc_prepage_tpl'),'channelnew[pretpl]',makeoption(array('' => lang('noset')) + mtplsarr('archive'),$channel['pretpl']),'select');
		trbasic(lang('search_tpl'),'channelnew[srhtpl]',makeoption(array('' => lang('noset')) + mtplsarr('search'),$channel['srhtpl']),'select');
		trbasic(lang('arc_add_tpl'),'channelnew[addtpl]',makeoption(array('' => lang('noset')) + mtplsarr('archive'),$channel['addtpl']),'select');
		tabfooter('bchannel');
		a_guide('tplchannel');
	}else{
		if(!$sid){
			$channelnew['arctpls'] = implode(',',$channelnew['arctpls']);
			$channelnew['warctpls'] = implode(',',$channelnew['warctpls']);
			$db->query("UPDATE {$tblprefix}channels SET 
				arctpls='$channelnew[arctpls]',
				warctpls='$channelnew[warctpls]',
				pretpl='$channelnew[pretpl]',
				srhtpl='$channelnew[srhtpl]',
				addtpl='$channelnew[addtpl]'
				WHERE chid='$chid'");
			updatecache('channels');
		}else{
			$s_channels = empty($subsites[$sid]['channels']) ? array() : $subsites[$sid]['channels'];
			$s_channels[$chid]['arctpls'] = implode(',',$channelnew['arctpls']);
			$s_channels[$chid]['warctpls'] = implode(',',$channelnew['warctpls']);
			$s_channels[$chid]['pretpl'] = $channelnew['pretpl'];
			$s_channels[$chid]['srhtpl'] = $channelnew['srhtpl'];
			$s_channels[$chid]['addtpl'] = $channelnew['addtpl'];
			$s_channels = addslashes(serialize($s_channels));
			$db->query("UPDATE {$tblprefix}subsites SET channels='$s_channels' WHERE sid='$sid'");
			updatecache('subsites');
		}
		adminlog(lang('detail_marc_channel'));
		amessage('channelmodifyfinish',M_REFERER);
	}
}elseif($action == 'tplfcatalog'){
	url_nav(lang('tplallconfig'),$urlsarr,'fcatalog',12);
	load_cache('fcatalogs');
	if(!submitcheck('bfcatalog')){
		tabheader(lang('freeinfo').lang('tpl_set'),'fcatalog',"?entry=tplconfig&action=tplfcatalog$param_suffix");
		foreach($fcatalogs as $k => $v){
			trbasic($v['title'].'-'.lang('msg_con_tpl'),"fcatalogsnew[$k][arctpl]",makeoption(array('' => lang('noset')) + mtplsarr('freeinfo'),$v['arctpl']),'select');
		}
		tabfooter('bfcatalog');
		a_guide('tplfcatalog');
	}else{
		if(!empty($fcatalogsnew)){
			foreach($fcatalogsnew as $k => $v){
				$db->query("UPDATE {$tblprefix}fcatalogs SET arctpl='$v[arctpl]' WHERE fcaid='$k'");
			}
		}
		updatecache('fcatalogs');
		adminlog(lang('detail0_modify_freeinfo'));
		amessage('coclasssetfinish',M_REFERER);
	}
}elseif($action == 'tplmchannel'){
	url_nav(lang('tplallconfig'),$urlsarr,'mchannel',12);
	load_cache('mchannels');
	if(!$mchids = array_keys($mchannels)) amessage('conmemcha');
	$mchid = empty($mchid) ? $mchids[0] : $mchid;
	$mchannel = $mchannels[$mchid];
	if(!submitcheck('bmchannel')){
		$arr = array();
		foreach($mchannels as $k => $v) $arr[] = $mchid == $k ? "<b>-$v[cname]-</b>" : "<a href=\"?entry=tplconfig&action=tplmchannel&mchid=$k$param_suffix\">$v[cname]</a>";
		echo tab_list($arr,6,0);

		tabheader("[$mchannel[cname]]".lang('tpl_set'),'mchannel',"?entry=tplconfig&action=tplmchannel&mchid=$mchid$param_suffix");
		trbasic(lang('regtpl'),'mchannelnew[addtpl]',makeoption(array('' => lang('noset')) + mtplsarr('marchive'),$mchannel['addtpl']),'select');
		trbasic(lang('search_member_tpl'),'mchannelnew[srhtpl]',makeoption(array('' => lang('noset')) + mtplsarr('search'),$mchannel['srhtpl']),'select');
		tabfooter('bmchannel');
		a_guide('tplmchannel');
	}else{
		$db->query("UPDATE {$tblprefix}mchannels SET
			addtpl='$mchannelnew[addtpl]', 
			srhtpl='$mchannelnew[srhtpl]' 
			WHERE mchid='$mchid'");
		adminlog(lang('det_modify_mchannel'));
		updatecache('mchannels');
		amessage('channelmodifyfinish',M_REFERER);
	}
}elseif($action == 'tplcommu'){
	url_nav(lang('tplallconfig'),$urlsarr,'commu',12);
	load_cache('commus');
	if(!submitcheck('bcommu')){
		tabheader(lang('archivecommu').lang('tpl_set'),'commu',"?entry=tplconfig&action=tplcommu$param_suffix");
		foreach($commus as $k => $v){
			if(in_array($v['cclass'],array('report','comment','answer','reply',))){
				if($sid) cache_merge($v,'commu',$sid);
				trbasic($v['cname'].'-'.lang('add_page_tpl'),"commusnew[$k][addtpl]",makeoption(array('' => lang('noset')) + mtplsarr('commu'),$v['addtpl']),'select');
			}
		}
		tabfooter('bcommu');
		a_guide('tplcommu');
	}else{
		if(!$sid){
			foreach($commus as $k => $v){
				if(in_array($v['cclass'],array('report','comment','answer','reply',))){
					$addtpl = empty($commusnew[$k]['addtpl']) ? '' : $commusnew[$k]['addtpl'];
					$db->query("UPDATE {$tblprefix}commus SET addtpl='$addtpl' WHERE cuid='$k'");
				}
			}
			updatecache('commus');
		}else{
			$s_commus = empty($subsites[$sid]['commus']) ? array() : $subsites[$sid]['commus'];
			foreach($commus as $k => $v){
				if(in_array($v['cclass'],array('report','comment','answer','reply',))){
					$s_commus[$k]['addtpl'] = empty($commusnew[$k]['addtpl']) ? '' : $commusnew[$k]['addtpl'];
				}
			}
			$s_commus = $s_commus ? addslashes(serialize($s_commus)) : '';
			$db->query("UPDATE {$tblprefix}subsites SET commus='$s_commus' WHERE sid='$sid'");
			updatecache('subsites');
		}
		adminlog(lang('detail_modify_citem'));
		amessage('itemmodifyfinish',M_REFERER);
	}
}elseif($action == 'tplmcommu'){
	url_nav(lang('tplallconfig'),$urlsarr,'mcommu',12);
	load_cache('mcommus');
	if(!submitcheck('bmcommu')){
		tabheader(lang('membercommu').lang('tpl_set'),'mcommu',"?entry=tplconfig&action=tplmcommu$param_suffix");
		foreach($mcommus as $k => $v){
			if($v['addable']){
				trbasic($v['cname'].'-'.lang('add_page_tpl'),"commusnew[$k][addtpl]",makeoption(array('' => lang('noset')) + mtplsarr('mcommu'),$v['addtpl']),'select');
			}
		}
		tabfooter('bmcommu');
		a_guide('tplmcommu');
	}else{
		foreach($mcommus as $k => $v){
			if($v['addable']){
				$addtpl = empty($commusnew[$k]['addtpl']) ? '' : $commusnew[$k]['addtpl'];
				$db->query("UPDATE {$tblprefix}mcommus SET addtpl='$addtpl' WHERE cuid='$k'");
			}
		}
		updatecache('mcommus');
		adminlog(lang('demomecomit'));
		amessage('itemmodifyfinish',M_REFERER);
	}
}elseif($action == 'tplmatype'){
	url_nav(lang('tplallconfig'),$urlsarr,'matype',12);
	load_cache('matypes');
	foreach($matypes as $k => $v)$matypes[$k] = read_cache('matype',$k);
	if(empty($matid))foreach($matypes as $k => $v){$matid = $k;break;}
	if(empty($matid))amessage('no_matype_tplset');
	$matype = read_cache('matype',$matid);
	if(!submitcheck('bmatype')){
		$arr = array();
		foreach($matypes as $k => $v) $arr[] = $matid == $k ? "<b>-$v[cname]-</b>" : "<a href=\"?entry=tplconfig&action=tplmatype&matid=$k$param_suffix\">$v[cname]</a>";
		echo tab_list($arr,6,0);

		tabheader("[$matype[cname]]".lang('tpl_set'),'matype',"?entry=tplconfig&action=tplmatype&matid=$matid$param_suffix");
		trbasic(lang('content_open_tpl'),'matypenew[arctpl]',makeoption(array('' => lang('noset')) + mtplsarr('marchive'),$matype['arctpl']),'select');
		trbasic(lang('content_limit_tpl'),'matypenew[parctpl]',makeoption(array('' => lang('noset')) + mtplsarr('marchive'),$matype['parctpl']),'select');
		trbasic(lang('search_tpl'),'matypenew[srhtpl]',makeoption(array('' => lang('noset')) + mtplsarr('marchive'),$matype['srhtpl']),'select');
		trbasic(lang('add_tpl'),'matypenew[addtpl]',makeoption(array('' => lang('noset')) + mtplsarr('marchive'),$matype['addtpl']),'select');
		tabfooter('bmatype');
		a_guide('tplmatype');
	}else{
		$matypenew['arctpl'] = empty($matypenew['arctpl']) ? '' : $matypenew['arctpl'];
		$matypenew['parctpl'] = empty($matypenew['parctpl']) ? '' : $matypenew['parctpl'];
		$matypenew['srhtpl'] = empty($matypenew['srhtpl']) ? '' : $matypenew['srhtpl'];
		$matypenew['addtpl'] = empty($matypenew['addtpl']) ? '' : $matypenew['addtpl'];
		$db->query("UPDATE {$tblprefix}matypes SET 
					arctpl='$matypenew[arctpl]',
					parctpl='$matypenew[parctpl]',
					srhtpl='$matypenew[srhtpl]',
					addtpl='$matypenew[addtpl]'
					WHERE matid='$matid'");
		updatecache('matypes');
		adminlog(lang('demomecomit'));
		amessage('itemmodifyfinish',M_REFERER);
	}
}
function saveconfig($cftype){
	global $mconfigs,$mconfigsnew,$db,$tblprefix;
	foreach($mconfigsnew as $k => $v){
		if(!isset($mconfigs[$k]) || $mconfigs[$k] != $v){
			$db->query("REPLACE INTO {$tblprefix}mconfigs (varname,value,cftype)
				VALUES ('$k','$v','$cftype')");
		}
	}
	updatecache('mconfigs');

}

?>