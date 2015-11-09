<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('mcconfig') || amessage('no_apermission');
load_cache('acatalogs,channels,cotypes,commus,mcommus,matypes,inmurls,ucotypes');
$uclassarr = array(
	'archives' => lang('archive_admin'),
	'albums' => lang('openalbum'),
	'comments' => lang('commentadmin'),//收到的评论管理
	'replys' => lang('replyadmin'),//收到的回复管理
	'areplys' => lang('areplyadmin'),//收到的回复管理
	'answers' => lang('answeradmin'),//问题管理
	'offers' => lang('offeradmin'),
	'arcadd' => lang('issuearchive'),
	'mdetail' => lang('memberdetail'),
	'farchives' => lang('freeinfo'),
	'farcadd' => lang('issue_freeinfo'),
	'mcomments' => lang('membercomment'),//收到的评论管理
	'mreplys' => lang('memberreply'),//收到的回复管理
	'amreplys' => lang('memberareply'),//收到的回复管理
	'custom' => lang('customphp'),
);
if(empty($uclass)) $uclass = '';
$uclass && $param_suffix .= "&uclass=$uclass";
$url_type = 'mcenter';include 'urlsarr.inc.php';
if($action == 'murlsedit'){
	url_nav(lang('mcenterconfig'),$urlsarr,'mu',10);
	$murls = fetch_arr($uclass);
	if(!submitcheck('bmurlsedit')){
		$arr = array();
		$uclassarr = array('' => lang('alltype')) + $uclassarr;
		foreach($uclassarr as $k => $v) $arr[] = $uclass == $k ? "<b>-$v-</b>" : "<a href=\"?entry=murls&action=murlsedit&uclass=$k\">$v</a>";
		echo tab_list($arr,10,0);

		tabheader(lang('murl_admin')."&nbsp; <a href=\"?entry=murls&action=murladd$param_suffix\">>>".lang('add')."</a>",'murlsedit',"?entry=murls&action=murlsedit$param_suffix",'7');
		trcategory(array(lang('delete'),lang('enable'),lang('cname'),lang('type'),lang('remark'),lang('order'),lang('url'),lang('copy'),lang('edit')));
		foreach($murls as $muid => $murl){
			echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$muid]\" value=\"$muid\"".($murl['issys'] ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"murlsnew[$muid][available]\" value=\"1\"".($murl['available'] ? ' checked' : '')."></td>\n".
			"<td class=\"txtC w100\"><input type=\"text\" size=\"10\" name=\"murlsnew[$muid][cname]\" value=\"$murl[cname]\"></td>\n".
			"<td class=\"txtC w80\">".@$uclassarr[$murl['uclass']]."</td>\n".
			"<td class=\"txtC w160\"><input type=\"text\" size=\"25\" name=\"murlsnew[$muid][remark]\" value=\"$murl[remark]\"></td>\n".
			"<td class=\"txtC w60\"><input type=\"text\" size=\"4\" name=\"murlsnew[$muid][vieworder]\" value=\"$murl[vieworder]\"></td>\n".
			"<td class=\"txtL\">$murl[url]</td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=murls&action=murlcopy&muid=$muid\" onclick=\"return floatwin('open_murlsedit',this)\">".lang('copy')."</a></td>\n".
			"<td class=\"txtC w30\"><a href=\"?entry=murls&action=murldetail&muid=$muid\" onclick=\"return floatwin('open_murlsedit',this)\">".lang('detail')."</a></td></tr>\n";
		}
		tabfooter('bmurlsedit',lang('modify'));
		a_guide('murlsedit');
	}else{
		if(!empty($delete)){
			foreach($delete as $muid){
				if($murls[$muid]['issys']) continue;
				$db->query("DELETE FROM {$tblprefix}murls WHERE muid='$muid'");
				unset($murlsnew[$muid]);
			}
		}
		if(!empty($murlsnew)){
			foreach($murlsnew as $muid => $murlnew){
				$murlnew['cname'] = empty($murlnew['cname']) ? $murls[$muid]['cname'] : $murlnew['cname'];
				$murlnew['remark'] = trim(strip_tags($murlnew['remark']));
				$murlnew['available'] = empty($murlnew['available']) ? 0 : 1;
				$murlnew['vieworder'] = max(0,intval($murlnew['vieworder']));
				$db->query("UPDATE {$tblprefix}murls SET cname='$murlnew[cname]',available='$murlnew[available]',vieworder='$murlnew[vieworder]',remark='$murlnew[remark]' WHERE muid='$muid'");
			}
		}
		updatecache('murls');	
		adminlog(lang('edit_citem_mlist'));
		amessage('itemmodifyfinish',M_REFERER);
	}
}elseif($action == 'murladd'){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	if(!submitcheck('bmurladd')){
		tabheader(lang('murl_add'),'murladd',"?entry=murls&action=murladd$forwardstr",2,1,1);
		$submitstr = '';
		trbasic(lang('murl_name'),'murlnew[cname]');
		trbasic(lang('murl_remark'),'murlnew[remark]','','btext');
		trbasic(lang('murl_type'),'murlnew[uclass]',makeoption($uclassarr, $uclass),'select');
		tabfooter('bmurladd');
		$submitstr .= makesubmitstr('murlnew[cname]',1,0,4,18);
		check_submit_func($submitstr);
		a_guide('murladd');
	}else{
		$murlnew['cname'] = trim(strip_tags($murlnew['cname']));
		empty($murlnew['cname']) && amessage('murlmissname',M_REFERER);
		$murlnew['remark'] = trim(strip_tags($murlnew['remark']));
		$db->query("INSERT INTO {$tblprefix}murls SET
		 cname='$murlnew[cname]',
		 remark='$murlnew[remark]',
		 uclass='$murlnew[uclass]'
		 ");
		$muid = $db->insert_id();
		updatecache('murls');
		adminlog(lang('murl_add'));
		amessage('murladdfinish',"?entry=murls&action=murldetail&muid=$muid");
	}
}elseif($action == 'murlcopy' && $muid){
	if(!($murl = fetch_one($muid,1))) amessage('choosecpurl');
	if(!submitcheck('burlcopy')){
		tabheader(lang('url_copy'),'urlcopy',"?entry=murls&action=murlcopy&muid=$muid");
		trbasic(lang('soc_url_name'),'',$murl['cname'],'');
		trbasic(lang('murl_type'),'',$uclassarr[$murl['uclass']],'');
		trbasic(lang('new_url_name'),'murlnew[cname]');
		tabfooter('burlcopy');
	}else{
		$murlnew['cname'] = empty($murlnew['cname']) ? '' : trim(strip_tags($murlnew['cname']));
		empty($murlnew['cname']) && amessage('tagdatamiss',M_REFERER);
		$sqlstr = "cname='$murlnew[cname]'";
		foreach($murl as $k => $v) if(!in_array($k,array('muid','cname','issys','vieworder','url'))) $sqlstr .= ",$k='".addslashes($murl[$k])."'";
		$db->query("INSERT INTO {$tblprefix}murls SET $sqlstr");
		$muid = $db->insert_id();
		updatecache('murls');
		adminlog(lang('copy_url_item'));
		amessage('urlcopyfinish',"?entry=murls&action=murldetail&muid=$muid");
	}
}elseif($action == 'murldetail' && $muid){
	$murl = fetch_one($muid);
	empty($murl) && amessage('choosemurl');
	if(!submitcheck('bmurldetail')) {
		tabheader(lang('murl_item_set'),'murldetail',"?entry=murls&action=murldetail&muid=$muid");
		trbasic(lang('murl_name'),'murlnew[cname]',$murl['cname']);
		trbasic(lang('murl_remark'),'murlnew[remark]',$murl['remark'],'btext');
		trbasic(lang('murl_type'),'',$uclassarr[$murl['uclass']],'');
		include M_ROOT.'./include/murls/'.$murl['uclass'].'.php';
		tabfooter('bmurldetail',lang('modify'));
		a_guide('murldetail');
	}else{
		$murlnew['cname'] = empty($murlnew['cname']) ? $murl['cname'] : $murlnew['cname'];
		$submitmode = true;
		include M_ROOT.'./include/murls/'.$murl['uclass'].'.php';
		$murlnew['tplname'] = empty($murlnew['tplname']) ? '' : trim(strip_tags($murlnew['tplname']));
		$murlnew['onlyview'] = empty($murlnew['onlyview']) ? 0 : 1;
		$murlnew['mtitle'] = empty($murlnew['mtitle']) ? '' : trim($murlnew['mtitle']);
		$murlnew['otitle'] = empty($murlnew['otitle']) ? '' : trim($murlnew['otitle']);
		$murlnew['guide'] = empty($murlnew['guide']) ? '' : trim($murlnew['guide']);
		$murlnew['remark'] = trim(strip_tags($murlnew['remark']));
		$murlnew['setting'] = !empty($murlnew['setting']) ? addslashes(serialize($murlnew['setting'])) : '';
		$db->query("UPDATE {$tblprefix}murls SET 
					cname='$murlnew[cname]',
					remark='$murlnew[remark]',
					tplname='$murlnew[tplname]',
					onlyview='$murlnew[onlyview]',
					mtitle='$murlnew[mtitle]',
					otitle='$murlnew[otitle]',
					guide='$murlnew[guide]',
					url='$murlnew[url]',
					setting='$murlnew[setting]'
					WHERE muid='$muid'");
		updatecache('murls');
		adminlog(lang('detail_modify_citem'));
		amessage('itemmodifyfinish',axaction(6,"?entry=murls&action=murlsedit"));
	}

}
function fetch_arr($uclass = ''){
	global $db,$tblprefix;
	$murls = array();
	$query = $db->query("SELECT * FROM {$tblprefix}murls WHERE ".($uclass ? "uclass='$uclass' AND " : '')."isbk=0 ORDER BY vieworder,muid");
	while($murl = $db->fetch_array($query)){
		if($murl['setting'] && is_array($setting = unserialize($murl['setting']))){$murl['setting'] = $setting;}
		else{$murl['setting'] = array();}
		$murls[$murl['muid']] = $murl;
	}
	return $murls;
}
function fetch_one($muid,$copy=0){
	global $db,$tblprefix;
	$murl = $db->fetch_one("SELECT * FROM {$tblprefix}murls WHERE muid='$muid'");
	if(!$copy){
			if($murl['setting'] && is_array($setting = unserialize($murl['setting']))){$murl['setting'] = $setting;}
			else{$murl['setting'] = array();}
	}
	return $murl;
}
?>
