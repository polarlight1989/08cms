<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('bkconfig') || amessage('no_apermission');
load_cache('channels,cotypes,commus,mcommus,matypes,inurls,currencys,ucotypes');
$uclassarr = array(
	'archives' => lang('archive_admin'),//将模型与合辑类型只能同时指定一种
	'arcadd' => lang('issuearchive'),//将模型与合辑类型只能同时指定一种
	'arcupdate' => lang('archive').lang('update'),
	'comments' => lang('archive').lang('comment'),
	'offers' => lang('offer').lang('admin'),
	'replys' => lang('archive').lang('reply'),
	'reports' => lang('archive').lang('pickbug'),
	'answers' => lang('question').lang('answer0'),
	'farchives' => lang('freeinfo'),
	'farcadd' => lang('issue_freeinfo'),
	'members' => lang('member_admin'),
	'memadd' => lang('add_member'),
	'mcomments' => lang('membercomment'),
	'mreplys' => lang('memberreply'),
	'mreports' => lang('memberreport'),
	'marchives' => lang('marchive'),
	'mtrans' => lang('mtran'),
	'utrans' => lang('utran'),
	'custom' => lang('customphp'),
);
if(empty($uclass)) $uclass = '';
$uclass && $param_suffix .= "&uclass=$uclass";
$url_type = 'backarea';include 'urlsarr.inc.php';
if($action == 'aurlsedit'){
	url_nav(lang('backareaconfig'),$urlsarr,'url',10);
	$aurls = fetch_arr($uclass);
	if(!submitcheck('baurlsedit')){
		$arr = array();
		$uclassarr = array('' => lang('alltype')) + $uclassarr;
		foreach($uclassarr as $k => $v) $arr[] = $uclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=aurls&action=aurlsedit&uclass=$k\">$v</a>";
		echo tab_list($arr,10,0);

		tabheader(lang('aurl_admin')."&nbsp; &nbsp; <a href=\"?entry=aurls&action=aurladd$param_suffix\">>>".lang('add').'</a>','aurlsedit',"?entry=aurls&action=aurlsedit$param_suffix",'7');
		trcategory(array(lang('delete'),lang('enable'),lang('cname'),lang('type'),lang('remark'),lang('order'),lang('url'),lang('copy'),lang('edit')));
		foreach($aurls as $auid => $aurl){
			echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$auid]\" value=\"$auid\"".($aurl['issys'] ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"aurlsnew[$auid][available]\" value=\"1\"".($aurl['available'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w100\"><input type=\"text\" size=\"10\" name=\"aurlsnew[$auid][cname]\" value=\"$aurl[cname]\"></td>\n".
			"<td class=\"txtC w60\">".@$uclassarr[$aurl['uclass']]."</td>\n".
			"<td class=\"txtC w160\"><input type=\"text\" size=\"25\" name=\"aurlsnew[$auid][remark]\" value=\"$aurl[remark]\"></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"aurlsnew[$auid][vieworder]\" value=\"$aurl[vieworder]\"></td>\n".
			"<td class=\"txtL\">$aurl[url]</td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=aurls&action=aurlcopy&auid=$auid\" onclick=\"return floatwin('open_aurlsedit',this)\">".lang('copy')."</a></td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=aurls&action=aurldetail&auid=$auid\" onclick=\"return floatwin('open_aurlsedit',this)\">".lang('detail')."</a></td></tr>\n";
		}
		tabfooter('baurlsedit',lang('modify'));
		a_guide('aurlsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $auid){
				if($aurls[$auid]['issys']) continue;
				$db->query("DELETE FROM {$tblprefix}aurls WHERE auid='$auid'");
				unset($aurlsnew[$auid]);
			}
		}
		if(!empty($aurlsnew)){
			foreach($aurlsnew as $auid => $aurlnew){
				$aurlnew['cname'] = empty($aurlnew['cname']) ? $aurls[$auid]['cname'] : $aurlnew['cname'];
				$aurlnew['remark'] = trim(html2text($aurlnew['remark']));
				$aurlnew['available'] = empty($aurlnew['available']) ? 0 : 1;
				$aurlnew['vieworder'] = max(0,intval($aurlnew['vieworder']));
				$db->query("UPDATE {$tblprefix}aurls SET cname='$aurlnew[cname]',available='$aurlnew[available]',vieworder='$aurlnew[vieworder]',remark='$aurlnew[remark]' WHERE auid='$auid'");
			}
		}
		updatecache('aurls');	
		adminlog(lang('edit_citem_mlist'));
		amessage('itemmodifyfinish',M_REFERER);
	}
}elseif($action == 'aurladd'){
	url_nav(lang('backareaconfig'),$urlsarr,'uadd',10);
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(!submitcheck('baurladd')){
		tabheader(lang('aurl_add'),'aurladd',"?entry=aurls&action=aurladd$forwardstr",2,1,1);
		$submitstr = '';
		trbasic(lang('aurl_name'),'aurlnew[cname]');
		trbasic(lang('aurl_remark'),'aurlnew[remark]','','btext');
		trbasic(lang('aurl_type'),'aurlnew[uclass]',makeoption($uclassarr,$uclass),'select');
		tabfooter('baurladd');
		$submitstr .= makesubmitstr('aurlnew[cname]',1,0,4,18);
		check_submit_func($submitstr);
		a_guide('aurladd');
	}else{
		$aurlnew['cname'] = trim(strip_tags($aurlnew['cname']));
		empty($aurlnew['cname']) && amessage('aurlmissname',M_REFERER);
		$aurlnew['remark'] = trim(html2text($aurlnew['remark']));
		$db->query("INSERT INTO {$tblprefix}aurls SET
		 cname='$aurlnew[cname]',
		 remark='$aurlnew[remark]',
		 uclass='$aurlnew[uclass]'
		 ");
		$auid = $db->insert_id();
		updatecache('aurls');
		adminlog(lang('aurl_add'));
		amessage('aurladdfinish',"?entry=aurls&action=aurldetail&auid=$auid");
	}
}elseif($action == 'aurlcopy' && $auid){
	if(!($aurl = fetch_one($auid,1))) amessage('choosecpurl');
	if(!submitcheck('burlcopy')){
		tabheader(lang('url_copy'),'urlcopy',"?entry=aurls&action=aurlcopy&auid=$auid");
		trbasic(lang('soc_url_name'),'',$aurl['cname'],'');
		trbasic(lang('aurl_type'),'',$uclassarr[$aurl['uclass']],'');
		trbasic(lang('new_url_name'),'aurlnew[cname]');
		tabfooter('burlcopy');
	}else{
		$aurlnew['cname'] = empty($aurlnew['cname']) ? '' : trim(strip_tags($aurlnew['cname']));
		empty($aurlnew['cname']) && amessage('tagdatamiss',M_REFERER);
		$sqlstr = "cname='$aurlnew[cname]'";
		foreach($aurl as $k => $v) if(!in_array($k,array('auid','cname','issys','vieworder','url'))) $sqlstr .= ",$k='".addslashes($aurl[$k])."'";
		$db->query("INSERT INTO {$tblprefix}aurls SET $sqlstr");
		$auid = $db->insert_id();
		updatecache('aurls');
		adminlog(lang('copy_url_item'));
		amessage('urlcopyfinish',"?entry=aurls&action=aurldetail&auid=$auid");
	}
}elseif($action == 'aurldetail' && $auid){
	$aurl = fetch_one($auid);
	empty($aurl) && amessage('chooseaurl');
	if(!submitcheck('baurldetail')) {
		tabheader(lang('aurl_item_set'),'aurldetail',"?entry=aurls&action=aurldetail&auid=$auid");
		trbasic(lang('aurl_name'),'aurlnew[cname]',$aurl['cname']);
		trbasic(lang('aurl_remark'),'aurlnew[remark]',$aurl['remark'],'btext');
		trbasic(lang('aurl_type'),'',$uclassarr[$aurl['uclass']],'');
		include M_ROOT.'./include/aurls/'.$aurl['uclass'].'.php';
		tabfooter('baurldetail',lang('modify'));
		a_guide('aurldetail');
	}else{
		$aurlnew['cname'] = empty($aurlnew['cname']) ? $aurl['cname'] : $aurlnew['cname'];
		$submitmode = true;
		include M_ROOT.'./include/aurls/'.$aurl['uclass'].'.php';
		$aurlnew['tplname'] = empty($aurlnew['tplname']) ? '' : trim(strip_tags($aurlnew['tplname']));
		$aurlnew['onlyview'] = empty($aurlnew['onlyview']) ? 0 : 1;
		$aurlnew['remark'] = trim(html2text($aurlnew['remark']));
		$aurlnew['mtitle'] = empty($aurlnew['mtitle']) ? '' : trim(strip_tags($aurlnew['mtitle']));
		$aurlnew['guide'] = empty($aurlnew['guide']) ? '' : trim(html2text($aurlnew['guide']));
		$aurlnew['setting'] = !empty($aurlnew['setting']) ? addslashes(serialize($aurlnew['setting'])) : '';
		$db->query("UPDATE {$tblprefix}aurls SET 
					cname='$aurlnew[cname]',
					remark='$aurlnew[remark]',
					mtitle='$aurlnew[mtitle]',
					guide='$aurlnew[guide]',
					tplname='$aurlnew[tplname]',
					onlyview='$aurlnew[onlyview]',
					url='$aurlnew[url]',
					setting='$aurlnew[setting]'
					WHERE auid='$auid'");
		updatecache('aurls');
		adminlog(lang('detail_modify_citem'));
		amessage('itemmodifyfinish',axaction(6,"?entry=aurls&action=aurlsedit"));
	}

}
function fetch_arr($uclass = ''){
	global $db,$tblprefix;
	$aurls = array();
	$query = $db->query("SELECT * FROM {$tblprefix}aurls WHERE ".($uclass ? "uclass='$uclass' AND " : '')."isbk=0 ORDER BY vieworder,auid");
	while($aurl = $db->fetch_array($query)){
		if($aurl['setting'] && is_array($setting = unserialize($aurl['setting']))){$aurl['setting'] = $setting;}
		else{$aurl['setting'] = array();}
		$aurls[$aurl['auid']] = $aurl;
	}
	return $aurls;
}
function fetch_one($auid,$copy=0){
	global $db,$tblprefix;
	$aurl = $db->fetch_one("SELECT * FROM {$tblprefix}aurls WHERE auid='$auid'");
	if(!$copy){
		if($aurl['setting'] && is_array($setting = unserialize($aurl['setting']))){$aurl['setting'] = $setting;}
		else{$aurl['setting'] = array();}
	}
	return $aurl;
}
?>
