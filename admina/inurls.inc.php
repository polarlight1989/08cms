<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('bkconfig') || amessage('no_apermission');
load_cache('channels,cotypes,commus,mcommus,matypes');
$uclassarr = array(
	'adetail' => lang('arcdetail'),
	'setalbum' => lang('asetalbum'),//将模型与合辑类型只能同时指定一种
	'inadd' => lang('maddinalbum'),
	'inadds' => lang('saddinalbum'),
	'content' => lang('abcontent'),
	'load' => lang('loadold'),
	'vol' => lang('vol_admin'),
	'comments' => lang('commentadmin'),
	'offers' => lang('offeradmin'),
	'replys' => lang('replyadmin'),
	'purchases' => lang('purchaseadmin'),
	'answers' => lang('answeradmin'),
	'reports' => lang('pickbugadmin'),
	'custom' => lang('customphp'),
);
if(empty($uclass)) $uclass = '';
$url_type = 'backarea';include 'urlsarr.inc.php';
if($action == 'inurlsedit'){
	url_nav(lang('backareaconfig'),$urlsarr,'inurl',10);
	$inurls = fetch_arr($uclass);
	if(!submitcheck('binurlsedit')){
		$arr = array();
		$uclassarr = array('' => lang('alltype')) + $uclassarr;
		foreach($uclassarr as $k => $v) $arr[] = $uclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=inurls&action=inurlsedit&uclass=$k\">$v</a>";
		echo tab_list($arr,10,0);

		tabheader(lang('inurl_admin').'&nbsp; &nbsp; <a href="?entry=inurls&action=inurladd">>>'.lang('add').'</a>','inurlsedit',"?entry=inurls&action=inurlsedit$param_suffix",'7');
		trcategory(array(lang('delete'),lang('enable'),lang('cname'),lang('type'),lang('remark'),lang('order'),lang('url'),lang('copy'),lang('edit')));
		foreach($inurls as $iuid => $inurl){
			echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$iuid]\" value=\"$iuid\"".($inurl['issys'] ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"inurlsnew[$iuid][available]\" value=\"1\"".($inurl['available'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w100\"><input type=\"text\" size=\"10\" name=\"inurlsnew[$iuid][cname]\" value=\"$inurl[cname]\"></td>\n".
			"<td class=\"txtC w60\">".@$uclassarr[$inurl['uclass']]."</td>\n".
			"<td class=\"txtC w160\"><input type=\"text\" size=\"25\" name=\"inurlsnew[$iuid][remark]\" value=\"$inurl[remark]\"></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"inurlsnew[$iuid][vieworder]\" value=\"$inurl[vieworder]\"></td>\n".
			"<td class=\"txtL\">$inurl[url]</td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=inurls&action=inurlcopy&iuid=$iuid\" onclick=\"return floatwin('open_inurlsedit',this)\">".lang('copy')."</a></td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=inurls&action=inurldetail&iuid=$iuid\" onclick=\"return floatwin('open_inurlsedit',this)\">".lang('detail')."</a></td></tr>\n";
		}
		tabfooter('binurlsedit',lang('modify'));
		a_guide('inurlsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $iuid){
				if($inurls[$iuid]['issys']) continue;
				$db->query("DELETE FROM {$tblprefix}inurls WHERE iuid='$iuid'");
				unset($inurlsnew[$iuid]);
			}
		}
		if(!empty($inurlsnew)){
			foreach($inurlsnew as $iuid => $inurlnew){
				$inurlnew['cname'] = empty($inurlnew['cname']) ? $inurls[$iuid]['cname'] : $inurlnew['cname'];
				$inurlnew['remark'] = trim(strip_tags($inurlnew['remark']));
				$inurlnew['available'] = empty($inurlnew['available']) ? 0 : 1;
				$inurlnew['vieworder'] = max(0,intval($inurlnew['vieworder']));
				$db->query("UPDATE {$tblprefix}inurls SET cname='$inurlnew[cname]',available='$inurlnew[available]',vieworder='$inurlnew[vieworder]',remark='$inurlnew[remark]' WHERE iuid='$iuid'");
			}
		}
		updatecache('inurls');	
		adminlog(lang('edit_citem_mlist'));
		amessage('itemmodifyfinish',M_REFERER);
	}
}elseif($action == 'inurladd'){
	url_nav(lang('backareaconfig'),$urlsarr,'inadd',10);
	if(!submitcheck('binurladd')){
		tabheader(lang('inurl_add'),'inurladd',"?entry=inurls&action=inurladd",2,1,1);
		$submitstr = '';
		trbasic(lang('inurl_name'),'inurlnew[cname]');
		trbasic(lang('inurl_remark'),'inurlnew[remark]','','btext');
		trbasic(lang('inurl_type'),'inurlnew[uclass]',makeoption($uclassarr),'select');
		tabfooter('binurladd');
		$submitstr .= makesubmitstr('inurlnew[cname]',1,0,4,18);
		check_submit_func($submitstr);
		a_guide('inurladd');
	}else{
		$inurlnew['cname'] = trim(strip_tags($inurlnew['cname']));
		empty($inurlnew['cname']) && amessage('inurlmissname',M_REFERER);
		$inurlnew['remark'] = trim(strip_tags($inurlnew['remark']));
		$db->query("INSERT INTO {$tblprefix}inurls SET
		 cname='$inurlnew[cname]',
		 remark='$inurlnew[remark]',
		 uclass='$inurlnew[uclass]'
		 ");
		$iuid = $db->insert_id();
		updatecache('inurls');
		adminlog(lang('inurl_add'));
		amessage('inurladdfinish',"?entry=inurls&action=inurldetail&iuid=$iuid");
	}
}elseif($action == 'inurlcopy' && $iuid){
	if(!($inurl = fetch_one($iuid,1))) amessage('choosecpurl');
	if(!submitcheck('burlcopy')){
		tabheader(lang('url_copy'),'urlcopy',"?entry=inurls&action=inurlcopy&iuid=$iuid");
		trbasic(lang('soc_url_name'),'',$inurl['cname'],'');
		trbasic(lang('inurl_type'),'',$uclassarr[$inurl['uclass']],'');
		trbasic(lang('new_url_name'),'inurlnew[cname]');
		tabfooter('burlcopy');
	}else{
		$inurlnew['cname'] = empty($inurlnew['cname']) ? '' : trim(strip_tags($inurlnew['cname']));
		empty($inurlnew['cname']) && amessage('tagdatamiss',M_REFERER);
		$sqlstr = "cname='$inurlnew[cname]'";
		foreach($inurl as $k => $v) if(!in_array($k,array('iuid','cname','issys','vieworder','url'))) $sqlstr .= ",$k='".addslashes($inurl[$k])."'";
		$db->query("INSERT INTO {$tblprefix}inurls SET $sqlstr");
		$iuid = $db->insert_id();
		updatecache('inurls');
		adminlog(lang('copy_url_item'));
		amessage('urlcopyfinish',"?entry=inurls&action=inurldetail&iuid=$iuid");
	}
}elseif($action == 'inurldetail' && $iuid){
	$inurl = fetch_one($iuid);
	empty($inurl) && amessage('chooseinurl');
	if(!submitcheck('binurldetail')) {
		tabheader(lang('inurl_item_set'),'inurldetail',"?entry=inurls&action=inurldetail&iuid=$iuid");
		trbasic(lang('inurl_name'),'inurlnew[cname]',$inurl['cname']);
		trbasic(lang('inurl_remark'),'inurlnew[remark]',$inurl['remark'],'btext');
		trbasic(lang('inurl_type'),'',$uclassarr[$inurl['uclass']],'');
		include M_ROOT.'./include/inurls/'.$inurl['uclass'].'.php';
		tabfooter('binurldetail',lang('modify'));
		a_guide('inurldetail');
	}else{
		$inurlnew['cname'] = empty($inurlnew['cname']) ? $inurl['cname'] : $inurlnew['cname'];
		$submitmode = true;
		include M_ROOT.'./include/inurls/'.$inurl['uclass'].'.php';
		$inurlnew['tplname'] = empty($inurlnew['tplname']) ? '' : trim(strip_tags($inurlnew['tplname']));
		$inurlnew['onlyview'] = empty($inurlnew['onlyview']) ? 0 : 1;
		$inurlnew['remark'] = trim(strip_tags($inurlnew['remark']));
		$inurlnew['mtitle'] = empty($inurlnew['mtitle']) ? '' : trim(strip_tags($inurlnew['mtitle']));
		$inurlnew['guide'] = empty($inurlnew['guide']) ? '' : trim(strip_tags($inurlnew['guide']));
		$inurlnew['setting'] = !empty($inurlnew['setting']) ? addslashes(serialize($inurlnew['setting'])) : '';
		$db->query("UPDATE {$tblprefix}inurls SET 
					cname='$inurlnew[cname]',
					remark='$inurlnew[remark]',
					mtitle='$inurlnew[mtitle]',
					guide='$inurlnew[guide]',
					tplname='$inurlnew[tplname]',
					onlyview='$inurlnew[onlyview]',
					url='$inurlnew[url]',
					setting='$inurlnew[setting]'
					WHERE iuid='$iuid'");
		updatecache('inurls');
		adminlog(lang('detail_modify_citem'));
		amessage('itemmodifyfinish',axaction(6,"?entry=inurls&action=inurlsedit"));
	}
}
function fetch_arr($uclass = ''){
	global $db,$tblprefix;
	$inurls = array();
	$query = $db->query("SELECT * FROM {$tblprefix}inurls WHERE ".($uclass ? "uclass='$uclass' AND " : '')."isbk=0 ORDER BY vieworder,iuid");
	while($inurl = $db->fetch_array($query)){
		if($inurl['setting'] && is_array($setting = unserialize($inurl['setting']))){$inurl['setting'] = $setting;}
		else{$inurl['setting'] = array();}
		$inurls[$inurl['iuid']] = $inurl;
	}
	return $inurls;
}
function fetch_one($iuid,$copy=0){
	global $db,$tblprefix;
	$inurl = $db->fetch_one("SELECT * FROM {$tblprefix}inurls WHERE iuid='$iuid'");
	if(!$copy){
		if($inurl['setting'] && is_array($setting = unserialize($inurl['setting']))){$inurl['setting'] = $setting;}
		else{$inurl['setting'] = array();}
	}
	return $inurl;
}
?>
