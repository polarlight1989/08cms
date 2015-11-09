<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('mcconfig') || amessage('no_apermission');
load_cache('channels,cotypes,commus,mcommus,matypes');
$uclassarr = array(
	'adetail' => lang('arcdetail'),
	'setalbum' => lang('asetalbum'),//归辑
	'content' => lang('abcontent'),//合辑内的内容管理
	'load' => lang('loadold'),//加载已有内容进自已的合辑
	'replys' => lang('replyadmin'),//收到的回复
	'answers' => lang('answeradmin'),//收到的答案
	'inadd' => lang('maddinalbum'),//合辑内不指定类型的添加
	'inadds' => lang('saddinalbum'),//合辑内指定类型的添加
	'odetail' => lang('offerdetail'),
	'rdetail' => lang('replydetail'),
	'cdetail' => lang('commentdetail'),
	'custom' => lang('customphp'),
);
if(empty($uclass)) $uclass = '';
$url_type = 'mcenter';include 'urlsarr.inc.php';
if($action == 'inmurlsedit'){
	url_nav(lang('mcenterconfig'),$urlsarr,'in',10);
	$inmurls = fetch_arr($uclass);
	if(!submitcheck('binmurlsedit')){
		$arr = array();
		$uclassarr = array('' => lang('alltype')) + $uclassarr;
		foreach($uclassarr as $k => $v) $arr[] = $uclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=inmurls&action=inmurlsedit&uclass=$k\">$v</a>";
		echo tab_list($arr,10,0);

		tabheader(lang('inmurl_admin')."&nbsp; <a href=\"?entry=inmurls&action=inmurladd\">>>".lang('add')."</a>",'inmurlsedit',"?entry=inmurls&action=inmurlsedit$param_suffix",'7');
		trcategory(array(lang('delete'),lang('enable'),lang('cname'),lang('type'),lang('remark'),lang('order'),lang('url'),lang('copy'),lang('edit')));
		foreach($inmurls as $imuid => $inmurl){
			echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$imuid]\" value=\"$imuid\"".($inmurl['issys'] ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"inmurlsnew[$imuid][available]\" value=\"1\"".($inmurl['available'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w100\"><input type=\"text\" size=\"10\" name=\"inmurlsnew[$imuid][cname]\" value=\"$inmurl[cname]\"></td>\n".
			"<td class=\"txtC w60\">".@$uclassarr[$inmurl['uclass']]."</td>\n".
			"<td class=\"txtC w160\"><input type=\"text\" size=\"25\" name=\"inmurlsnew[$imuid][remark]\" value=\"$inmurl[remark]\"></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"inmurlsnew[$imuid][vieworder]\" value=\"$inmurl[vieworder]\"></td>\n".
			"<td class=\"txtL\">$inmurl[url]</td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=inmurls&action=inmurlcopy&imuid=$imuid\" onclick=\"return floatwin('open_inmurlsedit',this)\">".lang('copy')."</a></td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=inmurls&action=inmurldetail&imuid=$imuid\" onclick=\"return floatwin('open_inmurlsedit',this)\">".lang('detail')."</a></td></tr>\n";
		}
		tabfooter('binmurlsedit',lang('modify'));
		a_guide('inmurlsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $imuid){
				if($inmurls[$imuid]['issys']) continue;
				$db->query("DELETE FROM {$tblprefix}inmurls WHERE imuid='$imuid'");
				unset($inmurlsnew[$imuid]);
			}
		}
		if(!empty($inmurlsnew)){
			foreach($inmurlsnew as $imuid => $inmurlnew){
				$inmurlnew['cname'] = empty($inmurlnew['cname']) ? $inmurls[$imuid]['cname'] : $inmurlnew['cname'];
				$inmurlnew['remark'] = trim(strip_tags($inmurlnew['remark']));
				$inmurlnew['available'] = empty($inmurlnew['available']) ? 0 : 1;
				$inmurlnew['vieworder'] = max(0,intval($inmurlnew['vieworder']));
				$db->query("UPDATE {$tblprefix}inmurls SET cname='$inmurlnew[cname]',available='$inmurlnew[available]',vieworder='$inmurlnew[vieworder]',remark='$inmurlnew[remark]' WHERE imuid='$imuid'");
			}
		}
		updatecache('inmurls');	
		adminlog(lang('edit_citem_mlist'));
		amessage('itemmodifyfinish',M_REFERER);
	}
}elseif($action == 'inmurladd'){
	if(!submitcheck('binmurladd')){
		tabheader(lang('inmurl_add'),'inmurladd',"?entry=inmurls&action=inmurladd",2,1,1);
		$submitstr = '';
		trbasic(lang('inmurl_name'),'inmurlnew[cname]');
		trbasic(lang('inmurl_remark'),'inmurlnew[remark]','','btext');
		trbasic(lang('inmurl_type'),'inmurlnew[uclass]',makeoption($uclassarr),'select');
		tabfooter('binmurladd');
		$submitstr .= makesubmitstr('inmurlnew[cname]',1,0,4,18);
		check_submit_func($submitstr);
		a_guide('inmurladd');
	}else{
		$inmurlnew['cname'] = trim(strip_tags($inmurlnew['cname']));
		empty($inmurlnew['cname']) && amessage('inmurlmissname',M_REFERER);
		$inmurlnew['remark'] = trim(strip_tags($inmurlnew['remark']));
		$db->query("INSERT INTO {$tblprefix}inmurls SET
		 cname='$inmurlnew[cname]',
		 remark='$inmurlnew[remark]',
		 uclass='$inmurlnew[uclass]'
		 ");
		$imuid = $db->insert_id();
		updatecache('inmurls');
		adminlog(lang('inmurl_add'));
		amessage('inmurladdfinish',"?entry=inmurls&action=inmurldetail&imuid=$imuid");
	}
}elseif($action == 'inmurlcopy' && $imuid){
	if(!($inmurl = fetch_one($imuid,1))) amessage('choosecpurl');
	if(!submitcheck('burlcopy')){
		tabheader(lang('url_copy'),'urlcopy',"?entry=inmurls&action=inmurlcopy&imuid=$imuid");
		trbasic(lang('soc_url_name'),'',$inmurl['cname'],'');
		trbasic(lang('inmurl_type'),'',$uclassarr[$inmurl['uclass']],'');
		trbasic(lang('new_url_name'),'inmurlnew[cname]');
		tabfooter('burlcopy');
	}else{
		$inmurlnew['cname'] = empty($inmurlnew['cname']) ? '' : trim(strip_tags($inmurlnew['cname']));
		empty($inmurlnew['cname']) && amessage('tagdatamiss',M_REFERER);
		$sqlstr = "cname='$inmurlnew[cname]'";
		foreach($inmurl as $k => $v) if(!in_array($k,array('imuid','cname','issys','vieworder','url'))) $sqlstr .= ",$k='".addslashes($inmurl[$k])."'";
		$db->query("INSERT INTO {$tblprefix}inmurls SET $sqlstr");
		$imuid = $db->insert_id();
		updatecache('inmurls');
		adminlog(lang('copy_url_item'));
		amessage('urlcopyfinish',"?entry=inmurls&action=inmurldetail&imuid=$imuid");
	}
}elseif($action == 'inmurldetail' && $imuid){
	$inmurl = fetch_one($imuid);
	empty($inmurl) && amessage('chooseinmurl');
	if(!submitcheck('binmurldetail')) {
		tabheader(lang('inmurl_item_set'),'inmurldetail',"?entry=inmurls&action=inmurldetail&imuid=$imuid");
		trbasic(lang('inmurl_name'),'inmurlnew[cname]',$inmurl['cname']);
		trbasic(lang('inmurl_remark'),'inmurlnew[remark]',$inmurl['remark'],'btext');
		trbasic(lang('inmurl_type'),'',$uclassarr[$inmurl['uclass']],'');
		include M_ROOT.'./include/inmurls/'.$inmurl['uclass'].'.php';
		tabfooter('binmurldetail',lang('modify'));
		a_guide('inmurldetail');
	}else{
		$inmurlnew['cname'] = empty($inmurlnew['cname']) ? $inmurl['cname'] : $inmurlnew['cname'];
		$submitmode = true;
		include M_ROOT.'./include/inmurls/'.$inmurl['uclass'].'.php';
		$inmurlnew['tplname'] = empty($inmurlnew['tplname']) ? '' : trim(strip_tags($inmurlnew['tplname']));
		$inmurlnew['onlyview'] = empty($inmurlnew['onlyview']) ? 0 : 1;
		$inmurlnew['remark'] = trim(strip_tags($inmurlnew['remark']));
		$inmurlnew['mtitle'] = empty($inmurlnew['mtitle']) ? '' : trim(strip_tags($inmurlnew['mtitle']));
		$inmurlnew['guide'] = empty($inmurlnew['guide']) ? '' : trim(strip_tags($inmurlnew['guide']));
		$inmurlnew['setting'] = !empty($inmurlnew['setting']) ? addslashes(serialize($inmurlnew['setting'])) : '';
		$db->query("UPDATE {$tblprefix}inmurls SET 
					cname='$inmurlnew[cname]',
					remark='$inmurlnew[remark]',
					tplname='$inmurlnew[tplname]',
					onlyview='$inmurlnew[onlyview]',
					mtitle='$inmurlnew[mtitle]',
					guide='$inmurlnew[guide]',
					url='$inmurlnew[url]',
					setting='$inmurlnew[setting]'
					WHERE imuid='$imuid'");
		updatecache('inmurls');
		adminlog(lang('detail_modify_citem'));
		amessage('itemmodifyfinish',axaction(6,"?entry=inmurls&action=inmurlsedit"));
	}

}
function fetch_arr($uclass = ''){
	global $db,$tblprefix;
	$inmurls = array();
	$query = $db->query("SELECT * FROM {$tblprefix}inmurls WHERE ".($uclass ? "uclass='$uclass' AND " : '')."isbk=0 ORDER BY vieworder,imuid");
	while($inmurl = $db->fetch_array($query)){
		if($inmurl['setting'] && is_array($setting = unserialize($inmurl['setting']))){$inmurl['setting'] = $setting;}
		else{$inmurl['setting'] = array();}
		$inmurls[$inmurl['imuid']] = $inmurl;
	}
	return $inmurls;
}
function fetch_one($imuid,$copy=0){
	global $db,$tblprefix;
	$inmurl = $db->fetch_one("SELECT * FROM {$tblprefix}inmurls WHERE imuid='$imuid'");
	if(!$copy){
		if($inmurl['setting'] && is_array($setting = unserialize($inmurl['setting']))){$inmurl['setting'] = $setting;}
		else{$inmurl['setting'] = array();}
	}
	return $inmurl;
}
?>
