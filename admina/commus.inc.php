<?
(!defined('M_COM') || !defined('M_ADMIN')) && exit('No Permission');
aheader();
backallow('cfcommu') || amessage('no_apermission');
load_cache('grouptypes,currencys,rprojects,channels,permissions,ucotypes');
sys_cache('fieldwords');
include_once M_ROOT."./include/fields.fun.php";
$cclassarr = array(
	'praise' => lang('praise_no'),
	'score' => lang('score'),
	'report' => lang('pickbug'),
	'favorite' => lang('favorite'),
	'comment' => lang('comment'),
	'answer' => lang('answer'),
	'subscribe' => lang('subscribe'),
	'purchase' => lang('purchase'),
	'offer' => lang('offer'),
	'reply' => lang('reply'),
	'spread' => lang('spread'),
	);
$url_type = 'commu';include 'urlsarr.inc.php';

if($action == 'commusedit'){
	if(!$sid) url_nav(lang('docinterconfig'),$urlsarr,'commu');
	$commus = fetch_arr();
	cache_merge($commus,'commus',$sid);
	if(!submitcheck('bcommusedit')){
		tabheader(lang('citem_admin'),'commusedit',"?entry=commus&action=commusedit$param_suffix",'7');
		trcategory(array(lang('delete'),array(lang('enable'),'txtL'),array(lang('item_name'),'txtL'),array(lang('type'),'txtL'),array(lang('pick_url_style'),'txtL'),array(lang('copy'),'txtL'),array(lang('edit'),'txtL')));
		foreach($commus as $cuid => $commu){
			$cclassstr = $cclassarr[$commu['cclass']];
			echo "<tr class=\"txt\">".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"delete[$cuid]\" value=\"$cuid\"".($commu['issystem'] || $sid ? ' disabled' : '')."></td>\n".
			"<td class=\"txtC w30\"><input class=\"checkbox\" type=\"checkbox\" name=\"commusnew[$cuid][available]\" value=\"1\"".(empty($commu['available']) ? '' : ' checked')."></td>\n".
			"<td class=\"txtL w200\">".($sid ? "$commu[cname]" : "<input type=\"text\" size=\"30\" maxlength=\"30\" name=\"commusnew[$cuid][cname]\" value=\"$commu[cname]\">")."</td>\n".
			"<td class=\"txtL w60\">$cclassstr</td>\n".
			"<td class=\"txtL w60\"><a href=\"?entry=commus&action=commulink&cuid=$cuid$param_suffix\" onclick=\"return floatwin('open_commusedit',this)\">".lang('look')."</a></td>\n".
			"<td class=\"txtC w30\">".($commu['ch'] && !$sid ? "<a href=\"?entry=commus&action=commucopy&cuid=$cuid$param_suffix\" onclick=\"return floatwin('open_commusedit',this)\">".lang('copy')."</a>" : '-')."</td>\n".
			"<td class=\"txtC w30\">".(!$sid ? "<a href=\"?entry=commus&action=commudetail&cuid=$cuid$param_suffix\" onclick=\"return floatwin('open_commusedit',this)\">".lang('detail')."</a>" : '-')."</td></tr>\n";
		}
		tabfooter('bcommusedit',lang('modify'));
		a_guide('commusedit');
	}else{
		if(!$sid){
			if(!empty($delete)){
				foreach($delete as $cuid){
					if($commus[$cuid]['issystem']) continue;
					if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}channels WHERE cuid='$cuid'")) continue;
					if(in_array($commus[$cuid]['cclass'],array('comment','reply','offer','answer','purchase','report'))){
						if($db->result_one("SELECT COUNT(*) FROM {$tblprefix}".$commus[$cuid]['cclass']."s WHERE cuid='$cuid'")) continue;
					}
					$db->query("DELETE FROM {$tblprefix}commus WHERE cuid='$cuid'");
					unset($commusnew[$cuid]);
				}
			}
			if(!empty($commusnew)){
				foreach($commusnew as $cuid => $communew){
					$communew['cname'] = empty($communew['cname']) ? $commus[$cuid]['cname'] : $communew['cname'];
					$communew['available'] = empty($communew['available']) ? 0 : 1;
					$db->query("UPDATE {$tblprefix}commus SET cname='$communew[cname]',available='$communew[available]' WHERE cuid='$cuid'");
				}
			}
			updatecache('commus');	
		}else{
			$t_commus = empty($subsites[$sid]['commus']) ? array() : $subsites[$sid]['commus'];
			foreach($commus as $k => $v){
				$t_commus[$k]['available'] = empty($commusnew[$k]['available']) ? 0 : 1;
			}
			$t_commus = addslashes(serialize($t_commus));
			$db->query("UPDATE {$tblprefix}subsites SET commus='$t_commus' WHERE sid='$sid'");
			updatecache('subsites');
		}
		adminlog(lang('edit_citem_mlist'));
		amessage('itemmodifyfinish', "?entry=commus&action=commusedit$param_suffix");
	}
}elseif($action == 'commucopy' && $cuid){
	$forward = empty($forward) ? M_REFERER : $forward;
	$forwardstr = '&forward='.rawurlencode($forward);
	$commu = fetch_one($cuid);
	(empty($commu) || !$commu['ch']) && amessage('chooseitem', '?entry=commus&action=commusedit');
	if(!submitcheck('bcommucopy')){
		tabheader(lang('commu_item_copy'),'commucopy',"?entry=commus&action=commucopy&cuid=$cuid$forwardstr");
		trbasic(lang('soc_citem_name'),'',$commu['cname'],'');
		trbasic(lang('soc_citem_type'),'',$cclassarr[$commu['cclass']],'');
		trbasic(lang('new_citem_name'),'communew[cname]');
		tabfooter('bcommucopy');
		a_guide('commucopy');
	}else{
		$communew['cname'] = empty($communew['cname']) ? '' : trim(strip_tags($communew['cname']));
		empty($communew['cname']) && amessage('tagdatamiss',M_REFERER);
		$commu['cname'] = $communew['cname'];
		$commu['setting'] = serialize($commu['setting']);
		$commu['usetting'] = empty($commu['usetting']) ? '' : serialize($commu['usetting']);
		$sqlstr = '';
		foreach($commu as $k => $v) if(!in_array($k,array('cuid','issystem','uconfig',))) $sqlstr .= ($sqlstr ? ',' : '').$k."='".addslashes($v)."'";
		$db->query("INSERT INTO {$tblprefix}commus SET $sqlstr");
		updatecache('commus');
		adminlog(lang('copy_commu_item'));
		amessage('commuitemcopyfinish',axaction(6,$forward));
	}
}elseif($action == 'commudetail' && $cuid){
	if(!($commu = fetch_one($cuid))) amessage('chooseitem');
	if(!submitcheck('bcommudetail')) {
		tabheader(lang('commu_item_set').'-'.$commu['cname'],'commudetail',"?entry=commus&action=commudetail&cuid=$cuid$param_suffix");
		trbasic(lang('item_type'),'',$cclassarr[$commu['cclass']],'');
		if(!empty($commu['usetting'])){
			$str = '';
			foreach($commu['usetting'] as $k => $v) $str .= $k.'='.$v."\n";	
			$commu['usetting'] = $str;
			unset($str);
		}
		include M_ROOT.'./include/commus/'.$commu['cclass'].'.php';
		tabfooter('bcommudetail',lang('modify'));
		a_guide('commudetail');
	}else{
		$submitmode = true;
		@include M_ROOT.'./include/commus/'.$commu['cclass'].'.php';
		$communew['func'] = empty($communew['func']) ? '' : $communew['func'];
		$communew['setting'] = !empty($communew['setting']) ? addslashes(serialize($communew['setting'])) : '';
		$communew['allowance'] = empty($communew['allowance']) ? 0 : 1;
		$communew['ucadd'] = empty($communew['ucadd']) ? '' : trim($communew['ucadd']);
		$communew['ucvote'] = empty($communew['ucvote']) ? '' : trim($communew['ucvote']);
		$communew['uadetail'] = empty($communew['uadetail']) ? '' : trim($communew['uadetail']);
		$communew['umdetail'] = empty($communew['umdetail']) ? '' : trim($communew['umdetail']);
		if(!empty($communew['usetting'])){
			$communew['usetting'] = str_replace("\r","",$communew['usetting']);
			$temps = explode("\n",$communew['usetting']);
			$communew['usetting'] = array();
			foreach($temps as $v){
				$temparr = explode('=',str_replace(array("\r","\n"),'',$v));
				if(!isset($temparr[1]) || !($temparr[0] = trim($temparr[0]))) continue;
				$communew['usetting'][$temparr[0]] = trim($temparr[1]);
			}
			unset($temps,$temparr);
		}
		$communew['usetting'] = !empty($communew['usetting']) ? addslashes(serialize($communew['usetting'])) : '';
		$db->query("UPDATE {$tblprefix}commus SET 
					allowance='$communew[allowance]',
					setting='$communew[setting]',
					usetting='$communew[usetting]',
					ucadd='$communew[ucadd]',
					ucvote='$communew[ucvote]',
					uadetail='$communew[uadetail]',
					umdetail='$communew[umdetail]',
					func='$communew[func]'
					WHERE cuid='$cuid'");
		updatecache('commus');
		adminlog(lang('detail_modify_citem'));
		amessage('itemmodifyfinish',axaction(6,"?entry=commus&action=commusedit$param_suffix"));
	}

}elseif($action == 'commulink' && $cuid){
	if(!($commu = fetch_one($cuid))) amessage('chooseitem');
	tabheader(lang('pick_url_style').'-'.$commu['cname']);
	trbasic(lang('item_type'),'',$cclassarr[$commu['cclass']],'');
	@include M_ROOT.'./include/commus/'.$commu['cclass'].'.php';
	tabfooter();
}
function fetch_arr(){
	global $db,$tblprefix;
	$commus = array();
	$query = $db->query("SELECT * FROM {$tblprefix}commus WHERE isbk='0' ORDER BY issystem DESC,cuid ASC");
	while($commu = $db->fetch_array($query)){
		if($commu['setting'] && is_array($setting = unserialize($commu['setting']))){$commu['setting'] = $setting;}
		else{$commu['setting'] = array();}
		$commus[$commu['cuid']] = $commu;
	}
	return $commus;
}
function fetch_one($cuid){
	global $db,$tblprefix;
	$commu = $db->fetch_one("SELECT * FROM {$tblprefix}commus WHERE cuid='$cuid'");
	foreach(array('setting','usetting') as $var){
		if($commu[$var] && is_array($setting = unserialize($commu[$var]))){$commu[$var] = $setting;}
		else{$commu[$var] = array();}
	}
	return $commu;
}
?>
